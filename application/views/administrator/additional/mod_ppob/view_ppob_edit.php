<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit PPOB</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/edit_ppob',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$rows[id_ppob]'>
                    <tr><th width='120px' scope='row'>Nama PPOB</th>    <td><input type='text' class='form-control' name='nama_ppob' value='$rows[nama_ppob]' required></td></tr>
                    <tr><th scope='row'>Aktif </th>        <td>"; if ($rows['aktif']=='1'){ echo "<input type='radio' name='aktif' value='1' checked> Aktif &nbsp; <input type='radio' name='aktif' value='0'> Non-aktif"; }else{ echo "<input type='radio' name='aktif' value='1'> Aktif &nbsp; <input type='radio' name='aktif' value='0' checked> Non-aktif"; } echo "</td></tr>
                    <tr><th scope='row'>Icon Kode</th>    <td><input type='text' class='form-control' name='icon_kode' value='$rows[icon_kode]'></td></tr>
                    <tr><th scope='row'>Icon Image</th>                 <td><input type='file' class='form-control' name='icon_image'>";
                    if ($rows['icon_image'] != ''){ echo "<i style='color:red'>Icon Saat ini : </i><a target='_BLANK' href='".base_url()."asset/images/$rows[icon_image]'>$rows[icon_image]</a>"; } echo "</td></tr>
                    
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";