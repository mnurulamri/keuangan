<?php
?>

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

/* Untuk autocomplete
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
 */
/* Untuk tabel rincian */
#tabel-rincian td {
    vertical-align: middle !important;
}

#tabel-rincian .sisa-anggaran {
    font-weight: bold;
    color: #333;
}
/* autocomplete deskrip dpss */
table.autocomplete-dpsj {
	/*left: 30px;
	width:191px;*/
	position: absolute;
	z-index: 99;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	
}
table.autocomplete-dpsj tr {
	cursor: pointer;
}
table.autocomplete-dpsj tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-dpsj tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-dpsj tr td:hover {
	background-color: #ddd;
}


/* autocomplete deskrip pc */
table.autocomplete-pc {
	/*left: 30px;
	width:191px;*/
	position: absolute;
	z-index: 99;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	
}
/*table.autocomplete-pc tr {
	cursor: pointer;
}*/
table.autocomplete-pc tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-pc tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
/*table.autocomplete-pc tr td:hover {
	background-color: #ddd;
}*/

.isi_pc:hover, .isi_akun:hover {
    color:#fa0;
    cursor: pointer;
}

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

.kotak{
    background-color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-top: 2px solid #fa0;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}

#pemohon tr td {
    border: 1px solid #fff;
    padding: 2px;
    font-size:14px;
}

#pemohon tr td.label {
    color: #555;
    font-weight: bold;
    width: 20%;
    text-align: right;
    padding-right: 10px;
    font-size:14px;
}
</style>