<?php 
$proses = '<i class="text-danger">Pending</i>'; 
?>
<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li><a href="<?php echo base_url()."produk"; ?>">Produk </a></li>
            <li><?php echo $title; ?></li>
        </ul>
    </div>
</div>
<div class="ps-section--shopping ps-shopping-cart">
    <div class="container">
      <?php 
          echo $this->session->flashdata('message'); 
          $this->session->unset_userdata('message');
      ?>
        <div class="ps-section__content refreshx">
            <div class="table-responsive">
            <div class='keranjang-all'>
                <div class="row">
                <?php 
                  if ($this->session->idp == ''){
                    echo "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                    <div style='padding:5%; text-align:center'>
                            <div><img style='width:160px' src='".base_url()."asset/images/shopping-empty.png'></div><br>
                            <span class='text-danger'>Maaf, Keranjang belanja anda saat ini masih kosong,...</span><br>
                            <a href='".base_url()."produk'>Klik Disini Untuk mulai Belanja!</a></div></div>";
                  }else{
                    echo "<div class='col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 '>
                      <form action='".base_url()."produk/keranjang' method='POST'>";
                      
                        //echo "<div class='show_cart_detail'></div>";
                        $i = 1;
                        $penjualx = $this->db->query("SELECT a.*, e.nama_reseller, c.nama_kota, e.id_reseller 
                                      FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk 
                                      JOIN rb_reseller e ON b.id_reseller=e.id_reseller
                                      JOIN rb_kota c ON e.kota_id=c.kota_id  where a.session='".$this->session->idp."' GROUP BY e.id_reseller");
                        if ($penjualx->num_rows()<=0){
                          echo "<center style='padding:40px 0px'><p><h4>Wah keranjang belanjaanmu kosong!</h4>
                          Daripada dianggurin, mending isi dengan barang-barang impianmu. Yuk, <a href='".base_url()."produk'><u>cek sekarang!</u></a></p><br>
                          <img width='200px' src='".base_url()."asset/images/shopping-empty.png'>
                          </center>";
                        }
                        foreach ($penjualx->result_array() as $rowx) {
                          echo "<div class='toko-list'>
                                    <div class='toko-name'>".cek_paket_icon($rowx['id_reseller'])." <b>$rowx[nama_reseller]</b> <br>
                                          <span class='fa fa-map-marker' style='font-size:16px; color:transparent'></span> <span style='color:green'>Dikirim dari  ".reseller_kota($rowx['id_reseller'])."</span>
                                    </div>";

                                    $drecord = $this->db->query("SELECT a.*, b.*, c.nama_reseller FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where b.id_reseller='$rowx[id_reseller]' AND a.session='".$this->session->idp."' ORDER BY id_penjualan_detail ASC");
                                    
                                    foreach ($drecord->result_array() as $row) {
                                    $kat = $this->model_app->view_where('rb_kategori_produk',array('id_kategori_produk'=>$row['id_kategori_produk']))->row_array();
                                      $ex = explode(';', $row['gambar']);
                                      if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                                      $harga_produk =  "Rp ".rupiah($row['harga_jual']-$row['diskon']);
                                      if ($row['id_level']!='0'){
                                        $level = $this->model_app->view_where('rb_produk_level',array('id_level'=>$row['id_level']))->row_array();
                                        $nama_level = "<span style='color:red; background:#ffd9d9; padding: 3px 7px 4px 7px;' class='badge badge-secondary'>$level[nama_level]</span>";
                                        $disabled_level = "readonly";
                                        $css_level = "color:red; border-color:red; cursor:no-drop;";
                                        $harga_produkx = "= <b style='color:green'>Rp ".rupiah(($row['harga_jual']-$row['diskon'])*$row['jumlah'])."</b>";
                                      }else{
                                        $nama_level = "<span class='badge badge-secondary'>".(substr($kat['nama_kategori'],0,1)=='+'?'Harian':'Satuan')."</span>";
                                        $disabled_level = "";
                                        $css_level = "";
                                        $harga_produkx = "x <b>$harga_produk</b>";
                                      }
                                      
                                      echo "<div class='ps-product--cart-mobile' style='padding:5px 0'>
                                            <input type='hidden' name='id$i' value='$row[id_penjualan_detail]'>
                                            <input type='hidden' name='idp$i' value='$row[id_produk]'>

                                            <label class='container-checkbox'>
                                              <input type='checkbox' name='pilih$i' id='checkbox$row[id_penjualan_detail]' onclick=\"checked_produk($row[id_penjualan_detail]);\" ".($row['checked']=='Y'?'checked':'').">
                                              <span class='checkmark'></span>
                                            </label>
                                              <div class='ps-product__thumbnail'>
                                                <img style='float:left; width:100px; margin-right:10px' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'>
                                              </div>
                                              <div class='ps-product__content'>
                                                <a href='".base_url()."produk/keranjang?del=$row[id_penjualan_detail]' class='ps-product__remove item_delete' style='cursor:pointer'><i class=icon-cross></i></a>";
                                                if ($row['pre_order']!='' AND $row['pre_order']>0){
                                                  echo "<p style='margin-bottom:0'> <span class='badge badge-secondary' style='background-color:#9be092; color:#006903'>Pre-order $row[pre_order] Hari</span> </p>";
                                                }
                                                echo "<a href='".base_url()."produk/detail/$row[produk_seo]'>
                                                  <span style='font-size:15px; display:block; font-weight:400'>$row[nama_produk] $nama_level</span>
                                                </a>
                                                <p style='border-bottom:1px dotted #cecece; margin-bottom:0px'><b>Qty.</b> 
                                                <small><input type='number' class='qty_update' min='1' name=qty$i value='$row[jumlah]'  id='qty$row[id_penjualan_detail]' onchange=\"qty($row[id_penjualan_detail]);\" style='display:inline-block; margin-bottom:3px; width:50px; text-align: center; $css_level'  autocomplete=off $disabled_level> $harga_produkx</small></p>";

                                                  if (trim($row['keterangan_order'])!=''){
                                                    echo "<b>Variasi</b> : $row[keterangan_order]<br>";
                                                  }
                                                echo "<input type='text' name='keterangan$i' value='$row[catatan]' style='display:inline-block; margin-bottom:3px; width:100%; border:1px dotted #cecece' placeholder='Tulis Catatan untuk Produk ini' autocomplete=off>

                                                </div>
                                            </div>";
                                      $i++;
                                    }
                          echo "</div>";
                                      
                        }
                    ?>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                        <div class="ps-block--shopping-total">
                        <div class='keranjang-page refresh'>
                            <div class="ps-block__header">
                                <p style='margin-bottom:0px'><b>Status Order<span> <?php echo $proses; ?></span></b></p><hr>
                                <p style='margin-bottom:0px'>Berat<span> <?php echo "$total[total_berat] Gram"; ?></span></p>
                                <?php 
                                  $ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
                                  $fv = explode('|',$ref['keterangan']);
                                  if ($fv[0]>'0'){
                                    $fee_admin = $fv[0];
                                    echo "<p style='margin-bottom:0px'>Fee Admin <span>Rp ".rupiah($fv[0])."</span></p>";
                                  }else{
                                    $fee_admin = 0;
                                  }
                                ?>
                                <p>Subtotal <span> <?php echo "Rp ".rupiah($total['total']); ?></span></p>
                            </div>
                            <div class="ps-block__content">
                                <h3>Total <span><?php echo "Rp ".rupiah(($total['total'])+$fee_admin); ?></span></h3>
                            </div><br>
                            
                        </div>
                        </div>
                        <button type='submit' id='oksimpan' name='update' style='padding:15px 0; font-size:16px' class='ps-btn ps-btn--fullwidth'>Lanjut ke Pembayaran <i class='icon-arrow-right'></i></button>
                    </div>
                    
              <?php } ?>
                </div>
                </div>
                </div>
        </div>
        <hr>

    </div>
</div>

<script type="text/javascript">
function checked_produk(id){
  if ($('#checkbox'+id).is(':checked')) {
    valuex = 'Y';
  }else{
    valuex = 'N';
  }
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('produk/checked_produk')?>",                   
    data:{id:id,valuex:valuex},                 
    success: function (data) {
      $(".refresh").hide().load(" .refresh").fadeIn();
    }
  });
}

function qty(id){
  var qty = $('#qty'+id).val();
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('produk/qty_produk')?>",                   
    data:{id:id,qty:qty},     
    dataType : 'json',            
    success: function (data) {
      if (data.value=='0'){
        $('#Modal_Notif').modal('show');
        $('#error_notif').html(data.pesan);
        $(".refreshx").hide().load(" .refreshx").fadeIn();
      }else{
        $(".refresh").hide().load(" .refresh").fadeIn();
        
      }
    }
  });
}
</script> 