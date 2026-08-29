<?php
//echo '<pre>';print_r($result);echo '</pre>';
if($result == 'data belum tersedia'){
	echo 'tidak ada data';
	return;
	//exit();
}
?>

<table class="table" id="tabel">
	<thead>
    <tr>
		<!--
        <th style="background:#fff; border:none" colspan="2"><input type="text" id="nomor_pengajuan-search" onkeyup="search()" placeholder="Nomor Pengajuan.." class="table-search-filters"></th>
        <th style="background:#fff; border:none"><input type="text" id="kode_kegiatan-search" onkeyup="search()" placeholder="Kode Kegiatan.." class="table-search-filters"></th>
        <th style="background:#fff; border:none"></th>
        <th style="background:#fff; border:none"></th>
        <th style="background:#fff; border:none"><input type="text" id="kode_akun-search" onkeyup="search()" placeholder="Kode Akun.." class="table-search-filters"></th>
        <th style="background:#fff; border:none"></th>
        <th style="background:#fff; border:none"></th>
		-->
    </tr>
	<tr class="header" style="color:#555">
        <!--<th>NOMR PENGAJUAN</th>-->
		<th>KODE KEGIATAN</th>
		<!--<th>NAMA KEGIATAN</th>-->
		<th>KODE DANA</th>
		<th>KODE AKUN</th>
		<!--<th>DESKRIPSI AKUN</th>-->
		<th>MUTASI</th>
	</tr>
	</thead>
	<tbody>
		<?php
		foreach($result as $row){
			echo '
			<tr class="item">
				<!--<td></td>-->
				<td>'.$row['kode_kegiatan'].'</td>
				<!--<td></td>-->
				<td>'.$row['kode_dana'].'</td>
				<td>'.$row['kode_akun'].'</td>
				<!--<td></td>-->
				<td>'.number_format($row['mutasi']).'</td>
				
			</tr>
			';
		}
		?>
	</tbody>
	<!--
    <tfoot>
        <tr style="font-weight: bold; background-color: #f8f9fa;">
            <td colspan="6" style="text-align: right;">TOTAL:</td>
            <td id="total-komitmen">0</td>
            <td id="total-aktual">0</td>
        </tr>
    </tfoot>
	-->
</table>

<script type="text/javascript">
var input_nomor_pengajuan = document.getElementById("nomor_pengajuan-search");
var input_kode_kegiatan = document.getElementById("kode_kegiatan-search");
var input_kode_akun = document.getElementById("kode_akun-search");
var table = document.getElementById("tabel");
var totalKomitmenElement = document.getElementById("total-komitmen");
var totalAktualElement = document.getElementById("total-aktual");
search();
function search() {
    let filter_nomor_pengajuan = input_nomor_pengajuan.value.toUpperCase();
    let filter_kode_kegiatan = input_kode_kegiatan.value.toUpperCase();
    let filter_kode_akun = input_kode_akun.value.toUpperCase();
    let tr = table.rows;
    
    let totalKomitmen = 0;
    let totalAktual = 0;
    
    for (let i = 0; i < tr.length; i++) {
        // Lewati baris header (baris 0 dan 1) dan baris footer
        if (i < 2 || tr[i].parentNode.nodeName === 'TFOOT') continue;
        
        let td = tr[i].cells;
        let td_nomor_pengajuan = td[0].innerText;
        let td_kode_kegiatan = td[1].innerText;
        let td_kode_akun = td[4].innerText;
        
        if (td_nomor_pengajuan.toUpperCase().indexOf(filter_nomor_pengajuan) > -1 && 
            td_kode_kegiatan.toUpperCase().indexOf(filter_kode_kegiatan) > -1 && 
            td_kode_akun.toUpperCase().indexOf(filter_kode_akun) > -1) {
            
            tr[i].style.display = "";
            
            // Hanya tambahkan total jika baris ditampilkan
            // Ambil nilai dari kolom KOMITMEN dan AKTUAL
            let komitmenText = td[6].innerText.replace(/,/g, ''); // Hapus koma dari format angka
            let aktualText = td[7].innerText.replace(/,/g, '');
            
            // Konversi ke number dan tambahkan ke total
            totalKomitmen += parseFloat(komitmenText) || 0;
            totalAktual += parseFloat(aktualText) || 0;
            
        } else {
            tr[i].style.display = "none";
        }
    }
    
    // Update tampilan total dengan format angka
    totalKomitmenElement.textContent = formatNumber(totalKomitmen);
    totalAktualElement.textContent = formatNumber(totalAktual);
}

// Fungsi untuk memformat angka dengan pemisah ribuan
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Tunggu sampai DOM sepenuhnya dimuat
document.addEventListener('DOMContentLoaded', function() {
    search();
});

</script>

<style>
table#tabel tr td {
    /*font-family:arial;*/
    font-size:13px
}
tfoot {
    background-color: #f8f9fa;
    font-weight: bold;
}
tfoot td {
    padding: 10px;
    border-top: 2px solid #dee2e6;
}
</style>