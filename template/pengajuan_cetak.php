<?php

//set databse anggaran
$dbHost     = "localhost";
$dbUsername = "remu_fisipui";
$dbPassword = "fisipui123456"; //"8hMhN!M-^Pgk+jL";
$dbName     = "fina_anggaran";

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// get id_pengajuan_pemohon
$id_pengajuan_pemohon = $_POST['id_pengajuan_pemohon'];
$form = $_POST['nama_form'];

if($form == 'D01'){
    include('form_D01.php');
} else {
    include('form_D02.php');
}

/*
// get nama form -> gak jadi krn nama form utk data yg blm diajukan bernilai null
$stmt = $db->prepare("SELECT form FROM monitoring WHERE id_pengajuan_pemohon = ?");
$stmt->bind_param("i", $id_pengajuan_pemohon);
if ($stmt->execute()) {
    // set nama form
    $result = $stmt->get_result();
    foreach ($result as $row) {
        $form = $row['form'];
    }
    
    if($form == 'D01'){
        include('form_D01.php');
    } else {
        include('form_D02.php');
    }

} else {
    echo "Terjadi kesalahan: " . $stmt->error;
}
*/
// 5. Tutup statement dan koneksi
$stmt->close();
$db->close();
?>