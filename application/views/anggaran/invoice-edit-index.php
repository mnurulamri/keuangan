<?php
//echo '<pre>';
//print_r($tgl); exit();
?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <label for="edit-tahun" class="col-sm-2 col-form-label text-right">Periode</label>
            
            <div class="col-sm-3">
                <select name="edit-tgl" id="edit-tgl" class="form-control select2">
                    <option value='00' selected>All</option>
                    <?php
                    for($d=1; $d<=31; $d++){
                        $day = str_pad($d, 2, "0", STR_PAD_LEFT);
                        $selected = ($day == $tgl) ? 'selected' : '';
                        echo "<option value='$day' $selected>$day</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-sm-3">
                <select name="edit-bulan" id="edit-bulan" class="form-control select2">
                    <?php echo optBulan($bulan); ?>
                </select>
            </div>

            <div class="col-sm-3">
                <select name="edit-tahun" id="edit-tahun" class="form-control select2">
                    <?php echo optTahun($tahun); ?>
                </select>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="edit-no_tiket" class="form-control" value="<?=$no_tiket?>" readonly>

<div class="row">
    
    <div class="col-md-12">
        <div class="form-group">
            <label for="edit-no_invoice_pp">No Invoice PP</label>
            <input type="text" id="edit-no_invoice_pp" class="form-control" value="<?=$no_invoice_pp?>" placeholder="Masukkan No Invoice">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="edit-uraian">Uraian</label>
            <textarea id="edit-uraian" class="form-control" rows="3" placeholder="Masukkan uraian..."><?=$uraian?></textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-primary edit-simpan-invoice">
            <i class="fa fa-save"></i> Simpan Perubahan
        </button>
    </div>
</div>

	
<script>
$(document).ready(function() {
    $('.edit-simpan-invoice').on('click', function() {
        var data = {
            tgl: $('#edit-tgl').val(),
            bulan: $('#edit-bulan').val(),
            tahun: $('#edit-tahun').val(),
            no_tiket: $('#edit-no_tiket').val(),
            no_invoice_pp: $('#edit-no_invoice_pp').val(),
            uraian: $('#edit-uraian').val()
        };

        $.ajax({
            url: "<?php echo base_url('invoice/edit_simpan'); ?>",
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response) {
                if(response.status == 'success') {
                    alert(response.message);
                    // Opsi: reload halaman atau tutup modal
                    $("#no_invoice_pp_"+$('#edit-no_tiket').val()).text(response.no_invoice_pp);
                    $("#uraian_"+$('#edit-no_tiket').val()).text(response.uraian);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan koneksi ke server.');
            }
        });
    });
});
</script>
