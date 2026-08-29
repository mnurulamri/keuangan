<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $title; ?>
            <small>Edit Data Anggaran</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('RKA'); ?>">Anggaran</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Form Edit Data</h3>
            </div>
            
            <form role="form" method="POST" action="<?php echo base_url('RKA/update/'.$anggaran->id); ?>" id="form-anggaran">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun Anggaran <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="tahun_anggaran" value="<?php echo $anggaran->tahun_anggaran; ?>" required>
                                <?php echo form_error('tahun_anggaran', '<small class="text-danger">', '</small>'); ?>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode DPSJ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_dpsj" value="<?php echo $anggaran->kode_dpsj; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Deskripsi DPSJ</label>
                                <textarea class="form-control" name="deskripsi_dpsj" rows="2"><?php echo $anggaran->deskripsi_dpsj; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Kode Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_kegiatan" value="<?php echo $anggaran->kode_kegiatan; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Nama Kegiatan Pendek</label>
                                <input type="text" class="form-control" name="nama_kegiatan_pendek" value="<?php echo $anggaran->nama_kegiatan_pendek; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Nama Kegiatan</label>
                                <textarea class="form-control" name="nama_kegiatan" rows="2"><?php echo $anggaran->nama_kegiatan; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Dana</label>
                                <input type="text" class="form-control" name="kode_dana" value="<?php echo $anggaran->kode_dana; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Kode Akun <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kode_akun" value="<?php echo $anggaran->kode_akun; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Deskripsi Akun</label>
                                <textarea class="form-control" name="deskripsi_akun" rows="2"><?php echo $anggaran->deskripsi_akun; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Anggaran</label>
                                <input type="text" class="form-control currency" name="anggaran" value="<?php echo number_format($anggaran->anggaran, 0, ',', '.'); ?>">
                            </div>
                            
                            <div class="form-group" style="display:none;">
                                <label>Komitmen</label>
                                <input type="text" class="form-control currency" name="komitmen" value="0">
                            </div>

                            <!-- Tambahan Field Flag Payroll di Edit -->
                            <div class="form-group">
                                <label>Flag Payroll</label>
                                <select class="form-control" name="flag_payroll" id="flag_payroll">
                                    <option value="">-- Pilih Flag Payroll --</option>
                                    <option value="Procost Unit" <?php echo (isset($anggaran->flag_payroll) && $anggaran->flag_payroll == 'Procost Unit') ? 'selected' : ''; ?>>Procost Unit</option>
                                    <option value="Procost Umum" <?php echo (isset($anggaran->flag_payroll) && $anggaran->flag_payroll == 'Procost Umum') ? 'selected' : ''; ?>>Procost Umum</option>
                                    <option value="Procost" <?php echo (isset($anggaran->flag_payroll) && $anggaran->flag_payroll == 'Procost Remun') ? 'selected' : ''; ?>>Procost Remun</option>
                                </select>
                                <small class="text-muted">Pilih jenis Procost</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="box-footer">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Update</button>
                    <a href="<?php echo base_url('RKA'); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- jQuery untuk format currency -->
<script>
$(document).ready(function() {
    // Format currency
    $('.currency').on('keyup', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value !== '') {
            $(this).val(parseInt(value).toLocaleString('id-ID'));
        }
    });
    
    // Saat form disubmit, ubah format currency ke angka biasa
    $('#form-anggaran').submit(function() {
        $('.currency').each(function() {
            var value = $(this).val().replace(/\./g, '');
            $(this).val(value);
        });
    });
});
</script>