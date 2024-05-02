<style>
.card-widget {
    border: 0;
    position: relative;
}
.card {
    box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
    margin-bottom: 1rem;
}
.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
}
.widget-user .widget-user-header {
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    height: 135px;
    padding: 1rem;
    text-align: center;
}

.widget-user .widget-user-image {
    left: 50%;
    margin-left: -45px;
    position: absolute;
    top: 80px;
}
.widget-user .card-footer {
    padding-top: 50px;
}
.card-footer {
    padding: 0.75rem 1.25rem;
    background-color: rgba(0,0,0,.03);
    border-top: 0 solid rgba(0,0,0,.125);
}
.widget-user .widget-user-username {
    font-size: 25px;
    font-weight: 300;
    margin-bottom: 0;
    margin-top: 0;
    text-shadow: 0 1px 1px rgb(0 0 0 / 20%);
}
.text-right {
    text-align: right!important;
    color:#000
}
.widget-user .widget-user-desc {
    margin-top: 0;
}
.text-right {
    text-align: right!important;
}
</style>

<?php 
$row = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
if (trim($rows['foto'])==''){ $foto_user = base_url()."asset/foto_user/toko.jpg"; }else{ $foto_user = base_url()."asset/foto_user/$rows[foto]"; } 
?>

<div class="card card-widget widget-user">
    <div class="widget-user-header text-white" style="background: #cecece url('https://members.phpmu.com/asset/css/img/flower-swirl10.png') center center;">
        <h3 class="widget-user-username text-right"><?= $rows['nama_reseller']; ?></h3>
        <h5 class="widget-user-desc text-right"><?= $rows['user_reseller']; ?></h5>
    </div>

    <div class="widget-user-image">
        <img class="rounded-circle" style='width:90px' src="<?php echo $foto_user; ?>" alt="User Avatar">
    </div>

    <div class="card-footer">
    <div class="row">
        <div class="col-sm-12">
            <div class="description-block">
                <h5 style='margin-bottom:0px' class="description-header">ID Toko</h5>
                <span style='font-size:16px;' class="description-text"><?php echo str_replace(':','',str_replace('-','',$rows['tanggal_daftar'])).'-'.sprintf("%04d", $rows['id_reseller']); ?></span>
            </div>
        </div>
    </div>
    </div>
</div>

<?php 
$komplain_toko = $this->db->query("SELECT * FROM rb_pusat_bantuan where id_terlapor='".$this->session->id_konsumen."' AND putusan='proses'"); 

echo "<div style='font-size:16px; padding:10px 0px 5px 0px'>Sisa Saldo <b class='pull-right'>Rp ".rupiah(saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen))."</b> </div>
      <a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href='".base_url()."members/edit_profil_toko/".reseller($this->session->id_konsumen)."'><i class='fa fa-plus'></i> Edit Toko</a>";
?>
<a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href="<?php echo base_url(); ?>members/operasional"><i class='fa fa-calendar'></i> Jadwal Operasional</a>
<a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href="<?php echo base_url(); ?>members/produk"><i class='fa fa-th'></i> Daftar Produk</a>
<a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href="<?php echo base_url(); ?>members/alamat_cod"><i class='fa fa-map-marker'></i>&nbsp; Alamat Transaksi COD</a>
<?php if (config('reseller')=='Y'){ ?><a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href="<?php echo base_url(); ?>members/pembelian"><i class='fa fa-reorder'></i> Orders Pusat (Reseller)</a> <?php } ?>
<a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href="<?php echo base_url(); ?>komplain?s=terlapor"><i class='fa fa-warning'></i> Komplain (Terlapor) <span class="badge badge-secondary" style='font-size:85%; background-color: #cecece; color:#000'><?php echo $komplain_toko->num_rows(); ?></span></a>
<a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href="<?php echo base_url(); ?>members/penjualan"><i class='fa fa-list-alt'></i> Orders Masuk <span class="badge badge-secondary" style='font-size:85%; background-color: #cecece; color:#000'><?php echo order_masuk(reseller($this->session->id_konsumen)); ?></span></a>
<a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href="<?php echo base_url(); ?>members/upgrade"><i class="fa fa-star text-yellow"></i> <span class="blink_me">Upgrade Toko</span></a><br><br>