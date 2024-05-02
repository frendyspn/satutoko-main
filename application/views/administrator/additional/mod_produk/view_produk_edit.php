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

<style>
    .scroll-horizontal {
        width: 100%; /* Atur lebar div sesuai kebutuhan */
        overflow-x: auto; /* Aktifkan scroll horizontal */
        white-space: nowrap; /* Hindari pemutusan baris otomatis */
    }
</style>
<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Produk Terpilih</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form','name'=>'demo');
              echo form_open_multipart('administrator/edit_produk',$attributes);
              $disk = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$rows['id_produk']))->row_array();
              if ($rows['id_reseller']=='0'){
                  $jual = $this->model_reseller->jual($rows['id_produk'])->row_array();
                  $beli = $this->model_reseller->beli($rows['id_produk'])->row_array();
              }else{
                  $jual = $this->model_reseller->jual_reseller($rows['id_reseller'],$rows['id_produk'])->row_array();
                  $beli = $this->model_reseller->beli_reseller($rows['id_reseller'],$rows['id_produk'])->row_array();
              }
              $kupon = $this->db->query("SELECT * FROM rb_produk_kupon where id_produk='$rows[id_produk]'");

          echo "<div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                      <input type='hidden' name='id' value='$rows[id_produk]'>
                      <tr><th width='150px'scope='row'>Pemilik</th>                   <td><select style='color:red' name='id_reseller' class='form-control combobox' required>";
                        if (config('mode')=='marketplace'){
                          echo "<option value='0' selected>Perusahaan / Mall</option>";
                          $reseller = $this->db->query("SELECT * FROM rb_reseller");
                        }else{
                          $reseller = $this->db->query("SELECT * FROM rb_reseller where verifikasi='Y'");
                        }
                        
                        foreach ($reseller->result_array() as $row){
                          if ($rows['id_reseller']==$row['id_reseller']){
                            echo "<option value='$row[id_reseller]' selected>$row[nama_reseller]</option>";
                          }else{
                            echo "<option value='$row[id_reseller]'>$row[nama_reseller]</option>";
                          }
                        }
                      echo "</td></tr>
                      <tr><th scope='row'>Kategori</th>                   <td><select name='a' class='form-control' onchange=\"showSub()\" required>
                                                                              <option value='' selected>- Pilih Kategori Produk -</option>";
                                                                              foreach ($record as $row){
                                                                                if ($rows['id_kategori_produk']==$row['id_kategori_produk']){
                                                                                  echo "<option value='$row[id_kategori_produk]' selected>$row[nama_kategori]</option>";
                                                                                }else{
                                                                                  echo "<option value='$row[id_kategori_produk]'>$row[nama_kategori]</option>";
                                                                                }
                                                                              }
                      echo "</select></td></tr>
                      <tr><th scope='row'>Sub Kategori</th>                   <td><select name='aa' class='form-control' id='sub_kategori_produk'>
                                                                                  <option value='' selected>- Pilih Sub Kategori Produk -</option>";
                                                                                  $sub_kategori_produk = $this->db->query("SELECT * FROM rb_kategori_produk_sub where id_kategori_produk='$rows[id_kategori_produk]'");
                                                                                  foreach ($sub_kategori_produk->result_array() as $row){
                                                                                    if ($rows['id_kategori_produk_sub']==$row['id_kategori_produk_sub']){
                                                                                      echo "<option value='$row[id_kategori_produk_sub]' selected>$row[nama_kategori_sub]</option>";
                                                                                    }else{
                                                                                      echo "<option value='$row[id_kategori_produk_sub]'>$row[nama_kategori_sub]</option>";
                                                                                    }
                                                                                  }
                      echo "</select></td></tr>
                      <tr><th width='130px' scope='row'>Nama Produk</th>  <td><input type='text' class='form-control' name='b' value='$rows[nama_produk]' required></td></tr>
                      <tr><th scope='row'>Satuan</th>                     <td><input type='text' class='form-control' name='c' value='$rows[satuan]'></td></tr>
                      <tr><th scope='row'>Berat / Gram</th>                      <td><input type='number' class='form-control' name='berat' value='$rows[berat]'></td></tr>
                      <tr><th scope='row'>Stok / Tambah</th>                       <td><input type='number' class='form-control' style='width:100px !important; display:inline-block' value='".stok($rows['id_reseller'],$rows['id_produk'])."' disabled>
                                                                              + <input type='number' name='stok' class='form-control' style='width:100px !important; display:inline-block'>  
                                                                              <a style='font-size:12px; margin-left:5px; margin-top:-3px' class='btn btn-success btn-sm allStok' data-toggle='modal' data-target='#stokProduk'><i class='fa fa-list'></i> History Stok</a> </td></tr>
                      <tr><th scope='row'>Harga Modal</th>                 <td><input type='text' class='form-control formatNumber' name='d' value='".rupiah($rows['harga_beli'])."'></td></tr>
                      <tr><th scope='row'>Harga Reseller</th>             <td><input type='text' class='form-control formatNumber' name='e' value='".rupiah($rows['harga_reseller'])."'></td></tr>
                      <tr><th scope='row'>Harga Konsumen</th>             <td><input type='text' class='form-control formatNumber' id='f' name='f' value='".rupiah($rows['harga_konsumen'])."'></td></tr>
                      <tr><th scope='row'>Harga Platform</th>             <td><input type='text' class='form-control formatNumber' style='width:30% !important; display:inline-block' name='harga_platform' value='".rupiah($rows['harga_platform_persen'])."'> <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Newbie</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_newbie' name='harga_level_newbie' value='".rupiah($rows['harga_level_newbie_persen'])."' style='width:30% !important; display:inline-block'>    <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Bronze</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_pedagang' name='harga_level_pedagang' value='".rupiah($rows['harga_level_pedagang_persen'])."' style='width:30% !important; display:inline-block'>    <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Silver</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_juragan' name='harga_level_juragan' value='".rupiah($rows['harga_level_juragan_persen'])."' style='width:30% !important; display:inline-block'>    <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Gold</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_big' name='harga_level_big' value='".rupiah($rows['harga_level_big_persen'])."' style='width:30% !important; display:inline-block'>    <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      <tr><th scope='row'>Harga Level Platinum</th>             <td><input type='text' class='form-control formatNumber' id='harga_level_bos' name='harga_level_bos' value='".rupiah($rows['harga_level_bos_persen'])."' style='width:30% !important; display:inline-block'>   <div class='input-group-text' style='width:5% !important; display:inline-block'>%</div> </td></tr>
                      
                      <tr><th scope='row'>Admin & Deviden</th>             <td><input type='text' class='form-control formatNumber' id='admin_persen' name='admin_persen' value='".rupiah($rows['admin_persen'])."' style='width:25% !important; display:inline-block'>   <div class='input-group-text' style='width:20% !important; display:inline-block'>% Admin / </div> <input type='text' class='form-control formatNumber' id='deviden_persen' name='deviden_persen' value='".rupiah($rows['deviden_persen'])."' style='width:25% !important; display:inline-block'>   <div class='input-group-text' style='width:20% !important; display:inline-block'>% Deviden</div> </td></tr>
                      
                      <tr><th scope='row'>Diskon</th>                 <td><input type='text' class='form-control formatNumber' name='diskon' value='".rupiah($disk['diskon'])."'></td></tr>
                      <tr><th scope='row'>Fee Produk (%)</th>                 <td><input type='text' class='form-control formatNumber' name='fee_produk' value='$rows[fee_produk]'></td></tr>
                      <tr><th scope='row'>Stock Keeping Unit</th>         <td><input type='text' class='form-control' name='sku' value='$rows[sku]'>
                                                                      <small><i>Kode unik SKU jika ingin menandai produk.</i></small></td></tr>
                      <tr><th scope='row'>Min. Order</th>                 <td><input type='number' class='form-control' name='minimum' value='$rows[minimum]'></td></tr>";
                      if (config('fee_produk')=='Y'){ echo "<tr><th scope='row'>Fee Produk</th>             <td><input style='border:1px solid blue' type='text' class='form-control formatNumber' name='fee_produk' value='".rupiah($rows['fee_produk'])."'></td></tr>"; }
                      echo "<tr><th scope='row'>Cuplikan</th>                 <td><textarea class='form-control' name='fff' style='height:160px'>$rows[tentang_produk]</textarea></td></tr>
                      </tbody>
                  </table>
                </div>
                <div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                      <tr><td colspan='2'><button type='button' class='btn btn-success btn-block btn-sm' style='padding:7px 10px' data-toggle='modal' data-target='#kuponProduk'>Buat Kupon / Voucher <span class='badge badge-secondary' style='background-color:#222; color:#fff; padding:5px 9px 4px 7px'><span class='kupon_button'>".$kupon->num_rows()."</span></span></button></td></tr>
                      
                      <tr><th scope='row'>Pre-Order</th>                 <td>";
                      if ($rows['pre_order']!=''){ $status = 'Ya'; $display = 'inline'; $style="width:40%; display:inline-block"; }else{ $status = 'Tidak'; $display = 'none'; $style=""; }
                      echo "<select name='pre_order_status' id='pre_order_status' class='form-control form-mini' style='$style'>";
                      $preorder = array('Tidak','Ya');
                      for ($i=0; $i < count($preorder) ; $i++) { 
                        if ($preorder[$i]==$status){
                          echo "<option value='".$preorder[$i]."' selected>".$preorder[$i]."</option>";
                        }else{
                          echo "<option value='".$preorder[$i]."'>".$preorder[$i]."</option>";
                        }
                      }
                      echo "</select>
                      <div class='lama_pre_order' style='display:$display'><input type='number' style='width:30%; display:inline-block' class='form-control form-mini' name='pre_order' placeholder='0' value='$rows[pre_order]'> Hari</div>
                      </td></tr>

                      <tr><th scope='row'>Group Order</th>    <td>
                        <div id='group'>";
                        $group = $this->db->query("SELECT * FROM rb_produk_group where id_produk='$rows[id_produk]' ORDER BY id_group ASC");
                        $no = 1;
                        foreach ($group->result_array() as $row) {
                          echo "<div id='div3_$no'>
                            <input type='hidden' value='$row[id_group]' name='id_group[]' id='id$no'>
                            <input style='width:35%; display:inline-block' placeholder='Jumlah $no' type='number' value='$row[jumlah_group]' class='form-control form-mini' id='jumlah_$no' name='jumlah[]'>
                            <input style='width:55%; display:inline-block' placeholder='Harga Group $no' type='number' value='$row[harga_group]' class='form-control form-mini' id='harga_$no' name='harga[]'>
                            <a href='".base_url().$this->uri->segment(1)."/edit_produk/".$this->uri->segment(3)."?id=$row[id_group]'><i class='fa fa-remove' style='color:red; font-weight:900'></i></a>
                          </div>";
                          $no++;
                        }
                        echo "</div>
                      </div>
                      <a class='btn btn-default btn-sm btn-block' href=\"javascript:void(0);\" onclick=\"addElementg();\"> Tambahkan</a> 
                    </td></tr>

                    <tr><th scope='row'>Jenis Paket</th>    <td>
                      <div id='jenisx'>";
                      $jenis_order = $this->db->query("SELECT * FROM rb_produk_level where id_produk='$rows[id_produk]' ORDER BY id_level ASC");
                      $no = 1;
                      foreach ($jenis_order->result_array() as $row) {
                        echo "<div id='div4_$no'>
                          <input type='hidden' value='$row[id_level]' name='id_level[]' id='id$no'>
                          <input style='width:40%; display:inline-block' placeholder='Nama Paket $no' type='text' value='$row[nama_level]' class='form-control form-mini' id='namal_$no' name='namal[]'>
                          <input style='width:20%; display:inline-block' placeholder='Qty' type='number' value='$row[qty_level]' class='form-control form-mini' id='qtyl_$no' name='qtyl[]'>
                          <input style='width:30%; display:inline-block' placeholder='Harga' type='number' value='$row[harga_level]' class='form-control form-mini' id='hargal_$no' name='hargal[]'>
                          <a href='".base_url().$this->uri->segment(1)."/edit_produk/".$this->uri->segment(3)."?idx=$row[id_level]'><i class='icon-cross-circle' style='color:red; font-weight:900'></i></a>
                        </div>";
                        $no++;
                      }
                      echo "</div>
                          <a class='btn btn-default btn-sm btn-block' href=\"javascript:void(0);\" onclick=\"addElementgx();\">Tambahkan</a>
                    </td></tr>

                      <tr><th width='130px' scope='row'>Merek</th>                    <td><div class='checkbox-scroll'>";
                              $_arrNilai = explode(',', $rows['tag']);
                              foreach ($tag as $tag){
                                  $_ck = (array_search($tag['tag_seo'], $_arrNilai) === false)? '' : 'checked';
                                  echo "<span style='display:block;'><input type=checkbox value='$tag[tag_seo]' name=tag[] $_ck> $tag[nama_tag] &nbsp; &nbsp; &nbsp; </span>";
                              }
                      echo "</div></td></tr>
                      
                      <datalist id='variasi'>
                        <option value='Warna'>
                        <option value='Ukuran'>
                        <option value='lainnya'>
                      </datalist>";
                      
                    $no = 1;
                    $noidcount = 0;
                    $variasi = $this->db->query("SELECT * FROM rb_produk_variasi where id_produk='$rows[id_produk]' ORDER BY id_variasi ASC");
                    if ($variasi->num_rows()>=1){
                      foreach ($variasi->result_array() as $row) {
                        if ($noidcount == 0){ $noid = ''; }else{ $noid = $noidcount; }
                        if ($no=='1'){ $intextbox1 = count(explode(';',$row['variasi'])); }
                        if ($no=='2'){$intextbox2 = count(explode(';',$row['variasi'])); }
                        if ($no=='3'){$intextbox3 = count(explode(';',$row['variasi'])); }
    
                        echo "<tr><th width='130px' scope='row'>Variasi $no</th>                 <td>
                        <div class='form-group row' style='margin-bottom:5px'>
                          <div class='col-sm-9'>
                          <input type='text' class='form-control form-mini' name='variasix$no' style='font-weight:bold; color:red' value='$row[nama]' placeholder='- - - - - - - -'>
                          <div id='content$noid'>";
                            $ex = explode(';',$row['variasi']);
                            $exx = explode(';',$row['variasi_harga']);
                            $nameh = array('a','b','c');
                            for ($i=0; $i < count($ex); $i++) { 
                              $nomor = $i+1;
                              echo "<div id='div_$nomor'>
                                      <input style='width:58%; display:inline-block' placeholder='Input $nomor.........' value='".$ex[$i]."' type='text' class='form-control form-mini' id='variasi".$no."_".$nomor."' name='variasi".$no."[]'>
                                      <input style='width:40%; display:inline-block' placeholder='+ Harga $nomor.........' value='".$exx[$i]."' type='number' class='form-control form-mini' id='harga$nameh[$noidcount]".$no."_".$nomor."' name='harga$nameh[$noidcount]".$no."[]'>
                                    </div>";
                            }
                            echo "</div>
                              <a href=\"javascript:void(0);\" onclick=\"addElement$noid();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp; 
                              <a href=\"javascript:void(0);\" onclick=\"removeElement$noid();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                          </div>
                        </div>    
                        </td></tr>";
                        $no++;
                        $noidcount++;
                      }
                    }
    
                    $total = (3-$variasi->num_rows());
                    $no = $variasi->num_rows()+1;
                    if ($variasi->num_rows() == 0){ 
                      $noidx = ''; 
                    }else{ 
                      $noidx = $variasi->num_rows(); 
                    }
                    $namehx = array('','a','b','c');
                    $data_example = array('','Warna','Ukuran','Lainnya');
                    for ($i=1; $i <= $total; $i++) { 
    
                      if ($no=='1'){ $intextbox1 = 2; }
                      if ($no=='2'){$intextbox2 = 2; }
                      if ($no=='3'){$intextbox3 = 2; }

                      if (($no-1)=='0'){ $divid = ''; }else{ $divid = ($no-1); }
                      
                      echo "<tr><th width='130px' scope='row'>Variasi $no</th>                 <td>
                        <div class='form-group row' style='margin-bottom:5px'>
                          <div class='col-sm-9'>
                          <input type='text' class='form-control form-mini' name='variasix$no' style='font-weight:bold; color:red' placeholder='- - - - - - - -'>
                          <div id='content$noidx'>
                            <div id='div".$divid."_1'>
                            <input style='width:58%; display:inline-block' placeholder='Input 1 .........' type='text' class='form-control form-mini' id='variasi".$no."_1' name='variasi".$no."[]'>
                            <input style='width:40%; display:inline-block' placeholder='+ Harga 1.........' type='number' class='form-control form-mini' id='hargac".$no."_1' name='hargac".$no."[]'>
                            </div>
                            <div id='div".$divid."_2'>
                            <input style='width:58%; display:inline-block' placeholder='Input 2 .........' type='text' class='form-control form-mini' id='variasi".$no."_2' name='variasi".$no."[]'>
                            <input style='width:40%; display:inline-block' placeholder='+ Harga 2.........' type='number' class='form-control form-mini' id='hargac".$no."_2' name='hargac".$no."[]'>
                            </div>
                          </div>
                              <a href=\"javascript:void(0);\" onclick=\"addElement$noidx();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp; 
                              <a href=\"javascript:void(0);\" onclick=\"removeElement$noidx();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                          </div>
                        </div>    
                        </td></tr>";
                        $no++;
                        $noidx++;
                    }

                      echo "
                      
                  <tr><th scope='row'>Jenis Produk</th> <td>
                  <select name='jenis_produk' id='jenis_produk' class='form-control form-mini'>";
                  if ($rows['jenis_produk']=='Digital'){ $display = 'block'; }else{ $display = 'none'; }
                  $preorder = array('Fisik','Digital');
                  for ($i=0; $i < count($preorder) ; $i++) { 
                    if ($rows['jenis_produk']==$preorder[$i]){
                      echo "<option value='".$preorder[$i]."' selected>".$preorder[$i]."</option>";
                    }else{
                      echo "<option value='".$preorder[$i]."'>".$preorder[$i]."</option>";
                    }
                  }
                  echo "</select>
                  <div class='jenis_produk_file' style='display:$display; margin-top:5px;'>
                    <div id='mulitplefileuploaderx' class='mt-2'>Choose files</div>
                    <div id='statusx'></div>";
                    if ($rows['produk_file'] != ''){ 
                      $ex = explode(';',$rows['produk_file']);
                      $hitungex = count($ex);
                      echo "<span style='color:Red'>File Produk Digital saat ini : </span><br>";
                      for($i=0; $i<$hitungex; $i++){
                          if (file_exists("asset/foto_produk/".$ex[$i])) { 
                              echo "<div class='item' style='border-bottom:1px dotted #cecece; padding-left: 10px;'><a target='_BLANK' href='".base_url().$this->uri->segment('1')."/download_file/files/".$ex[$i]."'>".($i+1)."). ".$ex[$i]."</a></div>";
                          }else{
                            echo "<div class='item' style='border-bottom:1px dotted #cecece; padding-left: 10px;'><a target='_BLANK' href='#'><del>".($i+1)."). ".$ex[$i]."</del>  (Not Found)</a></div>";
                          }
                      }
                    }
                  echo "
                  </div>
                  </td></tr>

                      </tbody>
                  </table>
                </div>";

                
                // VARIASI NEW START
                
                // echo "
                // <div class='col-md-12'>";
                    
                    $width_1 = '58%';
                    $width_2 = '40%';
                //     $width_3 = '200px';
                //     $width_4 = '200px';
                //     $width_5 = '200px';
                //     $width_6 = '200px';
                //     $width_7 = '200px';
                //     $width_8 = '200px';
                    
                //     $no = 1;
                //     $noidcount = 0;
                //     $variasi = $this->db->query("SELECT * FROM rb_produk_variasi where id_produk='$rows[id_produk]' ORDER BY id_variasi ASC");
                //     if ($variasi->num_rows()>=1){
                //       foreach ($variasi->result_array() as $row) {
                //         if ($noidcount == 0){ $noid = ''; }else{ $noid = $noidcount; }
                //         if ($no=='1'){ $intextbox1 = count(explode(';',$row['variasi'])); }
                //         if ($no=='2'){$intextbox2 = count(explode(';',$row['variasi'])); }
                //         if ($no=='3'){$intextbox3 = count(explode(';',$row['variasi'])); }
    
                //         echo "<tr><th width='130px' scope='row'>Variasi $no</th>                 <td>
                //         <div class='form-group row' style='margin-bottom:5px'>
                //           <div class='col-sm-12'>
                //           <input type='text' class='form-control form-mini' name='variasix$no' style='font-weight:bold; color:red' value='$row[nama]' placeholder='- - - - - - - -'>
                //           <div id='content$noid' class='scroll-horizontal'>";
                //             $ex = explode(';',$row['variasi']);
                //             $exx = explode(';',$row['variasi_harga']);
                //             $exxx = explode(';',$row['harga_platform']);
                //             $exxxx = explode(';',$row['harga_level_newbie']);
                //             $exxxxx = explode(';',$row['harga_level_pedagang']);
                //             $exxxxxx = explode(';',$row['harga_level_juragan']);
                //             $exxxxxxx = explode(';',$row['harga_level_big']);
                //             $exxxxxxxx = explode(';',$row['harga_level_bos']);
                            
                //             $nameh = array('a','b','c');
                //             echo "<div >
                //                       <input style='width:$width_1; display:inline-block' type='text' class='form-control form-mini' value='Nama Variasi' readonly>
                //                       <input style='width:$width_2; display:inline-block' type='text' class='form-control form-mini' value='Harga Konsumen' readonly>
                //                       <input style='width:$width_3; display:inline-block' type='text' class='form-control form-mini' value='Harga Platform' readonly>
                //                       <input style='width:$width_4; display:inline-block' type='text' class='form-control form-mini' value='Harga Newbie' readonly>
                //                       <input style='width:$width_5; display:inline-block' type='text' class='form-control form-mini' value='Harga Bronze' readonly>
                //                       <input style='width:$width_6; display:inline-block' type='text' class='form-control form-mini' value='Harga Silver' readonly>
                //                       <input style='width:$width_7; display:inline-block' type='text' class='form-control form-mini' value='Harga Gold' readonly>
                //                       <input style='width:$width_8; display:inline-block' type='text' class='form-control form-mini' value='Harga Platinum' readonly>
                //                     </div>";
                //             for ($i=0; $i < count($ex); $i++) { 
                //               $nomor = $i+1;
                //               echo "<div id='div_$nomor'>
                //                       <input style='width:$width_1; display:inline-block' placeholder='Input $nomor.........' value='".$ex[$i]."' type='text' class='form-control form-mini' id='variasi".$no."_".$nomor."' name='variasi".$no."[]'>
                //                       <input style='width:$width_2; display:inline-block' placeholder='+ Harga $nomor.........' value='".$exx[$i]."' type='number' class='form-control form-mini' id='harga$nameh[$noidcount]".$no."_".$nomor."' name='harga$nameh[$noidcount]".$no."[]'>
                //                       <input style='width:$width_3; display:inline-block' placeholder='+ Harga Platform $nomor.........' value='".$exxx[$i]."' type='number' class='form-control form-mini' id='platform$nameh[$noidcount]".$no."_".$nomor."' name='platform$nameh[$noidcount]".$no."[]'>
                //                       <input style='width:$width_4; display:inline-block' placeholder='+ Harga Newbie $nomor.........' value='".$exxxx[$i]."' type='number' class='form-control form-mini' id='newbie$nameh[$noidcount]".$no."_".$nomor."' name='newbie$nameh[$noidcount]".$no."[]'>
                //                       <input style='width:$width_5; display:inline-block' placeholder='+ Harga Bronze $nomor.........' value='".$exxxxx[$i]."' type='number' class='form-control form-mini' id='bronze$nameh[$noidcount]".$no."_".$nomor."' name='bronze$nameh[$noidcount]".$no."[]'>
                //                       <input style='width:$width_6; display:inline-block' placeholder='+ Harga Silver $nomor.........' value='".$exxxxxx[$i]."' type='number' class='form-control form-mini' id='silver$nameh[$noidcount]".$no."_".$nomor."' name='silver$nameh[$noidcount]".$no."[]'>
                //                       <input style='width:$width_7; display:inline-block' placeholder='+ Harga Gold $nomor.........' value='".$exxxxxxx[$i]."' type='number' class='form-control form-mini' id='gold$nameh[$noidcount]".$no."_".$nomor."' name='gold$nameh[$noidcount]".$no."[]'>
                //                       <input style='width:$width_8; display:inline-block' placeholder='+ Harga Platinum $nomor.........' value='".$exxxxxxxx[$i]."' type='number' class='form-control form-mini' id='platinum$nameh[$noidcount]".$no."_".$nomor."' name='platinum$nameh[$noidcount]".$no."[]'>
                //                     </div>";
                //             }
                //             echo "</div>
                //               <a href=\"javascript:void(0);\" onclick=\"addElement$noid();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp; 
                //               <a href=\"javascript:void(0);\" onclick=\"removeElement$noid();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                //           </div>
                //         </div>    
                //         </td></tr>";
                //         $no++;
                //         $noidcount++;
                //       }
                //     }
                    
                //     $total = (3-$variasi->num_rows());
                //     $no = $variasi->num_rows()+1;
                //     if ($variasi->num_rows() == 0){ 
                //       $noidx = ''; 
                //     }else{ 
                //       $noidx = $variasi->num_rows(); 
                //     }
                //     $namehx = array('','a','b','c');
                //     $data_example = array('','Warna','Ukuran','Lainnya');
                //     for ($i=1; $i <= $total; $i++) { 
    
                //       if ($no=='1'){ $intextbox1 = 2; }
                //       if ($no=='2'){$intextbox2 = 2; }
                //       if ($no=='3'){$intextbox3 = 2; }

                //       if (($no-1)=='0'){ $divid = ''; }else{ $divid = ($no-1); }
                      
                //       echo "<tr><th width='130px' scope='row'>Variasi $no</th>                 <td>
                //         <div class='form-group row' style='margin-bottom:5px'>
                //           <div class='col-sm-12'>
                //           <input type='text' class='form-control form-mini' name='variasix$no' style='font-weight:bold; color:red' placeholder='- - - - - - - -'>
                //           <div id='content$noidx' class='scroll-horizontal'>
                //                     <div >
                //                       <input style='width:$width_1; display:inline-block' type='text' class='form-control form-mini' value='Nama Variasi' readonly>
                //                       <input style='width:$width_2; display:inline-block' type='text' class='form-control form-mini' value='Harga Konsumen' readonly>
                //                       <input style='width:$width_3; display:inline-block' type='text' class='form-control form-mini' value='Harga Platform' readonly>
                //                       <input style='width:$width_4; display:inline-block' type='text' class='form-control form-mini' value='Harga Newbie' readonly>
                //                       <input style='width:$width_5; display:inline-block' type='text' class='form-control form-mini' value='Harga Bronze' readonly>
                //                       <input style='width:$width_6; display:inline-block' type='text' class='form-control form-mini' value='Harga Silver' readonly>
                //                       <input style='width:$width_7; display:inline-block' type='text' class='form-control form-mini' value='Harga Gold' readonly>
                //                       <input style='width:$width_8; display:inline-block' type='text' class='form-control form-mini' value='Harga Platinum' readonly>
                //                     </div>
                //             <div id='div".$divid."_1'>
                //                 <input style='width:$width_1; display:inline-block' placeholder='Input 1 .........' type='text' class='form-control form-mini' id='variasi".$no."_1' name='variasi".$no."[]'>
                //                 <input style='width:$width_2; display:inline-block' placeholder='+ Harga 1.........' type='number' class='form-control form-mini' id='hargac".$no."_1' name='hargac".$no."[]'>
                //                 <input style='width:$width_3; display:inline-block' placeholder='+ Harga Platform 1.........' type='number' class='form-control form-mini' id='platformc".$no."_1' name='platformc".$no."[]'>
                //                 <input style='width:$width_4; display:inline-block' placeholder='+ Harga Newbie 1.........' type='number' class='form-control form-mini' id='newbiec".$no."_1' name='newbiec".$no."[]'>
                //                 <input style='width:$width_5; display:inline-block' placeholder='+ Harga Bronze 1.........' type='number' class='form-control form-mini' id='bronzec".$no."_1' name='bronzec".$no."[]'>
                //                 <input style='width:$width_6; display:inline-block' placeholder='+ Harga Silver 1.........' type='number' class='form-control form-mini' id='silverc".$no."_1' name='silverc".$no."[]'>
                //                 <input style='width:$width_7; display:inline-block' placeholder='+ Harga Gold 1.........' type='number' class='form-control form-mini' id='goldc".$no."_1' name='goldc".$no."[]'>
                //                 <input style='width:$width_8; display:inline-block' placeholder='+ Harga Platinum 1.........' type='number' class='form-control form-mini' id='platinumc".$no."_1' name='platinumc".$no."[]'>
                //             </div>
                                    
                //             <div id='div".$divid."_2'>
                //                 <input style='width:$width_1; display:inline-block' placeholder='Input 2 .........' type='text' class='form-control form-mini' id='variasi".$no."_2' name='variasi".$no."[]'>
                //                 <input style='width:$width_2; display:inline-block' placeholder='+ Harga 2.........' type='number' class='form-control form-mini' id='hargac".$no."_2' name='hargac".$no."[]'>
                //                 <input style='width:$width_3; display:inline-block' placeholder='+ Harga Platform 2.........' type='number' class='form-control form-mini' id='platformc".$no."_2' name='platformc".$no."[]'>
                //                 <input style='width:$width_4; display:inline-block' placeholder='+ Harga Newbie 2.........' type='number' class='form-control form-mini' id='newbiec".$no."_2' name='newbiec".$no."[]'>
                //                 <input style='width:$width_5; display:inline-block' placeholder='+ Harga Bronze 2.........' type='number' class='form-control form-mini' id='bronzec".$no."_2' name='bronzec".$no."[]'>
                //                 <input style='width:$width_6; display:inline-block' placeholder='+ Harga Silver 2.........' type='number' class='form-control form-mini' id='silverc".$no."_2' name='silverc".$no."[]'>
                //                 <input style='width:$width_7; display:inline-block' placeholder='+ Harga Gold 2.........' type='number' class='form-control form-mini' id='goldc".$no."_2' name='goldc".$no."[]'>
                //                 <input style='width:$width_8; display:inline-block' placeholder='+ Harga Platinum 2.........' type='number' class='form-control form-mini' id='platinumc".$no."_2' name='platinumc".$no."[]'>
                //             </div>
                //           </div>
                //               <a href=\"javascript:void(0);\" onclick=\"addElement$noidx();\"><i class='fa fa-plus' style='color:green; font-weight:900'></i> Tambah</a> &nbsp; 
                //               <a href=\"javascript:void(0);\" onclick=\"removeElement$noidx();\"><i class='fa fa-remove' style='color:red; font-weight:900'></i> Hapus</a>
                //           </div>
                //         </div>    
                //         </td></tr>";
                //         $no++;
                //         $noidx++;
                //     }
                // echo "</div>";
                
                // VARIASI NEW END


                echo "
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='130px' scope='row'>Keterangan</th>                 <td><textarea  id='editor1' class='form-control' name='ff'>$rows[keterangan]</textarea></td></tr>
                    <tr><th scope='row'>Status</th>                          <td>"; if ($rows['aktif']=='Y'){ echo "<input type='radio' name='aktif' value='Y' checked> Publish &nbsp; <input type='radio' name='aktif' value='N'> Tolak &nbsp; <input type='radio' name='aktif' value='R'> Revisi &nbsp; <input type='radio' name='aktif' value='D'> Draft"; }else if ($rows['aktif']=='N'){ echo "<input type='radio' name='aktif' value='Y'> Publish &nbsp; <input type='radio' name='aktif' value='N' checked> Tolak &nbsp; <input type='radio' name='aktif' value='R'> Revisi &nbsp; <input type='radio' name='aktif' value='D'> Draft"; }else if ($rows['aktif']=='R'){ echo "<input type='radio' name='aktif' value='Y'> Publish &nbsp; <input type='radio' name='aktif' value='N'> Tolak &nbsp; <input type='radio' name='aktif' value='R' checked> Revisi &nbsp; <input type='radio' name='aktif' value='D'> Draft"; }else if ($rows['aktif']=='D'){ echo "<input type='radio' name='aktif' value='Y'> Publish &nbsp; <input type='radio' name='aktif' value='N'> Tolak &nbsp; <input type='radio' name='aktif' value='R'> Revisi &nbsp; <input type='radio' name='aktif' value='D' checked> Draft"; } echo "</td></tr>
                    <tr><th scope='row'>Catatan Untuk Revisi</th>           <td> <textarea class='form-control' rows='10' id='catatan_revisi' name='catatan_revisi'>$rows[revisi_feedback]</textarea></td></tr>
                    <tr><th scope='row'>Foto Produk</th>                     <td>
                      <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                      <div id='status'></div>";
                      if ($rows['gambar'] != ''){ 
                        $ex = explode(';',$rows['gambar']);
                        $hitungex = count($ex);
                        echo "<span style='color:Red'>File Foto Produk saat ini : </span><br>";
                        for($i=0; $i<$hitungex; $i++){
                            if (file_exists("asset/foto_produk/".$ex[$i])) { 
                                echo "<div class='item' style='border-bottom:1px dotted #cecece; padding-left: 10px;'><a target='_BLANK' href='".base_url()."asset/foto_produk/".$ex[$i]."'>".($i)."). ".$ex[$i]."</a></div>";
                            }
                        }
                      }
                    echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";
