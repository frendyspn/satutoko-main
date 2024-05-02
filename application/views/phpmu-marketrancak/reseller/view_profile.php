

<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li>Profile</li>
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
                      include "sidebar-members.php";
                      echo "<a href='".base_url()."members/edit_profile' class='ps-btn btn-block'><i class='icon-pen'></i> Edit Biodata Diri</a>";
                    ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                    <figure class="ps-block--vendor-status biodata">
                        <?php 
                            echo $this->session->flashdata('message'); 
                                 $this->session->unset_userdata('message');
                            echo "<p class='d-none d-sm-block' style='font-size:17px'>Hai <b>$row[nama_lengkap]</b>, selamat datang di halaman Biodata diri! <br>
                                                            Pastikan data profil sesuai dengan KTP untuk kemudahan dalam bertransaksi.</p><br>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Username</b></label>
                              <div class='col-sm-10'>
                                <b>$row[username]</b>";
                                if ($row['token']=='Y'){ echo "<br><small><i>Referral : <a target='_BLANK' style='color:blue' href='".base_url()."$row[username]'>".base_url()."$row[username]</a></i></small>"; }
                              echo "</div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Nama Lengkap</b></label>
                              <div class='col-sm-10'>
                                $row[nama_lengkap]
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Kelamin</b></label>
                              <div class='col-sm-10'>
                                $row[jenis_kelamin]
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Email</b></label>
                              <div class='col-sm-10'>
                                $row[email]
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>No Hp</b></label>
                            <div class='col-sm-10'>
                              ".($row['no_hp']==''?'<i style="color:#cecece">Wajib Diisi untuk berbelanja,..</i>':$row['no_hp'])."
                            </div>
                            </div>
                            
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Tmp/Tgl Lahir</b></label>
                              <div class='col-sm-10'>
                                ".($row['tempat_lahir'] == '' ? '-' : $row['tempat_lahir'])." / ".($row['tanggal_lahir'] == '0000-00-00' ? '-' : tgl_indo($row['tanggal_lahir']))."
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Alamat</b></label>
                              <div class='col-sm-10'>
                                ".($row['alamat_lengkap'] == '' ? '<i style="color:#cecece">Wajib Diisi untuk berbelanja,..</i>' : $row['alamat_lengkap'])."
                              </div>
                            </div>
                            
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Daerah</b></label>
                            <div class='col-sm-10'>
                              ".(trim(kecamatan($row['kecamatan_id'],$row['kota_id'])) == ', ,' ? '<i style="color:#cecece">Wajib Diisi untuk berbelanja,..</i>' : kecamatan($row['kecamatan_id'],$row['kota_id']))."
                            </div>
                            </div>";

                            if ($row['referral_id']!=''){
                              $ref = $this->db->query("SELECT nama_lengkap FROM rb_konsumen where id_konsumen='$row[referral_id]'")->row_array();
                              echo "<div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Sponsor Anda</b></label>
                              <div class='col-sm-10'>
                                <i style='color:green'>$ref[nama_lengkap]</i>
                              </div>
                              </div>";
                            }

                            if (config('verifikasi_akun')=='Y'){
                              $ver = $this->db->query("SELECT * FROM rb_konsumen_verifikasi where id_konsumen='".$this->session->id_konsumen."'");
                              echo "<div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Verifikasi</b></label>
                              <div class='col-sm-10'>";
                              if ($ver->num_rows()>=1){
                                $verif = $ver->row_array();
                                echo "<b style='color:".($verif['status_verifikasi']=='Verified Account' ? 'green' : 'blue')."'>$verif[status_verifikasi]</b>";
                              }else{
                                echo "<b style='color:red'>UNVERIFIED</b> <i class='icon-arrow-right'></i> <a class='btn btn-danger btn-xs' href='#'  data-toggle='modal' data-target='.verifikasi-modal-lg'>Verifikasi Sekarang!</a>
                  
                                      <div class='modal fade verifikasi-modal-lg' style='z-index:99999' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' aria-hidden='true'>
                                      <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>
                                      <div class='modal-content'>
                                          <div class='modal-header' style='border-bottom:0px solid #e9ecef'>
                                              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                  <span aria-hidden='true'>×</span>
                                              </button>
                                          </div>
                                          <div class='modal-body'>
                                              <div class='row'>
                                                  <div class='col-md-12' style='padding:0px 40px'>
                                                      <h3>Dokumen Verifikasi</h3><hr>
                                                      <form action='".base_url()."members/profile' enctype='multipart/form-data' method='POST'>
                                                          <div class='ps-form__content'>
                                                              <div class='form-group' style='margin-bottom: 1.2rem;'>
                                                                  <label style='margin-bottom:5px' class='col-form-label'>".config('jenis_verifikasi')."</label>
                                                                  <input class='form-control' name='file_verifikasi' style='height:40px' type='file' required>
                                                                  <small style='color:red'>Max 1 MB, Allowed File :  jpg, png, jpeg</small>
                                                              </div>
                                                              <div class='form-group' style='margin-bottom: 1rem;'>
                                                                  <div class='ps-checkbox'>
                                                                      ".nl2br(config('verifikasi_info'))."
                                                                  </div>
                                                              </div><br>
                                                              <div class='form-group submit' style='margin-bottom:5px'>
                                                              <button type='submit' name='verifikasi' class='ps-btn ps-btn--fullwidth gray-btn custom-btn spinnerButton'>Proses / Kirimkan</button>
                                                              </div><br>
                                                          </div>
                                                      </form>
                                                  </div>

                                              </div>
                                          </div>
                                      </div>
                                      </div>
                                      </div>";
                              }
                              echo "</div>
                              </div>";
                            }
                            
                            echo "<div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Status Akun</b></label>
                                <div class='col-sm-10'>";
                                  $cek_paket = $this->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='".$this->session->id_konsumen."' AND users='konsumen'");
                                  if ($cek_paket->num_rows()>=1){
                                    foreach ($cek_paket->result_array() as $rowp) {
                                      if ($rowp['status']=='Y'){
                                        $akhir  = strtotime($rowp['expire_date']); //Waktu awal
                                        $awal = time(); // Waktu sekarang atau akhir
                                        $diff  = $akhir - $awal;
                                        echo "<b>$rowp[nama_paket]</b><br>
                                                              <i>Berakhir ".tgl_indo($rowp['expire_date'])." (".floor($diff / (60 * 60 * 24)) ." hari lagi)</i>";
                                            if (floor($diff / (60 * 60 * 24))<1){
                                                $this->db->query("UPDATE rb_reseller_paket set status='N' where id_reseller='".($this->session->id_konsumen)."' AND users='konsumen'");
                                            }
                                      }else{
                                        echo "<span style='color:red'>Akun Free</span>";
                                      }
                                    }
                                  }else{
                                    echo "<span style='color:red'>Akun Free</span>";
                                  }
                                echo "</div>
                              </div>
                            
                            
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Terdaftar</b></label>
                            <div class='col-sm-10'>
                              ".tgl_indo($row['tanggal_daftar'])."
                            </div>
                            </div>";

                            echo "<br><div class='alert alert-success' style='border-radius:0px; font-weight:800; border-left:5px solid'>Data Upline</div>";
                            $x=1; 
                            $level = 5;
                            $sponsore = $this->session->id_konsumen;
                            do{
                                $rowx = $this->db->query("SELECT * FROM rb_konsumen WHERE id_konsumen='$sponsore'")->row_array();
                                if ($rowx['referral_id']!=''){
                                $kn = $this->db->query("SELECT * FROM rb_konsumen WHERE id_konsumen='$rowx[referral_id]'")->row_array();
                                if ($x==1){
                                  if (cek_paket_all($rowx['referral_id'],'konsumen')=='1'){
                                    echo "Level $x : <b>$kn[nama_lengkap]</b> = Premium Akun<br>";
                                  }else{
                                    echo "Level $x : <b>$kn[nama_lengkap]</b> = Free Akun<br>";
                                  }
                                }else{
                                  if (cek_paket_all($rowx['referral_id'],'konsumen')=='1'){
                                    echo "Level $x : <b>$kn[nama_lengkap]</b> = Premium Akun<br>";
                                  }else{
                                    echo "Level $x : <b>$kn[nama_lengkap]</b> = Free Akun<br>";
                                  }
                                }
                              }
                                $sponsore=$rowx['referral_id'];
                                $x++;
                          
                            }
                            while($x<=$level);

                            echo "<br><div class='alert alert-success' style='border-radius:0px; font-weight:800; border-left:5px solid'>Data Downline</div>";
                            $xc=1; 
                            $levelc = 5;
                            $sponsorec = $this->session->id_konsumen;
                            do{
                                $rowxc = $this->db->query("SELECT * FROM rb_konsumen WHERE referral_id='$sponsorec'")->row_array();
                                if ($rowxc['id_konsumen']!=''){
                                $knc = $this->db->query("SELECT * FROM rb_konsumen WHERE id_konsumen='$rowxc[id_konsumen]'")->row_array();
                                if ($xc==1){
                                  $knc_level1 = $this->db->query("SELECT * FROM rb_konsumen WHERE referral_id='$sponsorec'");
                                  foreach ($knc_level1->result_array() as $rowpa) {
                                      if (cek_paket_all($rowpa['id_konsumen'],'konsumen')=='1'){
                                        echo "Level $xc : <b>$rowpa[nama_lengkap]</b> = Premium Akun<br>";
                                      }else{
                                        echo "Level $xc : <b>$rowpa[nama_lengkap]</b> = Free Akun<br>";
                                      }
                                  }
                                }else{
                                  if (cek_paket_all($knc['id_konsumen'],'konsumen')=='1'){
                                    echo "Level $xc : <b>$knc[nama_lengkap]</b> = Premium Akun<br>";
                                  }else{
                                    echo "Level $xc : <b>$knc[nama_lengkap]</b> = Free Akun<br>";
                                  }
                                }
                              }
                                $sponsorec=$rowxc['id_konsumen'];
                                $xc++;
                          
                            }
                            while($xc<=$levelc);
                            
                        ?>
                    </figure>
                </div>
              
            </div>
        </div>
    </div>
