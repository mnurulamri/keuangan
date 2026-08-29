<!-- views/monitoring_evaluasi/isi_form.php 
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-check-square-o text-green"></i> Form Monitoring Evaluasi
      <small><?= htmlspecialchars($sesi->periode) ?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi/sesi') ?>">Daftar Sesi</a></li>
      <li class="active">Isi Form Monitoring</li>
    </ol>
  </section>

  <section class="content">
-->
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

    <!-- Info Penelitian -->
    <div class="row">
      <div class="col-md-12">
        <div class="callout callout-info">
          <h4><i class="fa fa-flask"></i> <?= htmlspecialchars($sesi->judul_penelitian) ?></h4>
          <p>
            <strong>Kode:</strong> <?= htmlspecialchars($sesi->kd_pengajuan) ?> &nbsp;|&nbsp;
            <strong>Periode:</strong> <?= htmlspecialchars($sesi->periode) ?> &nbsp;|&nbsp;
            <strong>Tanggal:</strong> <?= date('d M Y', strtotime($sesi->tanggal_monitoring)) ?> &nbsp;|&nbsp;
            <strong>Status:</strong>
            <?php if ($sesi->status === 'draft'): ?>
              <span class="label label-default"><i class="fa fa-pencil"></i> Draft</span>
            <?php elseif ($sesi->status === 'submitted'): ?>
              <span class="label label-warning"><i class="fa fa-send"></i> Menunggu Verifikasi</span>
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Petunjuk -->
    <div class="row">
      <div class="col-md-12">
        <div class="callout callout-warning">
          <h4><i class="fa fa-info-circle"></i> Petunjuk Pengisian</h4>
          <ul class="margin-bottom-none">
            <li>Jawab setiap pertanyaan dengan memilih <strong>Ya</strong>, <strong>Tidak</strong>, atau <strong>Tidak Berlaku</strong>.</li>
            <li>Pertanyaan ini untuk memastikan Anda telah melaksanakan rekomendasi reviewer.</li>
            <li>Isi kolom <em>keterangan</em> jika diperlukan penjelasan tambahan.</li>
            <li>Klik <strong>Simpan Draft</strong> untuk menyimpan sementara, atau <strong>Kirim ke Reviewer</strong> bila sudah selesai.</li>
          </ul>
        </div>
      </div>
    </div>

    <?php if ($sesi->status === 'submitted'): ?>
    <div class="alert alert-warning">
      <i class="fa fa-lock"></i> Form ini telah dikirimkan dan sedang menunggu verifikasi reviewer. Form tidak dapat diedit.
      <a href="<?= site_url('monitoring_evaluasi/detail/' . $sesi->id) ?>" class="btn btn-xs btn-info pull-right">
        <i class="fa fa-eye"></i> Lihat Detail
      </a>
    </div>
    <?php else: ?>

    <form id="form-monitoring" method="post" action="<?= site_url('monitoring_evaluasi/isi_form/' . $sesi->id) ?>">
      <input type="hidden" name="action" id="form_action" value="draft">

      <!-- Progress bar -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid box-default">
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <strong>Progress Pengisian:</strong>
                  <div class="progress progress-sm">
                    <div class="progress-bar progress-bar-green" id="progress-bar" role="progressbar" style="width: 0%"></div>
                  </div>
                </div>
                <div class="col-md-4 text-right">
                  <span id="progress-text" class="text-muted">0 dari <?= array_sum(array_map('count', $pertanyaan_grouped)) ?> pertanyaan dijawab</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php
      $no_global = 1;
      foreach ($pertanyaan_grouped as $kategori => $pertanyaan_list):
      ?>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-tag"></i> <?= htmlspecialchars($kategori) ?>
              </h3>
              <div class="box-tools pull-right">
                <span class="badge bg-blue"><?= count($pertanyaan_list) ?> pertanyaan</span>
              </div>
            </div>
            <div class="box-body no-padding">
              <table class="table table-bordered table-monitoring">
                <thead class="bg-gray">
                  <tr>
                    <th width="4%" class="text-center">No</th>
                    <th width="5%" class="text-center text-muted">Kode</th>
                    <th>Pertanyaan Monitoring</th>
                    <th width="28%" class="text-center">Jawaban</th>
                    <th width="20%">Keterangan (opsional)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($pertanyaan_list as $p): ?>
                  <?php $existing = isset($jawaban_map[$p->id]) ? $jawaban_map[$p->id] : NULL; ?>
                  <tr class="pertanyaan-row" data-id="<?= $p->id ?>">
                    <td class="text-center"><?= $no_global++ ?></td>
                    <td class="text-center"><code><?= htmlspecialchars($p->kode) ?></code></td>
                    <td>
                      <?= htmlspecialchars($p->pertanyaan) ?>
                    </td>
                    <td class="text-center">
                      <div class="jawaban-group">
                        <!-- Tombol Ya -->
                        <label class="btn-jawaban <?= ($existing && $existing->jawaban === 'ya') ? 'active-ya' : '' ?>"
                               for="j_ya_<?= $p->id ?>">
                          <input type="radio"
                                 name="jawaban[<?= $p->id ?>][jawaban]"
                                 id="j_ya_<?= $p->id ?>"
                                 value="ya"
                                 class="jawaban-radio"
                                 data-row="<?= $p->id ?>"
                                 <?= ($existing && $existing->jawaban === 'ya') ? 'checked' : '' ?>>
                          <i class="fa fa-check-circle"></i> Ya
                        </label>

                        <!-- Tombol Tidak -->
                        <label class="btn-jawaban <?= ($existing && $existing->jawaban === 'tidak') ? 'active-tidak' : '' ?>"
                               for="j_tidak_<?= $p->id ?>">
                          <input type="radio"
                                 name="jawaban[<?= $p->id ?>][jawaban]"
                                 id="j_tidak_<?= $p->id ?>"
                                 value="tidak"
                                 class="jawaban-radio"
                                 data-row="<?= $p->id ?>"
                                 <?= ($existing && $existing->jawaban === 'tidak') ? 'checked' : '' ?>>
                          <i class="fa fa-times-circle"></i> Tidak
                        </label>

                        <!-- Tombol Tidak Berlaku -->
                        <label class="btn-jawaban <?= ($existing && $existing->jawaban === 'tidak_berlaku') ? 'active-na' : '' ?>"
                               for="j_na_<?= $p->id ?>">
                          <input type="radio"
                                 name="jawaban[<?= $p->id ?>][jawaban]"
                                 id="j_na_<?= $p->id ?>"
                                 value="tidak_berlaku"
                                 class="jawaban-radio"
                                 data-row="<?= $p->id ?>"
                                 <?= ($existing && $existing->jawaban === 'tidak_berlaku') ? 'checked' : '' ?>>
                          <i class="fa fa-minus-circle"></i> N/A
                        </label>
                      </div>
                    </td>
                    <td>
                      <input type="text"
                             name="jawaban[<?= $p->id ?>][keterangan]"
                             class="form-control input-sm"
                             placeholder="Penjelasan..."
                             value="<?= $existing ? htmlspecialchars($existing->keterangan) : '' ?>">
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Catatan Peneliti -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-comments-o"></i> Catatan Tambahan Peneliti</h3>
            </div>
            <div class="box-body">
              <textarea name="catatan_peneliti" class="form-control" rows="4"
                        placeholder="Tuliskan kendala, catatan, atau informasi tambahan yang perlu diketahui reviewer..."
              ><?= htmlspecialchars($sesi->catatan_peneliti ?? '') ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Tombol Aksi -->
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <a href="<?= site_url('monitoring_evaluasi/sesi') ?>" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali
                  </a>
                </div>
                <div class="col-md-6 text-right">
                  <button type="button" class="btn btn-default btn-lg" id="btn-draft">
                    <i class="fa fa-floppy-o"></i> Simpan Draft
                  </button>
                  &nbsp;
                  <button type="button" class="btn btn-success btn-lg" id="btn-submit">
                    <i class="fa fa-send"></i> Kirim ke Reviewer
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </form>
    <?php endif; ?>
