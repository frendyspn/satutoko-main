<script language="JavaScript" type="text/JavaScript">
  function showSub(){
    <?php
    $query = $this->db->query("SELECT * FROM rb_kategori_produk");
    foreach ($query->result_array() as $data) {
       $id_kategori_produk = $data['id_kategori_produk'];
       echo "if (document.demo.a.value == \"".$id_kategori_produk."\")";
       echo "{";
       $query_sub_kategori = $this->db->query("SELECT * FROM rb_kategori_produk_sub where id_kategori_produk='$id_kategori_produk'");
       $content = "document.getElementById('sub_kategori_produk').innerHTML = \"  <option value=''>- Pilih Sub Kategori Produk -</option>";
       foreach ($query_sub_kategori->result_array() as $data2) {
           $content .= "<option value='".$data2['id_kategori_produk_sub']."'>".$data2['nama_kategori_sub']."</option>";
       }
       $content .= "\"";
       echo $content;
       echo "}\n";
    }
    ?>
    }
</script>

<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Produk Baru</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form','name'=>'demo');
              echo form_open_multipart('administrator/tambah_produk',$attributes); 
          echo "<div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                    <tr><th width='150px' scope='row'>Pemilik</th>                   <td><select style='color:red' name='id_reseller' class='form-control combobox' required>";
                                        if (config('mode')=='marketplace'){
                                          echo "<option value='0' selected>Perusahaan / Mall</option>";
                                          $reseller = $this->db->query("SELECT * FROM rb_reseller");
                                        }else{
                                          $reseller = $this->db->query("SELECT * FROM rb_reseller where verifikasi='Y'");
                                        }
                                        foreach ($reseller->result_array() as $row){
                                            echo "<option value='$row[id_reseller]'>$row[nama_reseller]</option>";
                                        }
                    echo "</td></tr>
                    <tr><th scope='row'>Kategori</th>                   <td><select name='a' class='form-control' onchange=\"showSub()\" required>
                                        <option value='' selected>- Pilih Kategori Produk -</option>";
                                        foreach ($record as $row){
                                            echo "<option value='$row[id_kategori_produk]'>$row[nama_kategori]</option>";
                                        }
                    echo "</td></tr>
                    <tr><th scope='row'>Sub Kategori</th>                   <td><select name='aa' class='form-control' id='sub_kategori_produk'>
                                          <option value='' selected>- Pilih Sub Kategori Produk -</option>
                                        </td></tr>
                    <tr><th scope='row'>Nama Produk</th>  <td><input type='text' class='form-control' name='b' required></td></tr>
                    <tr><th scope='row'>Satuan</th>                     <td><input type='text' class='form-control' name='c'></td></tr>
                    <tr><th scope='row'>Berat / Gram</th>                      <td><input type='number' class='form-control' name='berat'></td></tr>
                    <tr><th scope='row'>Stok Awal</th>             <td><input type='number' name='stok' class='form-control'> </td></tr>
                    <tr><th scope='row'>Harga Modal</th>                 <td><input type='text' class='form-control formatNumber' name='d'></td></tr>
                    <tr><th scope='row'>Harga Reseller</th>             <td><input type='text' class='form-control formatNumber' name='e'></td></tr>
                    <tr><th scope='row'>Harga Konsumen</th>             <td><input type='text' class='form-control formatNumber' id='f' name='f'></td></tr>
                    
                    <tr><th scope='row'>Harga Platform</th>             <td><input type='text' class='form-control formatNumber' name='harga_platform' ></td></tr>
                    <tr><th scope='row'>Harga Level Newbie</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_newbie' name='harga_level_newbie' style='width:60% !important; display:inline-block'>   <input type='text' class='form-control' style='width:30% !important; display:inline-block' onchange='$(`#harga_level_newbie`).val( (parseInt($(`#f`).val().replace(`,`,``))*(100-parseInt($(this).val())))/100 ).trigger(`change`) '> <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Bronze</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_pedagang' name='harga_level_pedagang' style='width:60% !important; display:inline-block'>   <input type='text' class='form-control' style='width:30% !important; display:inline-block' onchange='$(`#harga_level_pedagang`).val( (parseInt($(`#f`).val().replace(`,`,``))*(100-parseInt($(this).val())))/100 ).trigger(`change`) '> <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Silver</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_juragan' name='harga_level_juragan' style='width:60% !important; display:inline-block'>   <input type='text' class='form-control' style='width:30% !important; display:inline-block' onchange='$(`#harga_level_juragan`).val( (parseInt($(`#f`).val().replace(`,`,``))*(100-parseInt($(this).val())))/100 ).trigger(`change`) '> <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Gold</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_big' name='harga_level_big' style='width:60% !important; display:inline-block'>   <input type='text' class='form-control' style='width:30% !important; display:inline-block' onchange='$(`#harga_level_big`).val( (parseInt($(`#f`).val().replace(`,`,``))*(100-parseInt($(this).val())))/100 ).trigger(`change`) '> <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Platinum</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_bos' name='harga_level_bos' style='width:60% !important; display:inline-block'>   <input type='text' class='form-control' style='width:30% !important; display:inline-block' onchange='$(`#harga_level_bos`).val( (parseInt($(`#f`).val().replace(`,`,``))*(100-parseInt($(this).val())))/100 ).trigger(`change`) '> <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      
                      <tr><th scope='row'>Admin & Deviden</th>             <td><input type='text' class='form-control formatNumber' id='admin_persen' name='admin_persen' value='".rupiah($rows['admin_persen'])."' style='width:25% !important; display:inline-block'>   <div class='input-group-text' style='width:20% !important; display:inline-block'>% Admin / </div> <input type='text' class='form-control formatNumber' id='deviden_persen' name='deviden_persen' value='".rupiah($rows['deviden_persen'])."' style='width:25% !important; display:inline-block'>   <div class='input-group-text' style='width:20% !important; display:inline-block'>% Deviden</div> </td></tr>
                      
                      
                    <tr><th scope='row'>Diskon</th>                 <td><input type='text' class='form-control formatNumber' name='diskon'></td></tr>
                    <tr><th scope='row'>Fee Produk (%)</th>                 <td><input type='text' class='form-control formatNumber' name='fee_produk'></td></tr>
                    <tr><th scope='row'>Stock Keeping Unit</th>         <td><input type='text' class='form-control' name='sku'>
                                                                      <small><i>Kode unik SKU jika ingin menandai produk.</i></small></td></tr>
                    <tr><th scope='row'>Min. Order</th>                 <td><input type='number' class='form-control' name='minimum' value='1'></td></tr>
                    <tr><th scope='row'>Cuplikan</th>                 <td><textarea class='form-control' name='fff' style='height:140px'></textarea></td></tr>
                    
                    </tbody>
                  </table>
                </div>

                <div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                    <tr><th scope='row'>Pre-Order</th>                 <td><select name='pre_order_status' id='pre_order_status' class='form-control form-mini'>";
                    $preorder = array('Tidak','Ya');
                    for ($i=0; $i < count($preorder) ; $i++) { 
                      echo "<option value='".$preorder[$i]."'>".$preorder[$i]."</option>";
                    }
                    echo "</select>
                    <div class='lama_pre_order' style='display:none'><input type='number' style='width:30%; display:inline-block' class='form-control form-mini' name='pre_order' value='2'> Hari</div></td></tr>
                    
                    <tr><th scope='row'>Group Order</th>    <td>
                      <div id='group'>
                        <div id='div3_1'>
                          <input style='width:39%; display:inline-block' placeholder='Jumlah 1' type='number' class='form-control form-mini' id='jumlah_1' name='jumlah[]'>
                          <input style='width:58%; display:inline-block' placeholder='Harga Group 1' type='number' class='form-control form-mini' id='harga_1' name='harga[]'>
                        </div>
                      </div>
                      <a href=\"javascript:void(0);\" onclick=\"addElementg();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp; 
                      <a href=\"javascript:void(0);\" onclick=\"removeElementg();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                    </td></tr>

                    <tr><th scope='row'>Jenis Paket</th>    <td>
                      <div id='jenisx'>
                        <div id='div4_1'>
                          <input style='width:40%; display:inline-block' placeholder='Nama Paket 1' type='text' class='form-control form-mini' id='namal_1' name='namal[]'>
                          <input style='width:23%; display:inline-block' placeholder='Qty' type='number' class='form-control form-mini' id='qtyl_1' name='qtyl[]'>
                          <input style='width:33%; display:inline-block' placeholder='Harga' type='number' class='form-control form-mini' id='hargal_1' name='hargal[]'>
                        </div>
                      </div>
                          <a href=\"javascript:void(0);\" onclick=\"addElementgx();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a>
                          <a href=\"javascript:void(0);\" onclick=\"removeElementgx();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                    </td></tr>
                    

                    <tr><th scope='row'>Merek</th>                    <td><div class='checkbox-scroll'>";
                                                                            foreach ($tag as $tag){
                                                                                echo "<span style='display:block;'><input type=checkbox value='$tag[tag_seo]' name=tag[]> $tag[nama_tag] &nbsp; &nbsp; &nbsp; </span>";
                                                                            }
                      echo "</div></td></tr>
                    

                    <tr><th scope='row'>Jenis Produk</th> <td>
                        <select name='jenis_produk' id='jenis_produk' class='form-control form-mini'>";
                        $preorder = array('Fisik','Digital');
                        for ($i=0; $i < count($preorder) ; $i++) { 
                          echo "<option value='".$preorder[$i]."'>".$preorder[$i]."</option>";
                        }
                        echo "</select>
                        <div class='jenis_produk_file' style='display:none; margin-top:5px;'>
                          <div id='mulitplefileuploaderx' class='mt-2'>Choose files</div>
                          <div id='statusx'></div>
                        </div>
                    </td></tr>

                    </tbody>
                  </table>
                </div>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  
                  
                  <tr><th width='130px' scope='row'>Variasi 1</th>                 <td>
                      <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12'>
                        <input type='text' class='form-control form-mini' name='variasi1' style='font-weight:bold; color:red' placeholder='- - - - - - - -'>
                        <div id='content'>
                          <div id='div_1'>
                            <input style='width:38%; display:inline-block' placeholder='Input 1 .........' type='text' class='form-control form-mini' id='warna_1' name='warna[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga 1 .........' type='number' class='form-control form-mini' id='hargaa_1' name='hargaa[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga Platform 1 .........' type='number' class='form-control form-mini' id='platforma_1' name='platforma[]'>
                          </div>
                          <div id='div_2'>
                            <input style='width:38%; display:inline-block' placeholder='Input 2 .........' type='text' class='form-control form-mini' id='warna_2' name='warna[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga 2 .........' type='number' class='form-control form-mini' id='hargaa_2' name='hargaa[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga Platform 2 .........' type='number' class='form-control form-mini' id='platforma_2' name='platforma[]'>
                          </div>
                        </div>
                            <a href=\"javascript:void(0);\" onclick=\"addElement();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp;
                            <a href=\"javascript:void(0);\" onclick=\"removeElement();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                        </div>
                      </div>   
                    </td></tr>

                    <tr><th scope='row'>Variasi 2</th>                 <td>
                      <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12'>
                        <input type='text' class='form-control form-mini' name='variasi2' style='font-weight:bold; color:red' placeholder='- - - - - - - -'>
                        <div id='content1'>
                          <div id='div_1'>
                            <input style='width:38%; display:inline-block' placeholder='Input 1 .........' type='text' class='form-control form-mini' id='ukuran_1' name='ukuran[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga 1 .........' type='number' class='form-control form-mini' id='hargab_1' name='hargab[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga Platform 1 .........' type='number' class='form-control form-mini' id='platformb_1' name='platformb[]'>
                          </div>
                          <div id='div_2'>
                            <input style='width:38%; display:inline-block' placeholder='Input 2 .........' type='text' class='form-control form-mini' id='ukuran_2' name='ukuran[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga 2 .........' type='number' class='form-control form-mini' id='hargab_2' name='hargab[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga Platform 1 .........' type='number' class='form-control form-mini' id='platformb_2' name='platformb[]'>
                          </div>
                        </div>
                            <a href=\"javascript:void(0);\" onclick=\"addElement1();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp;
                            <a href=\"javascript:void(0);\" onclick=\"removeElement1();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                        </div>
                      </div>    
                    </td></tr>
                    
                    <tr><th scope='row'>Lainnya</th>                 <td>
                      <div class='form-group row' style='margin-bottom:5px'>
                        <div class='col-sm-12'>
                        <input type='text' class='form-control form-mini' name='variasi3' style='font-weight:bold; color:red' placeholder='- - - - - - - -'>
                        <div id='content2'>
                          <div id='div_1'>
                            <input style='width:38%; display:inline-block' placeholder='Input 1 .........' type='text' class='form-control form-mini' id='lainnya_1' name='lainnya[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga 1 .........' type='number' class='form-control form-mini' id='hargac_1' name='hargac[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga Platform 1 .........' type='number' class='form-control form-mini' id='platformc_1' name='platformc[]'>
                          </div>
                          <div id='div_2'>
                            <input style='width:38%; display:inline-block' placeholder='Input 2 .........' type='text' class='form-control form-mini' id='lainnya_2' name='lainnya[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga 2 .........' type='number' class='form-control form-mini' id='hargac_2' name='hargac[]'>
                            <input style='width:30%; display:inline-block' placeholder='+ Harga Platform 2 .........' type='number' class='form-control form-mini' id='platformc_2' name='platformc[]'>
                          </div>
                        </div>
                            <a href=\"javascript:void(0);\" onclick=\"addElement2();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp;
                            <a href=\"javascript:void(0);\" onclick=\"removeElement2();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                        </div>
                      </div> 
                    </td></tr>
                    
                    
                    <tr><th width='130px' scope='row'>Keterangan</th>                 <td><textarea id='editor1' class='form-control' name='ff'></textarea></td></tr>
                    <tr><th scope='row'>Aktif</th>                          <td><input type='radio' name='aktif' value='Y' checked> Ya &nbsp; <input type='radio' name='aktif' value='N'> Tidak</td></tr>
                    <tr><th scope='row'>Foto Produk</th>                     <td>
                      <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                      <div id='status'></div>
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Tambahkan</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";
?>
<script>
$(document).ready(function(){
var Privileges = jQuery('#pre_order_status');
Privileges.change(function () {
    if ($(this).val() == 'Ya') {
      $("#pre_order_status").attr("style", "width:40%; display:inline-block");
      $(".lama_pre_order").attr("style", "display:inline");
      $("input[name='pre_order']").prop('required',true);
    }else{
      $("#pre_order_status").removeAttr("style");
      $(".lama_pre_order").attr("style", "display:none");
      $("input[name='pre_order']").removeAttr('required');
    }
});

var jenisProduk = jQuery('#jenis_produk');
jenisProduk.change(function () {
    if ($(this).val() == 'Digital') {
      $(".jenis_produk_file").attr("style", "display:block; margin-top:5px;");
      $("input[name='jenis_produk_file']").prop('required',true);
    }else{
      $(".jenis_produk_file").attr("style", "display:none");
      $("input[name='jenis_produk_file']").removeAttr('required');
    }
});

var settings = {
    url: "<?php echo base_url().$this->uri->segment(1); ?>/upload",
    formData: {id: "<?php echo $this->session->id_konsumen; ?>"},
    dragDrop: true,
    
	  maxFileCount:5,
    fileName: "uploadFile",
	  maxFileSize:5000*1024,
    allowedTypes:"jpg,png,jpeg,gif,webp",		
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDone:false,
    showDelete:true,
    deleteCallback: function(data,pd) {
        $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile",{op: "delete", name:data},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");   
            });
        for(var i=0;i<data.length;i++) {
            $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile",{op:"delete",name:data[i]},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");  
            });
        }   
        pd.statusbar.hide();
    }   
}
$("#mulitplefileuploader").uploadFile(settings);
});

