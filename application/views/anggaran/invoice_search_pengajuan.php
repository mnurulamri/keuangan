<?php
//echo '<pre>';print_r($sql);print_r($result);echo '</pre>';
?>
<div class="text-center text-warning" style="font-weight:bold; margin-bottom:10px">
    Tahun: <?= $tahun ?> | Bulan: <?= namaBulan($bulan) ?>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>NOMOR PENGAJUAN</th>
            <th>KETERANGAN</th>
            <th>KODE DPSJ</th>
            <th>DESKRIPSI DPSJ</th>
            <th>KOMITMEN</th>
            <th>AKTUAL</th>
            <th>NO INVOICE PP</th>
            <th style="background-color:#DE3163; text-align:center">
                <div style="padding:3px"> 
                    <input type="checkbox" name="checkall" class="checkall">
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($result as $row) {
        echo '<tr>';
        echo '<td>' . $row['nomor_pengajuan'] . '</td>';
        echo '<td>' . $row['untuk'] . '</td>';
        echo '<td>' . $row['kode_dpsj'] . '</td>';
        echo '<td>' . $row['deskripsi_dpsj'] . '</td>';
        echo '<td class="text-right">' . number_format($row['komitmen']) . '</td>';
        echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';
		echo '<td>' . $row['no_invoice_pp'] . '</td>'; 

		if(!isset($row['no_invoice_pp']) or empty($row['no_invoice_pp']) or $row['no_invoice_pp']==''){
        echo'
            <td style="text-align:center">
                <input type="checkbox" name="checkcetak[]" class="checkcetak" value="' . $row['id_pengajuan_pemohon'] . '">
            </td>';
		} else {
		echo '<td></td>';
		}
        echo '</tr>';    
    }
    ?>
    </tbody>
</table>

<div>
    <button class="btn btn-primary" id="konfirmasi">Lanjut</button>
</div>

<script>

// jika memeilih seluruh checkbox
$(".checkall").click(function()
{   
    $('.checkcetak:checkbox').not(this).prop('checked', this.checked);

    // aktifkan tombol cetak excel jika checklis sudah di centrang
    var checkcetak = $('.checkcetak:checked').map(function(_, el) {
        return $(el).val();
    }).get();
    
    if (Object.keys(checkcetak).length===0) {
        $("#konfirmasi").prop("disabled", true);
    } else {
        $("#konfirmasi").prop("disabled", false);
    }
    // jika dicontreng semua maka ambil kode organisasi pada baris pertama dari data yang ditampilkan
    console.log(Object.keys(checkcetak));
});

// jika memilih salah satu atau seluruh checkbox
$(".checkcetak").click(function()
{   
    // aktifkan tombol cetak excel jika checklis sudah di centrang
    var checkcetak = $('.checkcetak:checked').map(function(_, el) {
        return $(el).val();
    }).get();
    
    if (Object.keys(checkcetak).length===0) {
        $("#konfirmasi").prop("disabled", true);
    } else {
        $("#konfirmasi").prop("disabled", false);
    }
    console.log(Object.keys(checkcetak));
});

// fungsi konfirmasi lanjut proses invoice
$('#konfirmasi').click(function() {
    var id_pengajuan_pemohon = $('.checkcetak:checked').map(function(_, el) {
        return $(el).val();
    }).get();
    var no_invoice_pp = $('#no_invoice_pp').val();
    var uraian = $('#uraian').val();
    $.ajax({
        url: "<?=base_url()?>/invoice/get_data_procost",
        type: "POST",
        data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_invoice_pp:no_invoice_pp, uraian:uraian},
        success: function(response) {
            // Tampilkan pesan sukses atau lakukan tindakan lain
            //alert("Data berhasil disimpan!");
            $("#data-procost").html(response);
            console.log(response);
        },
        error: function(xhr, status, error) {
            // Tampilkan pesan kesalahan
            //alert("Terjadi kesalahan saat ...");
            //console.log(error);
        }
    });

    // set nilai ke input hidden di modal otorisasi
    //$('#ids_kuitansi').val(id_monitoring.join(","));

});
</script>