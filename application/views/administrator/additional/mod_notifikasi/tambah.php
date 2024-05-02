<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Sebarkan Notifikasi</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/tambah_notifikasi',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='120px' scope='row'>Kategori</th>    <td><select class='form-control' name='kategori' required>";
                    $kategori = array('all');
                    $kategori_nama = array('All User');
                    for ($i=0; $i <count($kategori) ; $i++) { 
                      echo "<option value='".$kategori[$i]."'>".$kategori_nama[$i]."</option>";
                    }
                    echo "</select></td></tr>
                    <tr><th scope='row'>Judul</th>    <td><input type='text' class='form-control' name='judul' required></td></tr>
                    <tr><th scope='row'>Konten</th>    <td><textarea id='editor1' class='form-control' name='konten'></textarea></td></tr>
                    <tr><th scope='row'>Url</th>    <td><input type='url' class='form-control' name='url' required></td></tr>
                  </tbody>
                  </table>
                </div>
              
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Tambahkan</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div></div></div>";
            echo form_close();
