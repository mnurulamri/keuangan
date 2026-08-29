<!-- application/views/mutasi/index.php -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $title; ?>
            <small>Daftar Mutasi Anggaran</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Mutasi</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Mutasi</h3>
                        <div class="box-tools">
                            <a href="<?php echo site_url('mutasi/create'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Tambah Mutasi
                            </a>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                        <?php endif; ?>
                        
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No. Bukti</th>
                                    <th>Kode Kegiatan</th>
                                    <th>Kode Akun</th>
                                    <th>Deskripsi Akun</th>
                                    <th>Mutasi</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($mutasi as $row): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row->tanggal)); ?></td>
                                    <td><?php echo $row->no_bukti; ?></td>
                                    <td><?php echo $row->kode_kegiatan; ?></td>
                                    <td><?php echo $row->kode_akun; ?></td>
                                    <td><?php echo $row->deskripsi_akun; ?></td>
                                    <td class="<?php echo $row->mutasi < 0 ? 'text-danger' : 'text-success'; ?>">
                                        Rp <?php echo number_format($row->mutasi, 2, ',', '.'); ?>
                                    </td>
                                    <td><?php echo $row->keterangan; ?></td>
                                    <td>
                                        <a href="<?php echo site_url('mutasi/edit/' . $row->id); ?>" class="btn btn-warning btn-xs">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="confirmDelete(<?php echo $row->id; ?>)">
                                            <i class="fa fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="pull-right">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus mutasi ini?')) {
        window.location.href = '<?php echo site_url("mutasi/delete/"); ?>' + id;
    }
}
</script>