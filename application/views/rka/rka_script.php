<script src="<?= base_url('assets/AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script>
$(document).ready(function() {
    $('#table-anggaran').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ entri",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            },
            "zeroRecords": "Tidak ditemukan data yang sesuai",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            "infoFiltered": "(disaring dari _MAX_ total entri)"
        }
    });
    // Format currency untuk input
    $('.currency').on('keyup', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value !== '') {
            $(this).val(parseInt(value).toLocaleString('id-ID'));
        }
    });
    
    // Auto-calculate atau validasi lainnya
    $('#kode_kegiatan').on('blur', function() {
        let kode = $(this).val();
        if (kode.length > 0) {
            // AJAX call ke server untuk mengambil data kegiatan
            $.ajax({
                url: base_url + 'RKA/get_kegiatan',
                type: 'POST',
                data: {kode_kegiatan: kode},
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#nama_kegiatan_pendek').val(response.data.nama_pendek);
                        $('#nama_kegiatan').val(response.data.nama);
                    }
                },
                error: function() {
                    console.log('Error fetching data');
                }
            });
        }
    });
    
    // Validasi form sebelum submit
    $('#form-anggaran').submit(function(e) {
        // Konversi currency ke number sebelum submit
        $('.currency').each(function() {
            let value = $(this).val().replace(/\./g, '');
            $(this).val(value);
        });
        
        return true;
    });
    
    // Variabel untuk menyimpan timeout debounce
    var searchTimeout;
    
    // Variabel untuk menyimpan data DPSJ (cache)
    var dpsjCache = [];
    var selectedIndex = -1;
    
    // Event listener untuk input kode_dpsj
    $('#kode_dpsj').on('keyup', function(e) {
        var keyword = $(this).val().trim();
        
        // Handle keyboard navigation (panah atas/bawah)
        if (e.keyCode === 40) { // Panah bawah
            navigateResults('down');
            return;
        } else if (e.keyCode === 38) { // Panah atas
            navigateResults('up');
            return;
        } else if (e.keyCode === 13) { // Enter
            e.preventDefault();
            selectCurrentResult();
            return;
        } else if (e.keyCode === 27) { // Escape
            hideSearchResults();
            return;
        }
        
        // Reset selected index saat mengetik
        selectedIndex = -1;
        
        // Hapus timeout sebelumnya
        clearTimeout(searchTimeout);
        
        // Jika keyword kurang dari 2 karakter, sembunyikan hasil
        if (keyword.length < 2) {
            hideSearchResults();
            return;
        }
        
        // Tampilkan loading
        showSearchLoading();
        
        // Debounce pencarian (delay 300ms setelah user berhenti mengetik)
        searchTimeout = setTimeout(function() {
            searchDPSJ(keyword);
        }, 300);
    });
    
    // Event klik tombol cari
    $('#btnSearchDPSJ').on('click', function() {
        var keyword = $('#kode_dpsj').val().trim();
        if (keyword.length >= 2) {
            searchDPSJ(keyword);
        } else {
            alert('Minimal 2 karakter untuk mencari');
        }
    });
    
    // Sembunyikan hasil saat klik di luar
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#kode_dpsj, #btnSearchDPSJ, #dpsj-search-results').length) {
            hideSearchResults();
        }
    });
    
    // Fungsi pencarian DPSJ
    function searchDPSJ(keyword) {
        $.ajax({
            url: '<?php echo base_url("RKA/search_dpsj"); ?>',
            type: 'POST',
            data: {keyword: keyword},
            dataType: 'json',
            beforeSend: function() {
                showSearchLoading();
            },
            success: function(response) {
                if (response.status === 'success' && response.data.length > 0) {
                    dpsjCache = response.data;
                    displaySearchResults(response.data, keyword);
                } else {
                    showNoResults();
                }
            },
            error: function() {
                $('#dpsj-search-results').html('<div class="search-no-result"><i class="fa fa-exclamation-triangle text-danger"></i> Terjadi kesalahan</div>').show();
            }
        });
    }
    
    // Tampilkan hasil pencarian
    function displaySearchResults(results, keyword) {
        var html = '';
        $.each(results, function(index, item) {
            // Highlight keyword
            var kodeHighlight = highlightText(item.kode_dpsj, keyword);
            var deskripsiHighlight = highlightText(item.deskripsi_dpsj, keyword);
            
            html += '<div class="search-result-item" data-index="' + index + '" ' +
                    'data-kode="' + item.kode_dpsj + '" ' +
                    'data-deskripsi="' + item.deskripsi_dpsj.replace(/"/g, '&quot;') + '">' +
                    '<div class="kode">' + kodeHighlight + '</div>' +
                    '<div class="deskripsi">' + deskripsiHighlight + '</div>' +
                    '</div>';
        });
        
        $('#dpsj-search-results').html(html).show();
        selectedIndex = -1;
    }
    
    // Fungsi untuk highlight teks yang cocok
    function highlightText(text, keyword) {
        if (!text || !keyword) return text;
        var regex = new RegExp('(' + escapeRegExp(keyword) + ')', 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }
    
    // Escape regex special characters
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    // Tampilkan loading
    function showSearchLoading() {
        $('#dpsj-search-results').html('<div class="search-loading"><i class="fa fa-spinner fa-spin"></i> Mencari data...</div>').show();
    }
    
    // Tampilkan pesan tidak ada hasil
    function showNoResults() {
        $('#dpsj-search-results').html('<div class="search-no-result"><i class="fa fa-info-circle"></i> Tidak ada data ditemukan</div>').show();
    }
    
    // Sembunyikan hasil pencarian
    function hideSearchResults() {
        $('#dpsj-search-results').hide();
        selectedIndex = -1;
    }
    
    // Navigasi hasil dengan keyboard
    function navigateResults(direction) {
        var items = $('.search-result-item');
        if (items.length === 0) return;
        
        // Hapus class selected dari semua item
        items.removeClass('selected');
        
        if (direction === 'down') {
            selectedIndex = (selectedIndex + 1) % items.length;
        } else if (direction === 'up') {
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
        }
        
        // Tambahkan class selected ke item yang dipilih
        $(items[selectedIndex]).addClass('selected');
        
        // Scroll ke item yang dipilih
        var container = $('#dpsj-search-results');
        var item = $(items[selectedIndex]);
        var containerHeight = container.height();
        var itemTop = item.position().top;
        var itemHeight = item.outerHeight();
        
        if (itemTop < 0) {
            container.scrollTop(container.scrollTop() + itemTop);
        } else if (itemTop + itemHeight > containerHeight) {
            container.scrollTop(container.scrollTop() + (itemTop + itemHeight - containerHeight));
        }
    }
    
    // Pilih hasil yang sedang dipilih
    function selectCurrentResult() {
        var selectedItem = $('.search-result-item.selected');
        if (selectedItem.length > 0) {
            var kode = selectedItem.data('kode');
            var deskripsi = selectedItem.data('deskripsi');
            
            $('#kode_dpsj').val(kode);
            $('#deskripsi_dpsj').val(deskripsi);
            hideSearchResults();
            
            // Efek highlight
            $('#kode_dpsj').addClass('bg-success');
            setTimeout(function() {
                $('#kode_dpsj').removeClass('bg-success');
            }, 1000);
        }
    }
    
    // Event klik pada hasil pencarian
    $(document).on('click', '.search-result-item', function() {
        var kode = $(this).data('kode');
        var deskripsi = $(this).data('deskripsi');
        
        $('#kode_dpsj').val(kode);
        $('#deskripsi_dpsj').val(deskripsi);
        hideSearchResults();
        
        // Efek highlight
        $('#kode_dpsj').addClass('bg-success');
        setTimeout(function() {
            $('#kode_dpsj').removeClass('bg-success');
        }, 1000);
    });
    
    // Reset pencarian saat modal dibuka
    $('#modalDPSJ').on('show.bs.modal', function() {
        $('#searchDPSJ').val('');
        displayDpsjResults(allDpsjData);
    });
    
    // Pilih DPSJ
    $(document).on('click', '.pilih-dpsj', function() {
        var kode = $(this).data('kode');
        var deskripsi = $(this).data('deskripsi');
        $('#kode_dpsj').val(kode);
        $('#deskripsi_dpsj').val(deskripsi);
        
        // Tambahkan efek highlight pada input
        $('#kode_dpsj').addClass('bg-success');
        setTimeout(function() {
            $('#kode_dpsj').removeClass('bg-success');
        }, 1000);
        
        $('#modalDPSJ').modal('hide');
    });
    
    // DataTables untuk modal (opsional, bisa dinonaktifkan jika menggunakan pencarian custom)
    // $('#table-dpsj').DataTable(); // Hapus atau comment baris ini
    
    // Format currency
    $('.currency').on('keyup', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value !== '') {
            $(this).val(parseInt(value).toLocaleString('id-ID'));
        }
    });
    
    // Pilih Dana
    $('.pilih-dana').click(function() {
        var kode = $(this).data('kode');
        $('#kode_dana').val(kode);
        $('#modalDana').modal('hide');
    });
    
    // Auto-fill deskripsi dari kode
    $('#kode_kegiatan').on('blur', function() {
        var kode = $(this).val();
        if (kode !== '') {
            $.ajax({
                url: '<?php echo base_url("RKA/get_kegiatan_by_kode"); ?>',
                type: 'POST',
                data: {kode: kode},
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#nama_kegiatan_pendek').val(response.data.nama_pendek);
                        $('#nama_kegiatan').val(response.data.nama);
                    }
                }
            });
        }
    });

    // Fungsi pencarian dengan AJAX
    function searchDPSJAjax() {
        var keyword = $('#searchDPSJ').val();
        
        $('#loading-dpsj').show();
        $('#table-dpsj').hide();
        $('#no-data-dpsj').hide();
        
        $.ajax({
            url: '<?php echo base_url("RKA/search_dpsj"); ?>',
            type: 'POST',
            data: {keyword: keyword},
            dataType: 'json',
            success: function(response) {
                $('#loading-dpsj').hide();
                
                if (response.status === 'success' && response.data.length > 0) {
                    var html = '';
                    $.each(response.data, function(index, item) {
                        html += '<tr>' +
                            '<td><strong>' + item.kode_dpsj + '</strong></td>' +
                            '<td>' + item.deskripsi_dpsj + '</td>' +
                            '<td>' +
                                '<button class="btn btn-xs btn-primary pilih-dpsj" ' +
                                        'data-kode="' + item.kode_dpsj + '" ' +
                                        'data-deskripsi="' + item.deskripsi_dpsj.replace(/"/g, '&quot;') + '">' +
                                    '<i class="fa fa-check"></i> Pilih' +
                                '</button>' +
                            '</td>' +
                        '</tr>';
                    });
                    $('#dpsj-results').html(html);
                    $('#table-dpsj').show();
                } else {
                    $('#no-data-dpsj').show();
                }
            },
            error: function() {
                $('#loading-dpsj').hide();
                alert('Terjadi kesalahan saat mencari data');
            }
        });
    }

    // fungsi delete
    $(document).on('click', '.delete-rka', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        deleteRKA(id);
    });
    
    function deleteRKA(id) {
        if (confirm('Yakin ingin menghapus data ini?')) {
            window.location.href = '<?php echo base_url("RKA/delete/"); ?>' + id;
        }
    }
});
</script>
<style>
/* Styling untuk modal pencarian */
#modalDPSJ .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

