<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Konsumen</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('id' => 'formku','class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/edit_konsumen',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' value='".$this->uri->segment(3)."' name='id'>";
                    if (trim($rows['foto'])==''){ $foto_user = 'blank.png'; }else{ if (!file_exists("asset/foto_user/$rows[foto]")){ $foto_user = 'blank.png'; }else{ $foto_user = $rows['foto']; } }
                    echo "<tr bgcolor='#e3e3e3'><th rowspan='15' width='110px'><center><img style='border:1px solid #cecece; height:85px; width:85px' src='".base_url()."asset/foto_user/$foto_user' class='img-circle img-thumbnail'></center></th></tr>
                    <tr><th width='130px' scope='row'>Username</th>                     <td><input class='form-control' type='text' name='bb' value='$rows[username]' disabled></td></tr>
                    <tr><th scope='row'>Ganti Password</th>                     <td><input class='form-control' type='password' name='a'></td></tr>
                    <tr><th scope='row'>Nama Lengkap</th>                 <td><input class='form-control' type='text' name='b' value='$rows[nama_lengkap]'></td></tr>
                    <tr><th scope='row'>Alamat Email</th>                 <td><input class='form-control' type='email' name='c' value='$rows[email]'></td></tr>
                    <tr><th scope='row'>No Hp</th>                        <td><input class='form-control' type='number' name='k' value='$rows[no_hp]'></td></tr>
                    <tr><th scope='row'>Jenis Kelamin</th>                <td>"; if ($rows['jenis_kelamin']=='Laki-laki'){ echo "<input type='radio' value='Laki-laki' name='d' checked> Laki-laki <input type='radio' value='Perempuan' name='d'> Perempuan "; }else{ echo "<input type='radio' value='Laki-laki' name='d'> Laki-laki <input type='radio' value='Perempuan' name='d' checked> Perempuan "; } echo "</td></tr>
                    <tr><th scope='row'>Tanggal Lahir</th>                <td><input class='datepicker form-control' type='text' name='e' value='$rows[tanggal_lahir]' data-date-format='yyyy-mm-dd'></td></tr>
                    <tr><th scope='row'>Alamat</th>               <td><input class='form-control' type='text' name='g' value='$rows[alamat_lengkap]'></td></tr>
                    <tr><td><b>Propinsi</b></td>         <td><select class='form-control form-mini' name='provinsi_id' id='list_provinsi' required>";
                    echo "<option value=0>- Pilih Provinsi -</option>";
                      $provinsi = $this->db->query("SELECT * FROM tb_ro_provinces ORDER BY province_name ASC");
                      foreach ($provinsi->result_array() as $row) {
                        if ($rows['provinsi_id']==$row['province_id']){
                          echo "<option value='$row[province_id]' selected>$row[province_name]</option>";
                        }else{
                          echo "<option value='$row[province_id]'>$row[province_name]</option>";
                        }
                      }
                    echo "</select>
                          </td></tr>
                          <tr><td><b>Kota</b></td>             <td><select class='form-control form-mini' name='kota_id' id='list_kotakab' required>";
                          echo "<option value=0>- Pilih Kota / Kabupaten -</option>";
                          $kota = $this->db->query("SELECT * FROM tb_ro_cities where province_id='$rows[provinsi_id]' ORDER BY city_name ASC");
                            foreach ($kota->result_array() as $row) {
                              if ($rows['kota_id']==$row['city_id']){
                                echo "<option value='$row[city_id]' selected>$row[city_name]</option>";
                              }else{
                                echo "<option value='$row[city_id]'>$row[city_name]</option>";
                              }
                            }
                          echo "</select>
                          </td></tr>
                    <tr><td><b>Kecamatan</b></td>  <td><select class='form-control form-mini' name='kecamatan_id' id='list_kecamatan' required>";
                    echo "<option value=0>- Pilih Kecamatan -</option>";
                      $subdistrict = $this->db->query("SELECT * FROM tb_ro_subdistricts where city_id='$rows[kota_id]' ORDER BY subdistrict_name ASC");
                      foreach ($subdistrict->result_array() as $row) {
                        if ($rows['kecamatan_id']==$row['subdistrict_id']){
                          echo "<option value='$row[subdistrict_id]' selected>$row[subdistrict_name]</option>";
                        }else{
                          echo "<option value='$row[subdistrict_id]'>$row[subdistrict_name]</option>";
                        }
                      }
                    echo "</select></td></tr>
                    <tr><th scope='row'>Ganti Foto</th>                         <td><input type='file' class='form-control' name='gg'>";
                                                                               if ($rows['foto'] != ''){ echo "<i style='color:red'>Foto Profile saat ini : </i><a target='_BLANK' href='".base_url()."asset/foto_user/$rows[foto]'>$rows[foto]</a>"; } echo "</td></tr>";
                    
                    $ver = $this->db->query("SELECT * FROM rb_konsumen_verifikasi where id_konsumen='$rows[id_konsumen]'");
                    if ($ver->num_rows()>=1){
                      echo "<tr><th scope='row'>Status Akun</th>  <td><select name='verifikasi' class='form-control form-mini'>";
                        $verif = $ver->row_array();
                        $status_verifikasi = array('Pending Verification','Verified Account'); 
                        for ($i=0; $i < count($status_verifikasi); $i++) { 
                          if ($verif['status_verifikasi']==$status_verifikasi[$i]){
                            echo "<option value='".$status_verifikasi[$i]."' selected>".$status_verifikasi[$i]."</option>";
                          }else{
                            echo "<option value='".$status_verifikasi[$i]."'>".$status_verifikasi[$i]."</option>";
                          }
                        }
                      echo "</select>
                      File verifikasi : <a href='".base_url()."members/download/$verif[file_verifikasi]'>Lihat Lampiran</a>
                      </td></tr>";         
                    }else{
                      echo "<input type='hidden' value='0' name='verifikasi'>";
                    }
                    echo "</tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";
