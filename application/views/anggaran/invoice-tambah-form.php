<?php
$posts = $data['posts'];
$array_rincian = $data['array_rincian'];

if(!isset($posts) or empty($posts)){
    echo 'belum ada data pengajuan';
}
//print_r($no_invoice_pp);exit();
$array = array(); // inisialisasi array untuk menyimpan rincian

// set opt tahun
$opt_tahun ='';
foreach($array_tahun as $row){
	$opt_tahun .= '<option value="'.$row.'">'.$row.'</option>';
}

// set opt bulan
$nama_bulan = array(
	'01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
);
$opt_bulan ='';
$selected = '';
foreach($array_bulan as $row){
	$selected = ($row == $bulan_select) ? 'selected' : '';
	$opt_bulan .= '<option value="'.$row.'" '.$selected.'>'.$nama_bulan[$row].'</option>';
}
$head_invoice = $tgl.'-'.$bulan.'-'.$tahun;
?>

                <!-- Konten utama Anda di sini -->
         <input type="hidden" id="temp_page" value="0" style="display:nonex">
	<div class="containerx" style="padding:2px">
	    
	            <div class="row text-center">
	               
                    <input type="hidden" id="tgl_select" value="<?=$tgl?>" >
                    <input type="hidden" id="bulan_select" value="<?=$bulan?>" >
                    <input type="hidden" id="tahun_select" value="<?=$tahun?>" >
                    <input type="hidden" id="no_invoice_pp_select" value="<?=$no_invoice_pp?>" >
                    <input type="hidden" id="uraian_select" value="<?=$uraian?>" >
                    <input type="hidden" id="no_tiket_select" value="<?=$no_tiket?>" >
                    
                    <table class="styled-table invoice-table" width="100%" style="display:nonex;">
                        
                            <tr style="background-color:#fff; color:#43A5BE">
                                <th>PERIODE</th> 
                                <th>NO INVOICE PP</th>
                                <th>URAIAN</th>
                            </tr>
                            <tr style="color:#696969">
                                <th><?=$head_invoice?></th>
                                <th><?=$no_invoice_pp?></th>
                                <th><?=$uraian?></th>
                            </tr>
                       
                    </table>
	            </div>
	                
<!-- Modal -->
<div id="myModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
        <h3>Judul Modal</h3>
        <p>Isi konten modal Anda di sini...</p>
    </div>
</div>

<hr>
<h4 class="text-center">
    Cari Procost
</h4>
<div class="row text-center">

	                <div class="form-group form-inline col-sm-12">
	                    <label for="tahun2" class=" control-label text-right">Tahun</label>
	                    <select name="tahun2" id="tahun2" class="form-controlx" >
	                        <?php echo $opt_tahun; ?>
	                    </select>
	                    <label for="$bulan2" class=" control-label text-right">Bulan</label>
	                    <select name="$bulan2" id="bulan2" class="form-controlx" onchange="searchTable2()">
	                        <?php echo $opt_bulan; ?>
	                    </select>
    					<label for="keywords2" class=" control-label text-right">Nomor Pengajuan</label>
    	            	<input type="text" id="searchInput2" placeholder="cari Nomor Pengajuan.." onkeyup="searchTable2()"/>
			
            <button class="btn btn-primary btn-sm" id="tambah-procost">Tambah Procost</button>
       
	                </div>            
</div>
	              
        <div class="text-center"> </div>
        
<div class="post-list table-container" id="postList">
	                  
        <?php 
        //echo '<pre>'; print_r($data['sql']);echo '</pre>';
        
$html= '

<table class="styled-table" id="examplex2" width="100%" >
    <thead>
        <tr>
            <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
            <th>Norut</th>
            <th style="border-left:2px solid #ddd;">Tgl Pengajuan</th>
            <th>Nomor Pengajuan</th>
            <th>Atas Nama</th>
            <th>Untuk</th>
            <th>Form</th>
            <th>Komitmen</th>
            <th>Bruto</th>
            <th>Pajak</th>
            <th>Netto</th>
            <th>Status</th>
            <th>Catatan</th>
            <th style="display:none;"></th>
        </tr>
    <tbody>';

