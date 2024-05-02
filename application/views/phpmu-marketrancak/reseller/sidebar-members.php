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
if (trim($row['foto'])=='' OR !file_exists("asset/foto_user/".$row['foto'])){
    $foto = base_url()."asset/foto_user/blank.png";
}else{
    $foto = base_url()."asset/foto_user/$row[foto]";
}
?>
<div class='d-md-block d-sm-none'>
<div class="card card-widget widget-user d-none d-sm-block">
    <div class="widget-user-header text-white" style="background: #cecece url('https://members.phpmu.com/asset/css/img/flower-swirl10.png') center center;">
        <h3 class="widget-user-username text-right"><?= $row['nama_lengkap']; ?></h3>
        <h5 class="widget-user-desc text-right"><?= $row['username']; ?></h5>
    </div>

    <div class="widget-user-image">
        <img class="rounded-circle" style='width:90px' src="<?= $foto; ?>" alt="User Avatar">
    </div>

    <div class="card-footer">
    <div class="row">
        <div class="col-sm-12">
            <div class="description-block">
                <h5 style='margin-bottom:0px' class="description-header">ID Konsumen</h5>
                <span style='font-size:16px;' class="description-text"><?php echo str_replace('-','',$row['tanggal_daftar']).'-'.sprintf("%04d", $row['id_konsumen']); ?></span>
            </div>
        </div>
    </div>
    </div>
</div>

<?php 
echo "<div class='d-none d-sm-block' style='font-size:16px; padding:10px 0px 5px 0px'>Sisa Saldo <b class='pull-right'>Rp ".rupiah(saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen))."</b> 
      <a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href='".base_url()."members/tambah_withdraw'><i class='fa fa-plus'></i> Deposit / Withdraw</a>
      <a style='padding: 2px 15px;' class='ps-btn ps-btn--outline btn-block' href='".base_url()."members/upgrade?p'><i class='fa fa-star text-yellow'></i> <span class='blink_me'>Upgrade Akun Premium</span></a><br>
      </div>";

?>
</div>