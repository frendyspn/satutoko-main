
<?php
$rows = $this->db->query("SELECT a.*, b.nama_kota, c.nama_provinsi FROM `rb_reseller` a JOIN rb_kota b ON a.kota_id=b.kota_id
                            JOIN rb_provinsi c ON b.provinsi_id=c.provinsi_id where a.id_reseller='$record[id_reseller]'")->row_array();
$kat = $this->model_app->view_where('rb_kategori_produk',array('id_kategori_produk'=>$record['id_kategori_produk']))->row_array();
$jual = $this->model_reseller->jual_reseller($record['id_reseller'],$record['id_produk'])->row_array();
$beli = $this->model_reseller->beli_reseller($record['id_reseller'],$record['id_produk'])->row_array();
$disk = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='$record[id_produk]'")->row_array();
$diskon = rupiah(($disk['diskon']/$record['harga_konsumen'])*100,0);
if ($disk['diskon']>0){ $diskon_persen = "<div class='top-right'>$diskon %</div>"; }else{ $diskon_persen = ''; }

if (isset($_GET['g']) AND $_GET['g']!=''){
    $cgroup = $this->db->query("SELECT * FROM rb_produk_group where id_produk='$record[id_produk]' AND id_group='".cetak($this->input->get('g'))."'");
    if ($cgroup->num_rows()>=1){
        $cg = $cgroup->row_array();
        $harga_konsumenx =  $cg['harga_group'];
    }else{
        // $harga_konsumen =  "Rp ".rupiah($record['harga_konsumen']-$disk['diskon']);
        // $harga_konsumenx =  $record['harga_konsumen']-$disk['diskon'];
        // $harga_asli = "Rp ".rupiah($record['harga_konsumen']);
        $harga_konsumen =  "Rp ".getHargaJual($record['id_produk'],'nonformat','YES');
        $harga_konsumenx =  getHargaJual($record['id_produk'],'nonformat','YES');
        $harga_asli = "Rp ".getHargaJual($record['id_produk']);
        
    }
}else{
    if ($disk['diskon']>=1){ 
        // $harga_konsumen =  "Rp ".rupiah($record['harga_konsumen']-$disk['diskon']);
        // $harga_konsumenx =  $record['harga_konsumen']-$disk['diskon'];
        $harga_konsumen =  "Rp ".getHargaJual($record['id_produk'],'nonformat','YES');
        $harga_konsumenx =  getHargaJual($record['id_produk'],'nonformat','YES');
        $harga_asli = "Rp ".getHargaJual($record['id_produk']);
    }else{
        // $harga_konsumen =  "Rp ".rupiah($record['harga_konsumen']);
        // $harga_konsumenx =  $record['harga_konsumen'];
        $harga_konsumen =  "Rp ".getHargaJual($record['id_produk'],'nonformat');
        $harga_konsumenx =  getHargaJual($record['id_produk'],'nonformat');
        $harga_asli = "";
    }
}
?>
<div class="ps-breadcrumb">
<div class="ps-container">
    <ul class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
        <li><a href="<?php echo base_url().'produk/kategori/'.$kat['kategori_seo']; ?>"><?php echo $kat['nama_kategori']; ?></a></li>
        <li><?php echo $record['nama_produk']; ?></li>
    </ul>
</div>
</div>
<div class="ps-page--product">

    <div class="ps-container">
    <div class="product__header d-none d-sm-block">
        <h1><?php echo "<span class='produk-title'>$record[nama_produk]</span>"; 
        if ($record['pre_order']!='' AND $record['pre_order']>0){ echo "<small style='font-size:14px'> (Preorder : <span class='badge badge-danger'>$record[pre_order] Hari</span>)</small>"; }?></h1>
        <div class='sub_title'>
            <p><?php echo rate_bintang($record['id_produk']); ?>
                <?php 
                if (rate_jumlah($record['id_produk'])>0){
                    echo " <span style='color:#cecece'>(Ada ".rate_jumlah($record['id_produk'])." Ulasan)</span>"; 
                }
                ?> 
            </p>
            <?php 
            if (substr($kat['nama_kategori'],0,1)=='+'){
                echo "<p>$kat[nama_kategori]</p>";
            }else{
                if ($record['jenis_produk']=='Fisik'){
                    echo "<p>Berat : <a href='#'>".($record['berat']>1000?number_format($record['berat']/1000,1).' Kg':$record['berat'].' Gram')."</a></p>";
                }else{
                    echo "<p>Produk Digital</p>";
                } 

                echo "<p>Stok <b>".stok($record['id_reseller'],$record['id_produk'])."</b> $record[satuan]</p>"; 
            }


            if ($this->session->id_konsumen!=''){
                $kon = $this->db->query("SELECT username FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
                echo "<span style='display:none' class='reff'>".base_url()."produk/detail/$record[produk_seo]?reff=$kon[username]</span> <button type='button' style='border: none; padding: 0px 30px' id='copyx' data-toggle='button' aria-pressed='false' class='myButtonL' onclick=\"copyToClipboard('.reff')\"><span class='fa fa-copy'></span> Copy produk refferal</button>";
            }

            ?>
            
        </div>
    </div>



    <div class="ps-page__container">
        <div class="ps-page__left">
            <div class="ps-product--detail ps-product--fullwidth">
            <?php 
                echo "<form id='form1' action='".base_url()."produk/keranjang/$record[id_reseller]/$record[id_produk]' method='POST'>";
            ?>
                <div class="ps-product__header">
                    
                    <div class="ps-product__thumbnail" data-vertical="true">
                        <figure>
                            <div class="ps-wrapper">
                                <div class="ps-product__gallery" data-arrow="true">
                                    <?php
                                        if ($record['gambar'] != ''){ 
                                            $ex = explode(';',$record['gambar']);
                                            $hitungex = count($ex);
                                            for($i=0; $i<$hitungex; $i++){
                                                if (file_exists("asset/foto_produk/".$ex[$i])) { 
                                                    echo "<div class='item'><a href='".base_url()."asset/foto_produk/".$ex[$i]."'><img src='".base_url()."asset/foto_produk/".$ex[$i]."'></a></div>";
                                                }else{
                                                    echo "<img src='".base_url()."asset/foto_produk/no-image.png'>";
                                                }
                                            }
                                        }else{
                                            echo "<img src='".base_url()."asset/foto_produk/no-image.png'>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </figure>
                        <?php if(count(explode(';',$record['gambar']))>1){ ?>
                        <div class="ps-product__variants d-none d-sm-block" data-item="4" data-md="4" data-sm="4" data-arrow="false">
                            <?php
                                if ($record['gambar'] != ''){ 
                                    $ex = explode(';',$record['gambar']);
                                    $hitungex = count($ex);
                                    for($i=0; $i<$hitungex; $i++){
                                        if (file_exists("asset/foto_produk/".$ex[$i])) { 
                                            if (trim($ex[$i])=='' OR !file_exists("asset/foto_produk/".$ex[$i])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[$i])){ $foto_produk = $ex[$i]; }else{ $foto_produk = "thumb_".$ex[$i]; }}
                                            echo "<div class='item'><img src='".base_url()."asset/foto_produk/$foto_produk'></div>";
                                        }else{
                                            echo "<img src='".base_url()."asset/foto_produk/no-image.png'>";
                                        }
                                    }
                                }else{
                                    echo "<div class='item'><img src='".base_url()."asset/foto_produk/no-image.png'></div>";
                                }
                            ?>
                        </div>
                        <?php } ?>
                    </div>

                    <!-- // Header Mobile -->
                    <div class="product__header d-block d-sm-none">
                        <h1><?php echo "$record[nama_produk]";
                        if ($record['pre_order']!='' AND $record['pre_order']>0){ echo "<small style='font-size:11px'> (Preorder : <span class='badge badge-danger'>$record[pre_order] Hari</span>)</small>"; }
                        ?> 
                        </h1>
                        <div class='sub_title'>
                            <p><?php echo rate_bintang($record['id_produk']); ?>
                                <?php 
                                if (rate_jumlah($record['id_produk'])>0){
                                    echo " <span style='color:#cecece'>(Ada ".rate_jumlah($record['id_produk'])." Ulasan)</span>"; 
                                }
                                ?> 
                            </p>
                            <p><a href="#"><?php echo ($record['berat']>1000?number_format($record['berat']/1000,1).' Kg':$record['berat'].' Gram') ?></a></p>
                            <?php echo "<p><b>".stok($record['id_reseller'],$record['id_produk'])."</b> $record[satuan]</p>"; ?>
                        </div>
                    </div>
                    <!-- // End Header Mobile -->

                    <div class="ps-product__info">
                        <?php 
                            echo $this->session->flashdata('message'); 
                            $this->session->unset_userdata('message');
                        ?>
                        
                        <p class='d-none d-sm-block'><i class="icon-store float-left mr-2" style='font-size: 4.7rem; margin-top:-5px;'></i> 
                        <a href="<?php echo base_url()."u/".user_reseller($record['id_reseller']).""; ?>">
                            <strong style='font-size:18px'> <?php echo $rows['nama_reseller']; ?></strong>
                        </a>
                            <?php 
                                if (config('wa_seller')=='Y'){
                                    //echo "<a target='_BLANK' style='text-transform: capitalize; font-size: 9px; background: green; color: #fff; padding: 0px 10px;' class='ps-btn' href='https://api.whatsapp.com/send?phone=".format_telpon($rows['no_telpon'])."&amp;text=Hallo%20kak!%20$rows[nama_reseller],%20Saya%20Mau%20Order%20$record[nama_produk]...'><span class='icon-bubbles'></span> Chat Penjual.</a>";
                                    if ($rows['no_telpon']!=''){
                                        $action_chat = "https://wa.me/".format_telpon($rows['no_telpon']);
                                    }else{
                                        $action_chat = '';
                                    }
                                }else{
                                    //echo "<a target='_BLANK' style='text-transform: capitalize; font-size: 9px; background: green; color: #fff; padding: 0px 10px;' class='ps-btn' href='".base_url()."members/read/".konsumen($rows['id_reseller'])."/0'><span class='icon-bubbles'></span> Chat Penjual.</a>";
                                    $action_chat = base_url()."members/read/".konsumen($rows['id_reseller'])."/0";
                                }
                                echo "<a target='_BLANK' class='ps-btn ps-chat' href='https://admin.1toko.id/jualin/". $record['produk_seo'] ."'> Jualin</a>";
                                echo "<a target='_BLANK' class='ps-btn ps-chat' data-toggle='modal' data-target='#myChat'><span class='icon-bubbles'></span> Chat</a>";
                                
                            ?>
                            <br>Status : <?php echo verifikasi_icon($record['id_reseller']); ?>
                        </p>
                        <small class='text-primary'><?= textRekomenJual().rupiah($record['harga_konsumen']) ?></small>
                        <h4 class="ps-product__price">
                        <?php 
                            if (isset($_GET['g']) AND $_GET['g']!=''){
                                $cgroup = $this->db->query("SELECT * FROM rb_produk_group where id_produk='$record[id_produk]' AND id_group='".cetak($this->input->get('g'))."'");
                                if ($cgroup->num_rows()>=1){
                                    $cg = $cgroup->row_array();
                                    echo "<input type='hidden' id='group' name='group' value='$cg[id_group]'>
                                    <input type='hidden' id='kgroup' name='kgroup' value='".cetak($this->input->get('kode'))."'>
                                         <span id='totalharga'></span> <small style='font-size:14px; color:red'>/ Beli Ber-$cg[jumlah_group]</small>
                                        <p class='alert alert-danger' style='font-weight:400; padding:3px 0px 3px 10px;'><b><i class='icon-warning'></i> GARANSI</b> - Dapatkan Barang Pesananmu atau uang kembali.</p>"; 
                                }else{
                                    echo "<input type='hidden' id='group' name='group' value=''>
                                    <input type='hidden' id='kgroup' name='kgroup' value=''>
                                    <span id='totalharga'></span> <del style='color:#8a8a8a'>$harga_asli</del>"; 
                                }
                            }else{
                                echo "<input type='hidden' id='group' name='group' value=''>
                                    <input type='hidden' id='kgroup' name='kgroup' value=''>
                                    <span id='totalharga'></span> <del class='del' style='color:#8a8a8a'>$harga_asli</del>"; 
                            }
                        ?>
                        <?php 
                            if ($record['minimum']>1){
                                echo "<br><span style='color:red; font-size: 1.3rem;'>Minimal Order <b>$record[minimum]</b> $record[satuan]</span>";
                            }
                        ?>

                        <a style='padding-left:10px; padding-right:10px; font-size:19px; margin-right:0px; padding-bottom: 5px; margin-top: -5px;' class="ps-btn float-right d-block d-sm-none" id='save-<?= $record['id_produk']; ?>'> <i class="icon-heart" onclick="save('<?= $record['id_produk']; ?>',this.id)"></i></a>
                        
                        </h4>
                        <div class="ps-product__variations">
                            <input type='hidden' name='warnax' id='warnax' value='0'>
                            <input type='hidden' name='ukuranx' id='ukuranx' value='0'>
                            <input type='hidden' name='lainnyax' id='lainnyax' value='0'>
                            <input type='hidden' name='totalx' id='totalx' value='<?= $harga_konsumenx; ?>'>
                            <input type='hidden' name='totalxx' id='totalxx' value='<?= $harga_konsumenx; ?>'>
                            <figure>
                            <?php 
                                $variasi = $this->db->query("SELECT * FROM rb_produk_variasi where id_produk='$record[id_produk]' ORDER BY id_variasi ASC");
                                $level_order = $this->db->query("SELECT * FROM rb_produk_level where id_produk='$record[id_produk]'");
                                echo "<div class='form-row'>";
                                if (substr($kat['nama_kategori'],0,1)=='+'){
                                        $tombol_beli = "Book Now!";
                                        $tombol_keranjang = '+ Add to List';
                                }else{
                                    if ($record['pre_order']!='' AND $record['pre_order']>0){
                                        $tombol_beli = "Pre-Order";
                                    }else{
                                        $tombol_beli = "Beli Sekarang";
                                    }
                                    $tombol_keranjang = '+ Keranjang';
                                }

                                    if ($level_order->num_rows()>0){
                                        echo "<div style='display:block; width:100%; font-weight:bold; margin-left:5px'>Pilih Varian</div>
                                        <div class='form-group col-md-6 col-6 mb-2' style='margin-bottom:0px'>
                                        <select class='form-control level' id='id_level' name='id_level'>
                                            <option value='0' data-value='$harga_konsumenx:0'>".(substr($kat['nama_kategori'],0,1)=='+'?'Harian':'Satuan')."</option>";
                                        foreach ($level_order->result_array() as $va) {
                                            echo "<option value='$va[id_level]' data-value='$va[harga_level]:$va[qty_level]'>$va[nama_level]</option>";
                                        }
                                        echo "</select></div>";
                                    }else{
                                        echo "<input type='hidden' value='0' id='id_level' name='id_level'>";
                                    }

                                    if ($variasi->num_rows()>0){ 
                                        $no = 1;
                                        $varname = array('','warna','ukuran','lainnya');
                                        foreach ($variasi->result_array() as $va) {
                                            echo "<div class='form-group col-md-6 col-6' style='margin-bottom:0px'> <select class='form-control ".$varname[$no]."' id='var$no' name='variasi_$no' required> <option value=''>$va[nama]</option>"; 
                                            $varian = explode(';',$va['variasi']);
                                            
                                            if($this->session->level == 'konsumen'){
                                                $cekPaket = $this->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='".($this->session->id_konsumen)."' AND a.paket='on' AND users='konsumen' AND status = 'Y'")->row_array();    
                                                if($rowp['lable_harga']=='harga_level_pedagang'){
                                                    $varian_harga = explode(';',$va['harga_level_pedagang']);
                                                } else if($rowp['lable_harga']=='harga_level_juragan'){
                                                    $varian_harga = explode(';',$va['harga_level_juragan']);
                                                } else if($rowp['lable_harga']=='harga_level_big'){
                                                    $varian_harga = explode(';',$va['harga_level_big']);
                                                } else if($rowp['lable_harga']=='harga_level_bos'){
                                                    $varian_harga = explode(';',$va['harga_level_bos']);
                                                } else {
                                                    $varian_harga = explode(';',$va['harga_level_newbie']);
                                                }
                                            } else {
                                                $varian_harga = explode(';',$va['variasi_harga']);    
                                            }
                                            
                                            for ($i=0; $i<count($varian); $i++) { 
                                                if ($varian_harga[$i]=='0'){ $harga = ""; }else{ $harga = "(+Rp ".rupiah($varian_harga[$i]).")"; }
                                                echo "<option value='".$varian[$i]."' data-value='".($varian_harga[$i]!=''?$varian_harga[$i]:0)."'>".$varian[$i]."</option>";
                                            }
                                            echo "</select></div>";
                                            $no++;
                                        }
                                        
                                    } 
                                echo "</div>";
                                ?>
                            </figure>
                        </div>

                        <div class="ps-product__shopping" style='margin-bottom:0rem; padding-bottom:10px'>
                            <figure class='d-inline mr-2'>
                                <figcaption><b><?= (substr($kat['nama_kategori'],0,1)=='+'?'Durasi/Hari':'Quantity') ?></b></figcaption>
                                <div class="form-group--number refreshx">
                                    <button class="up"><i class="fa fa-plus"></i></button>
                                    <button class="down"><i class="fa fa-minus"></i></button>
                                    
                                    <input style='font-size:20px' min="1" id='qty' class="form-control qty" type="text" value='1' name='qty' onchange="qtyx(<?= $record['id_produk']; ?>)">
                                    
                                </div>
                            </figure>
                            <figure class='d-none d-sm-block'>
                                <?php 
                                    if (stok($record['id_reseller'],$record['id_produk'])<=0){ 
                                        echo "<a style='color:#a7a7a7; background-color:#e2e2e2; margin-right:0px' class='ps-btn ps-btn--black add-to-cart-empty'>$tombol_keranjang</a>";
                                    }else{
                                        echo "<a style='color:#fff !important; margin-right:0px' id='$record[id_produk]' class='ps-btn ps-btn--black add-to-cart'>$tombol_keranjang</a>";
                                    }
                                ?>
                                <!-- <button type='submit' name='keranjang' class="ps-btn ps-btn--black ml-3" href="#">+ Keranjang</button> -->
                                <button type='submit' name='beli' class="ps-btn" href="#" style='margin-right:0px'><?= $tombol_beli; ?></button>
                                <a style='padding-left:10px; padding-right:10px; font-size:19px; margin-right:0px' class="ps-btn" id='save-<?= $record['id_produk']; ?>'> <i class="icon-heart" onclick="save('<?= $record['id_produk']; ?>',this.id)"></i></a>
                            </figure>
                        </div><br>
                        <?php 
                        echo "<div class=''>".nl2br($record['tentang_produk'])."<hr><br></div>"; 
                        $idn = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array(); ?>
                        <!-- Go to www.addthis.com/dashboard to customize your tools --> 
                        <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->
                        <div class="ps-product__specification"><a class="report" target='_BLANK' href="<?php echo "https://api.whatsapp.com/send?phone=".format_telpon($idn['no_telp'])."&amp;text=Hallo%20kak!,%20Saya%20Mau%20Melaporkan%20Produk%20ini%20:%20$record[nama_produk]..."; ?>">Laporkan Penyalahgunaan</a>
                            <!--<p><strong>SKU:</strong> SF1133569600-1</p>-->
                            <p class="categories"><strong> Categories : </strong><a href="<?php echo base_url().'produk/kategori/'.$kat['kategori_seo']; ?>"><?php echo $kat['nama_kategori']; ?></a></p>
                            <?php if (trim($record['tag'])!=''){ echo "<p class='tags'><strong> Tags : </strong> $record[tag]</p>"; } ?>
                        </div>
                        
                    </div>
                </div>
            </form>

            <?php $komentar = $this->db->query("SELECT * FROM tbl_comment where id_produk='$record[id_produk]'")->num_rows(); ?>
                <div class="ps-product__content ps-tab-root">
                    <ul class="ps-tab-list">
                        <li class="active"><a href="#tab-1">Deskripsi</a></li>
                        <li><a href="#tab-3">Penjual</a></li>
                        <li><a href="#tab-4">Ulasan (<?php echo rate_jumlah($record['id_produk']); ?>)</a></li>
                        <li><a href="#tab-5">Diskusi (<?php echo $komentar; ?>)</a></li>
                    </ul>
                    <div class="ps-tabs">
                        <div class="ps-tab active" id="tab-1">
                            <div class="ps-document">
                            <?php echo $record['keterangan']; ?>
                            </div>
                        </div>
                        <div class="ps-tab" id="tab-3">
                            <h4><?php echo cek_paket_icon($rows['id_reseller']).' '.$rows['nama_reseller']; ?></h4>
                            <?php 
                                echo "$rows[keterangan] <hr>
                                        $rows[alamat_lengkap] <br>
                                        ".kecamatan($rows['kecamatan_id'],$rows['kota_id']); 
                            ?>
                        </div>
                        <div class="ps-tab" id="tab-4">
                                <?php 
                                    echo $this->session->flashdata('message_ulasan'); 
                                    $this->session->unset_userdata('message_ulasan');
                                ?>
                            <div class="row">
                                <?php 
                                    $ulasan = $this->db->query("SELECT a.*, b.nama_lengkap, b.foto, b.email FROM `rb_produk_ulasan` a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen where a.id_produk='$record[id_produk]' ORDER BY waktu_kirim DESC"); 
                                    $cek_order = $this->db->query("SELECT a.id_penjualan FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where a.id_produk='$record[id_produk]' AND b.id_pembeli='".$this->session->id_konsumen."' GROUP BY a.id_penjualan");
                                    $cek_ulasan = $this->db->query("SELECT * FROM `rb_produk_ulasan` where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$record[id_produk]'");
                                ?>
                            </div>

                            <div class="ps-post">
                                <?php 
                                    if ($ulasan->num_rows()<=0){
                                        echo "<center style='padding:50px 0px'>
                                            <img style='width:150px' src='".base_url()."asset/images/no-data.png'>
                                                <div class='mt-3'>Maaf Belum ada Ulasan Produk.</div>
                                            </center>";
                                    }
                                    foreach ($ulasan->result_array() as $row) {
                                        echo "<div class='ps-block--comment'>
                                                <div class='ps-block__thumbnail'>";
                                                if (trim($row['foto'])=='' OR !file_exists("asset/foto_user/".$row['foto'])){
                                                    echo "<img src='".base_url()."asset/foto_user/blank.png'>";
                                                }else{
                                                    echo "<img src='".base_url()."asset/foto_user/$row[foto]'>";
                                                }
                                                echo "</div>
                                                <div class='ps-block__content' style='padding:5px 0 0px 20px'>
                                                    <h5 style='margin-bottom:5px; font-size:17px; background:#f3f3f3; padding:3px 5px'>$row[nama_lengkap]<small>".jam_tgl_indo($row['waktu_kirim'])."</small></h5>
                                                    <select class='ps-rating' data-read-only='true'>".rate_bintang_ulasan($row['rating'])."</select>
                                                    <p style='margin-bottom:15px'>".nl2br($row['ulasan'])."</p>
                                                </div>
                                            </div>";
                                    }
                                ?>
                            </div>

                        </div>
                        <div class="ps-tab" id="tab-5">
                            <div class="ps-post">
                            <?php 
                            $tanya_jawab = $this->db->query("SELECT a.*, b.nama_lengkap, b.foto, b.email FROM `tbl_comment` a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen where a.id_produk='$record[id_produk]' AND a.reply='0' ORDER BY id_komentar DESC");
                            if ($tanya_jawab->num_rows()<=0){
                                echo "<center style='padding:50px 0px'>
                                    <img style='width:150px' src='".base_url()."asset/images/no-data.png'>
                                        <div class='mt-3'>Maaf Belum ada Data Diskusi Produk.</div>
                                    </center>";
                            }
                            if ($this->session->id_konsumen!=''){ ?>
                            <form class="ps-form--review" action="<?php echo base_url()."produk/detail/".$this->uri->segment(3); ?>" method="POST">
                                <h4>Kirimkan Pertanyaan.</h4>
                                <div class='form-group'>
                                    <textarea class='form-control' name='pesan' rows='2' placeholder='Tuliskan Pesan disini,..'></textarea>
                                </div>

                                <div class='form-group submit'>
                                    <button type='submit' name='submit_pertanyaan' class='ps-btn'>Kirimkan</button>
                                </div><br>
                            </form>

                                <?php 
                            }
                                    foreach ($tanya_jawab->result_array() as $row) {
                                        echo "<div class='ps-block--comment' style='margin-bottom:20px'>
                                                <div class='ps-block__thumbnail'>";
                                                if (trim($row['foto'])=='' OR !file_exists("asset/foto_user/".$row['foto'])){
                                                    echo "<img src='".base_url()."asset/foto_user/blank.png'>";
                                                }else{
                                                    echo "<img src='".base_url()."asset/foto_user/$row[foto]'>";
                                                }
                                                echo "<button style='padding:.175rem .55rem; color:green' id='click_$row[id_komentar]' class='btn btn-xs'>Balas Pesan</button></div>
                                                <div class=''>
                                                    <h5 style='margin-bottom:5px; font-size:17px; background:#f3f3f3; padding:3px 5px'>$row[nama_lengkap]<small style='float:right'>".jam_tgl_indo($row['tanggal_komentar'].' '.$row['jam_komentar'])."</small></h5>
                                                    <p>".nl2br($row['isi_pesan'])."</p>
                                                </div>
                                            </div>";
                                            
                                            if ($this->session->id_konsumen!=''){
                                                echo "<div id='hidee_$row[id_komentar]' style='display:none'>
                                                <form class='ps-form--review' action='".base_url()."produk/detail/".$this->uri->segment(3)."' method='POST'>
                                                    <div class='form-group'>
                                                        <textarea class='form-control' name='pesan' rows='3' placeholder='Tuliskan Pesan disini,..'></textarea>
                                                    </div>
                                                        <input type='hidden' name='reply' value='$row[id_komentar]'>
                                                    <div class='form-group submit'>
                                                        <button type='submit' name='submit_balasan' class='ps-btn'>Kirimkan Balasan</button>
                                                    </div><br>
                                                </form>
                                                </div>";
                                            }
                                            $tanya_jawab_reply = $this->db->query("SELECT a.*, b.nama_lengkap, b.foto, b.email FROM `tbl_comment` a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen where a.id_produk='$record[id_produk]' AND a.reply='$row[id_komentar]' ORDER BY id_komentar DESC");
                                            foreach ($tanya_jawab_reply->result_array() as $roww) {
                                                echo "<div class='ps-block--comment' style='margin-left:20px'>
                                                        <div class='ps-block__thumbnail'>";
                                                        if (trim($roww['foto'])=='' OR !file_exists("asset/foto_user/".$roww['foto'])){
                                                            echo "<img style='max-width:40px' src='".base_url()."asset/foto_user/blank.png'>";
                                                        }else{
                                                            echo "<img style='max-width:40px' src='".base_url()."asset/foto_user/$roww[foto]'>";
                                                        }
                                                        echo "</div>
                                                        <div class=''>
                                                            <h5 style='border-left:5px solid green; padding-left:10px'>$roww[nama_lengkap] <small>(Reply)</small><small style='float:right'>".jam_tgl_indo($roww['tanggal_komentar'].' '.$roww['jam_komentar'])."</small></h5>
                                                            <p>".nl2br($roww['isi_pesan'])."</p>
                                                        </div>
                                                    </div>";
                                            }
                                    }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="ps-page__right">
            <aside class="widget widget_product widget_features d-none d-sm-block">
                <?php 
                    $banner = $this->model_app->view_where_ordering('banner',array('posisi'=>'produk'),'id_banner','DESC');
                    foreach ($banner as $row) {
                        echo "<p><i class='$row[icon]'></i> <a href='$row[url]' target='_blank'>$row[keterangan]</a></p>";
                    }
                ?>
            </aside>
            <aside class="widget widget_sell-on-site d-none d-sm-block">
                <p><i class="icon-store"></i> Mau Jualan?<a href="<?php echo base_url(); ?>auth/login"> Daftar Sekarang!</a></p>
            </aside>
            <aside class="widget widget_ads d-none d-sm-block">
                <?php
                $pasangiklan2 = $this->model_utama->view_ordering_limit('pasangiklan','id_pasangiklan','ASC',0,2);
                foreach ($pasangiklan2->result_array() as $b) {
                    $string = $b['gambar'];
                    if ($b['gambar'] != ''){
                        if(preg_match("/swf\z/i", $string)) {
                            echo "<embed style='margin-bottom:10px' src='".base_url()."asset/foto_pasangiklan/$b[gambar]' quality='high' type='application/x-shockwave-flash'>";
                        } else {
                            echo "<a href='$b[url]' target='_blank'><img style='margin-bottom:10px' src='".base_url()."asset/foto_pasangiklan/$b[gambar]' alt='$b[judul]' /></a>";
                        }
                    }
                }
                ?>
            </aside>
            <?php 
                $pisah_kata  = explode(",",$record['tag']);
                $jml_katakan = (integer)count($pisah_kata);
                $jml_kata = $jml_katakan-1; 
                $ambil_id = substr($rows['id_produk'],0,4);
                $cari = "SELECT a.*, b.nama_reseller FROM rb_produk a JOIN rb_reseller b ON a.id_reseller=b.id_reseller WHERE (a.id_produk<'$ambil_id') and (a.id_produk!='$ambil_id') and (" ;
                for ($i=0; $i<=$jml_kata; $i++){
                $cari .= "a.tag LIKE '%$pisah_kata[$i]%'";
                if ($i < $jml_kata ){
                $cari .= " OR ";}}
                $cari .= ") ORDER BY RAND() DESC LIMIT 2";
                $hasil  = $this->db->query($cari);

            if ($hasil->num_rows()>=1){
            ?>

            <aside class="widget widget_same-brand">
                <h3>Kategori Sama</h3>
                <div class="widget__content">
                    <?php
                        foreach ($hasil->result_array() as $row) {	
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
                                    $diskon_persen = "<div class='ps-product__badge'>$diskon %</div>"; 
                                }else{
                                    $diskon_persen = ''; 
                                }
                            }
                
                            if ($diskon>=1){ 
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                            }else{
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                            }

                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                            
                            echo "<div class='ps-product'>
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
                                <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                    <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                    ".rate_bintang($row['id_produk'])."
                                        <p class='ps-product__price sale'>".getHargaJual($row['id_produk'])."</p>
                                    </div>
                                    <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                        <p class='ps-product__price sale'>".getHargaJual($row['id_produk'])."</p>
                                    </div>
                                    <small class='text-primary'>".textRekomenJual().rupiah($row['harga_konsumen'])."</small>
                                </div>
                            </div>";
                        }      
                    ?>
                </div>
            </aside>
            <?php } ?>
        </div>
    </div>
    <div class="ps-section--default">
        <div class="ps-section__header">
            <h3>Produk Terkait</h3>
        </div>
        <div class="ps-section__content">
            <div class="ps-carousel--nav owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="true" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">
                <?php
                    $pisah_kata  = explode(",",$record['tag']);
                    $jml_katakan = (integer)count($pisah_kata);
                    $jml_kata = $jml_katakan-1; 
                    $ambil_id = substr($rows['id_produk'],0,4);
                    $cari = "SELECT a.*, b.nama_reseller FROM rb_produk a JOIN rb_reseller b ON a.id_reseller=b.id_reseller WHERE a.id_reseller!='0' AND a.aktif='Y' AND (a.id_produk<'$ambil_id') and (a.id_produk!='$ambil_id') and (" ;
                    for ($i=0; $i<=$jml_kata; $i++){
                    $cari .= "a.tag LIKE '%$pisah_kata[$i]%'";
                    if ($i < $jml_kata ){
                    $cari .= " OR ";}}
                    $cari .= ") ORDER BY a.id_produk DESC LIMIT 10";
                    $hasil  = $this->db->query($cari);
                    
                    if ($hasil->num_rows()>=1){
                        foreach ($hasil->result_array() as $row) {	
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
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                            }else{
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                            }

                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                            
                            echo "<div class='ps-product'>
                                    <div class='ps-product__thumbnail'><a href='".base_url()."produk/detail/$row[produk_seo]'><img src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
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
                                    <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                        <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                        ".rate_bintang($row['id_produk'])."
                                            <br><small class='text-primary'>".textRekomenJual().$harga_produk."</small>
                                            <p class='ps-product__price'>getHargaJual($row[id_produk])</p>
                                        </div>
                                        <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                            <p class='ps-product__price'>getHargaJual($row[id_produk])</p>
                                        </div>
                                    </div>
                                </div>";
                        }
                    }else{
                        $kategori_sama = $this->model_reseller->detail_produk_terbaru(0,0,$record['id_kategori_produk'],10);
                        foreach ($kategori_sama->result_array() as $row) {	
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
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                            }else{
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                            }

                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                            
                            echo "<div class='ps-product'>
                                    <div class='ps-product__thumbnail'><a href='".base_url()."produk/detail/$row[produk_seo]'><img src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
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
                                    <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                        <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                        ".rate_bintang($row['id_produk'])."
                                            <br><small class='text-primary'>".textRekomenJual().rupiah($row['harga_konsumen'])."</small>
                                            <p class='ps-product__price'>".getHargaJual($row['id_produk'])."</p>
                                        </div>
                                        <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                            <p class='ps-product__price'>".getHargaJual($row['id_produk'])."</p>
                                        </div>
                                    </div>
                                </div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade bd-example-modal-lg" id="Keranjang_Notif" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <div class='berhasil_notif'>
                <h3 style='text-align:center'>Berhasil Ditambahkan</h3>
                <div id='berhasil_notif'></div>
                <div class='d-none d-sm-block'>
                <h3>Mungkin anda tertarik :</h3>
                <div class="row" style='height:320px; overflow-y: scroll;'>
                <?php
                    $pisah_kata  = explode(",",$record['tag']);
                    $jml_katakan = (integer)count($pisah_kata);
                    $jml_kata = $jml_katakan-1; 
                    $ambil_id = substr($rows['id_produk'],0,4);
                    $cari = "SELECT a.*, b.nama_reseller FROM rb_produk a JOIN rb_reseller b ON a.id_reseller=b.id_reseller WHERE a.id_produk!='$record[id_produk]' AND a.id_reseller!='0' AND a.aktif='Y' AND (a.id_produk<'$ambil_id') and (a.id_produk!='$ambil_id') and (" ;
                    for ($i=0; $i<=$jml_kata; $i++){
                    $cari .= "a.tag LIKE '%$pisah_kata[$i]%'";
                    if ($i < $jml_kata ){
                    $cari .= " OR ";}}
                    $cari .= ") ORDER BY a.id_produk DESC LIMIT 8";
                    $hasil  = $this->db->query($cari);
                    
                    if ($hasil->num_rows()>1){
                        foreach ($hasil->result_array() as $row) {	
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
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                            }else{
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                            }

                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                            
                            echo "<div class='col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 '>
                                    <div class='ps-product'>
                                    <div class='ps-product__thumbnail' style='height:150px'><a href='".base_url()."produk/detail/$row[produk_seo]'><img style='min-height:140px' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
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
                                    <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                        <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                        ".rate_bintang($row['id_produk'])."
                                            <p class='ps-product__price'>$harga_produk</p>
                                        </div>
                                        <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                            <p class='ps-product__price'>$harga_produk</p>
                                        </div>
                                    </div>
                                </div>
                                </div>";
                        }
                    }else{
                        $kategori_sama = $this->model_reseller->detail_produk_terkait(0,0,$record['id_kategori_produk'],8,$record['id_produk']);
                        foreach ($kategori_sama->result_array() as $row) {	
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
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']-$disk['diskon'])." <del>".rupiah($row['harga_konsumen'])."</del>";
                            }else{
                                $harga_produk =  "Rp ".rupiah($row['harga_konsumen']);
                            }

                            $cek_save = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='".$this->session->id_konsumen."' AND id_produk='$row[id_produk]'")->num_rows();
                            
                            echo "<div class='col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 '>
                                    <div class='ps-product'>
                                    <div class='ps-product__thumbnail' style='height:150px'><a href='".base_url()."produk/detail/$row[produk_seo]'><img  style='min-height:140px' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a>
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
                                    <div class='ps-product__container'><a class='ps-product__vendor' href='".base_url()."u/".user_reseller($row['id_reseller'])."'>".cek_paket_icon($row['id_reseller'])." $row[nama_reseller]</a>
                                        <div class='ps-product__content'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                        ".rate_bintang($row['id_produk'])."
                                            <p class='ps-product__price'>$harga_produk</p>
                                        </div>
                                        <div class='ps-product__content hover'><a class='ps-product__title' href='".base_url()."produk/detail/$row[produk_seo]'>$judul</a>
                                            <p class='ps-product__price'>$harga_produk</p>
                                        </div>
                                    </div>
                                </div></div>";
                        }
                    }
                ?>
                </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal" id="myChat">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title">Chat Penjual</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    
    <!-- Modal body -->
    <form action='<?= $action_chat; ?>' name="formchat" id="formchat" method='get'>
    <div class="modal-body">
        <textarea name='text' id='chat-text' class='form-control' placeholder='Tulis pesan anda,..' style='height:80px !important; margin-bottom:10px' required></textarea>
        <div># <span class='chat-text'>Apakah ini masih ada?</span></div>
        <div># <span class='chat-text'>Hallo, Saya butuh bantuan,..</span></div>
    </div>
    
    <!-- Modal footer -->
    
        <?php if ($action_chat!=''){ ?>
            <div class="modal-footer">
                <button type='submit' class='btn btn-success' style='padding:0 30px !important'>Kirim</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        <?php }else{ 
            echo "<div class='modal-footer' style='display:block'><div class='alert alert-danger text-center'>Maaf, No Seller Untuk Chat via WA belum aktif.</div></div>"; 
        } ?>
    
    </form>
    </div>
