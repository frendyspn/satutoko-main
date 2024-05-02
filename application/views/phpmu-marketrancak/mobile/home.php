<style>
.ps-form--search-mobile {
  border: 1px solid #cecece;
  background: #fff;
  border-radius: 10px;
  margin-right: 15px;
}

.ps-form--search-mobile input[type="search"] {
  border: none;
  background: transparent;
  margin: 0;
  padding: 0px 8px;
  font-size: 13px;
  color: inherit;
  border: 1px solid transparent;
  border-radius: inherit;
}
.ps-form--search-mobile input[type="search"]::placeholder {
  color: #8a8a8a;
}

.ps-form--search-mobile button[type="submit"] {
  text-indent: -999px;
  overflow: hidden;
  width: 40px;
  padding: 0;
  margin: 0;
  border: 1px solid transparent;
  border-radius: inherit;
  background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'%3E%3C/path%3E%3C/svg%3E") no-repeat center;
  cursor: pointer;
  opacity: 0.7;
}

.ps-form--search-mobile button[type="submit"]:hover {
  opacity: 1;
}
.ps-form--search-mobile button[type="submit"]:focus,
.ps-form--search-mobile input[type="search"]:focus {
  box-shadow: 0 0 3px 0 #1183d6;
  border-color: #1183d6;
  outline: none;
}

.header--mobile .navigation--mobile {
    padding: 20px 20px 10px 20px;
}
.ps-form--search-mobile input {
    height: 39px;
}
.ps-panel--sidebar .ps-panel__header {
    text-align: left;
}
.ps-panel--sidebar .ps-panel__header {
    background-color: #e3e3e3;
}
.ps-panel--sidebar .ps-panel__header h3 {
    color:#000;
}

.auth-form{
    margin-bottom: 10px !important;
    padding-bottom: 10px;
    border-bottom: 5px solid #f4f4f4;
}

.btn-auth{
    border: 1px solid #cecece;
    padding: 3px;
    font-size: 14px;
}
</style>
<?php 
$cek_keranjang = $this->db->query("SELECT a.*, b.*, c.nama_reseller FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where a.session='".$this->session->idp."' ORDER BY id_penjualan_detail ASC");
$total = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."'")->row_array();
$jmlpesan_unread = $this->model_reseller->pesanbelumbaca()->num_rows(); 
$jml_notifikasi = $this->model_utama->view_where('rb_notifikasi_send',array('id_konsumen'=>$this->session->id_konsumen,'dibaca'=>'N'))->num_rows(); 

