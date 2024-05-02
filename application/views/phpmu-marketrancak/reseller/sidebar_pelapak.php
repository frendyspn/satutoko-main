<style>
.card-widget {
    border: 0;
    position: relative;
}
.card {
    box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
    margin-bottom: 1rem;
}
.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
}
.widget-user .widget-user-header {
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    height: 135px;
    padding: 1rem;
    text-align: center;
}

.widget-user .widget-user-image {
    left: 50%;
    margin-left: -45px;
    position: absolute;
    top: 80px;
}
.widget-user .card-footer {
    padding-top: 50px;
}
.card-footer {
    padding: 0.75rem 1.25rem;
    background-color: rgba(0,0,0,.03);
    border-top: 0 solid rgba(0,0,0,.125);
}
.widget-user .widget-user-username {
    font-size: 25px;
    font-weight: 300;
    margin-bottom: 0;
    margin-top: 0;
    text-shadow: 0 1px 1px rgb(0 0 0 / 20%);
}
.text-right {
    text-align: right!important;
    color:#000
}
.widget-user .widget-user-desc {
    margin-top: 0;
}
.text-right {
    text-align: right!important;
}
</style>

<?php 
  if (trim($rows['foto'])==''){ $foto = 'toko.jpg'; }else{ $foto = $rows['foto']; }
  $ex = explode(' ', $rows['tanggal_daftar']);
  $sukses = $this->db->query("SELECT * FROM rb_penjualan where id_penjual='$rows[id_reseller]' AND status_penjual='reseller' AND proses!='0'");
  $total_produk = $this->db->query("SELECT * FROM rb_produk where id_reseller='$rows[id_reseller]' and aktif='Y'")->num_rows();
  //$pelanggan = $this->db->query("SELECT * FROM rb_penjualan where id_penjual='$rows[id_reseller]' AND status_pembeli='konsumen' AND status_penjual='reseller' GROUP BY id_pembeli");
?>
<div class="ps-section__left">
  <div class="ps-block--vendor">

      <div class="card card-widget widget-user">
          <div class="widget-user-header text-white" style="background: #cecece url('https://members.phpmu.com/asset/css/img/flower-swirl10.png') center center;">
              <h3 class="widget-user-username text-right"><?= $rows['nama_reseller']; ?></h3>
              <h5 class="widget-user-desc text-right"><?= $rows['user_reseller']; ?></h5>
          </div>

          <div class="widget-user-image">
              <img class="rounded-circle" style='width:90px' src="<?php echo base_url()."asset/foto_user/$foto"; ?>" alt="User Avatar">
          </div>

          <div class="card-footer">
          <div class="row">
              <div class="col-sm-12">
                  <div class="description-block">
                      <h5 style='margin-bottom:0px' class="description-header">ID Toko (<?= (verifikasi_icon($rows['id_reseller'])); ?>)</h5>
                      <span style='font-size:16px;' class="description-text"><?php echo str_replace(':','',str_replace('-','',$rows['tanggal_daftar'])).'-'.sprintf("%04d", $rows['id_reseller']); ?></span>
                  </div>
              </div>
          </div>
          </div>
      </div>


      <div class="ps-block__container">
          <div class="ps-block__header">
            <?php 
                    if (config('wa_seller')=='Y'){  
                      $action_chat = "https://wa.me/".format_telpon($rows['no_telpon']);
                    }else{
                        $action_chat = base_url()."members/read/".konsumen($rows['id_reseller'])."/0";
                    }
                    echo "<a target='_BLANK' class='ps-btn ps-chat ps-btn--fullwidth' href='$action_chat'><span class='icon-bubbles'></span> Chat</a>";
            ?>
            
            <span class="ps-block__divider"><br></span>

            <div class="br-wrapper br-theme-fontawesome-stars">
            <p><?php echo $rows['alamat_lengkap']; ?>, 
              <b><?php echo kecamatan($rows['kecamatan_id'],$rows['kota_id']); ?></b></p>
          
              <p class='m-0'>Produk : <strong><?= $total_produk; ?></strong> </p>
              <p class='m-0'>Penjualan : <strong><?= $sukses->num_rows(); ?></strong> </p>
              <p class='m-0'>Rating & Ulasan : <strong style='color:#000'><?= rating_produk($rows['id_reseller']); ?></strong> </p>
              <p class='m-0'>Join : <strong><?= jam_tgl_indo($rows['tanggal_daftar']); ?></strong> </p>
              <span class="ps-block__divider"></span>
              <aside class="widget widget_shop">
                <h4 class="widget-title">Kategori</h4>  
                  <ul>
                    <?php 
                      echo "<li><a style='border-bottom:1px dotted #cecece; display:block' href='".base_url()."u/$rows[user_reseller]'>Semua Produk  <span class='pull-right'>($total_produk)</span></a>";
                      $kategori = $this->db->query("SELECT a.id_kategori_produk, b.* FROM rb_produk a JOIN rb_kategori_produk b ON a.id_kategori_produk=b.id_kategori_produk where a.id_reseller='$rows[id_reseller]' GROUP BY a.id_kategori_produk");
                      foreach ($kategori->result_array() as $row) {
                        $total = $this->db->query("SELECT * FROM rb_produk where id_kategori_produk='$row[id_kategori_produk]' AND id_reseller='$rows[id_reseller]'")->num_rows();
                        echo "<li><a style='border-bottom:1px dotted #cecece; display:block' href='".base_url()."u/$rows[user_reseller]?k=$row[kategori_seo]'>$row[nama_kategori]  <span class='pull-right'>($total)</span></a>";
                      }
                    ?>
                    </ul>
              </aside>
              
              <div class="ps-block__content">
              <?php echo nl2br($rows['keterangan']); ?>
              <span class="ps-block__divider"></span>
          </div>
            </div>
          </div>
      </div>
  </div>
</div>
