<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li><?php echo $judul; ?></li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
          <br><a href='#' onclick="window.history.go(-1); return false;" class='ps-btn float-right'>Kembali</a>
          <div style='clear:both'></div>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <?php
                      if (trim($rows['foto'])=='' OR !file_exists("asset/foto_user/".$rows['foto'])){
                        echo "<img class='img-thumbnail' style='width:100%' src='".base_url()."asset/foto_user/blank.png'>";
                      }else{
                          echo "<img class='img-thumbnail' style='width:100%' src='".base_url()."asset/foto_user/$rows[foto]'>";
                      }
                    ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                    <figure class="ps-block--vendor-status biodata">
                        <?php 
                            echo $this->session->flashdata('message'); 
                                 $this->session->unset_userdata('message');
                            echo "<p style='font-size:17px'>Halaman Biodata diri pelanggan anda! <br>
                                                            Berikut informasi data pelanggan anda a/n <b>$rows[nama_lengkap]</b>.</p><br>


                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Nama Lengkap</b></label>
                              <div class='col-sm-10'>
                                $rows[nama_lengkap]
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Kelamin</b></label>
                              <div class='col-sm-10'>
                                $rows[jenis_kelamin]
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Email</b></label>
                              <div class='col-sm-10'>
                                $rows[email]
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>No Hp</b></label>
                            <div class='col-sm-10'>
                              $rows[no_hp]
                            </div>
                            </div>
                            
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Tmp/Tgl Lahir</b></label>
                              <div class='col-sm-10'>
                                ".($rows['tempat_lahir'] == '' ? '-' : $rows['tempat_lahir'])." / ".($rows['tanggal_lahir'] == '0000-00-00' ? '-' : tgl_indo($rows['tanggal_lahir']))."
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Alamat</b></label>
                              <div class='col-sm-10'>
                                ".($rows['alamat_lengkap'] == '' ? '<i style="color:#cecece">Wajib Diisi untuk berbelanja,..</i>' : $rows['alamat_lengkap'])."
                              </div>
                            </div>
                            
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Daerah</b></label>
                            <div class='col-sm-10'>
                              ".(kecamatan($rows['kecamatan_id'],$rows['kota_id']) == '' ? '<i style="color:#cecece">Wajib Diisi untuk berbelanja,..</i>' : kecamatan($rows['kecamatan_id'],$rows['kota_id']))."
                            </div>
                            </div>";


                          
                        ?>
                    </figure>
                </div>
              
            </div>
        </div>
    </div>
</div>











