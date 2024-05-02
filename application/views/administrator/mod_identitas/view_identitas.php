<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<style>#mapid { height: 300px; } .show-map{ display:none; } </style>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<?php 
echo "<div class='col-md-12'>
    <div class='box box-info'>
      <div class='box-header with-border'>
        <h3 class='box-title'>Identitas Website</h3>
      </div>
    <div class='box-body'>

    <div class='panel-body'>
      <ul id='myTabs' class='nav nav-tabs' role='tablist'>
        <li role='presentation' class='active'><a href='#umum' id='umum-tab' role='tab' data-toggle='tab' aria-controls='umum' aria-expanded='true'>Data Umum </a></li>
        <li role='presentation' class=''><a href='#payment' role='tab' id='payment-tab' data-toggle='tab' aria-controls='payment' aria-expanded='false'>Payment</a></li>
        <li role='presentation' class=''><a href='#server' role='tab' id='server-tab' data-toggle='tab' aria-controls='server' aria-expanded='false'>Server / API</a></li>
        <li role='presentation' class=''><a href='#app' role='tab' id='app-tab' data-toggle='tab' aria-controls='app' aria-expanded='false'>App Widget</a></li>
        <li role='presentation' class=''><a href='#sosial' role='tab' id='sosial-tab' data-toggle='tab' aria-controls='sosial' aria-expanded='false'>Sosial Login</a></li>
        <li role='presentation' class=''><a href='#verifikasi' role='tab' id='verifikasi-tab' data-toggle='tab' aria-controls='verifikasi' aria-expanded='false'>Verifikasi Akun</a></li>
        <li role='presentation' class=''><a href='#multilevel' role='tab' id='multilevel-tab' data-toggle='tab' aria-controls='multilevel' aria-expanded='false'>Multi Level</a></li>
      </ul><br>";

      echo $this->session->flashdata('message');
            $this->session->unset_userdata('message');

      echo "<div id='myTabContent' class='tab-content'>
            <div role='tabpanel' class='tab-pane fade active in' id='umum' aria-labelledby='umum-tab'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
              $maps = explode('|',$record['maps']);
              $ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
              $fv = explode('|',$ref['keterangan']);

          echo "<div class='col-md-6 col-xs-12'>
                  <input type='hidden' name='id' value='$record[id_identitas]'>
                  <input type='hidden' class='form-control' name='d' value='$record[facebook]'>
                  <input type='hidden' class='form-control' name='e' value='$record[rekening]'>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Website</label>
                    <div class='col-sm-9'>
                      <input type='text' class='form-control' placeholder='' name='title' value='".config('title')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Domain</label>
                    <div class='col-sm-9'>
                      <input type='text' class='form-control' placeholder='' name='c' value='$record[url]'>
                    </div>
                  </div>

                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Title</label>
                      <div class='col-sm-9'>
                        <input type='text' class='form-control' placeholder='' name='a' value='$record[nama_website]'>
                      </div>
                    </div>

                    
                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Meta Keyword</label>
                      <div class='col-sm-9'>
                        <textarea class='form-control' style='height:80px' placeholder='' name='h'>$record[meta_keyword]</textarea>
                      </div>
                    </div>

                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Meta Deskripsi</label>
                      <div class='col-sm-9'>
                        <textarea class='form-control' style='height:80px' placeholder='' name='g'>$record[meta_deskripsi]</textarea>
                      </div>
                    </div>

                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Info Footer</label>
                      <div class='col-sm-9'>
                        <textarea class='form-control' style='height:60px' placeholder='' name='info_footer'>".config('info_footer')."</textarea>
                      </div>
                    </div>

                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Twitter</label>
                      <div class='col-sm-9'>
                        <input type='text' class='form-control' placeholder='' name='twitter' value='".config('twitter')."'>
                      </div>
                    </div>

                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>FB Pixel</label>
                      <div class='col-sm-9'>
                        <input type='text' class='form-control' placeholder='' name='facebook_pixel' value='".config('facebook_pixel')."'>
                      </div>
                    </div>

                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Google verif.</label>
                      <div class='col-sm-9'>
                      <input type='text' class='form-control' placeholder='' name='google_site_verification' value='".config('google_site_verification')."'>
                      </div>
                    </div>

                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Mode Aktif</label>
                      <div class='col-sm-9'>";
                          echo "<input type='radio' name='mode' id='mode1' value='marketplace' class='radio marketplace' ".(config('mode')=='marketplace'?'checked':'')."> <label for='mode1'>Marketplace</label>
                                <input type='radio' name='mode' id='mode2' value='ecommerce' class='radio ecommerce' ".(config('mode')=='ecommerce'?'checked':'')."> <label for='mode2'>E-Commerce</label>";
         
                      echo "</div>
                        
                    </div>
                    <div class='desc' style='".(config('mode')=='ecommerce'?'display:block':'display:none')."'>
                      <div class='alert alert-danger'><b>PENTING</b> - Pastikan di system sudah terdaftar 1 <a href='".base_url().$this->uri->segment(1)."/reseller'>PELAPAK</a> dan jika lebih dari 1 maka pastikan hanya 1 pelapak saja yang di <u><b>Verfikasi</b></u>, karena pelapak terverfikasi akan menjadi default toko posting produk mode E-Commerce. </div>
                    </div>
                </div>

                <div class='col-md-6 col-xs-12'>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Izin Publish
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Produk yang diposting (Pelapak) perlu persetujuan untuk publish.'></span></label>
                    <div class='col-sm-9'>";
                        echo "<input type='radio' name='approve_produk' id='izin1' class='radio' value='N' ".(config('approve_produk')=='N'?'checked':'')."> <label for='izin1'>Ya</label>
                              <input type='radio' name='approve_produk' id='izin2' class='radio' value='Y' ".(config('approve_produk')=='Y'?'checked':'')."> <label for='izin2'>Tidak</label>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Resolution
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Nama Tampil - untuk Admin Resolution Center'></span></label>
                    <div class='col-sm-9'>
                      <input type='text' class='form-control' placeholder='' name='resolusi_center' value='".config('resolusi_center')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Info Atas</label>
                    <div class='col-sm-9'>
                    <textarea class='form-control' style='height:80px' placeholder='' name='info_atas'>$record[info_atas]</textarea>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>No Telpon</label>
                    <div class='col-sm-9'>
                      <input type='text' class='form-control' placeholder='' name='f' value='$record[no_telp]'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Flash Deal</label>
                    <div class='col-sm-9'>
                      <input type='text' class='form-control datepicker1' placeholder='' name='flash_deal' value='".tgl_view($record['flash_deal'])."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Free Seller
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Jumlah Produk yang dapat diposting oleh Pelapak (Free Seller)'></span></label>
                    <div class='col-sm-9'>
                    <input type='number' class='form-control' placeholder='' name='free_reseller' value='$record[free_reseller]'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>WA Seller
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Izinkan Konsumen Menghubungi Pelapak via Whatsapp?'></span></label>
                    <div class='col-sm-9'>";
                      echo "<input type='radio' id='wa1' name='wa_seller' class='radio' value='Y' ".(config('wa_seller')=='Y'?'checked':'')."> <label for='wa1'>Aktif</label>
                            <input type='radio' id='wa2' name='wa_seller' class='radio' value='N' ".(config('wa_seller')=='N'?'checked':'')."> <label for='wa2'>Non Aktif</label>";

                    echo "</div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Referral
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Langsung Aktifkan URL Referral Saat Pendaftaran Konsumen?'></span></label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='ref1' type='radio' name='token_referral' value='Y' ".(config('token_referral')=='Y'?'checked':'')."> <label for='ref1'>Ya</label>
                              <input class='radio' id='ref2' type='radio' name='token_referral' value='N' ".(config('token_referral')=='N'?'checked':'')."> <label for='ref2'>Tidak</label>";

                    echo "
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Reseller</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='res1' type='radio' name='reseller' value='Y' ".(config('reseller')=='Y'?'checked':'')."> <label for='res1'>Aktif</label>
                              <input class='radio' id='res2' type='radio' name='reseller' value='N' ".(config('reseller')=='N'?'checked':'')."> <label for='res2'>Non Aktif</label>
                          </div>
                  </div>

                  <div class='form-group'>
                      <label class='col-sm-3 control-label'>Favicon</label>
                      <div class='col-sm-9'>
                        <input type='file' class='form-control' name='j'>
                        Favicon Aktif Saat ini : <img style='width:32px; height:32px' src='".base_url()."asset/images/$record[favicon]'>
                      </div>
                    </div>

                  
                </div>

              <div style='clear:both'></div>
              <div class='box-footer'>
                <button type='submit' name='umum' class='btn btn-primary'>Simpan Perubahan</button>
                <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
              </div>";
              echo form_close();
            echo "</div>



            <div role='tabpanel' class='tab-pane fade' id='payment' aria-labelledby='payment-tab'>";
            $attributes = array('class'=>'form-horizontal','role'=>'form');
            echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
              echo "<input type='hidden' name='id' value='$record[id_identitas]'>
              <div class='col-md-6 col-xs-12'> 
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Verifikasi Toko 
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Verifikasi Pengaktifan Akun Toko/Pelapak oleh admin.'></span></label>
                    <div class='col-sm-8'>";
                      echo "<input class='radio' id='verif1' type='radio' name='verifikasi' value='Y' ".($fv[1]=='Y'?'checked':'')."><label for='verif1'>Ya</label>
                            <input class='radio' id='verif2' type='radio' name='verifikasi' value='N' ".($fv[1]=='N'?'checked':'')."><label for='verif2'>Tidak</label>";
     
                    echo "
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Order requires
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Untuk Order Produk harus Login atau tanpa Login?'></span></label>
                    <div class='col-sm-8'>";
                      echo "<input class='radio' id='order1' type='radio' name='requires' value='enable' ".($fv[2]=='enable'?'checked':'')."><label for='order1'>Harus Login</label>
                            <input class='radio' id='order2' type='radio' name='requires' value='disable' ".($fv[2]=='disable'?'checked':'')."><label for='order2'>Tanpa Login</label>";

                    echo "</div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Fee Admin (Rp)
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Fee admin tiap transaksi sukses (Dibebankan ke Konsumen).'></span></label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='admin_fee' value='$fv[0]'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Fee Produk (%) 
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Fee Transaksi Per-Produk (Bebankan ke Pelapak), jika nilai 0 maka terapkan fee khusus yang telah di set pada tiap produk.'></span></label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='fee_produk' value='".config('fee_produk')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Fee Referal (%)
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Fee diambil dari transaksi sukses pelapak yang disponsori.'></span></label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='referral_fee' value='$ref[referral]'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Fee Withdraw
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Fee untuk Tiap Transaksi Withdraw / Penarikan Dana.'></span></label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='withdraw_fee' value='".config('withdraw_fee')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Min. Withdraw
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Nilai Minimal Tiap Transaksi Withdraw / Penarikan Dana.'></span></label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='withdraw_min' value='".config('withdraw_min')."'>
                    </div>
                  </div>
                  
                  

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Kurir Lokal</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='kurir_lokal1' type='radio' name='kurir_lokal' value='Y' ".(config('kurir_lokal')=='Y'?'checked':'')."> <label for='kurir_lokal1'>Aktif</label>
                      <input class='radio' id='kurir_lokal2' type='radio' name='kurir_lokal' value='N' ".(config('kurir_lokal')=='N'?'checked':'')."> <label for='kurir_lokal2'>Non-Aktif</label>
                    </div>
                </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Kurir Nasional</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='kurir_nasional1' type='radio' name='kurir_nasional' value='Y' ".(config('kurir_nasional')=='Y'?'checked':'')."> <label for='kurir_nasional1'>Aktif</label>
                      <input class='radio' id='kurir_nasional2' type='radio' name='kurir_nasional' value='N' ".(config('kurir_nasional')=='N'?'checked':'')."> <label for='kurir_nasional2'>Non-Aktif</label>
                    </div>
                </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Kurir Toko (COD)</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='kurir_toko1' type='radio' name='kurir_toko' value='Y' ".(config('kurir_toko')=='Y'?'checked':'')."> <label for='kurir_toko1'>Aktif</label>
                      <input class='radio' id='kurir_toko2' type='radio' name='kurir_toko' value='N' ".(config('kurir_toko')=='N'?'checked':'')."> <label for='kurir_toko2'>Non-Aktif</label>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Kurir Marketplace Lain</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='kurir_lainnya1' type='radio' name='kurir_lainnya' value='Y' ".(config('kurir_lainnya')=='Y'?'checked':'')."> <label for='kurir_lainnya1'>Aktif</label>
                      <input class='radio' id='kurir_lainnya2' type='radio' name='kurir_lainnya' value='N' ".(config('kurir_lainnya')=='N'?'checked':'')."> <label for='kurir_lainnya2'>Non-Aktif</label>
                    </div>
                </div>
                <br>

                  <p class='titlepg'><span class='fa fa-gears'></span> Setting Kurir Internal</p>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Type Kurir</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='type_kurir1' type='radio' name='type_kurir' value='km' ".(config('type_kurir')=='km'?'checked':'')."> <label for='type_kurir1'>Per-KM</label>
                      <input class='radio' id='type_kurir2' type='radio' name='type_kurir' value='fix' ".(config('type_kurir')=='fix'?'checked':'')."> <label for='type_kurir2'>Antar Lokasi</label>
                    </div>
                </div>

                <div class='descs' style='".(config('type_kurir')=='fix'?'display:block':'display:none')."'>
                      <div class='alert alert-warning'><b>PENTING</b> - Silahkan Setting Ongkir Kurir Internal <a href='".base_url()."administrator/ongkir_driver'>disini</a> </div>
                </div>

                <div class='descx' style='".(config('type_kurir')=='km'?'display:block':'display:none')."'>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Tarif instant / KM</label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='ongkir_per_km' value='".config('ongkir_per_km')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Tarif Regular / KM</label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='ongkir_per_km_roda4' value='".config('ongkir_per_km_roda4')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Jarak max (KM)
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Jarak Layanan Kurir instant dan Regular yang dilayani.'></span></label>
                    <div class='col-sm-8'>
                    <input type='number' class='form-control' name='max_jarak_km' value='".config('max_jarak_km')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Kordinat Pusat
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Kordinat default marker pada maps Lokasi.'></span></label>
                    <div class='col-sm-8'>
                    <input type='text' class='form-control form-mini btn-geolocationx' value='".config('kordinat')."' name='kordinat' id='lokasi' autocomplete='off' />
                    <label class='switch mr-1 mt-2'>
                        <input type='checkbox' name='alamat_lainx' id='alamat_lain'> Cari Kordinat dari Peta
                    </label><br>

                    <div class='show-map'>
                        <div id='mapid' class='shadow-sm'></div>
                    </div>
                    </div>
                  </div>

                </div>

                </div>

              <div class='col-md-6 col-xs-12'>
                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Transfer Manual</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='ipaym1' type='radio' name='transfer_manual' value='Y' ".(config('transfer_manual')=='Y'?'checked':'')."> <label for='ipaym1'>Aktif</label>
                      <input class='radio' id='ipaym2' type='radio' name='transfer_manual' value='N' ".(config('transfer_manual')=='N'?'checked':'')."> <label for='ipaym2'>Non-Aktif</label>
                    </div>
                </div><br>
                
                <p class='titlepg'><span class='fa fa-gears'></span>  Api Xendit</p>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>API Trx Xendit</label>
                  <div class='col-sm-9'>
                  <textarea class='form-control' placeholder='XXXXXXX-XXXXXX-XXXX-XXXXXX-XXXXXXXXXX' name='xendit_api_depo'>".config('xendit_api_depo')."</textarea>
                  </div>
                </div>
                
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>API Xendit WD</label>
                  <div class='col-sm-9'>
                  <textarea class='form-control' placeholder='XXXXXXX-XXXXXX-XXXX-XXXXXX-XXXXXXXXXX' name='xendit_api'>".config('xendit_api')."</textarea>
                  </div>
                </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Status Xendit</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='xendit1' type='radio' name='xendit_aktif' value='Y' ".(config('xendit_aktif')=='Y'?'checked':'')."> <label for='xendit1'>Aktif</label>
                      <input class='radio' id='xendit2' type='radio' name='xendit_aktif' value='N' ".(config('xendit_aktif')=='N'?'checked':'')."> <label for='xendit2'>Non-Aktif</label>
                    </div>
                </div>
                
                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Disbursement</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='xendit_disbursement1' type='radio' name='xendit_disbursement' value='Y' ".(config('xendit_disbursement')=='Y'?'checked':'')."> <label for='xendit_disbursement1'>Aktif</label>
                      <input class='radio' id='xendit_disbursement2' type='radio' name='xendit_disbursement' value='N' ".(config('xendit_disbursement')=='N'?'checked':'')."> <label for='xendit_disbursement2'>Non-Aktif</label>
                    </div>
                </div>
                
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Webhook URL</label>
                  <div class='col-sm-9'>
                  <input type='text' class='form-control' style='color:red' value='".base_url()."konfirmasi/xendit_disbursement_cronjobs' disabled>
                  </div>
                </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Mode</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='debug_xendit1' type='radio' name='debug_xendit' value='Y' ".(config('debug_xendit')=='Y'?'checked':'')."> <label for='debug_xendit1'>Development</label>
                              <input class='radio' id='debug_xendit2' type='radio' name='debug_xendit' value='N' ".(config('debug_xendit')=='N'?'checked':'')."> <label for='debug_xendit2'>Production</label>
                          </div>
                  </div>

                <br><br>
                
              <p class='titlepg'><span class='fa fa-gears'></span>  Api Ipaymu</p>
                <div class='form-group'>
                  <label class='col-sm-3 control-label'>API ipaymu</label>
                  <div class='col-sm-9'>
                  <textarea class='form-control' placeholder='XXXXXXX-XXXXXX-XXXX-XXXXXX-XXXXXXXXXX' name='ipaymu'>".config('ipaymu')."</textarea>
                  </div>
                </div>

                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Url ipaymu</label>
                  <div class='col-sm-9'>
                  <input type='text' class='form-control' name='ipaymu_url' value='".config('ipaymu_url')."'>
                  </div>
                </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Fee Ipaymu
                    <span class='fa fa-question-circle' data-toggle='tooltip' title='Fee Transaksi Ipaymu dibayarkan oleh Konsumen.'></span></label>
                    <div class='col-sm-9'>
                    <input type='number' class='form-control' name='ipaymu_fee' value='".config('ipaymu_fee')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Mode</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='debug_ipaymu1' type='radio' name='debug_ipaymu' value='Y' ".(config('debug_ipaymu')=='Y'?'checked':'')."> <label for='debug_ipaymu1'>Development</label>
                              <input class='radio' id='debug_ipaymu2' type='radio' name='debug_ipaymu' value='N' ".(config('debug_ipaymu')=='N'?'checked':'')."> <label for='debug_ipaymu2'>Production</label>
                          </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Status ipaymu</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='ipay1' type='radio' name='ipaymu_aktif' value='Y' ".(config('ipaymu_aktif')=='Y'?'checked':'')."> <label for='ipay1'>Aktif</label>
                      <input class='radio' id='ipay2' type='radio' name='ipaymu_aktif' value='N' ".(config('ipaymu_aktif')=='N'?'checked':'')."> <label for='ipay2'>Non-Aktif</label>
                    </div>
                </div>

                <br><br>
                <p class='titlepg'><span class='fa fa-gears'></span>  Api Tripay</p>

                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Merchant Code</label>
                  <div class='col-sm-9'>
                  <input type='text' class='form-control' name='merchant_code' value='".config('merchant_code')."'>
                  </div>
                </div>

                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Private Key</label>
                  <div class='col-sm-9'>
                  <input type='text' class='form-control' name='private_key' value='".config('private_key')."'>
                  </div>
                </div>

                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Api Key</label>
                  <div class='col-sm-9'>
                  <input type='text' class='form-control' name='api_key' value='".config('api_key')."'>
                  </div>
                </div>

                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Payment URL</label>
                  <div class='col-sm-9'>
                  <input type='text' class='form-control' name='payment_url' value='".config('payment_url')."'>
                  </div>
                </div>

                <div class='form-group'>
                  <label class='col-sm-3 control-label'>Webhook URL</label>
                  <div class='col-sm-9'>
                  <input type='text' class='form-control' style='color:red' value='".base_url()."tripay/webhook' disabled>
                  </div>
                </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Mode</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='debug_tripay1' type='radio' name='debug_tripay' value='Y' ".(config('debug_tripay')=='Y'?'checked':'')."> <label for='debug_tripay1'>Development</label>
                              <input class='radio' id='debug_tripay2' type='radio' name='debug_tripay' value='N' ".(config('debug_tripay')=='N'?'checked':'')."> <label for='debug_tripay2'>Production</label>
                          </div>
                  </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Status Tripay</label>
                    <div class='col-sm-9'>
                      <input class='radio' id='tripay1' type='radio' name='tripay_aktif' value='Y' ".(config('tripay_aktif')=='Y'?'checked':'')."> <label for='tripay1'>Aktif</label>
                      <input class='radio' id='tripay2' type='radio' name='tripay_aktif' value='N' ".(config('tripay_aktif')=='N'?'checked':'')."> <label for='tripay2'>Non-Aktif</label>
                    </div>
                </div>

              </div>

                

                
                <div style='clear:both'></div>
                <div class='box-footer'>
                  <button type='submit' name='payment' class='btn btn-primary'>Simpan Perubahan</button>
                  <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                </div>";
                echo form_close();
              echo "</div>



              <div role='tabpanel' class='tab-pane fade' id='server' aria-labelledby='server-tab'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
                echo "<input type='hidden' name='id' value='$record[id_identitas]'>
                  <div class='col-md-6 col-xs-12'>
                  <p class='titlepg'><span class='fa fa-gears'></span>  Api Whatsapp</p>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>WA Gateway</label>
                    <div class='col-sm-9'>";
                    if (config('wa_gateway')=='wablas'){
                      $text1 = 'Domain Wablas';
                      $text2 = 'API Wablas';
                      echo "<input class='radio' id='gate1' type='radio' name='wa_gateway' value='wablas' ".(config('wa_gateway')=='wablas'?'checked':'')."><label for='gate1'>Wablas.com</label>
                            <input class='radio' id='gate2' type='radio' name='wa_gateway' value='woowa'><label for='gate2'>Woo-wa.com (Woowandroid)</label>";
                    }else{
                      $text1 = 'Authorization/License';
                      $text2 = 'Device / CS ID';
                      echo "<input class='radio' id='gate1' type='radio' name='wa_gateway' value='wablas'><label for='gate1'>Wablas.com</label>
                            <input class='radio' id='gate2' type='radio' name='wa_gateway' value='woowa' ".(config('wa_gateway')=='woowa'?'checked':'')."><label for='gate2'>Woo-wa.com (Woowandroid)</label>";
                    }
                    echo "</div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label text1'>$text1</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' name='wa_domain' placeholder='- - - - - - - -' value='".config('wa_domain')."'>
                    </div>
                  </div>
                  
                  <div class='form-group'>
                    <label class='col-sm-3 control-label text2'>$text2</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' name='wa' placeholder='- - - - - - - -' value='".$maps[2]."'>
                    </div>
                  </div>

                  <br><br>
                  <p class='titlepg'><span class='fa fa-gears'></span>  Api PPOB</p>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>API PPOB</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' name='ppob_url' placeholder='https://tripay.id/api' value='".config('ppob_url')."'>
                      <input type='text' class='form-control' name='maps' placeholder='API dari https://tripay.id' value='".$maps[0]."'>
                      <input type='text' class='form-control' style='color:red; margin-top: 3px;' name='pin' placeholder='PIN Transaksi' value='".$maps[1]."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Mode</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='debug_ppob1' type='radio' name='debug_ppob' value='Y' ".(config('debug_ppob')=='Y'?'checked':'')."> <label for='debug_ppob1'>Development</label>
                              <input class='radio' id='debug_ppob2' type='radio' name='debug_ppob' value='N' ".(config('debug_ppob')=='N'?'checked':'')."> <label for='debug_ppob2'>Production</label>
                          </div>
                  </div>
                  
                  <br><br>
                  <p class='titlepg'><span class='fa fa-gears'></span> Shipping Gateway</p>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>API Rajaongkir</label>
                    <div class='col-sm-9'>
                      <input type='text' class='form-control' name='api_rajaongkir' placeholder='API https://rajaongkir.com PRO' value='$record[api_rajaongkir]'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>API Binderbyte</label>
                    <div class='col-sm-9'>
                      <input type='text' class='form-control' name='api_resi' placeholder='API Cek Resi Dari https://binderbyte.com/' value='".config('api_resi')."'>
                        <small style='color:green'><i>Api Cek Resi Alternatif untuk : <span style='color:red'>".config('api_resi_off')."</span></i></small style='color:green'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Api Resi Aktif</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='wab1' type='radio' name='api_resi_aktif' value='rajaongkir' ".(config('api_resi_aktif')=='rajaongkir'?'checked':'')."> <label for='wab1'>Rajaongkir (Binderbyte Alternatif)</label>
                            <input class='radio' id='wab2' type='radio' name='api_resi_aktif' value='binderbyte' ".(config('api_resi_aktif')=='binderbyte'?'checked':'')."> <label for='wab2'>Binderbyte (Only)</label>";

                    echo "</div>
                  </div>
                </div>

                <div class='col-md-6 col-xs-12'>
                <p class='titlepg'><span class='fa fa-gears'></span>  Api Mutasi Bank</p>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Api MutasiBank</label>
                    <div class='col-sm-9'>
                    <textarea class='form-control' placeholder='API http://mutasibank.co.id' name='api_mutasibank'>$record[api_mutasibank]</textarea>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Api Moota</label>
                    <div class='col-sm-9'>
                    <textarea class='form-control' placeholder='API https://moota.co' name='api_moota'>".config('api_moota')."</textarea>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Api Aktif</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='mutasi1' type='radio' name='mutasi_aktif' value='mutasibank' ".(config('mutasi_aktif')=='mutasibank'?'checked':'')."><label for='mutasi1'>mutasibank.co.id</label>
                            <input class='radio' id='mutasi2' type='radio' name='mutasi_aktif' value='moota' ".(config('mutasi_aktif')=='moota'?'checked':'')."><label for='mutasi2'>moota.co</label>";

                    echo "</div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Callback Mutasi</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' style='color:red' value='".$this->uri->segment(1)."/mutasi_ty35fgdfgd777bba064b72be' disabled>
                    </div>
                  </div>
                
                <br><br>
                <p class='titlepg'><span class='fa fa-gears'></span>  Server Email</p>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Jenis</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='xxx' value='SMTP' disabled>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Secure</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='sec1' type='radio' name='smtp_secure' value='tls' ".(config('smtp_secure')=='tls'?'checked':'')."><label for='sec1'>TLS</label> 
                            <input class='radio' id='sec2' type='radio' name='smtp_secure' value='ssl' ".(config('smtp_secure')=='ssl'?'checked':'')."><label for='sec2'>SSL</label> ";

                    echo "</div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Server</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='Ex : smtp.googlemail.com' name='email_server' value='".config('email_server')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Port</label>
                    <div class='col-sm-9'>
                    <input type='number' class='form-control' placeholder='xxx' name='email_port' value='".config('email_port')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Pengirim</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='Nama Pengirim Email (Ex : TAJALAPAK.COM)' name='pengirim_email' value='$record[pengirim_email]'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>E-mail Addr.</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='Alamat Email' style='margin-top:3px' name='b' value='$record[email]'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Password</label>
                    <div class='col-sm-9'>
                    <input type='password' class='form-control' placeholder='*************' style='margin-top:3px' name='password'>
                    </div>
                  </div>


                </div>
                <div style='clear:both'></div>
                <div class='box-footer'>
                  <button type='submit' name='server' class='btn btn-primary'>Simpan Perubahan</button>
                  <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                </div>";
                echo form_close();
              echo "</div>

              <div role='tabpanel' class='tab-pane fade' id='app' aria-labelledby='app-tab'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
          echo "<div class='col-md-6 col-xs-12'>
                  <input type='hidden' name='id' value='$record[id_identitas]'>
                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Judul</label>
                      <div class='col-sm-9'>
                        <input type='text' class='form-control' placeholder='' name='apps_title' value='".config('apps_title')."'>
                      </div>
                    </div>

                    
                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Keterangan</label>
                      <div class='col-sm-9'>
                        <textarea class='form-control' style='height:120px' placeholder='' name='apps_deskripsi'>".config('apps_deskripsi')."</textarea>
                      </div>
                    </div>
                    
                    <div class='form-group'>
                      <label class='col-sm-3 control-label'>Image</label>
                      <div class='col-sm-9'>
                        <input type='file' class='form-control' name='apps_image'>
                        Gambar Terpasang : <a target='_BLANK' href='".base_url()."asset/images/".config('apps_image')."'>".config('apps_image')."</a>
                      </div>
                    </div>
                </div>

                <div class='col-md-6 col-xs-12'>
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Google Play</label>
                    <div class='col-sm-9'>
                    <input type='url' class='form-control' placeholder='https://...' name='apps_google_play' value='".config('apps_google_play')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>App Store</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='https://...' name='apps_app_store' value='".config('apps_app_store')."'>
                    </div>
                  </div>
                  
                  <div class='form-group'>
                    <label class='col-sm-3 control-label'>Status</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='stat1' type='radio' name='apps_aktif' value='Y' ".(config('apps_aktif')=='Y'?'checked':'')."><label for='stat1'>Aktif</label>
                              <input class='radio' id='stat2' type='radio' name='apps_aktif' value='N' ".(config('apps_aktif')=='N'?'checked':'')."><label for='stat2'>Non-Aktif</label>";
 
                    echo "</div>
                  </div>
                  
                </div>

              <div style='clear:both'></div>
              <div class='box-footer'>
                <button type='submit' name='apps' class='btn btn-primary'>Simpan Perubahan</button>
                <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
              </div>";
              echo form_close();
            echo "</div>


            <div role='tabpanel' class='tab-pane fade' id='sosial' aria-labelledby='sosial-tab'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
          echo "<div class='col-md-12 col-xs-12'>
                  <input type='hidden' name='id' value='$record[id_identitas]'>
                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>application_name</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='xxx' name='application_name' value='".config('application_name')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>redirect_uri</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='xxx' name='redirect_uri' value='".config('redirect_uri')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>facebook_app_id</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='xxx' name='facebook_app_id' value='".config('facebook_app_id')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>facebook_app_secret</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='xxx' name='facebook_app_secret' value='".config('facebook_app_secret')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>Google client_id</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='xxx' name='google_client_id' value='".config('google_client_id')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>Google client_secret</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' placeholder='xxx' name='google_client_secret' value='".config('google_client_secret')."'>
                    </div>
                  </div>
                </div>

              <div style='clear:both'></div>
              <div class='box-footer'>
                <button type='submit' name='sosial' class='btn btn-primary'>Simpan Perubahan</button>
                <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
              </div>";
              echo form_close();
            echo "</div>

            <div role='tabpanel' class='tab-pane fade' id='verifikasi' aria-labelledby='verifikasi-tab'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
          echo "<div class='col-md-12 col-xs-12'>
                  <input type='hidden' name='id' value='$record[id_identitas]'>

                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>Verifikasi OTP</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='otp1' type='radio' name='otp' value='aktif' ".(config('otp')=='aktif'?'checked':'')."><label for='otp1'>Aktif</label>
                              <input class='radio' id='otp2' type='radio' name='otp' value='non-aktif' ".(config('otp')=='non-aktif'?'checked':'')."><label for='otp2'>Non-Aktif</label>";

                    echo "<br><small style='color:green'><i>Verifikasi OTP Via Whatsapp dan Email</i></small>
                    </div>
                  </div>


                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>Status</label>
                    <div class='col-sm-9'>";
                      echo "<input class='radio' id='statu1' type='radio' name='verifikasi_akun' value='Y' ".(config('verifikasi_akun')=='Y'?'checked':'')."><label for='statu1'>Aktif</label>
                              <input class='radio' id='statu2' type='radio' name='verifikasi_akun' value='N' ".(config('verifikasi_akun')=='N'?'checked':'')."><label for='statu2'>Non-Aktif</label>";

                    echo "</div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-2 control-label'>Label Verifikasi</label>
                    <div class='col-sm-9'>
                    <input type='text' class='form-control' name='jenis_verifikasi' value='".config('jenis_verifikasi')."'>
                    </div>
                  </div>

                  <div class='form-group'>
                      <label class='col-sm-2 control-label'>Verifikasi Info</label>
                      <div class='col-sm-9'>
                        <textarea class='form-control' style='height:180px' placeholder='' name='verifikasi_info'>".config('verifikasi_info')."</textarea>
                      </div>
                    </div>
                  
                </div>

              <div style='clear:both'></div>
              <div class='box-footer'>
                <button type='submit' name='verifikasi' class='btn btn-primary'>Simpan Perubahan</button>
                <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
              </div>";
              echo form_close();
            echo "</div>


            <div role='tabpanel' class='tab-pane fade' id='multilevel' aria-labelledby='multilevel-tab'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/identitaswebsite',$attributes); 
          echo "<div class='col-md-4 col-xs-12'>
                  <input type='hidden' name='id' value='$record[id_identitas]'>
                  <p class='titlepg'><span class='fa fa-gears'></span>  Komisi Premium Akun</p>
                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 1</label>
                    <div class='col-sm-7'>
                      <div class='input-group'>
                        <span class='input-group-addon'>Rp</span>
                        <input type='text' class='form-control' placeholder='xxx' name='fee_level1' value='".config('fee_level1')."'>
                      </div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 2</label>
                    <div class='col-sm-7'>
                      <div class='input-group'>
                          <span class='input-group-addon'>Rp</span>
                        <input type='text' class='form-control' placeholder='xxx' name='fee_level2' value='".config('fee_level2')."'>
                      </div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 3</label>
                    <div class='col-sm-7'>
                      <div class='input-group'>
                        <span class='input-group-addon'>Rp</span>
                        <input type='text' class='form-control' placeholder='xxx' name='fee_level3' value='".config('fee_level3')."'>
                      </div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 4</label>
                    <div class='col-sm-7'>
                      <div class='input-group'>
                          <span class='input-group-addon'>Rp</span>
                        <input type='text' class='form-control' placeholder='xxx' name='fee_level4' value='".config('fee_level4')."'>
                      </div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 5</label>
                    <div class='col-sm-7'>
                      <div class='input-group'>
                          <span class='input-group-addon'>Rp</span>
                        <input type='text' class='form-control' placeholder='xxx' name='fee_level5' value='".config('fee_level5')."'>
                      </div>
                    </div>
                  </div>
                </div>

                <div class='col-md-4 col-xs-12'>
                <p class='titlepg'><span class='fa fa-gears'></span>  Komisi Star Seller</p>
                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 1</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_toko_level1' value='".config('fee_toko_level1')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 2</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_toko_level2' value='".config('fee_toko_level2')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 3</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_toko_level3' value='".config('fee_toko_level3')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 4</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_toko_level4' value='".config('fee_toko_level4')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 5</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_toko_level5' value='".config('fee_toko_level5')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>
                </div>

                <div class='col-md-4 col-xs-12'>
                <p class='titlepg'><span class='fa fa-gears'></span>  Fee Transaksi Produk</p>
                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Total Fee</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_trx_total' value='".config('fee_trx_total')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 1</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_trx_level1' value='".config('fee_trx_level1')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 2</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_trx_level2' value='".config('fee_trx_level2')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 3</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_trx_level3' value='".config('fee_trx_level3')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 4</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_trx_level4' value='".config('fee_trx_level4')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>

                  <div class='form-group'>
                    <label class='col-sm-5 control-label'>Fee Level 5</label>
                    <div class='col-sm-7'>
                    <div class='input-group'>
                    <input type='text' class='form-control' placeholder='xxx' name='fee_trx_level5' value='".config('fee_trx_level5')."'>
                    <span class='input-group-addon'>%</span></div>
                    </div>
                  </div>
                </div>

              <div style='clear:both'></div>
              <div class='box-footer'>
                <button type='submit' name='multilevel' class='btn btn-primary'>Simpan Perubahan</button>
                <a href='".base_url().$this->uri->segment(1)."/identitaswebsite'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
              </div>";
              echo form_close();
            echo "</div>
            

            </div></div></div>";
