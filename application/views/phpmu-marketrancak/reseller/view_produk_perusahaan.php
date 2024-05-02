<div class="ps-breadcrumb">
        <div class="ps-container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <?php 
                    if (cetak($this->input->post('kata'))!=''){
                        echo "<li><a href='#'>Produk</a></li>
                              <li>$title</li>";
                    }else{
                        if (isset($_POST['cari'])){
                            echo "<li>Produk</li>";
                            echo "<li>$judul</li>";
                        }else{
                            echo "<li>Produk Perusahaan</li>";
                        }
                    }
                ?>
                
            </ul>
        </div>
    </div>
    <div class="ps-vendor-store">
        <div class="container">
            <div class="ps-section__container">
                <?php // include "sidebar-produk.php"; ?>
                <div class="ps-section">
                    <?php echo $this->session->flashdata('message'); 
                    $this->session->unset_userdata('message'); ?>
                
                    <div class="ps-block--vendor-filter">
                        <div class="ps-block__left">
                            <ul class="ps-tab-list">
                            <li><a href="#">Terdapat <strong><?php echo $record->num_rows(); ?></strong> Reseller Produk </a></li>

                            </ul>
                        </div>
                        <div class="ps-block__right">
                            <form class="ps-form--search" action="<?php echo base_url(); ?>produk/perusahaan" method="get">
                                <input class="form-control" type="text" id='search_text' name='kata' value='<?= cetak($_GET['kata']); ?>' placeholder="Cari produk di toko ini,.">
                                <button type="submit" style='border:1px solid #e3e3e3; background:#6d6d6d' class='btn btn-primary'><span class='fa fa-search'></span></button>
                            </form>
                        </div>
                    </div>

                    <div class="ps-shopping ps-tab-root">
                        <div class="ps-tabs">
                            <div class="ps-tab active" id="tab-1">
                                <div class="ps-shopping-product">
                                    <?php 
                                        if ($record->num_rows()=='0'){ 
                                            echo "<center>
                                            <img style='width:250px' src='".base_url()."asset/images/no-product.png'>
                                            <h3><br>Oops, produk gak ditemukan</h3>
                                                Coba kata kunci lain untuk menemukan produk yang dicari...</center>";
                                        }
                                    ?>
                                    <div class="row">
                                    <?php 
                                        foreach ($record->result_array() as $row){
                                            $ex = explode(';', $row['gambar']);
                                            if (trim($ex[0])==''){ $foto_produk = 'no-image.png'; }else{ $foto_produk = $ex[0]; }
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
                                                $harga_produk =  "Rp ".rupiah($row['harga_reseller']-$disk['diskon'])." <del style='display:none'>".rupiah($row['harga_konsumen'])."</del>";
                                            }else{
                                                $harga_produk =  "Rp ".rupiah($row['harga_reseller']);
                                            }

                                            $sold = $this->model_reseller->produk_terjual($row['id_produk'],2);
                                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();

                                            echo "<div class='col-xl-2 col-lg-3 col-md-3 col-sm-6 col-6 '>
                                                    <div class='ps-product'>
                                                        <div class='ps-product__thumbnail'><a href='".base_url()."produk/perusahaan_detail/$row[produk_seo]'><img src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                                        $diskon_persen
                                                        </div>
                                                        <div class='ps-product__container'>
                                                            <div class='ps-product__content'>
                                                                <a class='ps-product__vendor text-success' href='#'>Produk Reseller</a>
                                                                <a class='ps-product__title' href='".base_url()."produk/perusahaan_detail/$row[produk_seo]'>$judul</a>
                                                                <p class='ps-product__price'>$harga_produk</p>
                                                            </div>
                                                            <div class='ps-product__content hover'>
                                                                <a class='ps-product__vendor' href='#'>Produk Reseller</a>
                                                                <a class='ps-product__title' href='".base_url()."produk/perusahaan_detail/$row[produk_seo]'>$judul</a>
                                                                <p class='ps-product__price'>$harga_produk</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";
                                        }
                                    ?>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="ps-tab" id="tab-2">
                                <div class="ps-shopping-product">
                                <?php 
                                    foreach ($record->result_array() as $row){
                                        $ex = explode(';', $row['gambar']);
                                        if (trim($ex[0])==''){ $foto_produk = 'no-image.png'; }else{ $foto_produk = $ex[0]; }
                                        $judul = $row['nama_produk'];
   
                                        $disk = $this->model_app->view_where("rb_produk_diskon",array('id_produk'=>$row['id_produk']))->row_array();
                                        if ($disk['diskon']>0 OR $disk['diskon']!=''){
                                            $diskon = rupiah(($disk['diskon']/$row['harga_reseller'])*100,0);
                                        }else{
                                            $diskon = 0;
                                        }

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
                                            $harga_produk =  "".rupiah($row['harga_reseller']-$disk['diskon'])." <del style='display:none'>".rupiah($row['harga_konsumen'])."</del>";
                                        }else{
                                            $harga_produk =  "".rupiah($row['harga_reseller']);
                                        }

                                        $sold = $this->model_reseller->produk_terjual($row['id_produk'],2);
                                        $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                                        
                                        echo "<div class='ps-product ps-product--wide'>
                                            <div class='ps-product__thumbnail'><a href='".base_url()."produk/perusahaan_detail/$row[produk_seo]'><img src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
                                            </div>
                                            <div class='ps-product__container'>
                                                <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/perusahaan_detail/$row[produk_seo]'>$judul</a>
                                       
                                                    ".nl2br($row['tentang_produk'])."
                                                </div>
                                                <div class='ps-product__shopping'>
                                                    <p class='ps-product__price'>$harga_produk</p>
                                                    
                                                </div>
                                            </div>
                                        </div>";
                                    }
                                ?>

                                </div>
                                
                            </div>
                            <?php 
                                if (cetak($this->input->post('kata'))==''){ 
                                if (!isset($_POST['cari'])){
                            ?>
                                <div class="ps-pagination">
                                    <?php echo $this->pagination->create_links(); ?>
                                </div>
                            <?php 
                                }}
                            ?>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