$barisKe = 1;
$norut = 1;
foreach($posts as $key => $value) {

    // jika $key nomor_pengajuan tidak ada di $posts, berarti belum ada $nomor_pengajuan
    if(!isset($posts[$key]['nomor_pengajuan']) or $posts[$key]['nomor_pengajuan'] == '') {
        $nomor_pengajuan = '-'; // set nomor pengajuan ke -
    }   
    else {
        $nomor_pengajuan = $posts[$key]['nomor_pengajuan']; // ambil nomor pengajuan dari posts
    }

    // jika $key untuk tidak ada di $posts, berarti belum ada $untuk
    if(!isset($posts[$key]['untuk']) or $posts[$key]['untuk'] == '') {
        $untuk = '-'; // set untuk sama dengan kosong
    }   
    else {
        $untuk = $posts[$key]['untuk']; // ambil untuk dari posts
    }

    // jika $array_monitoring[$key] tidak ada, berarti belum ada pengajuan
    if(!isset($array_monitoring[$key])) {
        $array_monitoring[$key] = 0; // set status ke 0 (belum diajukan)
    }

    // set nama status berdasarkan $array_nama_status
    if(nama_status($array_monitoring[$key])) {
        // jika ada status, ambil nama statusnya
        $status = nama_status($array_monitoring[$key]);

        // jika statusnya belum diajukan, set disabled
        if($array_monitoring[$key] == 0) {
            $disabled = '';
            $disabled_delete = '';
            $text_ajukan = 'ajukan';
            $text_decoration = '';
        } else if($array_monitoring[$key] == 12 || $array_monitoring[$key] == 33 || $array_monitoring[$key] == 52 || $array_monitoring[$key] == 63) {
            $disabled = '';
            $disabled_delete = '';
            $text_ajukan = 'ajukan ulang';
            $text_decoration = '';
        } else if($array_monitoring[$key] == 14 || $array_monitoring[$key] == 43 || $array_monitoring[$key] == 53 || $array_monitoring[$key] == 64) {
            $disabled = 'disabled';            
            $disabled_delete = '';
            $text_ajukan = 'ajukan';
            $text_decoration = 'text-decoration: line-through;';
        } else {
            $disabled = 'disabled';            
            $text_ajukan = 'ajukan';
            $text_decoration = '';
            $disabled_delete = 'disabled';
        }
    } else {
        $status = 'Tidak Diketahui';
    }

    $status = nama_status($array_monitoring[$key]);

    // jika ada keterangan korpum_realisasi_keterangan_pending maka status = Retur
    if(isset($posts[$key]['korpum_realisasi_keterangan_pending'])){
        if($posts[$key]['kode_status']==63){
            $status = 'Retur oleh Kor PUM ';
        } else if($posts[$key]['kode_status']==13) {
            $status = 'Membuat Procost';
        } else {
            $status = 'Retur - Pengisian SPJ';
        }
    }

    if(!isset($array_monitoring_keterangan[$key]) or empty($array_monitoring_keterangan[$key])) {
        
        $keterangan = '-';
    } else {
        $keterangan = $array_monitoring_keterangan[$key];
    }

    // masukkan array rincian ke dalam array daftar pengajuan berdasarkan id_pengajuan_pemohon
    foreach($array_rincian as $id_pengajuan_pemohon => $rincian) {

        // Tambahkan id unik untuk setiap detail toggle
        $toggleId = 'detail-' . $key;
        $toggleHeadId = 'head-' . $key;
        
            if($key == $id_pengajuan_pemohon) {

                // hitung nominal pengajuan, bruto, pajak, dan netto berdasarkan rincian
                $nominal_pengajuan = 0;
                $bruto = 0;
                $pajak = 0;
                $netto = 0;
                foreach($rincian as $row){
                    $nominal_pengajuan += $row['komitmen']; // jumlahkan komitmen
                    $bruto += $row['aktual'];
                    $pajak += $row['pph'];
                    $netto += $row['netto_d01_d02'];
                }

                if($row['form'] == 'D02') {
                    $bruto = $nominal_pengajuan;
                }
                
                // set tahun berdasdarkan nomor pengajuan
                if(isset($posts[$key]['nomor_pengajuan']) and !empty($posts[$key]['nomor_pengajuan'])) {
                    $bulan = substr($posts[$key]['nomor_pengajuan'], 8, 2);
                } else {
                    $bulan = date('m');
                }
                
                // baris untuk nomor pengajuan
                $html.= '
                <tr class="row-utama">
                    <td class="text-center"><input type="checkbox" name="pilih2[]" value="'.$key.'" class="pilih2"></td>
                    <td class="col-norut">'.$norut.'</td>
                    <td conclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.$posts[$key]['tanggal'].'</td>
                    <td conclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.$nomor_pengajuan.'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.$value['deskripsi_dpsj'].'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.$untuk.'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.$posts[$key]['form'].'</td>
                    <td class="text-right" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.number_format($nominal_pengajuan).'</td>
                    <td class="text-right" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.number_format($bruto).'</td>
                    <td class="text-right" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.number_format($pajak).'</td>
                    <td class="text-right" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'">'.number_format($netto).'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'"" id="'.$toggleHeadId.'" class="status_'.$key.'">'.$status.'</td>
                    <td>
                        <!--<button class="btn btn-info btn-xs view-catatan" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-catatan">View</button>-->
                        <button class="btn btn-info btn-xs fetch-logs" data-nomor_pengajuan="'.$nomor_pengajuan.'" data-toggle="modal" data-target="#modal-catatan">View</button>
                    </td>
                    <td style="display:none;">'.$bulan.'</td>
                </tr>'; // Separator row

                $html.= '
                <tr id="'.$toggleId.'" style="display:none;">
                    <td colspan="13" class="text-center" style="border:1px solid #f9f9f9">
                        <table id="tabel" class="table table-bordered table-striped" border="1">
                            <tr style="background-color:#f7f7f7;color:#777">
                                <th style="border-left:1px solid #ddd;">Kode Procost</th>
                                <th>Nama Procost</th>
                                <th>Kode Akun</th>
                                <th>Deskripsi Akun</th>
                                <th>Kode Dana</th>
                                <th>Keterangan</th>
                                <th style="border-right:1px solid #ddd;">Komitmen</th>
                                <th style="border-right:1px solid #ddd;">Bruto</th>
                                <th style="border-right:1px solid #ddd;">Pajak</th>
                                <th style="border-right:1px solid #ddd;">Netto</th>
                                <th style="border-right:1px solid #ddd;">Sisa</th>
                                <th style="border-right:1px solid #ddd;">Rincian Biaya</th>
                            </tr>';
            
                $n=1;  // $n untuk menunjukkan baris
                $nominal_pengajuan = 0; // inisialisasi nominal pengajuan
                $subtotal_realisasi = 0; // inisialisasi subtotal realisasi
                $subtotal_bruto = 0; // inisialisasi subtotal bruto
                $subtotal_pajak = 0; // inisialisasi subtotal pajak
                $subtotal_netto = 0; // inisialisasi subtotal netto
                $subtotal_sisa = 0; // inisialisasi subtotal sisa
                foreach($rincian as $row){

                    // hitung pph, jika form = D02 maka pph = pph_d02
                    $pph = $row['pph'];
                    $sisa_komitmen = $row['sisa_komitmen'];
                    if($row['form']=='D02'){
                        $pph = $row['pph_d02'];
                        $sisa_komitmen = 0; // untuk D02, sisa komitmen selalu 0 karena sudah dihitung di awal berdasarkan komitmen dikurangi pph_d02
                    }

                    // hitung sisa komitmen
                    
                    //echo '<pre>';
                    $html.= '<tr>';
                    $html.= '<td class="text-center" style="border-left:1px solid #ddd;">'.$row['kode_kegiatan'].'</td>';
                    $html.= '<td class="text-left">'.$row['nama_kegiatan'].'</td>';
                    $html.= '<td class="text-center">'.$row['kode_akun'].'</td>';
                    $html.= '<td class="text-left">'.$row['deskripsi_akun'].'</td>';
                    $html.= '<td class="text-center">'.$row['kode_dana'].'</td>';
                    $html.= '<td class="text-left">'.$row['keterangan'].'</td>';
                    $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';
                    $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($row['aktual_report']).'</td>';
                    $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($pph).'</td>';
                    $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($row['netto_d01_d02']).'</td>';
                    $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($sisa_komitmen).'</td>';

                    // jika status = 41 maka munculkan tombol view rincian biaya
                    /*if($posts[$key]['kode_status'] == 13 || $posts[$key]['kode_status'] == 41 || $posts[$key]['kode_status'] == 23 || $posts[$key]['kode_status'] == 51 || 
                        $posts[$key]['kode_status'] == 51 || $posts[$key]['kode_status'] == 61 || $posts[$key]['kode_status'] == 62 || $posts[$key]['kode_status'] == 71            
                    )*/
                    //if(in_array($posts[$key]['kode_status'], array(13, 23, 41, 51, 61, 62, 71))) {
                    if($row['aktual_report'] > 0 and $row['form'] == 'D01') {
                        $html.= '<td><button class="btn btn-default btn-xs view-realisasi" data-id="'.$row['id'].'" data-nomor_pengajuan="'.$row['nomor_pengajuan'].'" data-toggle="modal" data-target="#modal-realisasi">View</button></td>';
                    } else {
                        $html.= '<td></td>';
                    }

                        // Check if this is the first row to add action buttons
                        /*if($n > 1){
                            $html.='';
                        } else {
                            $html.= '';	
                        }*/

                    $html.= '</tr>';
                    //echo '</pre>';
                    $n++;
                    $nominal_pengajuan += $row['komitmen']; // jumlahkan komitmen
                    $subtotal_realisasi += $row['aktual_report']; // jumlahkan realisasi
                    $subtotal_bruto += $row['aktual']; // jumlahkan bruto
                    $subtotal_pajak += $pph; // jumlahkan pajak
                    $subtotal_netto += $row['netto_d01_d02']; // jumlahkan netto

                    $subtotal_sisa += $row['sisa_komitmen']; // jumlahkan sisa
                }

                // baris total
                $html.= '<tr>'; // Separator row
                $html.= '<td colspan="6" class="text-right" style="border-left:1px solid #ddd; color:#888"><b>Total: </b></td>'; // Empty row for spacing
                $html.= '<td class="text-right" style="border-left:1px solid #fff; border-bottom:1px solid #fff">'.number_format($nominal_pengajuan).'</td>'; // Empty row for spacing
                $html.= '<td class="text-right" style="border-right:1px solid #ddd; border-bottom:1px solid #fff">'.number_format($subtotal_bruto).'</td>'; // Empty row for spacing
                $html.= '<td class="text-right" style="border-right:1px solid #ddd; border-bottom:1px solid #fff">'.number_format($subtotal_pajak).'</td>'; // Empty row for spacing
                $html.= '<td class="text-right" style="border-right:1px solid #ddd; border-bottom:1px solid #fff">'.number_format($subtotal_netto).'</td>'; // Empty row for spacing
                $html.= '<td class="text-right" style="border-right:1px solid #ddd; border-bottom:1px solid #fff">'.number_format($subtotal_sisa).'</td>'; // Empty row for spacing
                //$html.= '<td style="border-right:1px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
                $html.= '</tr>';
                $html.= '<tr>'; // Separator row
                $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
                $html.= '</tr>';
                $html.= '</table></td></tr>'; // end Separator row
                break; // keluar dari loop setelah menemukan id_pengajuan_pemohon yang sesuai
            }

        
    }

    // jika tidak ada rincian untuk id_pengajuan_pemohon, tampilkan pesan
    if(!isset($array_rincian[$key]) or empty($array_rincian[$key])) {
        // cek dulu apakah pengajuan ini sudah pernah di ajukan sebelumnya
        
        if(isset($key) and !empty($key)){
            $disabled = '';
            $html.= '
                <tr>
                    <td>'.dbToTanggal($posts[$key]['tanggal']).'</td>
                    <td>'.$nomor_pengajuan.'</td>
                    <td>'.$value['deskripsi_dpsj'].'</td>
                    <td>'.$untuk.'</td>
                    <td>'.$posts[$key]['form'].'</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td>'.$status.'</td>
                    <td></td>
                    <td>
                        <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                        <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button>
                    </td>
                </tr>';
        }else{
            $disabled = 'disabled';
            $html.= '<tr>';
            $html.= '<td colspan="7" class="text-center" style="border-left:1px solid #ddd; border-right:1px solid #ddd; color:#888">'.$key.' - Tidak ada rincian untuk pengajuan ini</td>';
            $html.= '<td>
                    <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                    <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button></td>';
            $html.= '</tr>';
            $html.= '<tr>'; // Separator row
            $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
            $html.= '</tr>';
        }

        /*$html.= '<tr>';
        $html.= '<td colspan="7" class="text-center" style="border-left:1px solid #ddd; border-right:1px solid #ddd; color:#888">Tidak ada rincian untuk pengajuan ini</td>';
        $html.= '<td>
                <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button></td>';
        $html.= '</tr>';
        $html.= '</tr>';
        $html.= '<tr>'; // Separator row
        $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
        $html.= '</tr>';*/
    }

    $norut++;
}