$(document).ready(function(){
var settingsx = {
    url: "<?php echo base_url().$this->uri->segment(1); ?>/uploadx",
    formData: {id: "<?php echo $this->session->id_konsumen; ?>x"},
    dragDrop: true,
    
	  maxFileCount:5,
    fileName: "uploadFile",
	  maxFileSize:50000*1024,
    allowedTypes:"zip,rar,tar",		
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDone:false,
    showDelete:true,
    deleteCallback: function(data,pd) {
        $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFilex",{op: "delete", name:data},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");   
            });
        for(var i=0;i<data.length;i++) {
            $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFilex",{op:"delete",name:data[i]},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");  
            });
        }   
        pd.statusbar.hide();
    }   
}
$("#mulitplefileuploaderx").uploadFile(settingsx);
});
</script>
<script>
var intTextBox = 2;
function addElement() {
    intTextBox++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div_' + intTextBox);
    objNewDiv.innerHTML = '<input style="width:38%; display:inline-block" placeholder="Input ' + intTextBox + ' ........." type="text" class="form-control form-mini" id="warna_' + intTextBox + '" name="warna[]"/> <input style="width:30%; display:inline-block" placeholder="+ Harga ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="hargaa_' + intTextBox + '" name="hargaa[]"/>    <input style="width:30%; display:inline-block" placeholder="+ Harga Platform ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="platforma_' + intTextBox + '" name="platforma[]"/>';
    document.getElementById('content').appendChild(objNewDiv);
}

