
<style>
    @media (max-width: 576px) {
        .navigation__right i, .navigation__right a{
            color:#fff !important;
        }

        .dikirim{
            color:#fff;
            margin-top: -10px;
        }
        .kontent-kategori{
            padding: 10px 15px 0px 15px;
            position: relative;
            margin: 0 auto;
            background: #fff;
            border-radius: 20px 20px 0px 0px;
        }
        .kontent-kategori2{
            padding: 10px 15px 0px 15px;
            position: relative;
            margin: 0 auto;
            background: #fff;
        }
        .kontent-kategori .owl-slider, .kontent-kategori2 .owl-slider{
            margin-bottom: 0rem; padding-bottom: 0px; 
        }
        .header--mobile .header__extra span{
            background:#ff2e2e;
        }

        #homepage-1 .ps-home-banner .ps-container .ps-section__left {
            margin-bottom: 0px !Important;
        }

        .ps-product-list .owl-carousel .owl-item {
            padding: 0px 5px;
        }
        .ps-product-list .ps-product{
            border-radius:10px;
        }
        .ps-product-list .ps-product .ps-product__container {
            padding: 10px;
        }

        .ps-product-list .ps-product .ps-product__title {
            height: 40px;
        }

        .account-box{
            padding: 10px 15px; position: relative; margin: 0 auto;
        }
        .account-box .box{
            margin: 0px; background: #ffffff; border: 1px solid #cecece; border-radius: 10px; padding: 15px 5px;
        }
        .ps-collection {
            margin-bottom: 0px;
        }
        .ps-carousel--nav {
            margin-bottom: 0rem;
            padding-bottom: 10px;
        }
        .ps-form--subscribe-popup {
            padding: 60px 30px;
        }

        .ps-home-ads .ps-collection img {
            border: 3px solid #fff;
        }
    }

</style>

