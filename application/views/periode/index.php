<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= $title ?>
      <small>Daftar Pengajuan</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?= $title ?></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Konten utama Anda di sini


        <h2>Pengaturan Periode</h2>
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        
        <!-- Form Inisialisasi Periode Baru -->
        <div class="box box-primary">
            <div class="box-header with-border text-center">
                <h5>Inisialisasi Periode Baru</h5>
            </div>
            <div class="box-body">
                <form method="post" action="<?php echo site_url('pengaturan_periode/initialize'); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select class="form-select" id="tahun" name="tahun" required>
                            <?php for($i = date('Y')-1; $i <= date('Y')+1; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $i == date('Y') ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select class="form-select" id="bulan" name="bulan" required>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo $i == date('m') ? 'selected' : ''; ?>>
                                    <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Buat Periode</button>
                    </div>
                </form>
            </div>
        </div>
		
        
        <!-- Tabel Daftar Periode -->
        <div class="box">
            <div class="box-header">
                <h5>Daftar Periode</h5>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tahun Anggaran</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($periode as $p): ?>
                            <tr>
                                <td><?php echo $p->tahun_anggaran; ?></td>
                                <td><?php echo $p->tahun; ?></td>
                                <td><?php echo date('F', mktime(0, 0, 0, $p->bulan, 1)); ?></td>
                                <td>
                                    <?php if($p->lock_data == 0): ?>
                                        <span class="lock-open">
                                            <i class="fa fa-unlock"></i> Terbuka
                                        </span>
                                    <?php else: ?>
                                        <span class="lock-closed">
                                            <i class="fa fa-lock"></i> Tertutup
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($p->lock_data == 0): ?>
                                        <a href="<?php echo site_url('pengaturan_periode/toggle_lock/'.$p->id); ?>" 
                                           class="btn btn-warning btn-sm"
                                           onclick="return confirm('Tutup periode ini?')">
                                            <i class="fa fa-lock"></i> Tutup
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('pengaturan_periode/toggle_lock/'.$p->id); ?>" 
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('Buka periode ini?')">
                                            <i class="fa fa-unlock"></i> Buka
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Info Periode Aktif -->
        <?php if($active_periode): ?>
            <div class="alert alert-info mt-3">
                Periode aktif saat ini: 
                <strong>
                    <?php echo date('F', mktime(0, 0, 0, $active_periode->bulan, 1)); ?> 
                    <?php echo $active_periode->tahun; ?>
                </strong>
            </div>
        <?php endif; ?>
    </div>

  </section>
  <!-- /.content -->
</div>


</body>
</html>