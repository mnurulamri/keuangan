<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $title ?>
            <small>Edit Status</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?= base_url('status') ?>">Status</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Edit Status</h3>
                    </div>
                    <!-- /.box-header -->
                    <?= form_open('status/edit/' . $status['id']) ?>
                    <div class="box-body">
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?= validation_errors() ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_status">Kode Status *</label>
                                    <input type="number" class="form-control" id="kode_status" name="kode_status" 
                                        value="<?= set_value('kode_status', $status['kode_status']) ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nama_status">Nama Status *</label>
                                    <input type="text" class="form-control" id="nama_status" name="nama_status" 
                                        value="<?= set_value('nama_status', $status['nama_status']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" 
                                            rows="3"><?= set_value('keterangan', $status['keterangan']) ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Peran yang Memiliki Status Ini</h4>
                                <p class="text-muted">Centang peran yang memiliki akses ke status ini</p>
                                
                                <div class="row">
                                    <?php foreach ($roles as $key => $role_name): ?>
                                        <div class="col-md-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="<?= $key ?>" value="1" 
                                                        <?= ($status[$key] == 1) ? 'checked' : '' ?>>
                                                    <?= $role_name ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
                        </button>
                        <a href="<?= base_url('status') ?>" class="btn btn-default">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                    <?= form_close() ?>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content wrapper -->