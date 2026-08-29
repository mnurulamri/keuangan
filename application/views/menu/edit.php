<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= $title ?>
            <small>Edit Data Menu</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?= base_url('menu') ?>">Menu</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Edit Menu</h3>
                    </div>
                    
                    <?= form_open('menu/update/'.$menu['id']) ?>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="label">Label Menu *</label>
                            <input type="text" class="form-control" id="label" name="label" 
                                   value="<?= $menu['label'] ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="link">Link *</label>
                            <input type="text" class="form-control" id="link" name="link" 
                                   value="<?= $menu['link'] ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">Icon (Font Awesome)</label>
                            <input type="text" class="form-control" id="icon" name="icon" 
                                   value="<?= $menu['icon'] ?>" 
                                   placeholder="contoh: fa fa-dashboard">
                            <p class="help-block">Kosongkan jika tidak ada icon</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="parent">Parent Menu</label>
                            <select class="form-control" id="parent" name="parent">
                                <option value="0">- Tidak Ada Parent -</option>
                                <?php foreach($parent_menus as $parent): ?>
                                    <?php if($parent['id'] != $menu['id']): ?>
                                        <option value="<?= $parent['id'] ?>" 
                                                <?= $parent['id'] == $menu['parent'] ? 'selected' : '' ?>>
                                            <?= $parent['label'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort">Urutan</label>
                            <input type="number" class="form-control" id="sort" name="sort" 
                                   value="<?= $menu['sort'] ?>" min="0">
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="<?= base_url('menu') ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informasi Role Akses</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Role</th>
                                <th class="text-center">Akses</th>
                            </tr>
                            <?php 
                            $role_fields = ['pum', 'anggaran', 'korpum', 'manajer', 'kasir', 'verifikator', 'yunior_akuntan'];
                            $role_names = [
                                'pum' => 'PUM',
                                'anggaran' => 'Anggaran',
                                'korpum' => 'Korpum',
                                'manajer' => 'Manajer',
                                'kasir' => 'Kasir',
                                'verifikator' => 'Verifikator',
                                'yunior_akuntan' => 'Yunior Akuntan'
                            ];
                            ?>
                            <?php foreach($role_fields as $field): ?>
                            <tr>
                                <td><?= $role_names[$field] ?></td>
                                <td class="text-center">
                                    <?php if($menu[$field] == 1): ?>
                                        <span class="label label-success">
                                            <i class="fa fa-check"></i> Diizinkan
                                        </span>
                                    <?php else: ?>
                                        <span class="label label-danger">
                                            <i class="fa fa-times"></i> Tidak Diizinkan
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                        <p class="text-muted">
                            <small>Untuk mengubah akses per role, gunakan halaman utama pengaturan menu.</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>