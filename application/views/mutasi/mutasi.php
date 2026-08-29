    <!-- application/views/mutasi/create.php -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $title; ?>
            <small>Tambah Mutasi Anggaran</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('mutasi'); ?>">Mutasi</a></li>
            <li class="active">Tambah</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Tambah Mutasi</h3>
                    </div>
                    
                    <?php echo form_open('mutasi/create', array('id' => 'form-mutasi')); ?>
                    <div class="box-body">
                        <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kode Kegiatan *</label>
                                    <select name="kode_kegiatan" id="kode_kegiatan" class="form-control select2" required>
                                        <option value="">Pilih Kode Kegiatan</option>
                                        <?php foreach($akun_options as $option): ?>
                                        <option value="<?php echo $option->kode_kegiatan; ?>" 
                                                data-kode-akun="<?php echo $option->kode_akun; ?>"
                                                data-kode-dana="<?php echo $option->kode_dana; ?>"
                                                data-nama-kegiatan="<?php echo $option->nama_kegiatan; ?>"
                                                data-deskripsi-akun="<?php echo $option->deskripsi_akun; ?>">
                                            <?php echo $option->kode_kegiatan . ' - ' . $option->nama_kegiatan; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kode Akun *</label>
                                    <input type="text" name="kode_akun" id="kode_akun" class="form-control" readonly required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kode Dana *</label>
                                    <input type="text" name="kode_dana" id="kode_dana" class="form-control" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal *</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Bukti *</label>
                                    <input type="text" name="no_bukti" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nilai Mutasi *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">Rp</div>
                                        <input type="number" name="mutasi" class="form-control" step="0.01" required placeholder="Positif untuk penambahan, negatif untuk pengurangan">
                                    </div>
                                    <small class="text-muted">Gunakan nilai negatif (-) untuk mengurangi saldo</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan mutasi"></textarea>
                        </div>
                        
                        <!-- Informasi Akun -->
                        <div class="box box-info collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Informasi Akun</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kegiatan</label>
                                            <input type="text" id="nama_kegiatan" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deskripsi Akun</label>
                                            <input type="text" id="deskripsi_akun" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Anggaran</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                <input type="text" id="anggaran" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Komitmen</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                <input type="text" id="komitmen" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Aktual</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                <input type="text" id="aktual" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Total Mutasi</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                <input type="text" id="total_mutasi" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sisa Saldo Saat Ini</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                <input type="text" id="sisa_saldo" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sisa Saldo Setelah Mutasi</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                <input type="text" id="sisa_saldo_baru" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="<?php echo site_url('mutasi'); ?>" class="btn btn-default">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();
    
    // When kegiatan is selected
    $('#kode_kegiatan').change(function() {
        var selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
            $('#kode_akun').val(selectedOption.data('kode-akun'));
            $('#kode_dana').val(selectedOption.data('kode-dana'));
            $('#nama_kegiatan').val(selectedOption.data('nama-kegiatan'));
            $('#deskripsi_akun').val(selectedOption.data('deskripsi-akun'));
            
            // Get akun detail via AJAX
            $.ajax({
                url: '<?php echo site_url("mutasi/get_akun_info"); ?>',
                type: 'POST',
                data: {
                    kode_kegiatan: selectedOption.val(),
                    kode_akun: selectedOption.data('kode-akun'),
                    kode_dana: selectedOption.data('kode-dana')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        
                        // Format currency
                        function formatCurrency(value) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'decimal',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(value);
                        }
                        
                        $('#anggaran').val(formatCurrency(data.anggaran));
                        $('#komitmen').val(formatCurrency(data.komitmen));
                        $('#aktual').val(formatCurrency(data.aktual));
                        $('#total_mutasi').val(formatCurrency(data.mutasi));
                        $('#sisa_saldo').val(formatCurrency(data.sisa_saldo));
                        
                        // Calculate new saldo based on mutation input
                        $('input[name="mutasi"]').on('input', function() {
                            var mutasiBaru = parseFloat($(this).val()) || 0;
                            var sisaSaldoBaru = parseFloat(data.sisa_saldo) + mutasiBaru;
                            $('#sisa_saldo_baru').val(formatCurrency(sisaSaldoBaru));
                        });
                    }
                }
            });
        } else {
            // Clear fields if no selection
            $('#kode_akun, #kode_dana, #nama_kegiatan, #deskripsi_akun').val('');
            $('#anggaran, #komitmen, #aktual, #total_mutasi, #sisa_saldo, #sisa_saldo_baru').val('');
        }
    });
    
    // Form validation
    $('#form-mutasi').validate({
        rules: {
            kode_kegiatan: "required",
            kode_akun: "required",
            kode_dana: "required",
            mutasi: {
                required: true,
                number: true
            },
            tanggal: "required",
            no_bukti: "required"
        },
        messages: {
            kode_kegiatan: "Pilih kode kegiatan",
            kode_akun: "Kode akun wajib diisi",
            kode_dana: "Kode dana wajib diisi",
            mutasi: {
                required: "Nilai mutasi wajib diisi",
                number: "Masukkan angka yang valid"
            },
            tanggal: "Tanggal wajib diisi",
            no_bukti: "No. bukti wajib diisi"
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>