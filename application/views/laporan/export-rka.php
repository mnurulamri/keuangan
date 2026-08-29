<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Export Data RKA
        <small>Export data ke Excel dengan filter</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Export RKA</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Filter Data</h3>
                </div>
                
                <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-warning"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php endif; ?>
                
                <form action="<?php echo base_url('export_rka_xlsx/export_xlsx'); ?>" method="POST" id="exportForm">
                    <div class="box-body">

                        <!-- Tanggal Cetak -->
                        <div class="form-group">
                            <label for="tanggal_cetak">Tanggal Cetak</label>
                            <input type="date" class="form-control" id="tanggal_cetak" name="tanggal_cetak" value="<?php echo date('Y-m-d'); ?>" required >
                        </div>

                        <div class="form-group">
                            <label for="tahun">Tahun Anggaran</label>
                            <select class="form-control select2" id="tahun" name="tahun" style="width: 100%;" required >
                                <option value="">-- Semua Tahun --</option>
                                <?php foreach($tahun_list as $tahun): ?>
                                    <option value="<?php echo $tahun['tahun_anggaran']; ?>">
                                        <?php echo $tahun['tahun_anggaran']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="kode_dpsj">Kode DPSJ</label>
                            <select class="form-control select2-ajax" id="kode_dpsj_start" name="kode_dpsj_start" style="width: 100%;" required >
                                <option value="">-- Semua Kode DPSJ --</option>
                                <?php if(!empty($kode_dpsj_list)): ?>
                                    <?php foreach($kode_dpsj_list as $kode): ?>
                                        <option value="<?php echo $kode['kode_dpsj']; ?>">
                                            <?php echo $kode['kode_dpsj']; ?> - <?php echo $kode['deskripsi_dpsj']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="help-block">
                                <i class="fa fa-search text-info"></i> 
                                Ketik untuk mencari berdasarkan kode atau deskripsi DPSJ
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="kode_dpsj_end">Kode DPSJ</label>
                            <select class="form-control select2-ajax" id="kode_dpsj_end" name="kode_dpsj_end" style="width: 100%;" required >
                                <option value="">-- Semua Kode DPSJ --</option>
                                <?php if(!empty($kode_dpsj_list)): ?>
                                    <?php foreach($kode_dpsj_list as $kode): ?>
                                        <option value="<?php echo $kode['kode_dpsj']; ?>">
                                            <?php echo $kode['kode_dpsj']; ?> - <?php echo $kode['deskripsi_dpsj']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="help-block">
                                <i class="fa fa-search text-info"></i> 
                                Ketik untuk mencari berdasarkan kode atau deskripsi DPSJ
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Total Data:</label>
                                    <span id="totalData" class="badge bg-blue">0</span>
                                    <span id="loadingTotal" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted" id="filterInfo"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success btn-block btn-lg" id="exportBtn">
                            <i class="fa fa-file-excel-o"></i> Export ke Excel
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi</h3>
                </div>
                <div class="box-body">
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check-circle-o text-green"></i> Pilih tahun anggaran untuk filter</li>
                        <li><i class="fa fa-check-circle-o text-green"></i> Pilih kode DPSJ atau cari berdasarkan deskripsi</li>
                        <li><i class="fa fa-check-circle-o text-green"></i> Biarkan kosong untuk menampilkan semua data</li>
                        <li><i class="fa fa-check-circle-o text-green"></i> File akan diexport dalam format Excel (.xlsx)</li>
                        <li><i class="fa fa-check-circle-o text-green"></i> Fitur pencarian DPSJ dengan auto-complete</li>
                    </ul>
                </div>
            </div>
            
            <div id="loadingIndicator" class="text-center" style="display: none;">
                <div class="box box-solid box-primary">
                    <div class="box-body">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                        <h4>Sedang memproses export data...</h4>
                        <p class="text-muted">Mohon tunggu, proses export sedang berjalan</p>
                        <div class="progress progress-striped active">
                            <div class="progress-bar" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
<!-- /.content-wrapper -->

    <!-- Select2 CSS
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"> -->
    <!-- Select2 Bootstrap 3 Theme
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">   -->  
    <!-- Select2 (harus setelah jQuery)
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> -->
    <link rel="stylesheet" href="<?php echo base_url('assets/select2/css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/select2/css/select2-bootstrap.min.css'); ?>">
    <script src="<?php echo base_url('assets/select2/js/select2.min.js'); ?>"></script>

