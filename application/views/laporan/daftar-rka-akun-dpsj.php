<?php
//echo '<pre>';print_r($sql);echo '</pre>';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row text-center">
            <h4 style="color:#444">
                <div>Data Anggaran Tahun <?=$tahun?></div><input type="hidden" id="tahun" value="<?=$tahun?>">
                <div><?=$deskripsi_dpsj?></div>
            </h4>
            <input type="text" id="kode_kegiatan-search" onkeyup="search()" placeholder="Filter Procost.." class="table-search-filters">
            <input type="text" id="kode_akun-search" onkeyup="search()" placeholder="Filter Akun.." class="table-search-filters">
            <?php
            $array_role = array('pum');
            $array_username = array('PUM_REMUN', 'tiwi.gunarti');
            if(in_array($this->session->userdata('logged_anggaran')['role'], $array_role)){
                if(in_array($this->session->userdata('logged_anggaran')['username'], $array_username)){
                    ?>
                    <select name="pagu" id="pagu-search" class="form-controlx table-search-filters" onchange="search()">
                        <option value="">Pilih Pagu</option>
                        <option value="unit">Procost Unit</option>
                        <option value="Procost Remun" selected >Procost Remun</option>
                        <option value="Procost Umum">Procost Umum</option>
                    </select>
            <?php
                } else {
                    ?>
                    <input type="text" id="pagu-search" value="Procost Unit" placeholder="Filter Pagu.." class="table-search-filters" style="display: none;">
                    <?php
                }
            } else {
            ?>
                <select name="pagu" id="pagu-search" class="form-controlx table-search-filters" onchange="search()" >
                    <option value="">Pilih Pagu</option>
                    <option value="unit" selected >Procost Unit</option>
                    <option value="Procost Remun">Procost Remun</option>
                    <option value="Procost Umum">Procost Umum</option>
                </select>
            <?php
            }
            ?>
        </div>

        <div class="table-container">
            <table class="styled-table" id="tabel">
                <thead>
                    <tr class="header">
                        <th>PROCOST</th>
                        <th>AKUN</th>
                        <th>KODE DANA</th>
                        <th>PAGU</th>
                        <th>ANGGARAN</th>
                        <th>KOMITMEN</th>
                        <th>AKTUAL</th>
                        <th>MUTASI</th>
                        <th>SISA ANGGARAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($result as $row){
                
                        $style_anggaran = '';
                        $style_komitmen = '';
                        $style_aktual = '';
                        $style_mutasi = '';
                        $style_sisa_anggaran = '';
                        $detail_mutasi = '';
                        $toggle_komitmen = '';
                        $toggle_aktual = '';
                        $toggle_mutasi = '';

                        if($row['komitmen'] > 0){
                            $style_komitmen = 'style="cursor:pointer; color:blue" onMouseOver="this.style.color=\'red\'" onMouseOut="this.style.color=\'blue\'"';
                            $toggle_komitmen = 'data-toggle="custom-modal" data-target="customModal"';
                        }

                        if($row['aktual'] > 0){
                            $style_aktual = 'style="cursor:pointer; color:green" onMouseOver="this.style.color=\'red\'" onMouseOut="this.style.color=\'green\'"';
                            $toggle_aktual = 'data-toggle="custom-modal" data-target="customModal"';
                        }

                        if($row['mutasi'] != 0){
                            $style_mutasi = 'style="cursor:pointer; color:#A31497" onMouseOver="this.style.color=\'red\'" onMouseOut="this.style.color=\'#A31497\'"';
                            $detail_mutasi = 'detail-mutasi';
                            $toggle_mutasi = 'data-toggle="modal" data-target="#modal-akun"';
                        }

                        $procost = $row['kode_dpsj'].': '.$row['kode_kegiatan'].' - '.explode(":", $row['nama_kegiatan'])[1];

                        echo '
                        <tr class="item">
                            <td>'.$procost.'</td>
                            <td>'.$row['kode_akun'].' - '.$row['deskripsi_akun'].'</td>
                            <td>'.$row['kode_dana'].'</td>
                            <td>'.$row['flag_payroll'].'</td>
                            <td class="text-right">'.number_format($row['anggaran']).'</td>
                            <td class="text-right detail_komitmen_aktual" data-kode_kegiatan="'.$row['kode_kegiatan'].'" data-kode_akun="'.$row['kode_akun'].'" data-tahun="'.$row['tahun_anggaran'].'" data-kode_dana="'.$row['kode_dana'].'" '.$toggle_komitmen.' '.$style_komitmen.'>'.number_format($row['komitmen']).'</td>
                            <td class="text-right detail_komitmen_aktual" data-kode_kegiatan="'.$row['kode_kegiatan'].'" data-kode_akun="'.$row['kode_akun'].'" data-tahun="'.$row['tahun_anggaran'].'" data-kode_dana="'.$row['kode_dana'].'" '.$toggle_aktual.' '.$style_aktual.' '.$style_aktual.'>'.number_format($row['aktual']).'</td>
                            <td class="text-right '.$detail_mutasi.'" '.$style_mutasi.' data-kode_kegiatan="'.$row['kode_kegiatan'].'" data-kode_akun="'.$row['kode_akun'].'" data-tahun="'.$row['tahun_anggaran'].'" data-kode_dana="'.$row['kode_dana'].'" '.$toggle_mutasi.'>'.number_format($row['mutasi']).'</td>
                            <td class="text-right">'.number_format($row['sisa_anggaran']).'</td>
                            
                        </tr>
                        ';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold; background-color: #f8f9fa;">
                        <td colspan="4" style="text-align: right;">TOTAL:</td>
                        <td id="total-anggaran" class="text-right">0</td>
                        <td id="total-komitmen" class="text-right">0</td>
                        <td id="total-aktual" class="text-right">0</td>
                        <td id="total-mutasi" class="text-right">0</td>
                        <td id="total-sisa-anggaran" class="text-right">0</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</div>

<!-- modal rincian akun -->
<div class="modal fade" id="modal-akun" tabindex="-1" role="dialog" aria-labelledby="viewAkunModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="akun-title">RINCIAN AKUN</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
				<div id="data-akun">
					<?php //include(APPPATH.'views/unit_kerja/form_edit_pengajuan.php') ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>
<?php include(APPPATH.'views/custom_modal.php') ?>
<script type="text/javascript">
var input_kode_kegiatan = document.getElementById("kode_kegiatan-search");
var input_kode_akun = document.getElementById("kode_akun-search");
var table = document.getElementById("tabel");
var totalAnggaranElement = document.getElementById("total-anggaran");
var totalKomitmenElement = document.getElementById("total-komitmen");
var totalAktualElement = document.getElementById("total-aktual");
var totalMutasiElement = document.getElementById("total-mutasi");
var totalSisaAnggaranElement = document.getElementById("total-sisa-anggaran");

search();
function search() {

    let filter_kode_kegiatan = input_kode_kegiatan.value.toUpperCase();
    let filter_kode_akun = input_kode_akun.value.toUpperCase();
    let tr = table.rows;
    let totalAnggaran = 0;
    let totalKomitmen = 0;
    let totalAktual = 0;
    let totalMutasi = 0;
    let totalSisaAnggaran = 0;
    
    for (let i = 0; i < tr.length; i++) {
        // Lewati baris header (baris 0 dan 1) dan baris footer
        if (i < 1 || tr[i].parentNode.nodeName === 'TFOOT') continue;
        
        let td = tr[i].cells;
        let td_kode_kegiatan = td[0].innerText;
        let td_kode_akun = td[1].innerText;
        
        if (
            td_kode_kegiatan.toUpperCase().indexOf(filter_kode_kegiatan) > -1 && 
            td_kode_akun.toUpperCase().indexOf(filter_kode_akun) > -1) {
            
            tr[i].style.display = "";
            
            // Hanya tambahkan total jika baris ditampilkan
            // Ambil nilai dari kolom KOMITMEN, AKTUAL, MUTASI, dan SISA ANGGARAN
            let mutasiText = td[7].innerText.replace(/,/g, '');
            let sisaAnggaranText = td[8].innerText.replace(/,/g, '');
            let AnggaranText = td[4].innerText.replace(/,/g, '');
            let komitmenText = td[5].innerText.replace(/,/g, ''); // Hapus koma dari format angka
            let aktualText = td[6].innerText.replace(/,/g, '');
            
            // Konversi ke number dan tambahkan ke total
            totalAnggaran += parseFloat(AnggaranText) || 0;
            totalKomitmen += parseFloat(komitmenText) || 0;
            totalAktual += parseFloat(aktualText) || 0;
            totalMutasi += parseFloat(mutasiText) || 0;
            totalSisaAnggaran += parseFloat(sisaAnggaranText) || 0;
            
        } else {
            tr[i].style.display = "none";
        }
    }
    
    // Update tampilan total dengan format angka
    totalAnggaranElement.textContent = formatNumber(totalAnggaran);
    totalKomitmenElement.textContent = formatNumber(totalKomitmen);
    totalAktualElement.textContent = formatNumber(totalAktual);
    totalMutasiElement.textContent = formatNumber(totalMutasi);
    totalSisaAnggaranElement.textContent = formatNumber(totalSisaAnggaran);
}

// Fungsi untuk memformat angka dengan pemisah ribuan
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Tunggu sampai DOM sepenuhnya dimuat
document.addEventListener('DOMContentLoaded', function() {
    search();
    
    // saat halaman dimuat, jalankan toggle sidebar
    $(".sidebar-toggle").click();
    
	$(document).on("click",".detail_komitmen_aktual", function(){
		var tahun = $("#tahun").val();
		var kode_kegiatan = $(this).data("kode_kegiatan");
		var kode_akun = $(this).data("kode_akun");
		var kode_dana = $(this).data("kode_dana");
        //console.log(tahun+' '+kode_kegiatan+' '+kode_akun+' '+kode_dana); return false;
        
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/detail_akun_komitmen_aktual",
            type: "POST",
            data: {tahun: tahun, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-modal").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

	$(document).on("click",".detail-mutasi", function(){
		var kode_dpsj = $(this).data("kode_dpsj");
		var kode_kegiatan = $(this).data("kode_kegiatan");
		var kode_akun = $(this).data("kode_akun");
		var kode_dana = $(this).data("kode_dana");
		var tahun = $("#tahun").val();

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/detail_akun_mutasi",
            type: "POST",
            data: {tahun: tahun, kode_dpsj:kode_dpsj, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-akun").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});
});

</script>

<style>

.table-search-filters {
    margin: 5px;
	width: 25%;
	height: 30px;
	margin-bottom: 5px;
	padding: 5px;
	border: 1px solid #ddd;
	border-radius: 4px;
	background-color: #f9f9f9;

}

/* ===== SOLUSI STICKY HEADER ===== */
/* Container dengan scroll horizontal DAN vertikal */
.table-container {
    position: relative;
    border: 1px solid #e2e8f0;
    /*border-radius: 14px;*/
    overflow: auto;
    max-height: 70vh;
    background: white;
}

/* STICKY HEADER - ini yang benar */
.styled-table thead tr {
    position: sticky;
    top: 0;
    z-index: 20;
}

.styled-table {
   border-collapse: collapse;
   font-size:12px;
   font-family: Arial, Helvetica, sans-serif;
   min-width: 400px;
   box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
   background-color: #43A5BE;
   color: #ffffff;
   text-align: left;
   font-size:1em;
}
.styled-table th {
   padding: 7px 15px;
}
.styled-table td {
   padding: 7px 15px;
}
.styled-table tbody tr {
   border-bottom: 1px solid #dddddd;
}
.styled-table tbody tr:nth-of-type(even) {
   background-color: #f3f3f3;
}
.styled-table tbody tr:last-of-type {
   border-bottom: 2px solid #43A5BE;
}
.styled-table tbody tr.active-row {
   font-weight: bold;
   color: #43A5BE;
}

    .resize-wrapper {
        resize: both;
        overflow: auto;
        border: 1px solid #ccc;
        min-width: 200px;
        min-height: 200px;
        padding: 10px;
    }
    
    .table-container {
        width: 100%;
        overflow-x: auto;
    }
    
    .styled-table {
        width: 100%;
        border-collapse: collapse;
    }
</style>