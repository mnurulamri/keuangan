<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= $title ?>
            <small>Pengaturan Akses Menu Per Role</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar Menu</h3>
                        <div class="box-tools">
                            <div class="input-group input-group-sm">
                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Label</th>
                                    <th width="15%">Link</th>
                                    <th width="10%">Parent</th>
                                    <th width="10%">Sort</th>
                                    <?php foreach($roles as $role_key => $role_name): ?>
                                        <th width="8%" class="text-center"><?= $role_name ?></th>
                                    <?php endforeach; ?>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach($menus as $menu): ?>
                                <tr id="menu-row-<?= $menu['id'] ?>">
                                    <td><?= $no++ ?></td>
                                    <td><?= $menu['label'] ?></td>
                                    <td><?= $menu['link'] ?></td>
                                    <td>
                                        <?php 
                                            $parent_name = '-';
                                            if($menu['parent'] != 0) {
                                                foreach($menus as $parent_menu) {
                                                    if($parent_menu['id'] == $menu['parent']) {
                                                        $parent_name = $parent_menu['label'];
                                                        break;
                                                    }
                                                }
                                            }
                                            echo $parent_name;
                                        ?>
                                    </td>
                                    <td><?= $menu['sort'] ?></td>
                                    
                                    <?php foreach($roles as $role_key => $role_name): ?>
                                        <td class="text-center">
                                            <input type="checkbox" 
                                                   class="role-checkbox" 
                                                   data-menu-id="<?= $menu['id'] ?>" 
                                                   data-role="<?= $role_key ?>"
                                                   <?= $menu[$role_key] == 1 ? 'checked' : '' ?>>
                                            <!--<i class="fa fa-<?= $menu[$role_key] == 1 ? 'check text-success' : 'times text-danger' ?>"></i>-->
                                        </td>
                                    <?php endforeach; ?>
                                    
                                    <td>
                                        <a href="<?= base_url('menu/edit/'.$menu['id']) ?>" class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
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

<!-- Modal untuk konfirmasi edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Menu</h4>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label for="editLabel">Label</label>
                        <input type="text" class="form-control" id="editLabel" name="label">
                    </div>
                    <div class="form-group">
                        <label for="editLink">Link</label>
                        <input type="text" class="form-control" id="editLink" name="link">
                    </div>
                    <div class="form-group">
                        <label for="editIcon">Icon</label>
                        <input type="text" class="form-control" id="editIcon" name="icon" placeholder="fa fa-icon">
                    </div>
                    <input type="hidden" id="editId" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveEdit">Simpan</button>
            </div>
        </div>
    </div>
</div>

<style>
    .role-checkbox {
        cursor: pointer;
        transform: scale(1);
    }
    .fa-check, .fa-times {
        margin-left: 10px;
        font-size: 14px;
    }
</style>

<script>
$(document).ready(function() {
    // Update akses role via AJAX
    $('.role-checkbox').on('change', function() {
        var menuId = $(this).data('menu-id');
        var role = $(this).data('role');
        var isChecked = $(this).is(':checked');
        var checkbox = $(this);
        var icon = checkbox.next('i');
        
        // Tampilkan loading
        icon.removeClass('fa-check fa-times text-success text-danger').addClass('fa-spinner fa-spin');
        
        $.ajax({
            url: '<?= base_url('menu/update_role_access') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                menu_id: menuId,
                role: role,
                value: isChecked
            },
            success: function(response) {
                if(response.status == 'success') {
                    if(isChecked) {
                        icon.removeClass('fa-spinner fa-spin').addClass('fa-check text-success');
                    } else {
                        icon.removeClass('fa-spinner fa-spin').addClass('fa-times text-danger');
                    }
                    
                    // Show success message
                    toastr.success(response.message);
                } else {
                    // Rollback checkbox state
                    checkbox.prop('checked', !isChecked);
                    icon.removeClass('fa-spinner fa-spin').addClass(isChecked ? 'fa-times text-danger' : 'fa-check text-success');
                    toastr.error(response.message);
                }
            },
            error: function() {
                // Rollback on error
                checkbox.prop('checked', !isChecked);
                icon.removeClass('fa-spinner fa-spin').addClass(isChecked ? 'fa-times text-danger' : 'fa-check text-success');
                toastr.error('Terjadi kesalahan pada server');
            }
        });
    });

    // Untuk tabel search (jika diperlukan)
    $('input[name="table_search"]').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

// Jika menggunakan Toastr untuk notifikasi, tambahkan di header
// <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
// <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Toastr Configuration -->
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>