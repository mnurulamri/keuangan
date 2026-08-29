<!-- views/monitoring_evaluasi/laporan.php -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-file-text-o text-blue"></i> Laporan Monitoring Evaluasi
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li class="active">Laporan</li>
    </ol>
  </section>

  <section class="content">
    <!-- Filter Penelitian -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Pilih Penelitian</h3>
          </div>
          <div class="box-body">
            <form method="get" action="<?= site_url('monitoring_evaluasi/laporan') ?>" id="form-filter">
              <div class="row">
                <div class="col-md-10">
                  <select name="penelitian_id" class="form-control select2" id="sel-penelitian">
                    <option value="">-- Pilih Penelitian --</option>
                    <?php foreach ($penelitian_list as $p): ?>
                    <option value="<?= $p->id ?>" <?= ($penelitian_id == $p->id) ? 'selected' : '' ?>>
                      [<?= htmlspecialchars($p->kd_pengajuan) ?>] <?= htmlspecialchars($p->judul_bhs_ind) ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-search"></i> Tampilkan
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Hasil Laporan -->
    <?php if ($penelitian): ?>
    <div class="row">
      <div class="col-md-12">
        <div class="callout callout-info">
          <h4><i class="fa fa-flask"></i> <?= htmlspecialchars($penelitian->judul) ?></h4>
          <p>
            <strong>Kode:</strong> <?= htmlspecialchars($penelitian->kode_penelitian) ?> &nbsp;|&nbsp;
            <strong>Tahun:</strong> <?= $penelitian->tahun ?> &nbsp;|&nbsp;
            <strong>Status:</strong> <span class="label label-<?= $penelitian->status === 'aktif' ? 'success' : 'default' ?>"><?= ucfirst($penelitian->status) ?></span>
          </p>
        </div>
      </div>
    </div>

    <?php if (empty($riwayat)): ?>
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-info text-center">
          <i class="fa fa-info-circle fa-2x"></i><br><br>
          Belum ada sesi monitoring untuk penelitian ini.
          <br><a href="<?= site_url('monitoring_evaluasi/buat_sesi') ?>" class="btn btn-success btn-sm" style="margin-top:8px">
            <i class="fa fa-plus"></i> Buat Sesi Monitoring
          </a>
        </div>
      </div>
    </div>
    <?php else: ?>

    <!-- Tabel Riwayat -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-history"></i> Riwayat Monitoring</h3>
          </div>
          <div class="box-body no-padding">
            <table class="table table-bordered table-condensed">
              <thead>
                <tr class="bg-gray-light">
                  <th width="5%">#</th>
                  <th>Periode</th>
                  <th width="14%">Tanggal</th>
                  <th width="12%" class="text-center">Status</th>
                  <th width="18%" class="text-center">Skor Kepatuhan</th>
                  <th width="10%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($riwayat as $r): ?>
                <?php
                  $skor = ($r->total_jawaban > 0) ? round(($r->total_ya / $r->total_jawaban) * 100) : 0;
                  $warna = $skor >= 80 ? 'success' : ($skor >= 50 ? 'warning' : 'danger');
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($r->periode) ?></td>
                  <td><?= date('d/m/Y', strtotime($r->tanggal_monitoring)) ?></td>
                  <td class="text-center">
                    <?php
                    $badges = [
                      'draft'       => '<span class="label label-default">Draft</span>',
                      'submitted'   => '<span class="label label-warning">Menunggu</span>',
                      'diverifikasi'=> '<span class="label label-success">Diverifikasi</span>',
                    ];
                    echo $badges[$r->status] ?? $r->status;
                    ?>
                  </td>
                  <td class="text-center">
                    <?php if ($r->total_jawaban > 0): ?>
                    <div class="progress progress-sm" style="margin:0 0 4px 0">
                      <div class="progress-bar progress-bar-<?= $warna ?>" style="width:<?= $skor ?>%"></div>
                    </div>
                    <small>
                      <strong class="text-<?= $warna ?>"><?= $skor ?>%</strong>
                      &nbsp;<span class="text-muted">(<?= $r->total_ya ?>/<?= $r->total_jawaban ?> Ya)</span>
                    </small>
                    <?php else: ?>
                    <span class="text-muted">Belum diisi</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="<?= site_url('monitoring_evaluasi/detail/' . $r->id) ?>"
                       class="btn btn-xs btn-info">
                      <i class="fa fa-eye"></i> Detail
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Grafik Tren Kepatuhan -->
    <?php
    $labels = [];
    $data_skor = [];
    foreach ($riwayat as $r) {
      $labels[] = $r->periode;
      $skor_r = ($r->total_jawaban > 0) ? round(($r->total_ya / $r->total_jawaban) * 100) : 0;
      $data_skor[] = $skor_r;
    }
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-line-chart"></i> Tren Kepatuhan Monitoring</h3>
          </div>
          <div class="box-body">
            <canvas id="chart-tren" height="80"></canvas>
          </div>
        </div>
      </div>
    </div>

    <script>
    $(document).ready(function() {
      var ctx = document.getElementById('chart-tren').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [{
            label: 'Skor Kepatuhan (%)',
            data : <?= json_encode($data_skor) ?>,
            borderColor    : '#00a65a',
            backgroundColor: 'rgba(0,166,90,0.12)',
            pointBackgroundColor: '#00a65a',
            pointRadius   : 5,
            borderWidth   : 2,
            fill          : true,
            tension       : 0.3,
          }]
        },
        options: {
          scales: {
            y: { min: 0, max: 100, ticks: { callback: v => v + '%' } }
          },
          plugins: {
            tooltip: { callbacks: { label: ctx => ctx.parsed.y + '%' } }
          }
        }
      });
    });
    </script>
    <?php endif; ?>
    <?php endif; ?>

  </section>
</div>

<script>
$(document).ready(function() {
  $('.select2').select2({ width: '100%' });
});
</script>