$html.= '</tbody></table>';
echo $html;
        ?>
</div>

<?php include(APPPATH.'views/style_table.php') ?>

<script>
searchTable2();

$(document).ready(function()
{
    
    // fungsi konfirmasi lanjut proses invoice
    $('#tambah-procost').click(function() {
        var id_pengajuan_pemohon = $('.pilih2:checked').map(function(_, el) {
            return $(el).val();
        }).get(); 
        
        var no_invoice_pp = $('#no_invoice_pp_select').val();
        var uraian = $('#uraian_select').val();
        var tahun = $('#tahun_select').val();
        var bulan = $('#bulan_select').val();
        var tgl = $('#tgl_select').val();
        
        console.log(id_pengajuan_pemohon); 
        
        // ambil data pengajuan menggunakan AJAX
		$.ajax({
			url: "<?=base_url()?>index.php/invoice_update/konfirmasi",
			type: "POST",
			data: {id_pengajuan_pemohon:id_pengajuan_pemohon, 
        			no_invoice_pp:no_invoice_pp, 
        			uraian:uraian, 
        			tahun:tahun, 
        			bulan:bulan, 
        			tgl:tgl, 
        			no_tiket:$("#no_tiket_select").val()
				},
			success: function(response) {
				// Tampilkan pesan sukses atau lakukan tindakan lain
				//alert("Data berhasil disimpan!");
				///location.href = "../invoice";
				$("#data-custom-modal").html(response);
				//simpanKendaliDokumenProcost();
				console.log(response);
			},
			error: function(xhr, status, error) {
				// Tampilkan pesan kesalahan
				alert("Terjadi kesalahan saat ...");
				//console.log(error);
			}
		});
    });
	/*$(document).on("change", "#tahun2, #bulan2, #tgl2", function(){
		fetch_data2();
	});*/
});

