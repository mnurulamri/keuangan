<!-- views/monitoring_evaluasi/dashboard.php -->
<!-- Content Wrapper -->
<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <h1>
      <i class="fa fa-bar-chart text-blue"></i> Monitoring Evaluasi Penelitian
      <small>Dashboard</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li class="active">Monitoring Evaluasi</li>
    </ol>
  </section>

  <!-- Main content -->
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

    <!-- Info Boxes -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-list-alt"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Sesi</span>
            <span class="info-box-number"><?= $statistik['total_sesi'] ?></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-pencil"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Draft</span>
            <span class="info-box-number"><?= $statistik['total_draft'] ?></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-orange"><i class="fa fa-send"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Menunggu Verifikasi</span>
            <span class="info-box-number"><?= $statistik['total_submitted'] ?></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Diverifikasi</span>
            <span class="info-box-number"><?= $statistik['total_verified'] ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-bolt"></i> Aksi Cepat</h3>
          </div>
          <div class="box-body">
            <a href="<?= site_url('monitoring_evaluasi/buat_sesi') ?>" class="btn btn-success btn-lg">
              <i class="fa fa-plus-circle"></i> Buat Sesi Monitoring Baru
            </a>
            &nbsp;
            <a href="<?= site_url('monitoring_evaluasi/sesi') ?>" class="btn btn-primary btn-lg">
              <i class="fa fa-list"></i> Lihat Semua Sesi
            </a>
            &nbsp;
            <a href="<?= site_url('monitoring_evaluasi/laporan') ?>" class="btn btn-info btn-lg">
              <i class="fa fa-file-text-o"></i> Laporan
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Sesi Terbaru -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Sesi Monitoring Terbaru</h3>
            <div class="box-tools pull-right">
              <a href="<?= site_url('monitoring_evaluasi/sesi') ?>" class="btn btn-sm btn-default">
                Lihat Semua <i class="fa fa-arrow-right"></i>
              </a>
            </div>
          </div>
          <div class="box-body no-padding">
            <table class="table table-hover table-condensed">
              <thead>
                <tr>
                  <th width="5%">#</th>
                  <th>Judul Penelitian</th>
                  <th>Periode</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($sesi_list)): ?>
                <tr>
                  <td colspan="6" class="text-center text-muted">
                    <i class="fa fa-info-circle"></i> Belum ada sesi monitoring.
                    <a href="<?= site_url('monitoring_evaluasi/buat_sesi') ?>">Buat sesi baru</a>
                  </td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach (array_slice($sesi_list, 0, 5) as $s): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <strong><?= htmlspecialchars($s->kd_pengajuan) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars(substr($s->judul_penelitian, 0, 60)) ?>...</small>
                  </td>
                  <td><?= htmlspecialchars($s->periode) ?></td>
                  <td><?= date('d M Y', strtotime($s->tanggal_monitoring)) ?></td>
                  <td><?= _badge_status($s->status) ?></td>
                  <td>
                    <?php if ($s->status === 'draft'): ?>
                    <a href="<?= site_url('monitoring_evaluasi/isi_form/' . $s->id) ?>" class="btn btn-xs btn-warning">
                      <i class="fa fa-pencil"></i> Isi Form
                    </a>
                    <?php else: ?>
                    <a href="<?= site_url('monitoring_evaluasi/detail/' . $s->id) ?>" class="btn btn-xs btn-info">
                      <i class="fa fa-eye"></i> Detail
                    </a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>

<?php
function _badge_status($status) {
    $map = [
        'draft'       => '<span class="label label-default"><i class="fa fa-pencil"></i> Draft</span>',
        'submitted'   => '<span class="label label-warning"><i class="fa fa-send"></i> Menunggu Verifikasi</span>',
        'diverifikasi'=> '<span class="label label-success"><i class="fa fa-check"></i> Diverifikasi</span>',
    ];
    return $map[$status] ?? '<span class="label label-default">'.$status.'</span>';
}
?>
