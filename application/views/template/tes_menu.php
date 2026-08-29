
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
        <div class="user-panel">
          <?php if($this->session->userdata('logged_anggaran')['username'] == 'admin') { ?>
            <div class="header" style="padding-left:30px">
                <a href="<?=base_url()?>auth/unit_kerja" style="color:gold; font-style:italic; font-size:13px;">
                  <b>
                    <span style="border:1px solid gold">&nbsp;<i class="fa fa-user"></i>&nbsp;&nbsp;Role&nbsp;&nbsp;</span>
                    <span style="border:1px solid gold;background:gold;color:#000">&nbsp;<?php echo $this->session->userdata('logged_anggaran')['role']?>&nbsp;&nbsp;</span>
                  </b>
                </a>
            </div>
            <?php } else { ?>
                <a href="#" style="color:gold; font-style:italic; font-size:13px; cursor: none;">
                  <b>
                    <span style="border:1px solid gold">&nbsp;<i class="fa fa-user"></i>&nbsp;&nbsp;Role&nbsp;&nbsp;</span>
                    <span style="border:1px solid gold;background:gold;color:#000">&nbsp;<?php echo $this->session->userdata('logged_anggaran')['role']?>&nbsp;&nbsp;</span>
                  </b>
                </a>
            <?php } ?>
        </div>
<?php
function getMenu($parent = 0, $menu) {
    $html = '';
    $i = 1;
    foreach ($menu as $item) {
        if ($item['parent'] == $parent) {
            $html .= '<li>';

            //tampilkan icon untuk $item['parent'] > 0
            if ($item['parent'] > 0) {
                $icon = '<i class="'.$item['icon'].'"></i> ';
            } else {
                $icon = '<i class="'.$item['icon'].'"></i>';
            }
            $html .= '<a href="' . site_url($item['link']) . '">' . $icon . '<span>' . $item['label'] . '</span>';
            // tampilkan ikon panah hanya untuk menu parent
            if ($item['parent'] == 0 AND  $item['label'] != 'Dashboard') {
                $html .= '
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span></a>';
            } else {
                $html .= '</a>';
            }
            $children = getMenu($item['id'], $menu);
            if ($children) {
                $html .= '<ul class="treeview-menu" style="display:none">' . $children . '</ul>';
            }
            $html .= '</li>';
        }
        $i++;
    }
    return $html;
}
function renderMenu($menu) {
	$html = '';
    $html .= '<ul class="sidebar-menu" data-widget="tree">';
	  $html .= '<li class="header">MENU</li>';

    $html .= getMenu(0, $menu);
    
    $html.= '
      <!-- logout link -->
      <li class="header"></li>
      <li>
        <a href="'.site_url('auth/logout').'">
          <i class="fa fa-sign-out text-danger"></i> <span>Logout</span>
        </a>
      </li>';

    $html .= '</ul>';
    return $html;
}
echo renderMenu($menu);
?>

      <!-- sidebar menu: : style can be found in sidebar.less
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display:block">
            <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li>
      </ul> -->
  </section>
  <!-- /.sidebar -->
</aside>