<div class="ps-breadcrumb">
        <div class="ps-container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li>Semua Toko</li>
            </ul>
        </div>
    </div>
<section class="ps-store-list" style='padding-top:30px;'>
  <div class="container">
      <div class="ps-section__content">
          <div class="ps-section__search row">
              <div class="col-md-12">
                <?php 
                  echo "<div class='form-group'>";
                  echo "<select class='form-control theSelect' name='cari_reseller' onchange=\"this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);\" required>";
                  echo "<option value=0>- Pilih Kota / Kabupaten -</option>";
                  $kota = $this->db->query("SELECT * FROM rb_kota ORDER BY nama_kota ASC");
                    foreach ($kota->result_array() as $rows) {
                      if ($_GET['s']==$rows['nama_kota']){
                        echo "<option value='".base_url()."produk/reseller?s=$rows[nama_kota]' selected>$rows[nama_kota]</option>";
                      }else{
                        echo "<option value='".base_url()."produk/reseller?s=$rows[nama_kota]'>$rows[nama_kota]</option>";
                      }
                    }
                  echo "</select>
                        <button type='submit' name='submit'></button>";
                  echo "</div>";
                ?>
              </div>
          </div>
          <?php 
            if (isset($_POST['submit'])){
              echo "<div class='alert alert-info'><b>Pencarian : </b>".filter($this->input->post('cari_reseller'))."</div>";
            } 
          ?>
          <div class="row">
              <?php 
                $no = 1;
                foreach ($record->result_array() as $row){
                  $produk = $this->db->query("SELECT * FROM rb_produk where id_reseller='$row[id_reseller]'")->num_rows();
                  $sukses = $this->db->query("SELECT * FROM rb_penjualan where id_penjual='$row[id_reseller]' AND status_penjual='reseller' AND proses!='0'");
                  if (!file_exists("asset/foto_user/$row[foto]") OR $row['foto']==''){ $foto_user = "toko.jpg"; }else{ $foto_user = $row['foto']; }
                  if (trim($row['kota_id'])==''){ 
                      $kota = '<i style="color:red">Kota Tidak Ada..</i>'; 
                  }else{
                      $alamat_kota = kecamatan($row['kecamatan_id'],$row['kota_id']); 
                      $exp = explode(',',$alamat_kota);
                      $kota = "<b>".$exp[0].", ".$exp[1]."</b>";
                  }
                  if (config('wa_seller')=='Y'){  
                    $action_chat = "https://wa.me/".format_telpon($row['no_telpon']);
                  }else{
                    $action_chat = base_url()."members/read/".konsumen($row['id_reseller'])."/0";
                  }
                  echo "<div class='col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 '>
                      <article class='ps-block--store-2'>
                          <div class='ps-block__content bg--cover' data-background='".base_url()."asset/images/default-store-banner.png' style='background: url(&quot;".base_url()."asset/images/default-store-banner.png&quot;);'>
                              <figure>
                                <h4 style='background:#f7f7f7; padding:10px; border-bottom:2px solid #26901b; color:#000'>$row[nama_reseller]</h4>
                                <p style='color:#000 !important; height: 70px; overflow: hidden;'>$row[alamat_lengkap], $kota<br>
                                +".format_telpon($row['no_telpon'])."</p>

                                <p class='m-0'>Penjualan : <strong class='text-success'>".$sukses->num_rows()."</strong> </p>
                                <p class='m-0'>Join : <strong class='text-success'>".jam_tgl_indo($row['tanggal_daftar'])."</strong> </p>

                                <h4 class='mt-3' style='text-align:right'>Ada <span class='badge badge-secondary' style='background-color:#ff2e2e'>$produk</span> Produk</h4>
                              </figure>
                          </div>
                          <div class='ps-block__author'>
                            <a class='ps-block__user' href='#'><img src='".base_url()."asset/foto_user/$foto_user' alt=''></a><a class='ps-btn ps-btn--black' style='background-color: #26901b' href='".base_url()."u/$row[user_reseller]'><span class='fa fa-search'></span></a>";
                            if ($row['tokopedia']!=''){ echo "<a style='border: 1px solid #8a8a8a; border-radius: 5px; padding: 6px 10px; margin-right:2px' target='_BLANK' href='$row[tokopedia]'><img style='width:20px' src='".base_url()."asset/images/tokopedia.png'></a>"; }
                            if ($row['shoopee']!=''){echo "<a style='border: 1px solid #8a8a8a; border-radius: 5px; padding: 6px 10px; margin:2px' target='_BLANK' href='$row[shoopee]'><img style='width:20px' src='".base_url()."asset/images/shoopee.png'></a>"; }
                            echo "<a target='_BLANK' style='background:#fff' class='ps-btn ps-chat' href='$action_chat'><span class='icon-bubbles'></span></a>
                          </div>
                      </article>
                  </div>";
                }
              ?>
              
          </div>
          <div class="ps-pagination">
                <?php echo $this->pagination->create_links(); ?>
              </div>
      </div>
  </div>
</section>


