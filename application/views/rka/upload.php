<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Upload RKA Excel</h3>
            </div>
            <div class="box-body">
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <h4><i class="icon fa fa-info"></i> Panduan Upload</h4>
                    <ul>
                        <li>Format file yang didukung: <strong>.xlsx</strong> atau <strong>.xls</strong></li>
                        <li>Maksimal ukuran file: <strong>10 MB</strong></li>
                        <li>Pastikan format kolom sesuai dengan template yang disediakan</li>
                        <li>Kolom wajib: Tahun Anggaran, Kode Kegiatan, dan Kode Akun</li>
                        <li>Download template terlebih dahulu untuk memudahkan pengisian data</li>
                    </ul>
                </div>

                <form action="<?= base_url('rka_upload/do_upload') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file_excel">Pilih File Excel</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls" required>
                        <p class="help-block">File harus berekstensi .xlsx atau .xls</p>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="btn-upload">
                            <i class="fa fa-upload"></i> Upload
                        </button>
                        <a href="<?= base_url('rka_upload') ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('rka_upload/download_template') ?>" class="btn btn-success">
                            <i class="fa fa-download"></i> Download Template
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi</h3>
            </div>
            <div class="box-body">
                <div class="callout callout-info">
                    <h4>Tips Pengisian Data</h4>
                    <ul>
                        <li>Kolom ID bisa dikosongkan (auto increment)</li>
                        <li>Angka bisa menggunakan format:
                            <ul>
                                <li>Tanpa pemisah: 48000000</li>
                                <li>Dengan titik: 48.000.000</li>
                                <li>Dengan koma: 48,000,000</li>
                            </ul>
                        </li>
                        <li>Tanggal format: YYYY-MM-DD HH:MM:SS</li>
                        <li>Kolom yang wajib diisi:
                            <ul>
                                <li><strong>Tahun Anggaran</strong></li>
                                <li><strong>Kode Kegiatan</strong></li>
                                <li><strong>Kode Akun</strong></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#file_excel').change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                         'application/vnd.ms-excel'];
        
        if (!validTypes.includes(fileType)) {
            $(this).val('');
            alert('Format file tidak didukung! Gunakan file .xlsx atau .xls');
        }
    });
    
    $('form').submit(function() {
        var fileInput = $('#file_excel');
        if (fileInput.val() === '') {
            alert('Silahkan pilih file terlebih dahulu!');
            return false;
        }
        $('#btn-upload').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
    });
});
</script>