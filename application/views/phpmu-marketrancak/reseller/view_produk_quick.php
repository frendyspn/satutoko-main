<style>
.prc {
    padding: 10px 20px;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: 600;
    color: #000;
    background: #eaeaea;
}
.prc del {
    font-style: normal;
    color: #bcbcbc;
    font-size: 1.3rem;
}
@media (max-width: 998px){
    .item-mobile{
        max-height: 200px;
        overflow: hidden;
        margin-bottom: 20px;
    }
}
</style>
<?php
$rows = $this->db->query("SELECT a.*, b.nama_kota, c.nama_provinsi FROM `rb_reseller` a JOIN rb_kota b ON a.kota_id=b.kota_id
JOIN rb_provinsi c ON b.provinsi_id=c.provinsi_id where a.id_reseller='$record[id_reseller]'")->row_array();
$kat = $this->model_app->view_where('rb_kategori_produk',array('id_kategori_produk'=>$record['id_kategori_produk']))->row_array();
$jual = $this->model_reseller->jual_reseller($record['id_reseller'],$record['id_produk'])->row_array();
$beli = $this->model_reseller->beli_reseller($record['id_reseller'],$record['id_produk'])->row_array();
$disk = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='$record[id_produk]'")->row_array();
$diskon = rupiah(($disk['diskon']/$record['harga_konsumen'])*100,0);
if ($disk['diskon']>0){ $diskon_persen = "<div class='top-right'>$diskon %</div>"; }else{ $diskon_persen = ''; }

if ($disk['diskon']>=1){ 
    $harga_konsumen =  "Rp ".rupiah($record['harga_konsumen']-$disk['diskon']);
    $harga_konsumenx =  $record['harga_konsumen']-$disk['diskon'];
    $harga_asli = "Rp ".rupiah($record['harga_konsumen']);
}else{
    $harga_konsumen =  "Rp ".rupiah($record['harga_konsumen']);
    $harga_konsumenx =  $record['harga_konsumen'];
    $harga_asli = "";
}
?>


                                
<h3 style='font-weight:400;'><?php echo $record['nama_produk'];
if ($record['pre_order']!='' AND $record['pre_order']>0){ echo "<small style='font-size:14px'> (Preorder : <span class='badge badge-danger'>$record[pre_order] Hari</span>)</small>"; }?></h3>
<div class='sub_title'>
    <p><?php echo rate_bintang($record['id_produk']); ?>
        <?php 
        if (rate_jumlah($record['id_produk'])>0){
            echo " <span style='color:#cecece'>(Ada ".rate_jumlah($record['id_produk'])." Ulasan)</span>"; 
        }
        ?> 
    </p>
    <?php 
        if ($record['jenis_produk']=='Fisik'){
            echo "<p>Berat : <a href='#'>".($record['berat']>1000?number_format($record['berat']/1000,1).' Kg':$record['berat'].' Gram')."</a></p>";
        }else{
            echo "<p>Produk Digital</p>";
        } 

    echo "<p>Stok <b>".stok($record['id_reseller'],$record['id_produk'])."</b> $record[satuan]</p>"; ?>
</div>

<div class='row'>
    <div class='col-12 col-md-3'>
        <?php
            if ($record['gambar'] != ''){ 
                $ex = explode(';',$record['gambar']);
                $hitungex = count($ex);
                for($i=0; $i<1; $i++){
                    if (file_exists("asset/foto_produk/".$ex[$i])) { 
                        echo "<div class='item item-mobile'><a href='".base_url()."asset/foto_produk/".$ex[$i]."'><img style='width:100%' src='".base_url()."asset/foto_produk/".$ex[$i]."'></a></div>";
                    }
                }
            }
        ?>
    </div>
    <div class='col-12 col-md-8'>
        <p>
            <i class="icon-store float-left mr-2" style='font-size: 4.7rem; margin-top:-5px;'></i> 
            <a href="<?php echo base_url()."u/".user_reseller($record['id_reseller']).""; ?>">
                <strong style='font-size:18px'> <?php echo $rows['nama_reseller']; ?></strong>
            </a>
            <?php 
                if (config('wa_seller')=='Y'){
                    //echo "<a target='_BLANK' style='text-transform: capitalize; font-size: 9px; background: green; color: #fff; padding: 0px 10px;' class='ps-btn' href='https://api.whatsapp.com/send?phone=".format_telpon($rows['no_telpon'])."&amp;text=Hallo%20kak!%20$rows[nama_reseller],%20Saya%20Mau%20Order%20$record[nama_produk]...'><span class='icon-bubbles'></span> Chat Penjual.</a>";
                    $action_chat = "https://wa.me/".format_telpon($rows['no_telpon']);
                }else{
                    //echo "<a target='_BLANK' style='text-transform: capitalize; font-size: 9px; background: green; color: #fff; padding: 0px 10px;' class='ps-btn' href='".base_url()."members/read/".konsumen($rows['id_reseller'])."/0'><span class='icon-bubbles'></span> Chat Penjual.</a>";
                    $action_chat = base_url()."members/read/".konsumen($rows['id_reseller'])."/0";
                }
                echo "<a target='_BLANK' class='ps-btn ps-chat' href='$action_chat'><span class='icon-bubbles'></span> Chat</a>";
            ?>
            <br>Status : <?php echo verifikasi_icon($record['id_reseller']); ?>
        </p>

        <h4 class="ps-product__price prc" style='margin-bottom: 0px'>
        <?php 
            echo "<input type='hidden' id='group' name='group' value=''>
                <input type='hidden' id='kgroup' name='kgroup' value=''>
                Rp ".rupiah($harga_konsumenx)." <del class='del' style='color:#8a8a8a'>$harga_asli</del>";
                 
            if ($record['minimum']>1){
                echo "<br><span style='color:red; font-size: 1.3rem;'>Minimal Order <b>$record[minimum]</b> $record[satuan]</span>";
            }
        ?>

        <a style='padding-left:10px; padding-right:10px; font-size:19px; margin-right:0px; padding-bottom: 5px; margin-top: -5px;' class="ps-btn float-right d-block d-sm-none" id='save-<?= $record['id_produk']; ?>'> <i class="icon-heart" onclick="save('<?= $record['id_produk']; ?>',this.id)"></i></a>
        
        </h4>
        <div class="ps-product__variations">
            <?php 
            echo "<figure>";
                $variasi = $this->db->query("SELECT * FROM rb_produk_variasi where id_produk='$record[id_produk]' ORDER BY id_variasi ASC");
                $level_order = $this->db->query("SELECT * FROM rb_produk_level where id_produk='$record[id_produk]'");
                echo "<div class='form-row'>";
                    if ($variasi->num_rows()>0){ 
                        echo "<div style='display:block; width:100%; font-weight:bold; margin-left:5px; margin-top:10px'>Varian Produk</div>";
                        $no = 1;
                        $varname = array('','warna','ukuran','lainnya');
                        foreach ($variasi->result_array() as $va) {
                            echo "<div class='form-group col-md-6 col-6' style='margin-bottom:0px'> <select class='form-control ".$varname[$no]."' id='var$no' name='variasi_$no' required> <option value=''>$va[nama]</option>"; 
                            $varian = explode(';',$va['variasi']);
                            $varian_harga = explode(';',$va['variasi_harga']);
                            for ($i=0; $i<count($varian); $i++) { 
                                if ($varian_harga[$i]=='0'){ $harga = ""; }else{ $harga = "(+Rp ".rupiah($varian_harga[$i]).")"; }
                                echo "<option value='".$varian[$i]."' data-value='".($varian_harga[$i]!=''?$varian_harga[$i]:0)."'>".$varian[$i]."</option>";
                            }
                            echo "</select></div>";
                            $no++;
                        }
                        
                    } 
                echo "</div>
            </figure><br>";
            echo ($record['tentang_produk'])."<hr><br>"; 
            $idn = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array(); ?>
            <div class="ps-product__specification">
                <a class="report float-right text-danger" target='_BLANK' href="<?php echo "https://api.whatsapp.com/send?phone=".format_telpon($idn['no_telp'])."&amp;text=Hallo%20kak!,%20Saya%20Mau%20Melaporkan%20Produk%20ini%20:%20$record[nama_produk]..."; ?>"><i class='icon-warning'></i> Laporkan</a>
                <!--<p><strong>SKU:</strong> SF1133569600-1</p>-->
                <p class="categories m-0"><strong> Categories : </strong><a href="<?php echo base_url().'produk/kategori/'.$kat['kategori_seo']; ?>"><?php echo $kat['nama_kategori']; ?></a></p>
                <?php if (trim($record['tag'])!=''){ echo "<p class='tags'><strong> Tags : </strong> $record[tag]</p>"; } ?>
            </div>

        </div>

    </div>
</div>

                        