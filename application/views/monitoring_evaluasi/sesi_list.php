<!-- views/monitoring_evaluasi/sesi_list.php -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt text-blue"></i> Daftar Sesi Monitoring
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li class="active">Daftar Sesi</li>
    </ol>
  </section>

  <section class="content">
    <?php if ($flash_success): ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <i class="fa fa-check-circle"></i> <?= $flash_success ?>
    </div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <i class="fa fa-exclamation-triangle"></i> <?= $flash_error ?>
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-table"></i> Semua Sesi Monitoring</h3>
            <div class="box-tools pull-right">
              <a href="<?= site_url('monitoring_evaluasi/buat_sesi') ?>" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Buat Sesi Baru
              </a>
            </div>
          </div>
          <div class="box-body">
            <table id="tbl-sesi" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th width="5%">#</th>
                  <th>Penelitian</th>
                  <th width="12%">Periode</th>
                  <th width="12%">Tanggal</th>
                  <th width="15%" class="text-center">Status</th>
                  <th width="18%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($sesi_list)): ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">
                    <i class="fa fa-info-circle fa-2x"></i><br>
                    Belum ada sesi monitoring.
                    <a href="<?= site_url('monitoring_evaluasi/buat_sesi') ?>">Buat sesi baru sekarang.</a>
                  </td>
                </tr>
                <?php else: $no = 1; foreach ($sesi_list as $s): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <strong><?= htmlspecialchars($s->kd_pengajuan) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars(mb_strimwidth($s->judul_penelitian, 0, 70, '...')) ?></small>
                  </td>
                  <td><?= htmlspecialchars($s->periode) ?></td>
                  <td><?= date('d/m/Y', strtotime($s->tanggal_monitoring)) ?></td>
                  <td class="text-center">
                    <?php
                    $badges = [
                      'draft'       => '<span class="label label-default"><i class="fa fa-pencil"></i> Draft</span>',
                      'submitted'   => '<span class="label label-warning"><i class="fa fa-send"></i> Menunggu Verifikasi</span>',
                      'diverifikasi'=> '<span class="label label-success"><i class="fa fa-check"></i> Diverifikasi</span>',
                    ];
                    echo $badges[$s->status] ?? $s->status;
                    ?>
                  </td>
                  <td class="text-center">
                    <?php if ($s->status === 'draft'): ?>
                      <a href="<?= site_url('monitoring_evaluasi/isi_form/' . $s->id) ?>"
                         class="btn btn-xs btn-warning" title="Isi Form">
                        <i class="fa fa-pencil"></i> Isi Form
                      </a>
                      <a href="<?= site_url('monitoring_evaluasi/hapus_sesi/' . $s->id) ?>"
                         class="btn btn-xs btn-danger btn-hapus" title="Hapus">
                        <i class="fa fa-trash"></i>
                      </a>
                    <?php else: ?>
                      <a href="<?= site_url('monitoring_evaluasi/detail/' . $s->id) ?>"
                         class="btn btn-xs btn-info" title="Lihat Detail">
                        <i class="fa fa-eye"></i> Detail
                      </a>
                      <?php if ($s->status === 'submitted'): ?>
                      <a href="<?= site_url('monitoring_evaluasi/verifikasi/' . $s->id) ?>"
                         class="btn btn-xs btn-success" title="Verifikasi">
                        <i class="fa fa-check"></i> Verifikasi
                      </a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>

<script>
$(document).ready(function() {
  $('#tbl-sesi').DataTable({
    "paging"   : true,
    "ordering" : true,
    "info"     : true,
    "searching": true,
    "language" : {
      "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
    }
  });

  // Konfirmasi hapus
  $(document).on('click', '.btn-hapus', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    Swal.fire({
      title: 'Hapus Sesi?',
      text : 'Sesi draft ini akan dihapus permanen.',
      icon : 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor : '#3085d6',
      confirmButtonText : 'Ya, Hapus',
      cancelButtonText  : 'Batal'
    }).then(function(result) {
      if (result.isConfirmed) window.location.href = url;
    });
  });
});
</script>
