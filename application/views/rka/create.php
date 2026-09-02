<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $title; ?>
            <small>Tambah Data Anggaran DPSJ</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('RKA'); ?>">Anggaran DPSJ</a></li>
            <li class="active">Tambah</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Form Tambah Data</h3>
            </div>
            
            <form role="form" method="POST" action="<?php echo base_url('RKA/store'); ?>" id="form-anggaran">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun Anggaran <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="tahun_anggaran" value="<?php echo date('Y'); ?>" required>
                                <?php echo form_error('tahun_anggaran', '<small class="text-danger">', '</small>'); ?>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode DPSJ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                        class="form-control" 
                                        name="kode_dpsj" 
                                        id="kode_dpsj" 
                                        placeholder="Ketik kode atau deskripsi DPSJ..." 
                                        autocomplete="off"
                                        required>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" id="btnSearchDPSJ">
                                            <i class="fa fa-search"></i> Cari
                                        </button>
                                    </span>
                                </div>
                                <div id="dpsj-search-results" class="search-results" style="display:none;"></div>
                                <small class="text-muted">Ketik minimal 2 karakter untuk mencari</small>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi DPSJ</label>
                                <textarea class="form-control" name="deskripsi_dpsj" id="deskripsi_dpsj" rows="2" readonly></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_kegiatan" id="kode_kegiatan" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Nama Kegiatan Pendek</label>
                                <input type="text" class="form-control" name="nama_kegiatan_pendek" id="nama_kegiatan_pendek">
                            </div>
                            
                            <div class="form-group">
                                <label>Nama Kegiatan</label>
                                <textarea class="form-control" name="nama_kegiatan" id="nama_kegiatan" rows="2"></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Dana</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="kode_dana" id="kode_dana">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalDana">
                                            <i class="fa fa-search"></i> Pilih
                                        </button>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode Akun <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_akun" id="kode_akun" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Deskripsi Akun</label>
                                <textarea class="form-control" name="deskripsi_akun" id="deskripsi_akun" rows="2"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Kategori Kegiatan</label>
                                <select class="form-control" name="kategori_kegiatan" id="kategori_kegiatan">
                                    <option value="">-- Pilih Kategori Kegiatan --</option>
                                    <option value="Operasional">Operasional</option>
                                    <option value="Pengembangan">Pengembangan</option>
                                    <option value="Investasi">Investasi</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Anggaran</label>
                                <input type="text" class="form-control currency" name="anggaran" id="anggaran" value="0">
                            </div>
                            
                            <div class="form-group" style="display:none;">
                                <label>Komitmen</label>
                                <input type="text" class="form-control currency" name="komitmen" id="komitmen" value="0">
                            </div>
                            
                            <!-- Tambahan Field Flag Payroll -->
                            <div class="form-group">
                                <label>Flag Payroll</label>
                                <select class="form-control" name="flag_payroll" id="flag_payroll">
                                    <option value="">-- Pilih Flag Payroll --</option>
                                    <option value="Procost Unit">Procost Unit</option>
                                    <option value="Procost Umum">Procost Umum</option>
                                    <option value="Procost Remun">Procost Remun</option>
                                </select>
                                <small class="text-muted">Pilih jenis Procost</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <a href="<?php echo base_url('RKA'); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Modal Pilih DPSJ -->
<div class="modal fade" id="modalDPSJ" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pilih Kode DPSJ</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="table-dpsj">
                    <thead>
                        <tr>
                            <th>Kode DPSJ</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kode_dpsj_list as $dpsj): ?>
                        <tr>
                            <td><?php echo $dpsj->kode_dpsj; ?></td>
                            <td><?php echo $dpsj->deskripsi_dpsj; ?></td>
                            <td>
                                <button class="btn btn-xs btn-primary pilih-dpsj" 
                                        data-kode="<?php echo $dpsj->kode_dpsj; ?>" 
                                        data-deskripsi="<?php echo $dpsj->deskripsi_dpsj; ?>">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Dana -->
<div class="modal fade" id="modalDana" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pilih Kode Dana</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="table-dana">
                    <thead>
                        <tr>
                            <th>Kode Dana</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kode_dana_list as $dana): ?>
                        <tr>
                            <td><?php echo $dana->kode_dana; ?></td>
                            <td><?php echo $dana->deskripsi_dana; ?></td>
                            <td>
                                <button class="btn btn-xs btn-primary pilih-dana" 
                                        data-kode="<?php echo $dana->kode_dana; ?>">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery untuk fitur tambahan -->
<script>
$(document).ready(function() {
    // DataTables untuk modal
    $('#table-dpsj').DataTable();
    $('#table-dana').DataTable();
    
    // Format currency
    $('.currency').on('keyup', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value !== '') {
            $(this).val(parseInt(value).toLocaleString('id-ID'));
        }
    });
    
    /*// Pilih DPSJ
    $('.pilih-dpsj').click(function() {
        var kode = $(this).data('kode');
        var deskripsi = $(this).data('deskripsi');
        $('#kode_dpsj').val(kode);
        $('#deskripsi_dpsj').val(deskripsi);
        $('#modalDPSJ').modal('hide');
    });*/
    
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
            url: '<?php echo base_url("RKA/search_dpsj_rka"); ?>',
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
        
    // Pilih Dana
    $('.pilih-dana').click(function() {
        var kode = $(this).data('kode');
        $('#kode_dana').val(kode);
        $('#modalDana').modal('hide');
    });
    
    // Auto-fill deskripsi dari kode (contoh dengan AJAX)
    $('#kode_kegiatan').on('blur', function() {
        var kode = $(this).val();
        if (kode !== '') {
            // Contoh AJAX untuk mengambil data kegiatan (sesuaikan dengan kebutuhan)
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
});
</script>

<style>
/* Styling untuk hasil pencarian autocomplete */
.search-results {
    position: absolute;
    z-index: 1000;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #d2d6de;
    border-radius: 4px;
    margin-top: 2px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.175);
}

.search-result-item {
    padding: 10px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f4f4f4;
    transition: all 0.3s;
}

.search-result-item:hover {
    background-color: #3c8dbc;
    color: white;
}

.search-result-item:hover .text-muted {
    color: white !important;
}

.search-result-item .kode {
    font-weight: bold;
    margin-bottom: 3px;
}

.search-result-item .deskripsi {
    font-size: 0.9em;
    color: #666;
}

.search-result-item:hover .deskripsi {
    color: #f0f0f0;
}

.search-result-item.selected {
    background-color: #3c8dbc;
    color: white;
}

.search-result-item.selected .deskripsi {
    color: #f0f0f0;
}

/* Loading indicator */
.search-loading {
    padding: 10px 15px;
    text-align: center;
    color: #999;
}

.search-loading i {
    margin-right: 5px;
}

/* Info tidak ada hasil */
.search-no-result {
    padding: 10px 15px;
    text-align: center;
    color: #999;
    font-style: italic;
}

/* Highlight untuk teks yang cocok */
.highlight {
    background-color: #ffff99;
    font-weight: bold;
}

.search-result-item:hover .highlight {
    background-color: #ffd700;
}

/* Input group adjustment */
.input-group {
    position: relative;
}

/* Responsif */
@media (max-width: 768px) {
    .search-results {
        max-height: 250px;
        font-size: 14px;
    }
    
    .search-result-item {
        padding: 8px 12px;
    }
}
</style>