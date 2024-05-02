<div class="ps-page--single">
<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>">Home</a></li>
            <li><a href="#">Members</a></li>
            <li><?php echo $title; ?></li>
        </ul>
    </div>
</div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
      <div class="ps-section__content">
      <?php include "menu-members.php"; ?>
        <?php 
          echo $this->session->flashdata('message'); 
          $this->session->unset_userdata('message');
        ?>

        <div class="row">
          <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
              <?php
                include "sidebar-members.php";
                echo "<a href='".base_url()."members/tambah_withdraw' class='ps-btn btn-block'><i class='icon-pen'></i> Buat Permintaan</a>";
              ?><div style='clear:both'><br></div>
          </div>

          <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">

          <?php 
            $cp = $this->db->query("SELECT * FROM rb_withdraw where id_withdraw='".cetak($_GET['acc'])."'")->row_array();
            if (explode('/',$cp['keterangan'])[0]=='TP'){
              $cpd = $this->db->query("SELECT * FROM rb_tripay where kode_transaksi='".cetak($_GET['acc'])."'")->row_array();
                  //echo "Bayar disini : https://tripay.co.id/checkout/$cp[catatan]";
                  echo "<p style='font-size:18px' class='text-center'>Pembayaran Dengan <b>$cpd[payment_name]</b></p>
                  <div class='alert alert-danger text-center'><strong>PENTING</strong> - Pastikan anda melakukan pembayaran sebelum melewati batas <br>
                                                          pembayaran dan dengan nominal yang tepat</div>
                                                          
                  
                  <form>
                  <div class='form-row align-items-center'>
                      <div class='col-12 mb-3'>
                          <label style='color:#878787'>Nomor Referensi</label>
                          <div class='input-group'>
                          <input type='text' style='border-right:none;font-size:16px' class='form-control' value='$cpd[reference]'>
                          <div class='input-group-prepend'>
                              <div class='input-group-text' style='background:transparent;border-left:none'><i style='font-size:22px;color: #bdbdbd;' class='fa fa-copy'></i></div>
                          </div>
                          </div>
                      </div>

                      <div class='col-12 mb-3'>
                          <label style='color:#878787'>Kode Bayar/Nomor VA</label>
                          <div class='input-group'>
                          <input type='text' style='border-right:none;font-size:16px' class='form-control' value='$cpd[pay_code]'>
                          <div class='input-group-prepend'>
                              <div class='input-group-text' style='background:transparent;border-left:none'><i style='font-size:22px;color: #bdbdbd;' class='fa fa-copy'></i></div>
                          </div>
                          </div>
                      </div>

                      <div class='col-12 mb-3'>
                          <label style='color:#878787'>Jumlah Tagihan + Fee Rp ".rupiah($cpd['amount']-$cp['nominal'])."</label>
                          <div class='input-group'>
                          <div class='input-group-prepend'>
                              <div class='input-group-text' style='background:transparent;border-left:none'><span style='font-size:20px;color: #bdbdbd;'>Rp</span></div>
                          </div>
                          <input type='text' style='border-right:none;font-size:16px' class='form-control' value='".rupiah($cpd['amount'])."'>
                          <div class='input-group-prepend'>
                              <div class='input-group-text' style='background:transparent;border-left:none'><i style='font-size:22px;color: #bdbdbd;' class='fa fa-copy'></i></div>
                          </div>
                          </div>
                      </div>

                      <div class='col-12 mb-3'>
                          <label style='color:#878787'>Batas Pembayaran </label>
                          <div class='input-group'>
                              <span style='color:#FF5A92; font-size:18px'>".jam_tgl_indo_day(date('Y-m-d H:i:s', $cpd['expired_time']))."</span>
                          </div>
                      </div>
                  </div><br>
                  
                  <div class='row'>
                    <div class='col-12 col-md-6'>
                        <a target='_BLANK' style='margin-bottom:10px; padding:5px 25px' class='ps-btn' href='#' data-toggle='modal' data-target='#petunjuk'>Cara Pembayaran</a>
                        <a style='margin-bottom:10px; padding:5px 25px' class='ps-btn ps-btn--outline tripay' data-id='3' data-id1='$cp[id_withdraw]' href='#' >Ganti Metode</a>
                    </div>
                  </div>";

                  
                  //var_dump(json_decode($cpd['instructions'], true));
                  echo "<div style='z-index: 9999;' class='modal fade bd-example-modal-lg' id='petunjuk' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' aria-hidden='true'>
                      <div class='modal-dialog modal-dialog-centered' role='document'>
                          <div class='modal-content' style='padding:10px'>
                              <div class='modal-header'>
                                  <h5 class='modal-title h4' style='font-size:1.7rem' id='myModalLabel'>Petunjuk Pembayaran $cpd[payment_name]</h5>
                                  <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                              </div>
                              <div class='modal-body'>
                                      <div id='accordion'>";
                                      $obj = json_decode($cpd['instructions'], true);
                                      $num = 1;
                                      foreach ($obj as $key => $val) {
                                          echo "<div class='card'>
                                              <div class='card-header' id='heading$num'>
                                                  <h5 class='mb-0'>
                                                  <a class='btn btn-link' data-toggle='collapse' data-target='#collapse$num' aria-expanded='true' aria-controls='collapse$num'>
                                                      $val[title]
                                                  </a>
                                                  </h5>
                                              </div>";

                                              echo "<div id='collapse$num' class='collapse ".($num==1?'show':'')."' aria-labelledby='heading$num' data-parent='#accordion'>
                                              <div class='card-body'>
                                                  <ol>";
                                                  for($i=0; $i < count($val['steps']); $i++){
                                                      echo "<li>".$val['steps'][$i]."</li>";
                                                  }
                                                  echo "</ol>
                                              </div></div>
                                          </div>";
                                          $num++;
                                      }
                                      echo "</div>
                                      </div>
                                  <div class='modal-footer'>
                                      <button type='button' class='btn btn-secondary' data-dismiss='modal'>Tutup</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>";
            }else{
          ?>


        <div class='table-responsive'>
        <table id="example1" class="table table-striped table-sm iconset">
          <thead>
            <tr>
              <th style='width:20px'>No</th>
              <th colspan='3'>Keterangan</th>
              <th>Nominal</th>
              <th>Status</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
        <?php 
          $no = 1;
          foreach ($record->result_array() as $row){
          if ($row['transaksi']=='debit'){ $color =  'red'; $icon =  '<span style="padding: 5px 10px; border: 1px solid #cecece;" class="fa fa-arrow-down"></span>'; }else{ $color = 'green'; $icon =  '<span style="padding: 5px 10px; border: 1px solid #cecece;" class="fa fa-arrow-up"></span>'; }
          if ($row['status']=='Sukses'){ $color_status =  'green'; }else{ $color_status = 'orange'; }
          $ex = explode(';',$row['keterangan']);
          echo "<tr><td>$no</td>";
                  if ($row['id_rekening_reseller']=='0' AND $row['transaksi']=='debit'){
                    $cek_status = $this->db->query("SELECT id_penjualan FROM rb_penjualan where kode_transaksi='$row[keterangan_order]' AND status_pembeli='konsumen'");
                    if ($cek_status->num_rows()>=1){
                      $url_order = "konfirmasi/tracking/$row[keterangan_order]";
                    }else{
                      if ($row['akun']=='konsumen'){
                        $exk = explode('-',$row['keterangan_order']);
                        $kode_transaksi = trim($exk[0]);
                        $url_order = "konfirmasi/tracking/".$kode_transaksi;
                      }else{
                        $rew = $this->db->query("SELECT id_penjualan FROM rb_penjualan where kode_transaksi='$row[keterangan_order]' AND status_pembeli='reseller'")->row_array();
                        $url_order = "members/detail_pembelian/$rew[id_penjualan]";
                      }
                      
                    }

                    if (substr($row['keterangan_order'],-1)=='%'){
                      echo "<td><small style='color:green'>".jam_tgl_indo($row['waktu_withdraw'])."</small><br>Bayar Komisi</td>";
                      $km = explode(' - ',$row['keterangan_order']);
                      echo "<td><a style='color:blue; text-decoration:underline' target='_BLANK' href='".base_url()."konfirmasi/tracking/$km[0]'>$km[0]</a></td>
                          <td>$km[1]</td>";
                    }else{
                      $expp = explode(' ',$row['keterangan_order']);
                      if ($expp['1']=='Dipotong'){
                        echo "<td>$row[keterangan_order]</td>";
                        echo "<td>-</td>
                              <td>-</td>";
                      }else{
                        echo "<td><small style='color:green'>".jam_tgl_indo($row['waktu_withdraw'])."</small><br>Pembayaran Order</td>";
                        echo "<td><a style='color:blue; text-decoration:underline' target='_BLANK' href='".base_url().$url_order."'>$row[keterangan_order]</a></td>
                          <td>-</td>";
                      }
                    }

                  }else{
                    if ($row['transaksi']=='debit'){
                      echo "<td><small style='color:green'>".jam_tgl_indo($row['waktu_withdraw'])."</small><br>".bank($ex[0])."</td>
                            <td>$ex[1] <br> $ex[2]</td>
                            <td></td>";
                    }elseif ($row['transaksi']=='kredit' AND $row['id_rekening_reseller']=='0'){
                      echo "<td><small style='color:green'>".jam_tgl_indo($row['waktu_withdraw'])."</small><br>Deposit Payment Gateway</td>";
                            echo "<td>
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                                  Pay. Gateway
                                </button>
                                <div class='dropdown-menu' style='font-size: 1.4rem; min-width:100%'>";
                                    if (config('ipaymu_aktif')=='Y'){
                                      echo "<a target='_BLANK' class='dropdown-item ipaymu' href='".base_url()."konfirmasi/deposit?inv=$row[id_withdraw]'>Ipaymu</a>";
                                    }else{
                                      echo "<a class='dropdown-item ipaymu' href='#' onclick=\"return confirm('Pembayaran via Ipaymu tidak aktif!')\">Ipaymu</a>";
                                    }
                                    
                                    if (config('tripay_aktif')=='Y'){
                                      echo "<a target='_BLANK' class='dropdown-item tripay' data-id='3' data-id1='$row[id_withdraw]' href='#'>Tripay</a>";
                                    }else{
                                      echo "<a class='dropdown-item ipaymu' href='#' onclick=\"return confirm('Pembayaran via Tripay tidak aktif!')\">Tripay</a>";
                                    }
                                    
                                    if (config('xendit_aktif')=='Y'){
                                      echo "<a target='_BLANK' class='dropdown-item xendit' href='".base_url()."konfirmasi/deposit_xendit?inv=$row[id_withdraw]'>Xendit</a>";
                                    }else{
                                      echo "<a class='dropdown-item ipaymu' href='#' onclick=\"return confirm('Pembayaran via Xendit tidak aktif!')\">Xendit</a>";
                                    }
                                echo "</div>
                            </div>
                            </td>
                            <td></td>";
                    }else{
                      $rek = $this->model_app->view_where('rb_rekening',array('id_rekening'=>$row['id_rekening_reseller']))->row_array();
                      echo "<td><small style='color:green'>".jam_tgl_indo($row['waktu_withdraw'])."</small><br>$rek[nama_bank]</td>
                            <td>$rek[no_rekening] <br> $rek[pemilik_rekening]</td>
                            <td></td>";
                    }
                  }
                    echo "<td><b>".rupiah($row['nominal']-$row['withdraw_fee'])."</b></td>
                    <td style='color:$color_status'><i>$row[status]</i></td>
                    <td style='color:$color'>$icon</td>
                    <td><a class='ps-btn detail-keuangan' data-id='$row[id_withdraw]' href='#'><i class='icon-magnifier'></i></a></td>
                </tr>";
            $no++;
          }
        ?>
        </tbody>
      </table>
      </div>
      <?php } ?>
      </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg detail-modal" style='z-index:99999' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
<div class="modal-content">
    <div class="modal-header" style='border-bottom:0px solid #e9ecef'>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
      <div class='content-body' style='padding:0px 20px'></div>
    </div>
</div>
</div>
</div>

<div style='z-index: 9999;' class="modal fade bs-example-modal-lg" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" style='font-size:1.7rem' id="myModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
          <div class="modal-body">
            <div class="content-body"></div>
          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
    $(function(){
        $(document).on('click','.tripay',function(e){
            e.preventDefault();
            $("#myModalDetail").modal('show');
            $.post("<?php echo site_url()?>konfirmasi/pembayaran",
                {id:$(this).attr('data-id'),trx:$(this).attr('data-id1')},
                function(html){
                    //console.log(html);
                    $(".content-body").html(html);
                }   
            );
        });
    });
</script>

<script>
    $(function(){
        $(document).on('click','.detail-keuangan',function(e){
            e.preventDefault();
            $(".detail-modal").modal('show');
            $.post("<?php echo site_url()?>members/detail_keuangan",
                {id:$(this).attr('data-id')},
                function(html){
                    $(".content-body").html(html);
                }   
            );
        });
    });
</script>