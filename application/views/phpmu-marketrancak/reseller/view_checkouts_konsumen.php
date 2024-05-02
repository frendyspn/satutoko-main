<style>.line1{height:12px;margin:10px 10px 10px 0;animation:pulse 1s infinite ease-in-out;-webkit-animation:pulse 1s infinite ease-in-out}.line2{height:12px;margin:10px 10px 10px 0;animation:pulse 1s infinite ease-in-out;-webkit-animation:pulse 1s infinite ease-in-out}.line3{height:34px;margin:10px 10px 10px 0;animation:pulse 1s infinite ease-in-out;-webkit-animation:pulse 1s infinite ease-in-out}@keyframes pulse{0%{background-color:rgba(165,165,165,.1)}50%{background-color:rgba(165,165,165,.3)}100%{background-color:rgba(165,165,165,.1)}}@-webkit-keyframes pulse{0%{background-color:rgba(165,165,165,.1)}50%{background-color:rgba(165,165,165,.3)}100%{background-color:rgba(165,165,165,.1)}}</style>
<?php 
$this->session->unset_userdata('sopir1');
$proses = '<i class="text-danger">Pending</i>'; 
$total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-(a.diskon*a.jumlah)) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."' AND a.checked='Y'")->row_array();
$kupon = $this->db->query("SELECT sum(b.nilai_kupon) as diskon FROM `rb_penjualan_temp` a JOIN rb_produk_kupon b ON a.id_kupon=b.id_kupon where a.session='".$this->session->idp."'")->row_array();
$kupon_pusat = $this->db->query("SELECT b.nilai_kupon as diskon FROM rb_penjualan_temp a JOIN rb_produk_kupon b ON a.id_kupon_lain=b.id_kupon where a.session='".$this->session->idp."' GROUP BY a.id_kupon_lain")->row_array();
$cekk = $this->db->query("SELECT jenis_produk FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->id_konsumen."' GROUP BY b.jenis_produk")->row_array();
?>
<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="<?php echo base_url()."produk/keranjang"; ?>">Keranjang <?php echo $this->session->sopir1; ?></a></li>
            <li><?php echo $title; ?></li>
        </ul>
    </div>
</div>
<div class="ps-section--shopping ps-shopping-cart">
    <div class="container">
        <div class="ps-section__content">
            <div class="table-responsive">
              <?php echo "<form action='".base_url()."produk/selesai_belanja' id='formid' method='POST'>"; ?>
                <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 ">
                <?php 
                  echo $this->session->flashdata('message'); 
                  $this->session->unset_userdata('message');

                  $kon = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
                  if ($this->session->sesi_alamat=='' OR $this->session->sesi_alamat=='0'){
                    $alamat = $this->db->query("SELECT '(Alamat Utama)' as 'alamat_utama', 0 as 'idl', z.nama_lengkap, z.no_hp, z.alamat_lengkap, z.kordinat_lokasi, a.subdistrict_id as kecamatan_id, a.subdistrict_name as kecamatan, b.city_name as kota, c.province_name as provinsi FROM `rb_konsumen` z LEFT JOIN `tb_ro_subdistricts` a ON z.kecamatan_id=a.subdistrict_id LEFT JOIN tb_ro_cities b ON z.kota_id=b.city_id LEFT JOIN tb_ro_provinces c ON z.provinsi_id=c.province_id where z.id_konsumen='".$this->session->id_konsumen."'")->row_array();
                  }else{
                    $alamat = $this->db->query("SELECT '(Alamat Lainnya)' as 'alamat_utama', z.id_konsumen_alamat as idl, z.nama_lengkap, z.no_hp, z.alamat_lengkap, z.kordinat_lokasi, a.subdistrict_id as kecamatan_id, a.subdistrict_name as kecamatan, b.city_name as kota, c.province_name as provinsi FROM `rb_konsumen_alamat` z LEFT JOIN `tb_ro_subdistricts` a ON z.kecamatan_id=a.subdistrict_id LEFT JOIN tb_ro_cities b ON z.kota_id=b.city_id LEFT JOIN tb_ro_provinces c ON z.provinsi_id=c.province_id where z.id_konsumen_alamat='".$this->session->sesi_alamat."'")->row_array();
                  }

                    echo "<div class='judulmu'>".($cekk['jenis_produk']=='Fisik'?'Data Pengiriman':'Data Pembeli')."</div>

                            <div id='alamat_kirim' class='alert alert-success' style='margin:0px 15px 20px 15px; background:#fff'>
                              <div class='lazy'>
                                <div class='line1' style='width:50%'></div>
                                <div class='line2' style='width:30%'></div>
                                <div class='line3'></div>
                              </div>
                              <div id='show_alamat'></div>
                              
                              <a class='btn btn-success' href='#' data-toggle='modal' data-target='#pilihAlamat'>Lihat Alamat Lain</a> 
                              <a class='btn btn-primary' href='#' data-toggle='modal' data-target='#tambahAlamat'>Tambahkan Alamat Baru</a>
                            </div>";
                            echo "<div class='judulmu'>Data Produk</div>";
                            $i = 1;
                            $noo = 1;
                            $penjualx = $this->db->query("SELECT a.*, e.nama_reseller, e.kordinat, e.alamat_lengkap, e.keterangan, e.kecamatan_id, e.kota_id, e.pilihan_kurir, b.id_reseller, c.nama_kota, d.nama_provinsi
                                          FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk 
                                          JOIN rb_reseller e ON b.id_reseller=e.id_reseller
                                          JOIN rb_kota c ON e.kota_id=c.kota_id 
                                          JOIN rb_provinsi d ON c.provinsi_id=d.provinsi_id where a.session='".$this->session->idp."' AND a.checked='Y' GROUP BY e.id_reseller");
                            foreach ($penjualx->result_array() as $rowx) {
                              $ber = $this->db->query("SELECT sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."' AND b.id_reseller='$rowx[id_reseller]' AND a.checked='Y'")->row_array();
                              $kota_asal[] = $rowx['kota_id'];
                              $berat[] = $ber['total_berat'];
                              echo "<div class='toko-list'>
                                        <div class='toko-name'>".cek_paket_icon($rowx['id_reseller'])." <b>$rowx[nama_reseller]</b> <br>
                                            <input type='hidden' name='toko$noo' value='$rowx[id_reseller]'>
                                            <input type='hidden' id='lokasi_penjual$noo' value='$rowx[kordinat]'>
                                              <small>".($cekk['jenis_produk']=='Fisik'?'Dikirim':'Penjual')." dari  <b>".reseller_kota($rowx['id_reseller'])."</b></small>
                                        </div>
                                        
                                        <div class='row'>
                                        <div class='col-md-7 col-12 list-produk'>";
                                        $drecord = $this->db->query("SELECT a.*, b.*, c.nama_reseller FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where b.id_reseller='$rowx[id_reseller]' AND a.session='".$this->session->idp."' AND a.checked='Y' ORDER BY id_penjualan_detail ASC");
                                        foreach ($drecord->result_array() as $row) {
                                          $ex = explode(';', $row['gambar']);
                                          if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                                          $re = $this->model_app->view_where('rb_reseller',array('id_reseller'=>$row['id_reseller']))->row_array();
                                          $sub_total = (($row['harga_jual']-$row['diskon'])*$row['jumlah']);
                                          $ex = explode(';', $row['gambar']);
                                          if ($row['pre_order']!='' AND $row['pre_order']>0){
                                            $pre_order = "<span class='badge badge-secondary' style='background-color:#9be092; color:#006903'>Pre-Order $row[pre_order] Hari</span> <br>";
                                          }else{
                                            $pre_order = "";
                                          }

                                          if ($row['id_level']!='0'){
                                            $level = $this->model_app->view_where('rb_produk_level',array('id_level'=>$row['id_level']))->row_array();
                                            $nama_level = "<span style='color:red; background:#ffd9d9; padding: 3px 7px 4px 7px;' class='badge badge-secondary'>$level[nama_level]</span>";
                                            $harga_produkx = "= <b style='color:green'>Rp ".rupiah(($row['harga_jual']-$row['diskon'])*$row['jumlah'])."</b>";
                                          }else{
                                            $nama_level = "";
                                            $harga_produkx = "x Rp ".rupiah($row['harga_jual']-$row['diskon']);
                                          }
                                          
                                          echo "<div class='ps-product--cart-mobile' style='padding:5px 0'>
                                                  <input type='hidden' name='id$no' value='$row[id_penjualan_detail]'> 
                                                  <div class='ps-product__thumbnail'>
                                                    <img style='float:left; width:100px; margin-right:10px' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'>
                                                  </div>
                                                  <div class='ps-product__content'>
                                                    $pre_order <a href='".base_url()."produk/detail/$row[produk_seo]'>
                                                      <span style='font-size:15px; font-weight:400'>$row[nama_produk]</span> $nama_level
                                                    </a><br>";
                                                  if ($row['jenis_produk']=='Fisik'){
                                                    echo "<small style='font-size:11px'>$row[jumlah] Barang (".($row['berat']>1000?number_format($row['berat']/1000,1).' Kg':$row['berat'].' Gram').") </small> <b>$harga_produkx</b><br>";
                                                  }else{
                                                    echo "<small style='font-size:11px'>$row[jumlah] Barang </small> <b>$harga_produkx</b><br>";
                                                  }
                                                    if ($row['keterangan_order']!='' OR trim($row['catatan'])!=''){
                                                        echo "<div style='border:1px dashed green; padding:2px 5px'>";
                                                        if ($row['keterangan_order']!=''){
                                                          echo "<span>Variasi</span> <i style='color:#8a8a8a'>: $row[keterangan_order]</i><br>";
                                                        }
                                                        if (trim($row['catatan'])!=''){
                                                          echo "<span>Catatan</span> <i style='color:#8a8a8a'>: $row[catatan]</i>";
                                                        }
                                                        echo "</div>";
                                                      }
                                                    echo "</div>
                                                </div>";
                                          $i++;
                                        }
                              echo "</div>

                              <div class='col-md-5 col-12'>";
                                if ($cekk['jenis_produk']=='Fisik'){
                                  // Ongkir dan Kurir jika produk fisik
                                    echo "<p style='font-size:1.6rem; font-weight:600; margin-bottom:0px'><i class='fa fa-car'></i> Pilih Pengiriman</p>
                                    <input type='hidden' id='list_kecamatan_dari$noo' value='$rowx[kecamatan_id]'>";
                                    if (config('kurir_nasional')=='Y'){
                                      echo "<select class='form-control form-mini text-success' name='kode_kurir$noo' id='list_kurir$noo' style='margin-bottom: 2px;background: #28a745; border-radius: 5px; color: #fff !important;'>
                                          <option value='0'>Pengiriman Nasional</option>";
                                          $kurir_data = $this->model_app->view_ordering('rb_kurir','id_kurir','ASC');
                                          if ($rowx['pilihan_kurir']==''){
                                            foreach ($kurir_data as $rowk) {
                                              echo "<option value='$rowk[kode_kurir]'>$rowk[nama_kurir]</option>";
                                            }
                                          }else{
                                            $kurir_terpilih = explode(',',$rowx['pilihan_kurir']);
                                            foreach ($kurir_data as $rowk) {
                                              foreach ($kurir_terpilih as $select_option){
                                                if($rowk['id_kurir'] == $select_option) {
                                                  echo "<option value='$rowk[kode_kurir]'>$rowk[nama_kurir]</option>";
                                                  break;
                                                }
                                              }
                                            }
                                          }

                                      echo "</select>";
                                    }

                                    if (config('kurir_lokal')=='Y'){
                                      echo "<select class='form-control form-mini text-success' name='kode_sopir$noo' id='list_sopir$noo' style='margin-bottom:2px; border-radius: 5px;".($rowx['kordinat']!=''?'':'color:#8a8a8a !important')."' ".($rowx['kordinat']!=''?'':'disabled').">
                                        <option value='0'>".($rowx['kordinat']!=''?'Pengiriman Lokal':'Tidak tersedia')."</option>";
                                        if ($rowx['kordinat']!=''){
                                          $kurir_sopir = $this->model_app->view_ordering('rb_jenis_kendaraan','id_jenis_kendaraan','ASC');
                                          foreach ($kurir_sopir as $rowk) {
                                            echo "<option value='$rowk[id_jenis_kendaraan]'>$rowk[jenis_kendaraan]</option>";
                                          }
                                        }
                                      echo "</select>";
                                    }
                                    
                                    if (config('kurir_lainnya')=='Y'){
                                      echo '<ul class="list-group list-group-flush">
                                              <li id="service-lainnya" class="list-group-item clearall-kurir" style="cursor:pointer; margin-bottom:5px; padding-bottom: 5px; padding:6px 1.25rem; border-radius:3px; line-height: 1; border: 1px solid #e3e3e3;" onclick="klikongkir'.$noo.'(\'Marketplace Lainnya\',\'Marketplace Lainnya\',\'0\',\'0\',this.id)">
                                              <span style="color:red;">Marketplace lain (Upload Resi)</span>
                                        </li>
                                      </ul>';
                                    }
                                    
                                    

                                    if (config('kurir_toko')=='Y'){
                                      echo "<label style='display:block; cursor:pointer; margin-bottom:0rem'>
                                        <input type='checkbox' name='kurirx$noo' class='kurir$noo' value='cod'/> <span style='display:inline-block'>COD dengan Kurir Toko</span>
                                      </label>";
                                    }

                                    if (config('kurir_lokal')=='Y'){
                                      echo "<ul class='list-group list-group-flush'>
                                        <div id='list_sopir_div$noo'></div>
                                      </ul>";
                                    }
                                    
                                    if (config('kurir_nasional')=='Y'){
                                      echo "<ul class='list-group list-group-flush'>
                                          <div id='list_kurir_div$noo'></div>
                                          <div id='loader$noo' style='display:none'>
                                            <center><img src='".base_url()."asset/images/loading.gif'></center>
                                          </div>
                                      </ul>";
                                    }

                                    if (config('kurir_toko')=='Y'){
                                      echo "<ul class='list-group list-group-flush' id='kurir-list$noo'>";
                                      if ($this->session->id_konsumen==''){
                                        $cod = $this->db->query("SELECT * FROM rb_reseller_cod where id_reseller='$rowx[id_reseller]'");
                                      }else{
                                        $ress = $this->model_reseller->penjualan_konsumen_detail($this->session->idp)->row_array();
                                        $cod = $this->db->query("SELECT * FROM rb_reseller_cod where id_reseller='$rowx[id_reseller]'");
                                      }
                                      $service = 1;
                                      foreach ($cod->result_array() as $ros) {
                                        echo '<li id="'.$noo.$idn.'serv-'.$service.'" class="list-group-item clearall-kurir" style="cursor:pointer; margin:1px; padding-bottom: 5px; padding:5px 1.25rem; line-height: 1; border: 1px solid #e3e3e3; border-left: 3px solid red;" onclick="klikongkir'.$noo.'(\'COD (Cash on delivery)\',\''.$ros['nama_alamat'].'\',\''.$ros['biaya_cod'].'\',\''.number_format($ros['biaya_cod'],0).'\',this.id)">
                                                  <span style="color:black;">COD - '.$ros['nama_alamat'].'</span><br><small><b>Tarif.</b> <b style="color:red">Rp '.number_format($ros['biaya_cod'],0).'</b> - Bayar Di tempat</small>
                                            </li>';
                                        $service++;
                                      }
              
                                      if ($cod->num_rows()<=0){
                                        echo "<center style='color:red'>COD Tidak Tersedia!</center>";
                                      }
                                    }
                
                                    echo "</ul>";

                                }else{
                                  
                                }
                              echo "</div>

                              </div>
                              </div>";
                              $noo++;       
                            }
                ?>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                    <div class="ps-block--shopping-total">
                        <div class="ps-block__content">
                            <div class="ps-block__header" style='border-bottom:none; margin-bottom:10px'>
                                <?php if ($cekk['jenis_produk']=='Fisik'){ ?>
                                <p style='margin-bottom:0px'>Berat<span> <?php echo "$total[total_berat] Gram"; ?></span></p>
                                <p style='margin-bottom:0px'>Ongkos Kirim <span> <input type='text' id='ongkir_view' style='background:none; text-align:right; width:110px' value='0' disabled/></span></p>
                                <?php } ?>

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
                                <p style='margin-bottom:0px'>Subtotal <span> <?php echo "Rp ".rupiah($total['total']-$total['diskon_total']); ?></span></p>

                                <div class="form-group--nest" style='margin-top:10px'>
                                    <input class="form-control" name='kode_kupon' id='kode_kupon' type="text" placeholder="Kode Kupon / Voucher">
                                    <button type='button' id='submit_kupon' class="ps-btn"><span class='fa fa-check'></span></button>
                                </div>
                                <div class='kupon_list'></div>
                                <div class='kupon_list_pusat'></div>
                                <a style='margin-top:10px' href='#' data-toggle='modal' data-target='#promo' class='ps-btn ps-btn--outline btn-block'><span style='font-size:18px; margin-right:4px' class='fa fa-percent text-success'></span> Makin Hemat Pakai promo <i class='fa fa-angle-right float-right'></i></a>
                            </div>

                            <label style='border:1px solid #cecece; padding:5px 10px; cursor:pointer' class='d-block'>
                            <i class='fa fa-credit-card' style='font-size: 35px; margin-right: 5px; float: left'></i>
                            <input class='float-right' style='margin-top: 10px' type='checkbox' name='metode' id='saldo'  value='<?= saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen); ?>'>  <b>Pakai Saldo</b> 
                            <small style='display:block'>Saldo saat ini <span style='color:red'><?php echo "Rp ".rupiah(saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen)); ?></span></small></label>
                            
                            <?php if ($cekk['jenis_produk']=='Fisik'){ ?>
                              <hr><p style='font-size:1.6rem; font-weight:600; margin-bottom:0px'>Kirim Sebagai Dropshipper</p>
                              <?php 
                                $dropshipp = array('Tidak','Ya');
                                echo "<select class='form-control form-mini' name='dropshipp' id='dropshipp'>";
                                for ($i=0; $i < count($dropshipp); $i++) { 
                                  echo "<option value='".$dropshipp[$i]."'>".$dropshipp[$i]."</option>";
                                }
                                echo "</select>
                                
                                <input type='text' class='form-control form-mini dropshipp' name='nama_dropshipp' placeholder='Nama Pengirim....'>
                                <input type='text' class='form-control form-mini dropshipp' name='telp_dropshipp' placeholder='No. Telepon Pengirim....'>";
                              }
                              
                              echo "<span class='resix'><hr>
                                    <p style='font-size:1.6rem; font-weight:600; margin-bottom:0px'>Resi Marketplace Lain <span style='float:right' class='btn btn-danger' id='service-lainnya-close'>x</span></p>
                                    
                                    <input type='text' class='form-control form-mini resi' name='no_resi' placeholder='No Resi....'>
                                    
                                      <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                                      <div id='status'></div>
                                      <div style='clear:both'><br></div>
                                    </span>";
                            ?>

                            <br><span class='reff'><h3>Tagihan <span id='totalbayar'></span></h3></span>
                        </div>
                    </div>
                    <?php if ($cekk['jenis_produk']=='Fisik'){ ?>
                      <button type='submit' name='submit' id='oksimpan' style='display: none' class="ps-btn ps-btn--fullwidth">Proses Pembayaran</a>
                      <button type='button' id='oksimpanx' style='background:#e3e3e3; color:#000 !important; border:1px solid #000' class="ps-btn ps-btn--fullwidth oksimpanx">Proses Pembayaran</a>
                    <?php }else{ ?>
                      <button type='submit' name='submit' id='oksimpan_digital' style='display: block' class="ps-btn ps-btn--fullwidth">Proses Pembayaran</a>
                    <?php } ?>
                </div>
                </div>
                <?php 
                  echo "<input type='hidden' id='fee_admin' value='$fee_admin'/>
                        <input type='hidden' id='kupon' value='".($kupon_pusat['diskon']==''?0:$kupon_pusat['diskon'])."'/>
                        <input type='hidden' name='totalx' id='totalx' value='".($total['total']+$fee_admin)."'/>
                        <input type='hidden' name='total' id='total' value='".(($total['total']+$fee_admin)-$kupon['diskon'])."'/>";
                        if ($cekk['jenis_produk']=='Fisik'){
                          echo "<input type='hidden' placeholder='- - - - - -' name='ongkir' id='ongkir' style='color:red; border:1px solid #f4f4f4; text-align:center' value=''/>";
                        }else{
                          echo "<input type='hidden' name='ongkir' id='ongkir' style='color:red' value='0'/>";
                        }
                        echo "<input type='hidden' name='berat' value='$total[total_berat]'/>
                        <input type='hidden' name='diskonnilai' id='diskonnilai' value='$diskon_total'/>
                        <input type='hidden' style='color:blue; border:1px solid #f4f4f4; text-align:center' name='ongkir1' id='ongkir1' value='0'/>
                        <input type='hidden' name='service1' id='service1'/>
                        <input type='hidden' name='kurir1' id='kurir1'/>
                        <input type='hidden' style='color:blue; border:1px solid #f4f4f4; text-align:center' name='ongkir2' id='ongkir2' value='0'/>
                        <input type='hidden' name='service2' id='service2'/>
                        <input type='hidden' name='kurir2' id='kurir2'/>
                        <input type='hidden' style='color:blue; border:1px solid #f4f4f4; text-align:center' name='ongkir3' id='ongkir3' value='0'/>
                        <input type='hidden' name='service3' id='service3'/>
                        <input type='hidden' name='kurir3' id='kurir3'/>
                        <div class='kota'></div>";
                ?>
                </form>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="modal fade" id="pilihAlamat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pilih Alamat Pengiriman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style='padding:1rem 30px 30px'>
      <a class='btn btn-primary btn-block' data-dismiss="modal" style='margin-bottom:10px; padding:5px; font-size:14px;background: #cecece;color: #000;border: 1px solid #afafaf;' href='#' data-toggle='modal' data-target='#tambahAlamat'>Tambahkan Alamat Baru</a>
        <div style='height:340px; overflow:scroll;'>
              <?php 
                $alamat_list = $this->db->query("SELECT z.id_konsumen_alamat, '(Alamat Lainnya)' as 'alamat_utama', z.id_konsumen_alamat as idl, z.nama_lengkap, z.no_hp, z.alamat_lengkap, z.kordinat_lokasi, a.subdistrict_id as kecamatan_id, a.subdistrict_name as kecamatan, b.city_name as kota, c.province_name as provinsi FROM `rb_konsumen_alamat` z JOIN `tb_ro_subdistricts` a ON z.kecamatan_id=a.subdistrict_id JOIN tb_ro_cities b ON z.kota_id=b.city_id JOIN tb_ro_provinces c ON z.provinsi_id=c.province_id where z.id_konsumen='".$this->session->id_konsumen."'");
                echo "<div class='alert alert-success' style='margin:0px 15px 20px 15px; background-color: #ffffff;'>
                      <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12'>
                        <b>$kon[nama_lengkap]</b> <small>(Alamat Utama)</small>
                        </div>
                        </div>
        
                        <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12'>
                        ".substr($kon['no_hp'], 0, -2)."xx
                        </div>
                        </div>
                        
                        <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12' style='color:#8a8a8a'>
                        $kon[alamat_lengkap] ($kon[kordinat_lokasi]) <br>
                        ".kecamatan($kon['kecamatan_id'],$kon['kota_id'])."
                        </div>
                    </div>
                    <a class='btn btn-success pilihAlamat' href='#' data-alamat='0'>Pilih Alamat Ini</a> 
                    </div>";

                foreach ($alamat_list->result_array() as $rx) {
                  echo "<div class='alert alert-success' style='margin:0px 15px 20px 15px; background-color: #ffffff;'>
                        <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12'>
                        <b>$rx[nama_lengkap]</b>
                        </div>
                        </div>
        
                        <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12'>
                        ".substr($rx['no_hp'], 0, -2)."xx
                        </div>
                        </div>
                        
                        <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12' style='color:#8a8a8a'>
                        $kon[alamat_lengkap] ($rx[kordinat_lokasi]) <br>
                        ".kecamatan($rx['kecamatan_id'],$rx['kota_id'])."
                        </div>
                    </div>
                    <a class='btn btn-success pilihAlamat' data-alamat='$rx[id_konsumen_alamat]' href='#'>Pilih Alamat Ini</a>
                    </div>";
                }
              ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="tambahAlamat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Alamat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style='padding:1rem 30px 30px'>
      <form>
        <div class="form-group">
          <label>Alamat Lengkap</label>
          <textarea class="form-control" id='alamat_lengkapx' name='alamat_lengkapx' style='margin-bottom:5px' required></textarea>
          <div class='form-row'>
              <div class='form-group col-md-4' style='margin-bottom:5px'>
                  <select class='form-control form-mini' name='list_provinsi' id='list_provinsi' required></select>
              </div>
              <div class='form-group col-md-4' style='margin-bottom:5px'>
                  <select class='form-control form-mini' name='list_kotakab' id='list_kotakab' required></select>
              </div>
              <div class='form-group col-md-4' style='margin-bottom:5px'>
                  <select class='form-control form-mini' name='list_kecamatanx' id='list_kecamatanx' required></select>
              </div>
          </div>
        </div>

        <div class="form-group">
          <label>Nama Penerima</label>
          <input type="text" class="form-control" id='nama_lengkap' name='nama_lengkap' value='<?= $kon['nama_lengkap']; ?>' autocomplete='off' required>
        </div>

        <div class="form-group">
          <label>Nomor HP</label>
          <input type="number" id='no_hp' name='no_hp' class="form-control" value='<?= $kon['no_hp']; ?>' autocomplete='off' required>
        </div>

        <div class="form-group">
          <label>Kordinat Lokasi</label>
          <input type='text' class='form-control form-mini btn-geolocationx' value='<?= $kon['kordinat_lokasi']; ?>' name='kordinat_lokasi' id='kordinat_lokasi' autocomplete='off' />
          <label class='switch mr-1 mt-2'>
            <input type='checkbox' name='alamat_lainx' id='alamat_lain'> Cari Kordinat anda dari Peta <small style='color:#cecece'>(Untuk Pengiriman Kurir Lokal)</small>
          </label>
          <div class='show-map'>
              <div id='mapid' class='shadow-sm'></div>
          </div>
        </div>
        
        <button type="submit" id='submit' class="btn btn-primary">Simpan</button>
        <a href='#' data-dismiss="modal" class="btn btn-default" style='border:1px solid #b7b7b7; padding:3px 30px; background:#fbfbfb'>Batal</a>
      </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="promo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pilih / Pakai Promo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style='padding:1rem 30px 30px'>
      <form>
      
        <?php 
        $promo_list = $this->db->query("SELECT * FROM rb_produk_kupon where id_produk='0'");
        if($promo_list->num_rows()<=0){
          echo "<div style='height:240px;'>
                <center style='padding:50px 0px'>Belum ada Kupon promo!</center>";
        }else{
          echo "<div style='height:340px; overflow:scroll;'>";
        }
        foreach ($promo_list->result_array() as $pl) {
          if (berakhir($pl['expire_date'],1)>=0){
            if (berakhir($pl['expire_date'],1)=='0'){ $expired = "<span style='color:orange'>Berakhir Kurang dari 1 hari</span>"; }else{ $expired = berakhir($pl['expire_date'],0); }
            echo "<div class='alert alert-success' style='margin:10px 10px 10px 0px; background-color: #ffffff'>
                  <label style='cursor:pointer' class='d-block'>
                    <input class='float-right' style='margin-top: 10px' type='radio' name='promo' id='kodeKupon' value='$pl[kode_kupon]'>
                    <b style='color:#000'>$pl[keterangan] (Rp ".rupiah($pl['nilai_kupon']).")</b>
                    <small style='color:#8a8a8a; display:block'><i class='fa fa-hand-o-right'></i> $pl[catatan]</small>
                    <small style='color:#8a8a8a; display:block'><i class='fa fa-clock-o'></i> $expired</small>
                  </label>
                  </div>";
          }else{
            echo "<div class='alert alert-success' style='margin:10px 10px 10px 0px; background-color: #e3e3e3'>
                    <b style='color:#8a8a8a'>$pl[keterangan] (Rp ".rupiah($pl['nilai_kupon']).")</b>
                    <small style='color:#8a8a8a; display:block'><i class='fa fa-hand-o-right'></i> $pl[catatan]</small>
                    <small style='color:#8a8a8a; display:block'><i class='fa fa-clock-o'></i> ".berakhir($pl['expire_date'],0)."</small>
                  </div>";
          }
        }
        
        echo "</div><br>";
        if($promo_list->num_rows()>=1){
          echo "<button type='submit' id='submitKupon' class='btn btn-primary'>Pakai Promo</button>
                <a href='#' data-dismiss='modal' class='btn btn-default' style='border:1px solid #b7b7b7; padding:3px 30px; background:#fbfbfb'>Batal</a>";
        }
      ?>

      </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
var settings = {
    url: "<?php echo base_url().$this->uri->segment(1); ?>/upload",
    formData: {id: "<?php echo $this->session->id_konsumen; ?>"},
    dragDrop: false, 
	  maxFileCount:1,
    fileName: "uploadFile",
	  maxFileSize:5000*1024,
    allowedTypes:"jpg,png,jpeg,gif",		
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDone:false,
    showDelete:true,
    deleteCallback: function(data,pd) {
        $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile",{op: "delete", name:data},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");   
            });
        for(var i=0;i<data.length;i++) {
            $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile",{op:"delete",name:data[i]},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");  
            });
        }   
        pd.statusbar.hide();
    }   
}
$("#mulitplefileuploader").uploadFile(settings);
});