</div>
</div>

<script>
$(document).ready(function() { 
    $(".chat-text").click(function() {
        text = $(this).html();
        produk = '#'+$('.produk-title').html();
        $('#chat-text').val('');
        var segments = window.location.href.split( '/' );
        $('#chat-text').val($('#chat-text').val() + text + '\n' +produk);
    });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
$('.down').click(function () {
    if ($("#id_level").val()==0){
        var $input = $(this).parent().find('#qty');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    }
});
$('.up').click(function () {
    if ($("#id_level").val()==0){
        var $input = $(this).parent().find('#qty');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    }
});

$('#qty').on('change', function() {
    if ($("#id_level").val()==0){
        $("#totalx").val($("#totalxx").val()*$("#qty").val());
        hitung();
        console.log('cek')
    }
});

hitung();

$('.level').on('change', function() {
    if ($("#id_level").val()==0){
        $('.up').show();
        $('.down').show();
    }else{
        $('.up').hide();
        $('.down').hide();
    }
  var myarr = $(this).find(':selected').data("value").split(":");
  $("#totalx").val(myarr[0]);

  if (myarr[1]!=0){
    $('.del').hide();
    $('#qty').prop('disabled', true);
    $("#qty").attr("style", "color:red; font-size:20px; border-color:red");
    $('#qty').val(myarr[1]);
  }else{
    $('.del').show();
    $('#qty').prop('disabled', false);
    $("#qty").attr("style", "color:black; font-size:20px");
    $('#qty').val(1);
  }
  //console.log($(".level").val());
  hitung();
});


$('.warna').on('change', function() {
  $("#warnax").val($(this).find(':selected').data("value"));
  hitung();
});

$('.ukuran').on('change', function() {
  $("#ukuranx").val($(this).find(':selected').data("value"));
  hitung();
});

$('.lainnya').on('change', function() {
  $("#lainnyax").val($(this).find(':selected').data("value"));
  hitung();
});

$('#qty').on('input', function() {
  $("#totalx").val($("#totalxx").val()*$("#qty").val());
  hitung();
});

function hitung(){
    var warna=+$('#warnax').val()*$('#qty').val();
    var ukuran=+$('#ukuranx').val()*$('#qty').val();
    var lainnya=+$('#lainnyax').val()*$('#qty').val();
    var total=+$("#totalx").val();
    
    if(isNaN(total)){
        $("#totalharga").html($("#totalx").val());
    }else{
        // $("#totalharga").html(toDuit(warna+ukuran+lainnya+total)); 
        
        var newNominal = toDuit(warna+ukuran+lainnya+total)
        if(`<?php echo $this->session->id_konsumen ?>` == ''){
            
            var newNominal = newNominal.slice(0, 4) + newNominal.slice(4).replaceAll(/[0-9]/g, 'x')
            console.log(newNominal)
        }
        $("#totalharga").html(newNominal);
    }   
    
    
    
}
});

