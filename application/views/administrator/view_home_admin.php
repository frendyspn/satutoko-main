<?php if (config('market_sample')==1){ ?>
<div class="alert alert-warning alert-dismissible" style='background-color:#d0d0d0 !important; border-color:#a2a2a2;'>
  <i class="icon fa fa-warning text-danger"></i> <span style='color:#000'>Klik tombol Berikut untuk menghapus Sample/Dummy Data.</span> 
  <a style='text-decoration:none; margin-left:3px; color:#000' class='pull-right btn btn-default btn-xs' href='<?= base_url().$this->uri->segment(1); ?>/format_data?hide=0'>Hide</a>
  <a style='text-decoration:none' class='pull-right btn btn-danger btn-xs' href='<?= base_url().$this->uri->segment(1); ?>/format_data' onclick="return confirm('Apa anda yakin untuk Format Sample/Dummy Data?')">Format Data</a>
</div>
<?php } ?>

<?php 
echo $this->session->flashdata('message');
$this->session->unset_userdata('message');
?>

<div class="col-md-5 col-sm-5 col-xs-12">
<?php 
    $jmlpesan = $this->model_app->view_where('hubungi', array('dibaca'=>'N'))->num_rows(); 
    $jmlberita = $this->model_app->view_where('komentar', array('aktif'=>'N'))->num_rows(); 
    $jmlvideo = $this->model_app->view_where('komentarvid', array('aktif'=>'N'))->num_rows(); 
  ?>
  <div class='box'>
    <div class='box-header'>
      <h3 class='box-title'>Akses Cepat</h3>
    </div>
    <div class='box-body'>
      <p>Silahkan klik menu pilihan yang berada di sebelah kiri untuk mengelola konten website anda 
          atau pilih ikon-ikon pada Control Panel di bawah ini : </p>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/identitaswebsite" class='btn btn-app'><i class='fa fa-th'></i> Identitas</a>
      
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/konsumen" class='btn btn-app'><i class='fa fa-user'></i> Konsumen</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/reseller" class='btn btn-app'><i class='fa fa-users'></i> Toko</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/supplier" class='btn btn-app'><i class='fa fa-truck'></i> Supplier</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/paket" class='btn btn-app'><i class='fa fa-th-large'></i> Paket</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/produk" class='btn btn-app'><i class='fa fa-folder'></i> Produk</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/penjualan_konsumen" class='btn btn-app'><i class='fa fa-shopping-cart'></i> Transaksi</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/mutasi" class='btn btn-app'><i class='fa fa-money'></i> Mutasi</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/banner" class='btn btn-app'><i class='fa fa-info'></i> Komplain</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/reseller_transaksi" class='btn btn-app'><i class='fa fa-book'></i> Laporan</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/listberita" class='btn btn-app'><i class='fa fa-television'></i> Berita</a>
      <a href="<?php echo base_url().$this->uri->segment(1); ?>/manajemenuser" class='btn btn-app'><i class='fa fa-user-secret'></i> Users</a>
    </div>
  </div>

  <?php include "grafik.php"; ?>
</div>


<div class="col-md-7 col-sm-7 col-xs-12">

<a class='hidden-xs' style='color:#000' href='<?php echo base_url().$this->uri->segment(1); ?>/produk'>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Produk</span>
        <?php $jmla = $this->model_app->view('rb_produk')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmla; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a class='hidden-xs' style='color:#000' href='<?php echo base_url().$this->uri->segment(1); ?>/konsumen'>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Konsumen</span>
        <?php $jmlb = $this->model_app->view('rb_konsumen')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmlb; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a class='hidden-xs' style='color:#000' href='<?php echo base_url().$this->uri->segment(1); ?>/reseller'>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Reseller</span>
        <?php $jmlc = $this->model_app->view('rb_reseller')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmlc; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a class='hidden-xs' style='color:#000' href='<?php echo base_url().$this->uri->segment(1); ?>/komplain'>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Komplain</span>
        <?php $jmld = $this->model_app->view_where('rb_pusat_bantuan',array('putusan'=>'proses'))->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmld; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <div style='clear:both'></div>
  <div class="col-md-12 col-sm-12 col-xs-12">

  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Transaksi Terbaru</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      
  <table class="table table-bordered table-striped table-condensed table-condensed">
    <thead>
      <tr>
        <th>Kode Transaksi</th>
        <th>Kurir</th>
        <th>Status</th>
        <th>Total Belanja</th>
        <th>Waktu Transaksi</th>
      </tr>
    </thead>
    <tbody>
  <?php 
    $no = 1;
    $record = $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.status_penjual='reseller' ORDER BY a.id_penjualan DESC LIMIT 10");
    foreach ($record->result_array() as $row){
      $total = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total, sum(a.fee_produk_end*a.jumlah) as fee_produk FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
      $produk = $this->db->query("SELECT * FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->num_rows();
      $kupon = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                    where b.id_penjualan='$row[id_penjualan]'")->row_array();
      if ($total['fee_produk']>0){ $fee_produk = $total['fee_produk']; }else{ $fee_produk = 0; }
      if ($row['group_order']!=''){
        $ex = explode('.',$row['group_order']);
        $total_group = $this->db->query("SELECT * FROM rb_penjualan where group_order='$row[group_order]' AND proses!='0'");
        if ($total_group->num_rows()>=$ex[1]){
          $total_menunggu = "<i style='color:green'>(Kuota Group Order telah Terpenuhi!)</i>";
        }else{
          $total_menunggu = "<i style='color:red'>(Menunggu ".($ex[1]-$total_group->num_rows())." Orderan Lagi)</i>";
        }

        $kode_transaksi = "<a style='color:red' href='#' class='grouporder' data-id='$row[id_penjual]:$row[group_order]'>GROUP-<b>$row[group_order]</b></a>";
      }else{
        $kode_transaksi = $row['kode_transaksi'];
      }
      echo "<tr><td><a href='".base_url().$this->uri->segment(1)."/detail_penjualan_konsumen/$row[id_penjualan]'>$kode_transaksi</a></td>";
              if ($row['kode_kurir']=='1'){
                $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                echo "<td>$row[service] - $ceks[merek]</td>";
              }elseif ($row['kode_kurir']=='0'){
                $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                echo "<td>COD - $row[service]</td>";
              }else{
                echo "<td><span style='text-transform:uppercase'>$row[kode_kurir]</span> - $row[service]</td>";
              }
              echo "<td>".status($row['proses'])."</td>
              <td>
                Rp ".rupiah($total['total']+$row['ongkir']-$kupon['diskon']-$fee_produk)."</span></td>
              <td>".jam_tgl_indo($row['waktu_transaksi'])."</td>
              
          </tr>";
      $no++;
    }
  ?>
  </tbody>
</table>

<div class="box-footer text-center">
  <a href="<?= base_url(); ?>administrator/penjualan_konsumen" class="uppercase">Lihat Semua</a>
</div>
</div>
</div>

</div>

</div>

