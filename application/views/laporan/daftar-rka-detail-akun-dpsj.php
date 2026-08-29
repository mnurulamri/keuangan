<?php
//echo '<pre>';print_r($result);echo '</pre>';

?>

<table class="styled-table" id="tabel-modal">
	<thead>
    <tr>
        <th style="background:#fff; border:none" colspan="2"><input type="text" id="nomor_pengajuan-search-modal" onkeyup="search_modal()" placeholder="Filter Nomor Pengajuan.." class="table-search-filters-modal"></th>
        <th style="background:#fff; border:none"><input type="text" id="kode_kegiatan-search-modal" onkeyup="search_modal()" placeholder="Filter Kode Kegiatan.." class="table-search-filters-modal"></th>
        <th style="background:#fff; border:none"></th>
        <th style="background:#fff; border:none"></th>
        <th style="background:#fff; border:none"><input type="text" id="kode_akun-search-modal" onkeyup="search_modal()" placeholder="Filter Kode Akun.." class="table-search-filters-modal"></th>
        <th style="background:#fff; border:none"></th>
        <th style="background:#fff; border:none"></th>
    </tr>
	<tr class="header">
        <th>NOMR PENGAJUAN</th>
		<th>KODE KEGIATAN</th>
		<th>NAMA KEGIATAN</th>
		<th>KODE DANA</th>
		<th>KODE AKUN</th>
		<th>DESKRIPSI AKUN</th>
		<th>KOMITMEN</th>
		<th>AKTUAL</th>
        <th>STATUS</th>
        <th>FORM</th>
        <th>TGL_TERIMA</th>
	</tr>
	</thead>
	<tbody>
		<?php
		foreach($result as $row){
			echo '
			<tr class="item">
				<td>'.$row['nomor_pengajuan'].'</td>
				<td>'.$row['kode_kegiatan'].'</td>
				<td>'.$row['nama_kegiatan'].'</td>
				<td>'.$row['kode_dana'].'</td>
				<td>'.$row['kode_akun'].'</td>
				<td>'.$row['deskripsi_akun'].'</td>
				<td class="text-right">'.number_format($row['komitmen_report']).'</td>
				<td class="text-right">'.number_format($row['aktual_report']).'</td>
				<td>'.nama_status($row['kode_status']).'</td>
                <td>'.$row['form'].'</td>
                <td class="text-center">'.substr($row['anggaran_tgl_disetujui'], 0, 10).'</td>
			</tr>
			';
		}
		?>
	</tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f8f9fa;">
            <td colspan="6" style="text-align: right;">TOTAL:</td>
            <td class="text-right" id="total-komitmen-modal">0</td>
            <td class="text-right" id="total-aktual-modal">0</td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
var input_nomor_pengajuan_modal = document.getElementById("nomor_pengajuan-search-modal");
var input_kode_kegiatan_modal = document.getElementById("kode_kegiatan-search-modal");
var input_kode_akun_modal = document.getElementById("kode_akun-search-modal");
var table_modal = document.getElementById("tabel-modal");
var totalKomitmenElement = document.getElementById("total-komitmen-modal");
var totalAktualElement = document.getElementById("total-aktual-modal");
search_modal();
function search_modal() {
    let filter_nomor_pengajuan = input_nomor_pengajuan_modal.value.toUpperCase();
    let filter_kode_kegiatan = input_kode_kegiatan_modal.value.toUpperCase();
    let filter_kode_akun = input_kode_akun_modal.value.toUpperCase();
    let tr = table_modal.rows;
    
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
    search_modal();
});

</script>

<style>
.table-search-filters-modal {
    margin: 5px;
	margin-bottom: 5px;
	padding: 5px;
	border: 1px solid #ddd;
	border-radius: 4px;
	background-color: #f9f9f9;
    color:#444;
}
.styled-table {
   border-collapse: collapse;
   font-size:12px;
   font-family: Arial, Helvetica, sans-serif;
   min-width: 400px;
   box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
    position: sticky;
    top: 0;
    z-index: 20;
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