if ($this->uri->segment('2')!='video'){ 
?>
<header class="header header--mobile" data-sticky="true"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="navigation--mobile">
        <div class="navigation__left">
        <form class="ps-form--search-mobile" action="<?php echo base_url() ?>produk" method="GET">
            <div class="form-group--nest" style='height:37px'>
                <button type='submit' style='padding:10px 10px !important'><i class="icon-magnifier"></i></button>
                <input class="form-control" name='s' type="search" placeholder="Cari di <?= config('title'); ?>..." autocomplete='off' required>
            </div>
        </form>
        </div>
        <div class="navigation__right">
            <div class="header__actions">
                <div class="ps-block--user-header">
                    <?php if ($this->session->level == 'konsumen') { ?>
                        <div class="ps-block__left">
                            <a class='header__extra ps-toggle--sidebar' href='#inbox'><i class='fa fa-envelope-o'></i> <?= ($jmlpesan_unread>'0'?'<span>'.$jmlpesan_unread.'</span>':'<span>0</span>'); ?></a>
                            <a class='header__extra ps-toggle--sidebar notifikasi_count2' href='#notifikasi'><i class='fa fa-bell-o'></i> <?= ($jml_notifikasi>'0'?'<span>'.$jml_notifikasi.'</span>':'<span>0</span>'); ?></a>
                            <a class='header__extra ps-toggle--sidebar' href='#cart-mobile'><i class='icon-bag2'></i><span><i class='show_cart_count'></i></span></a>
                        </div>
                        <div class="ps-block__right">
                            <a href="<?php echo base_url(); ?>members/profile">Akun</a> 
                            <a href="<?php echo base_url(); ?>auth/logout">Keluar</a>
                        </div>
                    <?php }else{ ?>
                        <div class="ps-block__left">
                            <a class='header__extra ps-toggle--sidebar' href='#inbox'><i class='fa fa-envelope-o'></i></a> 
                            <a class='header__extra ps-toggle--sidebar' href='#notifikasi'><i class='fa fa-bell-o'></i></a>
                            <a class='header__extra ps-toggle--sidebar' href='#cart-mobile'><i class='icon-bag2'></i></a>
                        </div>
                        <div class="ps-block__right">
                            <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg">Login</a> 
                            <a href="<?php echo base_url(); ?>auth/login">Register</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</header>
<?php } ?>
<div class="navigation--list">
    <div class="navigation__content">
        <a class="navigation__item" href="<?= base_url(); ?>"><i class="icon-home"></i><span> Home</span></a>
        <a class="navigation__item ps-toggle--sidebar" href="#menu-mobile"><i class="icon-list4"></i><span> Menu</span></a>
        <?php 
            $cek_reseller = $this->db->query("SELECT * FROM rb_reseller where id_reseller='0' AND hapus_toko='N'");
            if ($cek_reseller->num_rows()>=1){
                echo "<a class='navigation__item mall-item' href='".base_url()."u/mall'><i class='icon-store'></i><span> Mall</span></a>
                      <a class='navigation__item' href='".base_url()."produk/reseller'><i class='fa fa-list-alt'></i><span> Toko</span></a>";
            }else{
                echo "<a class='navigation__item mall-item' href='".base_url()."produk/reseller'><i class='icon-store'></i><span> Toko</span></a>
                        <a class='navigation__item' href='".base_url()."members/wishlist'><i class='icon-heart'></i><span> Whistlist</span></a>";
            }
        ?>
        
        
        <?php 
            if ($this->session->id_konsumen!=''){
                echo "<a class='navigation__item ps-toggle--sidebar' href='#search-sidebar'><i class='icon-user'></i><span> Akun</span></a>";
            }else{
                echo "<a class='navigation__item ps-toggle--sidebar' href='#' data-toggle='modal' data-target='.bd-example-modal-lg'><i class='icon-user'></i><span> Masuk</span></a>";
            }
        ?>
    </div>
</div>


<div class="ps-panel--sidebar" id="cart-mobile">
    <div class="ps-panel__header">
        <h3><a class="navigation__item ps-toggle--sidebar mr-3" href="#cart-mobile"><i class="fa fa-long-arrow-left"></i></a> Keranjang</h3>
    </div>
    <div class="navigation__content">
        <div class="ps-cart--mobile">
            <div class="ps-cart__content"><span class='m1keranjangx'>
                <?php 
                    $no = 1;
                    if ($cek_keranjang->num_rows() > 0) {
                        echo "<a style='padding:5px 10px; margin-bottom:10px' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='".base_url()."produk/keranjang'>Lihat Keranjang</a>";
                    }
                    foreach ($cek_keranjang->result_array() as $row){
                    $sub_total = (($row['harga_jual']-$row['diskon'])*$row['jumlah']);
                    $ex = explode(';', $row['gambar']);
                    if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                        echo "<div class='ps-product--cart-mobile'>
                            <div class='ps-product__thumbnail'><a href='".base_url()."produk/detail/$row[produk_seo]'><img src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a></div>
                            <div class='ps-product__content'>
                            
                            <a class='ps-product__remove remove-produk-cart' style='cursor:pointer' id='remove-$row[id_penjualan_detail]' onclick=\"removecart('$row[id_penjualan_detail]',this.id)\"><i class='icon-cross'></i></a>
                            
                            <a href='".base_url()."produk/detail/$row[produk_seo]'>$row[nama_produk]</a>
                            <p style='border-bottom:1px dotted #cecece'><b>Qty.</b> <small>$row[jumlah] x <b>" . rupiah($row['harga_jual'] - $row['diskon']) . "</b></small></p>
                            
                            </div>
                        </div>";
                    }

                    if ($cek_keranjang->num_rows() <= 0) {
                        echo "<center style='padding:10px 15px'>
                        <img style='width:90px' src='".base_url()."asset/images/shopping-empty.png'><hr>
                        <h4>Wah keranjang belanjaanmu kosong!</h4>
                        Daripada dianggurin, mending isi dengan barang-barang impianmu. Yuk, cek sekarang!<br>
                        <a style='padding:5px 10px; margin-top:10px' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='".base_url()."produk'>Mulai Belanja</a>
                        </center>";
                    }
                ?>
            </span></div>

        </div>
    </div>
</div>

<div class="ps-panel--sidebar" id="navigation-mobile">
    <div class="ps-panel__header">
        <h3><a class="navigation__item ps-toggle--sidebar mr-3" href="#navigation-mobile"><i class="fa fa-long-arrow-left"></i></a> Kategori Produk</h3>
    </div>
    <div class="ps-panel__content">
        <ul class="menu--mobile">
            <?php 
                $kategori = $this->model_app->view('rb_kategori_produk');
                foreach ($kategori->result_array() as $rows) {
                    $sub_kategori = $this->db->query("SELECT * FROM rb_kategori_produk_sub where id_kategori_produk='$rows[id_kategori_produk]'");
                    if ($sub_kategori->num_rows()>=1){
                        echo "<li class='current-menu-item menu-item-has-children has-mega-menu'><a href='".base_url()."produk/kategori/$rows[kategori_seo]'>$rows[nama_kategori]</a> <span class='sub-toggle'></span> ";
                    }else{
                        echo "<li class='current-menu-item '><a href='".base_url()."produk/kategori/$rows[kategori_seo]'>$rows[nama_kategori]</a></li>";
                    }

                    if ($sub_kategori->num_rows()>=1){
                        echo "<div class='mega-menu'>
                            <div class='mega-menu__column'>
                                <ul class='menu-custom'>";
                                echo main_menuxxx($rows['id_kategori_produk']);
                            // foreach ($sub_kategori->result_array() as $row) {
                            //     echo "<li class='current-menu-item '><a href='".base_url()."produk/subkategori/$row[kategori_seo_sub]'>$row[nama_kategori_sub]</a></li>";
                            // }
                        echo "</ul></div></div>";
                    }
                    echo "</li>";
                }
            ?>
        </ul>
    </div>
</div>

<?php 
if ($this->session->id_konsumen!=''){ 
$row = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
if (trim($row['foto'])=='' OR !file_exists("asset/foto_user/".$row['foto'])){
    $foto = base_url()."asset/foto_user/blank.png";
}else{
    $foto = base_url()."asset/foto_user/$row[foto]";
}
?>
<div class="ps-panel--sidebar" id="search-sidebar">
    <div class="ps-panel__header">
        <h3><a class="navigation__item ps-toggle--sidebar mr-3" href="#search-sidebar"><i class="fa fa-long-arrow-left"></i></a> Menu Utama</h3>
    </div>

    <div class="navigation__content">
        <div class="card card-widget widget-user" style='box-shadow:none; border:none'>
            <div class="text-white m-4 mb-0">
                <img class="rounded-circle float-left mr-4" style='width:70px; border:1px solid #e3e3e3' src="<?= $foto; ?>" alt="User Avatar">
                <p class="m-0 font-weight-bold" style='color:#000; font-size: 1.6rem;'><?= $row['nama_lengkap']; ?></p>
                <p class="m-0"><span class='fa fa-square text-info m-1 font-weight-bold'></span> Rp <?= rupiah(saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen)); ?></p>
                <p class="m-0"> <?= premium($this->session->id_konsumen); ?></p>
                
            </div>
        </div>
        <div class="btn-group btn-block" style='border-bottom:1px dotted #e3e3e3' role="group">
            <a class="btn btn-default font-weight-bold rounded-0 font-weight-bold" style='font-size:14px; padding: 10px 20px; border:1px solid #e3e3e3; border-bottom:0px' href="<?= base_url(); ?>members/edit_profile">Setting Account</a>
            <a class="btn btn-default font-weight-bold rounded-0 font-weight-bold" style='font-size:14px; padding: 10px 20px; border:1px solid #e3e3e3; border-bottom:0px' href="<?= base_url(); ?>members/tambah_withdraw">Deposit & Withdraw</a>
        </div>
    </div>

    <div class="ps-panel__content">
        <ul class="menu--mobile">
            <?php 
                $data = array('<i class="icon-user"></i> Profile','<i class="icon-couch"></i> Sosmed','<i class="icon-bag-dollar"></i> Data Bank','<i class="fa fa-money"></i> Keuangan','<i class="icon-heart"></i> Wishlist','<i class="icon-bag2"></i> Pembelian','<i class="icon-phone"></i> PPOB','<i class="icon-car"></i> Jadi Kurir '.$pesanan_sopir.'');
                $link = array('profile','sosial_media','rekening_bank','withdraw','wishlist','orders_report','trx_pulsa','sopir');
                for ($i=0; $i < count($data); $i++) { 
                    echo "<li class='current-menu-item'><a href='".base_url()."members/".$link[$i]."'>".$data[$i]."</a></li>";
                }
            echo "<li class='current-menu-item'><a href='" . base_url() . "komplain?s=pelapor'><i class='fa fa-warning'></i> Komplain <span class='badge badge-secondary' style='font-size:85%; background-color: #cecece; color:#000'>".$komplain_beli->num_rows()."</span></a></li>
                    <li class='current-menu-item'><a style='color:red' href='".base_url()."auth/logout'><i class='icon-exit'></i> Keluar</a></li>";
            ?>
        </ul>
    </div>
</div>
<?php } ?>