function removeElement() {
    if(0 < intTextBox) {
        document.getElementById('content').removeChild(document.getElementById('div_' + intTextBox));
        intTextBox--;
    } else {
        alert("Tidak ditemukan textbox untuk dihapus.");
    }
}

var intTextBox1 = 2;
function addElement1() {
    intTextBox1++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div_' + intTextBox1);
    objNewDiv.innerHTML = '<input style="width:38%; display:inline-block" placeholder="Input ' + intTextBox1 + ' ........."  type="text" class="form-control form-mini" id="ukuran_' + intTextBox1 + '" name="ukuran[]"/> <input style="width:30%; display:inline-block" placeholder="+ Harga ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="hargab_' + intTextBox + '" name="hargab[]"/>   <input style="width:30%; display:inline-block" placeholder="+ Harga Platform ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="platformb_' + intTextBox + '" name="platformb[]"/>';
    document.getElementById('content1').appendChild(objNewDiv);
}

function removeElement1() {
    if(0 < intTextBox1) {
        document.getElementById('content1').removeChild(document.getElementById('div_' + intTextBox1));
        intTextBox1--;
    } else {
      alert("Tidak ditemukan textbox untuk dihapus.");
    }
}

var intTextBox2 = 2;
function addElement2() {
    intTextBox2++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div_' + intTextBox2);
    objNewDiv.innerHTML = '<input style="width:38%; display:inline-block" placeholder="Input ' + intTextBox2 + ' ........."  type="text" class="form-control form-mini" id="lainnya_' + intTextBox2 + '" name="lainnya[]"/> <input style="width:30%; display:inline-block" placeholder="+ Harga ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="hargac_' + intTextBox + '" name="hargac[]"/>     <input style="width:30%; display:inline-block" placeholder="+ Harga Platform ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="platformc_' + intTextBox + '" name="platformc[]"/>';
    document.getElementById('content2').appendChild(objNewDiv);
}

