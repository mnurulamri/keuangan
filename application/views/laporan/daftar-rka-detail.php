<?php
//echo '<pre>';print_r($result);echo '</pre>';

?>
<table class="table">
	<thead>
	<tr>
		<th>kode_dpsj</th>
		<th>deskripsi_dpsj</th>
		<th>kode_kegiatan</th>
		<th>nama_kegiatan</th>
		<th>kode_dana</th>
		<th>deskripsi_dana</th>
		<th>kategori_kegiatan</th>
		<th>kode_akun</th>
		<th>deskripsi_akun</th>
		<th>anggaran</th>
		<th>komitmen</th>
		<th>aktual</th>
		<th>mutasi</th>
		<th>sisa_anggaran</th>
	</tr>
	</thead>
	<tboby>
		<?php
		foreach($result as $row){
			echo '
			<tr>
				<td>'.$row['kode_dpsj'].'</td>
				<td>'.$row['deskripsi_dpsj'].'</td>
				<td>'.$row['kode_kegiatan'].'</td>
				<td>'.$row['nama_kegiatan'].'</td>
				<td>'.$row['kode_dana'].'</td>
				<td>'.$row['deskripsi_dana'].'</td>
				<td>'.$row['kategori_kegiatan'].'</td>
				<td>'.$row['kode_akun'].'</td>
				<td>'.$row['deskripsi_akun'].'</td>
				<td>'.$row['anggaran'].'</td>
				<td>'.$row['komitmen'].'</td>
				<td>'.$row['aktual'].'</td>
				<td>'.$row['mutasi'].'</td>
				<td>'.$row['sisa_anggaran'].'</td>
				
			</tr>
			';
		}
		?>
	</tboby>
</table>