</div>












<?php 
/*
echo "<table id='example11' class='table table-hover table-condensed'>
  <thead>
    <tr>
      <th width='20px'>No</th>
      <th>Nama Penjual</th>
      <th>Belanja & Ongkir</th>
      <th>Status</th>
      <th>Total + Ongkir</th>
      <th></th>
    </tr>
  </thead>
  <tbody>";

      $no = 1;
      $record = $this->model_reseller->orders_report($this->session->id_konsumen,'reseller');
      foreach ($record->result_array() as $row){
      if ($row['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; }else{ $proses = '<i class="text-info">Konfirmasi</i>'; }
      $total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
      echo "<tr><td>$no</td>
                <td><a href='".base_url()."members/detail_reseller/$row[id_reseller]'><small><b>$row[nama_reseller]</b></small><br><small class='text-success'>$row[kode_transaksi]</small></a></td>
                <td><span style='color:blue;'>Rp ".rupiah($total['total'])."</span> <br> <small><i style='color:green;'><b style='text-transform:uppercase'>$row[kurir]</b> - Rp ".rupiah($row['ongkir'])."</i></small></td>
                <td>$proses <br><small>$row[nama_reseller]</small></td>
                <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir'])."</td>
                <td width='130px'>";
                if ($row['proses']=='0'){
                  echo "<a style='margin-right:3px' class='btn btn-success btn-sm' title='Konfirmasi Pembayaran' href='".base_url()."konfirmasi?kode=$row[kode_transaksi]'>Konfirmasi</a>";
                }else{
                  echo "<a style='margin-right:3px' class='btn btn-default btn-sm' href='#'  onclick=\"return confirm('Maaf, Pembayaran ini sudah di konfirmasi!')\">Konfirmasi</a>";
                }
              
              echo "<a class='btn btn-info btn-sm' title='Detail data pesanan' href='".base_url()."members/keranjang_detail/$row[id_penjualan]'><span class='glyphicon glyphicon-search'></span></a></td>
            </tr>";
        $no++;
      }

  echo "</tbody>
</table>"; */
?>