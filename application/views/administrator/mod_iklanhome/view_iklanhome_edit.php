<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Iklan Home</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/edit_iklanhome',$attributes); 
              $ex = explode(' - ',$rows['judul']);
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$rows[id_iklantengah]'>
                    <tr><th width='120px' scope='row'>Posisi</th>    <td><select class='form-control' name='aa'>";
                    $posisi = array('Home','Mall','Footer');
                    for ($i=0; $i<count($posisi); $i++) { 
                      if ($ex[0]==$posisi[$i]){
                        echo "<option value='".$posisi[$i]."' selected>".$posisi[$i]."</option>";
                      }else{
                        echo "<option value='".$posisi[$i]."'>".$posisi[$i]."</option>";
                      }
                    }
                    echo "</select></td></tr>
                    <tr><th width='120px' scope='row'>Judul</th>    <td><input type='text' class='form-control' name='a' value='$ex[1]' required></td></tr>
                    <tr><th width='120px' scope='row'>Url</th>    <td><input type='url' class='form-control' name='b' value='$rows[url]' required></td></tr>
                    <tr><th width='120px' scope='row'>Gambar</th>    <td><input type='file' class='form-control' name='c'>";
                                                                        if ($rows['gambar'] != ''){ echo "Lihat Gambar : <a target='_BLANK' href='".base_url()."asset/foto_iklantengah/$rows[gambar]'>$rows[gambar]</a>"; } echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='".base_url().$this->uri->segment(1)."/iklanhome'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div></div></div>";
            echo form_close();
