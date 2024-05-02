<style>
@media (max-width: 998px){
    #formku .img-thumbnail{
        width: 50px !important;
        float: left;
    }
    #formku .numberx{
        width: 80%;
        border: 0px;
    }
    #formku small center{
        text-align: left;
        display: inline;
        margin-left: 10px;
    }
}
</style>

<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li>Profile</li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
            <?php include "menu-members.php"; ?>
            
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                  <?php
                    $attributes = array('id' => 'formku');
                    echo form_open_multipart('members/edit_profile'.($_GET['error']!=''?'?redirect=produk/keranjang':''),$attributes); 
                    if (trim($row['foto'])=='' OR !file_exists("asset/foto_user/".$row['foto'])){
                      echo "<img class='img-thumbnail' style='width:100%' src='".base_url()."asset/foto_user/blank.png'>";
                      $fotoku = base_url()."asset/foto_user/blank.png";
                    }else{
                      echo "<img class='img-thumbnail' style='width:100%' src='".base_url()."asset/foto_user/$row[foto]'>";
                      $fotoku = base_url()."asset/foto_user/$row[foto]";
                    }
                    echo "<input class='required numberx form-control form-mini' type='file' name='foto'><small><center>Allowed : gif, jpg, png, jpeg (Max 1 MB)</center></small><br>";
                  ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                <?php 
                    echo $this->session->flashdata('message'); 
                    $this->session->unset_userdata('message');
                ?>
                    <div class="ps-block--vendor-status biodata">
                        <?php 
                            echo "<p class='d-none d-sm-block' style='font-size:17px'>Hai <b>$row[nama_lengkap]</b>, selamat datang di halaman Biodata diri! <br>
                                                            Pastikan data profil sesuai dengan KTP untuk kemudahan dalam bertransaksi.</p><br>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Username</b></label>
                              <div class='col-sm-9'>
                              <input type='text' name='aa' class='form-control form-mini' value='$row[username]' autocomplete='off' onkeyup=\"nospaces(this)\">
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Password</b></label>
                              <div class='col-sm-9'>
                              <input type='text' name='a' class='form-control form-mini' placeholder='**************' onkeyup=\"nospaces(this)\">
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Nama Lengkap</b></label>
                              <div class='col-sm-9'>
                              <input type='text' name='b' class='form-control form-mini' value='$row[nama_lengkap]' autocomplete='off' required>
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Jenis Kelamin</b></label>
                              <div class='col-sm-9'>"; 
                              if ($row['jenis_kelamin']=='Laki-laki'){ echo "<input type='radio' value='Laki-laki' name='d' checked> Laki-laki <input type='radio' value='Perempuan' name='d'> Perempuan "; }else{ echo "<input type='radio' value='Laki-laki' name='d'> Laki-laki <input type='radio' value='Perempuan' name='d' checked> Perempuan "; } 
                              echo "</div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Email</b></label>
                              <div class='col-sm-9'>
                              <input type='text' name='c' class='form-control form-mini' value='$row[email]' onkeyup=\"nospaces(this)\" autocomplete='off' required>
                              </div>
                            </div>
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>No Hp</b></label>
                            <div class='col-sm-9'>
                              <input type='text' name='l' class='form-control form-mini' value='$row[no_hp]' onkeyup=\"nospaces(this)\" autocomplete='off' required>
                            </div>
                            </div>
                            
                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Tempat/Tanggal Lahir</b></label>
                              <div class='col-sm-9'>
                              <div class='row'>
                                <div class='col'>
                                <input type='text' name='f' class='form-control form-mini' value='".($row['tempat_lahir'] == '' ? '-' : $row['tempat_lahir'])."' autocomplete='off'>
                                </div>
                                <div class='col'>
                                <input type='text' name='e' class='form-control form-mini ps-datepicker' value='".($row['tanggal_lahir'] == '0000-00-00' ? date('m/d/Y') : tgl($row['tanggal_lahir']))."' autocomplete='off'>
                                </div>
                              </div>
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Alamat Pengiriman</b></label>
                              <div class='col-sm-9'>
                              <input type='text' name='g' class='form-control form-mini' value='$row[alamat_lengkap]' autocomplete='off' required>
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Daerah Pengiriman</b></label>
                            <div class='col-sm-9'>
                              <div class='row'>
                                <div class='col'>
                                  <select class='form-control form-mini' name='provinsi_id' id='list_provinsi' required>";
                                  echo "<option value=0>- Pilih Provinsi -</option>";
                                    $provinsi = $this->db->query("SELECT * FROM tb_ro_provinces ORDER BY province_name ASC");
                                    foreach ($provinsi->result_array() as $rows) {
                                      if ($row['provinsi_id']==$rows['province_id']){
                                        echo "<option value='$rows[province_id]' selected>$rows[province_name]</option>";
                                      }else{
                                        echo "<option value='$rows[province_id]'>$rows[province_name]</option>";
                                      }
                                    }
                                  echo "</select>
                                </div>
                                <div class='col'>
                                  <select class='form-control form-mini' name='kota_id' id='list_kotakab' required>";
                                  echo "<option value=0>- Pilih Kota / Kabupaten -</option>";
                                  $kota = $this->db->query("SELECT * FROM tb_ro_cities where province_id='$row[provinsi_id]' ORDER BY city_name ASC");
                                    foreach ($kota->result_array() as $rows) {
                                      if ($row['kota_id']==$rows['city_id']){
                                        echo "<option value='$rows[city_id]' selected>$rows[city_name]</option>";
                                      }else{
                                        echo "<option value='$rows[city_id]'>$rows[city_name]</option>";
                                      }
                                    }
                                  echo "</select>
                                </div>
                                <div class='col'>
                                  <select class='form-control form-mini' name='kecamatan_id' id='list_kecamatan' required>";
                                  echo "<option value=0>- Pilih Kecamatan -</option>";
                                    $subdistrict = $this->db->query("SELECT * FROM tb_ro_subdistricts where city_id='$row[kota_id]' ORDER BY subdistrict_name ASC");
                                    foreach ($subdistrict->result_array() as $rows) {
                                      if ($row['kecamatan_id']==$rows['subdistrict_id']){
                                        echo "<option value='$rows[subdistrict_id]' selected>$rows[subdistrict_name]</option>";
                                      }else{
                                        echo "<option value='$rows[subdistrict_id]'>$rows[subdistrict_name]</option>";
                                      }
                                    }
                                  echo "</select>
                                </div>
                              </div>
                            </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Kordinat Lokasi</b></label>
                              <div class='col-sm-9'>
                                <input type='text' class='form-control form-mini btn-geolocationx' value='$row[kordinat_lokasi]' name='lokasi' id='lokasi' autocomplete='off' />
                                <label class='switch mr-1 mt-2'>
                                    <input type='checkbox' name='alamat_lainx' id='alamat_lain'> Cari Kordinat dari Peta
                                </label>
                              </div>
                            </div>
                            
                            <div class='show-map'>
                                <div id='mapid' class='shadow-sm'></div>
                            </div>
                            
                            <button type='submit' name='submit' class='mt-3 ps-btn spinnerButton'><center><i class='icon-pen'></i> Simpan Perubahan</center></button>
                          </div>";
                          echo form_close();
                        ?>
                    </figure>
                </div>
              
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@v0.74.0/dist/L.Control.Locate.min.css" />