$("[id^='click_']").on("click",function () {
  $('#hidee_'+this.id.split('_')[1]).toggle();
});
</script>

<script>
    var $star_rating = $('.star-rating .fa');
    var SetRatingStar = function() {
    return $star_rating.each(function() {
        if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
        return $(this).removeClass('fa-star-o').addClass('fa-star');
        } else {
        return $(this).removeClass('fa-star').addClass('fa-star-o');
        }
    });
    };

    $star_rating.on('click', function() {
    $star_rating.siblings('input.rating-value').val($(this).data('rating'));
    return SetRatingStar();
    });

    SetRatingStar();
    
    $(document).ready(function() {
    });

    $(".selected").click(function() {
            var selected = $(this).hasClass("highlight");
            $(".selected").removeClass("highlight");
            if(!selected){
            $(this).addClass("highlight");
            }
        
    });

    function qtyx(id){
    var qty = $('#qty').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('produk/qty_produkx')?>",                   
        data:{id:id,qty:qty},     
        dataType : 'json',            
        success: function (data) {
        if (data.value=='0'){
            $('#Modal_Notif').modal('show');
            $('#error_notif').html(data.pesan);
            $(".refreshx").hide().load(" .refreshx").fadeIn();
        }else{
            
        }
        }
    });
    }
</script>