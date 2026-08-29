<?php
            $n = 1; // Initialize counter for rows
			$total_brut = 0;
			$total_net = 0;
            // Loop through the result_realisasi array to display each row
            foreach($result_realisasi as $key => $row) {
                echo '<tr>';
                echo '<td id="'.$row['id'].'">'.$n.'</td>';
                echo '<td class="tanggal" contenteditable="true">'.dbToTanggal($row['tanggal']).'</td>';
                echo '<td class="keterangan" contenteditable="true">'.$row['keterangan'].'</td>';
                echo '<td class="volume" contenteditable="true">'.$row['volume'].'</td>';
                echo '<td class="ket_volume" contenteditable="true">'.$row['ket_volume'].'</td>';
                echo '<td class="harga" contenteditable="true">'.number_format($row['harga']).'</td>';
                echo '<td class="bruto text-right" contenteditable="true">'.number_format($row['bruto']).'</td>';
                echo '<td class="persen_pajak" contenteditable="true">'.$row['persen_pajak'].'</td>';
                echo '<td class="pph" contenteditable="true">'.number_format($row['pph']).'</td>';
                echo '<td class="netto text-right" contenteditable="true">'.number_format($row['netto']).'</td>';
                echo '
                    <td>
                        <button type="button" class="btn btn-danger btn-xs btn-remove-row-db" id="'.$row['id'].'"><i class="fa fa-times"></i></button>
                    </td>';
                echo '</tr>';
                $n++;
				$total_brut=$total_brut+$row['bruto'];
				$total_net=$total_net+$row['netto'];
            }