<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@v0.74.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
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
    [<?php echo ($row['kordinat_lokasi']==''?config('kordinat'):$row['kordinat_lokasi']); ?>],
    15
  );

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mymap);

L.control.locate().addTo(mymap);
L.Control.geocoder().addTo(mymap);

var warehouse = L.icon({
  iconUrl: '<?= $fotoku; ?>',
  iconSize: [40, 40]
});

var delivery = L.icon({
  iconUrl: '<?= base_url(); ?>asset/images/delivery.png',
  iconSize: [40, 40]
});



  L.marker([<?php echo ($row['kordinat_lokasi']==''?config('kordinat'):$row['kordinat_lokasi']); ?>], { icon: warehouse })
    .addTo(mymap)
    //.bindPopup("Silahkan klik map untuk mendapatkan koordinat.")
    .openPopup();

  var popup = L.popup();

  mymap.on('click', function (e) {
			var newMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(mymap);
			var routingControl = L.Routing.control({
				waypoints: [
					L.latLng(<?php echo ($row['kordinat_lokasi']==''?config('kordinat'):$row['kordinat_lokasi']); ?>),
					L.latLng(e.latlng.lat, e.latlng.lng)
				],
        routeWhileDragging: true,
        geocoder: L.Control.Geocoder.nominatim()
			}).on('routesfound', function (e) {
				var routes = e.routes;
				console.log(routes);
				e.routes[0].coordinates.forEach(function (coord, index) {
					setTimeout(function () {
						newMarker.setLatLng([coord.lat, coord.lng]);
					}, 100 * index)
				})
			}).addTo(mymap);
	});

mymap.on('zoomend', function(e) {
  refreshDistanceAndLength();
});

function refreshDistanceAndLength() {
  _distance = L.GeometryUtil.distance(mymap, _firstLatLng, _secondLatLng);
  _length = L.GeometryUtil.length([_firstPoint, _secondPoint]);
  document.getElementById('distance').innerHTML = _distance;
  document.getElementById('length').innerHTML = _length;
};

  function onMapClick(e) {
    popup
      .setLatLng(e.latlng)
      .setContent(
        e.latlng.lat + ", " + e.latlng.lng
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