$(document).ready(function(){
$('#formid').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});

show_alamat(); 
$('.lazy').hide();

$( ".pilihAlamat" ).click(function(event) {
    var id = $(this).data('alamat');
    $.ajax({
      type: "POST",
      url: "<?php echo site_url('members/pilih_alamat')?>",                   
      data:{id:id},                 
      success: function (data) {
        $('#pilihAlamat').modal('hide');
        kurir_hide();
        hitung();
      },
		complete: function(){
      show_alamat(); 
			$('.lazy').hide();
		}
    });
});

function show_alamat(){
  $.ajax({
      type  : 'ajax',
      url   : '<?php echo site_url('members/alamat_terpilih'); ?>',
      method: 'POST',
      mode: 'cors',
      dataType : 'json',
      contentType: "application/json",
      success : function(data){
          var html = '';
          var i;
          console.log(data);
          for(i=0; i<data.length; i++){
            html += '<input type="hidden" value="'+data[i].kordinat_lokasi+'" id="lokasi_pembeli"> <input type="hidden" name="idl" id="idl" value="'+data[i].idl+'"> <input type="hidden" id="list_kecamatan" value="'+data[i].kecamatan_id+'">'+
                    '<div class="form-group row" style="margin-bottom:5px"><div class="col-sm-12">'+
                    '<b>'+data[i].nama_lengkap+'</b> <small>'+data[i].alamat_utama+'</small></div></div>'+

                    '<div class="form-group row" style="margin-bottom:5px"><div class="col-sm-12">'+
                    ''+data[i].no_hp+'</div></div>'+

                    '<div class="form-group row" style="margin-bottom:5px"><div class="col-sm-12" style="color:#8a8a8a">'+
                    ''+data[i].alamat_lengkap+' ('+data[i].kordinat_lokasi+')<br>'+ 
                    ''+data[i].kecamatan+', '+data[i].kota+', '+data[i].provinsi+'</div></div>';
          }
          $('#show_alamat').html(html);
      },
		complete: function(){
			$('.lazy').hide();
		}
  });
}

 //Save product
 $('#submit').on('click',function(){
    var nama_lengkap = $('#nama_lengkap').val();
    var no_hp = $('#no_hp').val();
    var alamat_lengkap = $('#alamat_lengkapx').val();
    var provinsi_id = $('#list_provinsi').val();
    var kota_id = $('#list_kotakab').val();
    var kecamatan_id = $('#list_kecamatanx').val();
    var kordinat_lokasi = $('#kordinat_lokasi').val();
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('members/tambah_alamat')?>",
        dataType : "JSON",
        data : {nama_lengkap:nama_lengkap, no_hp:no_hp, alamat_lengkap:alamat_lengkap, provinsi_id:provinsi_id, kota_id:kota_id, kecamatan_id:kecamatan_id, kordinat_lokasi:kordinat_lokasi},
        success: function(data){
          if (data=='0'){
            $('#tambahAlamat').modal('hide');
            $('#Modal_Notif').modal('show');
            $('#error_notif').html('GAGAL! Pastikan telah mengisi semua inputan dengan benar');
            console.log('gagal');
          }else{
            $('[name="nama_lengkap"]').val("");
            $('[name="no_hp"]').val("");
            $('[name="alamat_lengkapx"]').val("");
            $('[name="list_provinsi"]').val("");
            $('[name="list_kotakab"]').val("");
            $('[name="list_kecamatanx"]').val("");
            $('[name="kordinat_lokasi"]').val("");
            $('#tambahAlamat').modal('hide');
            kurir_hide();
            hitung();
            console.log('berhasil');
          }
        },
		complete: function(){
      console.log('complete');
      show_alamat(); 
			$('.lazy').hide();
			// $('#show_alamat').show();
		}
    });
    return false;
});

  $(".dropshipp").attr("style", "display:none");
  var dropshipp = jQuery('#dropshipp');
  dropshipp.change(function () {
      if ($(this).val() == 'Ya') {
        $(".dropshipp").attr("style", "display:block; margin:3px 0px; border:1px solid #000;");
        $(".dropshipp").prop('required',true);
      }else{
        $(".dropshipp").attr("style", "display:none");
        $(".dropshipp").removeAttr('required');
      }
  });
  
  $(".resi").attr("style", "display:none");
  $(".resix").attr("style", "display:none");
  var serviceLainnya = jQuery('#service-lainnya');
  serviceLainnya.click(function () {
    $(".resi").attr("style", "display:block; margin:3px 0px; border:1px solid #000;");
    $(".resi").prop('required',true);
    $(".resix").attr("style", "display:block; margin:3px 0px");
  });

  var serviceLainnyax = jQuery('#service-lainnya-close');
  serviceLainnyax.click(function () {
    $(".resi").attr("style", "display:none");
    $(".resi").removeAttr('required');
    $(".resix").attr("style", "display:none");
  });

  show_kupon_list();
  show_kupon_pusat();
  function show_kupon_list(){
      $.ajax({
          url   : '<?php echo site_url("members/kupon_list"); ?>',
          type  : 'GET',
          async : true,
          dataType : 'json',
          success : function(data){
              var html = '';
              var i;
              for(i=0; i<data.length; i++){
                  html += '<p style="margin-bottom:0px">'+
                          '<a href="javascript:void(0);" class="ps-product__remove kupon_delete" style="cursor:pointer" data-id_penjualan_detail="'+data[i].id_penjualan_detail+'"><i style="color:red" class="fa fa-remove"></i></a> '+
                          '<b style="color:green">'+data[i].kode_kupon+'</b>'+
                          '<span>Rp -'+toRupiah(data[i].nilai_kupon)+'</span></p>';
              }
              $('.kupon_list').html(html);
          }
      });
  } 

  function show_kupon_pusat(){
      $.ajax({
          url   : '<?php echo site_url("members/kupon_list_pusat"); ?>',
          type  : 'GET',
          async : true,
          dataType : 'json',
          success : function(data){
              var html = '';
              var i;
              for(i=0; i<data.length; i++){
                  html += '<p style="margin-bottom:0px">'+
                          '<a href="javascript:void(0);" class="ps-product__remove kupon_deletex" style="cursor:pointer" data-id_penjualan_detail="'+data[i].id_penjualan_detail+'"><i style="color:red" class="fa fa-remove"></i></a> '+
                          '<b style="color:green">'+data[i].keterangan+'</b>'+
                          '<span>Rp -'+toRupiah(data[i].nilai_kupon)+'</span></p>';
              }
              $('.kupon_list_pusat').html(html);
          }
      });
  } 

  function sum_kupon_list(){
      $.ajax({
          url   : '<?php echo site_url("members/kupon_list_sum"); ?>',
          type  : 'GET',
          async : true,
          dataType : 'json',
          success : function(data){
              var i;
              for(i=0; i<data.length; i++){
                tot = $('#totalx').val();
                hasil = tot-data[i].total_nilai_kupon;
                $('#total').val(hasil);
              }
              hitung();
          }
      });
  } 

  function sum_kupon_pusat(){
      $.ajax({
          url   : '<?php echo site_url("members/kupon_list_sum_pusat"); ?>',
          type  : 'GET',
          async : true,
          dataType : 'json',
          success : function(data){
              $('#kupon').val(data.total_nilai_kupon);
              hitung();
          }
      });
  } 

  $('#submit_kupon').on('click',function(){
    var kode_kupon = $('#kode_kupon').val();
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('members/kupon_used')?>",
        dataType : "JSON",
        data : {kode_kupon:kode_kupon},
        success: function(data){
          if(data==true){
            $('[name="kode_kupon"]').val("");
            show_kupon_list();
            sum_kupon_list();
            hitung();
          }else{
            if (data.pesan=='x'){
                $('[name="kode_kupon"]').val("");
                $('#kupon').val(data.nominal);
                show_kupon_pusat();
                hitung();
            }else{
              $('#Modal_Notif').modal('show');
              $('#error_notif').html(data.pesan);
              // alert(data.pesan);
            }
          }
          //console.log(data);
        }
    });
    return false;
  });

  $('#submitKupon').on('click',function(){
    var kode_kupon = $("input[type=radio][name=promo]:checked").val();
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('members/kupon_used')?>",
        dataType : "JSON",
        data : {kode_kupon:kode_kupon},
        success: function(data){
          if(data==true){
            $('[name="kodeKupon"]').val("");
            $('#promo').modal('hide');
            show_kupon_list();
            sum_kupon_list();
            hitung();
          }else{
            if (data.pesan=='x'){
                $('[name="kodeKupon"]').val("");
                $('#promo').modal('hide');
                $('#kupon').val(data.nominal);
                show_kupon_pusat();
                hitung();
            }else{
              $('#Modal_Notif').modal('show');
              $('#error_notif').html(data.pesan);
              // alert(data.pesan);
            }
          }
          //console.log(data);
        }
    });
    return false;
  });

  $('.kupon_list').on('click','.kupon_delete',function(){
    var id = $(this).data('id_penjualan_detail');
      $.ajax({
          type : "POST",
          url  : "<?php echo site_url('members/kupon_cart_delete')?>",
          dataType : "JSON",
          data : {id:id},
          success: function(data){
            show_kupon_list();  
            sum_kupon_list();
            hitung();
          }
      });
      return false;
  });

  $('.kupon_list_pusat').on('click','.kupon_deletex',function(){
    var id = $(this).data('id_penjualan_detail');
      $.ajax({
          type : "POST",
          url  : "<?php echo site_url('members/kupon_cart_deletex')?>",
          dataType : "JSON",
          data : {id:id},
          success: function(data){
            show_kupon_pusat();
            $('#kupon').val('0');
            hitung();
          }
      });
      return false;
  });

});