<!--
  </section>
</div>-->

<style>
/* === Tombol Jawaban Ya/Tidak === */
.jawaban-group {
  display: flex;
  gap: 6px;
  justify-content: center;
  flex-wrap: wrap;
}
.jawaban-group input[type="radio"] {
  display: none;
}
.btn-jawaban {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 6px 14px;
  border-radius: 20px;
  border: 2px solid #ddd;
  background: #f9f9f9;
  color: #555;
  cursor: pointer;
  font-size: 13px;
  font-weight: 600;
  transition: all 0.18s ease;
  user-select: none;
  margin-bottom: 2px;
}
.btn-jawaban:hover {
  border-color: #aaa;
  background: #eee;
}
.btn-jawaban.active-ya {
  border-color: #00a65a;
  background: #00a65a;
  color: #fff;
}
.btn-jawaban.active-tidak {
  border-color: #dd4b39;
  background: #dd4b39;
  color: #fff;
}
.btn-jawaban.active-na {
  border-color: #777;
  background: #777;
  color: #fff;
}

/* Row highlight */
.pertanyaan-row.answered-ya td { background: #f0fff4 !important; }
.pertanyaan-row.answered-tidak td { background: #fff5f5 !important; }
.pertanyaan-row.answered-na td { background: #f8f8f8 !important; }

.table-monitoring td { vertical-align: middle !important; }
</style>

<script>
$(document).ready(function() {

  var totalPertanyaan = <?= array_sum(array_map('count', $pertanyaan_grouped)) ?>;

  // === Klik tombol jawaban ===
  $('.btn-jawaban').on('click', function() {
    var row = $(this).find('input[type="radio"]').data('row');
    var val = $(this).find('input[type="radio"]').val();

    // Reset label di baris ini
    $('input[data-row="' + row + '"]').each(function() {
      $(this).closest('label').removeClass('active-ya active-tidak active-na');
    });

    // Aktifkan yang diklik
    var cls = val === 'ya' ? 'active-ya' : (val === 'tidak' ? 'active-tidak' : 'active-na');
    $(this).addClass(cls);

    // Tandai baris
    var tr = $(this).closest('tr');
    tr.removeClass('answered-ya answered-tidak answered-na');
    tr.addClass('answered-' + (val === 'tidak_berlaku' ? 'na' : val));

    updateProgress();
  });

  // === Inisialisasi baris yang sudah ada jawabannya ===
  $('.pertanyaan-row').each(function() {
    var checked = $(this).find('input[type="radio"]:checked').val();
    if (checked) {
      $(this).addClass('answered-' + (checked === 'tidak_berlaku' ? 'na' : checked));
    }
  });

  // === Progress bar ===
  function updateProgress() {
    var dijawab = 0;
    $('.pertanyaan-row').each(function() {
      if ($(this).find('input[type="radio"]:checked').length > 0) {
        dijawab++;
      }
    });
    var pct = totalPertanyaan > 0 ? Math.round((dijawab / totalPertanyaan) * 100) : 0;
    $('#progress-bar').css('width', pct + '%');
    $('#progress-text').text(dijawab + ' dari ' + totalPertanyaan + ' pertanyaan dijawab');

    if (pct === 100) {
      $('#progress-bar').removeClass('progress-bar-green').addClass('progress-bar-success');
    }
  }

  updateProgress();

  // === Simpan Draft ===
  $('#btn-draft').on('click', function() {
    $('#form_action').val('draft');
    $('#form-monitoring').submit();
  });

  // === Kirim ke Reviewer ===
  $('#btn-submit').on('click', function() {
    var dijawab = 0;
    var belumDijawab = [];

    $('.pertanyaan-row').each(function(i) {
      if ($(this).find('input[type="radio"]:checked').length > 0) {
        dijawab++;
      } else {
        belumDijawab.push(i + 1);
      }
    });

    if (dijawab < totalPertanyaan) {
      Swal.fire({
        title: 'Form Belum Lengkap',
        html: 'Masih ada <strong>' + (totalPertanyaan - dijawab) + '</strong> pertanyaan yang belum dijawab.<br>Harap jawab semua pertanyaan sebelum mengirim.',
        icon: 'warning',
        confirmButtonColor: '#f39c12',
      });
      return;
    }

    Swal.fire({
      title: 'Kirim Form Monitoring?',
      html: 'Setelah dikirim, form tidak dapat diedit.<br>Pastikan semua jawaban sudah benar.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#00a65a',
      cancelButtonColor: '#d33',
      confirmButtonText: '<i class="fa fa-send"></i> Ya, Kirim Sekarang',
      cancelButtonText: 'Batal'
    }).then(function(result) {
      if (result.isConfirmed) {
        $('#form_action').val('submit');
        $('#form-monitoring').submit();
      }
    });
  });

});
</script>
