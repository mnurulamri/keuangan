<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>

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
        var tahun_anggaran = $("#tahun").val();

        $.ajax({
            url: "<?=base_url()?>index.php/mutasi/search_project",
            type: "POST",
            data: {kata:kata, dpsj:dpsj, tahun_anggaran:tahun_anggaran},
            success: function(data)
            {
                $(".kotaksugest_pc").css("visibility", "");
                $(".kotaksugest_pc").show();
                $(".kotaksugest_pc").html(data);
            }
        });


        // nonaktifkan tag input akun -> masih salah krn tampilan input malah ilang
        var deskripsi_akun = $(this).parent().next().next().next().children(".akun").val();
        //$(this).parent().next().next().next().text(deskripsi_akun);
        if ($(this).parent().next().next().next().children(".akun").is(':disabled')) {
            // Element is disabled
            console.log('Element is disabled');
        } else {
            $(this).parent().next().next().next().text(deskripsi_akun);
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
        var tahun_anggaran = $("#tahun").val();

        $.ajax({
            url: "<?=base_url()?>index.php/mutasi/search_project",
            type: "POST",
            data: {kata:kata, dpsj:dpsj, tahun_anggaran:tahun_anggaran},
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
        var tahun_anggaran = $("#tahun").val();
        console.log(dpsj+'tahun_anggaran:'+tahun_anggaran)
        if(kata.length==0)
        {
            //$("#kode_akun").text("");            
            $(".kotaksugest_pc").text("");
        } else {
            $.ajax({
                url: "<?=base_url()?>index.php/mutasi/search_project",
                type: "POST",
                data: {kata:kata, dpsj:dpsj, tahun_anggaran:tahun_anggaran},
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
        var id = $("#id").val();
        var kategori_kegiatan = $(this).parent().parent().find("td").eq(3).text();
        var array_nama_kegiatan = txt.split(":");
        var nama_kegiatan = array_nama_kegiatan[1];
        var arrays_kode_kegiatan = array_nama_kegiatan[0];//$(this).parent().find("td").eq(1).text();
        var array_kode_kegiatan = arrays_kode_kegiatan.split("-");
        var kode_kegiatan = array_kode_kegiatan[1];

        $(this).parent().parent().parent().parent().closest("td").prev().text(kode_kegiatan); 
        $("#kategori_kegiatan_"+id).text(kategori_kegiatan);
        $(this).parent().parent().parent().parent().closest("td").next().next().next().find(".akun").prop("disabled", false);      
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
        var tahun_anggaran = $("#tahun").val();

        $.ajax({
            url: "<?=base_url()?>index.php/anggaran/search_akun",
            type: "POST",
            data: {kata:kata, kode_kegiatan:kode_kegiatan, tahun_anggaran:tahun_anggaran},
            success: function(data)
            {
                $(".kotaksugest_akun").css("visibility", "");
                $(".kotaksugest_akun").show();
                $(".kotaksugest_akun").html(data);
            }
        });

        // nonaktifkan tag input project-costing
        var nama_kegiatan = $(this).parent().prev().prev().prev().children(".project-costing").val();
        $(this).parent().prev().prev().prev().text(nama_kegiatan);
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
        var tahun_anggaran = $("#tahun").val();

        $.ajax({
            url: "<?=base_url()?>index.php/anggaran/search_akun",
            type: "POST",
            data: {kata:kata, kode_kegiatan:kode_kegiatan, tahun_anggaran:tahun_anggaran},
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
        var tahun_anggaran = $("#tahun").val();
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
                data: {kata:kata, kode_kegiatan:kode_kegiatan, tahun_anggaran:tahun_anggaran},
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
        var tahun_anggaran = $("#tahun").val();
        var kode_kegiatan = $("#kode_kegiatan_"+id).text();
        var kode_akun = $("#kode_akun_"+id).text();
        var kode_dana = $("#dana_"+id).text();
        var jumlah = $("#jumlah_"+id).text();
        if(jumlah == "" || jumlah == null || typeof jumlah === 'undefined' || isNaN(jumlah)|| jumlah == 'NaN'|| jumlah == 'Infinity')
        {
            jumlah = 0;
        } else {
            var jumlah = jumlah.replace(/,/g, ''); // Hapus tanda koma
            var jumlah = parseFloat(jumlah);
        }

        console.log('tahun_anggaran:'+tahun_anggaran+' kode_dpsj:'+kode_dpsj+' kode_kegiatan:'+kode_kegiatan+' kode_akun:'+kode_akun+' kode_dana:'+kode_dana+'jumlah:'+jumlah);
        $.ajax({
            url: "<?=base_url()?>Sisa_anggaran_mutasi",
            type: "POST",
            data: {tahun_anggaran: tahun_anggaran, kode_dpsj: kode_dpsj, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana, jumlah:jumlah},
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
    $(document).on("click", "#btn-add-row", function() {        
        var newId = $("#newId").val() || 0; // Ambil nilai newId dari input tersembunyi atau set ke 0 jika tidak ada
        var rowCount = $('#tabel-rincian tbody tr').length + 1;
        var newRow = '<tr>' +
            '<td id="99999999999">' + rowCount + '</td>' +
            '<td class="kode-kegiatan" id="kode_kegiatan_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="nama-kegiatan" data-id="'+newId+'"><input type="text" name="nama_kegiatan[]" class="form-control nama-kegiatan project-costing hovertext" title="" placeholder="Cari project costing..." autocomplete="off"></td>' +
            '<td class="kategori-kegiatan" id="kategori_kegiatan_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="kode-akun" id="kode_akun_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="deskripsi-akun" id="akun_'+newId+'" data-id="'+newId+'"><input type="text" name="deskripsi_akun[]" class="form-control akun" placeholder="Cari akun..." disabled autocomplete="off" data-id="'+newId+'"></td>' +
            '<td class="kode-dana" id="dana_'+newId+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>' +
            '<td class="jumlah" id="jumlah_'+newId+'"><input type="text" name="jumlah[]" class="form-control money input-jumlah" data-id="jumlah_'+newId+'" value="0"></td>' +
            '<td class="keterangan" contenteditable="true"></td>' +
            '<td class="sisa_anggaran" id="sisa_anggaran_'+newId+'"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>' +
            '</tr>';
            
        // Tambahkan baris baru ke tabel
        $('#tabel-rincian tbody').append(newRow);

        newId++;
        $("#newId").val(newId);
        $("#btn-add-row").prop("disabled", true);
        console.log(newRow);
    });

    // Hapus baris rincian
    $(document).on('click', '.btn-remove-row', function() {

        var id = $(this).attr("id");
		if (!confirm("Apakah Anda yakin ingin menghapus data rincian mutasi ini?")) {
            return false;
        } else {

            $.ajax({
                url: "<?=base_url()?>index.php/mutasi_edit/deletePengajuanRincian",
                type: "POST",
                data: {id:id},
                success: function(data)
                {
                    location.reload(); // reload halaman setelah sukses
                }
            });

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
        }
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
        var tahun_anggaran = $("#tahun").val();
        var kode_kegiatan = $("#kode_kegiatan_"+id).text();
        var kode_akun = $("#kode_akun_"+id).text();
        var kode_dana = $("#dana_"+id).text();
        console.log('kode_dpsj:'+kode_dpsj+' kode_kegiatan:'+kode_kegiatan+' kode_akun:'+kode_akun+' kode_dana:'+kode_dana);
        //var sisa_anggaran = "<?php //echo sisaAnggaran($kode_dpsj, $kode_kegiatan, $kode_akun, $kode_dana); ?>";
        //console.log('sisa anggaran:'+sisa_anggaran);
        $.ajax({
            url: "<?=base_url()?>Sisa_anggaran_mutasi",
            type: "POST",
            data: {tahun_anggaran: tahun_anggaran, kode_dpsj: kode_dpsj, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana, jumlah:jumlah},
            //dataType: 'json',
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                if (response == "anggaran tidak mencukupi") {
                    alert("Anggaran tidak mencukupi!");
                    $("#simpan").prop("disabled", true);
                    $("#jumlah_"+id).text("0");
                    
                    // jika anggaran tidak mencukupi, hitung ulang total
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
    
    $(document).on("input", ".input-jumlah", function(evt){
        /*let keyCode = $(this).val()
        var charCode = (evt.which) ? evt.which : keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        } else {
            //var jumlah = $(this).val();
            // Remove all non-digit characters except decimal point
            let value = keyCode.replace(/[^\d.]/g, '');
            let jumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            $(this).val(jumlah);
            console.log('jumlah input:'+jumlah);
        }*/

        /*let value = keyCode.replace(/[^\d.]/g, '');
        let jumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).val(jumlah);
        console.log('jumlah input:'+jumlah);*/
        
        let v = $(this).val();
        
        v = v.replace(/[^-\d]/g, '');
        //v = v.replace(/(\d{1,2})$/g, ',$1');
        v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        
        v = v ? '' + v : '';
        $(this).val(v);
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
    
    // Simpan data pengajuan
    $(document).on("click", "#simpan", function() {
        // ambil data tahun dan bulan
        var tahun = $("#tahun").val();
        var bulan = $("#bulan").val();

        // ambil data kod_grup
        var kode_grup = $(this).data("kode_grup");

        // ambil data pemohon
        var nomor_pengajuan = $("#nomor_pengajuan").val();     
        var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");     
        var penanggung_jawab = $("#penanggung_jawab").val();
        var nip = $("#nip").val();
        var telp = $("#telp").val();        
        var untuk = $("#untuk").val();

        // ambil data rincian  
        var kode_dpsj = $("#kode_dpsj").text();
        var deskripsi_dpsj = $("#deskripsi_dpsj").val();
        //var nama_pengajuan = $("#nama_pengajuan").val();
        var array_data = [];
        var nominal_pengajuan = 0;

        var page = $("#temp_page").val(); // ambil halaman saat ini
            
        $('#tabel-rincian tbody tr').each(function(row, tr) 
        {
            var id = $(this).find('td').eq(0).attr("id");
            var kode_kegiatan = $(this).find('td').eq(1).text();
            var nama_kegiatan = $(this).find('td').eq(2).text();
            var kategori_kegiatan = $(this).find('td').eq(3).text();
            var kode_akun = $(this).find('td').eq(4).text();
            var deskripsi_akun = $(this).find('td').eq(5).text();
            var kode_dana = $(this).find('td').eq(6).text();
            var jumlah = $(this).find('td').eq(7).text();
            var jumlah = jumlah.replace(/,/g, '');
            var jumlah = parseFloat(jumlah);
            var keterangan = $(this).find('td').eq(8).text();  
            var tgl_sistem = new Date().toISOString().slice(0, 19).replace('T', ' '); // format YYYY-MM-DD HH:MM:SS                      
            nominal_pengajuan = parseFloat(nominal_pengajuan) + jumlah;
            
            array_data.push({        
                id: id,     
                tahun: tahun,
                bulan: bulan,
                kode_grup: kode_grup,
                kode_dpsj: kode_dpsj,
                deskripsi_dpsj: deskripsi_dpsj,
                kode_kegiatan: kode_kegiatan,
                nama_kegiatan: nama_kegiatan,
                kode_akun: kode_akun,
                deskripsi_akun: deskripsi_akun,
                kode_dana: kode_dana,
                //nomor_pengajuan: nomor_pengajuan,
                mutasi: jumlah,
                keterangan: keterangan,
                created_at: tgl_sistem,
                created_by: "xxx"
            }); 
        });

        // kirim data ke server
        $.ajax({
            url: "<?=base_url()?>index.php/mutasi_edit/simpan",
            type: "POST",
            data: {
                tahun:tahun,
                bulan:bulan,
                kode_grup:kode_grup,
                id_pengajuan_pemohon: id_pengajuan_pemohon, // ambil id pengajuan pemohon
                nomor_pengajuan: $("#nomor_pengajuan").val(), // ambil nomor pengajuan
                //untuk: $("#untuk").val(),
                penanggung_jawab: $("#penanggung_jawab").val(),
                nip: $("#nip").val(),
                telp: $("#telepon").val(),
                kode_dpsj: kode_dpsj,
                deskripsi_dpsj: deskripsi_dpsj,
                rincian: array_data  //: JSON.stringify(rincian)
                //nama_pengajuan: nama_pengajuan,                
            },
            success: function(response) {
                alert("Data pengajuan berhasil disimpan!");
                //location.reload(); // reload halaman setelah sukses
                getDataPage(page);
                $("#modal-ajukan").modal('hide');
                console.log(response);
            },
            error: function(xhr, status, error) {
                alert("Terjadi kesalahan saat menyimpan data pengajuan.");
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

    $(document).on("click", ".money", function(){
        $(this).select();
    });

    $(document).on("change", "#tahun, #bulan", function()
    {        
        var tahun = $("#tahun").val();
        var bulan = $("#bulan").val();
        $.ajax({
            url: "<?=base_url()?>mutasi/lock_data",
            type: "POST",
            data: {tahun: tahun, bulan: bulan},
            success: function(data)
            {
                if(data==0){
                    $("#data-rincian-mutasi").css("display", "block");
                    $("#simpan").prop("disabled", false);
                    $("#btn-add-row").prop("disabled", false);
                } else {
                    //alert("Data pada periode ini sudah dikunci. Anda tidak dapat menambah atau mengubah data.");
                    $("#data-rincian-mutasi").css("display", "none");
                    $("#simpan").prop("disabled", true);
                    $("#btn-add-row").prop("disabled", true);
                }
                
                console.log(data)
            }
        });
    });  

    $(document).on("input", "#test-number", function(evt)
    {
        /*let value = $(this).val().replace(/[^\d.]/g, '');
        let jumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        var result = jumlah.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        */
       //var result = $(this).val().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //$("#test-number").val(result);
        //console.log(result);
    });
    
    $(document).on("blur", "#test-number", function(evt)
    {
        /*let value = $(this).val().replace(/[^\d.]/g, '');
        let jumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        var result = jumlah.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        */
       var result = $(this).val().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $("#test-number").val(result);
        console.log(result);
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