/*$(document).ready(function(){
    $("#submit").on("click", function(){
    var a = parseInt($('#a').val());
    var b = parseInt($('#b').val());
        var sum = a + b;
        $("#ongkir").val(sum);
    })
})*/
$("#form_alamat").hide();

$("#kurir-list1").hide();
$(".kurir1").change(function(){
    $("#kurir-list1").toggle();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});


$("#kurir-list2").hide();
$(".kurir2").change(function(){
    $("#kurir-list2").toggle();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

$("#kurir-list3").hide();
$(".kurir3").change(function(){
    $("#kurir-list3").toggle();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

function kurir_hide(){
  $("#ongkir").val('');
  $("#ongkir_view").val('0');
  $("#list_kurir_div1").hide();
  $("#list_kurir_div2").hide();
  $("#list_kurir_div3").hide();

  $("#list_sopir_div1").hide();
  $("#list_sopir_div2").hide();
  $("#list_sopir_div3").hide();
  
  $("#kurir-list1").hide();
  $("#kurir-list2").hide();
  $("#kurir-list3").hide();

  $('.kurir1').prop('checked', false);
  $('.kurir2').prop('checked', false);
  $('.kurir3').prop('checked', false);
  
  $("#list_kurir1").val('0');
  $("#list_kurir2").val('0');
  $("#list_kurir3").val('0');

  $("#list_sopir1").val('0');
  $("#list_sopir2").val('0');
  $("#list_sopir3").val('0');
  
}

function klikongkir1(data,detail,harga,harga_rp,id){
  $("#ongkir1").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir1");
  $(".clearall-kurir").removeClass("selected-ongkir10");
  $('#'+id).addClass("selected-ongkir1");
  $('#service1').val(detail);
  $('#kurir1').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();

  hitung();
}

function klikongkir2(data,detail,harga,harga_rp,id){
  $("#ongkir2").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir2");
  $(".clearall-kurir").removeClass("selected-ongkir11");
  $('#'+id).addClass("selected-ongkir2");
  $('#service2').val(detail);
  $('#kurir2').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  hitung();
}

function klikongkir3(data,detail,harga,harga_rp,id){
  $("#ongkir3").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir3");
  $(".clearall-kurir").removeClass("selected-ongkir12");
  $('#'+id).addClass("selected-ongkir3");
  $('#service3').val(detail);
  $('#kurir3').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  hitung();
}


function klikongkir10(data,detail,harga,harga_rp,id){
  $("#ongkir1").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir10");
  $(".clearall-kurir").removeClass("selected-ongkir1");
  $('#'+id).addClass("selected-ongkir10");
  $('#service1').val(detail);
  $('#kurir1').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();
  hitung();
}

function klikongkir11(data,detail,harga,harga_rp,id){
  $("#ongkir2").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir11");
  $(".clearall-kurir").removeClass("selected-ongkir2");
  $('#'+id).addClass("selected-ongkir11");
  $('#service2').val(detail);
  $('#kurir2').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();
  hitung();
}

function klikongkir12(data,detail,harga,harga_rp,id){
  $("#ongkir3").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir12");
  $(".clearall-kurir").removeClass("selected-ongkir3");
  $('#'+id).addClass("selected-ongkir12");
  $('#service3').val(detail);
  $('#kurir3').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();
  hitung();
}

$(document).ready(function(){
//* select Provinsi */
var base_url    = "<?php echo base_url();?>";
$.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_provinsi',
    data: {},
    dataType  : 'html',
    success: function (data) {
        $("#list_provinsi").html(data);
    }
});
/* select Provinsi */

$("#list_provinsi").change(function(){
    var id_province = this.value;
    kota(id_province);
    $("#div_kota").show();
});

/* select Kota */
kota = function(id_province){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kota',
    data: {id_province:id_province},
    dataType  : 'html',
    success: function (data) {
        $("#list_kotakab").html(data);
    },
    beforeSend: function () {
        
    },
    complete: function () {
      
    }
});
}

$("#list_kotakab").change(function(){
    var id_kota = this.value;
    kecamatan(id_kota);
    $("#div_kecamatan").show();
});

kecamatan = function(id_kota){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kecamatan',
    data: {id_kota:id_kota},
    dataType  : 'html',
    success: function (data) {
        $("#list_kecamatanx").html(data);
    }
});
}


