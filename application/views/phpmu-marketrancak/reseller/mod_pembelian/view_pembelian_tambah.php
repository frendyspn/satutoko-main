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
<div class="ps-section--shopping ps-shopping-cart">
<div class="container">
<?php 
echo $this->session->flashdata('message'); 
$this->session->unset_userdata('message');
?>
<div class="ps-section__content">
<div class="table-responsive">
<?php echo "<form action='".base_url().$this->uri->segment(1)."/tambah_pembelian' class='form-horizontal refreshx' id='formid' method='POST'>"; ?>
<div class='keranjang-all'>

      <div class="row">
        <div class='col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12'>
        <?php 
          $cek_keranjang = $this->db->query("SELECT * FROM rb_penjualan_detail where id_penjualan='".$this->session->idpx."'");
          if ($cek_keranjang->num_rows()<=0){
            echo "<a class='ps-btn ps-btn--outline ps-btn--fullwidth' style='margin-bottom:5px' href='".base_url()."produk/perusahaan'>Lihat Produk Perusahaan</a><hr>
                  <center style='padding:50px 0px'>Wahh Keranjang Belanjaan-mu  Masih Kosong...<br>
                  <a style='font-weight:700; text-decoration:underline' href='".base_url()."produk/perusahaan'>Yuk Mulai belanja!</a></center>";
          }else{
            echo "<a class='ps-btn ps-btn--outline ps-btn--fullwidth' style='margin-bottom:5px' href='".base_url()."produk/perusahaan'>Lihat Produk Perusahaan Lainnya</a><hr>";
          }

          $no = 1;
          foreach ($record as $row){
            $ex = explode(';', $row['gambar']);
            if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
            $harga_produk =  "Rp ".rupiah($row['harga_jual']-$row['diskon']);

            echo "<div class='ps-product--cart-mobile' style='padding:5px 0'>
                  <input type='hidden' name='id$i' value='$row[id_penjualan_detail]'>
                  <input type='hidden' name='idp$i' value='$row[id_produk]'>

                  <label class='container-checkbox'>
                    <input type='checkbox' name='pilih$i' disabled checked>
                    <span class='checkmark'></span>
                  </label>
                    <div class='ps-product__thumbnail'>
                      <img style='float:left; width:100px; margin-right:10px' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'>
                    </div>
                    <div class='ps-product__content'>
                      <a href='".base_url().$this->uri->segment(1)."/delete_pembelian_tambah_detail/$row[id_penjualan_detail]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\" class='ps-product__remove item_delete' style='cursor:pointer'><i class=icon-cross></i></a>";
                      if ($row['pre_order']!='' AND $row['pre_order']>0){
                        echo "<p style='margin-bottom:0'> <span class='badge badge-secondary'>Pre-order $row[pre_order] Hari</span> </p>";
                      }
                      echo "<a href='".base_url()."produk/perusahaan_detail/$row[produk_seo]'>
                        <span style='font-size:15px; display:block; font-weight:400'>$row[nama_produk]</span>
                      </a>
                      <p style='border-bottom:1px dotted #cecece; margin-bottom:0px'><b>Qty.</b> 
                      <small><input type='number' class='qty_update' qty min='1' name='qty$i' value='$row[jumlah]'  id='qty$row[id_penjualan_detail]' onchange=\"qtyx($row[id_penjualan_detail]);\" style='display:inline-block; margin-bottom:3px; width:50px; text-align: center;'  autocomplete=off> x <b>$harga_produk</b></small></p>";
                        $exp = explode('||',$row['keterangan_order']);
                        if (trim($exp[1])!=''){
                          echo "<b>Variasi</b> : $exp[1]<br>";
                        }
                      echo "<input type='text' name='keterangan$i' value='$exp[0]' id='ctt$row[id_penjualan_detail]' onchange=\"qtyx($row[id_penjualan_detail]);\" style='display:inline-block; margin-bottom:3px; width:100%; border:1px dotted #cecece' placeholder='Tulis Catatan untuk Produk ini' autocomplete=off>

                      </div>
                  </div>";
            $i++;
          }

          echo "<hr><br><br>";
          $total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='".$this->session->idpx."'")->row_array();
          if (saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen)>=$total['total']){
            $disabled = '';
            $checked = 'checked';
          }else{
            $disabled = 'disabled';
            $checked = '';
          }
        
        $totalx = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='".$this->session->idpx."'")->row_array();
        $proses = '<i class="text-danger">Pending</i>'; 
        echo "</div>
        <div class='col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12'>
            <div class='ps-block--shopping-total'>
            <div class='keranjang-page refresh'>
                <div class='ps-block__header'>
                    <p style='margin-bottom:0px'><b>Status Order<span> $proses</span></b></p><hr>
                    <p style='margin-bottom:0px'>Berat<span> $totalx[total_berat] Gram</span></p>
                    <p style='margin-bottom:0px'>Fee Admin <span>Rp 0</span></p>
                    <p>Subtotal <span> Rp ".rupiah($total['total'])."</span></p>
                </div>

                <label style='border:1px solid #cecece; padding:5px 10px; cursor:pointer' class='d-block'>
                <i class='fa fa-credit-card' style='font-size: 35px; margin-right: 5px; float: left'></i>
                <input class='float-right' style='margin-top: 10px' type='checkbox' name='metode' id='saldo'  value='saldo'  $checked $disabled>  <b>Pakai Saldo</b> 
                <small style='display:block'>Saldo saat ini <span style='color:red'>Rp ".rupiah(saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen))."</span></small></label>
                <hr>

                <div class='ps-block__content'>
                    <h3>Total <span>Rp ".rupiah(($total['total']))."</span></h3>
                </div><br>
            </div>
            </div>";
            if ($cek_keranjang->num_rows()>0){
              echo "<button type='submit' id='oksimpan' name='selesai' style='padding:15px 0; font-size:16px' class='ps-btn ps-btn--fullwidth'>Selesai dan Proses <i class='icon-arrow-right'></i></button>";
            }
        
            if ($this->session->idpx!=''){ echo "<a style='margin-top:5px' href='".base_url()."members/delete_pembelian/".$this->session->idpx."' class='ps-btn ps-btn--outline ps-btn--fullwidth'>Batalkan Transaksi</a>"; }
            echo "</div>
          </div>
      
</div>
</div>
</div>
</div>
</div>
</form>";
?>
             

<script type="text/javascript">    
<?php echo $jsArray; ?>  
  function changeValue(id){  
    document.getElementById('harga').value = prdName[id].name;  
    document.getElementById('satuan').value = prdName[id].desc;  
  };  

function qtyx(id){
  var qty = $('#qty'+id).val();
  var ctt = $('#ctt'+id).val();
  $.ajax({
    type: "POST",
    url: "<?php echo site_url('produk/qty_produk_perusahaan')?>",    
    dataType : "JSON",               
    data:{id:id,qty:qty,ctt:ctt},                 
    success: function (data) {
      if(data.pesan=='x'){  
        $(".refresh").hide().load(" .refresh").fadeIn();
      }else{
        $('#Modal_Notif').modal('show');
        $('#error_notif').html(data.pesan);
        $(".refresh").hide().load(" .refresh").fadeIn();
        $(".refreshx").hide().load(" .refreshx").fadeIn();
      }
    }
  });

}
</script> 