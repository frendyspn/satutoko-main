
<html>
<head>
<title>Invoice <?= $judul; ?></title>
<style>
.invoice{
  border:1px solid #000;
  width:90%;
  margin:0 auto;
  padding:20px
}
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
$peng = $this->db->query("SELECT * FROM rb_reseller where id_konsumen='".$this->session->id_konsumen."'")->row_array();
$ong = $this->db->query("SELECT sum(a.jumlah*b.berat) as berat_total FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='$rows[id_penjualan]'")->row_array();
if ($rows['dropshipper']!=''){
  $drp = explode('||',$rows['dropshipper']);
  $pengirim = $drp[0];
  $no_hp = $drp[1];
}else{
  $pengirim = $peng['nama_reseller'];
  $no_hp = $peng['no_telpon'];
}
$alamat = $peng['alamat_lengkap'];

?>
<div style="border-bottom:1px solid #8a8a8a; padding:5px 10px">
<img alt="Phpmu.com" src="<?php echo base_url()."asset/logo/".$logo['gambar']; ?>" class="img-responsive">
</div>

<?php echo "<p>$rows[kode_transaksi]</p>"; ?>

<?php echo "<p>".kurir($rows['kode_kurir'],$rows['kurir'],$rows['service'])."<br>
            Berat : <b>".($ong['berat_total']/1000)." Kg</b> <br>
            Ongkir : <b>Rp ".rupiah($rows['ongkir'])."</b></p>"; ?>

<div style='clear:both'></div>
      <!-- info row -->
      <div class="row invoice-info">
        <div style='float:right; text-align:left'>
        Dari :<br>
            <b><?php echo $pengirim; ?></b><br>
            <?php echo kecamatanx($peng['kecamatan_id'],$peng['kota_id']); ?><br>
            <?php echo $no_hp; ?><br>
        </div>
        
        <div style='float:left; text-align:left; width: 350px;'>
        Kepada :
          <address>
          <?php 
            $nama_lengkap = $rows['nama_lengkap'];
            $no_telpon = substr($rows['no_hp'], 0, -2);
            if ($rows['keterangan']!=''){
              $exal = explode('|',$rows['keterangan']);
              echo "<b>".$exal[5]."</b> ($exal[6])";
            }else{
              echo "<b>$rows[nama_lengkap]</b> ($rows[no_hp]";
            }
          ?>
            <br><?php echo alamat($rows['kode_transaksi']); ?><br>
          </address>
        </div>      
      </div>

<div style='clear:both'><br><hr style='border:1px dashed #8a8a8a'></div>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <p>Produk</p>
            <?php 
            $no = 1;
            foreach ($record as $row){
                $cku = $this->db->query("SELECT * FROM rb_penjualan_kupon where id_penjualan_detail='$row[id_penjualan_detail]'")->row_array();
                $sub_total = (($row['harga_jual']-$row['diskon'])*$row['jumlah']);
                $ext = explode(';', $row['gambar']);
                $pre_order = "";

                if ($cku['nilai']>0){
                    $harga = "<del style='color:#8a8a8a'><b>".rupiah($sub_total)."</b></del> <b>".rupiah($sub_total-$cku['nilai'])."</b>";
                }else{
                    $harga = "<b>".rupiah($sub_total)."</b>";
                }
                
                $catatan = explode('||',$row['keterangan_order']);
                if (trim($catatan[1])!=''){
                    $catatan1 = "<b>Variasi</b> : ".$catatan[1].'<br>';
                }
                if (trim($catatan[0])!=''){
                    $catatan2 = "<b>Catatan</b> : ".$catatan[0];
                }

                echo "$no. $row[nama_produk]</a> $row[jumlah] - ($row[satuan]) <br>";

                $no++;
            }
                ?>     

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    </body>
</html>