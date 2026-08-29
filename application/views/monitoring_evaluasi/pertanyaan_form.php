<!-- views/monitoring_evaluasi/pertanyaan_form.php -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-<?= isset($pertanyaan) ? 'pencil' : 'plus-circle' ?> text-orange"></i>
      <?= isset($pertanyaan) ? 'Edit' : 'Tambah' ?> Pertanyaan Monitoring
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi/pertanyaan') ?>">Kelola Pertanyaan</a></li>
      <li class="active"><?= isset($pertanyaan) ? 'Edit' : 'Tambah' ?> Pertanyaan</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-question-circle"></i>
              <?= isset($pertanyaan) ? 'Edit' : 'Form Tambah' ?> Pertanyaan
            </h3>
          </div>
          <form method="post" action="<?= isset($pertanyaan) ? site_url('monitoring_evaluasi/edit_pertanyaan/' . $pertanyaan->id) : site_url('monitoring_evaluasi/tambah_pertanyaan') ?>">
            <div class="box-body">
              <?= validation_errors('<div class="alert alert-danger">', '</div>') ?>

              <?php if (!isset($pertanyaan)): ?>
              <div class="form-group <?= form_error('kode') ? 'has-error' : '' ?>">
                <label><i class="fa fa-code"></i> Kode Pertanyaan <span class="text-red">*</span></label>
                <input type="text" name="kode" class="form-control"
                       placeholder="Contoh: P016" maxlength="10"
                       value="<?= set_value('kode') ?>" required>
                <span class="help-block text-muted">Kode unik untuk pertanyaan (maks. 10 karakter).</span>
                <?= form_error('kode', '<span class="help-block">', '</span>') ?>
              </div>
              <?php endif; ?>

              <div class="form-group <?= form_error('pertanyaan') ? 'has-error' : '' ?>">
                <label><i class="fa fa-question"></i> Teks Pertanyaan <span class="text-red">*</span></label>
                <textarea name="pertanyaan" class="form-control" rows="3"
                          placeholder="Apakah peneliti telah melakukan..."
                          required><?= set_value('pertanyaan', isset($pertanyaan) ? $pertanyaan->pertanyaan : '') ?></textarea>
                <span class="help-block text-muted">Gunakan kalimat tanya yang jelas dan dapat dijawab Ya/Tidak.</span>
                <?= form_error('pertanyaan', '<span class="help-block">', '</span>') ?>
              </div>

              <div class="form-group <?= form_error('kategori') ? 'has-error' : '' ?>">
                <label><i class="fa fa-tag"></i> Kategori <span class="text-red">*</span></label>
                <!--
                <input type="text" name="kategori" class="form-control"
                       placeholder="Contoh: Metodologi, Etika Penelitian, Pelaporan"
                       list="kategori-list"
                       value="<?= set_value('kategori', isset($pertanyaan) ? $pertanyaan->kategori : '') ?>" required>
                
                <datalist id="kategori-list">
                  <option value="Penyelenggaraan Administrasi">
                  <option value="Penyelenggaraan Penelitian">
                  <option value="Metodologi">
                  <option value="Etika Penelitian">
                  <option value="Pelaksanaan">
                  <option value="Pelaporan & Dokumentasi">
                  <option value="Luaran Penelitian">
                </datalist>-->
                
                <select name="kategori" class="form-control" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Penyelenggaraan Administrasi" <?= set_select('kategori', 'Penyelenggaraan Administrasi', isset($pertanyaan) && $pertanyaan->kategori == 'Penyelenggaraan Administrasi') ?>>Penyelenggaraan Administrasi</option>
                    <option value="Penyelenggaraan Penelitian" <?= set_select('kategori', 'Penyelenggaraan Penelitian', isset($pertanyaan) && $pertanyaan->kategori == 'Penyelenggaraan Penelitian') ?>>Penyelenggaraan Penelitian</option>
                    <option value="Metodologi" <?= set_select('kategori', 'Metodologi', isset($pertanyaan) && $pertanyaan->kategori == 'Metodologi') ?>>Metodologi</option>
                    <option value="Etika Penelitian" <?= set_select('kategori', 'Etika Penelitian', isset($pertanyaan) && $pertanyaan->kategori == 'Etika Penelitian') ?>>Etika Penelitian</option>
                    <option value="Pelaksanaan" <?= set_select('kategori', 'Pelaksanaan', isset($pertanyaan) && $pertanyaan->kategori == 'Pelaksanaan') ?>>Pelaksanaan</option>
                    <option value="Pelaporan & Dokumentasi" <?= set_select('kategori', 'Pelaporan & Dokumentasi', isset($pertanyaan) && $pertanyaan->kategori == 'Pelaporan & Dokumentasi') ?>>Pelaporan & Dokumentasi</option>
                    <option value="Luaran Penelitian" <?= set_select('kategori', 'Luaran Penelitian', isset($pertanyaan) && $pertanyaan->kategori == 'Luaran Penelitian') ?>>Luaran Penelitian</option>
                    <option value="Lain-lain" <?= set_select('kategori', 'Lain-lain', isset($pertanyaan) && $pertanyaan->kategori == 'Lain-lain') ?>>Lain-lain</option>
                </select>
                
                <?= form_error('kategori', '<span class="help-block">', '</span>') ?>
              </div>

              <div class="form-group <?= form_error('urutan') ? 'has-error' : '' ?>">
                <label><i class="fa fa-sort-numeric-asc"></i> Nomor Urut <span class="text-red">*</span></label>
                <input type="number" name="urutan" class="form-control" min="1"
                       value="<?= set_value('urutan', isset($pertanyaan) ? $pertanyaan->urutan : '') ?>" required>
                <span class="help-block text-muted">Menentukan urutan tampil dalam form monitoring.</span>
                <?= form_error('urutan', '<span class="help-block">', '</span>') ?>
              </div>

              <div class="callout callout-info">
                <p><i class="fa fa-lightbulb-o"></i>
                  <strong>Tips:</strong> Pertanyaan yang baik bersifat spesifik, dapat diverifikasi, dan menggunakan kata tanya yang
                  langsung merujuk pada rekomendasi reviewer. Contoh: <em>"Apakah Anda telah memperbaiki desain penelitian sesuai saran reviewer?"</em>
                </p>
              </div>
            </div>
            <div class="box-footer">
              <a href="<?= site_url('monitoring_evaluasi/pertanyaan') ?>" class="btn btn-default">
                <i class="fa fa-times"></i> Batal
              </a>
              <button type="submit" class="btn btn-warning pull-right">
                <i class="fa fa-save"></i> <?= isset($pertanyaan) ? 'Simpan Perubahan' : 'Tambah Pertanyaan' ?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
