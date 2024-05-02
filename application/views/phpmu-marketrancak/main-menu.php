<nav class="navigation">
    <div class="ps-container">
        <div class="navigation__left">
            <div class="menu--product-categories">
                <?php include "inc_kategori.php"; ?>
            </div>
        </div>
        <div class="navigation__right">
        <?php 
                function main_menu() {
                    $ci = & get_instance();
                    $query = $ci->db->query("SELECT id_menu, nama_menu, link, id_parent FROM menu where aktif='Ya' AND position='Bottom' order by urutan");
                    $menu = array('items' => array(),'parents' => array());
                    foreach ($query->result() as $menus) {
                        $menu['items'][$menus->id_menu] = $menus;
                        $menu['parents'][$menus->id_parent][] = $menus->id_menu;
                    }
                    if ($menu) {
                        $result = build_main_menu(0, $menu);
                        return $result;
                    }else{
                        return FALSE;
                    }
                }
        
                function build_main_menu($parent, $menu) {
                    $html = "";
                    if (isset($menu['parents'][$parent])) {
                        if ($parent=='0'){
                            $html .= "<ul class='menu'>";
                        }else{
                            $html .= "<ul class='sub-menu'>";
                        }
                        foreach ($menu['parents'][$parent] as $itemId) {
                            if (!isset($menu['parents'][$itemId])) {
                                if(preg_match("/^http/", $menu['items'][$itemId]->link)) {
                                    $html .= "<li class='current-menu-item'><a target='_BLANK' href='".$menu['items'][$itemId]->link."'>".$menu['items'][$itemId]->nama_menu."</a><span class='sub-toggle'></span></li>";
                                }else{
                                    $html .= "<li class='current-menu-item'><a href='".base_url().''.$menu['items'][$itemId]->link."'>".$menu['items'][$itemId]->nama_menu."</a><span class='sub-toggle'></span></li>";
                                }
                            }
                            if (isset($menu['parents'][$itemId])) {
                                if(preg_match("/^http/", $menu['items'][$itemId]->link)) {
                                    $html .= "<li class='menu-item-has-children'><a target='_BLANK' href='".$menu['items'][$itemId]->link."'><span>".$menu['items'][$itemId]->nama_menu."</span></a>";
                                }else{
                                    $html .= "<li class='menu-item-has-children'><a href='".base_url().''.$menu['items'][$itemId]->link."'><span>".$menu['items'][$itemId]->nama_menu."</span></a>";
                                }
                                $html .= build_main_menu($itemId, $menu);
                                $html .= "</li>";
                            }
                        }
                        $html .= "</ul>";
                    }
                    return $html;
                }
                echo main_menu();
            ?>
            <ul class="navigation__extra">
                <?php 
                    $topmenu = $this->model_utama->view_where_ordering_limit('menu',array('position' => 'Top','aktif' => 'Ya'),'urutan','ASC',0,5);
                    foreach ($topmenu->result_array() as $row) {
                        if(preg_match("/^http/", $row['link'])) {
                            echo "<li><a href='$row[link]'>$row[nama_menu]</a></li>";
                        }else{
                            echo "<li><a href='".base_url()."$row[link]'>$row[nama_menu]</a></li>";
                        }
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>