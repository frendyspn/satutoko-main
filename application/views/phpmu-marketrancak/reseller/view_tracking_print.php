
<html>
<head>
<title>Invoice <?= $judul; ?></title>

<link rel="stylesheet" href="https://members.phpmu.com/asset/css/members-phpmu-com.min.css">
<style>
table#tablestd{
	border-width: 1px;
	border-style: solid;
	border-color: #D8D8D8;
	border-collapse: collapse;
	margin: 10px 0px;
}
table#tablestd td{
	padding: 0.5em; 	color: #000;
	vertical-align: top;
	border-width: 0px;
	padding: 4px;
	border: 1px solid #000;
	
}

table#tablemodul1{
	border-width: 1px;
	border-style: solid;
	border-color: #000;
	border-collapse: collapse;
	margin: 10px 0px;
}
table#tablemodul1 td{
	padding:1px 6px 2px 6px !important;
	border: 1px solid #000; 
	
}
</style>
</head>
<body onload="window.print()">

<section class="invoice animated fadeIn">
<?php 
$logo = $this->model_utama->view_ordering_limit('logo', 'id_logo', 'DESC', 0, 1)->row_array(); 

?>
<img alt="Phpmu.com" src="<?php echo base_url()."asset/logo/".$logo['gambar']; ?>" class="img-responsive">
<div style='clear:both'><br></div>
      <!-- info row -->
      <div class="row invoice-info">
        <b>INVOICE UNTUK :</b> <br><a href='#'><?php echo $rows['nama_lengkap']; ?></a><div style='clear:both'><br></div>
        
        <div style='float:right; text-align:left'>
            <b>Status:</b> <?php echo status($total['proses']); ?><br>
            <b>Invoice: <?= $judul; ?></b><br>
            <b>Order ID:</b> <?php echo $rows['id_penjualan']."/".$rows['id_pembeli']."/".$rows['id_penjual']; ?><br>
            <b>Date Time :</b> <?php echo jam_tgl_indox($rows['waktu_transaksi']); ?>
        </div>
        
        <div style='float:left; text-align:left; width: 350px;'>
          <address>
          <?php 
            $nama_lengkap = $rows['nama_lengkap'];
            $no_telpon = substr($rows['no_hp'], 0, -2);
            $angka_acak = substr($judul,-2);
          ?>

            <?php echo alamat($judul); ?><br>
            <b>Hp:</b> <?php echo substr($no_telpon, 0, -2).'XX'; ?><br>
          </address>
        </div>      </div>