<div class="ps-panel--sidebar" id="menu-mobile">
    <div class="ps-panel__header">
        <h3><a class="navigation__item ps-toggle--sidebar mr-3" href="#menu-mobile"><i class="fa fa-long-arrow-left"></i></a> Menu Utama</h3>
    </div>
    <div class="ps-panel__content">
        <?php if ($this->session->id_konsumen==''){
            echo "<div class='row container m-0 auth-form'>
                    <div class='col-6'><a class='btn btn-block btn-success btn-auth' href='#' data-toggle='modal' data-target='.bd-example-modal-lg'>Masuk</a></div>
                    <div class='col-6'><a class='btn btn-block btn-default btn-auth' href='".base_url()."auth/login'>Daftar</a></div>
                </div>";
        }

        function main_menu1() {
            $ci = & get_instance();
            $query = $ci->db->query("SELECT id_menu, nama_menu, link, id_parent FROM menu where aktif='Ya' AND position='Bottom' order by urutan");
            $menu = array('items' => array(),'parents' => array());
            foreach ($query->result() as $menus) {
                $menu['items'][$menus->id_menu] = $menus;
                $menu['parents'][$menus->id_parent][] = $menus->id_menu;
            }
            if ($menu) {
                $result = build_main_menu1(0, $menu);
                return $result;
            }else{
                return FALSE;
            }
        }

        function build_main_menu1($parent, $menu) {
            $ci = & get_instance();
            $html = "";
            if (isset($menu['parents'][$parent])) {
                if ($parent=='0'){
                    $html .= "<ul class='menu--mobile'>";
                }else{
                    $html .= "<ul class='sub-menu'>";
                }
                foreach ($menu['parents'][$parent] as $itemId) {
                    if (!isset($menu['parents'][$itemId])) {
                        if(preg_match("/^http/", $menu['items'][$itemId]->link)) {
                            $html .= "<li class='current-menu-item'><a target='_BLANK' href='".$menu['items'][$itemId]->link."'><span class='icon-chevron-right'></span> ".$menu['items'][$itemId]->nama_menu."</a></li>";
                        }else{
                            $html .= "<li class='current-menu-item'><a href='".base_url().''.$menu['items'][$itemId]->link."'><span class='icon-chevron-right'></span> ".$menu['items'][$itemId]->nama_menu."</a></li>";
                        }
                    }
                    if (isset($menu['parents'][$itemId])) {
                        if(preg_match("/^http/", $menu['items'][$itemId]->link)) {
                            $html .= "<li class='menu-item-has-children has-mega-menu'><a target='_BLANK' href='".$menu['items'][$itemId]->link."'><span class='icon-chevron-right'></span> ".$menu['items'][$itemId]->nama_menu."</a> <span class='sub-toggle'></span>";
                        }else{
                            $html .= "<li class='menu-item-has-children'><a href='".base_url().''.$menu['items'][$itemId]->link."'><span class='icon-chevron-right'></span> ".$menu['items'][$itemId]->nama_menu."</a> <span class='sub-toggle'></span>";
                        }
                        $html .= build_main_menu1($itemId, $menu);
                        $html .= "</li>";
                    }
                }

                if ($parent=='0'){
                    if ($ci->session->level=='konsumen'){ 
                        if (reseller($ci->session->id_konsumen)!=''){
                            $komplain_toko = $ci->db->query("SELECT * FROM rb_pusat_bantuan where id_terlapor='".$ci->session->id_konsumen."' AND putusan='proses'")->num_rows(); 
                            $html .= "<li class='menu-item-has-children'><a href='#'><span class='icon-chevron-right'></span> Seller Center <span class='badge badge-secondary' style='font-size:85%; background-color: #cecece; color:#000'>".(order_masuk(reseller($ci->session->id_konsumen))+$komplain_toko)."</span></a> <span class='sub-toggle'></span>
                                    <ul class='sub-menu'>
                                        <li class='current-menu-item'><a href='".base_url()."members/profil_toko'><span class='icon-chevron-right'></span> Dashboard</a></li>
                                        <li class='current-menu-item'><a href='".base_url()."members/operasional'><span class='icon-chevron-right'></span> Operasional</a></li>
                                        <li class='current-menu-item'><a href='".base_url()."members/produk'><span class='icon-chevron-right'></span> Produk</a></li>
                                        <li class='current-menu-item'><a href='".base_url()."members/video'><span class='icon-chevron-right'></span> Video Produk</a></li>
                                        <li class='current-menu-item'><a href='".base_url()."members/alamat_cod'><span class='icon-chevron-right'></span> Kurir Toko</a></li>";
                                        
                                        if (config('reseller')=='Y'){ $html .= "<li class='current-menu-item'><a href='".base_url()."members/pembelian'><span class='icon-chevron-right'></span> Jadi Reseller</a></li>"; }
                                        
                            $html .= "<li class='current-menu-item'><a href='".base_url()."members/pembelian'><span class='icon-chevron-right'></span> Komplain <span class='badge badge-secondary' style='font-size:85%; background-color: #cecece; color:#000'>$komplain_toko</span></a></li>
                                        <li class='current-menu-item'><a href='".base_url()."members/penjualan'><span class='icon-chevron-right'></span> Orders <span class='badge badge-secondary' style='font-size:85%; background-color: #cecece; color:#000'>".order_masuk(reseller($ci->session->id_konsumen))."</span></a></li>
                                        <li class='current-menu-item'><a href='".base_url()."members/upgrade'><span class='icon-chevron-right'></span> <span class='blink_me'>Upgrade</span></a></li>
                                    </ul>
                            </li>";
                        }else{
                            $html .= "<li class='current-menu-item'><a href='".base_url()."members/buat_toko'><span class='icon-chevron-right'></span> Buat Toko</a></li>";
                        }
                    } 
                }

                if ($parent=='0'){
                    if ($ci->session->id_konsumen!=''){
                        $jmlpesan_unread = $ci->model_reseller->pesanbelumbaca()->num_rows(); 
                        $html .= "<li class='current-menu-item'><a href='".base_url()."members/messages'><span class='icon-chevron-right'></span> Inbox <span class='badge badge-secondary'>$jmlpesan_unread</span></a></li>";
                        $html .= "<li class='current-menu-item'><a href='".base_url()."auth/logout'><span class='icon-chevron-right'></span> Keluar</a></li>";
                    }
                }
                
                $html .= "</ul>";
            }
            return $html;
        }
        echo main_menu1();
    ?>

    </div>
