<div class="menu__toggle"><i class="icon-menu"></i><span>Kategori</span></div>
<div class="menu__content">
    <ul class="menu--dropdown">
        <?php 
            $kategori = $this->model_app->view_ordering('rb_kategori_produk','nama_kategori','ASC');
            foreach ($kategori as $rows) {
                if ($rows['icon_kode']!=''){
                    $icon = "<i class='$rows[icon_kode]'></i>";
                }elseif ($rows['icon_image']!=''){
                    $icon = "<img style='width:18px; height:18px; margin-right:10px' src='".base_url()."asset/foto_produk/kategori/$rows[icon_image]'>";
                }else{
                    $icon = "";
                }
                $sub_kategori = $this->db->query("SELECT * FROM rb_kategori_produk_sub where id_kategori_produk='$rows[id_kategori_produk]' ORDER BY nama_kategori_sub ASC");
                if ($sub_kategori->num_rows()>=1){
                    echo "<li class='current-menu-item menu-item-has-children has-mega-menu'><a href='".base_url()."produk/kategori/$rows[kategori_seo]'> $icon $rows[nama_kategori] <span class='caret caret-right'></span></a>
                        <div class='mega-menu'>";
                        
                            echo "<div class='mega-menu__column'>";
                            echo main_menux($rows['id_kategori_produk']);
                            echo "</div>";
                        
                        echo "</div>
                    </li>";
                }else{
                    echo "<li class='current-menu-item'><a href='".base_url()."produk/kategori/$rows[kategori_seo]'> $icon $rows[nama_kategori]</a></li>";
                }
            }
        ?>
    </ul>
</div>