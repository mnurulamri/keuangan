<!-- views/monitoring_evaluasi/detail.php -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-file-text-o text-blue"></i> Detail Monitoring Evaluasi
      <small><?= htmlspecialchars($sesi->periode ?? '') ?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi/sesi') ?>">Daftar Sesi</a></li>
      <li class="active">Detail</li>
    </ol>
  </section>

  <section class="content">

    <?php if ($flash_success): ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <i class="fa fa-check-circle"></i> <?= $flash_success ?>
    </div>
    <?php endif; ?>

    <?php if (!$sesi): ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Sesi tidak ditemukan.</div>
    <?php else: ?>

    <!-- Info Sesi -->
    <div class="row">
      <div class="col-md-8">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-info-circle"></i> Informasi Sesi</h3>
          </div>
          <div class="box-body">
            <table class="table table-condensed table-borderless">
              <tr>
                <td width="35%"><strong>Judul Penelitian</strong></td>
                <td><?= htmlspecialchars($sesi->judul_penelitian) ?></td>
              </tr>
              <tr>
                <td><strong>Kode Penelitian</strong></td>
                <td><code><?= htmlspecialchars($sesi->kd_pengajuan) ?></code></td>
              </tr>
              <tr>
                <td><strong>Periode Monitoring</strong></td>
                <td><?= htmlspecialchars($sesi->periode) ?></td>
              </tr>
              <tr>
                <td><strong>Tanggal Monitoring</strong></td>
                <td><?= date('d F Y', strtotime($sesi->tanggal_monitoring)) ?></td>
              </tr>
              <tr>
                <td><strong>Status</strong></td>
                <td>
                  <?php
                  $badge = [
                    'draft'       => '<span class="label label-default"><i class="fa fa-pencil"></i> Draft</span>',
                    'submitted'   => '<span class="label label-warning"><i class="fa fa-send"></i> Menunggu Verifikasi</span>',
                    'diverifikasi'=> '<span class="label label-success"><i class="fa fa-check-circle"></i> Diverifikasi</span>',
                  ];
                  echo $badge[$sesi->status] ?? $sesi->status;
                  ?>
                </td>
              </tr>
              <?php if ($sesi->verified_at): ?>
              <tr>
                <td><strong>Diverifikasi Pada</strong></td>
                <td><?= date('d F Y H:i', strtotime($sesi->verified_at)) ?></td>
              </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>

      <!-- Rekapitulasi -->
      <div class="col-md-4">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-pie-chart"></i> Rekapitulasi</h3>
          </div>
          <div class="box-body">
            <?php if ($rekapitulasi && $rekapitulasi->total > 0):
              $pct_ya    = round(($rekapitulasi->total_ya / $rekapitulasi->total) * 100);
              $pct_tidak = round(($rekapitulasi->total_tidak / $rekapitulasi->total) * 100);
              $pct_na    = round(($rekapitulasi->total_tidak_berlaku / $rekapitulasi->total) * 100);
            ?>
            <div class="description-block border-right text-center" style="margin-bottom:10px">
              <span class="description-percentage text-green" style="font-size:2.5em; font-weight:bold"><?= $pct_ya ?>%</span>
              <h5 class="description-header" style="font-size:1.3em"><?= $rekapitulasi->total_ya ?> / <?= $rekapitulasi->total ?></h5>
              <span class="description-text text-muted">Pertanyaan dijawab <strong>Ya</strong></span>
            </div>

            <!-- Progress bar visual -->
            <div class="progress progress-sm" title="Ya: <?= $rekapitulasi->total_ya ?> | Tidak: <?= $rekapitulasi->total_tidak ?> | N/A: <?= $rekapitulasi->total_tidak_berlaku ?>">
              <div class="progress-bar progress-bar-success" style="width:<?= $pct_ya ?>%"></div>
              <div class="progress-bar progress-bar-danger"  style="width:<?= $pct_tidak ?>%"></div>
              <div class="progress-bar progress-bar-default" style="width:<?= $pct_na ?>%"></div>
            </div>
            <div class="row text-center" style="margin-top:8px">
              <div class="col-xs-4">
                <span class="badge bg-green"><?= $rekapitulasi->total_ya ?></span><br>
                <small>Ya</small>
              </div>
              <div class="col-xs-4">
                <span class="badge bg-red"><?= $rekapitulasi->total_tidak ?></span><br>
                <small>Tidak</small>
              </div>
              <div class="col-xs-4">
                <span class="badge bg-gray"><?= $rekapitulasi->total_tidak_berlaku ?></span><br>
                <small>N/A</small>
              </div>
            </div>
            <?php else: ?>
            <p class="text-muted text-center"><i class="fa fa-info-circle"></i> Belum ada jawaban.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Rekap Per Kategori -->
    <?php if (!empty($rekap_kategori)): ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default box-solid">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-table"></i> Rekap Per Kategori</h3>
          </div>
          <div class="box-body no-padding">
            <table class="table table-bordered table-condensed">
              <thead>
                <tr class="bg-gray">
                  <th>Kategori</th>
                  <th class="text-center">Total</th>
                  <th class="text-center text-green">Ya</th>
                  <th class="text-center text-red">Tidak</th>
                  <th class="text-center text-muted">N/A</th>
                  <th class="text-center">Skor</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rekap_kategori as $rk): ?>
                <?php $skor = $rk->total > 0 ? round(($rk->total_ya / $rk->total) * 100) : 0; ?>
                <tr>
                  <td><i class="fa fa-tag"></i> <?= htmlspecialchars($rk->kategori) ?></td>
                  <td class="text-center"><?= $rk->total ?></td>
                  <td class="text-center"><span class="badge bg-green"><?= $rk->total_ya ?></span></td>
                  <td class="text-center"><span class="badge bg-red"><?= $rk->total_tidak ?></span></td>
                  <td class="text-center"><span class="badge bg-gray"><?= $rk->total_tidak_berlaku ?></span></td>
                  <td class="text-center">
                    <div class="progress progress-xs" style="margin:0">
                      <div class="progress-bar <?= $skor >= 80 ? 'progress-bar-success' : ($skor >= 50 ? 'progress-bar-warning' : 'progress-bar-danger') ?>"
                           style="width:<?= $skor ?>%"></div>
                    </div>
                    <small><?= $skor ?>%</small>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Detail Jawaban Per Kategori -->
    <?php foreach ($jawaban_grouped as $kategori => $jawaban_list): ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-default">
          <div class="box-header with-border bg-gray-light">
            <h3 class="box-title"><i class="fa fa-tag text-blue"></i> <?= htmlspecialchars($kategori) ?></h3>
          </div>
          <div class="box-body no-padding">
            <table class="table table-bordered table-condensed">
              <thead>
                <tr>
                  <th width="5%" class="text-center">Kode</th>
                  <th>Pertanyaan</th>
                  <th width="15%" class="text-center">Jawaban</th>
                  <th width="25%">Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($jawaban_list as $j): ?>
                <tr>
                  <td class="text-center"><code><?= htmlspecialchars($j->kode) ?></code></td>
                  <td><?= htmlspecialchars($j->pertanyaan) ?></td>
                  <td class="text-center">
                    <?php if ($j->jawaban === 'ya'): ?>
                      <span class="label label-success label-jawaban">
                        <i class="fa fa-check-circle"></i> Ya
                      </span>
                    <?php elseif ($j->jawaban === 'tidak'): ?>
                      <span class="label label-danger label-jawaban">
                        <i class="fa fa-times-circle"></i> Tidak
                      </span>
                    <?php else: ?>
                      <span class="label label-default label-jawaban">
                        <i class="fa fa-minus-circle"></i> N/A
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="text-muted"><small><?= $j->keterangan ? htmlspecialchars($j->keterangan) : '-' ?></small></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Catatan -->
    <?php if ($sesi->catatan_peneliti || $sesi->catatan_reviewer): ?>
    <div class="row">
      <?php if ($sesi->catatan_peneliti): ?>
      <div class="col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user"></i> Catatan Peneliti</h3>
          </div>
          <div class="box-body">
            <p><?= nl2br(htmlspecialchars($sesi->catatan_peneliti)) ?></p>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php if ($sesi->catatan_reviewer): ?>
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-check-square-o"></i> Catatan Reviewer</h3>
          </div>
          <div class="box-body">
            <p><?= nl2br(htmlspecialchars($sesi->catatan_reviewer)) ?></p>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Tombol Aksi -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid">
          <div class="box-body">
            <a href="<?= site_url('monitoring_evaluasi/sesi') ?>" class="btn btn-default">
              <i class="fa fa-arrow-left"></i> Kembali ke Daftar Sesi
            </a>
            <?php if ($sesi->status === 'submitted'): ?>
            <a href="<?= site_url('monitoring_evaluasi/verifikasi/' . $sesi->id) ?>"
               class="btn btn-success pull-right">
              <i class="fa fa-check-circle"></i> Verifikasi Sesi Ini
            </a>
            <?php endif; ?>
            <?php if ($sesi->status === 'draft'): ?>
            <a href="<?= site_url('monitoring_evaluasi/isi_form/' . $sesi->id) ?>"
               class="btn btn-warning pull-right">
              <i class="fa fa-pencil"></i> Edit Form
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <?php endif; ?>
  </section>
</div>

<style>
.label-jawaban { font-size: 13px; padding: 5px 12px; border-radius: 12px; }
table.table-condensed td { vertical-align: middle !important; }
</style>
