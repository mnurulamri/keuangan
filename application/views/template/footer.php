<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong><a href="htttp://fisip.ui.ac.id">Fakultas Ilmu Sosial dan Ilmu Politik Universitas Indonesia</a>.</strong>
</footer>

</div>
<!-- ./wrapper -->



<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->

<script>
  // Inisialisasi input mask untuk format uang
  /*$('.money').inputmask('numeric', {
    radixPoint: ",",
    groupSeparator: ".",
    digits: 2,
    autoGroup: true,
    prefix: 'Rp ',
    rightAlign: false
  });*/
  
  // Inisialisasi tooltip
  $('[data-toggle="tooltip"]').tooltip();
  
  // Konversi jQuery UI untuk conflict dengan other libraries
  $.widget.bridge('uibutton', $.ui.button);
</script>
</body>
</html>