function searchTable2() {
  //alert('test'); return false;
    document.querySelectorAll('[id^="detail-"]').forEach(el => el.style.display = 'none');
    var input = document.getElementById("searchInput2");
    var filter = input.value.toLowerCase();
    var inputBulan = document.getElementById("bulan2");
    var filterBulan = inputBulan.value.toLowerCase();
    var table = document.getElementById("examplex2");
    var tr = table.getElementsByClassName("row-utama");
    
    var counter = 1; // Mulai hitungan dari 1

    for (var i = 0; i < tr.length; i++) {
        var tdNomor = tr[i].getElementsByTagName("td")[3]; // Kolom Nomor Pengajuan
        var tdNorut = tr[i].getElementsByClassName("col-norut")[1]; // Kolom Norut        
        var tdBulan = tr[i].getElementsByTagName("td")[13]; // Kolom bulan (misal di kolom ke-4, sesuaikan dengan posisi sebenarnya)

        
        if (tdNomor && tdBulan) {
            var txtValue = tdNomor.textContent || tdNomor.innerText;
            var isMatch = txtValue.toLowerCase().indexOf(filter) > -1;
            var isMatchBulan = tdBulan.textContent.toLowerCase().indexOf(filterBulan) > -1;
            
            // 1. Sembunyikan/Tampilkan baris utama
            tr[i].style.display = isMatch && isMatchBulan ? "" : "none";
            
            /*// 2. Sembunyikan baris detail terkait jika baris utama disembunyikan
            var idKey = tr[i].querySelector('button').getAttribute('data-id_pengajuan_pemohon');
            var detailRow = document.getElementById('detail-' + idKey);
            if (detailRow) {
                detailRow.style.display = isMatch ? (detailRow.style.display === 'table-row' ? 'table-row' : 'none') : 'none';
            }
            
            // 3. Update Nomor Urut jika baris sedang tampil
            if (isMatch) {
                tdNorut.textContent = counter;
                counter++;
            }*/
        }
    }
}
      
