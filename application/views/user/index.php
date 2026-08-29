<div class="content-wrapper">
    <section class="content-header">
        <h1>Pengaturan User</h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Pengaturan User</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar User</h3>
                        <div class="pull-right">
                            <button class="btn btn-primary" onclick="add_user()">
                                <i class="fa fa-plus"></i> Tambah User
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="user_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Unit</th>
                                    <th>DPSJ</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $user->nama ?></td>
                                    <td><?= $user->username ?></td>
                                    <td><?= $user->email ?></td>
                                    <td><span class="label label-primary"><?= ucfirst($user->role) ?></span></td>
                                    <td><?= $user->unit ?: ($user->nama_unit ?: '-') ?></td>
                                    <td><?= $user->deskripsi_dpsj ?: '-' ?></td>
                                    <td>
                                        <?php if ($user->is_active == 1): ?>
                                            <span class="label label-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="label label-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary btn-edit" 
                                                    data-id="<?= $user->id ?>" 
                                                    data-nama="<?= $user->nama ?>"
                                                    data-username="<?= $user->username ?>"
                                                    data-email="<?= $user->email ?>"
                                                    data-role="<?= $user->role ?>"
                                                    data-kode_bidang="<?= $user->kode_bidang ?>"
                                                    data-kode_dpsj="<?= $user->kode_dpsj ?>"
                                                    data-unit="<?= $user->unit ?>"
                                                    data-is_active="<?= $user->is_active ?>"
                                                    title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <!--<button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                    data-id="<?= $user->id ?>" 
                                                    data-nama="<?= $user->nama ?>"
                                                    title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>-->
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Form User</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Lengkap *</label>
                            <div class="col-md-9">
                                <input name="nama" placeholder="Nama Lengkap" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Username *</label>
                            <div class="col-md-9">
                                <input name="username" placeholder="Username" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email" class="form-control" type="email">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-9">
                                <input name="password" placeholder="Password (kosongkan jika tidak diubah)" 
                                       class="form-control" type="password">
                                <span class="help-block">Minimal 6 karakter</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Role *</label>
                            <div class="col-md-9">
                                <select name="role" class="form-control">
                                    <option value="">Pilih Role</option>
                                    <?php foreach ($roles as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Unit</label>
                            <div class="col-md-9">
                                <select name="kode_bidang" id="kode_bidang" class="form-control" 
                                        onchange="getUnitsByBidang(this.value)">
                                    <option value="">Pilih Nama Unit</option>
                                    <?php 
                                    $unique_bidang = [];
                                    foreach ($units as $unit): 
                                        if (!in_array($unit->kode_bidang, $unique_bidang) && $unit->kode_bidang):
                                            $unique_bidang[] = $unit->kode_bidang;
                                    ?>
                                    <option value="<?= $unit->kode_bidang ?>"><?= $unit->nama_unit ?></option>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">DPSJ</label>
                            <div class="col-md-9">
                                <select name="kode_dpsj" id="kode_dpsj" class="form-control">
                                    <option value="">Pilih DPSJ</option>
                                </select>
                            </div>
                        </div>
                        
                        <!--<div class="form-group">
                            <label class="control-label col-md-3">Nama Unit</label>
                            <div class="col-md-9">-->
                                <input name="unit" placeholder="Nama Unit (jika tidak ada di dropdown)" 
                                       class="form-control" type="hidden">
                                <!--<span class="help-block">Isi manual jika unit tidak ada dalam daftar</span>
                            </div>
                        </div>-->
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_active" value="1" checked> Aktif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal_delete" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Konfirmasi Hapus</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user <strong id="delete_name"></strong>?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnDelete" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/AdminLTE/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script>
let save_method; // global variable to track save mode

$(document).ready(function() {
    // Initialize DataTable
    $('#user_table').DataTable({
        "responsive": true,
        "autoWidth": false
    });
    
    // Edit button click
    $('#user_table').on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const username = $(this).data('username');
        const email = $(this).data('email');
        const role = $(this).data('role');
        const kode_bidang = $(this).data('kode_bidang');
        const kode_dpsj = $(this).data('kode_dpsj');
        const unit = $(this).data('unit');
        const is_active = $(this).data('is_active');
        save_method = 'edit';
        
        $('[name="id"]').val(id);
        $('[name="nama"]').val(nama);
        $('[name="username"]').val(username);
        $('[name="email"]').val(email);
        $('[name="role"]').val(role);
        $('[name="unit"]').val(unit);
        $('[name="is_active"]').prop('checked', is_active == 1);
        
        // Set kode bidang dan load units
        if (kode_bidang) {
            $('#kode_bidang').val(kode_bidang);
            getUnitsByBidang(kode_bidang, function() {
                if (kode_dpsj) {
                    $('#kode_dpsj').val(kode_dpsj);
                }
            });
        }
        
        $('#modal_form').modal('show');
        $('.modal-title').text('Edit User');
    });
    
    // Delete button click
    $('#user_table').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        $('#delete_name').text(nama);
        $('#btnDelete').data('id', id);
        $('#modal_delete').modal('show');
    });
    
    // Confirm delete
    $('#btnDelete').click(function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: "<?= site_url('user/ajax_delete/') ?>" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $('#modal_delete').modal('hide');
                    showAlert('success', 'Berhasil', 'User berhasil dihapus');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showAlert('error', 'Error', 'Terjadi kesalahan saat menghapus data');
            }
        });
    });
});

