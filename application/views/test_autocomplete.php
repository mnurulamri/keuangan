<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="<?=base_url();?>assets/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?=base_url();?>assets/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url();?>assets/AdminLTE/dist/js/app.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $title ?>
            <small>Tahun Anggaran 2025</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>

                                    
                                <div class="box">
                                    <?=$this->session->userdata('kode_bidang');?>
                                    <div class="form-group">
                                        <label for="nomor_pengajuan">Nomor</label>
                                        <input type="text" class="form-control" id="nomor_pengajuan" name="nomor_pengajuan" value="<?= $preview_nomor ?>" readonly style="font-weight: bold; font-size: 16px;">
                                        <small class="text-muted">Format: 000/ANG.BB/YYYY-KODEUNIT (Otomatis tergenerate)</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="untuk_nama">Deskripsi DPSJ</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control deskripsi_dpsj" id="deskripsi_dpsj" name="deskripsi_dpsj" placeholder="cari deskripsi DPSJ..." autocomplete="off" required>
                                            <span class="input-group-addon" id="kode_dpsj" style="width:145px"></span>
                                            <span class="input-group-addon btn btn-danger" id="clear_dpsj">clear</span>
                                        </div>
                                        <div id="kotaksugest_deskripsi_dpsj" ></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="untuk_nama">Untuk dan Atas Nama</label>
                                        <input type="text" class="form-control" id="untuk_nama" name="untuk_nama" required>
                                    </div>

                                    <table class="tablex table-borderedx" border="1" id="tabel-rincian">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="25%" colspan="2">Nomor dan Nama Project Costing</th>
                                                <th width="25%" colspan="2">Nomor dan Nama Akun</th>
                                                <th width="15%">Jumlah (Rp)</th>
                                                <th width="20%">Keterangan</th>
                                                <th width="5%">Anggaran</th>
                                                <th width="5%">Sisa Anggaran</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--
                                            <tr>
                                                <td>1</td>
                                                <td class="kode-kegiatan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td class="nama-kegiatan">                                                        
                                                    <input type="text" name="nama_kegiatan[]" class="form-control project-costing hovertext" title="" placeholder="Cari project costing...">
                                                </td>                                                
                                                <td class="kode-akun">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td class="deskripsi-akun">
                                                    <input type="text" name="deskripsi_akun[]" class="form-control deskripsi-akun akun" placeholder="Cari akun..." >
                                                </td>
                                                <td>
                                                    <input type="text" name="jumlah[]" class="form-control money jumlah" >
                                                    <div class="help-block text-danger"></div>
                                                </td>
                                                <td><input type="text" name="keterangan[]" class="form-control" ></td>
                                                <td class="sisa-anggaran text-right">-</td>
                                                <td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>
                                            </tr>
                                            -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td class="total"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="9">
                                                    <button type="button" class="btn btn-primary btn-sm" id="btn-add-row" disabled >
                                                        <i class="fa fa-plus"></i> Tambah Rincian
                                                    </button>
                                                    <button class="btn btn-success btn-sm" id="simpan" disabled><i class="fa fa-floppy-o"></i> Simpan</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>                                    
                                </div>
                                    <!-- Di bagian Rincian Pembayaran -->

</div>

