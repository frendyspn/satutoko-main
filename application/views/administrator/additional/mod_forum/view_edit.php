<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Topic</h3>
                </div>
              <div class='box-body'>
              <div class='col-md-12'>";
                    echo $this->session->flashdata('message'); 
                    $this->session->unset_userdata('message');
                    $attributes = array('id' => 'formku');
                    echo form_open_multipart('administrator/topic_edit',$attributes); 
                ?>
                    <div class="ps-block--vendor-status biodata">
                        <?php 
                            echo "<input type='hidden' value='$topic[id_topic]' name='id'><div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-1 col-form-label' style='margin-bottom:1px'>Jenis</b></label>
                              <div class='col-sm-11'>
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
                            <label class='col-sm-1 col-form-label' style='margin-bottom:1px'>Judul</b></label>
                              <div class='col-sm-11'>
                              <input type='text' name='judul' class='form-control form-mini'  value='$topic[judul]' placeholder='- - - - - - - - - - - - - - - - - ' autocomplete='off' required>
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-1 col-form-label' style='margin-bottom:1px'>Isi Topic</b></label>
                              <div class='col-sm-11'>
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
                            echo "
                              </div>
                            </div>

                            <input type='submit' name='submit' class='btn btn-primary spinnerButton btn-sm pull-right' value='Update'>
                          </div>";
                          echo form_close();
                        ?>
                </div>
                </div>
            </div>
            </div>
          </div>";

          
<script>
$(document).ready(function(){
var settings = {
    url: "<?php echo base_url(); ?>members/upload_forum",
    formData: {id: "<?php echo $this->session->id_session; ?>"},
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
        $.post("<?php echo base_url(); ?>administrator/deleteFile_forum",{op: "delete", name:data},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");   
            });
        for(var i=0;i<data.length;i++) {
            $.post("<?php echo base_url(); ?>administrator/deleteFile_forum",{op:"delete",name:data[i]},
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