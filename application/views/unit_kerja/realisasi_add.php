<?php 
//echo '<pre>'; print_r($result); echo '</pre>';
$id_pengajuan_rincian = isset($result[0]['id']) ? $result[0]['id'] : '';
$id_pengajuan_pemohon = isset($result[0]['id_pengajuan_pemohon']) ? $result[0]['id_pengajuan_pemohon'] : '';
$kode_akun = isset($result[0]['kode_akun']) ? $result[0]['kode_akun'] : '';
$deskripsi_akun = isset($result[0]['deskripsi_akun']) ? $result[0]['deskripsi_akun'] : '';
$kode_kegiatan = isset($result[0]['kode_kegiatan']) ? $result[0]['kode_kegiatan'] : '';
$nama_kegiatan = isset($result[0]['nama_kegiatan']) ? $result[0]['nama_kegiatan'] : '';
$nomor_pengajuan = isset($result[0]['nomor_pengajuan']) ? $result[0]['nomor_pengajuan'] : '';
$kode_dana = isset($result[0]['kode_dana']) ? $result[0]['kode_dana'] : '';

?>

                          
<div class="containerx">
    
    <input type="hidden" id="id" value="0" >
    <input type="hidden" id="newId" value="0" >

    <table  style="margin:auto; width:80%" class="table table-bordered table-stripedx">
        <tr>
            <td class="label-1">KEGIATAN</td>
            <td>:</td>
            <td width="70%" id="kegiatan" contenteditable="true"></td>
        </tr>
        <tr>
            <td class="label-1">HARI/TANGGAL/WAKTU/TEMPAT</td>
            <td>:</td>
            <td id="jadwal" contenteditable="true"></td>
        </tr>
        <tr>
            <td class="label-1">JENIS BIAYA</td>
            <td>:</td>
            <td id="jenis_biaya" contenteditable="true"></td>
        </tr>
        <tr>
            <td class="label-1">NOMOR/NAMA AKUN</td>
            <td>:</td>
            <td id="akun"><?php echo $kode_akun .'/'.$deskripsi_akun; ?></td>
        </tr>
        <tr>
            <td class="label-1">NOMOR/NAMA PROCOST</td>
            <td>:</td>
            <td id="procost"><?php echo $kode_kegiatan .'/'.$nama_kegiatan; ?></td>
        </tr>
    </table>

    <div class="kotak">
        <table style="margin:auto" id="tabel-rincian">
            <label for="">&nbsp;</label>
            <thead>
                <tr>
                    <td class="text-center" colspan="10">REKAP BIAYA</td>
                </tr>
                <tr>
                    <td rowspan="2" class="label-head">NO</td>
                    <td rowspan="2" class="label-head">TANGGAL</td>
                    <td rowspan="2" class="label-head">KETERANGAN</td>
                    <td colspan="3" class="label-head">SATUAN</td>
                    <td rowspan="2" class="label-head">BRUTO</td>
                    <td colspan="2" class="label-head">TARIF PAJAK</td>
                    <td rowspan="2" class="label-head">NETTO (Rp)</td>
                </tr>
                <tr>
                    <td class="label-head">VOL</td>
                    <td class="label-head">KET VOL</td>
                    <td class="label-head">HARGA (Rp)</td>
                    <td class="label-head">%</td>
                    <td class="label-head">PPh (Rp)</td>
                </tr>            
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right">TOTAL</td>
                    <td class="total-bruto"></td>
                    <td></td>
                    <td></td>
                    <td class="total-netto"></td>
                </tr>
                <tr>
                    <td colspan="9" style="border: 1px solid #fff">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-add-row" >
                            <i class="fa fa-plus"></i> Tambah Rincian                                                            
                        </button>                                                        
                        <button class="btn btn-success btn-sm" id="simpan" data-id="<?=$id_pengajuan_rincian?>" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>"><i class="fa fa-floppy-o"></i> Simpan<?=$id_pengajuan_rincian?></button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- bootstrap datepicker -->
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.id.js"></script>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?=base_url()?>assets/AdminLTE/plugins/datepicker/datepicker3.css">