#searchDPSJ {
    border-right: none;
}

#searchDPSJ:focus {
    border-color: #3c8dbc;
    box-shadow: none;
}

#btnSearchDPSJ {
    border-left: none;
}

#btnSearchDPSJ:hover {
    background-color: #3c8dbc;
    color: white;
}

/* Highlight untuk hasil pencarian */
.highlight {
    background-color: yellow;
    font-weight: bold;
}

/* Loading animation */
#loading-dpsj {
    padding: 30px;
}

#loading-dpsj i {
    color: #3c8dbc;
}

/* Efek hover pada baris tabel */
#table-dpsj tbody tr:hover {
    background-color: #f5f5f5;
    cursor: pointer;
}

/* Styling untuk tombol pilih */
.pilih-dpsj {
    transition: all 0.3s;
}

.pilih-dpsj:hover {
    transform: scale(1.05);
}

/* Animasi untuk input yang terpilih */
.bg-success {
    background-color: #d4edda !important;
    transition: background-color 0.5s ease;
}

/* Responsif untuk mobile */
@media (max-width: 768px) {
    #modalDPSJ .modal-dialog {
        margin: 10px;
    }
    
    #table-dpsj {
        font-size: 12px;
    }
    
    #table-dpsj .btn-xs {
        font-size: 10px;
        padding: 3px 5px;
    }
}
</style>