?>

<div class="modal fade" id="stokProduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">History Stok Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style='padding:1rem 30px 30px'>
      <div style='height:250px; overflow:scroll;'>
      <div class='listStok'>
        <?php 
          $stok_history = $this->db->query("SELECT a.*, b.service, b.waktu_transaksi FROM rb_penjualan_detail a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where a.id_produk='$rows[id_produk]' AND b.id_pembeli='$rows[id_reseller]' ORDER BY a.id_penjualan_detail DESC");
          foreach ($stok_history->result_array() as $row) {
            echo "<div style='border-bottom:1px dotted #cecece'>+ <b>$row[jumlah] $row[satuan]</b>, <i style='color:#8a8a8a'>$row[service]</i> 
                  <a style='cursor:pointer; margin-left:10px; margin-right:10px; color:red' onclick=\"delete_stok('$row[id_penjualan_detail]','$rows[id_reseller]')\" class='pull-right'><i class='fa fa-remove'></i></a> <small style='cursor:pointer' title='$row[waktu_transaksi]' class='pull-right'>".cek_terakhir($row['waktu_transaksi'])." Lalu</small> </div>";
          }
        ?>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="kuponProduk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Kode Kupon / Voucher Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style='padding:1rem 30px 30px'>
      <div style='height:250px; overflow:scroll;'>
      <div class='listKupon'>
        <table class='table table-sm'>
          <thead>
          <tr>
            <th>No</th>
            <th>Kode Kupon</th>
            <th>Jumlah</th>
            <th>Expire</th>
            <th>Min Order</th>
            <th>Nilai (Rp)</th>
            <th></th>
          </tr>
          <tr style='background:#e3e3e3'>
            <th><input type="hidden" name='id_kupon' id='id_kupon'>
                <input type="hidden" name='id_produk' id='id_produk' value='<?= $rows['id_produk']; ?>'></th>
            <th><input style='background:#e3e3e3; width:120px' id='kode_kupon' onkeyup="nospaces(this)" type='text' name='kode_kupon' placeholder='XXXXXXXXX' autocomplete='off'></th>
            <th><input type='number' id='jumlah_kupon' style='height:30px; background:#e3e3e3; width:90px' name='jumlah_kupon' placeholder='0' autocomplete='off'></th>
            <th><input style='background:#e3e3e3; width:90px' id='expire_date' type='text' name='expire_date' placeholder='YYYY-MM-DD HH:II:SS' value='<?= date('Y-m-d'); ?>' autocomplete='off'></th>
            <th><input type='number' id='min_order' style='height:30px; background:#e3e3e3; width:90px;' name='min_order' value='1' autocomplete='off'></th>
            <th><input style='background:#e3e3e3; width:100px' id='nilai_kupon' type='text' name='nilai_kupon' class='formatNumber' placeholder='**.***.***' autocomplete='off'></th>
            <th><button type='button' name='submit' id='submit' class='ps-btn margin-btn' style='padding:2px 10px !important; font-size:12px; width:80px'>Simpan</button></th>
          </tr>
          </thead>
          <tbody id="show_data"></tbody>
        </table>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    
    
                    
