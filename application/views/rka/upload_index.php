<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Data RKA</h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url('rka_upload/upload') ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-upload"></i> Upload Excel
            </a>
            <a href="<?= base_url('rka_upload/download_template') ?>" class="btn btn-success btn-sm">
                <i class="fa fa-download"></i> Download Template
            </a>
            <a href="<?= base_url('rka_upload/delete_all') ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus semua data?')">
                <i class="fa fa-trash"></i> Hapus Semua
            </a>
        </div>
    </div>
    <div class="box-body">
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="table-rka">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Anggaran</th>
                        <th>Kode DPSJ</th>
                        <th>Deskripsi DPSJ</th>
                        <th>Kode Kegiatan</th>
                        <th>Nama Kegiatan Pendek</th>
                        <th>Kode Dana</th>
                        <th>Kode Akun</th>
                        <th>Anggaran</th>
                        <th>Komitmen</th>
                        <th>Aktual</th>
                        <th>Flag Payroll</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($rka as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row->tahun_anggaran ?></td>
                        <td><?= $row->kode_dpsj ?></td>
                        <td><?= substr($row->deskripsi_dpsj, 0, 30) ?>...</td>
                        <td><?= $row->kode_kegiatan ?></td>
                        <td><?= substr($row->nama_kegiatan_pendek, 0, 30) ?>...</td>
                        <td><?= $row->kode_dana ?></td>
                        <td><?= $row->kode_akun ?></td>
                        <td><?= number_format($row->anggaran, 0, ',', '.') ?></td>
                        <td><?= number_format($row->komitmen, 0, ',', '.') ?></td>
                        <td><?= number_format($row->aktual, 0, ',', '.') ?></td>
                        <td><?= $row->flag_payroll ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-6">
                <strong>Total Data: <?= count($rka) ?></strong>
            </div>
            <div class="col-md-6 text-right">
                <?php if(count($rka) > 0): ?>
                <small class="text-muted">Terakhir diupdate: <?= date('d-m-Y H:i:s') ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table-rka').DataTable({
        "order": [[0, "desc"]],
        "pageLength": 25,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>