<div style='clear:both'><br></div>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table id='tablemodul1'  width='100%'>
            <thead>
                <tr bgcolor="#e3e3e3">
                    <th>No</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $no = 1;
            foreach ($record->result_array() as $row){
                $cku = $this->db->query("SELECT * FROM rb_penjualan_kupon where id_penjualan_detail='$row[id_penjualan_detail]'")->row_array();
                $sub_total = (($row['harga_jual']-$row['diskon'])*$row['jumlah']);
                $ext = explode(';', $row['gambar']);
                //   if ($row['preorder']!='' AND $row['preorder']>0){
                //     $pre_order = "<span class='badge badge-secondary'>Pre-Order $row[preorder] Hari</span>";
                //   }else{
                //     $pre_order = "";
                //   }
                $pre_order = "";

                if ($cku['nilai']>0){
                    $harga = "<del style='color:#8a8a8a'><b>".rupiah($sub_total)."</b></del> <b>".rupiah($sub_total-$cku['nilai'])."</b>";
                }else{
                    $harga = "<b>".rupiah($sub_total)."</b>";
                }
                
                $catatan = explode('||',$row['keterangan_order']);
                if (count($catatan)>1){
                  $catatan0 = $catatan[0];
                  $catatan1 = $catatan[1];
                }else{
                  $catatan0 = '';
                  $catatan1 = $catatan[0];
                }

                if ($row['keterangan_order']!=''){
                  if (trim($catatan1)!=''){
                    $catatan1x =  "<b>Variasi</b> : $catatan1<br>";
                  }else{
                    $catatan1x =  "";
                  }
                  if (trim($catatan0)!=''){
                    $catatan2x =  "<b>Catatan</b> : ".$catatan0;
                  }else{
                    $catatan2x =  "";
                  }
                }

                if ($row['id_level']!='0'){
                  $level = $this->model_app->view_where('rb_produk_level',array('id_level'=>$row['id_level']))->row_array();
                  $nama_level = "<span style='color:red; background:#ffd9d9; padding: 3px 7px 4px 7px; font-size:11px' class='badge badge-secondary'>$level[nama_level]</span>";
                  $harga_produkx = "= <b style='color:green'>Rp ".rupiah(($row['harga_jual']-$row['diskon'])*$row['jumlah'])."</b>";
                }else{
                  $nama_level = "";
                  $harga_produkx = "x Rp ".rupiah($row['harga_jual']-$row['diskon']);
                }

                echo "<tr>
                        <td width='30px'>$no</td>
                        <td><i style='font-size:11px'>Toko. $row[nama_reseller]</i><br> 
                            <a href='#'>$row[nama_produk]</a> $nama_level <br> 
                            <span>$catatan1x $catatan2x</span></td>
                        <td align=center>$row[jumlah]</td>
                        <td>".rupiah($row['harga_jual']-$row['diskon'])."</td>
                        <td>$harga</td>
                        
                    </tr> ";
                    $no++;
            }
                ?>     
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <!-- /.col -->
        <?php 
        $co = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$judul' AND kode_kurir!=''")->num_rows();
        if ($co>=1){
            $angka_acak = substr($judul,-2);
            $angka_acak_delete = 0;
            $cek_payment = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='$judul' AND pembayaran='1'");
            if ($cek_payment->num_rows()<=0){ ?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <p class="lead">Pilih Metode Pembayaran :</p>
                <table class="table table-condensed table-hover">
                <tbody>
                    <?php 
                    $rekening = $this->model_app->view('rb_rekening');
                    foreach ($rekening->result_array() as $row){
                        echo "<tr><td colspan='3' scope='row' class='rekening'> $row[nama_bank]</td> <td colspan='2' scope='row'> a/n : <b>$row[pemilik_rekening]</b>, Rek : <b>$row[no_rekening]</b></td></tr>";
                    }
                    ?>
                </tbody>
                </table>
    
                <p class="alert-danger text-muted well well-sm no-shadow" style="margin-top: 10px;">
                Silahkan Transfer ke no rekening di atas untuk keamanan pesanan anda, dan jika pesanan sudah diterima dengan baik seperti seharusnya, maka konfirmasi penerimaan pada halaman pembelian.
                </p>
            </div>
            <?php }
        }else{
            $angka_acak = 0;
            $angka_acak_delete = substr($judul,-2);
        }
        ?>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="table-responsive">
            <?php 
                $ong = $this->db->query("SELECT sum(ongkir) as ongkir, sum(fee_admin)/count(*) as fee_admin FROM rb_penjualan where kode_transaksi='$kode'")->row_array();
                
                $cekk = $this->db->query("SELECT jenis_produk FROM rb_penjualan_detail a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='$rows[id_penjualan]' GROUP BY b.jenis_produk")->row_array();
            ?>
          <table border='0' width='100%' style='text-align:right'>
                <tbody>
                <tr>
                    <td style='font-weight:bold'>Berat</td>
                    <td width='160px'><?php echo ($total['total_berat']>1000?number_format($total['total_berat']/1000,1).' Kg':$total['total_berat'].' Gram'); ?></td>
                </tr>
                <tr>
                    <td style='font-weight:bold'>Ongkos Kirim</td>
                    <td><?php echo "Rp ".rupiah($ong['ongkir']); ?></td>
                </tr>
                <tr>
                    <td style='font-weight:bold'>Fee admin</td>
                    <td><?php echo "Rp ".rupiah($ong['fee_admin']); ?></td>
                </tr>
                <tr>
                    <td style='font-weight:bold'>Subtotal</td>
                    <td><?php echo "Rp ".rupiah($total['total']-$total['diskon_total']); ?></i> </td>
                </tr>
                <tr><td colspan='2'><br></td></tr>
                <?php 
                  $cek_withdraw = $this->db->query("SELECT * FROM rb_withdraw where keterangan='$judul'");
                  if ($cek_withdraw->num_rows()>=1){
                      $cw = $cek_withdraw->row_array();
                      echo "<tr><td style='font-weight:bold'>Pakai Saldo</td> <td>- Rp ".rupiah($cw['nominal'])."</td></tr>";
                  }
  
                  $data_kupon = $this->db->query("SELECT c.* FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                  JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                  where b.kode_transaksi='$judul'");
                  foreach ($data_kupon->result_array() as $kup) {
                      echo "<tr><td style='font-weight:bold'>Diskon Produk</td> <td>- Rp ".rupiah($kup['nilai'])."</td></tr>";
                  }

                  $cek_kup = $this->db->query("SELECT * FROM rb_penjualan_kupon where status_kupon='$judul'");
                  foreach ($cek_kup->result_array() as $kup) {
                    echo "<tr><td style='font-weight:bold'>$kup[keterangan_kupon]</td> <td>- Rp ".rupiah($kup['nilai'])."</td></tr>";
                  }      
                ?>
                <tr>
                    <td style='font-weight:bold'>Kode Unik Transfer</td>
                    <td><?php echo "- $angka_acak"; ?></td>
                </tr>
                <tr>
                    <td style='font-weight:bold'>Total Belanja</td>
                    <td style='border: 1px solid #000; font-weight:bold'><?php echo rupiah($unik['nominal']); ?></td>
                </tr>
                </tbody>
          </table>          
          </div>
        </div>
        <!-- /.col -->
      </div>

    </section>
    </body>
</html>