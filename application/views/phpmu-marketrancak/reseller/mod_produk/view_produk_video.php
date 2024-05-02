<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li><a href="#">Produk</a></li>
                <li>Video</li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
            <?php include "menu-members.php"; ?>
   
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <?php
                         require_once(APPPATH.'views/'.template().'/reseller/sidebar-members.php');
                    ?>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                <div class="ps-block--vendor-filter" style='display:block'>
                    <div class="ps-block" style='max-width:100%'>
                        Terdapat <strong><?php echo $record->num_rows(); ?></strong> Video : <a class='btn btn-success btn-sm float-right' href="<?= base_url(); ?>members/video?t=<?= $_GET['t']; ?>"><span class='fa fa-plus'></span> Video</a>
                    </div>
                </div>
                    <figure class="ps-block--vendor-status biodata">
                        <?php if ($record->num_rows()<=0){
                            echo "<div class='alert alert-info'><strong>INFORMASI!</strong> - Anda Belum memiliki video. <br> Yuk Upload video pertamanya <a href='".base_url()."members/video_tambah' style='color:#000'><b>disini</b></a>.</div>
                            <div style='clear:both'></div>";
                        } ?>
                    <div class="ps-shopping-product">
                        <div class="row">
                    <?php 
                    foreach ($record->result_array() as $row){
                        $ex = explode(';', $row['gambar']);
                        if (trim($ex[0])==''){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                        if (strlen($row['nama_produk']) > 38){ $judul = substr($row['nama_produk'],0,38).',..';  }else{ $judul = $row['nama_produk']; }

                        $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                        $diskon = rupiah(($disk['diskon']/$row['harga_konsumen'])*100,0);

                        if (stok($row['id_reseller'],$row['id_produk'])<=0){ 
                            $stok = "<div class='ps-product__badge out-stock'>Habis</div>"; 
                            $diskon_persen = ''; 
                        }else{ 
                            $stok = ""; 
                            if ($diskon>0){ 
                                $diskon_persen = "<div class='ps-product__badgex'>$diskon %</div>"; 
                            }else{
                                $diskon_persen = ''; 
                            }
                        }

                        if ($diskon>=1){ 
                            $harga_produk =  "Rp. ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del style='display:none'>".rupiah($row['harga_konsumen'])."</del>";
                        }else{
                            $harga_produk =  "Rp. ".rupiah($row['harga_konsumen']);
                        }

                        $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                        
                        echo "<div class='col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 '>
                                <div class='ps-product'>
                                    <div class='ps-product__thumbnail' style='height:290px'>
                                        <video width=100% loop src='".base_url()."asset/img_video/$row[video]' controls type='video/mp4'></video>
                                        <ul class='ps-product__actions produk-$row[id_produk]' style='width:100%; padding:10px 10px; border: 1px solid #e3e3e3'>
                                            <li><a href='".base_url()."members/video?e=$row[id_produk_video]' data-toggle='tooltip' data-placement='top' title='Edit Video'><i class='fa fa-edit'></i></a></li>
                                            <li><a href='".base_url()."produk/video?watch=$row[id_produk_video]' data-toggle='tooltip' data-placement='top' title='Lihat Video'><i class='fa fa-search'></i></a></li>
                                            <li><a href='".base_url()."members/video?d=$row[id_produk_video]' data-toggle='tooltip' data-placement='top' title='Delete Video'  onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-remove'></i></a></li>
                                        </ul>
                                    </div>
                                    <div class='ps-product__container'>
                                        <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                            <p class='ps-product__price'>$harga_produk</p>
                                        </div>
                                        <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
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
                    </figure>
                </div>
              
            </div>
        </div>
    </div>
</div>