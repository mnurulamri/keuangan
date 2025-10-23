
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <h1>Selamat Datang di Beranda Unit Kerja</h1>
			<?php echo '<pre>';print_r($this->session->userdata()); echo $this->session->userdata('role'); echo '<pre>'; ?>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
            <!-- Info Box: Belum Diajukan -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>0</h3>
                    <p>Belum Diajukan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-hourglass-start"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Info Box: Menunggu Persetujuan Koordinator Anggaran -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>0</h3>
                    <p>Menunggu Persetujuan Koordinator Anggaran</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Info Box: Menunggu Konfirmasi Koordinator PUM -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-blue">
                <div class="inner">
                    <h3>0</h3>
                    <p>Menunggu Konfirmasi Koordinator PUM</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-clock"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Info Box: Menunggu Persetujuan Manajer Keuangan -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-purple">
                <div class="inner">
                    <h3>0</h3>
                    <p>Menunggu Persetujuan Manajer Keuangan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-check"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            </div>
            <div class="row">
            <!-- Info Box: Disetujui Kasir -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-teal">
                <div class="inner">
                    <h3>0</h3>
                    <p>Disetujui Kasir</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Info Box: Pemeriksaan Verifikator -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                <div class="inner">
                    <h3>0</h3>
                    <p>Pemeriksaan Verifikator</p>
                </div>
                <div class="icon">
                    <i class="fa fa-search"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Info Box: Pencatatan Yunior Akuntan -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-orange">
                <div class="inner">
                    <h3>0</h3>
                    <p>Pencatatan Yunior Akuntan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book"></i>
                </div>
                <a href="#" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs"></div>
        <strong>Unit Kerja &copy; 2024</strong>
    </footer>
</div>
<!-- ./wrapper -->