var Privileges = jQuery('#pre_order_status');
Privileges.change(function () {
    if ($(this).val() == 'Ya') {
      $("#pre_order_status").attr("style", "width:40%; display:inline-block");
      $(".lama_pre_order").attr("style", "display:inline");
    }else{
      $("#pre_order_status").removeAttr("style");
      $(".lama_pre_order").attr("style", "display:none");
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

show_kupon();  
function show_kupon(){
  $.ajax({
      type  : 'ajax',
      url   : '<?php echo site_url('administrator/kupon/'.$rows['id_produk']); ?>',
      async : true,
      dataType : 'json',
      success : function(data){
          var html = '';
          var i;
          for(i=0; i<data.length; i++){
            html += '<tr>'+
                    '<td>'+(i+1)+'</td>'+
                    '<td><b style="color:green">'+data[i].kode_kupon+'</b></td>'+
                    '<td>'+data[i].used+'/'+data[i].jumlah_kupon+' Kupon</td>'+
                    '<td>'+data[i].expire_date+'</td>'+
                    '<td><b>'+data[i].min_order+'</b></td>'+
                    '<td><b>'+toDuit(data[i].nilai_kupon)+'</b></td>'+
                    '<td>'+
                      '<a href="javascript:void(0);" class="ps-btn margin-btn gray-btn custom-btn item_edit" style="padding:2px 10px !important; font-size:12px" data-id_kupon="'+data[i].id_kupon+'" data-kode_kupon="'+data[i].kode_kupon+'" data-nilai_kupon="'+data[i].nilai_kupon+'" data-jumlah_kupon="'+data[i].jumlah_kupon+'" data-expire_date="'+data[i].expire_date+'" data-min_order="'+data[i].min_order+'">Edit</a>'+' '+
                      '<a href="javascript:void(0);" class="ps-btn margin-btn red-btn custom-btn item_delete" style="padding:2px 10px !important; font-size:12px" data-id_kupon="'+data[i].id_kupon+'"><i class="icon-cross"></i></a></td>'+
                  '</tr>';
          }
          $('#show_data').html(html);
      }
  });
}

 //Save product
 $('#submit').on('click',function(){
    var a = $('#kode_kupon').val();
    var b = $('#jumlah_kupon').val();
    var c = $('#expire_date').val();
    var d = $('#nilai_kupon').val();
    var e = $('#min_order').val();
    var id_produk = $('#id_produk').val();
    var id_kupon = $('#id_kupon').val();
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('administrator/kupon_save')?>",
        dataType : "JSON",
        data : {a:a , b:b, c:c, d:d, e:e, id_produk:id_produk, id_kupon:id_kupon},
        success: function(data){
            $('[name="id_kupon"]').val("");
            $('[name="kode_kupon"]').val("");
            $('[name="jumlah_kupon"]').val("");
            $('[name="min_order"]').val("1");
            $('[name="nilai_kupon"]').val("");
            $(".kupon_button").hide().load(" .kupon_button").fadeIn();
            show_kupon();
        }
    });
    return false;
});

//get data for update record
$('#show_data').on('click','.item_edit',function(){
    var id_kupon = $(this).data('id_kupon');
    var kode_kupon = $(this).data('kode_kupon');
    var nilai_kupon        = $(this).data('nilai_kupon');
    var min_order        = $(this).data('min_order');
    var jumlah_kupon        = $(this).data('jumlah_kupon');
    var expire_date        = $(this).data('expire_date');
      
    $('[name="kode_kupon"]').val(kode_kupon);
    $('[name="jumlah_kupon"]').val(jumlah_kupon);
    $('[name="expire_date"]').val(expire_date);
    $('[name="nilai_kupon"]').val(toRupiah(nilai_kupon));
    $('[name="id_kupon"]').val(id_kupon);
    $('[name="min_order"]').val(min_order);
});

$('#show_data').on('click','.item_delete',function(){
  var id = $(this).data('id_kupon');
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('administrator/kupon_delete')?>",
        dataType : "JSON",
        data : {id:id},
        success: function(data){
          show_kupon();  
          $(".kupon_button").hide().load(" .kupon_button").fadeIn();
        }
    });
    return false;
});
});