</div>

<div class="ps-panel--sidebar" id="inbox">
    <div class="ps-panel__header">
        <h3><a class="navigation__item ps-toggle--sidebar mr-3" href="#inbox"><i class="fa fa-long-arrow-left"></i></a> Inbox</h3>
    </div>
    <div class="ps-panel__content">
        <?php 
        echo "<ul class='menu-message' style='min-height:333px; border:1px solid #e3e3e3; padding:10px'>";
        $recordx = $this->model_reseller->tampilmessageshome(10,0);
        foreach ($recordx->result() as $r) {
        $mgs = $this->model_reseller->tampilmessagescontenthome($r->id_konsumen)->row();
            if ($mgs->stat == '1'){ $bg = '#dcf9e4'; }else{ $bg = ''; }
        if (trim($mgs->message) == '' AND $mgs->file_upload != ''){ 
            $message = '<i class="fa fa-link fa-fw"></i> Melampirkan Sebuah File,..'; 
        }else{ 
            if (strlen($mgs->message) > 30){ $message = strip_tags(substr($mgs->message,0,30)).',..';  }else{ $message = strip_tags($mgs->message); }
        }

        $tglex = cek_terakhir($mgs->date_time);
        if ($r->foto==''){
            $foto_members = 'blank.png';
        }else{
            if (file_exists("asset/foto_user/".$r->foto)){ $foto_members = $r->foto; }else{ $foto_members = 'blank.png'; }
        }
        $email_gravatar = md5(strtolower(trim($r->email))); 
        echo "<li style='background:$bg; border-bottom:2px dotted #cecece; margin:0px; padding-top:5px'>
            <a href='".base_url()."members/read/".$r->id_konsumen."/0'>
                <div class='float-left'>
                    <img style='width:40px; height:40px; margin-right:10px' src='".base_url()."asset/foto_user/$foto_members' class='rounded-circle' alt='User Image'>
                </div>
                <span class='font-weight-bold text-success'> ".$r->nama_lengkap."</span> 
                <small class='float-right' style='color:#b7b7b7'><i class='fa fa-clock-o'></i> $tglex</small>
                <p style='overflow:hidden; text-overflow:ellipsis'>";
                if ($mgs->file_upload != ''){ 
                    echo "<i style='color:red' class='fa fa-files-o fa-fw'></i> ";
                }
                echo strip_tags($message)."</p>
            </a>
            </li>";
        } 

        if ($recordx->num_rows()<=0){
            echo "<center style='margin:50px 0px'>Maaf, tidak ada Data...</center>";
        }
        echo "</ul>";
        ?>
    </div>
