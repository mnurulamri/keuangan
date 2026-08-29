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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login — Sistem Keuangan FISIP UI</title>

  <!-- Font & Icon (opsional, bisa diganti sesuai kebijakan) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --ui-yellow: #ffd200;      /* Warna khas UI */
      --ui-black: #1a1a1a;
      --ui-gray: #6b7280;
      --card-bg: rgba(255,255,255,0.12);
      --card-border: rgba(255,255,255,0.25);
      --focus: #3b82f6;
    }

    *{ box-sizing: border-box; }
    html, body{
      height: 100%;
      margin: 0;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color: #fff;
      background: #0b0f19;
    }

    /* Background: kombinasi foto kampus + gradient */
    .bg{
      position: fixed;
      inset: 0;
      /*z-index: -2;*/
      background:
        linear-gradient(120deg, rgba(11,15,25,0.85) 0%, rgba(11,15,25,0.55) 60%, rgba(11,15,25,0.85) 100%),
        url('../assets/images/cover.jpg') center/cover no-repeat;
      /* Ganti URL di atas dengan foto kampus UI/FISIP yang memiliki izin pakai */
      filter: saturate(1.05) contrast(1.05);
    }

    /* Aksen grid halus */
    .bg::after{
      content: "";
      position: absolute;
      inset: 0;
      background-image:
        radial-gradient(circle at 20% 10%, rgba(255,210,0,0.15), transparent 35%),
        radial-gradient(circle at 80% 70%, rgba(59,130,246,0.12), transparent 40%),
        linear-gradient(transparent 0 0);
      mix-blend-mode: screen;
      pointer-events: none;
    }

    /* Container utama */
    .page{
      min-height: 100%;
      display: grid;
      grid-template-columns: 1fr;
      place-items: center;
      padding: 24px;
    }

    /* Kartu login bergaya glass */
    .card{
      width: 100%;
      max-width: 420px;
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      backdrop-filter: blur(14px) saturate(1.2);
      border-radius: 18px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.35);
      overflow: hidden;
    }

    .brand{
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 22px 22px 0 22px;
    }

    .logo{
      width: 44px; height: 44px;
      border-radius: 50%;
      /*background: radial-gradient(circle at 30% 30%, #fff 0%, #ffe680 35%, var(--ui-yellow) 70%, #caa400 100%);*/
      background: radial-gradient(circle at 30% 30%, #fa0 0%, rgb(224, 142, 87) 35%, var(--ui-yellow) 70%, rgb(238, 223, 192) 100%);
      box-shadow: 0 0 0 2px rgba(255,255,255,0.25), inset 0 8px 16px rgba(0,0,0,0.25);
    }

    .brand h1{
      font-size: 1.05rem;
      margin: 0;
      line-height: 1.2;
      font-weight: 700;
      letter-spacing: 0.2px;
    }

    .brand p{
      margin: 2px 0 0 0;
      font-size: 0.85rem;
      color: #e5e7eb;
    }

    .form{
      padding: 22px;
      display: grid;
      gap: 14px;
    }

    .field{
      display: grid;
      gap: 8px;
    }

    .field label{
      font-size: 0.85rem;
      color: #e5e7eb;
      font-weight: 600;
    }

    .input{
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.22);
      border-radius: 12px;
      padding: 12px 14px;
      transition: border-color .2s ease, box-shadow .2s ease;
    }

    .input:focus-within{
      border-color: var(--focus);
      box-shadow: 0 0 0 3px rgba(59,130,246,0.25);
    }

    .input input{
      width: 100%;
      border: none;
      outline: none;
      background: transparent;
      color: #fff;
      font-size: 0.95rem;
      letter-spacing: 0.2px;
    }

    .hint{
      font-size: 0.8rem;
      color: var(--ui-gray);
    }

    .row{
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-top: 4px;
    }

    .checkbox{
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 0.85rem;
      color: #e5e7eb;
      cursor: pointer;
      user-select: none;
    }

    .checkbox input{
      width: 16px; height: 16px;
      accent-color: var(--ui-yellow);
      cursor: pointer;
    }

    .link{
      color: var(--ui-yellow);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.85rem;
    }
    .link:hover{ text-decoration: underline; }

    .btn{
      width: 100%;
      border: none;
      border-radius: 12px;
      padding: 12px 16px;
      font-weight: 700;
      font-size: 0.95rem;
      letter-spacing: 0.3px;
      cursor: pointer;
      color: #1a1a1a;
      background: linear-gradient(180deg, #ffe680 0%, var(--ui-yellow) 70%, #e6b800 100%);
      box-shadow: 0 10px 20px rgba(255,210,0,0.25);
      transition: transform .06s ease, box-shadow .2s ease, filter .2s ease;
    }
    .btn:hover{ filter: brightness(1.05); }
    .btn:active{ transform: translateY(1px); }

    .divider{
      display: flex;
      align-items: center;
      gap: 12px;
      color: #cbd5e1;
      font-size: 0.8rem;
      margin: 6px 0 2px;
    }
    .divider::before, .divider::after{
      content: "";
      flex: 1;
      height: 1px;
      background: rgba(255,255,255,0.25);
    }

    .footer{
      padding: 0 22px 22px 22px;
      display: grid;
      gap: 10px;
      color: #cbd5e1;
      font-size: 0.8rem;
    }

    /* Responsif kecil */
    @media (max-width: 420px){
      .brand h1{ font-size: 0.98rem; }
      .card{ border-radius: 16px; }
    }

    /* Preferensi reduced motion */
    @media (prefers-reduced-motion: reduce){
      .btn, .input{ transition: none; }
    }
  </style>
</head>
<body>
  <div class="bg" aria-hidden="true"></div>

  <main class="page" role="main">
    <section class="card" aria-label="Form login sistem keuangan FISIP UI">
      <header class="brand">
        <div class="logo" aria-hidden="true">
            <img src="../assets/images/UI_logo.png" alt="Logo Makara UI" class="logo" />
        </div>
        <div>
          <h1>Sistem Keuangan FISIP UI</h1>
          <p>Pengajuan Uang Muka Kas Operasional</p>
        </div>
      </header>




      <form class="form" action="<?= site_url('auth/set_role_test') ?>" method="post" novalidate>
        <div class="row" aria-label="Login alternatif">
          <!--<a class="link" href="/keuangan/auth/sso">Masuk dengan SSO UI</a>-->
        </div>  
        <div class="divider"><a class="#" href="#">Masuk dengan SSO UI</a></div>      
        <div class="field">
          <label for="username">Username</label>
          <div class="input">
            <!-- Ikon sederhana (SVG inline) -->
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M4 6h16v12H4z" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
              <path d="M4 7l8 6 8-6" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
            </svg>
            <input id="username" name="username" type="text" inputmode="email"
                   autocomplete="username" placeholder="" required />
          </div>
          <!--<div class="hint">Gunakan akun UI atau NPM terdaftar.</div>-->
        </div>

        <div class="field">
          <label for="password">Kata sandi</label>
          <div class="input">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M6 10h12v10H6z" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
              <path d="M9 10V7a3 3 0 0 1 6 0v3" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" />
            </svg>
            <input id="password" name="password" type="password" autocomplete="current-password"
                   placeholder="••••••••" required />
          </div>
        </div>

        <div class="row">
          <!--<label class="checkbox">
            <input type="checkbox" name="remember" />
            Ingat saya
          </label>
          <a class="link" href="/keuangan/auth/forgot">Lupa kata sandi?</a>-->
        </div>

        <button class="btn" type="submit" aria-label="Masuk ke sistem">
          Masuk
        </button>
        <!--
        <div class="divider">atau</div>
        
        <div class="row" aria-label="Login alternatif">
          <a class="link" href="/keuangan/auth/sso">Masuk dengan SSO UI</a>
          <a class="link" href="/keuangan/auth/help">Butuh bantuan?</a>
        </div>
        -->
      </form>

      <footer class="footer">
        <div>FISIP Universitas Indonesia — {{tahun}}</div>
        <div>Keamanan: akun Anda dilindungi sesuai kebijakan TI UI. Jangan bagikan kredensial kepada siapa pun.</div>
      </footer>
    </section>
  </main>

  <script>
    // Ganti placeholder tahun tanpa framework
    document.querySelectorAll('.footer div')[0].innerHTML =
      'FISIP Universitas Indonesia — ' + new Date().getFullYear();
  </script>
</body>
</html>
