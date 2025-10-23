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

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulir Pengajuan Dana</h3>
                    </div>
                    
                    <form role="form" action="<?= site_url('anggaran/pengajuan') ?>" method="post">
                        <div class="box-body">
                            
                            <!-- Bagian Nomor Pengajuan -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Nomor Pengajuan</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="nomor_pengajuan">Nomor</label>
                                        <input type="text" class="form-control" id="nomor_pengajuan" name="nomor_pengajuan" value="<?= $preview_nomor ?>" readonly style="font-weight: bold; font-size: 16px;">
                                        <small class="text-muted">Format: 000/ANG.BB/YYYY-KODEUNIT (Otomatis tergenerate)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian 1: Data Pemohon -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Data Pemohon</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="unit_id">PAF/Dept/Prog/Unit</label>
                                                <select class="form-control" id="unit_id" name="unit_id" required>
                                                    <option value="">Pilih Unit</option>
                                                    <?php foreach($units as $unit): ?>
                                                        <option value="<?= $unit->id ?>"><?= $unit->nama_unit ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="penanggung_jawab">Penanggung Jawab/Contact Person</label>
                                                <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nomor_identitas">NPM/NIP/NUP</label>
                                                <input type="text" class="form-control" id="nomor_identitas" name="nomor_identitas" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="telepon">No Telepon</label>
                                                <input type="text" class="form-control" id="telepon" name="telepon" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bagian 2: Rincian Pembayaran -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Rincian Pembayaran</h3>
                                </div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <label for="untuk_nama">Deskripsi DPSJ</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control deskripsi_dpsj" id="deskripsi_dpsj" name="deskripsi_dpsj" required>
                                            <span class="input-group-addon" id="deskripsi_dpsj">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                        <div id="kotaksugest_deskripsi_dpjs" ></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="untuk_nama">Untuk dan Atas Nama</label>
                                        <input type="text" class="form-control" id="untuk_nama" name="untuk_nama" required>
                                    </div>
                                    
                                    <!-- Di bagian Rincian Pembayaran -->
                                    <table class="table table-bordered" id="tabel-rincian">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="25%">Nomor dan Nama Project Costing</th>
                                                <th width="25%">Nomor dan Nama Akun</th>
                                                <th width="15%">Jumlah (Rp)</th>
                                                <th width="20%">Keterangan</th>
                                                <th width="5%">Sisa Anggaran</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <input type="text" name="project_costing[]" class="form-control project-costing" placeholder="Cari project costing...">
                                                    <input type="hidden" name="kode_kegiatan[]" class="kode-kegiatan">
                                                    <input type="hidden" name="nama_kegiatan[]" class="nama-kegiatan">
                                                </td>
                                                <td>
                                                    <input type="text" name="akun_search[]" class="form-control akun-search" placeholder="Cari akun..." disabled>
                                                    <input type="hidden" name="akun[]" class="akun">
                                                    <input type="hidden" name="deskripsi_akun[]" class="deskripsi-akun">
                                                </td>
                                                <td>
                                                    <input type="text" name="jumlah[]" class="form-control money jumlah" disabled>
                                                    <div class="help-block text-danger"></div>
                                                </td>
                                                <td><input type="text" name="keterangan[]" class="form-control" disabled></td>
                                                <td class="sisa-anggaran text-right">-</td>
                                                <td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <button type="button" class="btn btn-primary btn-sm" id="btn-add-row">
                                                        <i class="fa fa-plus"></i> Tambah Rincian
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Bagian 3: Pemeriksaan -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Pemeriksaan</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <p>Pemohon</p>
                                            <br><br><br>
                                            <p>(_______________________)</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p>Anggaran</p>
                                            <br><br><br>
                                            <p>(_______________________)</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p>Keuangan</p>
                                            <br><br><br>
                                            <p>(_______________________)</p>
                                        </div>
                                        <div class="col-md-3">
                                            <p>Mengetahui</p>
                                            <br><br><br>
                                            <p>(_______________________)</p>
                                        </div>
                                    </div>
                                    <div class="row text-center" style="margin-top: 30px;">
                                        <div class="col-md-12">
                                            <p>Menyetujui</p>
                                            <br><br><br>
                                            <p>(_______________________)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Ajukan</button>
                            <button type="button" class="btn btn-success" id="btn-print"><i class="fa fa-print"></i> Cetak</button>
                            <a href="<?= site_url('anggaran') ?>" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {

    // Ketika unit dipilih, update nomor pengajuan
    $('#unit_id').change(function() {
        var unit_id = $(this).val();
        if(unit_id) {
            $.ajax({
                url: '<?= site_url("anggaran/get_kode_unit/") ?>' + unit_id,
                type: 'GET',
                success: function(response) {
                    var kode_unit = response.kode || 'kom';
                    updateNomorPengajuan(kode_unit);
                }
            });
        }
    });
    
    function updateNomorPengajuan(kode_unit) {
        $.ajax({
            url: '<?= site_url("anggaran/generate_nomor_pengajuan/") ?>' + kode_unit,
            type: 'GET',
            success: function(response) {
                $('#nomor_pengajuan').val(response.nomor);
            }
        });
    }

    // Format mata uang
    $('.money').inputmask('numeric', {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        prefix: 'Rp ',
        rightAlign: false
    });
    
    // Tambah baris rincian
    $('#btn-add-row').click(function() {
        var rowCount = $('#tabel-rincian tbody tr').length + 1;
        var newRow = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td><input type="text" name="project_costing[]" class="form-control"></td>' +
            '<td><input type="text" name="akun[]" class="form-control"></td>' +
            '<td><input type="text" name="jumlah[]" class="form-control money"></td>' +
            '<td><input type="text" name="keterangan[]" class="form-control"></td>' +
            '<td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>' +
            '</tr>';
        $('#tabel-rincian tbody').append(newRow);
        $('.money').inputmask('numeric', {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            prefix: 'Rp ',
            rightAlign: false
        });
    });
    
    // Hapus baris rincian
    $(document).on('click', '.btn-remove-row', function() {
        $(this).closest('tr').remove();
        updateRowNumbers();
    });
    
    // Update nomor urut
    function updateRowNumbers() {
        $('#tabel-rincian tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    
    // Cetak form
    $('#btn-print').click(function() {
        window.print();
    });

    // Autocomplete Project Costing
    $(document).on('focus', '.deskripsi-dpsj', function() {
        var deskripsi_dpsj = $('#deskripsi_dpsj').val();
        var tahun_anggaran = 2025;
        $(this).autocomplete({
            source: function(request, response) {
                $.getJSON('<?= site_url("anggaran/search_dpsj") ?>', {
                    tahun_anggaran: 2025,
                    deskripsi_dpsj: deskripsi_dpsj
                }, response);
            },
            minLength: 2,
            select: function(event, ui) {
                var row = $(this).closest('tr');
                console.log(event); console.log(ui);
                return false;
            }
        });
    });
        
    // Autocomplete Project Costing
    $(document).on('focus', '.project-costing', function() {
        var unit_id = $('#unit_id').val();
        $(this).autocomplete({
            source: function(request, response) {
                $.getJSON('<?= site_url("anggaran/search_project") ?>', {
                    term: request.term,
                    unit_id: unit_id
                }, response);
            },
            minLength: 2,
            select: function(event, ui) {
                var row = $(this).closest('tr');
                row.find('.kode-kegiatan').val(ui.item.id);
                row.find('.nama-kegiatan').val(ui.item.nama_kegiatan);
                row.find('.akun-search').prop('disabled', false);
                row.find('.project-costing').val(ui.item.value);
                return false;
            }
        });
    });
    
    // Autocomplete Akun
    $(document).on('focus', '.akun-search', function() {
        var row = $(this).closest('tr');
        var kode_kegiatan = row.find('.kode-kegiatan').val();
        
        $(this).autocomplete({
            source: function(request, response) {
                $.getJSON('<?= site_url("anggaran/search_akun") ?>', {
                    term: request.term,
                    kode_kegiatan: kode_kegiatan
                }, response);
            },
            minLength: 2,
            select: function(event, ui) {
                var row = $(this).closest('tr');
                row.find('.akun').val(ui.item.id);
                row.find('.deskripsi-akun').val(ui.item.deskripsi);
                row.find('.akun-search').val(ui.item.value);
                row.find('.jumlah').prop('disabled', false);
                row.find('.sisa-anggaran').text(formatRupiah(ui.item.sisa_anggaran));
                row.find('[name="keterangan[]"]').prop('disabled', false);
                return false;
            }
        });
    });
    
    // Validasi jumlah tidak melebihi sisa anggaran
    $(document).on('blur', '.jumlah', function() {
        var row = $(this).closest('tr');
        var kode_kegiatan = row.find('.kode-kegiatan').val();
        var akun = row.find('.akun').val();
        var jumlah = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
        
        if(kode_kegiatan && akun) {
            $.post('<?= site_url("anggaran/check_anggaran") ?>', {
                kode_kegiatan: kode_kegiatan,
                akun: akun,
                jumlah: jumlah
            }, function(response) {
                if(response.valid) {
                    row.find('.help-block').text('');
                    row.find('.sisa-anggaran').text(formatRupiah(response.sisa_anggaran - jumlah));
                } else {
                    row.find('.help-block').text(response.message);
                    row.find('.sisa-anggaran').text(formatRupiah(response.sisa_anggaran));
                }
            }, 'json');
        }
    });
    
    // Format Rupiah
    function formatRupiah(angka) {
        if(!angka) return '0';
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        
        if(ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        return 'Rp ' + (rupiah ? rupiah : '0');
    }
    
    // Tambah baris rincian baru
    $('#btn-add-row').click(function() {
        var rowCount = $('#tabel-rincian tbody tr').length + 1;
        var newRow = '<tr>' +
            '<td>' + rowCount + '</td>' +
            '<td>' +
                '<input type="text" name="project_costing[]" class="form-control project-costing" placeholder="Cari project costing...">' +
                '<input type="hidden" name="kode_kegiatan[]" class="kode-kegiatan">' +
                '<input type="hidden" name="nama_kegiatan[]" class="nama-kegiatan">' +
            '</td>' +
            '<td>' +
                '<input type="text" name="akun_search[]" class="form-control akun-search" placeholder="Cari akun..." disabled>' +
                '<input type="hidden" name="akun[]" class="akun">' +
                '<input type="hidden" name="deskripsi_akun[]" class="deskripsi-akun">' +
            '</td>' +
            '<td>' +
                '<input type="text" name="jumlah[]" class="form-control money jumlah" disabled>' +
                '<div class="help-block text-danger"></div>' +
            '</td>' +
            '<td><input type="text" name="keterangan[]" class="form-control" disabled></td>' +
            '<td class="sisa-anggaran text-right">-</td>' +
            '<td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="fa fa-times"></i></button></td>' +
        '</tr>';
        
        $('#tabel-rincian tbody').append(newRow);
        
        // Re-init inputmask
        $('.money').inputmask('numeric', {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            prefix: 'Rp ',
            rightAlign: false
        });
    });

    // autocomplete deskripsi dpjs    
    $("#deskripsi_dpjs").click(function()
    {
        alert("test")
        $("#kotaksugest_deskripsi_dpjs").show();
    });

    $(document).on("keyup", "#deskripsi_dpjs", function(){
    //$("#kotaksugest_lokasi").hide();

        var kata = $(this).val();
        //alert(kata)
        if(kata.length==0)
        {
            $("#kotaksugest_deskripsi_dpjs").css("visibility", "hidden");
        } else {
            $.ajax({
                url: "<?=base_url()?>index.php/anggaran/search_deskripsi_dpjs",
                type: "POST",
                data: {kata:kata},
                success: function(data)
                {
                $("#kotaksugest_deskripsi_dpjs").css("visibility", "");
                $("#kotaksugest_deskripsi_dpjs").show();
                $("#kotaksugest_deskripsi_dpjs").html(data)
                    //alert(data)
                }
            });
            //clock.start();
        }
    });

	$(document).on("click", ".isi", function()
	{    
	    //var nm_ruang = $(this).find("td").eq(0).text();
		var nm_ruang = $(this).text();
	    
	    $("#lokasi").val(nm_ruang);
	
	    $("#kotaksugest_lokasi").hide();
	});

});
</script>

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

/* Untuk autocomplete */
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

/* Untuk tabel rincian */
#tabel-rincian td {
    vertical-align: middle !important;
}

#tabel-rincian .sisa-anggaran {
    font-weight: bold;
    color: #333;
}

/* autocomplete deskrip dpjs */
table.autocomplete-lokasi {
	/*left: 30px;*/
	width:191px;
	position: absolute;
	z-index: 99;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	
}
table.autocomplete-lokasi tr {
	cursor: pointer;
}
table.autocomplete-lokasi tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 12px;
}
table.autocomplete-lokasi tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 12px;
}
table.autocomplete-lokasi tr td:hover {
	background-color: #ddd;
}

table.autocomplete-lokasi-1 tr {
	cursor: pointer;
}
table.autocomplete-lokasi-1 tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 12px;
}
table.autocomplete-lokasi-1 tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 12px;
}
table.autocomplete-lokasi-1 tr td:hover {
	background-color: #ddd;
}
</style>