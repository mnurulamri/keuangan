<?php
//echo '<pre>';print_r($result);echo '</pre>';
if($result == 'data belum tersedia'){
	echo 'tidak ada data';
	return;
	//exit();
}
?>

<table class="styled-table" id="tabel">
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
	<tr class="header">
        <th>NOMOR PENGAJUAN</th>
		<th>KODE KEGIATAN</th>
		<th>NAMA KEGIATAN</th>
		<th>KODE DANA</th>
		<th>KODE AKUN</th>
		<th>DESKRIPSI AKUN</th>
		<th>MUTASI</th>
	</tr>
	</thead>
	<tbody>
		<?php
		foreach($result as $row){
			echo '
			<tr class="item">
				<!--<td></td>-->
				<td>'.$row['nomor_pengajuan'].'</td>
				<td>'.$row['kode_kegiatan'].'</td>
				<td>'.$row['nama_kegiatan'].'</td>
				<td>'.$row['kode_dana'].'</td>
				<td>'.$row['kode_akun'].'</td>
				<td>'.$row['deskripsi_akun'].'</td>
				<td class="text-right">'.number_format($row['mutasi']).'</td>
				
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

<style>
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