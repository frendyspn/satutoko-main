

<div class="ps-breadcrumb">
    <div class="ps-container">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>">Home</a></li>
            <li><a href="<?php echo base_url(); ?>produk">Produk</a></li>
            <li><?php echo $rows['nama_reseller']; ?></li>
        </ul>
    </div>
</div>

<div class="ps-vendor-store">
    <div class="container">
    <?= operasional($rows['id_reseller']); ?>
        <div class="ps-section__container">
            <?php include "sidebar_pelapak.php"; ?>
            <div class="ps-section__right">
                <?php echo $this->session->flashdata('message'); 
              $this->session->unset_userdata('message'); ?>
                <div class="ps-block--vendor-filter">
                    <div class="ps-block__left">
                        <ul class="ps-tab-list">
                          <li><a href="#">Terdapat <strong><?php echo $record_hitung->num_rows(); ?></strong> Produk <b class='text-success'><?= $kategorix; ?></b> </a></li>
                        </ul>
                    </div>
                    <div class="ps-block__right">
                        <form class="ps-form--search" action="<?php echo base_url(); ?>u/<?= $this->uri->segment(2); ?>" method="get">
                            <input class="form-control" type="text" id='search_text' name='s' placeholder="Cari produk di toko ini,.">
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
                                echo "<div class='col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 '>
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

