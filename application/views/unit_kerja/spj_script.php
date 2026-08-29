<script>
$(document).ready(function() {
    // disbeld tombol #simpan
    $('#simpan').prop('disabled', true);
    
    // Variabel untuk timeout debounce
    var saveTimeout = null;
    var currentInput = null;
    
    // Fungsi untuk menampilkan notifikasi floating
    function showFloatingNotification(element, message, isError = false) {
        // Hapus notifikasi sebelumnya
        $('.floating-notification').remove();
        
        var pos = $(element).offset();
        var width = $(element).outerWidth();
        
        var notification = $('<div class="floating-notification" style="position: fixed; padding: 8px 15px; background: ' + 
            (isError ? '#e74c3c' : '#2ecc71') + 
            '; color: white; border-radius: 4px; font-size: 13px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.2); pointer-events: none;">' + 
            message + '</div>');
        
        notification.css({
            top: (pos.top - 40) + 'px',
            left: (pos.left + (width/2) - (notification.outerWidth()/2)) + 'px'
        });
        
        $('body').append(notification);
        
        // Auto hide after 2 seconds
        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 2000);
    }
    
    // Fungsi untuk update data
    function updateRincianData(element, field, value) {
        var id = $(element).closest('tr').find('td:first').attr('id');
        var idPengajuanRincian = $('#btn-add-row').data('id');
        
        // Jika tidak ada ID (baris baru), skip
        if (!id || id === '') {
            return;
        }
        
        // Tampilkan notifikasi saving
        showFloatingNotification(element, 'Saving...');
        
        // Hapus formatting number untuk field tertentu
        if (field === 'harga' || field === 'bruto' || field === 'netto' || field === 'pph') {
            value = value.replace(/\./g, '').replace(/,/g, '');
        }
        
        $.ajax({
            url: '<?=base_url()?>SPJ/update_rincian',
            type: 'POST',
            data: {
                id: id,
                field: field,
                value: value,
                id_pengajuan_rincian: idPengajuanRincian
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showFloatingNotification(element, '✓ Saved');
                    console.log(response);
                    
                    // Update total jika ada perubahan
                    if (field === 'bruto' || field === 'netto' || field === 'harga' || 
                        field === 'volume' || field === 'persen_pajak' || field === 'pph') {
                        updateTotals();
                    }
                } else {
                    showFloatingNotification(element, 'Error: ' + response.message, true);
                }
            },
            error: function() {
                showFloatingNotification(element, 'Error saving data', true);
            }
        });
    }
    
    // Event listener untuk contenteditable
    $('#tabel-rincian tbody').on('blur', '[contenteditable="true"]', function() {
        var field = $(this).attr('class').split(' ')[0]; // ambil class pertama
        var value = $(this).text().trim();
        
        // Validasi untuk field number
        if (field === 'volume' || field === 'harga' || field === 'bruto' || 
            field === 'pph' || field === 'persen_pajak' || field === 'netto') {
            if (value === '' || isNaN(value.replace(/\./g, '').replace(/,/g, ''))) {
                showFloatingNotification(this, 'Invalid number format', true);
                return;
            }
        }
        
        // Cek apakah nilai berubah
        var oldValue = $(this).data('old-value');
        if (oldValue === value) {
            return;
        }
        
        // Simpan nilai baru
        $(this).data('old-value', value);
        
        // Hapus timeout sebelumnya
        if (saveTimeout) {
            clearTimeout(saveTimeout);
        }
        
        // Debounce save
        var element = this;
        saveTimeout = setTimeout(function() {
            updateRincianData(element, field, value);
        }, 500);
    });
    
    // Simpan nilai awal saat fokus
    $('#tabel-rincian tbody').on('focus', '[contenteditable="true"]', function() {
        $(this).data('old-value', $(this).text().trim());
    });
    
    // Fungsi update total
    function updateTotals() {
        var totalBruto = 0;
        var totalNetto = 0;
        
        $('#tabel-rincian tbody tr').each(function() {
            var bruto = $(this).find('.bruto').text().trim().replace(/\./g, '').replace(/,/g, '');
            var netto = $(this).find('.netto').text().trim().replace(/\./g, '').replace(/,/g, '');
            
            if (bruto && !isNaN(bruto)) totalBruto += parseFloat(bruto);
            if (netto && !isNaN(netto)) totalNetto += parseFloat(netto);
        });
        
        $('.total-bruto').text(formatNumber(totalBruto));
        $('.total-netto').text(formatNumber(totalNetto));
    }
    
    // Fungsi format number dengan pemisah ribuan menggunakan koma
    function formatNumber(num) {
        if (num === undefined || num === null || isNaN(num)) {
            return '0';
        }
        
        var isNegative = num < 0;
        num = Math.abs(num);
        
        // Format dengan pemisah ribuan menggunakan koma
        var parts = num.toString().split('.');
        var integerPart = parts[0];
        var decimalPart = parts[1] || '';
        
        // Tambahkan pemisah ribuan dengan koma
        var formatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        if (decimalPart) {
            formatted += '.' + decimalPart;
        }
        
        return (isNegative ? '-' : '') + formatted;
    }

    // Fungsi untuk parse number dari format dengan koma
    function parseNumberFromFormat(value) {
        if (!value || value === '') return 0;
        
        // Hapus semua karakter selain digit, titik, dan minus
        var cleaned = value.replace(/[^\d.-]/g, '');
        var num = parseFloat(cleaned);
        
        return isNaN(num) ? 0 : num;
    }
    
    // Event untuk tombol Tambah Rincian
    $('#btn-add-row').click(function() {
        // enable tombol #simpan
        $('#simpan').prop('disabled', false);
    
        var idPengajuanRincian = $(this).data('id');
        var komitmen = $('#komitmen').val();
        
        // Tampilkan form input
        $('#input-data-rincian').show();
        
        // Isi data default
        $('#input-tanggal').val('');
        $('#input-keterangan').val('');
        $('#input-volume').val('0');
        $('#input-ket_volume').val('');
        $('#input-harga').val('0');
        $('#input-bruto').val('0');
        $('#input-persen_pajak').val('0');
        $('#input-pph').val('0');
        $('#input-netto').val('0');
        
        // Scroll ke form
        $('html, body').animate({
            scrollTop: $('#input-data-rincian').offset().top - 100
        }, 500);
        
        // disabled tombol #btn-add-row'
        $("#btn-add-row").prop("disabled", "true");
    });
    
    /*$('#input-volume, #input-harga, #input-persen_pajak').on('input', function() {
        hitungOtomatis();
    });*/
    
    function hitungOtomatis() {
        var volume = parseFloat($('#input-volume').val()) || 0;
        var harga = parseFloat($('#input-harga').val().replace(/\,/g, '')) || 0;
        var persenPajak = parseFloat($('#input-persen_pajak').val()) || 0;
       
        var bruto = volume * harga;
        var pph = bruto * (persenPajak / 100);
        var netto = bruto - pph;
        
        
        $('#input-bruto').val(formatNumber(bruto));
        $('#input-pph').val(formatNumber(pph));
        $('#input-netto').val(formatNumber(netto));
        console.log(bruto);console.log(harga);console.log(netto);
    }
    
    // Event untuk tombol Simpan
    $('#simpan').click(function() {
        
        
        
        var idPengajuanRincian = $(this).data('id');
        var data = {
            id_pengajuan_rincian: idPengajuanRincian,
            tanggal: $('#input-tanggal').val(),
            keterangan: $('#input-keterangan').val(),
            volume: $('#input-volume').val(),
            ket_volume: $('#input-ket_volume').val(),
            harga: $('#input-harga').val().replace(/\,/g, ''),
            bruto: $('#input-bruto').val().replace(/\,/g, ''),
            persen_pajak: $('#input-persen_pajak').val(),
            pph: $('#input-pph').val().replace(/\,/g, ''),
            netto: $('#input-netto').val().replace(/\,/g, ''),
            kegiatan: $('#kegiatan').text().trim(),
            jadwal: $('#jadwal').text().trim(),
            komitmen: $('#komitmen').val()
        };
        //console.log(data); return false;
        // Validasi
        if (!data.tanggal || !data.keterangan) {
            alert('Tanggal dan Keterangan harus diisi!');
            return;
        }
        
        $.ajax({
            url: '<?=base_url()?>SPJ/simpan_rincian',
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('#simpan').html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                $('#simpan').prop('disabled', true);
                $('#tabel-rincian tbody').html('loading...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Reload page atau tambahkan baris baru
                    // sembunyikan #input-data-rincian
				    $('#input-data-rincian').hide();   ;
                } else {
                    alert('Error: ' + response.message);
                    console.log(response);
                }
            },
            error: function() {
                alert('Error saving data');
            },
            complete: function() {
                $('#simpan').html('<i class="fa fa-floppy-o"></i> Simpan');
                $('#simpan').prop('disabled', true);
                $("#btn-add-row").prop("disabled", false);
                
                // kosongkan input value
                $('#input-tanggal').val('');
                $('#input-keterangan').val('');
                $('#input-volume').val('0');
                $('#input-ket_volume').val('');
                $('#input-harga').val('0');
                $('#input-bruto').val('0');
                $('#input-persen_pajak').val('0'),
                $('#input-pph').val('0');
                $('#input-netto').val('0');
                
                    // refresh tabel rincian menggunakan ajax
                    $.ajax({
                        url: '<?=base_url()?>SPJ/spj_rincian_tbody',
                        type: 'POST',
                        dataType: 'html',
                        data: {
                            id: idPengajuanRincian
                        },
                        success: function(response) {
                            console.log(response);
                            $('#tabel-rincian tbody').html(response);
                        }
                    });
            }
        });
    });
    
    // Event untuk tombol hapus
    $('#tabel-rincian').on('click', '.btn-remove-row-db', function() {
        var id = $(this).attr('id');
        var idPengajuanRincian = $('#btn-add-row').data('id');
        $('#tabel-rincian tbody').html('loading...');
        if (confirm('Are you sure you want to delete this row?')) {
            $.ajax({
                url: '<?=base_url()?>SPJ/hapus_rincian',
                type: 'POST',
                data: {
                    id: id,
                    id_pengajuan_rincian: idPengajuanRincian
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        
                    // refresh tabel rincian menggunakan ajax
                    $.ajax({
                        url: '<?=base_url()?>SPJ/spj_rincian_tbody',
                        type: 'POST',
                        dataType: 'html',
                        data: {
                            id: idPengajuanRincian
                        },
                        success: function(response) {
                            console.log(response);
                            $('#tabel-rincian tbody').html(response);
                        },
                        complete: function() {
                            updateTotals();
                        }
                    });
                        //location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error deleting data');
                }
            });
        }
    });
    
    // Format number untuk input fields
    // MODIFIKASI: Menggunakan 'input' bukan 'keyup'
    $('#input-harga, #input-bruto, #input-pph, #input-netto, #input-volume, #input-persen_pajak').on('input', function() {
        var val = $(this).val();
        
        // Simpan posisi cursor
        var cursorPos = this.selectionStart;
        var oldLength = val.length;
        var oldValue = val;
        
        // Hapus semua karakter selain angka, koma, titik, dan minus
        var cleaned = val.replace(/[^\d,.-]/g, '');
        
        // Hapus pemisah ribuan untuk kalkulasi
        var numericValue = cleaned.replace(/,/g, '');
        
        if (!isNaN(numericValue) && numericValue !== '' && numericValue !== '-') {
            var num = parseFloat(numericValue);
            if (!isNaN(num)) {
                // Format dengan koma sebagai pemisah ribuan
                var formatted = formatNumber(num);
                
                // Hanya update jika berbeda untuk menghindari infinite loop
                if (formatted !== val) {
                    $(this).val(formatted);
                    
                    // Hitung selisih panjang untuk penyesuaian cursor
                    var newLength = formatted.length;
                    var diff = newLength - oldLength;
                    
                    // Set cursor position dengan aman
                    var newPos = Math.min(cursorPos + diff, formatted.length);
                    this.setSelectionRange(newPos, newPos);
                }
            }
        } else if (val === '' || val === '-') {
            // Biarkan kosong atau minus
        } else {
            // Jika tidak valid, hapus karakter terakhir
            $(this).val(oldValue.substring(0, oldValue.length - 1));
            this.setSelectionRange(cursorPos - 1, cursorPos - 1);
        }
        
        // Trigger hitung otomatis jika diperlukan
        if ($(this).is('#input-volume, #input-harga, #input-persen_pajak')) {
            hitungOtomatis();
            cekTotalBrutoNetto() 
        }
    });
    
    // Event untuk blur - validasi dan format ulang
    $('#input-harga, #input-bruto, #input-pph, #input-netto, #input-volume, #input-persen_pajak').on('blur', function() {
        var val = $(this).val();
        if (val && val !== '-') {
            var cleaned = val.replace(/,/g, '');
            var num = parseFloat(cleaned);
            if (!isNaN(num) && num > 0) {
                $(this).val(formatNumber(num));
            } else if (num === 0) {
                $(this).val('0');
            }
        } else if (val === '') {
            $(this).val('0');
        }
    });

    // Event untuk focus - tampilkan nilai tanpa pemisah ribuan
    $('#input-harga, #input-bruto, #input-pph, #input-netto, #input-volume, #input-persen_pajak').on('focus', function() {
        var val = $(this).val();
        if (val) {
            // Hapus pemisah ribuan untuk memudahkan editing
            var cleaned = val.replace(/,/g, '');
            if (cleaned !== val) {
                $(this).val(cleaned);
                // Set cursor di akhir
                this.setSelectionRange(cleaned.length, cleaned.length);
            }
        }
    });

    // MODIFIKASI: Handle paste dengan lebih baik
    $('#input-harga, #input-bruto, #input-pph, #input-netto, #input-volume, #input-persen_pajak').on('paste', function(e) {
        // Delay untuk menangkap nilai setelah paste
        var element = this;
        setTimeout(function() {
            var val = $(element).val();
            // Hapus semua karakter selain angka, koma, titik, dan minus
            var cleaned = val.replace(/[^\d,.-]/g, '');
            $(element).val(cleaned);
            
            // Trigger input event untuk formatting
            $(element).trigger('input');
        }, 10);
    });

    // Event untuk update kegiatan dan jadwal
    $('#kegiatan, #jadwal').on('blur', function() {
        var field = $(this).attr('id');
        var value = $(this).text().trim();
        var idPengajuanRincian = $('#btn-add-row').data('id');
        var oldValue = $(this).data('old-value');
        
        if (oldValue === value) {
            return;
        }
        
        $(this).data('old-value', value);
        
        // Simpan perubahan kegiatan/jadwal
        $.ajax({
            url: '<?=base_url()?>SPJ/update_pengajuan_rincian',
            type: 'POST',
            data: {
                id: idPengajuanRincian,
                field: field,
                value: value
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showFloatingNotification(document.getElementById(field), '✓ Saved');
                }
            }
        });
    });
    
    // Simpan nilai awal untuk kegiatan dan jadwal
    $('#kegiatan, #jadwal').each(function() {
        $(this).data('old-value', $(this).text().trim());
    });    

    // format kolom tanggal -> datepicker dengan format dd mmm yyyy dalam bahasa Indonesia
    $(document).on("focus", ".tanggal", function() {
        $(this).datepicker({
            format: "dd MM yyyy",
            autoclose: true,
            language: "id",
            todayHighlight: true,
            orientation: "bottom auto"
        }).on('changeDate', function(e) {
            $(this).text(e.format());
        });
    });
    

    $(document).on("click", ".volume", function(){
        $(this).select();
        console.log("clicked");
        if (window.getSelection && document.createRange) {
            var range = document.createRange();
            range.selectNodeContents(this);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
    });

    // keypress untuk kolom volume
    $(document).on("keypress", ".volume", function(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // Allow only digits and decimal point
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
            return false;
        }        
        return true;
    });

    $(document).on("input", ".volume", function(evt){
        
        // Remove all non-digit characters except decimal point
        let keyCode = $(this).text();
        let value = keyCode.replace(/[^\d.]/g, '');
        let volume = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).text(volume);
        
        // Set caret ke akhir
        var el = this;
        var range = document.createRange();
        var sel = window.getSelection();
        range.selectNodeContents(el);
        range.collapse(false); // false = akhir node
        sel.removeAllRanges();
        sel.addRange(range);
        console.log(volume);
        
        //  tentukan nilai bruto
        var harga = $(this).closest('tr').find('.harga').text().replace(/,/g, '');
        var bruto = volume.replace(/,/g, '') * harga;
        $(this).closest('tr').find('.bruto').text(bruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // tentukan nilai netto
        var persenPajak = $(this).closest('tr').find('.persen_pajak').text().replace(/,/g, '');
        var pph = (bruto * persenPajak / 100).toFixed(2);
        $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        var netto = bruto - pph;
        $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // Update total bruto dan netto
        calculateTotalBrutoNetto();
        //cekTotalBrutoNetto();
    });

    // formatting harga input
    $(document).on("click", ".harga", function(){
        $(this).select();
        console.log("clicked");
        if (window.getSelection && document.createRange) {
            var range = document.createRange();
            range.selectNodeContents(this);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
    });

    $(document).on("input", ".harga", function(evt){
        let keyCode = $(this).text();
        let value = keyCode.replace(/[^\d.]/g, '');
        let harga = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).text(harga);
        
        // Set caret ke akhir
        var el = this;
        var range = document.createRange();
        var sel = window.getSelection();
        range.selectNodeContents(el);
        range.collapse(false); // false = akhir node
        sel.removeAllRanges();
        sel.addRange(range);
        console.log(harga);

        //  tentukan nilai bruto
        var bruto = $(this).closest('tr').find('.volume').text().replace(/,/g, '') * harga.replace(/,/g, '');
        $(this).closest('tr').find('.bruto').text(bruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // tentukan nilai netto
        var persenPajak = $(this).closest('tr').find('.persen_pajak').text().replace(/,/g, '');
        var pph = (bruto * persenPajak / 100);
        $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        var netto = bruto - pph;
        $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // Update total bruto dan netto
        calculateTotalBrutoNetto();
        //cekTotalBrutoNetto();
        
    });
    
    $(document).on("input", ".pph", function(evt){
        let keyCode = $(this).text();
            let value = keyCode.replace(/[^\d.]/g, '');
            let pph = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            $(this).text(pph);
            
            // Set caret ke akhir
            var el = this;
            var range = document.createRange();
            var sel = window.getSelection();
            range.selectNodeContents(el);
            range.collapse(false); // false = akhir node
            sel.removeAllRanges();
            sel.addRange(range);

            // tentukan nilai netto
            var bruto = $(this).closest('tr').find('.bruto').text().replace(/,/g, '');
            var netto = bruto - pph.replace(/,/g, '');
            $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
            
            calculateTotalBrutoNetto();
    });
    
    $(document).on("input", ".persen_pajak", function(evt){
        let keyCode = $(this).text();
        let value = keyCode.replace(/[^\d.]/g, '');
        let persenPajak = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).text(persenPajak);

        // Set caret ke akhir
        var el = this;
        var range = document.createRange();
        var sel = window.getSelection();
        range.selectNodeContents(el);
        range.collapse(false); // false = akhir node
        sel.removeAllRanges();
        sel.addRange(range);

        // tentukan nilai bruto
        var bruto = $(this).closest('tr').find('.bruto').text().replace(/,/g, '');
        var pph = (bruto * persenPajak.replace(/,/g, '') / 100).toFixed(2);
        $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        
        // tentukan nilai netto
        var netto = bruto - pph;
        $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // Update total bruto
        calculateTotalBrutoNetto();
        //cekTotalBrutoNetto();
    });
    
    // formatting harga input
    $(document).on("click", ".netto", function(){
        $(this).select();
        console.log("clicked");
        if (window.getSelection && document.createRange) {
            var range = document.createRange();
            range.selectNodeContents(this);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
    });

    $(document).on("input", ".netto", function(evt){
        let keyCode = $(this).text();
        let value = keyCode.replace(/[^\d.]/g, '');
        let netto = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).text(netto);

        // Set caret ke akhir
        var el = this;
        var range = document.createRange();
        var sel = window.getSelection();
        range.selectNodeContents(el);
        range.collapse(false); // false = akhir node
        sel.removeAllRanges();
        sel.addRange(range);
        
        calculateTotalBrutoNetto();
    });

    $(document).on("click", "#input-volume, #input-harga, #input-bruto, #input-persen_pajak, #input-pph, #input-netto", function(){
        $(this).select();
    });

	// close modal realisasi
	$('.close-modal-realisasi').click(function(){
        // reload halaman
        location.reload();
	});
});

