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
  padding: 10px 20px;
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

.btn-chat{
    flex-basis: 25%; padding: 5px 10px; margin-top:0px; background: #fff; color: #000 !important; border-bottom: 1px solid #cecece; border-right: 2px dashed #cecece;
}

.add-to-cart{
    font-weight:400; flex-basis: 55%; padding: 10px 10px 5px 10px; color:#000 !important; background-color:#fff; margin-top:0px
}

.add-to-cart-empty{
    font-weight:400; flex-basis: 55%; padding: 10px 10px 5px 10px; color:red !important; background-color:#fff; margin-top:0px
}

.belimobile{
    font-weight:400; flex-basis: 55%; padding: 5px 10px; margin-top:0px; font-size: 16px !important;
}
</style>

<header class="header header--mobile header--mobile-product" data-sticky="true"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="navigation--mobile">
        <div class="navigation__left"><a class="header__back" href="#" onclick="window.history.go(-1); return false;"><i class="icon-chevron-left"></i><strong>Kembali</strong></a></div>
        <div class="navigation__right">
        <div class="header__actions">
                <?php 
                $cek_keranjang = $this->db->query("SELECT a.*, b.*, c.nama_reseller FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where a.session='".$this->session->idp."' ORDER BY id_penjualan_detail ASC");
                $total = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."'")->row_array();
                $jmlpesan_unread = $this->model_reseller->pesanbelumbaca()->num_rows(); 
                $jml_notifikasi = $this->model_utama->view_where('rb_notifikasi_send',array('id_konsumen'=>$this->session->id_konsumen,'dibaca'=>'N'))->num_rows(); 
                ?>

                <div class="ps-block--user-header">
                    <?php if ($this->session->level == 'konsumen') { ?>
                        <div class="ps-block__left">
                            <a class='header__extra ps-toggle--sidebar' href='#inbox'><i class='fa fa-envelope-o'></i> <?= ($jmlpesan_unread>'0'?'<span>'.$jmlpesan_unread.'</span>':'<span>0</span>'); ?></a>
                            <a class='header__extra ps-toggle--sidebar notifikasi_count2' href='#notifikasi'><i class='fa fa-bell-o'></i> <?= ($jml_notifikasi>'0'?'<span>'.$jml_notifikasi.'</span>':'<span>0</span>'); ?></a>
                            <a class='header__extra ps-toggle--sidebar' href='#cart-mobile'><i class='icon-bag2'></i><span><i class='show_cart_count'></i></span></a>
                        </div>
                        <div class="ps-block__right">
                            <a href="<?php echo base_url(); ?>members/profile">Akun</a> 
                            <a href="<?php echo base_url(); ?>auth/logout">Logout</a>
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

    <nav class="navigation--mobile-product">
        <input class="form-control" type="hidden" value='1' name='qty'>
        <?php 
            if ($record['pre_order']!='' AND $record['pre_order']>0){
                $tombol_beli = "PreOrder";
            }else{
                $tombol_beli = "Beli Sekarang";
            } 

            echo "<a target='_BLANK' class='ps-btn ps-black btn-block rounded-0 btn-chat' data-toggle='modal' data-target='#myChat'><span class='icon-bubbles' style='font-size:22px'></span></a>";

            if (stok($record['id_reseller'],$record['id_produk'])<=0){ 
                echo "<a class='ps-btn ps-black rounded-0 add-to-cart-empty'><span class='fa fa-plus'></span> Keranjang</a>";
            }else{
                echo "<a id='$record[id_produk]' class='ps-btn ps-black rounded-0 add-to-cart'><span class='fa fa-plus'></span> Keranjang</a>";
            }
            echo "<a target='_BLANK' style='background-color: #05D0A4; align-items: center; display: grid;' class='ps-btn rounded-0' href='https://admin.1toko.id/jualin/". $record['produk_seo'] ."'> Jualin</a>";
            echo "<button name='beli' class='ps-btn btn-block rounded-0 belimobile'>$tombol_beli</button>";
        ?>
    </nav>



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
<script>
$(document).ready(function(){
    $('.belimobile').on('click',function(){
        $('#form1').submit();
    });
});
</script>
                        