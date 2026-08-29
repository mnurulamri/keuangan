<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data ke Excel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .filter-group {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        select:focus, input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }
        .btn-export {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }
        .btn-export:hover {
            background: #45a049;
        }
        .btn-export:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .info-text {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
            padding: 15px;
            background: #e7f3ff;
            border-radius: 5px;
            border-left: 4px solid #2196F3;
        }
        .row {
            display: flex;
            gap: 20px;
        }
        .col {
            flex: 1;
        }
        @media (max-width: 600px) {
            .row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📊 Export Data ke Excel</h2>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo base_url('export_rka/export_to_excel'); ?>" method="post">
            <div class="filter-group">
                <h3 style="margin-top: 0; color: #333;">Filter Data</h3>
                <p style="color: #666; font-size: 14px;">Pilih filter untuk mengekspor data spesifik (opsional)</p>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="tahun_anggaran">Tahun Anggaran</label>
                            <select name="tahun_anggaran" id="tahun_anggaran">
                                <option value="">-- Semua Tahun --</option>
                                <?php foreach ($tahun as $t): ?>
                                    <option value="<?php echo $t['tahun_anggaran']; ?>">
                                        <?php echo $t['tahun_anggaran']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="form-group">
                            <label for="kode_dana">Kode Dana</label>
                            <input type="text" name="kode_dana" id="kode_dana" 
                                   placeholder="Masukkan kode dana" maxlength="2">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="kategori_kegiatan">Kategori Kegiatan</label>
                            <input type="text" name="kategori_kegiatan" id="kategori_kegiatan" 
                                   placeholder="Masukkan kategori kegiatan">
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn-export" id="btnExport">
                ⬇️ Export ke Excel
            </button>
        </form>
        
        <div class="info-text">
            <strong>💡 Informasi:</strong><br>
            • File akan diekspor dalam format .xlsx (Excel 2007+)<br>
            • Data akan difilter berdasarkan pilihan yang Anda tentukan<br>
            • Kolom angka akan diformat dengan pemisah ribuan<br>
            • Header baris akan tetap terlihat saat scroll
        </div>
    </div>
    
    <script>
        /*document.getElementById('btnExport').addEventListener('click', function(e) {
            this.textContent = '⏳ Memproses...';
            this.disabled = true;
            // Form akan submit secara normal
        });*/
    </script>
</body>
</html>