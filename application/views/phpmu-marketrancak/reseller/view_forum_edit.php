<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Forum</a></li>
                <li>Edit Topik Terpilih</li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content"><br>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                  <?php
                    include "sidebar-members.php";
                    echo "<a href='".base_url()."members/edit_profile' class='ps-btn btn-block'><i class='icon-pen'></i> Edit Biodata Diri</a>";
                  ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                <?php 
                    echo $this->session->flashdata('message'); 
                    $this->session->unset_userdata('message');
                    $attributes = array('id' => 'formku');
                    echo form_open_multipart('forum/topic_edit/'.$topic['judul_seo'],$attributes); 
                ?>
                    <div class="ps-block--vendor-status biodata">
                        <?php 
                            echo "<p style='font-size:17px'>Hai <b>$row[nama_lengkap]</b>, Silahkan untuk edit data topic anda! <br>
                                                            Pastikan untuk menulis topic sesuai dengan syarat dan ketentuan forum diskusi.</p><br>

                            <input type='hidden' value='$topic[id_topic]' name='id'>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Jenis</b></label>
                              <div class='col-sm-10'>
                              <select name='id_kategori_topic' class='form-control form-mini' required>";
                              foreach ($kategori->result_array() as $rows) {
                                if ($topic['id_kategori_topic']==$rows['id_kategori_topic']){
                                    echo "<option value='$rows[id_kategori_topic]' selected>$rows[nama_kategori_topic]</option>";
                                }else{
                                    echo "<option value='$rows[id_kategori_topic]'>$rows[nama_kategori_topic]</option>";
                                }
                              }
                              echo "</select>
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Judul</b></label>
                              <div class='col-sm-10'>
                              <input type='text' name='judul' class='form-control form-mini' value='$topic[judul]' placeholder='- - - - - - - - - - - - - - - - - ' autocomplete='off' required>
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Isi Topic</b></label>
                              <div class='col-sm-10'>
                              <textarea name='isi_topic' class='form-control' style='height:220px' autocomplete='off' placeholder='Tulis isi Topic...' required>$topic[isi_topic]</textarea>
                              <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                              <div id='status'></div>";
                              if ($topic['file_topic'] != ''){ 
                                $ex = explode(';',$topic['file_topic']);
                                $hitungex = count($ex);
                                echo "<span style='color:Red'>File saat ini : </span><br>";
                                for($i=0; $i<$hitungex; $i++){
                                    if (file_exists("asset/files/".$ex[$i])) { 
                                        echo "<div class='item' style='border-bottom:1px dotted #cecece; padding-left: 10px;'><a target='_BLANK' href='".base_url()."asset/files/".$ex[$i]."'>".($i+1)."). ".$ex[$i]."</a></div>";
                                    }
                                }
                              }
                            echo "</div>
                            </div>

                            <input type='submit' name='submit' class='btn btn-primary spinnerButton btn-sm pull-right' value='Update'>
                          </div>";
                          echo form_close();
                        ?>
                    </figure>
                </div>
              
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