function add_user() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('[name="id"]').val('');
    
    // Reset dropdowns
    $('#kode_bidang').val('');
    $('#kode_dpsj').html('<option value="">Pilih DPSJ</option>');
    
    $('#modal_form').modal('show');
    $('.modal-title').text('Tambah User Baru');
}

function save() {
    $('#btnSave').text('Menyimpan...').prop('disabled', true);
    
    const url = save_method == 'add' ? 
        "<?= site_url('user/ajax_save') ?>" : 
        "<?= site_url('user/ajax_update') ?>";
    
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {
            if (data.status) {
                $('#modal_form').modal('hide');
                showAlert('success', 'Berhasil', 'Data user berhasil disimpan');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                for (let i = 0; i < data.inputerror.length; i++) {
                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                }
            }
            $('#btnSave').text('Simpan').prop('disabled', false);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showAlert('error', 'Error', 'Terjadi kesalahan saat menyimpan data');
            $('#btnSave').text('Simpan').prop('disabled', false);
        }
    });
}

function getUnitsByBidang(kode_bidang, callback) {
    if (!kode_bidang) {
        $('#kode_dpsj').html('<option value="">Pilih DPSJ</option>');
        return;
    }
    
    $.ajax({
        url: "<?= site_url('user/ajax_get_units/') ?>" + kode_bidang,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            let options = '<option value="">Pilih DPSJ</option>';
            $.each(data, function(index, unit) {
                options += '<option value="' + unit.kode_dpsj + '">' + 
                          unit.deskripsi_dpsj + ' (' + unit.kode_dpsj + ')</option>';
            });
            $('#kode_dpsj').html(options);
            
            if (callback) callback();
        },
        error: function() {
            $('#kode_dpsj').html('<option value="">Error loading units</option>');
        }
    });
}

function showAlert(type, title, message) {
    // Using AdminLTE toast or alert
    const toast = `<div class="alert alert-${type} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-${type === 'success' ? 'check' : 'ban'}"></i> ${title}</h4>
        ${message}
    </div>`;
    
    $('.content').prepend(toast);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
}
</script>

<style>
.modal-header {
    background-color: #3c8dbc;
    color: white;
}
.btn-group {
    white-space: nowrap;
}
.label {
    font-size: 85%;
}

/* Untuk modal form */
.modal-body .form-horizontal .control-label {
    text-align: left;
}

/* Untuk table responsive */
.table-responsive {
    overflow-x: auto;
}

/* Untuk label role */
.label-primary {
    background-color: #3c8dbc;
}

/* Spacing untuk form groups */
.form-group {
    margin-bottom: 15px;
}
</style>