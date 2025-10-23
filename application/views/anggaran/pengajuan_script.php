<script>
    var id = 0;
$(document).ready(function() {

    // set variabel global
    var id_dana = "";
    var id_akun = 1; 
    

    $("#test").click(function() {
        location.href = "../daftar_pengajuan";
    });
    // autocomplete deskripsi dpjs    
    $("#deskripsi_dpsj").click(function()
    {
        $("#kotaksugest_deskripsi_dpsj").show();
        
        var kata = $(this).val();
        var kode_bidang = $("#kode_bidang").val();
        
            $.ajax({
                url: "<?=base_url()?>index.php/anggaran/search_dpsj",
                type: "POST",
                data: {kata:kata, kode_bidang:kode_bidang},
                success: function(data)
                {
                $("#kotaksugest_deskripsi_dpsj").css("visibility", "");
                $("#kotaksugest_deskripsi_dpsj").show();
                $("#kotaksugest_deskripsi_dpsj").html(data)
                    //alert(data)
                }
            });
    });

    /*$(document).on("keyup", "#deskripsi_dpsj", function(){
    //$("#kotaksugest_lokasi").hide();

        var kata = $(this).val();
        var kode_bidang = $("#kode_bidang").val();
        
        if(kata.length==0)
        {
            $("#kotaksugest_deskripsi_dpsj").css("visibility", "hidden");
            $("#kode_dpsj").text("");
        } else {
            $.ajax({
                url: "<?=base_url()?>index.php/anggaran/search_dpsj",
                type: "POST",
                data: {kata:kata, kode_bidang:kode_bidang},
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
    });*/

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

    $(document).on("click", ".nama-kegiatan", function()
    {
        $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();

        id = $(this).data("id");
        $("#id").val(id); // set id untuk input nama kegiatan
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
		var txt = $(this).data("value");
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
        $(".kotaksugest_akun").remove();var id = $("#id").val();

        console.log('klik isi pc id:'+id);
        console.log(nama_kegiatan);

        $("#kode_akun_"+id).text('00000');        
        $("#akun_"+id).text('Pilih Akun');
        $("#sisa_anggaran_"+id).text('0');
        $("#btn-add-row").prop("disabled", true);
		
        return false;
	});

    // ------------------ end of autocomplete project-costing -----------------------------

    // ------------------ autocomplete akun ---------------------------
    
    $(document).on("click", ".akun", function()
    {
        $("#id").val(id); //var id = $(this).data("id");
        
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

    $(document).on("click", ".deskripsi-akun", function()
    {
        var id = $(this).data("id");
        $("#id").val(id); 
        console.log('id on deskripsi-akun:'+id);
        $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();

		var deskripsi_akun = $(this).text();
		$(this).html('<input type="text" name="deskripsi_akun[]" class="form-control akun hovertext" title="" placeholder="Cari project costing..." value="'+deskripsi_akun+'" data-id="'+id+'">');
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
        var id = $("#id").val();
        var kode_akun = $(this).parent().parent().find("td").eq(0).text();
        var deskripsi_akun = $(this).parent().parent().find("td").eq(1).text();
        var kode_dana = $(this).parent().parent().find("td").eq(2).text();
        console.log('id on isi_akun:'+id+' kode_dana:'+kode_dana);
        // set sisa anggaran
        /*var array_sisa_anggaran = <?php //echo $array_sisa_anggaran; ?>;
        var array = [];
        array = array_sisa_anggaran[kode_akun];
        let sisa_anggaran = array['sisa_anggaran'];*/
        let sisa_anggaran = 0;

        $(this).parent().parent().parent().parent().closest("td").siblings(".anggaran").text(sisa_anggaran);
        $(this).parent().parent().parent().parent().closest("td").prev().text(kode_akun);  
        $(this).parent().parent().parent().parent().closest("td").text(deskripsi_akun);
        $("#dana_"+id).text(kode_dana);
        
        //$(this).parent().parent().parent().parent().closest("td").find(".nama-kegiatan").val(nama_kegiatan);        
        //$(this).parent().parent().parent().parent().closest("td").find(".project-costing").attr("title", nama_kegiatan);
	    $(".kotaksugest_pc").remove();
        $(".kotaksugest_akun").remove();

        // set anggaran
        var kode_dpsj = $("#kode_dpsj").text();
        var kode_kegiatan = $("#kode_kegiatan_"+id).text();
        var kode_akun = $("#kode_akun_"+id).text();
        var kode_dana = $("#dana_"+id).text();
        var jumlah = $("#jumlah_"+id).text();
        var jumlah = jumlah.replace(/,/g, ''); // Hapus tanda koma
        var jumlah = parseFloat(jumlah);

        console.log('kode_dpsj:'+kode_dpsj+' kode_kegiatan:'+kode_kegiatan+' kode_akun:'+kode_akun+' kode_dana:'+kode_dana+'jumlah:'+jumlah);
        $.ajax({
            url: "<?=base_url()?>Sisa_anggaran",
            type: "POST",
            data: {kode_dpsj: kode_dpsj, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana, jumlah:jumlah},
            //dataType: 'json',
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                $("#sisa_anggaran_"+id).text(response);
                $("#test-script").text(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                console.log(error);
            }
        });


        $("#btn-add-row").prop("disabled", false);
		return false;
	});

    // ------------------ end of autocomplete akun -----------------------------

    // Tambah baris rincian
    var newId = 1;
    $('#btn-add-row').click(function() {        
        var rowCount = $('#tabel-rincian tbody tr').length + 1;
        /*var newRow = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td class="kode-kegiatan">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="nama-kegiatan"><input type="text" name="nama_kegiatan[]" class="form-control nama-kegiatan project-costing hovertext" title="" placeholder="Cari project costing..." autocomplete="off"></td>' +
            '<td class="kode-akun">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="deskripsi-akun" id="akun_'+newId+'" data-id="'+newId+'"><input type="text" name="deskripsi_akun[]" class="form-control akun" placeholder="Cari akun..." disabled autocomplete="off" data-id="'+newId+'"></td>' +
            '<td class="kode-dana" id="dana_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="jumlah" id="jumlah_'+newId+'"><input type="text" name="jumlah[]" class="form-control money input-jumlah" data-id="jumlah_'+newId+'"></td>' +
            '<td class="keterangan" contenteditable="true"></td>' +
            '<td class="anggaran">anggaran</td>' +
            '<td class="sisa_anggaran"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>' +
            '</tr>';
        */
       var newRow = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td class="kode-kegiatan" id="kode_kegiatan_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="nama-kegiatan" data-id="'+newId+'"><input type="text" name="nama_kegiatan[]" class="form-control nama-kegiatan project-costing hovertext" title="" placeholder="Cari project costing..." autocomplete="off"></td>' +
            '<td class="kode-akun" id="kode_akun_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="deskripsi-akun" id="akun_'+newId+'" data-id="'+newId+'"><input type="text" name="deskripsi_akun[]" class="form-control akun" placeholder="Cari akun..." disabled autocomplete="off" data-id="'+newId+'"></td>' +
            '<td class="kode-dana" id="dana_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="jumlah" id="jumlah_'+newId+'"><input type="text" name="jumlah[]" class="form-control money input-jumlah" id="input_jumlah_'+newId+'" data-id="jumlah_'+newId+'" value="0"></td>' +
            '<td class="keterangan" contenteditable="true"></td>' +
            '<td class="sisa_anggaran" id="sisa_anggaran_'+newId+'"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>' +
            '</tr>';
            
        // Tambahkan baris baru ke tabel
        $('#tabel-rincian tbody').append(newRow);

        newId++;

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
        $(this).closest('tr').remove();
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

    // Format input jumlah
    var id_jumlah = 1;
    $(document).on("blur", ".input-jumlah", function(){

        // manipulasi karakter input jumlah
        var id_jumlah = $(this).data("id");
        var id = $("#id").val();
        var jumlah = $(this).val();
        var jumlah = jumlah.replace(/,/g, ''); // Hapus tanda koma
        var jumlah = parseFloat(jumlah);
        $(this).parent().text(jumlah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $(this).parent().removeClass("input-jumlah");


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
            
        // set sisa anggaran
        var id = $("#id").val();
        var kode_dpsj = $("#kode_dpsj").text();
        var kode_kegiatan = $("#kode_kegiatan_"+id).text();
        var kode_akun = $("#kode_akun_"+id).text();
        var kode_dana = $("#dana_"+id).text();
        console.log('kode_dpsj:'+kode_dpsj+' kode_kegiatan:'+kode_kegiatan+' kode_akun:'+kode_akun+' kode_dana:'+kode_dana);
        //var sisa_anggaran = "<?php //echo sisaAnggaran($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana); ?>";
        //console.log('sisa anggaran:'+sisa_anggaran);
        $.ajax({
            url: "<?=base_url()?>Sisa_anggaran",
            type: "POST",
            data: {kode_dpsj: kode_dpsj, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana, jumlah:jumlah},
            //dataType: 'json',
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                if (response === "0") {
                    alert("Anngaran tidak mencukupi!");
                    $("#simpan").prop("disabled", true);
                    $("#jumlah_"+id).text("0");
                    return;
                } else {
                    $("#simpan").prop("disabled", false);
                    $("#sisa_anggaran_"+id).text(response);
                }
                
                $("#test-script").text(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                console.log(error);
            }
        });
        
        $("#simpan").prop("disabled", false);
    });
    
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
        }
    });

    $(document).on("click", ".jumlah", function(){
        var jumlah_element = $(this).find("*").length;
        if (jumlah_element == 0) {
            var jumlah = $(this).text();        
            var id_jumlah = $(this).attr("id");
            var id = id_jumlah.split("_")[1]; // ambil id dari id_jumlah
            $("#id").val(id); // set id untuk input jumlah
            $(this).html('<input type="text" name="jumlah[]" class="form-control money input-jumlah" value="'+jumlah+'" data-id="'+id_jumlah+'">');
            $(".input-jumlah").select();
            console.log('id_jumlah:'+id_jumlah);
        } else {
            return
        }        
    });

    $(document).on("click", "#simpan", function(e){
        e.preventDefault();
        // set tahun anggaran sementera di set tahun 2025
        var tahun_anggaran = '2025';
        //var tahun_anggaran = $("#nomor_pengajuan").val().split("/")[2];
        //var tahun_anggaran = tahun_anggaran.split("-")[0];

        // set tanggal sistem
        var now = new Date();
        var pad = n => n.toString().padStart(2, '0');
        var tgl_sistem = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}`;

        // Ambil data dari tabel untuk pengajuan rincian
        if ($("#kode_dpsj").text() == "") {
            alert("Kode DPSJ tidak boleh kosong!");
            return false;
        }

        if ($("#deskripsi_dpsj").val() == "") {
            alert("Deskripsi DPSJ tidak boleh kosong!");
            return false;
        }

        /*if ($("#nomor_pengajuan").val() == "") {
            alert("Nomor pengajuan tidak boleh kosong!");
            return false;
        }*/
        if ($("#penanggung_jawab").val() == "") {
            alert("Penanggung jawab tidak boleh kosong!");
            return false;
        }
        if ($("#nip").val() == "") {
            alert("NIP tidak boleh kosong!");
            return false;
        }


        var array_data = [];
        var nominal_pengajuan = 0;
            
        $('#tabel-rincian tbody tr').each(function(row, tr) 
        {
            var kode_kegiatan = $(this).find('td').eq(1).text();
            var nama_kegiatan = $(this).find('td').eq(2).text();
            var kode_akun = $(this).find('td').eq(3).text();
            var deskripsi_akun = $(this).find('td').eq(4).text();
            var kode_dana = $(this).find('td').eq(5).text();
            var jumlah = $(this).find('td').eq(6).text();
            var jumlah = jumlah.replace(/,/g, '');
            var jumlah = parseFloat(jumlah);
            var keterangan = $(this).find('td').eq(7).text();
            //var sisa_anggaran = $(this).find('td').eq(7).text();
            nominal_pengajuan = parseFloat(nominal_pengajuan) + jumlah;
            console.log(jumlah);
            array_data.push({
                tahun_anggaran: tahun_anggaran,                
                kode_dpsj: $("#kode_dpsj").text(),
                deskripsi_dpsj: $("#deskripsi_dpsj").val(),
                kode_kegiatan: kode_kegiatan,
                nama_kegiatan: nama_kegiatan,
                kode_akun: kode_akun,
                deskripsi_akun: deskripsi_akun,
                kode_dana: kode_dana,
                nomor_pengajuan: $("#nomor_pengajuan").val(),
                komitmen: jumlah,
                keterangan: keterangan,
                tgl_sistem: tgl_sistem,
                username: "xxx"
            }); 
        });
        
        // ambil data identitas pejabat
        var array_pejabat = [];
        array_pejabat.push({
            tanggal: $("#tanggal").val(),              
            kode_dpsj: $("#kode_dpsj").text(),
            deskripsi_dpsj: $("#deskripsi_dpsj").val(),
            nomor_pengajuan: $("#nomor_pengajuan").val(),
            penanggung_jawab: $('#penanggung_jawab').val(),
            nip: $('input#nip').val(),
            telp: $('input#telp').val(),
            untuk: $('input#untuk').val()
        });
        
        // array data untuk tabel monitoring -> dieksekusi saat pengajuan diajukan
        var array_monitoring = [];
        array_monitoring.push({
            tahun_anggaran: tahun_anggaran,
            uraian: $("#untuk").val(),
            nominal_pengajuan: nominal_pengajuan,
            created_at: tgl_sistem
        });

        // Kirim data ke controller menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/pengajuan/simpan_rincian",
            type: "POST",
            data: {data: array_data, kode_dpsj: $("#kode_dpsj").text(), nomor_pengajuan: $("#nomor_pengajuan").val(), 
                data_pejabat: array_pejabat, data_monitoring: array_monitoring},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                alert("Data berhasil disimpan!");
                location.href = "../pengajuan";
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                alert("Terjadi kesalahan saat menyimpan data.");
                console.log(error);
            }
        });
    });

    $("select#penanggung_jawab").click(function(){
        var nama = $(this).val();
        //console.log(nama); return false;
        $.ajax({
            url: "<?=base_url()?>pengajuan/get_nip_pejabat",
            type: "POST",
            data: {nama: nama},
            dataType: 'json',
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                $("#nip").val(response.nip);
                $("#telp").val(response.telp);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat menyimpan data.");
                console.log(error);
            }
        });
    });
});



function sumGroupBy(arr, groupByProperty, sumProperty) {
    const grouped = {};

    arr.forEach(item => {
        const groupValue = item[groupByProperty];
        if (!grouped[groupValue]) {
            grouped[groupValue] = 0;
        }
        grouped[groupValue] += Number(item[sumProperty]);
    });

    return Object.entries(grouped).map(([group, sum]) => ({ [groupByProperty]: group, [sumProperty]: sum }));
}
</script>