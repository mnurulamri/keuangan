<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//echo $role = $this->session->userdata('logged_anggaran')['role'];
if(!empty($this->session->userdata('logged_anggaran')['role']) or isset($this->session->userdata('logged_anggaran')['role'])) {
    //echo $role;
    if($this->session->userdata('logged_anggaran')['role'] == 'anggaran' || $this->session->userdata('logged_anggaran')['role'] == 'korpum' || $this->session->userdata('logged_anggaran')['role'] == 'manajer' || $this->session->userdata('logged_anggaran')['role'] == 'kasir' || $this->session->userdata('logged_anggaran')['role'] == 'verifikator' || $this->session->userdata('logged_anggaran')['role'] == 'yunior_akuntan') {
    // jika role adalah pengelola, redirect ke form pengelola
        if($this->session->userdata('logged_anggaran')['role'] == 'anggaran'){
            $url = 'unit_anggaran';
        } else {
            $url = $this->session->userdata('logged_anggaran')['role'];
        }
        redirect( $url.'/monitoring' );
        exit();
    } else {
        $url = 'pengajuan_ajax';
        redirect( $url );
        exit();
    }
    //if ($role !== 'admin') {
    //redirect('auth/login');
    //exit();
    //}    
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pencairan Dana Online</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/skin-blue.min.css">
    <style>
        .login-logo img { max-width: 80px; margin-bottom: 10px; }
        .info-box { margin-bottom: 20px; }
        .login-box-body { border-radius: 8px; }
        .login-box { width: 400px; }
        @media (max-width: 480px) {
            .login-box { width: 95%; }
        }
    </style>
</head>
<body class="hold-transition login-page" style="background: linear-gradient(120deg, #210342ff 0%, #3a022aff 100%);
    background-image: url('<?= base_url('assets/images/cover.jpg') ?>');
    background-repeat: no-repeat;
    background-position: center center;
    background-size:100%;
    background-attachment: fixed;
    background-color: #322e36ff;
    background-blend-mode: overlay;
    ">
    <div class="login-logo" style="color:#fff;">
        <b>Sistem Informasi Keuangan</b>        
    </div>
    <div class="text-center" style="color:gold">Fakultas Ilmu Sosial dan Ilmu Politik Universitas Indonesia</div>    
<div class="login-box">

    <!--<div class="info-box" style="color:#fff; background: linear-gradient(120deg, #001F3F 0%, #7b7d80ff 100%); opacity: 0.9;">
        <span class="info-box-icon"><i class="fa fa-info-circle"></i></span>
        <div class="info-box-content" style="font-weight:bold; margin-left: 0px;">
            <span>Selamat datang di Sistem Informasi Keuangan<br>
            Proses mudah, cepat, dan transparan.<br><br>
            <ul style="padding-left: 18px; margin-bottom: 0;">
                <li>Ajukan pencairan dana kapan saja, di mana saja.</li>
                <li>Proses verifikasi aman &amp; terintegrasi.</li>
                <li>Pantau status secara real-time.</li>
                <li>Dukungan: Admin, Unit Anggaran, Korpum, Manajer, Kasir, Verifikator, Yunior Akuntan.</li>
            </ul>
            </span>
        </div>
    </div>-->
    <div class="login-box-body">
        <p class="login-box-msg">Login Akun</p>
        <div class="alert alert-danger" id="errorMsg" style="display:none; padding:7px 10px; font-size:14px;"></div>
        <form id="loginForm" autocomplete="off" action="<?= site_url('auth/set_role') ?>" method="post">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" placeholder="Username" id="username" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" id="password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8"></div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- jQuery 3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/js/adminlte.min.js"></script>
<script>
    /*
$('#loginForm').on('submit', function(e) {
    e.preventDefault();
    var username = $('#username').val().trim();
    var password = $('#password').val().trim();
    var errorMsg = $('#errorMsg');
    errorMsg.hide();

    if (!username || !password) {
        errorMsg.text('Semua field harus diisi!').show();
        return;
    }

    setTimeout(function() {
        if (username === 'admin') {
            window.location.href = "<?=site_url('auth/unit_kerja')?>";
        } else if (username === 'anggaran') {
            window.location.href = "<?=site_url('unit_anggaran/monitoring')?>";
        } else if (username === 'korpum') {
            window.location.href = "<?=site_url('korpum/monitoring')?>";
        } else if (username === 'manajer') {
            window.location.href = "<?=site_url('manajer/monitoring')?>";
        } else if (username === 'kasir') {
            window.location.href = "<?=site_url('kasir/monitoring')?>";
        } else if (username === 'verifikator') {
            window.location.href = "<?=site_url('verifikator/monitoring')?>";
        } else if (username === 'yunior_akuntan') {
            window.location.href = "<?=site_url('yunior_akuntan/monitoring')?>";
        } else if (username === 'user') {
            window.location.href = "<?=site_url('unit_kerja/dashboard')?>";
        } else {
            errorMsg.text('Username, Password, atau Role salah!').show();
        }
    }, 500);
});
*/
</script>
</body>
</html>
