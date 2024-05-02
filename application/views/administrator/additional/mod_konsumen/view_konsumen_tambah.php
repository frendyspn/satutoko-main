<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/css/bootstrap-multiselect.css">
<style>
#mapid { height: 300px; } .show-map{ display:none; }
.multiselect-container {
    width: 100%;
    font-size: 13px;
}

button.multiselect {
    font-size: 14px;
}

.multiselect-container>li {
    border-bottom: 1px dotted #e3e3e3;
}
</style>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Konsumen dan Toko</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('id' => 'formku','class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/tambah_konsumen',$attributes); 
          echo "<div class='col-md-6 col-xs-12'> 
                  <h4 style='background: #e3e3e3; padding: 8px 5px; border-bottom: 1px solid #c7c7c7; font-weight:700'>Data Konsumen</h4>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <tr><th width='130px' scope='row'>Username</th>       <td><input style='border-color:#ffabab' class='form-control' onkeyup=\"nospaces(this)\" type='text' name='aa' required></td></tr>
                  <tr><th scope='row'>Password</th>                     <td><input  style='border-color:#ffabab' class='form-control' onkeyup=\"nospaces(this)\" type='password' name='a' required></td></tr>
                  <tr><th scope='row'>Nama Lengkap</th>                 <td><input class='form-control' type='text' name='b' required></td></tr>
                  <tr><th scope='row'>Alamat Email</th>                 <td><input class='form-control' type='email' name='c' onkeyup=\"nospaces(this)\" required></td></tr>
                  <tr><th scope='row'>No Hp</th>                        <td><input class='form-control' type='number' name='k' onkeyup=\"nospaces(this)\" required></td></tr>
                  <tr><th scope='row'>Alamat Lengkap</th>               <td><textarea style='height:80px' class='form-control' name='alamat_lengkap' required></textarea></td></tr>
                  </tbody>
                  </table>
                </div>

                <div class='col-md-6 col-xs-12'> 
                <h4 style='background: #e3e3e3; padding: 8px 5px; border-bottom: 1px solid #c7c7c7; font-weight:700'>Data Toko / Lapak</h4>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='130px' scope='row'>Nama Toko</th>    <td><input class='form-control' type='text' name='nama_reseller'></td></tr>
                    <tr><th scope='row'>Daerah Pengiriman</th>          <td>
                    
                    <select class='form-control form-mini' name='provinsi_id' id='list_provinsi' required>";
                    echo "<option value=0>- Pilih Provinsi -</option>";
                      $provinsi = $this->db->query("SELECT * FROM tb_ro_provinces ORDER BY province_name ASC");
                      foreach ($provinsi->result_array() as $row) {
                        echo "<option value='$row[province_id]'>$row[province_name]</option>";
                      }
                    echo "</select>

                    <select class='form-control form-mini' name='kota_id' id='list_kotakab' required>
                    <option value=0>- Pilih Kota / Kabupaten -</option>
                    </select>

                    <select class='form-control form-mini' name='kecamatan_id' id='list_kecamatan' required>
                    <option value=0>- Pilih Kecamatan -</option>
                    </select>
                    
                    </td></tr>
                    <tr><th scope='row'>Kontak Toko</th>                <td><input class='form-control' type='text' name='no_telpon'></td></tr>
                    <tr><th scope='row'>Kurir Support</th>              <td><select class='form-mini' id='multiple_select' multiple='multiple'>";
                          $kurir_data = $this->model_app->view_ordering('rb_kurir','id_kurir','ASC');
                          foreach ($kurir_data as $rowk) {
                            echo "<option value='$rowk[id_kurir]' $print_selected>$rowk[nama_kurir]</option>";
                          }
                      echo "</select>
                      <input type='hidden' name='pilihan_kurir' value=''></td></tr>
                    <tr><th scope='row'>Kordinat Lokasi</th>            <td><input type='text' class='form-control form-mini btn-geolocationx' name='lokasi' id='lokasi' autocomplete='off' />
                    <label class='switch mr-1 mt-2'>
                        <input type='checkbox' name='alamat_lainx' id='alamat_lain'> Cari Kordinat dari Peta
                    </label>
                    
                    <div class='show-map'>
                        <div id='mapid' class='shadow-sm'></div>
                    </div>
              </td></tr>
                  </tbody>
                  </table>
                </div>

              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Tambah</button>
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
    [<?= config('kordinat'); ?>],
    15
  );
  L.tileLayer(
    "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw", {
      maxZoom: 18,
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      id: "mapbox/streets-v11",
      tileSize: 512,
      zoomOffset: -1,
    }
  ).addTo(mymap);

  L.marker([<?= config('kordinat'); ?>])
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

<script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#multiple_select').multiselect({
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: false,
                maxHeight: 300,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '99%',
                numberDisplayed: 6
            });

            $('#multiple_select2').multiselect({
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: false,
                maxHeight: 200,
                enableCaseInsensitiveFiltering: true
            });
        });
    </script>
    