$("#list_sopir1").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    var id_kecamatan_dari      = $("#list_kecamatan_dari1").val();
    var lokasi = $("#lokasi_pembeli").val();
    var lokasi_penjual = $("#lokasi_penjual1").val();
    sopircost1(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual);
    $("#div_sopir1").show();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

sopircost1 = function(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/sopir_get_cost/10/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_dari:id_kecamatan_dari,kecamatan_tujuan:id_kecamatan,lokasi: lokasi, lokasi_penjual: lokasi_penjual},
    dataType  : 'html',
    success: function (data) {
      $("#list_sopir_div1").show();
      $("#list_sopir_div1").html(data);
    }
});
}

$("#list_sopir2").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    var id_kecamatan_dari      = $("#list_kecamatan_dari2").val();
    var lokasi = $("#lokasi_pembeli").val();
    var lokasi_penjual = $("#lokasi_penjual2").val();
    sopircost2(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual);
    $("#div_sopir2").show();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

sopircost2 = function(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/sopir_get_cost/11/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_dari:id_kecamatan_dari,kecamatan_tujuan:id_kecamatan,lokasi: lokasi, lokasi_penjual: lokasi_penjual},
    dataType  : 'html',
    success: function (data) {
        $("#list_sopir_div2").show();
        $("#list_sopir_div2").html(data);
        console.log(lokasi);
    }
    
});
}

