<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <?php 
                  if (isset($_GET['daterange'])){
                    $tgl = explode(' - ',$_GET['daterange']);
                    echo "<h3 class='box-title'>Data Rekap Transaksi Penjualan <br><small style='color:red'><b>Periode :</b> ".tgl_indo(tgl_simpan($tgl[0]))." s/d ".tgl_indo(tgl_simpan($tgl[1]))."</small></h3>";
                  }else{
                    echo "<h3 class='box-title'>Data Rekap Transaksi Penjualan</h3>";
                  }
                  ?>
                  
                  <form class='pull-right' action="" method='GET'>
                    Periode : <input style='padding:4px 15px; border:1px solid #cecece' type="text" name="daterange" required/>
                    <input class='btn btn-primary btn-sm' style='margin-top:-3px; width:60px' type='submit' value='Lihat'>
                    <a target='_BLANK' class='btn btn-default btn-sm' style='margin-top:-3px' href='<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; echo ($_GET['daterange']!=''?'&print':'?print'); ?>'><span class='fa fa-print'></span> Print</a>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body">
                <?php 
                if (isset($_GET['daterange'])){
                  $tgl = explode(' - ',$_GET['daterange']);
                  $tgl1 = tgl_simpan($tgl[0]);
                  $tgl2 = tgl_simpan($tgl[1]);
                  $rows = $this->db->query("SELECT sum(x.belanja+x.ongkir) as total_belanja, sum(x.fee) as fee, sum(x.fee_admin) as fee_admin, sum(y.nominal) as total_bayar, sum((y.nominal-(x.belanja+x.ongkir))-x.fee_admin) as kodeunik, x.waktu_transaksi FROM 
                                              (SELECT SUM(((a.jumlah*a.harga_jual)-(a.diskon*a.jumlah))) as belanja, ongkir, b.kode_transaksi, b.fee, b.fee_admin, b.waktu_transaksi FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where b.status_penjual='reseller' AND b.proses='4' GROUP BY b.kode_transaksi) as x
                                              LEFT JOIN rb_penjualan_otomatis y ON x.kode_transaksi=y.kode_transaksi where date(x.waktu_transaksi) BETWEEN '$tgl1' AND '$tgl2'")->row_array();
                  $paket = $this->db->query("SELECT sum(tagihan) as tagihan FROM rb_reseller_paket where paket='on' AND date(waktu_paket) BETWEEN '$tgl1' AND '$tgl2'")->row_array();
                }else{
                  $rows = $this->db->query("SELECT sum(x.belanja+x.ongkir) as total_belanja, sum(x.fee) as fee, sum(x.fee_admin) as fee_admin, sum(y.nominal) as total_bayar, sum((y.nominal-(x.belanja+x.ongkir))-x.fee_admin) as kodeunik FROM 
                                              (SELECT SUM(((a.jumlah*a.harga_jual)-(a.diskon*a.jumlah))) as belanja, ongkir, b.kode_transaksi, b.fee, b.fee_admin FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where b.status_penjual='reseller' AND b.proses='4' GROUP BY b.kode_transaksi) as x
                                              LEFT JOIN rb_penjualan_otomatis y ON x.kode_transaksi=y.kode_transaksi")->row_array();
                  $paket = $this->db->query("SELECT sum(tagihan) as tagihan FROM rb_reseller_paket where paket='on'")->row_array();
                }
                ?>
                <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='160px' scope='row'>Kode Unik</th>  <td><?php echo rupiah($rows['kodeunik']); ?></td></tr>
                    <tr><th scope='row'>Fee Admin</th>  <td><?php echo rupiah($rows['fee_admin']); ?></td></tr>
                    <tr><th scope='row'>Total Transaksi</th>  <td><?php echo rupiah($rows['total_belanja']); ?></td></tr>
                    <tr><th scope='row'>Upgrade Paket</th>            <td><?php echo rupiah($paket['tagihan']); ?></td></tr>
                    <tr><th class='alert-success' scope='row'>Pendapatan bersih</th>            <td class='success' style='color:green; font-weight:700'><?php echo rupiah($paket['tagihan']+$rows['fee_admin']+$rows['kodeunik']); ?></td></tr>
                    </td></tr>
                  </tbody>
                  </table><br>

                  <table id="example1" class="table table-bordered table-condensed">
                    <thead>
                      <tr bgcolor='#e3e3e3'>
                        <th style='width:20px'>No</th>
                        <th>Nama Reseller</th>
                        <th>Modal</th>
                        <th>Trx. Sukses</th>
                        <th>+ Ongkir</th>
                        <th>Jasa Kurir</th>
                        <th>Saldo</th>
                        <th>Withdraw</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){
                      if (verifikasi_cek($row['id_reseller'])=='1'){ 
                        $bintang = "<i title='Unverified' style='color:red' class='fa fa-remove'></i>"; 
                      }else{ 
                        $bintang = "<i title='Verified' style='color:green' class='fa fa-check-square'></i>"; 
                      }
                      
                      

                      echo "<tr><td>$no</td>";
                            if ($row['kordinat']=='x'){
                                echo "<td>$bintang <a href='".base_url()."administrator/detail_konsumen/$row[id_konsumen]'>$row[nama_reseller]</a> <small><i>(Sopir)</i></small></td>";
                            }else{
                                echo "<td>$bintang <a href='".base_url()."administrator/detail_reseller/$row[id_reseller]'>$row[nama_reseller]</a></td>";
                            }
                                
                                if (isset($_GET['daterange'])){
                                  $ongkir = $this->db->query("SELECT sum(z.ongkir) as ongkir FROM (SELECT sum(c.ongkir) as ongkir FROM rb_penjualan c where c.status_penjual='reseller' AND c.id_penjual='$row[id_reseller]' AND c.kode_kurir!='0' AND c.kode_kurir!='1' AND c.proses>'3' AND date(c.waktu_transaksi) BETWEEN '$tgl1' AND '$tgl2' GROUP BY c.kode_transaksi) as z")->row_array();
                                  $sop = $this->db->query("SELECT id_sopir FROM rb_sopir where id_konsumen='$row[id_konsumen]'")->row_array();
                                  $sopir = $this->db->query("SELECT sum(ongkir) as total FROM rb_penjualan a WHERE a.kurir='$sop[id_sopir]' AND a.proses='4' AND a.kode_kurir='1' AND date(a.waktu_transaksi) BETWEEN '$tgl1' AND '$tgl2'")->row_array();
                                  $tarik = $this->db->query("SELECT sum(nominal) as total FROM rb_withdraw WHERE id_reseller='$row[id_reseller]' AND status='Sukses' AND transaksi='debit' AND date(waktu_withdraw) BETWEEN '$tgl1' AND '$tgl2'")->row_array();
                                  echo "<td>".rupiah(modalx($row['id_reseller'],'4',$tgl1,$tgl2))."</td>
                                        <td>".rupiah(penjualanx($row['id_reseller'],'4',$tgl1,$tgl2))."</td>
                                        <td>".rupiah($ongkir['ongkir'])."</td>
                                        <td>".rupiah($sopir['total'])."</td>
                                        <td class='success'><b>-</b></td>
                                        <td class='danger'><b>".rupiah($tarik['total'])."</b></td>";

                                }else{
                                    if ($row['kordinat']=='x'){
                                      $sopir = $this->db->query("SELECT sum(ongkir) as total FROM rb_penjualan a WHERE a.kurir='$row[id_reseller]' AND a.proses='4' AND a.kode_kurir='1'")->row_array();
                                      echo "<td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>".rupiah($sopir['total'])."</td>
                                        <td class='success'><b>".rupiah(saldo($row['id_reseller'],$row['id_konsumen']))."</b></td>
                                        <td class='danger'><b>".rupiah($tarik['total'])."</b></td>";   
                                    }else{
                                      $ongkir = $this->db->query("SELECT sum(z.ongkir) as ongkir FROM (SELECT sum(c.ongkir) as ongkir FROM rb_penjualan c where c.status_penjual='reseller' AND c.id_penjual='$row[id_reseller]' AND c.kode_kurir!='0' AND c.kode_kurir!='1' AND c.proses>'3' GROUP BY c.kode_transaksi) as z")->row_array();
                                      $sop = $this->db->query("SELECT id_sopir FROM rb_sopir where id_konsumen='$row[id_konsumen]'")->row_array();
                                      $sopir = $this->db->query("SELECT sum(ongkir) as total FROM rb_penjualan a WHERE a.kurir='$sop[id_sopir]' AND a.proses='4' AND a.kode_kurir='1'")->row_array();
                                      $tarik = $this->db->query("SELECT sum(nominal) as total FROM rb_withdraw WHERE id_reseller='$row[id_reseller]' AND status='Sukses' AND transaksi='debit'")->row_array();
                                      
                                      echo "<td>".rupiah(modal($row['id_reseller'],'4'))."</td>
                                            <td>".rupiah(penjualan($row['id_reseller'],'4'))."</td>
                                            <td>".rupiah($ongkir['ongkir'])."</td>
                                            <td>".rupiah($sopir['total'])."</td>
                                            <td class='success'><b>".rupiah(saldo($row['id_reseller'],$row['id_konsumen']))."</b></td>
                                            <td class='danger'><b>".rupiah($tarik['total'])."</b></td>";
                                    }
                                }
                      echo "</tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>

<script>
$('input[name="daterange"]').daterangepicker({
  opens: 'left',
  startDate: moment(), 
  endDate: moment().add(7, 'day'),
  locale: {
    format: 'DD-MM-YYYY'
  }
});

</script>