<script>
$(document).ready(function()
{
    // Tambah baris rincian
    //$(document).on("click", "#btn-add-row", function() {   // terjadi bubble   
    $("#btn-add-row").click( function() {        
        var newId = $("#newId").val() || 0; // Ambil nilai newId dari input tersembunyi atau set ke 0 jika tidak ada
        var rowCount = $('#tabel-rincian tbody tr').length + 1;
        var newRow = '<tr>' +
            '<td id="99999999999">' + rowCount + '</td>' +
            '<td class="tanggal" contenteditable="true"></td>' +
            '<td class="keterangan" contenteditable="true"></td>' +
            '<td class="volume" contenteditable="true"></td>' +
            '<td class="ket_volume" contenteditable="true"></td>' +
            '<td class="harga" contenteditable="true"></td>' +
            '<td class="bruto" contenteditable="true"></td>' +
            '<td class="persen_pajak" contenteditable="true"></td>' +
            '<td class="pph" contenteditable="true"></td>' +
            '<td class="netto" contenteditable="true"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>' +
        '</tr>';
            
        // Tambahkan baris baru ke tabel
        $('#tabel-rincian tbody').append(newRow);

        newId++;
        $("#newId").val(newId);
        console.log(newRow);
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
        let keyCode = $(this).text()
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
        console.log('volume'+volume);

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

        // Update total bruto
        calculateTotalBrutoNetto()
    });

    // keyup untuk kolom volume
    /*$(document).on("keyup", ".volume", function(evt){
        let keyCode = $(this).text()
        var charCode = (evt.which) ? evt.which : keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
            return true;
        } else {
            //var jumlah = $(this).val();
            // Remove all non-digit characters except decimal point
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

            // Update total bruto
            calculateTotalBrutoNetto()
        }
    });    

    // blur untuk kolom volume -> karena fungsi keyup tidak bekerja dengan baik pada browsers android

    $(document).on("blur", ".volume", function(){
        var volume = $(this).text().replace(/,/g, '');
        // Format harga dengan pemisah ribuan
        //harga = parseFloat(harga).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        volume = formatNumberWithThousandSeparator(volume);
        console.log(volume);
        $(this).text(volume);
		

            //  tentukan nilai bruto
            var bruto = $(this).closest('tr').find('.harga').text().replace(/,/g, '') * volume.replace(/,/g, '');
            $(this).closest('tr').find('.bruto').text(bruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

            // tentukan nilai netto
            var persenPajak = $(this).closest('tr').find('.persen_pajak').text().replace(/,/g, '');
            var pph = (bruto * persenPajak / 100).toFixed(2);
            $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
            var netto = bruto - pph;
            $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

		// Update total bruto
            calculateTotalBrutoNetto();
    });*/


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
        let keyCode = $(this).text()
        let value = keyCode.replace(/[^\d.]/g, '');
        //harga = formatNumberWithThousandSeparator(value); //
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
        console.log('harga'+harga);

        //  tentukan nilai bruto
        var bruto = $(this).closest('tr').find('.volume').text().replace(/,/g, '') * harga.replace(/,/g, '');
        $(this).closest('tr').find('.bruto').text(bruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // tentukan nilai netto
        var persenPajak = $(this).closest('tr').find('.persen_pajak').text().replace(/,/g, '');
        var pph = (bruto * persenPajak / 100);
        $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        var netto = bruto - pph;
        $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // Update total bruto
        calculateTotalBrutoNetto()
    });

    /*$(document).on("keyup", ".harga", function(evt){
        let keyCode = $(this).text()
        var charCode = (evt.which) ? evt.which : keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
            return true;
        } else {
            //var jumlah = $(this).val();
            // Remove all non-digit characters except decimal point
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
            var pph = (bruto * persenPajak / 100).toFixed(2);
            $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
            var netto = bruto - pph;
            $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

            // Update total bruto
            calculateTotalBrutoNetto()

        }
    });

    // blur untuk kolom harga -> karena fungsi keyup tidak bekerja dengan baik pada browsers android
    $(document).on("blur", ".harga", function(){
        var harga = $(this).text().replace(/,/g, '');
        // Format harga dengan pemisah ribuan
        //harga = parseFloat(harga).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        harga = formatNumberWithThousandSeparator(harga);
        console.log(harga);
        $(this).text(harga);

        //  tentukan nilai bruto
        var bruto = $(this).closest('tr').find('.volume').text().replace(/,/g, '') * harga.replace(/,/g, '');
        $(this).closest('tr').find('.bruto').text(bruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // tentukan nilai netto
        var persenPajak = $(this).closest('tr').find('.persen_pajak').text().replace(/,/g, '');
        var pph = (bruto * persenPajak / 100).toFixed(2);
        $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        var netto = bruto - pph;
        $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // Update total bruto
        calculateTotalBrutoNetto()
    });*/
    
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
    });

    // keyup untuk kolom pph
    /*$(document).on("keyup", ".pph", function(evt){
        let keyCode = $(this).text()
        var charCode = (evt.which) ? evt.which : keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
            return true;
        } else {
            //var jumlah = $(this).val();
            // Remove all non-digit characters except decimal point
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
            console.log(pph);
            // tentukan nilai netto
            var bruto = $(this).closest('tr').find('.bruto').text().replace(/,/g, '');
            var netto = bruto - pph.replace(/,/g, '');
            $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        }
    });*/

    // method input untuk kolom persen pajak -> karena fungsi keyup tidak bekerja dengan baik pada mobile browsers 
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
        calculateTotalBrutoNetto()
    });

    // keyup untuk kolom persen_pajak
    /*$(document).on("keyup", ".persen_pajak", function(evt){
        let keyCode = $(this).text()
        var charCode = (evt.which) ? evt.which : keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
            return true;
        } else {
            //var jumlah = $(this).val();
            // Remove all non-digit characters except decimal point
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
            console.log(persenPajak);

            // tentukan nilai bruto
            var bruto = $(this).closest('tr').find('.bruto').text().replace(/,/g, '');
            var pph = (bruto * persenPajak.replace(/,/g, '') / 100).toFixed(2);
            $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
            
            // tentukan nilai netto
            var netto = bruto - pph;
            $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

            // Update total bruto
            calculateTotalBrutoNetto()
        }
    });

    // blur untuk kolom pph -> karena fungsi keyup tidak bekerja dengan baik pada browsers android
    $(document).on("blur", ".persen_pajak", function(){
        var persen_pajak = $(this).text().replace(/,/g, '');
        persen_pajak = formatNumberWithThousandSeparator(persen_pajak);
        console.log(persen_pajak);
        $(this).text(persen_pajak);

        //  tentukan nilai bruto
        var bruto = $(this).closest('tr').find('.volume').text().replace(/,/g, '') * harga.replace(/,/g, '');
        $(this).closest('tr').find('.bruto').text(bruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // tentukan nilai netto
        var persenPajak = $(this).closest('tr').find('.persen_pajak').text().replace(/,/g, '');
        var pph = (bruto * persenPajak / 100).toFixed(2);
        $(this).closest('tr').find('.pph').text(pph.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        var netto = bruto - pph;
        $(this).closest('tr').find('.netto').text(netto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

        // Update total bruto
        calculateTotalBrutoNetto()
    });*/

    // Simpan data ke tabel realisasi melalui AJAX
    //$(document).on("click", "#simpan", function() {
    $("#simpan").click(function() {
        // Ambil data dari inputan
        var id_pengajuan_rincian = $(this).data('id');
        var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
        var kegiatan = $("#kegiatan").text();
        var tanggal = $("#tanggal").text();
        var jenis_biaya = $("#jenis_biaya").text();
        var akun = $("#akun").text();
        var procost = $("#procost").text();
        var page = $("#temp_page").val(); // ambil halaman saat ini

        // ambil data dari inputan jadwal kegiatan
        
        var kegiatan = $("#kegiatan").text();
        if (kegiatan === '') {
            alert('Kegiatan tidak boleh kosong!');
            return false;
        }
        var jadwal = $("#jadwal").text();
        if (jadwal === '') {
            alert('Jadwal tidak boleh kosong!');
            return false;
        }
        var jenis_biaya = $("#jenis_biaya").text();
        if (jenis_biaya === '') {
            alert('Jenis biaya tidak boleh kosong!');
            return false;
        }

        // masukkan data ke dalam array data_kegiatan
        var data_kegiatan = {
            kegiatan: kegiatan,
            jadwal: jadwal,
            jenis_biaya: jenis_biaya
        };

        // Ambil data rincian
        var rincianData = [];
        $('#tabel-rincian tbody tr').each(function() {
            var row = $(this);
            rincianData.push({
                id_pengajuan_rincian: id_pengajuan_rincian,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                kode_kegiatan: <?= json_encode($kode_kegiatan) ?>,
                nama_kegiatan: <?= json_encode($nama_kegiatan) ?>,
                kode_akun: <?= json_encode($kode_akun) ?>,
                deskripsi_akun: <?= json_encode($deskripsi_akun) ?>,
                kode_dana: <?= json_encode($kode_dana) ?>,
                tanggal: row.find('.tanggal').text(),
                keterangan: row.find('.keterangan').text(),
                volume: row.find('.volume').text().replace(/,/g, ''),
                ket_volume: row.find('.ket_volume').text(),
                harga: row.find('.harga').text().replace(/,/g, ''),
                bruto: row.find('.bruto').text().replace(/,/g, ''),
                persen_pajak: row.find('.persen_pajak').text().replace(/,/g, ''),
                pph: row.find('.pph').text().replace(/,/g, ''),
                netto: row.find('.netto').text().replace(/,/g, '')
            });
        });
        console.log(rincianData);
        // Kirim data melalui AJAX
        $.ajax({
            url: '<?= base_url('realisasi/simpan') ?>',
            type: 'POST',
            //dataType: 'json',
            data: {
                rincian: rincianData, data_kegiatan: data_kegiatan,
                id_pengajuan_rincian: id_pengajuan_rincian
            },
            success: function(response) {
                /*if (response.status === 'success') {
                    //alert('Data berhasil disimpan!');
                    // Reset form atau lakukan tindakan lain setelah sukses
                    location.reload(); // Reload halaman untuk melihat perubahan
                } else {
                    alert('Gagal menyimpan data. Silakan coba lagi.');
                }*/
               getDataPage(page); // refresh data
               $("#modal-realisasi").modal('hide');
               console.log(response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });
    
    // Hapus baris rincian
    $(document).on('click', '.btn-remove-row', function() {
        $(this).closest('tr').remove();
        updateRowNumbers();

        calculateTotalBrutoNetto()
    });

});


// Update nomor urut
function updateRowNumbers() {
    $('#tabel-rincian tbody tr').each(function(index) {
        $(this).find('td:first').text(index + 1);
    });
}

function calculateTotalBrutoNetto() {
    
    var totalBruto = 0;
    var totalNetto = 0;

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

    $('#tabel-rincian .total-bruto').text(totalBruto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    $('#tabel-rincian .total-netto').text(totalNetto.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));

}

function getDataPage(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'realisasi_ajax/data/'+page;
	}
	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy },
		success: function(data){
			$('#postList').html(data);
            $('.loading-overlay').fadeOut("slow");
		}
	});
}

function formatNumberWithThousandSeparator(num) {
    num = num.toString().replace(/,/g, ''); //num = num.toString().replace(/[^\d.]/g, '');
    if (num === '') return '';
    var parts = num.split('.');
    var integerPart = num;
    var result = '';
    var count = 0;
    for (var i = integerPart.length - 1; i >= 0; i--) {
        result = integerPart[i] + result;
        count++;
        if (count % 3 === 0 && i !== 0) {
            result = ',' + result;
        }
    }
    return result;
}
</script>

<style>
.label-1 {
    font-weight: bold;
    width: 30%;
    text-align:right;
    color: #777;
}

.label-head {
    font-weight: bold;
    color: #777;
}
td {
    padding: 5px;
    border: 1px solid #ddd;
}
#kegiatan, #tanggal, #jenis_biaya, #akun, #procost {
    /*background-color: #f9f9f9;
    min-height: 30px;*/
}

.kotak{
    background-color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-top: 2px solid #fa0;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}
</style>