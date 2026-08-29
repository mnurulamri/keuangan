<?php
// buat array warna untuk box pada class="small-box bg-..."
$box_colors = array(
    'bg-aqua',
    'bg-maroon',
    'bg-blue',
    'bg-purple',
    'bg-teal',
    'bg-green',
    'bg-orange',
    'bg-lime',
    'bg-fuchsia',
    'bg-navy',
    'bg-olive'
);

//buat array untuk menyimpan jumlah per kode_status dari monitoring_list
$jumlah_per_status = array();
foreach ($monitoring_list as $monitoring) {
    $jumlah_per_status[$monitoring['kode_status']] = $monitoring['jumlah'];
}

// jika tidak ada nilai $monitoring['jumlah'], set ke 0
foreach ($status_list as $status) {
    if (!isset($jumlah_per_status[$status['kode_status']])) {
        $jumlah_per_status[$status['kode_status']] = 0;
    }
}
?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <h1>Dashboard PUM</h1>
			<?php //echo '<pre>';print_r($jumlah_per_status); echo '<pre>'; ?>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <?php
                // Loop melalui data status_list untuk membuat info box
                foreach ($status_list as $index => $status) {
                    // Pilih warna box berdasarkan index, gunakan modulo untuk mengulang warna jika lebih banyak status
                    $box_color = $box_colors[$index % count($box_colors)];
                    $detail_link = base_url('pengajuan_ajax/?kode_status=' . $status['kode_status']);
                    ?>
                    <!-- Info Box: <?php echo $status['nama_status']; ?> -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box <?php echo $box_color; ?>">
                            <div class="inner">
                                <h3><?php echo $jumlah_per_status[$status['kode_status']]; ?></h3>
                                <p><?php echo $status['nama_status']; ?></p>
                                <?php
                                // jika status == 0 atau status = 41 maka tampilkan marque
                                if(($status['kode_status'] == '0' and $jumlah_per_status[$status['kode_status'] > 0])|| $status['kode_status'] == '41' and $jumlah_per_status[$status['kode_status'] > 0]){
                                ?>
                                <marquee behavior="" direction="">untuk segera ditindaklanjuti</marquee>
                                <?php } else {
                                    echo '<marquee behavior="" direction="">&nbsp;</marquee>';
                                } ?>
                            </div>
                            <div class="icon">
                                <i class="fa fa-info-circle"></i>
                            </div>
                            <a href="<?php echo $detail_link; ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                <?php } ?>
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