$("#list_sopir3").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    var id_kecamatan_dari      = $("#list_kecamatan_dari3").val();
    var lokasi = $("#lokasi_pembeli").val();
    var lokasi_penjual = $("#lokasi_penjual3").val();
    sopircost3(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual);
    $("#div_sopir3").show();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

sopircost3 = function(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/sopir_get_cost/12/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_dari:id_kecamatan_dari,kecamatan_tujuan:id_kecamatan,lokasi: lokasi, lokasi_penjual: lokasi_penjual},
    dataType  : 'html',
    success: function (data) {
        $("#list_sopir_div3").show();
        $("#list_sopir_div3").html(data);
    }
});
}

$("#list_kurir1").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    cost1(id_kurir,id_kecamatan);
    $("#div_kurir1").show();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

cost1 = function(id_kurir,id_kecamatan){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_cost/1/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_tujuan:id_kecamatan},
    dataType  : 'html',
    beforeSend: function(){
        $("#loader1").show();
        $("#list_kurir_div1").hide();
    },
    success: function (data) {
        $("#list_kurir_div1").html(data);
    },
    complete:function(response){
        // Hide image container
        $("#loader1").hide();
        $("#list_kurir_div1").show();
    }
});
}

$("#list_kurir2").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    cost2(id_kurir,id_kecamatan);
    $("#div_kurir2").show();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

