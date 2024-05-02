<style>
.inputx {
    width: 45px;
    height: 45px;
    background-color: lighten(#0f0f1a, 5%);
    line-height: 45px;
    text-align: center;
    font-size: 24px;
    font-family: 'Raleway', sans-serif;
    font-weight: 200;
    margin: 0 2px;
    border:1px solid #cecece;
    margin-bottom:5px
}
.ps-tab-list{
    background: #f1f1f1;
}
.ps-footer {
    padding-top: 0px;
}
.ps-footer__copyright p {
    margin: 0 auto;
}
.ps-form--account .ps-tab {
    border-radius: 10px 10px 0px 0px;
}
.ps-form--account .ps-tab-list li.active{
    color:#000;
}
</style>
<script>
$(document).ready(function(){
$('.digit-group').find('input').each(function() {
	$(this).attr('maxlength', 1);
	$(this).on('keyup', function(e) {
		var parent = $($(this).parent());
		
		if(e.keyCode === 8 || e.keyCode === 37) {
			var prev = parent.find('input#' + $(this).data('previous'));
			if(prev.length) {
				$(prev).select();
			}
		} else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
			var next = parent.find('input#' + $(this).data('next'));
			
			if(next.length) {
				$(next).select();
			} else {
				if(parent.data('autosubmit')) {
					parent.submit();
				}
			}
		}
	});
});
});
</script>

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
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 d-none d-sm-block">
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
                            $attributes = array('id' => 'formku','class'=>'digit-group');
                            echo form_open_multipart('members/otp',$attributes); 
                            if ($row['no_hp']!=''){
                                $wa_notif = "WA : <b>+".format_telpon($row['no_hp'])."</b> dan";
                            }else{
                                $wa_notif = "";
                            }
                            echo "<p style='font-size:17px'>Hai! Kode verfikasi telah dikirim ke $wa_notif Email <b>$row[email]</b>.<br>
                            Jika Anda belum menerimanya, klik <a href='".base_url()."members/otp?resend'><u>disini</u></a></p><br><br>

                            <div class='form-group row' style='margin-bottom:5px'>
                              <div class='col-sm-12'>";

                              echo "<b>Two Factor Code</b>
                              <div>
                                <input class='inputx' type='text' id='digit-1' name='kode[]' value='".substr($this->session->pin,0,1)."' data-next='digit-2' autofocus/>
                                <input class='inputx' type='text' id='digit-2' name='kode[]' value='".substr($this->session->pin,1,1)."' data-next='digit-3' data-previous='digit-1' />
                                <input class='inputx' type='text' id='digit-3' name='kode[]' value='".substr($this->session->pin,2,1)."' data-next='digit-4' data-previous='digit-2' />
                                
                                <input class='inputx' type='text' id='digit-4' name='kode[]' value='".substr($this->session->pin,3,1)."' data-next='digit-5' data-previous='digit-3' />
                                <input class='inputx' type='text' id='digit-5' name='kode[]' value='".substr($this->session->pin,4,1)."' data-next='digit-6' data-previous='digit-4' />
                                <input class='inputx' type='text' id='digit-6' name='kode[]' value='".substr($this->session->pin,5,1)."' data-previous='digit-5' />
                              </div><br>";


                              echo "<input type='checkbox' name='remember' checked> Don't ask again for 30 days
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                              <div class='col-sm-12'><br>
                                <button style='width:150px' class='ps-btn spinnerButton' type='submit' name='submit'>Verifikasi</button>
                                <a class='ps-btn ps-btn--outline' href='".base_url()."auth/logout'>Logout</a>
                              </div>
                            </div>

                            </form>";
                            
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