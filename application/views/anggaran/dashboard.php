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
                <?php
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
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->