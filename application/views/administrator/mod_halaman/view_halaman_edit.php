<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Halaman Statis</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/edit_halamanbaru',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$rows[id_halaman]'>
                    <tr><th width='120px' scope='row'>Judul</th>   <td><input type='text' class='form-control' name='a' value='$rows[judul]'></td></tr>
                    <tr><th scope='row'>Isi Halaman</th>           <td><textarea id='editor1' class='form-control' name='b' style='height:260px'>$rows[isi_halaman]</textarea></td></tr>
                    <tr><th scope='row'>Landing Page</th>               <td>"; if ($rows['landing_page']=='Y'){ echo "<input type='radio' name='landing_page' value='Y' checked> Ya &nbsp; <input type='radio' name='landing_page' value='N'> Tidak"; }else{ echo "<input type='radio' name='landing_page' value='Y'> Ya &nbsp; <input type='radio' name='landing_page' value='N' checked> Tidak"; } echo "</td></tr>
                    <tr><th scope='row'>Ganti Gambar</th>          <td><input type='file' class='form-control' name='c'><hr style='margin:5px'>";
                                                                   if ($rows['gambar']!=''){ echo " Gambar Saat ini : <a target='_BLANK' href='".base_url()."asset/foto_statis/$rows[gambar]'>$rows[gambar]</a> - <a class='btn btn-xs btn-danger' href='".base_url()."administrator/edit_halamanbaru/46?hapus'>Hapus</a>"; } echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='".base_url().$this->uri->segment(1)."/halamanbaru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div></div></div>";
            echo form_close();