cost2 = function(id_kurir,id_kecamatan){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_cost/2/<?php echo $kota_asal[1]; ?>/<?php echo $berat[1]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_tujuan:id_kecamatan},
    dataType  : 'html',
    beforeSend: function(){
        $("#loader2").show();
        $("#list_kurir_div2").hide();
    },
    success: function (data) {
        $("#list_kurir_div2").html(data);
    },
    complete:function(response){
        // Hide image container
        $("#loader2").hide();
        $("#list_kurir_div2").show();
    }
});
}

$("#list_kurir3").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    cost3(id_kurir,id_kecamatan);
    $("#div_kurir3").show();
    $(".resix").hide();
    $(".resi").removeAttr('required');
});

cost3 = function(id_kurir,id_kecamatan){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_cost/3/<?php echo $kota_asal[2]; ?>/<?php echo $berat[2]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_tujuan:id_kecamatan},
    dataType  : 'html',
    beforeSend: function(){
        $("#loader3").show();
        $("#list_kurir_div3").hide();
    },
    success: function (data) {
        $("#list_kurir_div3").html(data);
    },
    complete:function(response){
        // Hide image container
        $("#loader3").hide();
        $("#list_kurir_div3").show();
    }
});
}

$(".alamat").click(function(){
    $("#form_alamat").toggle();
});

