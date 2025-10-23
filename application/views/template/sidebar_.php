<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?= base_url('assets/dist/img/user2-160x160.jpg') ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?= $this->session->userdata('nama') ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div> -->
    
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENU UTAMA</li>
      
      <li class="<?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      
      <!-- Menu Anggaran -->
      <li class="header">UNIT KERJA</li>

      <li class="treeview <?= $this->uri->segment(1) == 'anggaran' ? 'active menu-open' : '' ?>">
        <a href="#">
          <i class="fa fa-money"></i> <span>Anggaran</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?= $this->uri->segment(1) == 'anggaran' && $this->uri->segment(2) == '' ? 'active' : '' ?>">
            <a href="<?= site_url('index.php/daftar_pengajuan') ?>"><i class="fa fa-circle-o"></i> Daftar Pengajuan</a>
          </li>
          <li class="<?= $this->uri->segment(2) == 'pengajuan' ? 'active' : '' ?>">
            <a href="<?= site_url('index.php/pengajuan/form') ?>"><i class="fa fa-circle-o"></i> Buat Pengajuan</a>
          </li>
        </ul>
      </li>
      
      <!-- Menu Realisasi -->
      <li class="<?= $this->uri->segment(1) == 'realisasi' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-check-square-o"></i> <span>Realisasi</span>
        </a>
      </li>
      
      <!-- Menu Laporan (hanya untuk admin/keuangan) -->
      <li class="header">ANGGARAN</li>
      <?php //if(in_array($this->session->userdata('role'), ['admin', 'keuangan'])): ?>
      <li class="<?= $this->uri->segment(1) == 'monitoring' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Monitoring</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(1) == 'laporan' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Laporan</span>
        </a>
      </li>
      <?php //endif; ?>

      <!-- Menu Laporan (hanya untuk admin/keuangan) -->
      <li class="header">KORPUM</li>
      <?php //if(in_array($this->session->userdata('role'), ['admin', 'keuangan'])): ?>
      <li class="<?= $this->uri->segment(1) == 'monitoring' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Monitoring</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(1) == 'laporan' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Laporan</span>
        </a>
      </li>
      <?php //endif; ?>

      <!-- Menu Laporan (hanya untuk admin/keuangan) -->
      <li class="header">MANAJER KUANGAN</li>
      <?php //if(in_array($this->session->userdata('role'), ['admin', 'keuangan'])): ?>
      <li class="<?= $this->uri->segment(1) == 'monitoring' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Monitoring</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(1) == 'laporan' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Laporan</span>
        </a>
      </li>
      <?php //endif; ?>      

      <!-- Menu Laporan (hanya untuk admin/keuangan) -->
      <li class="header">VERIFIKATOR</li>
      <?php //if(in_array($this->session->userdata('role'), ['admin', 'keuangan'])): ?>
      <li class="<?= $this->uri->segment(1) == 'monitoring' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Monitoring</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(1) == 'laporan' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Laporan</span>
        </a>
      </li>
      <?php //endif; ?>

      <!-- Menu Laporan (hanya untuk admin/keuangan) -->
      <li class="header">KASIR</li>
      <?php //if(in_array($this->session->userdata('role'), ['admin', 'keuangan'])): ?>
      <li class="<?= $this->uri->segment(1) == 'monitoring' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Monitoring</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(1) == 'laporan' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Laporan</span>
        </a>
      </li>
      <?php //endif; ?>

      <!-- Menu Laporan (hanya untuk admin/keuangan) -->
      <li class="header">JUNIOR AKUNTAN</li>
      <?php //if(in_array($this->session->userdata('role'), ['admin', 'keuangan'])): ?>
      <li class="<?= $this->uri->segment(1) == 'monitoring' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Monitoring</span>
        </a>
      </li>
      <li class="<?= $this->uri->segment(1) == 'laporan' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-file-text"></i> <span>Laporan</span>
        </a>
      </li>
      <?php //endif; ?>

      <!-- Menu Pengguna (hanya untuk admin) -->
      <li class="header">ADMIN</li>
      <?php //if($this->session->userdata('role') == 'admin'): ?>
      <li class="<?= $this->uri->segment(1) == 'user' ? 'active' : '' ?>">
        <a href="<?= site_url('index.php/anggaran/test') ?>">
          <i class="fa fa-users"></i> <span>Manajemen Pengguna</span>
        </a>
      </li>
      <?php //endif; ?>
      
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>