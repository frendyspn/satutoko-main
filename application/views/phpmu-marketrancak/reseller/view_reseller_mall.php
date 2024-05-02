

<div class="ps-breadcrumb">
    <div class="ps-container">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>">Home</a></li>
            <li><a href="<?php echo base_url(); ?>produk">Produk</a></li>
            <li><a href='<?php echo base_url()."u/$rows[user_reseller]"; ?>'><?php echo $rows['nama_reseller']; ?></a></li>
            <?php 
                if (isset($_GET['k'])){ 
                    echo "<li style='text-transform:capitalize'>".cetak($_GET['k'])."</li>";
                } 
            ?>
        </ul>
    </div>
</div>

<div class="ps-vendor-store" style='padding:0px'>
        <div id="homepage-1">
            <div class="ps-home-banner ps-home-banner--1" style='margin-bottom:0px'>
            <div class="ps-container container1" style='margin-top:30px'>
            <?php
            if (!isset($_GET['k'])){
                $slide1 = $this->db->query("SELECT * FROM slide where posisi='mall' ORDER BY id_slide ASC");
                if ($slide1->num_rows()>1){
                echo "<div class='ps-section__left' style='padding-right:10px;'>
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
                ?>

                <div class="ps-section__right d-none d-lg-block">
                <?php
                $noads = 1;
                $pasangiklan2 = $this->db->query("SELECT * FROM iklantengah where judul like 'mall%'");
                foreach ($pasangiklan2->result_array() as $b) {
                    if ($noads=='1'){ $radius = "border-radius: 0px 20px 0px 0px;"; }else{ $radius = "border-radius: 0px 0px 20px 0px;"; }
                    $string = $b['gambar'];
                    if ($b['gambar'] != ''){
                        if(preg_match("/swf\z/i", $string)) {
                            echo "<embed class='ps-collection preview' loading='lazy' src='".base_url()."asset/foto_iklantengah/$b[gambar]' quality='high' type='application/x-shockwave-flash'>";
                        } else {
                            echo "<a class='ps-collection' href='$b[url]' target='_blank'><img style='$radius' class='preview' loading='lazy' src='".base_url()."asset/foto_iklantengah/$b[gambar]' alt='$b[judul]' /></a>";
                        }
                    }
                    $noads++;
                }
                ?>
                </div>
            <?php } ?>
            </div>
            </div>

            <?php if (!isset($_GET['k'])){ ?>
            <div class="ps-site-features d-none d-sm-block" style='padding-bottom:0px'>
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
            <?php } ?>


            <div class="ps-container">
                <div class='row'>
                    <div class='col-12 col-md-12'>
                    <div class="ps-section__content xxx" style='border:1px solid #cecece; padding:2px 8px 5px 8px'>
                    <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true" data-owl-item="6" data-owl-item-xs="3" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                <?php    
                    $top_kategori = $this->db->query("SELECT a.id_kategori_produk, b.* FROM rb_produk a JOIN rb_kategori_produk b ON a.id_kategori_produk=b.id_kategori_produk where a.id_reseller='$rows[id_reseller]' GROUP BY a.id_kategori_produk");
                    foreach($top_kategori->result_array() as $row){
                        if ($row['icon_kode']!=''){
                            $icon = "<i style='font-size:36px' class='$row[icon_kode]'></i>";
                        }elseif ($row['icon_image']!=''){
                            $icon = "<center><img style='width:55px; height:55px; margin-bottom:3px' src='".base_url()."asset/foto_produk/kategori/$row[icon_image]'></center>";

                        }else{
                            $icon = "";
                        }
                        
                        echo "<div class='col4'>
                            <a style='margin-top:15px; height:80%' class='ps-block__overlay' href='".base_url()."u/$rows[user_reseller]?k=$row[kategori_seo]'>
                                $icon <p style='font-size:13px; line-height:1.1em; color:#000; height:30px; overflow:hidden;'>$row[nama_kategori]</p>
                            </a>
                            </div>";
                    }
                    echo "
                    </div>
                    </div><br>";
                ?>
                </div>
            </div>
            <div style='clear:both'></div> 
        </div>

    <div class="container">
        <div class="ps-section__container">
            <div class="ps-section">
                <?php echo $this->session->flashdata('message'); 
              $this->session->unset_userdata('message'); ?>
              
                <div class="ps-block--vendor-filter">
                    <div class="ps-block__left">
                        <ul class="ps-tab-list">
                          <li><a href="#"><b>MALL</b> : Ada <strong><?php echo $record_hitung->num_rows(); ?></strong> Produk <b class='text-success'><?= $kategorix; ?></b> </a></li>
                        </ul>
                    </div>
                    <div class="ps-block">
                        <form class="ps-form--search" action="<?php echo base_url(); ?>u/<?= $this->uri->segment(2); ?>" method="get">
                            <input class="form-control" type="text" id='search_text' name='s' placeholder="Cari produk Mall,..">
                            <button type="submit" style='border:1px solid #e3e3e3; background:#6d6d6d' class='btn btn-primary'><span class='fa fa-search'></span></button>
                        </form>
                    </div>
                </div>
                
                <div class="ps-shopping ps-tab-root">
                    <div class="ps-tabs">
                        <div class="ps-tab active" id="tab-1">
                        <div class="row">
                        <?php 
                            foreach ($record->result_array() as $row){
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
                                    $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del style='display:none'>".rupiah($row['harga_konsumen'])."</del>";
                                }else{
                                    $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                                }

                                $sold = $this->model_reseller->produk_terjual($row['id_produk'],2);
                                $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                                echo "<div class='col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 '>
                                        <div class='ps-product'>
                                            <div class='ps-product__thumbnail'><a href='".base_url()."produk/detail/$row[produk_seo]'><img src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
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
                                                    ".rate_bintang($row['id_produk'])."
                                                    <a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>$row[nama_reseller]</a>
                                                    <a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]' title='$row[nama_produk]'>$judul</a>
                                                    <p class='ps-product__price'>$harga_produk</p>
                                                </div>
                                                <div class='ps-product__content hover'>
                                                    ".rate_bintang($row['id_produk'])."
                                                    <a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>$row[nama_reseller]</a>
                                                    <a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]' title='$row[nama_produk]'>$judul</a>
                                                    <p class='ps-product__price'>$harga_produk</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                            }
                        ?>
                        </div>

                        <div class="ps-pagination">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
 load_data();
 function load_data(query){
  $.ajax({
   url:"<?php echo base_url(); ?>produk/reseller_cari_produk",
   method:"POST",
   data:{query:query,id:<?php echo cetak($rows['id_reseller']); ?>},
   success:function(data){
    $('#result').html(data);
   }
  })
 }

 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != ''){
   load_data(search);
  }else{
   load_data();
  }
 });
});
</script>

