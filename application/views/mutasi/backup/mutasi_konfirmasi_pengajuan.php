<?php
if (!$this->session->userdata('kode_bidang')) {
    //redirect('auth/unit_kerja');
}
//echo '<pre>'; echo 'tahun ';print_r($tahun); echo 'bulan '; print_r($bulan); print_r($result); echo '</pre>';exit();
?>

    <div class="box box-warning collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title"><b>Data Pemohon</b></h3>
            <div class="box-tools pull-right">
                <!-- Collapse Button -->
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <!-- The icon will automatically change between fa-minus and fa-plus when clicked -->
                    <i class="fa fa-plus"></i> 
                </button>
                <!-- Remove Button (Optional) -->
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="display: none;">
            <!-- Your box content goes here -->
                                         
            <div class="row">                                    
                <div class="form-group">
                    <label for="nomor_pengajuan" class="col-sm-4 control-label text-right">Nomor Pengajuan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nomor_pengajuan" name="nomor_pengajuan" value="<?= $preview_nomor ?>" readonly>
                    </div>
                </div>                                        
                <br><br>
                <div class="form-group">
                    <label for="tanggal" class="col-sm-4 control-label text-right">Tanggal Pengajuan</label>
                    <div class="col-sm-8">                                                
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="unit_id" class="col-sm-4 control-label text-right" text-right>PAF/Dept/Prog/Unit</label>
                    <div class="col-sm-8">                                                
                        <input type="text" class="form-control" id="nama_unit" name="nama_unit" value="<?= $nama_unit ?>">
                        <input type="hidden" id="kode_bidang" name="kode_bidang" value="<?= $kode_bidang ?>">                                            
                        <!--
                        <select class="form-control" id="kode_bidang" name="kode_bidang" required>
                            <option value="">Pilih Unit</option>
                            <?php
                            // Loop through the units and create an option for each
                            foreach($units as $unit){
                                // Check if the unit is selected
                                $selected = ($unit->kode_bidang == $kode_bidang) ? 'selected' : '';
                            ?>    
                                <option value="<?= $unit->kode_bidang ?>" <?= $selected ?>><?= $unit->nama_unit ?></option>
                            <?php } ?>
                        </select>
                        -->
                    </div>
                    <div class="form-group">
                        <label for="penanggung_jawab" class="col-sm-4 control-label text-right">Penanggung Jawab/Contact Person</label>   
                        <div class="col-sm-8">
                            <?php
                            $i = 0;
                            $j = 0; // untuk tombol tambaj rincian                                                                                      
                            foreach($pejabat as $row){
                                $i++;
                                $j++;
                            }
                            
                            if ($i==1) {
                                echo '<input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" value="'.$row['nama'].'">';
                            } else {                                                    
                                echo '
                                <select class="form-control" id="penanggung_jawab" name="penanggung_jawab">';
                                    foreach($pejabat as $row){
                                        echo '<option value="'.$row['nama'].'">'.$row['nama'].'</option>';
                                    }
                                echo '</select>';
                            }                                                
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nip" class="col-sm-4 control-label text-right">NPM/NIP/NUP</label> 
                        <div class="col-sm-8">
                            <?php
                            $i = 0;                                                                                               
                            foreach($pejabat as $row){
                                $i++;
                            }
                            
                            if ($i==1) {
                                echo '<input type="text" class="form-control" id="nip" name="nip" value="'.$row['nip'].'">';
                            } else {                                                    
                                echo '<input type="text" class="form-control" id="nip" name="nip" value="'.$pejabat[0]['nip'].'">';
                            }  
                            ?>    
                        </div>                                            
                    </div>
                    <div class="form-group">
                        <label for="telepon" class="col-sm-4 control-label text-right">No Telepon</label>
                        <div class="col-sm-8">
                            <?php
                            $i = 0;                                                                                               
                            foreach($pejabat as $row){
                                $i++;
                            }
                            
                            if ($i==1) {
                                echo '<input type="text" class="form-control" id="telepon" name="telepon" value="'.$row['telp'].'">';
                            } else {                                                    
                                echo '<input type="text" class="form-control" id="telepon" name="telepon" value="'.$pejabat[0]['telp'].'">';
                            }  
                            ?>
                        </div>                                                    
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer" style="display: none;">
            <!-- Optional footer content -->
            Footer content is also hidden.
        </div>
        <!-- /.box-footer -->
    </div>
    <!-- /.box -->

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><b>Data Mutasi</b></h3>
            <div class="box-tools pull-right">
                <!-- Collapse Button -->
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <!-- The icon will automatically change between fa-minus and fa-plus when clicked -->
                    <i class="fa fa-plus"></i> 
                </button>
                <!-- Remove Button (Optional) -->
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <!-- Your box content goes here 
            <div class="row text-center">
                <div class="form-group form-inline col-sm-12">
                    <label for="tahun" class=" control-label text-right">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" >
                        <?php echo optTahun($tahun); ?>
                    </select>
                    <label for="bulan" class=" control-label text-right">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control" >
                        <?php echo optBulan($bulan); ?>
                    </select>
                </div>                                   
            </div>
			-->
            <!-- rincian mutasi -->
            <div id="data-rincian-mutasi">
                <input type="hidden" class="form-control" id="untuk" name="untuk">
                <div class="row">
                    <div class="form-group">
                        <label for="untuk_nama" class="col-sm-3 control-label text-right">DPSJ</label>
                        <div class="col-sm-9">
                                <div class="input-group">                                                
                                    <?php
                                    $i = 0;                                                                                               
                                    foreach($array_dpsj as $row){
                                        $i++;
                                    }

                                    if ($i==1) {
                                        echo'
                                        <input type="text" class="form-control deskripsi_dpsj" id="deskripsi_dpsj" name="deskripsi_dpsj" autocomplete="off" value="'.$array_dpsj[0]['deskripsi_dpsj'].'">
                                        <span class="input-group-addon" id="kode_dpsj" style="width:145px; background-color:#ddd" readonly>'.$array_dpsj[0]['kode_dpsj'].'</span>
                                        <span class="input-group-addon btn btn-danger" id="clear_dpsj">clear</span>';
                                    } else { ?>                                                
                                        <input type="text" class="form-control deskripsi_dpsj" id="deskripsi_dpsj" name="deskripsi_dpsj" autocomplete="off" >
                                        <span class="input-group-addon" id="kode_dpsj" style="width:145px; background-color:#ddd" readonly></span>
                                        <span class="input-group-addon btn btn-danger" id="clear_dpsj">clear</span>
                                    <?php } ?>
                                </div>
                                <div id="kotaksugest_deskripsi_dpsj" ></div>
                        </div>
                    </div>
                </div>
                <!--<input type="hidden" class="form-control" id="uraian" name="uraian">-->

                <div style="line-height:14x;">&nbsp;</div>
                <input type="hidden" id="id" value="0" >
                <input type="hidden" id="newId" value="0" >
                

                <!-- Di bagian Rincian Pembayaran -->
                <table class="tablex table-borderedx" border="1" id="tabel-rincian">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%" colspan="2">Nomor dan Nama Project Costing</th>
                            <th width="25%" colspan="3">Nomor dan Nama Akun</th>
                            <th width="15%">Jumlah (Rp)</th>
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
                        foreach($result as $row){

                            // Inisialisasi variabel untuk menghitung sisa anggaran  
                            $kode_kegiatan = $row['kode_kegiatan'];
                            $kode_akun = $row['kode_akun'];
                            $kode_dana = $row['kode_dana'];

                            // Ambil sisa anggaran dari database
                            
                            $sisa = 0;
                            echo '
                            <tr>
                                <td id="'.$row['id'].'">'.$rowCount.'</td>
                                <td class="kode-kegiatan text-center" id="kode_kegiatan_'.$newId.'">'.$row['kode_kegiatan'].'</td>
                                <td class="nama-kegiatan" data-id="'.$newId.'">'.$row['nama_kegiatan'].'</td>
                                <td class="kode-akun text-center" id="kode_akun_'.$newId.'">'.$row['kode_akun'].'</td>
                                <td class="deskripsi-akun" id="akun_'.$newId.'" data-id="'.$newId.'">'.$row['deskripsi_akun'].'</td>
                                <td class="kode-dana text-center" id="dana_'.$newId.'">'.$row['kode_dana'].'</td>
                                <td class="jumlah text-right" id="jumlah_'.$newId.'">'.number_format($row['mutasi']).'</td>
                                <td class="keterangan" contenteditable="true">'.$row['keterangan'].'</td>
                                <td class="sisa_anggaran text-center" id="sisa_anggaran_'.$newId.'">'.number_format($row['sisa_anggaran']).'</td>                                
                            </tr>';
                            $rowCount++;
                            $newId++;    
                            $nominal_pengajuan += $row['mutasi'];                                                
                        }
                        ?>
                        <script>$("#newId").val(<?=$newId?>);</script>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"></td>
                            <td class="total"><?=number_format($nominal_pengajuan)?></td>
                            <td colspan="3"></td>
                        </tr>
						<!--
                        <tr>
                            <td colspan="9" style="border: 1px solid #fff">
                                <?php
                                if ($j==1){
                                    $disabled = '';
                                } else {
                                    $disabled = 'disabled';
                                }
                                ?>
                                <button class="btn btn-primary btn-sm" id="ajukan" data-kode_grup="<?= $kode_grup ?>" ><i class="fa fa-paper-plane"></i> Ajukan </button>
                            </td>
                        </tr>
						-->
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
            <br> 
            <div class="text-center"><?php if($this->session->userdata['logged_anggaran']['role'] == 'pum'){ ?>
	        	<button class="btn btn-primary btn-sm" id="ajukan" data-kode_grup="<?= $kode_grup ?>" data-tahun="<?= $tahun ?>" data-bulan="<?= $bulan ?>" data-kode_unit="<?= $kode_unit ?>" ><i class="fa fa-paper-plane"></i> Ajukan </button>  
			<?php } else if($this->session->userdata['logged_anggaran']['role'] == 'anggaran') { ?>
				<button class="btn btn-primary btn-sm" id="approve" data-kode_grup="<?= $kode_grup ?>" ><i class="fa fa-paper-plane"></i> Disetujui </button>  
				<button class="btn btn-warning btn-sm" id="dikembalikan" data-kode_grup="<?= $kode_grup ?>" ><i class="fa fa-undo"></i> Dikembalikan </button>  
				<button class="btn btn-danger btn-sm" id="dibatalkan" data-kode_grup="<?= $kode_grup ?>" ><i class="fa fa-minus-circle"></i> Dibatalkan </button>   
			<?php } ?>
            </div>			                
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
<?php //include(APPPATH.'views/mutasi/mutasi_form_edit_script.php');?>
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .panel, .panel * {
        visibility: visible;
    }
    .panel {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
    }
    .btn-remove-row, #btn-add-row, .box-footer {
        display: none !important;
    }
    .form-control {
        border: none;
        background: transparent;
        box-shadow: none;
    }
    input.form-control {
        border-bottom: 1px dotted #000;
    }
}

