<style>
.menu-message {
    max-height: 650px;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow-x: hidden;
}
.menu-message>li>a {
    margin: 0;
    padding: 10px 10px;
}
.menu-message>li>a {
    color: #444;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding: 10px;
}
.menu-message>li>a {
    display: block;
    white-space: nowrap;
    border-bottom: 1px solid #f4f4f4;
}
.menu-message>li>a>h4 {
    padding: 0;
    margin: 0 0 0 45px;
    color: #444;
    font-size: 15px;
    position: relative;
}
.menu-message>li>a>p {
    margin: 0 0 0 45px;
    font-size: 14px;
    color: #888;
}
.menu-message>li>a>h4>small {
    color: #999;
    font-size: 10px;
    position: absolute;
    top: 0;
    right: 0;
}
.menu-message>li>a:hover{background:#f4f4f4;text-decoration:none !important}
</style>

<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li><?= $title; ?></li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
            <?php include "menu-members.php"; ?>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <?php
                      include "sidebar-members.php";
                      echo "<a href='".base_url()."members/edit_profile' class='ps-btn btn-block'><i class='icon-pen'></i> Edit Biodata Diri</a>";
                    ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                    <?php 
                    if ($this->session->id_konsumen==''){
                        echo "<center style='padding:60px 10px'>Anda Tidak Memiliki Akses, Silahkan Login,...</center>";
                    }else{
                        $jmlpesan_unread = $this->model_reseller->pesanbelumbaca()->num_rows(); 
                        echo "<div class='row'>
                                <div class='col-md-12 col-sm-6 col-xs-12'>
                                    <a class='ps-btn ps-btn--outline btn-block'  href='".base_url()."members/messages?unread'><center>Pesan Belum Dibaca <span class='badge badge-secondary'>$jmlpesan_unread</span></center></a>
                                </div>
                            </div><br>
                        
                        <ul class='menu-message' style='min-height:333px'>";
                        foreach ($record->result() as $r) {
                        $mgs = $this->model_reseller->tampilmessagescontenthome($r->id_konsumen)->row();
                            if ($mgs->stat == '1'){ $bg = '#dcf9e4'; }else{ $bg = ''; }
                        if (trim($mgs->message) == '' AND $mgs->file_upload != ''){ 
                            $message = '<i class="fa fa-link fa-fw"></i> Melampirkan Sebuah File,..'; 
                        }else{ 
                        $message = substr($mgs->message,0,90); 
                        }
                        $tglex = cek_terakhir($mgs->date_time);
                        if ($r->foto==''){
                            $foto_members = 'blank.png';
                        }else{
                            if (file_exists("asset/foto_user/".$r->foto)){ $foto_members = $r->foto; }else{ $foto_members = 'blank.png'; }
                        }
                        $email_gravatar = md5(strtolower(trim($r->email))); 
                        echo "<li style='background:$bg'>
                            <a href='".base_url()."members/read/".$r->id_konsumen."/0'>
                                <div class='float-left'>
                                <img width='40px' height='40px' src='".base_url()."asset/foto_user/$foto_members' class='rounded-circle' alt='User Image'>
                                </div>
                                <h4> ".$r->nama_lengkap." <small><i class='fa fa-clock-o'></i> $tglex</small></h4>
                                <p>";
                                if ($mgs->file_upload != ''){ 
                                    echo "<i style='color:red' class='fa fa-files-o fa-fw'></i> ";
                                }
                                echo strip_tags($message)."...</p>
                            </a>
                            </li>";
                        } 

                        if ($record->num_rows()<=0){
                            echo "<center style='margin:50px 0px'>Maaf, tidak ada Data...</center>";
                        }
                        echo "</ul>";
                    }
                    ?>
                    <div class="ps-pagination">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>