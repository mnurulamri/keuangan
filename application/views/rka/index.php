<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $title; ?>
            <small>Data Anggaran</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Anggaran</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Anggaran</h3>
                <div class="box-tools pull-right">
                    
                    <div class="form-inline" style="margin-right: 10px;display:none;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <select class="form-control input-sm" id="filter_tahun" style="width: 150px;">
                                <option value="">Semua Tahun</option>
                                <?php 
                                $tahun_sekarang = date('Y');
                                for($tahun = $tahun_sekarang; $tahun >= $tahun_sekarang - 5; $tahun--): 
                                ?>
                                <option value="<?php echo $tahun; ?>" <?php echo (isset($tahun_filter) && $tahun_filter == $tahun) ? 'selected' : ''; ?>>
                                    <?php echo $tahun; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div class="btn-group" style="margin-left: 5px;">
                            <button type="button" class="btn btn-sm btn-info" id="btn_filter">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <button type="button" class="btn btn-sm btn-default" id="btn_reset">
                                <i class="fa fa-refresh"></i> Reset
                            </button>
                        </div>
                    </div>

                    <a href="<?php echo base_url('RKA/create'); ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Tambah Data
                    </a>
                    <!-- export button -->
                    <form action="<?php echo base_url('export_rka/export_to_excel'); ?>" method="post">
                        <button type="submit" class="btn btn-success btn-sm" style="margin-left: 5px;">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </button>
                        <input type="hidden" name="tahun_anggaran" id="export_tahun_anggaran" value="2026">
                    </form>
                </div>
            </div>
            
            <div class="box-body">
                <!-- Info Box untuk Total Anggaran per Tahun -->
                <div class="row" id="info_total" style="margin-bottom: 20px; display: none;">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            <span id="total_info_text"></span>
                        </div>
                    </div>
                </div>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table id="table-anggaran" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun</th>
                                <th>Kode DPSJ</th>
                                <th>Deskripsi DPSJ</th>
                                <th>Kode Kegiatan</th>
                                <th>Nama Kegiatan</th>
                                <th>Kode Dana</th>
                                <th>Kode Akun</th>
                                <th>Deskripsi Akun</th>
                                <th>Flag Payroll</th>
                                <th>Anggaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($anggaran as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row->tahun_anggaran; ?></td>
                                <td><?php echo $row->kode_dpsj; ?></td>
                                <td><?php echo $row->deskripsi_dpsj; ?></td>
                                <td><?php echo $row->kode_kegiatan; ?></td>
                                <td><?php echo $row->nama_kegiatan; ?></td>
                                <td><?php echo $row->kode_dana; ?></td>
                                <td><?php echo $row->kode_akun; ?></td>
                                <td><?php echo $row->deskripsi_akun; ?></td>
                                <td>
                                    <?php 
                                    if ($row->flag_payroll == 'Procost Unit') {
                                        echo '<span class="label label-primary">Procost Unit</span>';
                                    } elseif ($row->flag_payroll == 'Procost Umum') {
                                        echo '<span class="label label-success">Procost Umum</span>';
                                    } elseif ($row->flag_payroll == 'Procost') {
                                        echo '<span class="label label-warning">Procost</span>';
                                    } elseif ($row->flag_payroll == 'Procost Remun') {
                                        echo '<span class="label label-info">Procost Remun</span>';
                                    } else {
                                        echo '<span class="label label-default">-</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-right"><?php echo number_format($row->anggaran, 0, ',', '.'); ?></td>
                                <td>
                                    <a href="<?php echo base_url('RKA/edit/'.$row->id); ?>" class="btn btn-warning btn-xs" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-xs delete-rka" data-id="<?php echo $row->id; ?>" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <!--<a href="<?php echo base_url('RKA/delete/'.$row->id); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus" disabled>
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <button class="btn btn-danger btn-xs"title="Hapus" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>-->
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot id="table-footer" style="display: none;">
                            <tr style="font-weight: bold; background-color: #f9f9f9;">
                                <td colspan="10" class="text-right">Total Anggaran:</td>
                                <td class="text-right" id="total_anggaran">0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- jQuery DataTables -->
<script>
$(document).ready(function() {
    /*$('#table-anggaran').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.22/i18n/Indonesian.json"
        }
    });*/
});
</script>

<!-- Tambahan CSS untuk styling filter -->
<style>
.box-tools .form-inline {
    display: flex;
    align-items: center;
}

.box-tools .input-group {
    width: auto;
}

.box-tools .input-group-addon {
    background-color: #f4f4f4;
    border-color: #d2d6de;
}

.box-tools select {
    border-color: #d2d6de;
    border-left: none;
}

.box-tools select:focus {
    border-color: #3c8dbc;
    outline: none;
}

.box-tools .btn-group {
    box-shadow: none;
}

.box-tools .btn-sm {
    padding: 5px 10px;
    font-size: 12px;
    line-height: 1.5;
}

#table-footer {
    font-size: 14px;
}

#table-footer td {
    border-top: 2px solid #ddd;
}

/* Animasi untuk filter */
#filter_tahun {
    transition: all 0.3s;
}

#filter_tahun:focus {
    border-color: #3c8dbc;
    box-shadow: 0 0 5px rgba(60, 141, 188, 0.5);
}

/* Responsif untuk mobile */
@media (max-width: 768px) {
    .box-tools .form-inline {
        flex-direction: column;
        align-items: stretch;
    }
    
    .box-tools .input-group {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .box-tools .btn-group {
        width: 100%;
        margin-left: 0 !important;
        margin-bottom: 5px;
    }
    
    .box-tools .btn-group .btn {
        width: 50%;
    }
    
    .box-tools .btn-primary {
        width: 100%;
        margin-left: 0 !important;
    }
}
</style>