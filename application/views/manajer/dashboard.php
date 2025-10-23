    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    ?>
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <h1><?=$title?></h1>
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