<script>
$(document).ready(function(){
    
    // autocomplete deskripsi dpjs    
    $("#deskripsi_dpsj").click(function()
    {
        $("#kotaksugest_deskripsi_dpsj").show();
    });

    $(document).on("keyup", "#deskripsi_dpsj", function(){
    //$("#kotaksugest_lokasi").hide();

        var kata = $(this).val();
        //alert(kata)
        if(kata.length==0)
        {
            $("#kotaksugest_deskripsi_dpsj").css("visibility", "hidden");
            $("#kode_dpsj").text("");
        } else {
            $.ajax({
                url: "<?=base_url()?>index.php/anggaran/search_dpsj",
                type: "POST",
                data: {kata:kata},
                success: function(data)
                {
                $("#kotaksugest_deskripsi_dpsj").css("visibility", "");
                $("#kotaksugest_deskripsi_dpsj").show();
                $("#kotaksugest_deskripsi_dpsj").html(data)
                    //alert(data)
                }
            });
            //clock.start();
        }
    });

    $(document).on("click", ".isi_dpsj", function()
	{    
		var deskripsi_dpsj = $(this).text();
        var kode_dpsj = $(this).parent().find("td").eq(1).text();
	    $("#deskripsi_dpsj").val(deskripsi_dpsj);
	    $("#kode_dpsj").text(kode_dpsj);
	    $("#kotaksugest_deskripsi_dpsj").hide();
        $("#btn-add-row").prop("disabled", false);
	});

    $("#clear_dpsj").click(function(){
	    $("#deskripsi_dpsj").val("");
	    $("#kode_dpsj").text("");
        $("#btn-add-row").prop("disabled", true);
    });

    // --------------------- autocomplete project-costing ---------------------
    $(document).on("click", ".project-costing", function()
    {
        $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();
        
        $(this).parent().append('<div class="kotaksugest_pc"></div>');
        
        var kata = '';
        var dpsj = $("#kode_dpsj").text();

        $.ajax({
            url: "<?=base_url()?>index.php/anggaran/search_project",
            type: "POST",
            data: {kata:kata, dpsj:dpsj},
            success: function(data)
            {
                $(".kotaksugest_pc").css("visibility", "");
                $(".kotaksugest_pc").show();
                $(".kotaksugest_pc").html(data);
            }
        });

        // nonaktifkan tag input akun -> masih salah krn tampilan input malah ilang
        var deskripsi_akun = $(this).parent().next().next().children(".akun").val();
        //$(this).parent().next().next().text(deskripsi_akun);
        if ($(this).parent().next().next().children(".akun").is(':disabled')) {
            // Element is disabled
            console.log('Element is disabled');
        } else {
            $(this).parent().next().next().text(deskripsi_akun);
        }
    });

    $(document).on("dblclick", ".nama-kegiatan", function()
    {
        $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();

		var nama_kegiatan = $(this).text();
		$(this).html('<input type="text" name="nama_kegiatan[]" class="form-control project-costing hovertext" title="" placeholder="Cari project costing..." value="'+nama_kegiatan+'">');
		$(".project-costing").select();
		$(this).append('<div class="kotaksugest_pc"></div>');

        

        // supaya di dblclick berkali-kali cuma muncul satu tag input aja
        /*var jumlah_element = $(this).find("*").length;
        if (jumlah_element == 0) {
            $(this).append('<input type="text" name="nama_kegiatan[]" class="form-control project-costing hovertext" title="" placeholder="Cari project costing..." value="'+nama_kegiatan+'">');
        } else {
            $(".project-costing").remove();
            $(this).append('<input type="text" name="nama_kegiatan[]" class="form-control project-costing hovertext" title="" placeholder="Cari project costing..." value="'+nama_kegiatan+'">');
        }*/
    });

    $(document).on("select", ".project-costing", function()
    {
        var kata = '';
        var dpsj = $("#kode_dpsj").text();

        $.ajax({
            url: "<?=base_url()?>index.php/anggaran/search_project",
            type: "POST",
            data: {kata:kata, dpsj:dpsj},
            success: function(data)
            {
                $(".kotaksugest_pc").css("visibility", "");
                $(".kotaksugest_pc").show();
                $(".kotaksugest_pc").html(data);
            }
        });
    });

	$(document).on("blur", ".project-costing", function()
    {
		var jumlah_element = $(this).parent().find("*").length;
		if(jumlah_element == 2)
		{
			var nama_kegiatan = $(this).val();
			$(this).parent().text(nama_kegiatan);
	        $(".project-costing").css("visibility","");
		} 	
        $(".project-costing").css("visibility","");	
    });

    $(document).on("keyup", ".project-costing", function(){
    //$("#kotaksugest_lokasi").hide();

        var kata = $(this).val();
        var dpsj = $("#kode_dpsj").text();
        console.log(dpsj)
        if(kata.length==0)
        {
            //$("#kode_akun").text("");            
            $(".kotaksugest_pc").text("");
        } else {
            $.ajax({
                url: "<?=base_url()?>index.php/anggaran/search_project",
                type: "POST",
                data: {kata:kata, dpsj:dpsj},
                success: function(data)
                {
                    $(".kotaksugest_pc").css("visibility", "");
                    $(".kotaksugest_pc").show();
                    $(".kotaksugest_pc").html(data);
                }
            });
            //clock.start();
        }
    });
    
    $(document).on("click", ".isi_pc", function()
	{    
		var txt = $(this).text();
        var array_nama_kegiatan = txt.split(":");
        var nama_kegiatan = array_nama_kegiatan[1];
        var arrays_kode_kegiatan = array_nama_kegiatan[0];//$(this).parent().find("td").eq(1).text();
        var array_kode_kegiatan = arrays_kode_kegiatan.split("-");
        var kode_kegiatan = array_kode_kegiatan[1];

        $(this).parent().parent().parent().parent().closest("td").prev().text(kode_kegiatan); 
        $(this).parent().parent().parent().parent().closest("td").next().text("test"); 
        $(this).parent().parent().parent().parent().closest("td").next().next().find(".akun").prop("disabled", false);      
        $(this).parent().parent().parent().parent().closest("td").text(nama_kegiatan); 
        $(this).parent().parent().parent().parent().closest("td").find(".nama-kegiatan").val(nama_kegiatan);        
        $(this).parent().parent().parent().parent().closest("td").find(".project-costing").attr("title", nama_kegiatan);
	    $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();
	});

    // ------------------ end of autocomplete project-costing -----------------------------

    // ------------------ autocomplete akun ---------------------------
    
    $(document).on("click", ".akun", function()
    {
        $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();

        $(this).parent().append('<div class="kotaksugest_akun"></div>');

        var kata = '';
        var kode_kegiatan = $(this).parent().siblings(".kode-kegiatan").text();

        $.ajax({
            url: "<?=base_url()?>index.php/anggaran/search_akun",
            type: "POST",
            data: {kata:kata, kode_kegiatan:kode_kegiatan},
            success: function(data)
            {
                $(".kotaksugest_akun").css("visibility", "");
                $(".kotaksugest_akun").show();
                $(".kotaksugest_akun").html(data);
            }
        });

        // nonaktifkan tag input project-costing
        var nama_kegiatan = $(this).parent().prev().prev().children(".project-costing").val();
        $(this).parent().prev().prev().text(nama_kegiatan);
    });

    $(document).on("dblclick", ".deskripsi-akun", function()
    {
        $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();

		var deskripsi_akun = $(this).text();
		$(this).html('<input type="text" name="deskripsi_akun[]" class="form-control akun hovertext" title="" placeholder="Cari project costing..." value="'+deskripsi_akun+'">');
		$(".akun").select();
		$(this).append('<div class="kotaksugest_akun"></div>');


    });

    $(document).on("select", ".akun", function()
    {
        var kata = '';
        var kode_kegiatan = $(this).parent().siblings(".kode-kegiatan").text();

        $.ajax({
            url: "<?=base_url()?>index.php/anggaran/search_akun",
            type: "POST",
            data: {kata:kata, kode_kegiatan:kode_kegiatan},
            success: function(data)
            {
                $(".kotaksugest_akun").css("visibility", "");
                $(".kotaksugest_akun").show();
                $(".kotaksugest_akun").html(data);
            }
        });        
    });

	$(document).on("blur", ".akun", function()
    {
		var jumlah_element = $(this).parent().find("*").length;
		if(jumlah_element == 2)
		{
			var deskripsi_akun = $(this).val();
			$(this).parent().text(deskripsi_akun);
	        $(".akun").css("visibility","");
		} 
        console.log(jumlah_element)
    });

    $(document).on("keyup", ".akun", function(){
    //$("#kotaksugest_lokasi").hide();

        var kata = $(this).val();
        var kode_kegiatan = $(this).parent().siblings(".kode-kegiatan").text();
        console.log(kode_kegiatan)
        if(kata.length==0)
        {
            //$("#kode_akun").text("");            
            $(".kotaksugest_akun").text("");
        } else {
            $.ajax({
                url: "<?=base_url()?>index.php/anggaran/search_akun",
                type: "POST",
                data: {kata:kata, kode_kegiatan:kode_kegiatan},
                success: function(data)
                {
                    $(".kotaksugest_akun").css("visibility", "");
                    $(".kotaksugest_akun").show();
                    $(".kotaksugest_akun").html(data);
                }
            });
            //clock.start();
        }
    });
    
    $(document).on("click", ".isi_akun", function()
	{    
		var txt = $(this).text();
        var kode_akun = $(this).parent().find("td").eq(0).text();
        var deskripsi_akun = $(this).parent().find("td").eq(1).text();
        var anggaran = $(this).parent().find("td").eq(2).text();
        $(this).parent().parent().parent().parent().closest("td").siblings(".anggaran").text(anggaran)
        $(this).parent().parent().parent().parent().closest("td").prev().text(kode_akun);  
        $(this).parent().parent().parent().parent().closest("td").text(deskripsi_akun);

        //$(this).parent().parent().parent().parent().closest("td").find(".nama-kegiatan").val(nama_kegiatan);        
        //$(this).parent().parent().parent().parent().closest("td").find(".project-costing").attr("title", nama_kegiatan);
	    $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();
	});

    // ------------------ end of autocomplete akun -----------------------------

    // Tambah baris rincian
    $('#btn-add-row').click(function() {
        var rowCount = $('#tabel-rincian tbody tr').length + 1;
        var newRow = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td class="kode-kegiatan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="nama-kegiatan"><input type="text" name="nama_kegiatan[]" class="form-control nama-kegiatan project-costing hovertext" title="" placeholder="Cari project costing..." autocomplete="off"></td>' +
            '<td class="kode-akun">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="deskripsi-akun"><input type="text" name="deskripsi_akun[]" class="form-control akun" placeholder="Cari akun..." disabled  autocomplete="off"></td>' +
            '<td class="jumlah"><input type="text" name="jumlah[]" class="form-control money input-jumlah"></td>' +
            '<td class="keterangan" contenteditable="true"></td>' +
            '<td class="anggaran">anggaran</td>' +
            '<td class="sisa_anggaran"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>' +
            '</tr>';
        $('#tabel-rincian tbody').append(newRow);
        /*$('.money').inputmask('numeric', {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            prefix: 'Rp ',
            rightAlign: false
        });*/
    });
    
    // Hapus baris rincian
    $(document).on('click', '.btn-remove-row', function() {
        // Hapus baris yang sesuai
        $(this).closest('tr').remove();
        
        // Update nomor urut
        updateRowNumbers();

        // hitung total
        var total = 0;
        $(".jumlah").each(function(){
            var val = $(this).text().replace(/,/g, '');
            if(val != "")
            {
                total += parseFloat(val);
            }
        });
        
        // Format the total as currency
        let jumlah_total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $(".total").text(jumlah_total);
    });
    
    // Update nomor urut
    function updateRowNumbers() {
        $('#tabel-rincian tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Format the value as currency
    $(document).on("blur", ".input-jumlah", function(){   

        // hitung sisa anggaran
        var jumlah = $(this).val();
        var anggaran = $(this).parent().siblings(".anggaran").text();
        
        var jumlah = jumlah.replace(/,/g, '');
        var anggaran = anggaran.replace(/,/g, '');
        
        var jumlah = parseFloat(jumlah);
        var anggaran = parseFloat(anggaran);
        
        var sisa = anggaran - jumlah;

        if (sisa < 0) {
            alert("Jumlah melebihi anggaran!");
            $(this).val("");
            $(this).parent().siblings(".sisa_anggaran").text("");
            return;
        }

        $(this).parent().siblings(".sisa_anggaran").text(sisa.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        
        $(this).parent().text(jumlah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        // hitung total
        var total = 0;
        $(".jumlah").each(function(){
            var val = $(this).text().replace(/,/g, '');
            if(val != "")
            {
                total += parseFloat(val);
            }
        });
        
        // Format the total as currency
        let jumlah_total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $(".total").text(jumlah_total);
        
        $("#simpan").prop("disabled", false);
    });

    // Allow only numbers and decimal point
    $(document).on("keypress keyup", ".input-jumlah", function(evt){        
        let keyCode = $(this).val()
        var charCode = (evt.which) ? evt.which : keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        } else {
            //var jumlah = $(this).val();
            // Remove all non-digit characters except decimal point
            let value = keyCode.replace(/[^\d.]/g, '');
            let jumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            $(this).val(jumlah);

            // Format the value as currency -> cara 2
            /*value = parseFloat(value).toLocaleString('id-ID', {
                //style: 'currency',
                //currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });*/
        }
    });

    $(document).on("dblclick", ".jumlah", function(){
        var jumlah = $(this).text();
        $(this).html('<input type="text" name="jumlah[]" class="form-control money input-jumlah" value="'+jumlah+'">');
        $(".input-jumlah").select();
    });

    $(document).on("click", "#simpan", function(){

        // set tahun anggaran
        var tahun_anggaran = $("#nomor_pengajuan").val().split("/")[2];
        var tahun_anggaran = tahun_anggaran.split("-")[0];

        // set tanggal sistem
        var now = new Date();
        var pad = n => n.toString().padStart(2, '0');
        var tgl_sistem = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}`;

        // Ambil data dari tabel
        var array_data = [];        
        $('#tabel-rincian tbody tr').each(function(row, tr) {
            var kode_kegiatan = $(this).find('td').eq(1).text();
            var nama_kegiatan = $(this).find('td').eq(2).text();
            var kode_akun = $(this).find('td').eq(3).text();
            var deskripsi_akun = $(this).find('td').eq(4).text();
            var jumlah = $(this).find('td').eq(5).text();
            var jumlah = jumlah.replace(/,/g, '');
            var jumlah = parseFloat(jumlah);
            var keterangan = $(this).find('td').eq(6).text();
            //var sisa_anggaran = $(this).find('td').eq(7).text();

            array_data.push({
                tahun_anggaran: tahun_anggaran,                
                kode_dpsj: $("#kode_dpsj").text(),
                deskripsi_dpsj: $("#deskripsi_dpsj").val(),
                kode_kegiatan: kode_kegiatan,
                nama_kegiatan: nama_kegiatan,
                kode_akun: kode_akun,
                deskripsi_akun: deskripsi_akun,
                nomor_pengajuan: $("#nomor_pengajuan").val(),
                komitmen: jumlah,
                keterangan: keterangan,
                tgl_sistem: tgl_sistem,
                username: "xxx"
            }); 
        });

        // Kirim data ke controller menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/Test_autocomplete/simpan_rincian",
            type: "POST",
            data: {data: array_data, kode_dpsj: $("#kode_dpsj").text(), nomor_pengajuan: $("#nomor_pengajuan").val()},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                alert("Terjadi kesalahan saat menyimpan data.");
                console.log(error);
            }
        });
    });
});
</script>

    <style>
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
            top: 100%;
            left: 0;
            right: 0;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff; 
            border-bottom: 1px solid #d4d4d4; 
        }
        .autocomplete-items div:hover {
            background-color: #e9e9e9; 
        }
    </style>