function calculateTotalBrutoNetto() {
    
    var totalBruto = 0;
    var totalNetto = 0;

    $('#tabel-rincian tbody tr').each(function() {

        // Hitung total bruto
        var brutoText = $(this).find('.bruto').text().replace(/,/g, '');
        if (brutoText) {
            totalBruto += parseFloat(brutoText);
            // jika total bruto lebih dari nominal pengajuan, tampilkan peringatan
            var komitmen = parseFloat($('#komitmen').val().replace(/,/g, ''));
            if (totalBruto > komitmen) {
                //alert('Total bruto melebihi nominal pengajuan!');
                $(".pesan").html('<b style="color:red">      - Total bruto melebihi nominal pengajuan! - </b>')
                $("#simpan").prop('disabled', true)
                totalBruto -= parseFloat(brutoText); // kembalikan total bruto ke nilai sebelumnya
                $(this).find('.bruto').text('0');
                $(this).find('.netto').text('0');
                return false; // keluar dari loop each
            } else {
                $(".pesan").html('')
                //$("#simpan").prop('disabled', false)
            }
        }

        // Hitung total netto
        var nettoText = $(this).find('.netto').text().replace(/,/g, '');
        if (nettoText) {
            totalNetto += parseFloat(nettoText);
        }
        
    });

    $('#tabel-rincian .total-bruto').text(totalBruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    $('#tabel-rincian .total-netto').text(totalNetto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

}

function cekTotalBrutoNetto() {
    
    var totalBruto = 0;
    var totalNetto = 0;

    // total yang sebelum input
    $('#tabel-rincian tbody tr').each(function() {

        // Hitung total bruto
        var brutoText = $(this).find('.bruto').text().replace(/,/g, '');
        if (brutoText) {
            totalBruto += parseFloat(brutoText);
        }

        // Hitung total netto
        var nettoText = $(this).find('.netto').text().replace(/,/g, '');
        if (nettoText) {
            totalNetto += parseFloat(nettoText);
        }
        
    });

    var inputBruto = $("#input-bruto").val().replace(/,/g, '');
    var inputNetto = $("#input-netto").val().replace(/,/g, '');
    
    // total bruto dari perhitungan yang diambil dari database ditambah dengan inputan baru
    var totalBrutoAfterInput = totalBruto + parseFloat(inputBruto);
    var totalNettoAfterInput = totalNetto + parseFloat(inputNetto);
    
    // jika total bruto lebih dari nominal pengajuan, tampilkan peringatan
    var komitmen = parseFloat($('#komitmen').val().replace(/,/g, ''));
    
    if (totalBrutoAfterInput > komitmen) {
        //alert('Total bruto melebihi nominal pengajuan!');
        $(".pesan").html('<b style="color:red">      - Total bruto melebihi nominal pengajuan! - </b>')
        $("#simpan").prop('disabled', true);
        
        // kembalikan total bruto ke nilai sebelumnya dan set input value menjadi 0
        totalBrutoAfterInput -= parseFloat(inputBruto);
        totalNettoAfterInput -= parseFloat(inputNetto);
        
        $('#input-bruto').val('0');
        $('#input-netto').val('0');
        
        $(this).closest('tr').find('.bruto').text('0');
        $(this).closest('tr').find('.netto').text('0');
        alert($(this).closest('tr').find('.bruto').text());
        // set nilai Total pada tabel-rincian
        $('#tabel-rincian .total-bruto').text(totalBrutoAfterInput.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        $('#tabel-rincian .total-netto').text(totalNettoAfterInput.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));


        return false; // keluar dari loop each
    } else {
        $(".pesan").html('')
        $("#simpan").prop('disabled', false);
        
        // set nilai Total pada tabel-rincian
        $('#tabel-rincian .total-bruto').text(totalBrutoAfterInput.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        $('#tabel-rincian .total-netto').text(totalNettoAfterInput.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    }

}

function test(evt) {
    console.log(evt)
}
</script>

<style>
.floating-notification {    
    position: fixed;
    padding: 8px 15px;
    border-radius: 4px;
    font-size: 13px;
    z-index: 9999;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    pointer-events: none;
    font-weight: bold;
    animation: fadeInDown 0.3s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

[contenteditable="true"]:hover {
    background-color: #f9f9f9;
    cursor: text;
}

[contenteditable="true"]:focus {
    outline: 2px solid #3c8dbc;
    background-color: #fff;
}

/* Style untuk form input rincian */

#input-data-rincian td input {
    width: 100%;
    padding: 5px;
    border: 1px solid #fff;
    /*border-radius: 3px;*/
}

#input-data-rincian td input:focus {
    border-color: #fff;
    outline: none;
    /*box-shadow: 0 0 5px rgba(60, 141, 188, 0.3);*/
}

/* Style untuk input dengan format koma */
input[type="text"].number-format {
    text-align: right;
    font-family: 'Courier New', monospace;
    font-size: 14px;
}

/* Style untuk tampilan angka di tabel */
td.number-format {
    text-align: right;
    font-family: 'Courier New', monospace;
}

/* Mencegah infinite loop pada input */
input.number-format {
    user-select: auto;
}

/* Animasi smooth untuk feedback */
input.number-format:focus {
    background-color: #fffef0;
    transition: background-color 0.2s ease;
}

input.number-format {
    transition: background-color 0.2s ease;
}
</style>