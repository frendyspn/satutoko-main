<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li><a href="">PPOB</a></li>
                <li style="text-transform:capitalize"><?= $title; ?></li>
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
                      $rows = $this->db->query("SELECT maps FROM identitas where id_identitas='1'")->row_array();
                      if ($rows['maps']!='|' AND trim($rows['maps'])!='||'){
                          if(isset($_GET['ppob']) AND !isset($_POST['submit'])){ 
                            echo "<div class='ps-block--site-features' style='margin-bottom:20px; background-color:#fff; border: none; padding: 10px 0px;'>
                                    <form class='col-md-12' style='padding-right:0px; padding-left:0px' method='POST' action='".base_url()."members/trx_pulsa?ppob=".cetak($_GET['ppob'])."'>
                                        <div class='form-group'>
                                        <label>Jenis Tagihan</label>
                                          <select name='operator' class='form-control' required>
                                          <option value=''>- Pilih -</option>";
                                            $payload = [
                                              'category_id'=>$_GET['ppob'], //Tidak wajib
                                            ];
                                            $operator = tripay_pascabayar($payload,config('ppob_url').'/v2/pembayaran/produk','GET');
                                            foreach($operator->data as $item){
                                                if ($item->status!='0'){
                                                    echo "<option value='".$item->code."' $status>".$item->product_name." (Fee admin ".rupiah($item->biaya_admin).")</option>";
                                                }
                                            }
                                          echo "</select>
                                        </div>";
                                        
                                        //print_r($operator);
                                            
                                        echo "<div class='form-group'>
                                        <label>ID Pelanggan</label>
                                            <input type='text' name='id_pelanggan' class='form-control' required>
                                        </div>
                 
                                        <div class='form-group'>
                                        <label>Nomor Hp</label>
                                          <input type='text' name='tujuan' class='form-control' required>
                                        </div>
                                        <button type='submit' name='submit' class='ps-btn margin-btn spinnerButton'>Cek Tagihan</button>
                                    </form>
                                </div>
                                <div style='clear:both'></div>";
                            echo "</div>";
                        }
                      }
                      
                      if (isset($_POST['submit'])){
                         echo "<h4>Detail Tagihan</h4><br>";
                         
                        $maps = explode('|',$row['maps']);
                        $payload = [
                            'product' => cetak($this->input->post('operator')),
                            'phone'   => cetak($this->input->post('tujuan')),
                            'no_pelanggan' => cetak($this->input->post('id_pelanggan')),
                            'api_trxid'    => 'PPOB/'.$this->session->id_konsumen.'/'.date('YmdHis'),
                            'pin'       => $maps[1]  
                        ];
                        
                        //print_r($operator);
                        
                            if ($operator->data->status=='0'){ 
                                $status = 'PENDING'; 
                            }elseif ($operator->data->status=='1'){ 
                                $status = 'SUKSES'; 
                            }elseif ($operator->data->status=='2'){ 
                                $status = 'GAGAL - Saldo dikembalikan'; 
                            }elseif ($operator->data->status=='3'){
                                $status = 'REFUND - Saldo dikembalikan'; 
                            }else{ 
                                $status = '-'; 
                            }
                        
                            echo "<form class='col-md-12' style='padding-right:0px; padding-left:0px' action='".base_url()."members/trx_pulsa?ppob=".cetak($_GET['ppob'])."'>
                                
                                <div class='form-group row' style='margin-bottom:5px; background: #efefef;'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>ID Pelanggan</label>
                                  <div class='col-sm-10'>
                                    ".$operator->data->no_pelanggan."
                                    <span class='pull-right'>
                                        ".jam_tgl_indo($operator->data->created_at)."
                                    </span>
                                    </div>
                                </div>
                            
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Nama</label>
                                  <div class='col-sm-10 font-weight-bold' style='border-bottom:1px dotted #cecece'>
                                    ".$operator->data->nama."
                                    </div>
                                </div>
                                
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Nomor Hp</label>
                                  <div class='col-sm-10' style='border-bottom:1px dotted #cecece'>
                                    ".$operator->data->phone."
                                    </div>
                                </div>
                                
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Detail Produk</label>
                                  <div class='col-sm-10' style='border-bottom:1px dotted #cecece'>
                                    ".$operator->data->product_name."
                                    </div>
                                </div>
                                
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Jumlah Tagihan</label>
                                  <div class='col-sm-10' style='border-bottom:1px dotted #cecece'>
                                    Rp ".rupiah($operator->data->jumlah_tagihan)." 
                                    </div>
                                </div>
                                
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Fee Admin</label>
                                  <div class='col-sm-10' style='border-bottom:1px dotted #cecece'>
                                    Rp ".rupiah($operator->data->biaya_admin)."
                                    </div>
                                </div>
                                
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Jumlah Bayar</label>
                                  <div class='col-sm-10 alert text-danger font-weight-bold' style='border-bottom:1px dotted #cecece'>
                                    Rp ".rupiah($operator->data->jumlah_bayar)."
                                    </div>
                                </div>
                                
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Periode</label>
                                  <div class='col-sm-10' style='border-bottom:1px dotted #cecece'>
                                    ".tgl_indo($operator->data->periode)."
                                    </div>
                                </div>
                                
                                <div class='form-group row' style='margin-bottom:5px'>
                                <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Status</label>
                                  <div class='col-sm-10' style='border-bottom:1px dotted #cecece'>
                                    ".$status."
                                    </div>
                                </div><br><br>
                        
                                <button type='submit' name='bayar' class='ps-btn margin-btn spinnerButton'>Bayar Tagihan</button>
                            </form>";
                      }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

<div class="modal fade bd-example-modal-lg ppob-modal" style='z-index:99999' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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

<script>
    $(function(){
        $(document).on('click','.cekstatus',function(e){
            e.preventDefault();
            $(".ppob-modal").modal('show');
            $.post("<?php echo site_url()?>members/trx_pulsa_komplain",
                {id:$(this).attr('data-id')},
                function(html){
                    $(".content-body").html(html);
                }   
            );
        });
    });
</script>         
<script>
function belippob(produk){
  var tujuan = $('#tujuan').val();
  var id_pelanggan = $('#id_pelanggan').val();
  if (tujuan==''){
    $('#tujuan').focus();
  }else{
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('main/proses?trx_pulsa=1')?>",
        dataType : "JSON",
        data : {tujuan:tujuan,produk:produk,id_pelanggan:id_pelanggan},
        beforeSend: function(){
            // Show image container
            $("#loader").show();
            $(".ppob").hide();
            $("#historytrx").hide();
        },
        success: function(data){
          $('#id_pelanggan').val("");
          $('#operator').val("");
        },
        complete:function(response){
            // Hide image container
            $("#loader").hide();
            $("#historytrx").hide().load(" #historytrx").fadeIn();
            $('#id_pelanggan').val("");
            $('#operator').val("");
            $("#respon").html(response.responseText);
        }
    });
    return false;
  }
}
</script>