function removeElement2() {
    if(0 < intTextBox2) {
        document.getElementById('content2').removeChild(document.getElementById('div_' + intTextBox2));
        intTextBox2--;
    } else {
      alert("Tidak ditemukan textbox untuk dihapus.");
    }
}

var intTextBoxg = 1;
function addElementg() {
    intTextBoxg++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div3_' + intTextBoxg);
    objNewDiv.innerHTML = '<input style="width:39%; display:inline-block" placeholder="Jumlah ' + intTextBoxg + '"  type="number" class="form-control form-mini" id="jumlah_' + intTextBoxg + '" name="jumlah[]"/> <input style="width:58%; display:inline-block" placeholder="Harga Group ' + intTextBoxg + '"  type="number" class="form-control form-mini" id="harga_' + intTextBoxg + '" name="harga[]"/>';
    document.getElementById('group').appendChild(objNewDiv);
}

function removeElementg() {
    if(0 < intTextBoxg) {
        document.getElementById('group').removeChild(document.getElementById('div3_' + intTextBoxg));
        intTextBoxg--;
    } else {
      alert("Tidak ditemukan textbox untuk dihapus.");
    }
}

var intTextBoxgx = 1;
function addElementgx() {
    intTextBoxgx++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div4_' + intTextBoxgx);
    objNewDiv.innerHTML = '<input type="hidden" value="0" name="id_level[]"><input style="width:40%; display:inline-block" placeholder="Nama Paket ' + intTextBoxgx + '"  type="text" class="form-control form-mini" id="namal_' + intTextBoxgx + '" name="namal[]"/> <input style="width:23%; display:inline-block" placeholder="Qty"  type="number" class="form-control form-mini" id="qtyl_' + intTextBoxgx + '" name="qtyl[]"/> <input style="width:33%; display:inline-block" placeholder="Harga"  type="number" class="form-control form-mini" id="hargal_' + intTextBoxgx + '" name="hargal[]"/>';
    document.getElementById('jenisx').appendChild(objNewDiv);
}

function removeElementgx() {
    if(0 < intTextBoxgx) {
        document.getElementById('jenisx').removeChild(document.getElementById('div4_' + intTextBoxgx));
        intTextBoxgx--;
    } else {
      alert("Tidak ditemukan textbox untuk dihapus.");
    }
}
</script>