<div id="homepage-1">
    <div class="ps-home-banner ps-home-banner--1">
        <div class="ps-container container1" style='margin-top:30px'>
            
            <?php 
                $rowp = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array(); 
                if ($this->session->id_konsumen!=''){
                    echo "<div class='d-block d-sm-none account-box'>
                    <p class='dikirim'><i class='icon-map-marker'></i> Dikirim ke <a href='".base_url()."/members/profile'><b>$rowp[nama_lengkap]</b></a></p>
                    <div class='row box'>
                        <div class='col-6'>
                        <i class='fa fa-money text-success'></i>
                            Saldo<hr style='padding:0px; margin:0px'>
                            <span class='font-weight-bold'>Rp ".rupiah(saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen))."</span>
                        </div>
                        <div class='col-6'>
                        <i class='icon-star text-success'></i>
                            Status<hr style='padding:0px; margin:0px'>
                            <span class='font-weight-bold'>".status_akun($this->session->id_konsumen)."</span>
                        </div>
                    </div>
                    </div>";
                }else{ 
                    echo "<div class='d-block d-sm-none account-box row'>
                        <p class='dikirim' style='margin-bottom:0px'><i class='icon-map-marker'></i> Dikirim ke : <a href='".base_url()."/auth/login'><b>Pilih Pengiriman</b></a></p>
                        <div style='padding:0' class='ml-0 mt-3 mb-3 float-left dikirim col-8'>
                            <div class='mr-3 float-left'><img style='margin-top:-3px' src='https://members.phpmu.com/asset/members/no-image.jpg' width='35px' class='rounded-circle'></div>
                            <div style='line-height:15px'>
                                <div class='title font-weight-bold'>Hai, Pengunjung!</div>
                                <small>Akses semua fitur, yuk~</small>
                            </div>
                        </div>
                        <div style='padding:0' class='float-right mt-3 col-4 mr-0'><a class='btn btn-block btn-default btn-auth' style='background-color:#f8f8f8'  href='#' data-toggle='modal' data-target='.bd-example-modal-lg'>Masuk</a></div>
                    </div>";
                }
            ?>

            <div class='d-block d-sm-none kontent-kategori'>
                <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="false" data-owl-item="6" data-owl-item-xs="5" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                <?php    
                    $i = 1;
                    $x=1;
                    $top_kategori1 = $this->db->query("SELECT * FROM (SELECT a.*, b.jumlah FROM
                    (SELECT * FROM rb_kategori_produk) as a LEFT JOIN
                    (SELECT z.id_kategori_produk, COUNT(*) jumlah FROM rb_penjualan_detail y JOIN rb_produk z ON y.id_produk=z.id_produk GROUP BY z.id_kategori_produk HAVING COUNT(z.id_kategori_produk)) as b on a.id_kategori_produk=b.id_kategori_produk) as x ORDER BY x.jumlah DESC LIMIT 0,11");
                    foreach($top_kategori1->result_array() as $row){
                        if ($row['icon_kode']!=''){
                            $icon = "<center><i style='font-size:30px' class='$row[icon_kode]'></i></center>";
                        }elseif ($row['icon_image']!=''){
                            $icon = "<center><img style='width:35px; height:35px; margin-bottom:3px' src='".base_url()."asset/foto_produk/kategori/$row[icon_image]'></center>";

                        }else{
                            $icon = "";
                        }
                        
                        echo "<div class='ps-product ps-product--inner' style='margin-bottom:0px;'>";
                        echo "<div class='col4'>
                                <a style='margin-top:15px; height:80%' class='ps-block__overlay' href='".base_url()."produk/kategori/$row[kategori_seo]'>
                                    $icon <p style='text-align: center; font-size:11px; line-height:1.1em; color:#000; height:25px; overflow:hidden;'>$row[nama_kategori]</p>
                                </a>
                        </div>";
                        echo "</div>"; $x++; 

                        $i++;
                    }
                    echo "</div>";
                ?>
            </div>

            <?php
            $slide1 = $this->db->query("SELECT * FROM slide where posisi='desktop' ORDER BY id_slide ASC");
            if ($slide1->num_rows()>1){
            echo "<div class='ps-section__left d-none d-sm-block' style='padding-right:10px;'>
                <div class='ps-carousel--nav-inside owl-slider' data-owl-auto='true' data-owl-loop='true' data-owl-speed='5000' data-owl-gap='0' data-owl-nav='true' data-owl-dots='true' data-owl-item='1' data-owl-item-xs='1' data-owl-item-sm='1' data-owl-item-md='1' data-owl-item-lg='1' data-owl-duration='1000' data-owl-mousedrag='on'>";
                    foreach ($slide1->result_array() as $row) {
                        if ($row['gambar'] ==''){ $foto_slide = base_url()."asset/foto_berita/no-image.jpg"; }else{ $foto_slide = base_url()."asset/foto_slide/$row[gambar]"; }
                        $judul = explode('||',$row['keterangan']);
                        echo "<div class='ps-banner'><a target='_BLANK' title='$judul[1]' href='$judul[0]'><img class='preview' loading='lazy' src='$foto_slide' alt='$row[gambar]'></a></div>"; 
                        $no++;
                    }
                echo "</div>
            </div>";
            }
            
            $slide2 = $this->db->query("SELECT * FROM slide where posisi='mobile' ORDER BY id_slide ASC");
            if ($slide2->num_rows()>1){
            echo "<div class='ps-section__left d-block d-sm-none' style='padding:10px 10px 0px 0px; background:#fff; height:80px; overflow:hidden'>
                <div class='ps-carousel--nav-inside owl-slider' data-owl-auto='true' data-owl-loop='true' data-owl-speed='5000' data-owl-gap='0' data-owl-nav='true' data-owl-dots='true' data-owl-item='1' data-owl-item-xs='1' data-owl-item-sm='1' data-owl-item-md='1' data-owl-item-lg='1' data-owl-duration='1000' data-owl-mousedrag='on'>";
                    foreach ($slide2->result_array() as $row) {
                        if ($row['gambar'] ==''){ $foto_slide = base_url()."asset/foto_berita/no-image.jpg"; }else{ $foto_slide = base_url()."asset/foto_slide/$row[gambar]"; }
                        $judul = explode('||',$row['keterangan']);
                        echo "<div class='ps-banner'><a target='_BLANK' title='$judul[1]' href='$judul[0]'><img style='padding: 0px 15px; background:#fff' class='preview' loading='lazy' src='$foto_slide' alt='$row[gambar]'></a></div>"; 
                        $no++;
                    }
                echo "</div>
            </div>";
            }
            ?>

            <div class='d-block d-sm-none kontent-kategori2'>
                <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="false" data-owl-item="6" data-owl-item-xs="5" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                <?php    
                    $i = 1;
                    $x=1;
                    $top_kategori1 = $this->db->query("SELECT * FROM (SELECT a.*, b.jumlah FROM
                    (SELECT * FROM rb_kategori_produk) as a LEFT JOIN
                    (SELECT z.id_kategori_produk, COUNT(*) jumlah FROM rb_penjualan_detail y JOIN rb_produk z ON y.id_produk=z.id_produk GROUP BY z.id_kategori_produk HAVING COUNT(z.id_kategori_produk)) as b on a.id_kategori_produk=b.id_kategori_produk) as x ORDER BY x.jumlah DESC LIMIT 11,11");
                    foreach($top_kategori1->result_array() as $row){
                        if ($row['icon_kode']!=''){
                            $icon = "<center><i style='font-size:30px' class='$row[icon_kode]'></i></center>";
                        }elseif ($row['icon_image']!=''){
                            $icon = "<center><img style='width:35px; height:35px; margin-bottom:3px' src='".base_url()."asset/foto_produk/kategori/$row[icon_image]'></center>";

                        }else{
                            $icon = "";
                        }
                        
                            echo "<div class='ps-product ps-product--inner' style='margin-bottom:0px'>";
                                echo "<div class='col4'>
                                    <a style='margin-top:15px; height:80%' class='ps-block__overlay' href='".base_url()."produk/kategori/$row[kategori_seo]'>
                                        $icon <p style='text-align: center; font-size:11px; line-height:1.1em; color:#000; height:25px; overflow:hidden;'>$row[nama_kategori]</p>
                                    </a>
                                    </div>";
                            echo "</div>"; $x++; 

                        $i++;
                    }
                    echo "</div>";
                ?>
            </div>


            <div class="ps-section__right d-none d-lg-block">
            <?php
            $noads = 1;
            $pasangiklan2 = $this->model_utama->view_ordering_limit('pasangiklan','id_pasangiklan','ASC',0,2);
            foreach ($pasangiklan2->result_array() as $b) {
                if ($noads=='1'){ $radius = "border-radius: 0px 20px 0px 0px;"; }else{ $radius = "border-radius: 0px 0px 20px 0px;"; }
                $string = $b['gambar'];
                if ($b['gambar'] != ''){
                    if(preg_match("/swf\z/i", $string)) {
                        echo "<embed class='ps-collection preview' loading='lazy' src='".base_url()."asset/foto_pasangiklan/$b[gambar]' quality='high' type='application/x-shockwave-flash'>";
                    } else {
                        echo "<a class='ps-collection' href='$b[url]' target='_blank'><img style='$radius' class='preview' loading='lazy' src='".base_url()."asset/foto_pasangiklan/$b[gambar]' alt='$b[judul]' /></a>";
                    }
                }
                $noads++;
            }
            ?>
            </div>
        </div>
    </div>

    <?php if (!$this->agent->is_mobile()){ ?>
    <div class="ps-container">
        <div class='row'>
            <div class='d-none d-sm-block col-12 col-md-12'>
            <h4>Kategori Pilihan</h4>
            <div class="ps-section__content xxx" style='border:1px solid #cecece; padding:2px 8px 5px 8px'>
            <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true" data-owl-item="6" data-owl-item-xs="5" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
        <?php    
            $i = 1;
            $x=1;
            echo "<div class='ps-product ps-product--inner' style='margin-bottom:0px'>";
            $top_kategori = $this->db->query("SELECT * FROM (SELECT a.*, b.jumlah FROM
            (SELECT * FROM rb_kategori_produk) as a LEFT JOIN
            (SELECT z.id_kategori_produk, COUNT(*) jumlah FROM rb_penjualan_detail y JOIN rb_produk z ON y.id_produk=z.id_produk GROUP BY z.id_kategori_produk HAVING COUNT(z.id_kategori_produk)) as b on a.id_kategori_produk=b.id_kategori_produk) as x ORDER BY x.jumlah DESC LIMIT 30");
            foreach($top_kategori->result_array() as $row){
                if ($row['icon_kode']!=''){
                    $icon = "<i style='font-size:36px' class='$row[icon_kode]'></i>";
                }elseif ($row['icon_image']!=''){
                    $icon = "<center><img style='width:55px; height:55px; margin-bottom:3px' src='".base_url()."asset/foto_produk/kategori/$row[icon_image]'></center>";

                }else{
                    $icon = "";
                }
                
                if ($x%2==0){ echo "<div class='ps-product ps-product--inner' style='margin-bottom:0px'>"; $x++; }
                    echo "<div class='col4'>
                        <a style='margin-top:15px; height:80%' class='ps-block__overlay' href='".base_url()."produk/kategori/$row[kategori_seo]'>
                            $icon <p style='font-size:13px; line-height:1.1em; color:#000; height:30px; overflow:hidden;'>$row[nama_kategori]</p>
                        </a>
                        </div>";
                if ($i%2==0){ echo "</div>"; $x++; }

                $i++;
            }
            echo "</div>
                <a style='margin-top:20px' class='ps-toggle--sidebar btn-custom d-block d-sm-none' href='#navigation-mobile'><i class='icon-list4'></i><span> Tampilkan Semua Kategori</span></a></center><br>
            </div>
            </div>";
        ?>
        </div>
    </div>
    <div style='clear:both'></div>
    <?php } ?>
    
    <div class="ps-home-ads" style='background:#fff; padding-top:20px'>
        <div class="ps-container">
        <h4>Spesial di Hari ini</h4>
            <div class="row no-gutters">
                <?php
                $iklantengah = $this->db->query("SELECT * FROM iklantengah where judul like 'home%'");
                foreach ($iklantengah->result_array() as $b) {
                    $string = $b['gambar'];
                    if ($b['gambar'] != ''){
                        if(preg_match("/swf\z/i", $string)) {
                            echo "<div class='col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6' style='border:3px solid #fff'><embed loading='lazy' class='ps-collection preview' src='".base_url()."asset/foto_iklantengah/$b[gambar]' quality='high' type='application/x-shockwave-flash'></div>";
                        } else {
                            echo "<div class='col-xl-3 col-lg-3 col-md-6 col-sm-6 col-6' style='border:3px solid #fff'><a loading='lazy' class='ps-collection preview' href='$b[url]' target='_blank'><img class='preview' loading='lazy' src='".base_url()."asset/foto_iklantengah/$b[gambar]' alt='$b[judul]' /></a></div>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php 
        $produk = $this->model_reseller->produk_flashdeal(0,0,10);
        if($produk->num_rows()>=1){
        // Aktifkan Flash Deal Tiap Hari
        $idn = $this->db->query("SELECT flash_deal FROM identitas where id_identitas='1'")->row_array();
        $kini = new DateTime('now');  
        $besok = date('Y-m-d', strtotime('+1 days'));
        $kemarin = new DateTime($besok.' 00:00:00');
        $tanggal = $kemarin->diff($kini)->format('%a:%h:%i:%s'); 
        $date1 = date('Y-m-d');
        $date2 = $besok;
        if(selisih_waktu_run($date1,$date2)>='1'){

        // $idn = $this->db->query("SELECT flash_deal FROM identitas where id_identitas='1'")->row_array();
        // $kini = new DateTime('now');  
        // $kemarin = new DateTime($idn['flash_deal'].' 00:00:00');
        // $tanggal = $kemarin->diff($kini)->format('%a:%h:%i:%s'); 

        // $date1 = date('Y-m-d');
        // $date2 = $idn['flash_deal'];
        // if(selisih_waktu_run($date1,$date2)>='1'){
    ?>
    <div class="ps-deal-of-day">
        <div class="ps-container flashdeal">
            <div class="ps-section__header">
                <div class="ps-block--countdown-deal">
                    <div class="ps-block__left">
                        <h4 class='m-0 mr-2'>Penawaran</h4>
                    </div>
                    <div class="ps-block__right">
                        <figure>
                            <figcaption></figcaption>
                            <span style='display:none' id='berakhir'><?php echo $tanggal; ?></span>
                            
                            <ul class="ps-countdown" data-time="">
                                <li>Berakhir Dalam </li>
                                <li><span class="hours"></span> </li>
                                <li><span class="minutes"></span> </li>
                                <li><span class="seconds"></span> </li>
                            </ul>
                            
                        </figure>
                    </div>
                </div><a class='d-none d-sm-block' href="<?php echo base_url(); ?>produk">Lihat Semua</a>
            </div>
            <div class="ps-section__content">
                <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                    <?php 
                        foreach ($produk->result_array() as $row){
                            $ex = explode(';', $row['gambar']);
                            if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                            if (strlen($row['nama_produk']) > 38){ $judul = substr($row['nama_produk'],0,38).',..';  }else{ $judul = $row['nama_produk']; }

                            $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                            $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0);

                            if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                                $stok = "<div class='ps-product__badge out-stock'>Habis</div>"; 
                                $diskon_persen = ''; 
                            }else{ 
                                $stok = ""; 
                                if ($diskon>0){ 
                                    $diskon_persen = "<div class='ps-product__badge'>$diskon %</div>"; 
                                }else{
                                    $diskon_persen = ''; 
                                }
                            }
                
                            if ($diskon>=1){ 
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon']);
                            }else{
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                            }
                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                            echo "<div class='ps-product ps-product--inner'>
                                    <div class='ps-product__thumbnail'>
                                    <a href='".base_url()."produk/detail/$row[produk_seo]'><img class='preview' loading='lazy' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                        $diskon_persen
                                        $stok
                                        <ul class='ps-product__actions produk-$row[id_produk]'>
                                            <li><a href='".base_url()."produk/detail/$row[produk_seo]' data-toggle='tooltip' data-placement='top' title='Read More'><i class='icon-bag2'></i></a></li>
                                            <li><a href='#' data-toggle='tooltip' data-placement='top' title='Quick View' class='quick_view' data-id='$row[id_produk]'><i class='icon-eye'></i></a></li>";
                                            if ($cek_save>='1'){
                                                echo "<li><a data-toggle='tooltip' data-placement='top' title='Add to Whishlist'><i style='color:red' class='icon-heart'></i></a></li>";
                                            }else{
                                                echo "<li><a data-toggle='tooltip' data-placement='top' id='save-$row[id_produk]' title='Add to Whishlist'><i class='icon-heart' onclick=\"save('$row[id_produk]',this.id)\"></i></a></li>";
                                            }
                                        echo "</ul>
                                    </div>

                                    <div class='ps-product__container'>
                                        <p class='ps-product__price sale' style='padding-left:10px; background: #d7d7d7;'>".getHargaJual($row['id_produk'])."</p>
                                        <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                            ".rate_bintang($row['id_produk'])."
                                            <p><span class='fa fa-map-marker'></span> ".reseller_kota($row['id_reseller'])."</p>
                                        </div>
                                    </div>
                                
                                </div>";
                
                          
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php }else{ echo "<span style='display:none' id='berakhir'></span>"; } } ?>
    

    <div style='margin:20px 0px' class="ps-site-features d-none d-sm-block">
        <div class="ps-container">
            <div class="ps-block--site-features ps-block--site-features-2">
            <?php 
                $banner = $this->model_app->view_where_ordering_limit('banner',array('posisi'=>'top'),'id_banner','DESC',0,4);
                foreach ($banner->result_array() as $row) {
                    echo "<div class='ps-block__item'>
                            <div class='ps-block__left'><i class='$row[icon]'></i></div>
                            <div class='ps-block__right'>
                            <a href='$row[url]'>
                                <h4>$row[judul]</h4>
                                <p>$row[keterangan]</p>
                            </a>
                            </div>
                          </div>";
                }
            ?>
            </div>
        </div>
    </div>

    <?php 
        $kategori_content = $this->db->query('SELECT a.*,b.jumlah FROM
        (SELECT * FROM `rb_kategori_produk`) as a left join
        (select id_kategori_produk, COUNT(*) jumlah from rb_produk GROUP BY id_kategori_produk HAVING COUNT(id_kategori_produk)) as b on a.id_kategori_produk=b.id_kategori_produk
        where b.jumlah>=6 ORDER BY RAND() LIMIT 5');
        foreach ($kategori_content->result_array() as $ku1) {
    ?>

    <div class="ps-product-list ps-clothings">
        <div class="ps-container">
            <div class="ps-section__header">
                <?php 
                    echo "<h4 class='m-0'>$ku1[nama_kategori]</h4>
                          <ul class='ps-section__links'>
                            <li><a style='color:green; font-weight:500' href='".base_url()."produk/kategori/$ku1[kategori_seo]'>Lihat Semua</a></li>
                          </ul>";
                ?>
            </div>
            <div class="ps-section__content">
            <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="false" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                    
                <?php 
                $produk = $this->model_reseller->produk_perkategori(0,0,$ku1['id_kategori_produk'],10);
                foreach ($produk->result_array() as $row){
                    $ex = explode(';', $row['gambar']);
                    if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                    if (strlen($row['nama_produk']) > 38){ $judul = substr($row['nama_produk'],0,38).',..';  }else{ $judul = $row['nama_produk']; }

                    $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                    $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0);

                    if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                        $stok = "<div class='ps-product__badge out-stock'>Habis</div>"; 
                        $diskon_persen = ''; 
                    }else{ 
                        $stok = ""; 
                        if ($diskon>0){ 
                            $diskon_persen = "<div class='ps-product__badge'>$diskon %</div>"; 
                        }else{
                            $diskon_persen = ''; 
                        }
                    }
        
                    if ($diskon>=1){ 
                        $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon']);
                    }else{
                        $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                    }

                    $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                    echo "<div class='ps-product'>
                            <div class='ps-product__thumbnail'><a href='".base_url()."asset/foto_produk/$foto_produk' class='progressive replace'><img class='preview' loading='lazy' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                $diskon_persen
                                $stok
                                <ul class='ps-product__actions produk-$row[id_produk]'>
                                    <li><a href='".base_url()."produk/detail/$row[produk_seo]' data-toggle='tooltip' data-placement='top' title='Read More'><i class='icon-bag2'></i></a></li>
                                    <li><a href='#' data-toggle='tooltip' data-placement='top' title='Quick View' class='quick_view' data-id='$row[id_produk]'><i class='icon-eye'></i></a></li>";
                                    if ($cek_save>='1'){
                                        echo "<li><a data-toggle='tooltip' data-placement='top' title='Add to Whishlist'><i style='color:red' class='icon-heart'></i></a></li>";
                                    }else{
                                        echo "<li><a data-toggle='tooltip' data-placement='top' id='save-$row[id_produk]' title='Add to Whishlist'><i class='icon-heart' onclick=\"save('$row[id_produk]',this.id)\"></i></a></li>";
                                    }
                                echo "</ul>
                            </div>
                            <div class='ps-product__container'>
                                <div class='ps-product__content'>
                                    <a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                    <a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                    ".rate_bintang($row['id_produk'])."
                                    <br><small class='text-primary'>".textRekomenJual().$harga_produk."</small>
                                    <p class='ps-product__price sale'>".getHargaJual($row['id_produk'])."</p>
                                </div>
                                <div class='ps-product__content hover'>
                                    <a class='ps-product__vendor' style='color:blue' href='".base_url()."u/".user_reseller($row['id_reseller'])."'><span class='fa fa-map-marker' style='font-size:16px'></span> ".reseller_kota($row['id_reseller'])."</a>
                                    <a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$row[nama_produk]</a>
                                    <p class='ps-product__price sale'>".getHargaJual($row['id_produk'])."</p>
                                    <a style='margin-top:0px' href='".base_url()."produk/detail/$row[produk_seo]' class='ps-btn ps-btn--fullwidth add-to-cart'>Lihat Detail</a>
                                </div>
                            </div>
                        </div>";
                }
                ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="ps-home-ads">
        <div class="ps-container">
            <div class="row">
                <?php
                $no = 1;
                $iklantengah = $this->db->query("SELECT * FROM iklantengah where judul like 'footer%'");
                foreach ($iklantengah->result_array() as $b) {
                    if ($no=='1'){ $class = 'col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12'; }else{ $class = 'col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12'; }
                    $string = $b['gambar'];
                    if ($b['gambar'] != ''){
                        if(preg_match("/swf\z/i", $string)) {
                            echo "<div class='$class '><embed class='ps-collection' src='".base_url()."asset/foto_iklantengah/$b[gambar]' quality='high' type='application/x-shockwave-flash'></div>";
                        } else {
                            echo "<div class='$class '><a class='ps-collection' href='$b[url]' target='_blank'><img class='preview' loading='lazy' src='".base_url()."asset/foto_iklantengah/$b[gambar]' alt='$b[judul]' /></a></div>";
                        }
                    }
                    $no++;
                }
                ?>
            </div>
        </div>
    </div>

    <div style='clear:both'></div>
    <div class="ps-product-list ps-new-arrivals">
        <div class="ps-container">
            <div class="ps-section__header">
                <h3>Produk Baru Terpopuler</h3>
                <ul class="ps-section__links d-none d-sm-block">
                    <?php 
                        $kategori = $this->db->query("SELECT * FROM rb_kategori_produk ORDER BY RAND() LIMIT 3");
                        foreach ($kategori->result_array() as $row) {
                            echo "<li><a href='".base_url()."produk/kategori/$row[kategori_seo]'>$row[nama_kategori]</a></li>";
                        }
                    ?>
                    <li><a href="<?php echo  base_url(); ?>produk">Lihat Semua</a></li>
                </ul>
            </div>

            <div class="ps-section__content" style='background:transparent'>
            <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="false" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                    
                    <?php 
                    $terbaru = $this->model_reseller->produk_terbaru(0,0,8);
                    foreach ($terbaru->result_array() as $row){
                        $ex = explode(';', $row['gambar']);
                        if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                        if (strlen($row['nama_produk']) > 38){ $judul = substr($row['nama_produk'],0,38).',..';  }else{ $judul = $row['nama_produk']; }

                        $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                        $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0);
    
                        if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                            $stok = "<div class='ps-product__badge out-stock'>Habis</div>"; 
                            $diskon_persen = ''; 
                        }else{ 
                            $stok = ""; 
                            if ($diskon>0){ 
                                $diskon_persen = "<div class='ps-product__badge'>$diskon %</div>"; 
                            }else{
                                $diskon_persen = ''; 
                            }
                        }
            
                        if ($diskon>=1){ 
                            $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon']);
                        }else{
                            $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                        }
    
                        $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                        echo "<div class='ps-product'>
                                <div class='ps-product__thumbnail'><a href='".base_url()."asset/foto_produk/$foto_produk' class='progressive replace'><img class='preview' loading='lazy' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                    $diskon_persen
                                    $stok
                                    <ul class='ps-product__actions produk-$row[id_produk]'>
                                        <li><a href='".base_url()."produk/detail/$row[produk_seo]' data-toggle='tooltip' data-placement='top' title='Read More'><i class='icon-bag2'></i></a></li>
                                        <li><a href='#' data-toggle='tooltip' data-placement='top' title='Quick View' class='quick_view' data-id='$row[id_produk]'><i class='icon-eye'></i></a></li>";
                                        if ($cek_save>='1'){
                                            echo "<li><a data-toggle='tooltip' data-placement='top' title='Add to Whishlist'><i style='color:red' class='icon-heart'></i></a></li>";
                                        }else{
                                            echo "<li><a data-toggle='tooltip' data-placement='top' id='save-$row[id_produk]' title='Add to Whishlist'><i class='icon-heart' onclick=\"save('$row[id_produk]',this.id)\"></i></a></li>";
                                        }
                                    echo "</ul>
                                </div>
                                <div class='ps-product__container'>
                                    <div class='ps-product__content'>
                                        <a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                        <a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                        ".rate_bintang($row['id_produk'])."
                                        <br><small class='text-primary'>".textRekomenJual().$harga_produk."</small>
                                        <p class='ps-product__price sale'>".getHargaJual($row['id_produk'])."</p>
                                    </div>
                                    <div class='ps-product__content hover'>
                                        <a class='ps-product__vendor' style='color:blue' href='".base_url()."u/".user_reseller($row['id_reseller'])."'><span class='fa fa-map-marker' style='font-size:16px'></span> ".reseller_kota($row['id_reseller'])."</a>
                                        <a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$row[nama_produk]</a>
                                        <p class='ps-product__price sale'>".getHargaJual($row['id_produk'])."</p>
                                        <a style='margin-top:0px' href='".base_url()."produk/detail/$row[produk_seo]' class='ps-btn ps-btn--fullwidth add-to-cart'>Lihat Detail</a>
                                    </div>
                                </div>
                            </div>";
                    }
                    ?>
                    </div>
                    
                </div>
                <a class='mt-5 mb-5 ps-btn ps-btn--fullwidth ps-btn--black'  style='border:1px solid #b7b7b7; padding:5px 30px; background:#fbfbfb; color:#8a8a8a !important' href='<?php echo base_url(); ?>produk'>Lihat Lainnya</a>
            </div>
        </div>
    </div>
    
    <?php if (config('apps_aktif')=='Y'){ ?>
    <div class="ps-download-app d-none d-sm-block" style='margin:30px 0px;'>
        <div class="container">
            <div class="ps-block--download-app" style='border:1px solid #e8e8e8'>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                        <div class="ps-block__thumbnail"><a href='<?php echo base_url(); ?>asset/images/<?= config('apps_image'); ?>' class='progressive replace'><img class='preview' loading='lazy' src="<?php echo base_url(); ?>asset/images/<?= config('apps_image'); ?>" alt=""></a></div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                        <div class="ps-block__content">
                            <h3><?= config('apps_title'); ?></h3>
                            <p><?= config('apps_deskripsi'); ?></p>
                            <form class="ps-form--download-app" action="<?php echo base_url() ?>main/subscribe" method="post">
                                <div class="form-group--nest">
                                    <input class="form-control" type="email" name='email' placeholder="Email Address" autocomplete='off' required>
                                    <button type='submit' name='submit' class="ps-btn">Subscribe</button>
                                </div>
                            </form>
                            <p class="download-link"><a href="<?= config('apps_google_play'); ?>"><img class='preview' loading='lazy' src="<?php echo base_url(); ?>asset/images/google-play.webp" alt="google-play.webp"></a><a href="<?= config('apps_app_store'); ?>"><img class='preview' loading='lazy' src="<?php echo base_url(); ?>asset/images/app-store.webp" alt="app-store.webp"></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>