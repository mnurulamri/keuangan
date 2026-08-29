<!-- views/monitoring_evaluasi/pertanyaan.php -->
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-question-circle text-orange"></i> Kelola Pertanyaan Monitoring
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('/') ?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url('monitoring_evaluasi') ?>">Monitoring Evaluasi</a></li>
      <li class="active">Kelola Pertanyaan</li>
    </ol>
  </section>

  <section class="content">

    <?php
    // 1. Definisikan pemetaan kategori ke nomor urut
    $urutan_kategori = [
        'Penyelenggaraan Administrasi' => 1,
        'Penyelenggaraan Penelitian'    => 2,
        'Metodologi'                   => 3,
        'Etika Penelitian'             => 4,
        'Pelaksanaan'                  => 5,
        'Pelaporan & Dokumentasi'      => 6,
        'Luaran Penelitian'            => 7,
        'Lain-lain'                    => 8,
    ];
    
    $dataBaru = [];
    
    // 2. Lakukan looping untuk menyusun ulang berdasarkan mapping
    foreach ($pertanyaan_grouped as $kategori => $items) {
        // Cek apakah kategori ada dalam mapping, jika tidak gunakan urutan default
        $keyBaru = isset($urutan_kategori[$kategori]) ? $urutan_kategori[$kategori] : 99;
        $dataBaru[$keyBaru] = $items;
    }
    
    // 3. Urutkan array berdasarkan key (nomor urut)
    ksort($dataBaru);
    $pertanyaan_grouped = $dataBaru;
    
    // Menggunakan array_flip untuk menukar posisi key dan value
    $kategori_terbalik = array_flip($urutan_kategori);
    // Hasil akhir;
    //echo '<pre>';print_r($kategori_terbalik);echo '</pre>';exit();
    ?>
    
    <?php if ($flash_success): ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <i class="fa fa-check-circle"></i> <?= $flash_success ?>
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list"></i> Bank Pertanyaan Monitoring</h3>
            <div class="box-tools pull-right">
              <a href="<?= site_url('monitoring_evaluasi/tambah_pertanyaan') ?>" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Tambah Pertanyaan
              </a>
            </div>
          </div>
          <div class="box-body no-padding">

            <?php if (empty($pertanyaan_grouped)): ?>
            <div class="text-center text-muted" style="padding:30px">
              <i class="fa fa-info-circle fa-3x"></i><br><br>
              Belum ada pertanyaan monitoring.
              <a href="<?= site_url('monitoring_evaluasi/tambah_pertanyaan') ?>">Tambah pertanyaan pertama</a>.
            </div>
            <?php else: ?>

            <?php foreach ($pertanyaan_grouped as $kategori => $list): ?>
            <div class="panel panel-default" style="margin:0; border-radius:0; border-left:0; border-right:0">
              <div class="panel-heading bg-light-blue-active" style="padding:8px 15px">
                <strong><i class="fa fa-tag"></i> <?= $kategori_terbalik[htmlspecialchars($kategori)] ?></strong>
                <span class="badge pull-right"><?= count($list) ?></span>
              </div>
              <table class="table table-condensed table-hover" style="margin:0">
                <thead>
                  <tr class="bg-gray-light">
                    <th width="6%" class="text-center">Kode</th>
                    <th width="5%" class="text-center">No</th>
                    <th>Pertanyaan</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="15%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($list as $p): ?>
                  <tr class="<?= !$p->is_aktif ? 'text-muted' : '' ?>">
                    <td class="text-center"><code><?= htmlspecialchars($p->kode) ?></code></td>
                    <td class="text-center text-muted"><?= $p->urutan ?></td>
                    <td <?= !$p->is_aktif ? 'style="text-decoration:line-through;color:#aaa"' : '' ?>>
                      <?= htmlspecialchars($p->pertanyaan) ?>
                    </td>
                    <td class="text-center">
                      <?php if ($p->is_aktif): ?>
                        <span class="label label-success"><i class="fa fa-check"></i> Aktif</span>
                      <?php else: ?>
                        <span class="label label-default"><i class="fa fa-ban"></i> Nonaktif</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="<?= site_url('monitoring_evaluasi/edit_pertanyaan/' . $p->id) ?>"
                         class="btn btn-xs btn-primary" title="Edit">
                        <i class="fa fa-pencil"></i>
                      </a>
                      <a href="<?= site_url('monitoring_evaluasi/toggle_pertanyaan/' . $p->id) ?>"
                         class="btn btn-xs <?= $p->is_aktif ? 'btn-warning' : 'btn-success' ?>" 
                         title="<?= $p->is_aktif ? 'Nonaktifkan' : 'Aktifkan' ?>">
                        <i class="fa fa-<?= $p->is_aktif ? 'eye-slash' : 'eye' ?>"></i>
                      </a>
                      <a href="<?= site_url('monitoring_evaluasi/hapus_pertanyaan/' . $p->id) ?>"
                         class="btn btn-xs btn-danger btn-hapus" title="Hapus">
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
$(document).ready(function() {
  $(document).on('click', '.btn-hapus', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    Swal.fire({
      title: 'Hapus Pertanyaan?',
      text : 'Pertanyaan ini akan dihapus permanen dari bank pertanyaan.',
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