$("#diskon").html(toDuit(0));
hitung();
});

</script>

<script>
$('document').ready(function(){
    $('#oksimpanx').click(function(){
      $('#Modal_Notif').modal('show');
      if ($('#ongkir').val()==''){
        $('#error_notif').html("Maaf, Anda Belum memilih Kurir untuk Pengiriman!");
      }else{
        $('#error_notif').html("Ada yang belum sesuai nih, Betulkan dulu ya!");
      }
    });

    $('#assign').click(function(){
    var ag = $('#multiple_select').val();
        $('[name="pilihan_kurir"]').val(ag);
    });

    $("body").on("click", "input[name='alamat_lainx']", function () {
      if ($('#alamat_lain').is(':checked')) {
        $(".show-map").show();
        showMapsx();
      }else{
        $(".btn-geolocationx").val('');
        $(".show-map").hide();
      }
    });
});

function showMapsx() {
  // MAPS
  var mymap = L.map("mapid").setView(
    [<?php echo ($kon['kordinat_lokasi']==''?config('kordinat'):$kon['kordinat_lokasi']); ?>],
    15
  );
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mymap);

  L.marker([<?php echo ($kon['kordinat_lokasi']==''?config('kordinat'):$kon['kordinat_lokasi']); ?>])
    .addTo(mymap)
    .bindPopup("Silahkan klik map untuk mendapatkan koordinat.")
    .openPopup();

  var popup = L.popup();

  function onMapClick(e) {
    popup
      .setLatLng(e.latlng)
      .setContent(
        "Map yang anda klik berada di " + e.latlng.lat + ", " + e.latlng.lng
      )
      .openOn(mymap);
    document.getElementById("lokasi_pembeli").value =
      e.latlng.lat + ", " + e.latlng.lng;
  }
  mymap.on("click", onMapClick);
}

