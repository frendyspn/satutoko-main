<div class="ps-breadcrumb">
        <div class="ps-container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <?php 
                    echo "<li><a href='".base_url()."/forum'>Forum</a></li>
                    <li>$title</li>";
                ?>
            </ul>
        </div>
    </div>
    <div class="ps-page" id="shop-sidebar">
        <div class="container">
            <div class="ps-layout">
                <div class="ps-layout">
                    <div class="ps-block">
                    <?php echo $this->session->flashdata('message'); 
                        $this->session->unset_userdata('message'); 
                        $isi_topic = nl2br(kosong($record['isi_topic']));
                        $id = $record['id_topic'];
                        if ($record['akun']=='konsumen'){
                            $kons = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='$record[id_konsumen]'")->row_array();
                            $nama = $kons['nama_lengkap'];
                            $foto = "foto_user/".($record['foto']=='' ? 'blank.png':$kons['foto']);
                        }else{
                            $kons = $this->db->query("SELECT * FROM users where username='$record[id_konsumen]'")->row_array();
                            $nama = $kons['nama_lengkap'].' (Admin)';
                            $foto = "foto_user/".($kons['foto']=='' ? 'blank.png':$kons['foto']);
                        }
                    ?>
                        <table style='border-radius:6px; margin-top:30px' class='table table-hover'>
                            <thead>
                                <tr><td colspan='2'>
                                <?php if ($rowx['id_members_download']!=''){ echo "<small style='display:block; color:green'><b>PRODUK</b> - <a href='".base_url()."forum/produk/$rowx[judul_file_seo]'>$rowx[judul_file]</a></small>"; } ?>
                                    <h1 style="font-size:2.07692308rem !important; margin-top:0px"><b><?php echo stripslashes($record['judul']); ?></b></h1> 
                                    <div style="display:inline; margin-right:12px"><span>Dibuat</span>
                                        <time itemprop="dateCreated" datetime="<?php echo $record['tanggal']; ?>"><?php echo cek_terakhir($record['tanggal']); ?> lalu | <?php echo jam_tgl_indo($record['tanggal']); ?> WIB,</time>
                                    </div>
                                    <span class="fc-light mr2">Telah Dilihat</span> <?php echo $record['view']; ?> Kali
                                </td></tr>
                                <tr bgcolor="#f4f4f4">
                                    <td valign='top' rowspan="2" width='100px'>
                                        <a href="#">
                                            <img width='80px' src='<?php echo base_url()."asset/$foto"; ?>' class='img-thumbnail' alt='User Image'>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top'> 
                                        <div style='margin-bottom:8px'>
                                            <i class='fa fa-user'></i> 
                                            <a href="#"><b><?= $nama; ?></b></a> 
                                            <i><small>Mengatakan :</small></i>
                                        </div>
                                        <?php 
                                        echo "$isi_topic";
                                        if ($record['file_topic'] != ''){ 
                                            $ex = explode(';',$record['file_topic']);
                                            $hitungex = count($ex);
                                            echo "<div style='padding:5px; margin:0px' class='alert alert-info'><i class='fa fa-link fa-fw'></i> Ada $hitungex File Kiriman : </div>";
                                            $no = 1;
                                            for($i=0; $i<$hitungex; $i++){
                                                if (file_exists("asset/files/".$ex[$i])) { 
                                                    $files_topic = $this->mylibrary->Size("asset/files/".$ex[$i]);
                                                    echo "<small style='color:#000; font-weight:bold'><span style='margin-left:20px'>$no. <a href='".site_url('members/download/'.$ex[$i])."'>$ex[$i]</a> ($files_topic)</span></small><br>";
                                                }else{
                                                    echo "<small style='color:#000; font-weight:bold'><span style='margin-left:20px'>$no. <a href='#'><i>Maaf File '$ex[$i] (0)' Gagal Terkirim!</i></a></span></small><br>";
                                                }
                                                $no++;
                                            }
                                        } 
                                        ?>
                                    </td>
                                </tr>
                            </thead>
                        </table>

                        <?php 
                            foreach ($komentar->result() as $k) {
                                if ($k->akun=='konsumen'){
                                    $kons = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$k->id_konsumen."'")->row_array();
                                    $nama = $kons['nama_lengkap'];
                                    $foto = "foto_user/".($kons['foto']=='' ? 'blank.png':$kons['foto']);
                                }else{
                                    $kons = $this->db->query("SELECT * FROM users where username='".$k->id_konsumen."'")->row_array();
                                    $nama = $kons['nama_lengkap'].' (Admin)';
                                    $foto = "foto_user/".($kons['foto']=='' ? 'blank.png':$kons['foto']);
                                }

                                $tglk = explode(' ',$k->tanggal_komentar);
                                $tanggalkomentar = $this->mylibrary->tgl_indo($tglk[0]);
                                $isi_komentar = nl2br(kosong($k->isi_pesan));
                                echo "<table id='komentar-".$k->id_komentar."' style='background:#fff; border-radius:6px; margin-bottom:10px;' class='table table-hover table-condensed'>
                                        <thead>
                                        <tr bgcolor='#f6f8fa'>
                                            <td style='padding:10px; text-transform:capitalize' colspan='2'><a style='color:#000' href='#'> <span class='fa fa-user'></span> $nama</a> <small style='color:#b7b7b7'>commented on ".$this->mylibrary->tgl_indo($tglk[0])." ".$tglk[1]."</small>
                                                
                                            <div class='btn-group pull-right' role='group'>
                                                    <button id='btnGroupDrop".$k->id_komentar."' type='button' style='color:#000; background:transparent' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                        <span class='fa fa-ellipsis-h'></span>
                                                    </button>
                                                    <div class='dropdown-menu' aria-labelledby='btnGroupDrop".$k->id_komentar."'>
                                                        <a class='dropdown-item' href='".base_url()."forum/read/".$this->uri->segment('3')."?hide=".$k->id_komentar."'>Hidden</a>
                                                        <a class='dropdown-item' href='".base_url()."forum/read/".$this->uri->segment('3')."?delete=".$k->id_komentar."' onclick=\"return confirm('Apa anda yakin untuk hapus Permanent Data ini?')\">Delete</a>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign='top' rowspan='2' width=60px><a href='#'><img width='50px' src='".base_url()."asset/$foto' class='img-thumbnail' alt='User Image'></a></td>
                                        </tr>
                                        <tr>
                                            <td valign='top'>$isi_komentar";
                                                if ($k->file_comment != ''){ 
                                                $exx = explode(';',$k->file_comment);
                                                $hitungex1 = count($exx);
                                                echo "<div style='padding:5px; margin:0px'  style='padding:5px; margin:0px' class='alert alert-info'><i class='fa fa-link fa-fw'></i> Terdapat $hitungex1 File dilampirkan : </div>";
                                                $noi = 1;
                                                    for($i=0; $i<$hitungex1; $i++){
                                                    if (file_exists("asset/files/".$exx[$i])) { 
                                                        $files_forum = $this->mylibrary->Size("asset/files/".$exx[$i]);
                                                        echo "<small style='color:#000; font-weight:bold'><span style='margin-left:25px'>$noi. <a href='".site_url('members/download/'.$exx[$i])."'>$exx[$i]</a> ($files_forum)</span></small><br>";
                                                    }else{
                                                        echo "<small style='color:#000; font-weight:bold'><span style='margin-left:25px'>$noi. <a href='#'><i>Maaf File '$exx[$i] (0)' Gagal Terkirim!</i></a></span></small><br>";
                                                    }
                                                    $noi++;
                                                }
                                            }
                                            echo "</td>
                                          </tr>
                                          </thead>
                                </table>";
                                $no++;
                            }

                            if ($this->session->id_konsumen!=''){
                                echo "<tr>
                                    <td valign='top'>
                                        <div id='form-comment'>
                                            <textarea class='required textarea komentarx' id='comment' placeholder='Tuliskan Komentar...' onkeyup=\"auto_grow(this)\" required></textarea>
                                            <button id='sendButton' class='btn btn-primary btn-sm submitx spinnerButton' style='float:right'>Kirimkan <span class='hidden-xs'>Komentar</span></button>
                                            <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                                            <div id='status'></div>
                                        </div>
                                    </td>
                                </tr>";
                            }else{
                                echo "<div class='alert alert-info' style='text-align:center'>Silahkan <a href='#' data-toggle='modal' data-target='.bd-example-modal-lg'><u>login</u></a> untuk memberikan komentar.</div>";
                            }

                        ?>

                        <div class="ps-pagination">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                        <div style='clear:both'><br></div>

                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function(){
var settings = {
    url: "<?php echo base_url(); ?>forum/upload_forum",
    formData: {id: "<?php echo $this->session->id_konsumen; ?>"},
    dragDrop: true,
    
	  maxFileCount:10,
    fileName: "uploadFile",
	  maxFileSize:5000*1024,
    allowedTypes:"jpg,png,jpeg,gif",		
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDone:false,
    showDelete:true,
    deleteCallback: function(data,pd) {
        $.post("<?php echo base_url(); ?>forum/deleteFile_forum",{op: "delete", name:data},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");   
            });
        for(var i=0;i<data.length;i++) {
            $.post("<?php echo base_url(); ?>forum/deleteFile_forum",{op:"delete",name:data[i]},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");  
            });
        }   
        pd.statusbar.hide();
    }   
}
$("#mulitplefileuploader").uploadFile(settings);
});
</script>
<script type="text/javascript">
$("#sendButton").click(function(){
  var comment = $('#comment').val();
  $.post('<?php echo base_url(); ?>forum/sendComment', {id: "<?php echo $id ?>", comment: comment})
  .done(function(){
    location.reload();
  });
}); 
</script>
