<style>
  .ppob .col-lg-3 .box{
    border: 1px solid #e3e3e3;
    padding: 5px 10px;
    margin-bottom: 10px;
    display: block;
    text-align:center;
    text-transform:capitalize;
  }

  .ppob .col-lg-3 .box .fa{
    font-size:50px;
  }

  .alert-ppob{
    text-transform:uppercase; 
    padding:10px 20px;
    font-weight:600;
    margin-bottom:10px;
    font-size:18px;
    background:#f4f4f4
  }
</style>

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
            <?php include "menu-members.php"; 
                echo $this->session->flashdata('message'); 
                $this->session->unset_userdata('message');
            ?>
            <div id='respon'></div>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <div class="ps-section__left">
                      <?php 
                        include "sidebar-members.php";
                        include "view_ppob_dashboard.php"; 
                      ?>
                    </div>
                  <div style='clear:both'><br></div>
                </div>
                
                <div class='col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 biodata notif'>
                        <?php 
                          echo "<div class='alert-ppob'> Prabayar</div>
                          <div class='row ppob' style='margin-bottom:50px'>";
                            $rows = $this->db->query("SELECT maps FROM identitas where id_identitas='1'")->row_array();
                            
                            // Kategori Prabayar
                            // $kategori1 = tripay($payload,'https://tripay.id/api/v2/pembelian/category');
                            // foreach($kategori1->data as $item){

                            $kategori1 = $this->db->query("SELECT * FROM rb_ppob where jenis='prabayar' AND aktif='1'");
                            foreach($kategori1->result() as $item){  
                              $this->db->query("UPDATE rb_ppob SET nama_ppob='.strtolower($item->nama_ppob).' where id_ppob=''");
                              if ($item->icon_kode!=''){
                                $icon = "<i style='font-size:50px' class='".$item->icon_kode."'></i>";
                              }elseif ($item->icon_image!=''){
                                  $icon = "<center><img style='width:55px; height:55px; margin-bottom:3px' src='".base_url()."asset/images/".$item->icon_image."'></center>";
                              }else{
                                  $icon = "<center><img style='width:55px; height:55px; margin-bottom:3px' src='".base_url()."asset/images/ppob.jpg'></center>";
                              }

                              echo "<div class='col-lg-3 col-6'>
                                <a class='box' href='".base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'?ppob='.$item->id_api."'>$icon <span style='display:block'>".($item->nama_ppob)."</span></a>
                              </div>";
                            }
                            echo "</div>";

                            // Kategori Pascabayar
                            echo "<div class='alert-ppob'> Pascabayar</div>
                              <div class='row ppob' style='margin-bottom:50px'>";

                              //$kategori2 = tripay($payload,'https://tripay.id/api/v2/pembayaran/category');
                              //foreach($kategori2->data as $itemx){

                              $kategori2 = $this->db->query("SELECT * FROM rb_ppob where jenis='pascabayar' AND aktif='1'");
                              foreach($kategori2->result() as $itemx){  
                                if ($itemx->icon_kode!=''){
                                  $icon = "<i style='font-size:50px' class='".$itemx->icon_kode."'></i>";
                                }elseif ($itemx->icon_image!=''){
                                    $icon = "<center><img style='width:55px; height:55px; margin-bottom:3px' src='".base_url()."asset/images/".$itemx->icon_image."'></center>";
                                }else{
                                  $icon = "<center><img style='width:55px; height:55px; margin-bottom:3px' src='".base_url()."asset/images/ppob.jpg'></center>";
                                }
                                echo "<div class='col-lg-3 col-6'>
                                  <a class='box' href='".base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'?ppob='.$itemx->id_api."'>$icon <span style='display:block'>".($itemx->nama_ppob)."</span></a>
                                </div>";
                              }
                            echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

