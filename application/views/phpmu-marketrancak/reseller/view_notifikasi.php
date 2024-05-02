<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li><?= $title; ?></li>
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
                      <div class="btn-group btn-block" style='border-bottom:1px dotted #e3e3e3' role="group">
                          <button class="btn btn-default text-success rounded-0 font-weight-bold" onclick="dibaca_all(<?= $this->session->id_konsumen; ?>)" style='font-size:12px; padding: 10px 20px; border-right:1px solid #e3e3e3'>Tandai Semua dibaca</button>
                          <a class="btn btn-default text-success rounded-0 font-weight-bold" style='font-size:12px; padding: 10px 20px' href="<?= base_url(); ?>members/notifikasi">Refresh Halaman</a>
                      </div>
                      <div style='clear:both'><br></div>
                      <dl class='notifikasi_all2'>
                      <?php 
                          foreach ($notifikasi->result_array() as $row) {
                              if (strlen($row['konten']) > 65){ $konten = strip_tags(substr($row['konten'],0,65)).',..';  }else{ $konten = strip_tags($row['konten']); }
                              echo "<div class='m-0 notifikasi2-$row[id_notifikasi_send]'>
                                  <div class='notifikasi_box' style='background:".($row['dibaca']=='N'?'#dcf9e4':'#ffffff')."'><a href='$row[url]'>
                                      <dt> $row[judul]</dt>
                                      <dd style='border-bottom:2px dotted #cecece; margin:0px; padding:5px 0px 10px 0px'>
                                      <small class='text-success font-weight-bold'><i class='fa fa-clock-o'></i> ".cek_terakhir($row['waktu_kirim'])." lalu</small>
                                      <input class='float-right' type='checkbox' id='$row[id_notifikasi_send]' onclick=\"dibaca('Y',this.id)\" ".($row['dibaca']=='N'?'':'checked disabled').">
                                      <p>$row[konten]</p></dd>
                                  </a>
                                  </div>
                                  </div>";
                          }
                      ?>
                      </dl>
                    </figure>
                    <div class="ps-pagination">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>