function delete_stok(id,id_reseller){
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('produk/delete_stok')?>",
        dataType : "JSON",
        data : {id:id,id_reseller:id_reseller},
        success: function(data){
            $(".listStok").hide().load(" .listStok").fadeIn();
            $(".allStok").hide().load(" .allStok").fadeIn();
        }
    });
    return false;
}
</script>
<script>
$(document).ready(function(){
var settings = {
    url: "<?php echo base_url().$this->uri->segment(1); ?>/upload",
    formData: {id: "<?php echo $this->session->id_konsumen; ?>"},
    dragDrop: true,
	  maxFileCount:10,
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
    
	  maxFileCount:10,
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
    var width_1 = '<?= $width_1 ?>';
    var width_2 = '<?= $width_2 ?>';
    var width_3 = '<?= $width_3 ?>';
    var width_4 = '<?= $width_4 ?>';
    var width_5 = '<?= $width_5 ?>';
    var width_6 = '<?= $width_6 ?>';
    var width_7 = '<?= $width_7 ?>';
    var width_8 = '<?= $width_8 ?>';
    
var intTextBox = <?php echo $intextbox1; ?>;
function addElement() {
    intTextBox++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div_' + intTextBox);
    // objNewDiv.innerHTML = '<input style="width:'+width_1+'; display:inline-block" placeholder="Input ' + intTextBox + ' ........." type="text" class="form-control form-mini" id="variasi1_' + intTextBox + '" name="variasi1[]"/>    <input style="width:'+width_2+'; display:inline-block" placeholder="+ Harga ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="hargaa_' + intTextBox + '" name="hargaa[]"/>   <input style="width:'+width_3+'; display:inline-block" placeholder="+ Harga Platform ' + intTextBox + '........." type="number" class="form-control form-mini" id="platforma_' + intTextBox + '" name="platforma[]"/>  <input style="width:'+width_4+'; display:inline-block" placeholder="+ Harga Newbie ' + intTextBox + '........." type="number" class="form-control form-mini" id="newbiea_' + intTextBox + '" name="newbiea[]"/>    <input style="width:'+width_5+'; display:inline-block" placeholder="+ Harga Bronze ' + intTextBox + '........." type="number" class="form-control form-mini" id="bronzea_' + intTextBox + '" name="bronzea[]"/>    <input style="width:'+width_6+'; display:inline-block" placeholder="+ Harga Silver ' + intTextBox + '........." type="number" class="form-control form-mini" id="silvera_' + intTextBox + '" name="silvera[]"/>    <input style="width:'+width_7+'; display:inline-block" placeholder="+ Harga Gold ' + intTextBox + '........." type="number" class="form-control form-mini" id="golda_' + intTextBox + '" name="golda[]"/>  <input style="width:'+width_8+'; display:inline-block" placeholder="+ Harga Platinum ' + intTextBox + '........." type="number" class="form-control form-mini" id="platinuma_' + intTextBox + '" name="platinuma[]"/>';
    objNewDiv.innerHTML = '<input style="width:'+width_1+'; display:inline-block" placeholder="Input ' + intTextBox + ' ........." type="text" class="form-control form-mini" id="variasi1_' + intTextBox + '" name="variasi1[]"/>    <input style="width:'+width_2+'; display:inline-block" placeholder="+ Harga ' + intTextBox + ' ........." type="number" class="form-control form-mini" id="hargaa_' + intTextBox + '" name="hargaa[]"/>   ';
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

var intTextBox1 = <?php echo $intextbox2; ?>;
function addElement1() {
    intTextBox1++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div_' + intTextBox1);
    // objNewDiv.innerHTML = '<input style="width:'+width_1+'; display:inline-block" placeholder="Input ' + intTextBox1 + ' ........."  type="text" class="form-control form-mini" id="variasi2_' + intTextBox1 + '" name="variasi2[]"/>   <input style="width:'+width_2+'; display:inline-block" placeholder="+ Harga ' + intTextBox1 + ' ........." type="number" class="form-control form-mini" id="hargab_' + intTextBox1 + '" name="hargab[]"/>   <input style="width:'+width_3+'; display:inline-block" placeholder="+ Harga Platform ' + intTextBox1 + '........." type="number" class="form-control form-mini" id="platformb_' + intTextBox1 + '" name="platformb[]"/>     <input style="width:'+width_4+'; display:inline-block" placeholder="+ Harga Newbie ' + intTextBox1 + '........." type="number" class="form-control form-mini" id="newbieb_' + intTextBox1 + '" name="newbieb[]"/>   <input style="width:'+width_5+'; display:inline-block" placeholder="+ Harga Bronze ' + intTextBox1 + '........." type="number" class="form-control form-mini" id="bronzeb_' + intTextBox1 + '" name="bronzeb[]"/>    <input style="width:'+width_6+'; display:inline-block" placeholder="+ Harga Silver ' + intTextBox1 + '........." type="number" class="form-control form-mini" id="silverb_' + intTextBox1 + '" name="silverb[]"/>    <input style="width:'+width_7+'; display:inline-block" placeholder="+ Harga Gold ' + intTextBox1 + '........." type="number" class="form-control form-mini" id="goldb_' + intTextBox1 + '" name="goldb[]"/>  <input style="width:'+width_8+'; display:inline-block" placeholder="+ Harga Platinum ' + intTextBox1 + '........." type="number" class="form-control form-mini" id="platinumb_' + intTextBox1 + '" name="platinumb[]"/>';
    objNewDiv.innerHTML = '<input style="width:'+width_1+'; display:inline-block" placeholder="Input ' + intTextBox1 + ' ........."  type="text" class="form-control form-mini" id="variasi2_' + intTextBox1 + '" name="variasi2[]"/>   <input style="width:'+width_2+'; display:inline-block" placeholder="+ Harga ' + intTextBox1 + ' ........." type="number" class="form-control form-mini" id="hargab_' + intTextBox1 + '" name="hargab[]"/>   ';
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

var intTextBox2 = <?php echo $intextbox3; ?>;
function addElement2() {
    intTextBox2++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div_' + intTextBox2);
    // objNewDiv.innerHTML = '<input style="width:'+width_1+'; display:inline-block" placeholder="Input ' + intTextBox2 + ' ........."  type="text" class="form-control form-mini" id="variasi3_' + intTextBox2 + '" name="variasi3[]"/>   <input style="width:'+width_2+'; display:inline-block" placeholder="+ Harga ' + intTextBox2 + ' ........." type="number" class="form-control form-mini" id="hargac_' + intTextBox2 + '" name="hargac[]"/>   <input style="width:'+width_3+'; display:inline-block" placeholder="+ Harga Platform ' + intTextBox2 + '........." type="number" class="form-control form-mini" id="platformc_' + intTextBox2 + '" name="platformc[]"/>     <input style="width:'+width_4+'; display:inline-block" placeholder="+ Harga Newbie ' + intTextBox2 + '........." type="number" class="form-control form-mini" id="newbiec_' + intTextBox2 + '" name="newbiec[]"/>   <input style="width:'+width_5+'; display:inline-block" placeholder="+ Harga Bronze ' + intTextBox2 + '........." type="number" class="form-control form-mini" id="bronzec_' + intTextBox2 + '" name="bronzec[]"/>    <input style="width:'+width_6+'; display:inline-block" placeholder="+ Harga Silver ' + intTextBox2 + '........." type="number" class="form-control form-mini" id="silverc_' + intTextBox2 + '" name="silverc[]"/>    <input style="width:'+width_7+'; display:inline-block" placeholder="+ Harga Gold ' + intTextBox2 + '........." type="number" class="form-control form-mini" id="goldc_' + intTextBox2 + '" name="goldc[]"/>     <input style="width:'+width_8+'; display:inline-block" placeholder="+ Harga Platinum ' + intTextBox2 + '........." type="number" class="form-control form-mini" id="platinumc_' + intTextBox2 + '" name="platinumc[]"/>';
    objNewDiv.innerHTML = '<input style="width:'+width_1+'; display:inline-block" placeholder="Input ' + intTextBox2 + ' ........."  type="text" class="form-control form-mini" id="variasi3_' + intTextBox2 + '" name="variasi3[]"/>   <input style="width:'+width_2+'; display:inline-block" placeholder="+ Harga ' + intTextBox2 + ' ........." type="number" class="form-control form-mini" id="hargac_' + intTextBox2 + '" name="hargac[]"/>   ';
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


var intTextBoxg = <?php echo $group->num_rows(); ?>;
function addElementg() {
    intTextBoxg++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div3_' + intTextBoxg);
    objNewDiv.innerHTML = '<input type="hidden" value="0" name="id_group[]"><input style="width:39%; display:inline-block" placeholder="Jumlah ' + intTextBoxg + '"  type="number" class="form-control form-mini" id="jumlah_' + intTextBoxg + '" name="jumlah[]"/> <input style="width:58%; display:inline-block" placeholder="Harga Group ' + intTextBoxg + '"  type="number" class="form-control form-mini" id="harga_' + intTextBoxg + '" name="harga[]"/>';
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

var intTextBoxgx = <?php echo $jenis_order->num_rows(); ?>;
function addElementgx() {
    intTextBoxgx++;
    var objNewDiv = document.createElement('div');
    objNewDiv.setAttribute('id', 'div4_' + intTextBoxgx);
    objNewDiv.innerHTML = '<input type="hidden" value="0" name="id_level[]"><input style="width:40%; display:inline-block" placeholder="Nama Paket ' + intTextBoxgx + '"  type="text" class="form-control form-mini" id="namal_' + intTextBoxgx + '" name="namal[]"/> <input style="width:20%; display:inline-block" placeholder="Qty"  type="number" class="form-control form-mini" id="qtyl_' + intTextBoxgx + '" name="qtyl[]"/> <input style="width:30%; display:inline-block" placeholder="Harga"  type="number" class="form-control form-mini" id="hargal_' + intTextBoxgx + '" name="hargal[]"/>';
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
