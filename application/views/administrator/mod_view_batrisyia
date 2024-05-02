<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Images Slide</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/edit_imagesslider',$attributes); 
              $ex = explode("||",$rows['keterangan']);
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$rows[id_slide]'>
                    <tr><th width='120px' scope='row'>Keterangan</th>   <td><textarea class='form-control' name='a' style='height:100px'>$ex[1]</textarea></td></tr>
                    <tr><th width='120px' scope='row'>URL</th>    <td><input type='url' class='form-control' name='url' value='$ex[0]'></td></tr>
                    <tr><th scope='row'>Posisi</th>               <td>
                        <select class='form-control' style='text-transform:capitalize' name='posisi' required>
                          <option value=''>- Pilih -</option>";
                          $posisi = array('desktop','mobile','mall');
                          for ($i=0; $i<count($posisi); $i++) { 
                            if ($rows['posisi']==$posisi[$i]){
                              echo "<option value='".$posisi[$i]."' selected>".str_replace('_',' ',$posisi[$i])."</option>";
                            }else{
                              echo "<option value='".$posisi[$i]."'>".str_replace('_',' ',$posisi[$i])."</option>";
                            }
                          }
                    echo "</select></td></tr>
                    <tr><th scope='row'>Ganti Gambar</th>                    <td><input type='file' class='form-control' name='b'><hr style='margin:5px'>
                                                                                 <img class='img-thumbnail' style='height:80px' src='".base_url()."asset/foto_slide/$rows[gambar]'></td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='index.php'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";
