<!-- views/monitoring_evaluasi/sesi_form.php -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-plus-circle text-green"></i> Buat Sesi Monitoring Baru
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi/sesi') ?>">Daftar Sesi</a></li>
      <li class="active">Buat Sesi Baru</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-edit"></i> Data Sesi Monitoring</h3>
          </div>
          <form method="post" action="<?= site_url('monitoring_evaluasi/buat_sesi') ?>">
            <div class="box-body">

              <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

              <div class="form-group <?= form_error('penelitian_id') ? 'has-error' : '' ?>">
                <label><i class="fa fa-flask"></i> Pilih Penelitian <span class="text-red">*</span></label>
                <select name="penelitian_id" class="form-control select2" required>
                  <option value="">-- Pilih Penelitian --</option>
                  <?php foreach ($penelitian as $p): ?>
                  <option value="<?= $p->id ?>" <?= set_select('kd_pengajuan', $p->id) ?>>
                    [<?= htmlspecialchars($p->kd_pengajuan) ?>] <?= htmlspecialchars($p->judul_bhs_ind) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
                <?= form_error('penelitian_id', '<span class="help-block">', '</span>') ?>
              </div>

              <div class="form-group <?= form_error('periode') ? 'has-error' : '' ?>">
                <label><i class="fa fa-calendar"></i> Periode Monitoring <span class="text-red">*</span></label>
                <input type="text" name="periode" class="form-control"
                       placeholder="Contoh: Bulan 1, Kuartal 2, Semester I 2025"
                       value="<?= set_value('periode') ?>" required>
                <span class="help-block text-muted">Tuliskan periode sesuai jadwal monitoring penelitian Anda.</span>
                <?= form_error('periode', '<span class="help-block">', '</span>') ?>
              </div>

              <div class="form-group <?= form_error('tanggal_monitoring') ? 'has-error' : '' ?>">
                <label><i class="fa fa-calendar-check-o"></i> Tanggal Monitoring <span class="text-red">*</span></label>
                <div class="input-group date" id="datepicker-wrapper">
                  <input type="text" name="tanggal_monitoring" class="form-control datepicker"
                         placeholder="yyyy-mm-dd" id="tanggal_monitoring"
                         value="<?= set_value('tanggal_monitoring', date('Y-m-d')) ?>" required>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <?= form_error('tanggal_monitoring', '<span class="help-block">', '</span>') ?>
              </div>

              <div class="form-group">
                <label><i class="fa fa-comment-o"></i> Catatan Awal (Opsional)</label>
                <textarea name="catatan_peneliti" class="form-control" rows="3"
                          placeholder="Tuliskan catatan awal atau konteks monitoring ini..."
                ><?= set_value('catatan_peneliti') ?></textarea>
              </div>

              <div class="callout callout-info">
                <p><i class="fa fa-info-circle"></i>
                  Setelah membuat sesi, Anda akan diarahkan ke <strong>form isian monitoring</strong>
                  berisi daftar pertanyaan yang perlu dijawab.
                </p>
              </div>

            </div>
            <div class="box-footer">
              <a href="<?= site_url('monitoring_evaluasi/sesi') ?>" class="btn btn-default">
                <i class="fa fa-times"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary pull-right">
                <i class="fa fa-arrow-right"></i> Buat Sesi & Isi Form
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
$(document).ready(function() {
  $('.select2').select2({ width: '100%' });

  $('.datepicker').datepicker({
    autoclose: true,
    format   : 'yyyy-mm-dd',
    todayBtn : 'linked',
    language : 'id'
  });
});
</script>