// Fungsi untuk memilih semua checkbox yang terlihat
function toggleAll(source) {
    var checkboxes = document.querySelectorAll('input[name="pilih[]"]');
    for (var i = 0; i < checkboxes.length; i++) {
        // Hanya centang yang barisnya terlihat (display bukan none)
        if (checkboxes[i].closest('tr').style.display !== 'none') {
            checkboxes[i].checked = source.checked;
        }
    }
}
</script>
    
<style>
/* Mengatur kontainer agar memiliki tinggi maksimal dan scroll */
    .table-container {
        max-height: 500px; /* Atur tinggi sesuai kebutuhan */
        overflow-y: auto;
        border: 1px solid #ddd;
    }

    .styled-table {
        border-collapse: collapse;
        width: 100%;
    }

    /* Membuat header tetap di atas */
    .styled-table thead th {
        position: sticky;
        top: 0;
        background-color: #43A5BE; /* Warna background agar tidak transparan */
        z-index: 10; /* Agar header di atas isi tabel */
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4); /* Opsional: memberi garis bawah halus */
    }

    /* Khusus untuk rincian biaya yang Anda buat (sub-tabel) */
    /* Jika ingin header sub-tabel juga sticky (opsional) */
    #tabel tr:first-child th {
        position: sticky;
        top: 40px; /* Sesuaikan dengan tinggi header utama */
        background: #4bc2cb;
        z-index: 9;
    }
</style>

<?php //include(APPPATH.'views/modal.php') ?>