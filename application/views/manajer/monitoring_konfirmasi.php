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

                                    <div class="col-sm-12 kotakx">
                                        
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

                        <div class="col-sm-12 kotakx">
                            <div class="box-header with-border text-center" style="line-height:7px"><b>Approval</b></div>
                            <br>
                            <div id="approvalForm" class="text-center">
                                <div class="form-group">
                                    <label for="manajer_keterangan" style="color:#555">Catatan</label>
                                    <textarea class="form-control" id="manajer_keterangan" name="manajer_keterangan" rows="3"><?=$manajer_keterangan?></textarea>
                                </div>
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
        var manajer_keterangan = $('#manajer_keterangan').val();
        var page = $("temp_page").val(); // halaman saat ini, bisa diubah sesuai kebutuhan
        // hitung total jumlah komitmen yang di setujui
        var total_disetujui = 0;
        $(".jumlah-disetujui").each(function(){
            var val = $(this).val().replace(/,/g, '');
            if(val != "")
            {
                total_disetujui += parseFloat(val);
            }
        });

        var array_jumlah_disetujui = [];
        $(".jumlah-disetujui").each(function(){
            var inputId = $(this).attr('id').replace('jumlah_disetujui_', '');
            // Ambil id dari kolom pertama pada baris yang sama
            var row = $(this).closest('tr');
            var id = row.find('td:first').attr('id');
            var jumlah = $(this).val().replace(/,/g, '');
            array_jumlah_disetujui.push({
                id: id,
                jumlah_disetujui: jumlah
            });
        });
        
        $.ajax({
            url: '<?=base_url("manajer/monitoring/approval")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                manajer_keterangan: manajer_keterangan,
                array_jumlah_disetujui:array_jumlah_disetujui,
                total_disetujui: total_disetujui
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