</div>

<div class="ps-panel--sidebar" id="notifikasi">
    <div class="ps-panel__header">
        <h3><a class="navigation__item ps-toggle--sidebar mr-3" href="#notifikasi"><i class="fa fa-long-arrow-left"></i></a> Notifikasi</h3>
    </div>
    <div class="ps-panel__content" style='padding-top:0px'>

    <div class="btn-group btn-block" style='border-bottom:1px dotted #e3e3e3' role="group">
        <button class="btn btn-default text-success rounded-0 font-weight-bold" onclick="dibaca_all(<?= $this->session->id_konsumen; ?>)" style='font-size:12px; padding: 10px 20px; border-right:1px solid #e3e3e3'>Tandai Semua dibaca</button>
        <a class="btn btn-default text-success rounded-0 font-weight-bold" style='font-size:12px; padding: 10px 20px' href="<?= base_url(); ?>members/notifikasi">Lihat Selengkapnya</a>
    </div>
    <div style='clear:both'><br></div>
        <dl class='notifikasi_all'>
        <?php 
            $notifikasi = $this->db->query("SELECT * FROM rb_notifikasi_send a JOIN rb_notifikasi b ON a.id_notifikasi=b.id_notifikasi where a.id_konsumen='".$this->session->id_konsumen."' ORDER BY a.id_notifikasi_send DESC LIMIT 10");
            foreach ($notifikasi->result_array() as $row) {
                if (strlen($row['konten']) > 65){ $konten = strip_tags(substr($row['konten'],0,65)).',..';  }else{ $konten = strip_tags($row['konten']); }
                echo "<div class='m-0 notifikasi-$row[id_notifikasi_send]'>
                    <div style='padding:10px 20px; background:".($row['dibaca']=='N'?'#dcf9e4':'#ffffff')."'><a href='$row[url]'>
                        <dt> $row[judul]</dt>
                        <dd style='border-bottom:2px dotted #cecece; margin:0px; padding:5px 0px 10px 0px'>
                        <small class='text-success font-weight-bold'><i class='fa fa-clock-o'></i> ".cek_terakhir($row['waktu_kirim'])." lalu</small>
                        <input class='float-right' type='checkbox' id='$row[id_notifikasi_send]' onclick=\"dibaca('Y',this.id)\" ".($row['dibaca']=='N'?'':'checked disabled').">
                        <p>$konten</p></dd>
                    </a>
                    </div>
                    </div>";
            }

            if ($notifikasi->num_rows()<=0){
                echo "<center style='padding:50px 0px'>Tidak ada data</center>";
            }
        ?>
        </dl>
    </div>
</div>
