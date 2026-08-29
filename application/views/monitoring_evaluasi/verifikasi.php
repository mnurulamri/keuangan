<!-- views/monitoring_evaluasi/verifikasi.php -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-check-square-o text-green"></i> Verifikasi Sesi Monitoring
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li class="active">Verifikasi</li>
    </ol>
  </section>
  <section class="content">
    <?php if (!$sesi): ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Sesi tidak ditemukan.</div>
    <?php else: ?>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="callout callout-info">
          <h4><i class="fa fa-flask"></i> <?= htmlspecialchars($sesi->judul_penelitian) ?></h4>
          <p>
            <strong>Periode:</strong> <?= htmlspecialchars($sesi->periode) ?> &nbsp;|&nbsp;
            <strong>Tanggal:</strong> <?= date('d F Y', strtotime($sesi->tanggal_monitoring)) ?>
          </p>
        </div>

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-check-circle"></i> Form Verifikasi Reviewer</h3>
          </div>
          <form method="post" action="<?= site_url('monitoring_evaluasi/verifikasi/' . $sesi->id) ?>">
            <div class="box-body">

              <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                Silakan tinjau jawaban peneliti melalui halaman detail, kemudian berikan catatan verifikasi dan konfirmasi di bawah ini.
                <br><a href="<?= site_url('monitoring_evaluasi/detail/' . $sesi->id) ?>" target="_blank" class="btn btn-xs btn-info" style="margin-top:6px">
                  <i class="fa fa-eye"></i> Lihat Detail Jawaban (Tab Baru)
                </a>
              </div>

              <div class="form-group">
                <label><i class="fa fa-comment"></i> Catatan Reviewer</label>
                <textarea name="catatan_reviewer" class="form-control" rows="5"
                          placeholder="Tuliskan hasil verifikasi, temuan, atau rekomendasi tindak lanjut kepada peneliti..."
                ><?= set_value('catatan_reviewer') ?></textarea>
              </div>

              <div class="callout callout-warning">
                <p><i class="fa fa-exclamation-triangle"></i>
                  Dengan mengklik <strong>Verifikasi & Setujui</strong>, Anda menyatakan telah meninjau
                  seluruh jawaban monitoring dan menyetujui laporan sesi ini.
                </p>
              </div>
            </div>
            <div class="box-footer">
              <a href="<?= site_url('monitoring_evaluasi/sesi') ?>" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Kembali
              </a>
              <button type="submit" class="btn btn-success pull-right">
                <i class="fa fa-check-circle"></i> Verifikasi & Setujui
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>
</div>
