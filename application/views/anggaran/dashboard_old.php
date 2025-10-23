    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $info_boxes = [
        [
            'count' => 0,
            'label' => 'Belum Diajukan',
            'icon' => 'fa-hourglass-start',
            'color' => 'bg-aqua',
            'link' => '#'
        ],
        [
            'count' => 0,
            'label' => 'Menunggu Persetujuan Koordinator Anggaran',
            'icon' => 'fa-user',
            'color' => 'bg-maroon',
            'link' => '#'
        ],
        [
            'count' => 0,
            'label' => 'Menunggu Konfirmasi Koordinator PUM',
            'icon' => 'fa-user-clock',
            'color' => 'bg-blue',
            'link' => '#'
        ],
        [
            'count' => 0,
            'label' => 'Menunggu Persetujuan Manajer Keuangan',
            'icon' => 'fa-user-check',
            'color' => 'bg-purple',
            'link' => '#'
        ],
        [
            'count' => 0,
            'label' => 'Disetujui Kasir',
            'icon' => 'fa-user',
            'color' => 'bg-teal',
            'link' => '#'
        ],
        [
            'count' => 0,
            'label' => 'Pemeriksaan Verifikator',
            'icon' => 'fa-search',
            'color' => 'bg-green',
            'link' => '#'
        ],
        [
            'count' => 0,
            'label' => 'Pencatatan Yunior Akuntan',
            'icon' => 'fa-book',
            'color' => 'bg-orange',
            'link' => '#'
        ]
    ];

    foreach ($info_boxes as $box) {
        echo '<div class="col-lg-3 col-xs-6">';
        echo '  <div class="small-box ' . htmlspecialchars($box['color']) . '">';
        echo '    <div class="inner">';
        echo '      <h3>' . htmlspecialchars($box['count']) . '</h3>';
        echo '      <p>' . htmlspecialchars($box['label']) . '</p>';
        echo '    </div>';
        echo '    <div class="icon">';
        echo '      <i class="fa ' . htmlspecialchars($box['icon']) . '"></i>';
        echo '    </div>';
        echo '    <a href="' . htmlspecialchars($box['link']) . '" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>';
        echo '  </div>';
        echo '</div>';
    }
    ?>
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <h1>Dashboard Unit Anggaran</h1>
            <p>Ringkasan status pengajuan dan proses anggaran.</p>
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