<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<style>#mapid { height: 300px; } .show-map{ display:none; } </style>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

<style>
#mapid { height: 300px; } .show-map{ display:none; }
</style>
<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Informasi Toko / Pelapak</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/edit_reseller',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden'  value='$rows[id_reseller]' name='id'>";
                    if (trim($rows['foto'])==''){ $foto_toko = 'toko.jpg'; }else{ if (!file_exists("asset/foto_user/$rows[foto]")){ $foto_toko = 'toko.jpg'; }else{ $foto_toko = $rows['foto']; } } 
                    echo "<tr bgcolor='#e3e3e3'><th rowspan='14' width='110px'><center><img style='border:1px solid #cecece; height:85px; width:85px' src='".base_url()."asset/foto_user/$foto_toko' class='img-circle img-thumbnail'></center></th></tr>
                    <tr><th width='130px' scope='row'>Nama Pelapak</th>                <td><input class='form-control' type='text' name='c' value='$rows[nama_reseller]'></td></tr>
                    <tr><th scope='row'>Alamat Lengkap</th>               <td><input class='form-control' type='text' name='e' value='$rows[alamat_lengkap]'></td></tr>
                    <tr><th><b>Propinsi</b></th>         <td><select class='form-control form-mini' name='provinsi_id' id='list_provinsi' required>";
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
                          <tr><th><b>Kota</b></th>             <td><select class='form-control form-mini' name='kota_id' id='list_kotakab' required>";
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
                    <tr><th><b>Kecamatan</b></th>  <td><select class='form-control form-mini' name='kecamatan_id' id='list_kecamatan' required>";
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
                    <tr><th scope='row'>No Hp</th>                        <td><input class='form-control' type='number' name='f' value='$rows[no_telpon]'></td></tr>
                    <tr><th scope='row'>Pilihan Kurir</th>              <td><select class='form-mini' id='multiple_select' multiple='multiple'>";
                          $kurir_data = $this->model_app->view_ordering('rb_kurir','id_kurir','ASC');
                          $no = 0;
                          $kurir_terpilih = explode(',',$rows['pilihan_kurir']);
                          foreach ($kurir_data as $rowk) {
                            $print_selected = ''; 
                            foreach ($kurir_terpilih as $select_option){
                              if($rowk['id_kurir'] == $select_option) {
                                $print_selected = 'selected';
                                // break `foreach` as you found the right item
                                break;
                              }
                            }
                            echo "<option value='$rowk[id_kurir]' $print_selected>($rowk[kode_kurir]) $rowk[nama_kurir]</option>";
                            $no++;
                          }
                      echo "</select>
                      <input type='hidden' name='pilihan_kurir' value=''></td></tr>
                    <tr><th scope='row'>Keterangan</th>                   <td><textarea class='form-control' type='text' name='i' style='height:120px'>$rows[keterangan]</textarea></td></tr>
                    <input class='form-control' type='hidden' name='j' value='$rows[referral]'>
                    <tr><th scope='row'>Ganti Foto</th>                         <td><input type='file' class='form-control' name='gg'>";
                                                                               if ($rows['foto'] != ''){ echo "<i style='color:red'>Foto Profile saat ini : </i><a target='_BLANK' href='".base_url()."asset/foto_user/$rows[foto]'>$rows[foto]</a>"; } echo "</td></tr>
                  
                    <tr><th scope='row'>Kordinat</th>                      <td>
                      <input type='text' class='form-control form-mini btn-geolocationx' value='$rows[kordinat]' name='kordinat' id='lokasi' autocomplete='off' />
                      <input type='checkbox' name='alamat_lainx' id='alamat_lain'> Cari Kordinat dari Peta
                      <div class='show-map'>
                          <div id='mapid' class='shadow-sm'></div>
                      </div> 
                    </td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' id='assign' name='submit' class='btn btn-info'>Update</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";
?>
<script>
$('document').ready(function(){
    $('#assign').click(function(){
    var ag = $('#multiple_select').val();
        $('[name="pilihan_kurir"]').val(ag);
    });

    $("body").on("click", "input[name='alamat_lainx']", function () {
      if ($('#alamat_lain').is(':checked')) {
        $(".show-map").show();
        showMapsx();
      }else{
        $(".btn-geolocationx").val('');
        $(".show-map").hide();
      }
    });
});

function showMapsx() {
  // MAPS
  var mymap = L.map("mapid").setView(
    [<?php echo ($rows['kordinat']==''?config('kordinat'):$rows['kordinat']); ?>],
    15
  );
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mymap);

  L.marker([<?php echo ($rows['kordinat']==''?config('kordinat'):$rows['kordinat']); ?>])
    .addTo(mymap)
    .bindPopup("Silahkan klik map untuk mendapatkan koordinat.")
    .openPopup();

  var popup = L.popup();

  function onMapClick(e) {
    popup
      .setLatLng(e.latlng)
      .setContent(
        "Map yang anda klik berada di " + e.latlng.lat + ", " + e.latlng.lng
      )
      .openOn(mymap);
    document.getElementById("lokasi").value =
      e.latlng.lat + ", " + e.latlng.lng;
  }

  mymap.on("click", onMapClick);
}

$(window).ready(function () {
  $(".btn-geolocationx").click(findLocationx);
});

function findLocationx() {
  navigator.geolocation.getCurrentPosition(getCoordsx, handleErrorsx);
}

function getCoordsx(position) {
  $(".btn-geolocationx").val(
    position.coords.latitude + "," + position.coords.longitude
  );
}

function handleErrorsx(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      alert("You need to share your geolocation data.");
      break;

    case error.POSITION_UNAVAILABLE:
      alert("Current position not available.");
      break;

    case error.TIMEOUT:
      alert("Retrieving position timed out.");
      break;

    default:
      alert("Error");
      break;
  }
}
</script>