<script>
$(document).ready(function() {
    console.log('jQuery version:', $.fn.jquery);
    console.log('Select2 available:', typeof $.fn.select2 !== 'undefined');
    
    // Inisialisasi Select2 untuk Tahun
    if (typeof $.fn.select2 !== 'undefined') {
        $('#tahun').select2({
            placeholder: 'Pilih Tahun Anggaran',
            allowClear: true,
            theme: 'bootstrap'
        });
        console.log('Select2 untuk Tahun berhasil diinisialisasi');
        
        // Inisialisasi Select2 untuk Kode DPSJ dengan AJAX
        $('#kode_dpsj_start').select2({
            placeholder: 'Cari Kode atau Deskripsi DPSJ',
            allowClear: true,
            minimumInputLength: 0,
            theme: 'bootstrap',
            ajax: {
                url: '<?php echo base_url("export_rka_xlsx/search_kode_dpsj"); ?>',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    console.log('Response dari server:', data);
                    
                    // Pastikan response memiliki format yang benar
                    var results = [];
                    if (data && data.results && Array.isArray(data.results)) {
                        results = data.results;
                    } else if (data && Array.isArray(data)) {
                        // Jika response berupa array langsung
                        results = data.map(function(item) {
                            return {
                                id: item.kode_dpsj || item.id || '',
                                text: item.deskripsi_dpsj ? 
                                    item.kode_dpsj + ' - ' + item.deskripsi_dpsj : 
                                    item.kode_dpsj || item.text || '',
                                kode: item.kode_dpsj || item.id || '',
                                deskripsi: item.deskripsi_dpsj || ''
                            };
                        });
                    }
                    
                    // Filter hasil yang memiliki id valid
                    results = results.filter(function(item) {
                        return item.id && item.id !== '' && item.id !== 'undefined';
                    });
                    
                    console.log('Hasil yang diproses:', results);
                    
                    return {
                        results: results,
                        pagination: {
                            more: data.pagination ? data.pagination.more : false
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) { 
                return markup; 
            },
            templateResult: formatDPSJResult,
            templateSelection: formatDPSJSelection
        });

        // Inisialisasi Select2 untuk Kode DPSJ Akhir dengan AJAX
        $('#kode_dpsj_end').select2({
            placeholder: 'Cari Kode atau Deskripsi DPSJ',
            allowClear: true,
            minimumInputLength: 0,
            theme: 'bootstrap',
            ajax: {
                url: '<?php echo base_url("export_rka_xlsx/search_kode_dpsj"); ?>',
                dataType: 'json',
                delay: 300,
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    console.log('Response dari server:', data);
                    
                    // Pastikan response memiliki format yang benar
                    var results = [];
                    if (data && data.results && Array.isArray(data.results)) {
                        results = data.results;
                    } else if (data && Array.isArray(data)) {
                        // Jika response berupa array langsung
                        results = data.map(function(item) {
                            return {
                                id: item.kode_dpsj || item.id || '',
                                text: item.deskripsi_dpsj ? 
                                    item.kode_dpsj + ' - ' + item.deskripsi_dpsj : 
                                    item.kode_dpsj || item.text || '',
                                kode: item.kode_dpsj || item.id || '',
                                deskripsi: item.deskripsi_dpsj || ''
                            };
                        });
                    }
                    
                    // Filter hasil yang memiliki id valid
                    results = results.filter(function(item) {
                        return item.id && item.id !== '' && item.id !== 'undefined';
                    });
                    
                    console.log('Hasil yang diproses:', results);
                    
                    return {
                        results: results,
                        pagination: {
                            more: data.pagination ? data.pagination.more : false
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) { 
                return markup; 
            },
            templateResult: formatDPSJResult,
            templateSelection: formatDPSJSelection
        });

        console.log('Select2 untuk Kode DPSJ berhasil diinisialisasi');
    } else {
        console.error('Select2 tidak tersedia!');
    }
    
    // Format function untuk hasil pencarian
    function formatDPSJResult(repo) {
        if (repo.loading) {
            return repo.text || 'Loading...';
        }
        
        if (repo.id && repo.id !== '' && repo.id !== 'undefined') {
            var kode = repo.kode || repo.id || '';
            var deskripsi = repo.deskripsi || repo.text || '';
            // Hapus kode dari deskripsi jika ada
            deskripsi = deskripsi.replace(kode, '').replace(/^ - /, '').trim();
            
            var $container = $(
                '<div class="select2-result-dpsj">' +
                    '<div><strong>' + $('<div>').text(kode).html() + '</strong></div>' +
                    '<div><small>' + $('<div>').text(deskripsi).html() + '</small></div>' +
                '</div>'
            );
            return $container;
        }
        
        return repo.text || '-- Pilih Kode DPSJ --';
    }
    
    // Format function untuk item yang dipilih
    function formatDPSJSelection(repo) {
        if (repo.id && repo.id !== '' && repo.id !== 'undefined') {
            if (repo.kode && repo.deskripsi) {
                return repo.kode + ' - ' + repo.deskripsi;
            }
            return repo.text || repo.id;
        }
        return repo.text || '-- Semua Kode DPSJ --';
    }
    
    // Event handler untuk change
    $('#tahun, #kode_dpsj').on('change', function() {
        updateTotalData();
    });
    
    // Handle when option is selected
    $('#kode_dpsj').on('select2:select', function(e) {
        var data = e.params.data;
        if (data && data.id) {
            console.log('Selected:', data);
            $('#kode_dpsj').val(data.id).trigger('change');
        }
    });
    
    // Update total data
    function updateTotalData() {
        var tahun = $('#tahun').val();
        var kode_dpsj = $('#kode_dpsj').val();
        
        console.log('Update total data - Tahun:', tahun, 'Kode DPSJ:', kode_dpsj);
        
        $('#loadingTotal').show();
        $('#totalData').hide();
        
        $.ajax({
            url: '<?php echo base_url("export_rka_xlsx/get_total_data"); ?>',
            type: 'POST',
            data: {
                tahun: tahun,
                kode_dpsj: kode_dpsj
            },
            dataType: 'json',
            timeout: 30000,
            success: function(response) {
                console.log('Total data response:', response);
                var total = response.total || 0;
                $('#totalData').text(formatNumber(total));
                
                // Update filter info
                var info = [];
                if (tahun) info.push('Tahun: ' + tahun);
                if (kode_dpsj) info.push('DPSJ: ' + kode_dpsj);
                if (info.length === 0) info.push('Semua data');
                $('#filterInfo').text('Filter: ' + info.join(' | '));
            },
            error: function(xhr, status, error) {
                console.error('Error get total data:', error);
                console.log('Response:', xhr.responseText);
                $('#totalData').text('Error');
            },
            complete: function() {
                $('#loadingTotal').hide();
                $('#totalData').show();
            }
        });
    }
    
    // Format number with thousand separator
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Initial load
    setTimeout(updateTotalData, 1000);
    
    // Form submit handler
    $('#exportForm').on('submit', function(e) {
        var tahun = $('#tahun').val();
        var kode_dpsj = $('#kode_dpsj').val();
        var totalText = $('#totalData').text();
        var total = parseInt(totalText.replace(/\./g, '')) || 0;
        
        if (!tahun && !kode_dpsj) {
            if (!confirm('Anda akan mengekspor SEMUA data (' + formatNumber(total) + ' records). Lanjutkan?')) {
                e.preventDefault();
                return false;
            }
        }
        
        if (total > 10000) {
            if (!confirm('Data yang akan diexport berjumlah ' + formatNumber(total) + ' records. Proses mungkin memakan waktu lama. Lanjutkan?')) {
                e.preventDefault();
                return false;
            }
        }
        
        // Show loading
        $('#exportBtn').prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin"></i> Memproses Export...');
        $('#loadingIndicator').show();
        
        return true;
    });
});

console.log('Script selesai di-load');
</script>

<style>
.select2-result-dpsj {
    /* padding: 5px 0; */
}
.select2-result-dpsj strong {
    color: #3c8dbc;
}
.select2-result-dpsj small {
    color: #666;
}
.select2-container--default .select2-results__option--highlighted .select2-result-dpsj strong {
    color: #fff;
}
.select2-container--default .select2-results__option--highlighted .select2-result-dpsj small {
    color: #eee;
}
.progress {
    margin-top: 10px;
    height: 10px;
}
</style>