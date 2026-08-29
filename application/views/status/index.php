<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $title ?>
            <small>Pengaturan Status Berdasarkan Peran</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar Status</h3>
                        <div class="box-tools">
                            <a href="<?= base_url('status/create') ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Tambah Status
                            </a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?= $this->session->flashdata('success') ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="10%">Kode</th>
                                        <th width="20%">Nama Status</th>
                                        <th width="25%">Keterangan</th>
                                        <?php foreach ($roles as $key => $role_name): ?>
                                            <th width="5%" class="text-center"><?= $role_name ?></th>
                                        <?php endforeach; ?>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($statuses as $status): ?>
                                        <tr>
                                            <td><?= $status['id'] ?></td>
                                            <td><?= $status['kode_status'] ?></td>
                                            <td><?= $status['nama_status'] ?></td>
                                            <td><?= $status['keterangan'] ?: '-' ?></td>
                                            
                                            <?php foreach ($roles as $key => $role_name): ?>
                                                <td class="text-center">
                                                    <?php if ($status[$key] == 1): ?>
                                                        <span class="text-success"><i class="fa fa-check-circle"></i></span>
                                                    <?php else: ?>
                                                        <span class="text-muted"><i class="fa fa-circle-o"></i></span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                            
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="<?= base_url('status/edit/' . $status['id']) ?>" 
                                                    class="btn btn-warning btn-xs" 
                                                    title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <!--<button type="button" 
                                                            class="btn btn-danger btn-xs" 
                                                            onclick="confirmDelete(<?= $status['id'] ?>)" 
                                                            title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </button>-->
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <?php 
                                        $totals = array();
                                        foreach ($roles as $key => $role_name) {
                                            $total = array_sum(array_column($statuses, $key));
                                            echo '<th class="text-center">' . $total . '</th>';
                                        }
                                        ?>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="callout callout-info">
                                    <h4><i class="fa fa-info-circle"></i> Informasi</h4>
                                    <p>
                                        <span class="text-success"><i class="fa fa-check-circle"></i></span> : Status tersedia untuk peran tersebut<br>
                                        <span class="text-muted"><i class="fa fa-circle-o"></i></span> : Status tidak tersedia untuk peran tersebut
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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
 
<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus status ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <a href="#" id="btn-delete" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(statusId) {
        var deleteUrl = "<?= base_url('status/delete/') ?>" + statusId;
        $('#btn-delete').attr('href', deleteUrl);
        $('#confirmDeleteModal').modal('show');
    }
</script>