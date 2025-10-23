
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
        <div class="user-panel">
          <?php if($this->session->userdata('logged_anggaran')['username'] == 'admin') { ?>
            <div class="header" style="padding-left:30px">
                <a href="<?=base_url()?>auth/set_role_admin_sidebar" style="color:gold; font-style:italic; font-size:13px;">
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
$menu = menu(); // Fetch the menu based on user role
function getMenu($parent = 0, $menu) {
    $html = '';
    foreach ($menu as $item) {
        if ($item['parent'] == $parent) {
            $html .= '<li>';
            $html .= '<a href="' . site_url($item['link']) . '">' . $item['label'] . '</a>';
            $children = getMenu($item['id'], $menu);
            if ($children) {
                $html .= '<ul class="treeview-menu" style="display:block">' . $children . '</ul>';
            }
            $html .= '</li>';
        }
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
          <i class="fa fa-sign-out"></i> <span>Logout</span>
        </a>
      </li>';

    $html .= '</ul>';
    return $html;
}
echo renderMenu($menu);
?>

  </section>
  <!-- /.sidebar -->
</aside>