$(window).ready(function () {
  $(".btn-geolocationx").click(findLocationx);
});

function findLocationx() {
  navigator.geolocation.getCurrentPosition(getCoordsx, handleErrorsx);
}

function getCoordsx(position) {
  $(".btn-geolocationx").val(
    position.coords.latitude + "," + position.coords.longitude
  );
}

function handleErrorsx(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      alert("You need to share your geolocation data.");
      break;

    case error.POSITION_UNAVAILABLE:
      alert("Current position not available.");
      break;

    case error.TIMEOUT:
      alert("Retrieving position timed out.");
      break;

    default:
      alert("Error");
      break;
  }
}

$("#saldo").click(function(){

  var saldo = $('#saldo').val();
  var total=$('#total').val();
  var ongkirx=$("#ongkir").val();
  var kupon_pusat = parseFloat($('#kupon').val());
  if (kupon_pusat>parseInt(ongkirx)){
    ongkir = 0;
  }else{
    ongkir = parseInt(ongkirx)-kupon_pusat;
  }
  if ($('#saldo').is(':checked')) {
    var bayar=((parseFloat(total)+parseInt(ongkir))-parseFloat(saldo));


    if (ongkirx==''){
      $('#Modal_Notif').modal('show');
      $('#error_notif').html("Maaf, Anda Belum memilih Kurir untuk Pengiriman!");
      $('#transfer').prop('checked', true);
      $('#saldo').prop('checked', false);
    }else{
      if (bayar<=0){
        $("#totalbayar").html(toDuit(0));
      }else{
        $("#totalbayar").html(toDuit(bayar));
      }
    }

  }else{
    
    var bayar=(parseFloat(total)+parseInt(ongkir));
    $("#totalbayar").html(toDuit(bayar));
  }
});

$("#transfer").click(function(){
  hitung();
});

function hitung(){
    var diskon=$('#diskonnilai').val();
    var total=$('#total').val();
    var ongkirx=$("#ongkir").val();
    var fee_admin=$("#fee_admin").val();
    var kupon_pusat = parseFloat($('#kupon').val());
    if (kupon_pusat>parseInt(ongkirx)){
      ongkir = 0;
    }else{
      ongkir = parseInt(ongkirx)-kupon_pusat;
    }

    if(parseFloat(ongkir) >= 0){
        $("#oksimpan").show();
        $("#oksimpanx").hide();
    }else{
        $("#oksimpan").hide();
        $("#oksimpanx").show();
    }
    
    ongkir = ongkir || 0;
    if ($('#saldo').is(':checked')) {
      var saldo = $('#saldo').val();
      var bayarx=((parseFloat(total)+parseInt(ongkir))-parseFloat(saldo));
      if (bayarx<=0){
        var bayar = 0;
      }else{
        var bayar = bayarx;
      }
    }else{
      var bayar=(parseFloat(total)+parseFloat(ongkir));
    }
    if (<?php echo saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen); ?>==0){
      $("#saldo").prop("disabled", true);
    }else{
      $("#saldo").prop("disabled", false); // disable
    }
    
    $("#totalbayar").html(toDuit(bayar));

    // Buat dan Simpan Session Total Ongkir
    var base_url    = "<?php echo base_url(); ?>";
    var ongkir1=$('#ongkir1').val();
    var ongkir2=$('#ongkir2').val();
    var ongkir3=$('#ongkir3').val();

    $.ajax({
        type: 'post',
        url: base_url + 'produk/checkouts',
        data: { ongkir:ongkir,ongkir1:ongkir1,ongkir2:ongkir2,ongkir3:ongkir3 },
        dataType  : 'html',
        success: function (data) {
          //$(".ongkirxxx").hide().load(" .ongkirxxx").fadeIn();
        }
    });

}
</script>