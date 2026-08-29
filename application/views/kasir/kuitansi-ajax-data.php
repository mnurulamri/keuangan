<?php
//echo '<pre>';
//print_r($sql); 
//print_r($posts); 
//$nama_status = nama_status(11); print_r($nama_status);
//var_dump(array_keys($array_deskripsi_dpsj));
//echo '</pre>';
//exit();
?>

<div class="row" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 5px;">    
    
    <div class="col-md-6">
        <label>Filter Tgl Terima:</label>
        <div class="input-group">
            <input type="text" id="filterTanggal" class="form-control" placeholder="Contoh: 05 Juni 2026">
            <span class="input-group-btn">
                <button class="btn btn-default btn-clear" type="button" data-target="filterTanggal">Clear</button>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <label>Cari Nomor Pengajuan:</label>
        <div class="input-group">
            <input type="text" id="filterNomor" class="form-control" placeholder="Contoh: 006/ANG.06...">
            <span class="input-group-btn">
                <button class="btn btn-default btn-clear" type="button" data-target="filterNomor">Clear</button>
            </span>
        </div>
    </div>
</div>

<table class="styled-table" width="100%">
    <thead>
        <tr>
            <th>Tgl Terima Dokumen</th>
            <th>Nomor Pengajuan</th>
            <th>Unit</th>
            <th>Form</th>
            <th>Uraian</th>
            <th>Nominal Pengajuan</th>
            <th>Nominal Disetujui</th>
            <th>Nominal Cair</th>
            <!--<th>Tgl Penyerahan SPJ</th>-->
            <th>Catatan</th>
            <th>Status</th>
            <th>Aksi</th>
            <th style="background-color:#DE3163; text-align:center">
                Data yg akan Dicetak 
                <div style="padding:3px"> 
                    <input type="checkbox" name="checkall" class="checkall">
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $row): 
                // Set tanggal terima jika ada
                if (isset($row['tgl_terima']) && !empty($row['tgl_terima'])) {
                    $row['tgl_terima'] = date('Y-m-d', strtotime($row['tgl_terima']));
                } else {
                    $row['tgl_terima'] = '';
                }

                switch ($row['kode_status']) {
					case 11:
						//code block
						$keterangan = $row['anggaran_keterangan_disetujui'];
						break;
					case 12:
						//code block;
						$keterangan = $row['anggaran_keterangan_pending'];
						break;
					case 21:
						//code block
						$keterangan = $row['anggaran_keterangan_pending'];
						break;
					default:
						//code block
						$keterangan = '-';
                }
            ?>
                <tr>
                    <td><?= (isset($row['anggaran_tgl_disetujui'])|| !empty($row['anggaran_tgl_disetujui']) || is_null($row['anggaran_tgl_disetujui'])) ? dateTimeToTanggal($row['anggaran_tgl_disetujui']) : '' ?></td>
                    <td><?= htmlspecialchars($row['nomor_pengajuan'] ?? '') ?></td>
                    <td><?= htmlspecialchars($array_deskripsi_dpsj[$row['kode_dpsj']] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['form'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['uraian'] ?? '') ?></td>
                    <td><?= isset($row['nominal_pengajuan']) ? number_format($row['nominal_pengajuan'], 0, ',', '.') : '' ?></td>
                    <td><?= isset($row['nominal_disetujui_umko']) ? number_format($row['nominal_disetujui_umko'], 0, ',', '.') : '' ?></td>
                    <td><?= isset($row['nominal_umko_cair']) ? number_format($row['nominal_umko_cair'], 0, ',', '.') : '0' ?></td>
                    <!--
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-controlx tgl_penyerahan_spj_umko" value="<?= dbToTanggal($row['tgl_penyerahan_spj_umko'] ?? '') ?>">
                            <span class="input-group-addon btn btn-primary btn-xs simpan-tgl-terima-spj" data-id="<?=$row['id']?>">simpan</span>
                        </div>                        
                    </td>
                    -->
                    <td>
                        <!--<button class="btn btn-info btn-xs view-catatan" data-id="<?=$row['id']?>" data-toggle="modal" data-target="#modal-catatan">View</button>-->
                        <button class="btn btn-info btn-xs fetch-logs" data-nomor_pengajuan="<?=$row['nomor_pengajuan']?>" data-no_pp="<?=$row['no_pp']?>" data-toggle="modal" data-target="#modal-catatan">View</button>
                    </td>
                    <td id="status_<?=$row['id']?>"><?= nama_status($row['kode_status']) ?? $row['kode_status'] ?></td>
                    <td>
                        
                        <?php if ($row['kode_status'] == 31): ?>
                            <button id="btn_pengembalian_<?=$row['id']?>" class="btn btn-primary btn-xs approval" data-id_pengajuan_pemohon="<?=$row['id_pengajuan_pemohon']?>" data-id_monitoring="<?=$row['id']?>" data-kode_dpsj="<?=$row['kode_dpsj']?>" data-toggle="modal" data-target="#modal-approval" >Proses</button>
                        <?php else: ?>
                            <button class="btn btn-info btn-xs detail" data-id_pengajuan_pemohon="<?= $row['id_pengajuan_pemohon'] ?>" data-id_monitoring="<?= $row['id'] ?>" data-kode_dpsj="<?=$row['kode_dpsj']?>" data-toggle="modal" data-target="#modal-approval" >Detail</button>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                        <input type="checkbox" name="checkcetak[]" class="checkcetak" value="<?=$row['id']?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" align="center">Data tidak ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="11"></td>
            <td class="text-center">
                <button id="konfirmasi" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-kuitansi" style="background-color:#DE3163;">cetak</button>
            </td>
        </tr>
        
    </tfoot>
</table>

<div class="row text-right">
    
</div>

<?php echo $this->ajax_pagination_kuitansi->create_links(); ?>

<script>
// disable tombol cetak excel saat pertama load
$("#konfirmasi").prop("disabled", true);

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

// fungsi konfirmasi cetak kuitansi
$('#konfirmasi').click(function() {
    var id_monitoring = $('.checkcetak:checked').map(function(_, el) {
        return $(el).val();
    }).get();

    // tampilkan nomor pengajuan, uraian, dan nominal pada modal konfirmasi
    // ambil data pengajuan menggunakan AJAX
    $.ajax({
        url: "<?=base_url()?>/kasir/kuitansi/konfirmasiCetak",
        type: "POST",
        data: {id_monitoring:id_monitoring},
        success: function(response) {
            // Tampilkan pesan sukses atau lakukan tindakan lain
            //alert("Data berhasil disimpan!");
            $("#data-kuitansi").html(response);
            console.log(response);
        },
        error: function(xhr, status, error) {
            // Tampilkan pesan kesalahan
            //alert("Terjadi kesalahan saat ...");
            //console.log(error);
        }
    });

    // set nilai ke input hidden di modal otorisasi
    $('#ids_kuitansi').val(id_monitoring.join(","));

});

// Fungsi untuk menyaring baris tabel
function jalankanFilter() {
    var valNo = $("#filterNomor").val().toLowerCase();
    var valTgl = $("#filterTanggal").val().toLowerCase();

    $(".styled-table tbody tr").filter(function() {
        var row = $(this);
        var textNo = row.find("td:eq(1)").text().toLowerCase(); // Kolom ke-2 (Nomor Pengajuan)
        var textTgl = row.find("td:eq(0)").text().toLowerCase(); // Kolom ke-1 (Tgl Terima Dokumen)
        
        // Tampilkan baris jika kedua filter cocok
        $(this).toggle(textNo.indexOf(valNo) > -1 && textTgl.indexOf(valTgl) > -1);
    });
}

// Event trigger saat mengetik di input
$("#filterNomor, #filterTanggal").on("keyup change", function() {
    jalankanFilter();
});

// fungsi clear filter
$(".btn-clear").click(function() {
    var target = $(this).data("target");
    $("#" + target).val("");
    jalankanFilter();
});

</script>