/* Untuk autocomplete
.ui-autocomplete {
    position: absolute;
    z-index: 1000;
    cursor: default;
    padding: 0;
    margin-top: 2px;
    list-style: none;
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
}

.ui-autocomplete > li {
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
}

.ui-autocomplete > li:hover, 
.ui-autocomplete > li.ui-state-focus {
    background-color: #f5f5f5;
    cursor: pointer;
}

.ui-helper-hidden-accessible {
    display: none;
}
 */

.kotak{
    background-color: #f7f7f7;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

}

/* Untuk tabel rincian */
#tabel-rincian {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background-color: #fff;
}
/*
#tabel-rincian td {
    vertical-align: middle !important;
    background-color: #fff;
}*/

#tabel-rincian .sisa-anggaran {
    font-weight: bold;
    color: #333;
}
/* autocomplete deskrip dpss */
table.autocomplete-dpsj {
	/*left: 30px;
	width:191px;*/
	position: absolute;
	z-index: 99;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	
}
table.autocomplete-dpsj tr {
	cursor: pointer;
}
table.autocomplete-dpsj tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-dpsj tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-dpsj tr td:hover {
	background-color: #ddd;
}


/* autocomplete deskrip pc */
table.autocomplete-pc {
	/*left: 30px;
	width:191px;*/
	position: absolute;
	z-index: 99;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	
}
/*table.autocomplete-pc tr {
	cursor: pointer;
}*/
table.autocomplete-pc tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-pc tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
/*table.autocomplete-pc tr td:hover {
	background-color: #ddd;
}*/

.isi_pc:hover, .isi_akun:hover {
    color:#fa0;
    cursor: pointer;
}

table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
}

.autocomplete {
    position: relative;
    display: inline-block;
}
.autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top