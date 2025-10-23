<?php
if (!$this->session->userdata('kode_bidang')) {
    //redirect('auth/unit_kerja');
}
//echo '<pre>';print_r($pejabat);echo '</pre>';
?>

                
                <!--<div class="panel panel-default">-->
                    <!--<div class="panel-body">-->
                        <div class="boxx box-primary">                            
                            <div class="box-header with-border text-center">
                                <div class="panel-title"><b>Data Pemohon</b></div>
                            </div>
                            <div class="box-body">
                                <!-- data pemohon -->
                                 <table id="pemohon">                                    
                                    <tr>
                                        <td width="20%" class="label">Nomor Pengajuan</td>
                                        <td>: </td>
                                        <td width="30%"> <?= $preview_nomor ?></td>
                                        <td width="20%" class="label">Penanggung Jawab</td>
                                        <td>: </td>
                                        <td width="30%"> <?= $pejabat[0]['nama'] ?></td>
                                    </tr>
                                    <tr>
                                        <td width="20%" class="label">Tanggal Pengajuan</td>
                                        <td>: </td>
                                        <td width="30%"> <?= dateTimeToTanggal($pejabat[0]['tgl_diajukan']) ?></td>
                                        <td width="20%" class="label">NPM/NIP/NUP</td>
                                        <td>: </td>
                                        <td width="30%"> <?= $pejabat[0]['nip'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">PAF/Dept/Prog/Unit</td>
                                        <td>: </td>
                                        <td> <?= $nama_unit ?></td>
                                        <td class="label">No Telepon</td>
                                        <td>: </td>
                                        <td> <?= $pejabat[0]['telp'] ?></td>
                                    </tr>
                                 </table>
                            </div>
                        </div>

                        <hr>
                        <!--<div class="box box-primary">
                            <div class="box-header with-border text-center">
                                <h3 class="box-title">Rincian Pembayaran</h3>
                            </div>
                            <div class="box-body" style="overflow:auto">-->
                                <div class="row" style="width:99%; margin:0 auto;">

                                    <div class="col-sm-12 kotak">
                                        
                                      <div class="box-header with-border text-center" style="line-height:7px;"><b>Rincian Pembayaran</b></div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="uraian" class="col-sm-3 control-label text-right" style="color:#555">Untuk</label>
                                                <div class="col-sm-9">
                                                    <?=$untuk?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="untuk_nama" class="col-sm-3 control-label text-right" style="color:#555">Atas Nama</label>
                                                <div class="col-sm-9">
                                                    <?= $array_dpsj[0]['deskripsi_dpsj'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <label for="untuk_nama">Untuk dan Atas Nama</label>
                                            <input type="text" class="form-control" id="uraian" name="uraian" required>
                                        </div>
                                        -->
                                        <input type="hidden" class="form-control" id="uraian" name="uraian">

                                        <div style="line-height:14x;">&nbsp;</div>

                                        <input type="hidden" id="id" value="0" >
                                        <input type="hidden" id="newId" value="0" >
                                        <input type="hidden" id="kode_dpsj" value="<?=$array_dpsj[0]['kode_dpsj']?>">
                                        <input type="hidden" id="kode_bidang" value="<?=$kode_bidang?>">

                                        <!-- Di bagian Rincian Pembayaran -->
                                        <div style="overflow:auto;">
                                            <table class="tablex table-borderedx" id="tabel-rincian">
                                                <thead>
                                                    <tr style="color:#555">
                                                        <th width="5%">No</th>
                                                        <th width="25%" colspan="2">Nomor dan Nama Project Costing</th>
                                                        <th width="25%" colspan="3">Nomor dan Nama Akun</th>
                                                        <th width="15%">Jumlah (Rp)</th>
                                                        <th width="15%">Jumlah Disetujui</th>
                                                        <th width="20%">Keterangan</th>
                                                        <th width="5%">Sisa Anggaran</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $rowCount = 1;
                                                    $newId = 1;
                                                    
                                                    $kode_dpsj = $array_dpsj[0]['kode_dpsj'];
                                                    $nominal_pengajuan = 0;
                                                    $nominal_disetujui = 0;

                                                    foreach($rincian as $row){

                                                        // Inisialisasi variabel untuk menghitung sisa anggaran  
                                                        $kode_kegiatan = $row['kode_kegiatan'];
                                                        $kode_akun = $row['kode_akun'];
                                                        $kode_dana = $row['kode_dana'];

                                                        // Ambil sisa anggaran dari database
                                                        
                                                        $sisa = 0;
                                                        echo '
                                                        <tr>
                                                            <td id="'.$row['id'].'">'.$rowCount.'</td>
                                                            <td class="kode-kegiatan" id="kode_kegiatan_'.$newId.'">'.$row['kode_kegiatan'].'</td>
                                                            <td class="nama-kegiatan" data-id="'.$newId.'">'.$row['nama_kegiatan'].'</td>
                                                            <td class="kode-akun" id="kode_akun_'.$newId.'">'.$row['kode_akun'].'</td>
                                                            <td class="deskripsi-akun" id="akun_'.$newId.'" data-id="'.$newId.'">'.$row['deskripsi_akun'].'</td>
                                                            <td class="kode-dana" id="dana_'.$newId.'">'.$row['kode_dana'].'</td>
                                                            <td class="jumlah" id="jumlah_'.$newId.'">'.number_format($row['komitmen']).'</td>
                                                            <td class="jumlah-disetujui" id="jumlah_disetujui_'.$newId.'">
                                                                '.number_format($row['komitmen_disetujui']).'
                                                            </td>                                                        
                                                            <td class="keterangan">'.$row['keterangan'].'</td>
                                                            <td class="sisa_anggaran" id="sisa_anggaran_'.$newId.'" data-sisa_anggaran="'.$sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana].'">'.$sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana].'</td>

                                                        </tr>';
                                                        $rowCount++;
                                                        $newId++;    
                                                        $nominal_pengajuan += $row['komitmen'];
                                                        $nominal_disetujui += $row['komitmen_disetujui'];                                              
                                                    }
                                                    ?>
                                                    <script>$("#newId").val(<?=$newId?>);</script>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="6" class="text-right"><b>Total: </b></td>
                                                        <td class="total"><?=number_format($nominal_pengajuan)?></td>
                                                        <td class="total-disetujui"><?=number_format($nominal_disetujui)?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" style="border: 1px solid #fff">             
                                                            
                                                        </td>
                                                    </tr>
                                                    <!--
                                                    <tr>
                                                        <td colspan="10" class="text-info" style="border: 1px solid #fff">
                                                            <ul>
                                                                <li><i>double klik pada kolom nama project costing, nama akun, jumlah dan keterangan untuk edit</i></li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    -->
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <!--</div>
                        </div>-->
                                                    <hr>
                    <div class="row" style="width:99%; margin:0 auto;">

                        <div class="col-sm-12 kotak">
                            <div class="box-header with-border text-center" style="line-height:7px"><b>Approval</b></div>
                            <br>
                            <div id="approvalForm" class="text-center">
                                <form class="form-horizontal">
                                    
                                    <div class="form-group">
                                        <label for="tgl_umko_cair" class="control-label col-xs-4" style="color:#555">Tanggal UMKO Cair :</label>
                                        <div class="col-xs-3">
                                            <input class="form-control" id="tgl_umko_cair" name="tgl_umko_cair" size="10" value="???" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nominal_umko_cair" class="control-label col-xs-4" style="color:#555">Nominal UMKO Cair :</label>
                                        <div class="col-xs-3">
                                            <input class="form-control" id="nominal_umko_cair" name="nominal_umko_cair" size="10" value="<?=number_format($nominal_disetujui_umko)?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="kasir_penerima" class="control-label col-xs-4" style="color:#555">Penerima :</label>
                                        <div class="col-xs-3">
                                            <input class="form-control" id="kasir_penerima" name="kasir_penerima" size="10" value="<?=$kasir_penerima?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="kasir_keterangan" class="control-label col-xs-4" style="color:#555">Catatan :</label>
                                        <div class="col-xs-8">
                                            <textarea class="form-control" id="kasir_keterangan" name="kasir_keterangan" rows="3"><?=$kasir_keterangan?></textarea>
                                        </div>
                                    </div> 
                                </form>
                                <button type="button" class="btn btn-warning" id="btnApprovalSave">Pending</button>
                                <button class="btn btn-success" id="setujui" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>"  data-id_monitoring="<?=$id_monitoring?>"><i class="fa fa-check"></i> Setujui</button>
                            </div>
                        </div>
                    </div>
                <!--</div>-->
                

<script>
$(document).ready(function()
{
    $('#setujui').on('click', function() {
        var id_monitoring = $(this).data('id_monitoring');
        var id_pengajuan_pemohon = '<?=$id_pengajuan_pemohon?>';
        var kasir_penerima = $('#kasir_penerima').val();
        var kasir_keterangan = $('#kasir_keterangan').val();
        var page = $("temp_page").val(); // halaman saat ini, bisa diubah sesuai kebutuhan
        var nominal_umko_cair = $('#nominal_umko_cair').val();

        $.ajax({
            url: '<?=base_url("kasir/monitoring/approval")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                kasir_penerima: kasir_penerima,
                kasir_keterangan: kasir_keterangan,
                nominal_umko_cair: nominal_umko_cair
            },
            //dataType: 'json',
            success: function(res) {
                getDataPageMonitoring(page); // refresh data
				$("#modal-approval").modal('hide');
                /*if(res.status == 'success') {
                    alert('Approval berhasil disimpan.');
                } else {
                    alert('Gagal menyimpan approval.');
                }*/
                console.log(res);
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan approval.');
            }
        });
    });

    $("#nominal_umko_cair").click(function() {
        $(this).select();
        $("#id").val($(this).data('id'));
    });

    $(document).on("keyup", "#nominal_umko_cair", function(evt){        
        // Cek apakah inputan hanya berisi angka
        let keyCode = $(this).val();
        let value = keyCode.replace(/[^\d.]/g, '');
        let jumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).val(jumlah);
    });    
});




function getDataPageMonitoring(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'monitoring_ajax/data/'+page;
	}
	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy },
		success: function(data){
			$('#postList').html(data);
            $('.loading-overlay').fadeOut("slow");
		}
	});
}
</script>

<?php 
include (APPPATH . 'views/template/css/style_form_konfirmasi.php'); 
?>