<?php
?>
<style>
.styled-table {
   border-collapse: collapse;
   margin: 25px 0;
   font-size:12px;
   font-family: Arial, Helvetica, sans-serif;
   min-width: 400px;
   box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
   background-color: #009879;
   color: #ffffff;
   text-align: left;
   font-size:1em;
}
.styled-table thead tr th {
   padding: 5px 15px;
}
.styled-table td {
   padding: 7px 15px;
}
.styled-table tbody tr {
   border-bottom: 1px solid #dddddd;
}
.styled-table tbody tr:nth-of-type(even) {
   background-color: #f3f3f3;
}
.styled-table tbody tr:last-of-type {
   border-bottom: 2px solid #009879;
}
.styled-table tbody tr.active-row {
   font-weight: bold;
   color: #009879;
}

/* Tables detail
table#tabel-rincian tr td, th {
	border:1px solid gray;
} */

#tabel-rincian {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 99%;
    margin:auto;
}

#tabel-rincian td, #tabel-rincian th {
    border: 1px solid #ddd;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;
    font-size:12px;
}

#tabel-rincian th{text-align:center}
#tabel-rincian tr:nth-child(even){background-color: #f2f2f2;}
#tabel-rincian tr:nth-child(odd){background-color: #fff;}

#tabel-rincian tr:hover {background-color: #ddd;}

#tabel-rincian th {
    padding-top: 2px;
    padding-bottom: 2px;
    text-align: center;
    vertical-align: middle;
    background-color: #4CAF50;
    color: white;
}

.kalban, .bimbingan {
	cursor:pointer;
	text-align:right;
}

table#tabel-rincian tr td.total {
	text-align:right !important;
	font-weight: bold !important;
	background: #BCFF00 !important;
}

.space {
	background-color:#fff;
}

/* Tables */
table.tabel tr td, th {
	border:1px solid #c315c3ff;
}

.tabel {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    margin:auto;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

.tabel td, .tabel th {
    border: 1px solid #009879;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;
    font-size:12px;
}

.tabel th{text-align:center}
.tabel tr:nth-child(even){background-color: #f2f2f2;}
.tabel tr:nth-child(odd){background-color: #fff;}

.tabel tr:hover {background-color: #ddd;}

.tabel th {
    padding-top: 2px;
    padding-bottom: 2px;
    text-align: center;
    vertical-align: middle;
    background-color: #009879;
    color: white;
}

.kalban, .bimbingan {
	cursor:pointer;
	text-align:right;
}

table.tabel tr td.total {
	text-align:right !important;
	font-weight: bold !important;
	background: #BCFF00 !important;
}
</style>