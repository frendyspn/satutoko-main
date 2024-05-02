<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Beranda</a></li>
                <li><a href="#">Produk</a></li>
                <li>Tambah Video</li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
            <?php 
                $attributes = array('class'=>'biodata','role'=>'form','name'=>'demo');
                echo form_open_multipart('members/video?t='.$_GET['t'],$attributes);
                echo $this->session->flashdata('message'); 
                $this->session->unset_userdata('message');
            
                include "menu-members.php"; ?>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <?php
                      require_once(APPPATH.'views/'.template().'/reseller/sidebar-members.php');
                    ?>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                    <figure class="ps-block--vendor-status biodata">
                        <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Produk</b></label>
                            <div class='col-sm-9'>
                            <?php 
                                echo "<select class='form-control' name='id_produk'>
                                <option required value=''></option>";
                                $produk = $this->db->query("SELECT * FROM rb_produk where id_reseller='".reseller($this->session->id_konsumen)."' AND aktif='Y'");
                                foreach($produk->result_array() as $rowx){
                                    echo "<option value='$rowx[id_produk]'>$rowx[nama_produk]</option>";
                                }
                                echo "</select>";
                            ?>
                            </div>
                        </div>
                        
                        <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Judul</b></label>
                            <div class='col-sm-9'>
                            <textarea class='form-control' name='judul_video' style='height:90px'></textarea>
                            </div>
                        </div>

                        <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Keterangan</b></label>
                                <div class='col-sm-9'>
                                <textarea class='form-control' name='keterangan_video' style='height:180px'></textarea>
                                </div>
                        </div>

                        <div class='form-group row' style='margin-bottom:5px'>
                        <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Pilih Video</b></label>
                        <div class='col-sm-9'>
                            <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                            <div id='status'></div>
                        </div>
                    </div>
                    <button type='submit' name='submit' class='mt-3 ps-btn spinnerButton'><center><i class='icon-pen'></i> Tambahkan</center></button>
                    </figure>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
var settings = {
    url: "<?php echo base_url().$this->uri->segment(1); ?>/upload_video",
    formData: {id: "<?php echo $this->session->id_konsumen; ?>"},
    dragDrop: true,
	  //maxFileCount:10,
    fileName: "uploadFile",
	  maxFileSize:20000*1024,
    allowedTypes:"mp4,MP4",		
    returnType:"json",
    onSelect:function(files) {  
        var cnt = files.length;
        if(cnt <= 1){
          return true; //to allow file submission.
        }else{
        alert("Maksimal hanya 1 video saja.");
        return false;
        }
    },
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDone:false,
    showDelete:true,
    deleteCallback: function(data,pd) {
        $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile_video",{op: "delete", name:data},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");   
            });
        for(var i=0;i<data.length;i++) {
            $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile_video",{op:"delete",name:data[i]},
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