?>
<script>
$(document).ready(function() {
$(".mode").click(function() {
  // remove the background color from all labels.
  $(".mode").removeClass("btn-primary");

  // add the background only to the parent-label of the clicked button.
  $(this).parent().addClass("btn-primary");
});

  $(".marketplace").click(function(){
    $(".desc").hide();
  });
  $(".ecommerce").click(function(){
    $(".desc").show();
  });

  $("#type_kurir2").click(function(){
    $(".descx").hide();
    $(".descs").show();
  });
  $("#type_kurir1").click(function(){
    $(".descx").show();
    $(".descs").hide();
  });

  $('input[name="wa_gateway"]').on('change', function(){
    if ($(this).val()=='wablas') {
      //change to "show update"
      $(".text1").text("Domain Wablas");
      $(".text2").text("API Wablas");
    }else  {
      $(".text1").text("Authorization");
      $(".text2").text("Device / CS ID");
    }
});
});
</script>

<script>
$('document').ready(function(){
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
    [<?php echo ($row['kordinat_lokasi']==''?config('kordinat'):$row['kordinat_lokasi']); ?>],
    15
  );
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mymap);

  L.marker([<?php echo ($row['kordinat_lokasi']==''?config('kordinat'):$row['kordinat_lokasi']); ?>])
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
    document.getElementById("lokasi").value =
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
</script>
            
