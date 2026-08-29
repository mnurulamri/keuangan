<?php
//echo '<pre>';print_r($result);echo '</pre>';

?>
<table class="styled-table" id="data-tabel">
	<thead>
	<tr>
		<th>KODE DPSJ</th>
		<th>DESKRIPSI DPSJ</th>
		<th>ANGGARAN</th>
		<th>KOMITMEN</th>
		<th>AKTUAL</th>
		<th>MUTASI</th>
		<th>SISA ANGGARAN</th>
	</tr>
	</thead>
	<tboby>
		<?php
		if($result=='data belum tersedia'){
			echo '<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>';
			return;
		}
		
		$grand_total_anggaran = 0;
		$grand_total_komitmen = 0;
		$grand_total_aktual = 0;
		$grand_total_mutasi = 0;
		$grand_total_sisa_anggaran = 0;
		
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
				$toggle_komitmen = 'data-toggle="custom-modal" data-target="customModal"'; //'data-toggle="modal" data-target="#modal-akun"';
			}

			if($row['aktual'] > 0){
				$style_aktual = 'style="cursor:pointer; color:green" onMouseOver="this.style.color=\'red\'" onMouseOut="this.style.color=\'green\'"';
				$toggle_aktual = 'data-toggle="custom-modal" data-target="customModal"'; //'data-toggle="modal" data-target="#modal-akun"';
			}

			if($row['flag_count_mutasi'] > 0){
				$style_mutasi = 'style="cursor:pointer; color:#A31497" onMouseOver="this.style.color=\'red\'" onMouseOut="this.style.color=\'#A31497\'"';
				$detail_mutasi = 'detail-mutasi';
				$toggle_mutasi = 'data-toggle="modal" data-target="#modal-akun"';
			}

			// hitung grand total
			$grand_total_anggaran += $row['anggaran'];
			$grand_total_komitmen += $row['komitmen'];
			$grand_total_aktual += $row['aktual'];
			$grand_total_mutasi += $row['mutasi'];
			$grand_total_sisa_anggaran += $row['sisa_anggaran'];

			echo '
			<tr>
				<td class="text-center detail-all" '.$style_sisa_anggaran.' data-kode_dpsj="'.$row['kode_dpsj'].'" style="cursor:pointer; color:#690450" onMouseOver="this.style.color=\'#c83434\'" onMouseOut="this.style.color=\'#690450\'">'.$row['kode_dpsj'].'</td>
				<td>'.$row['deskripsi_dpsj'].'</td>
				<td class="text-right detail" '.$style_anggaran.'>'.number_format($row['anggaran']).'</td>
				<td class="text-right detail" '.$style_komitmen.' data-kode_dpsj="'.$row['kode_dpsj'].'" '.$toggle_komitmen.'>'.number_format($row['komitmen']).'</td>
				<td class="text-right detail" '.$style_aktual.' data-kode_dpsj="'.$row['kode_dpsj'].'" '.$toggle_aktual.'>'.number_format($row['aktual']).'</td>
				<td class="text-right '.$detail_mutasi.'" '.$style_mutasi.' data-kode_dpsj="'.$row['kode_dpsj'].'" '.$toggle_mutasi.'>'.number_format($row['mutasi']).'</td>
				<td class="text-right detail-all" '.$style_sisa_anggaran.' data-kode_dpsj="'.$row['kode_dpsj'].'" style="cursor:pointer; color:#690461" onMouseOver="this.style.color=\'#c83434\'" onMouseOut="this.style.color=\'#690461\'">'.number_format($row['sisa_anggaran']).'</td>				
			</tr>
			';
		}
		?>
	</tboby>
	<tfoot>
		<tr>
			<td colspan="2"></td>
			<td class="text-right" style="font-weight:bold;"><?=number_format($grand_total_anggaran)?></td>
			<td class="text-right" style="font-weight:bold;"><?=number_format($grand_total_komitmen)?></td>
			<td class="text-right" style="font-weight:bold;"><?=number_format($grand_total_aktual)?></td>	
			<td class="text-right" style="font-weight:bold;"><?=number_format($grand_total_mutasi)?></td>			
			<td class="text-right" style="font-weight:bold;"><?=number_format($grand_total_sisa_anggaran)?></td>
		</tr>
	</tfoot>
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