<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://monim67.github.io/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li><?php echo $title; ?></li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
  <div class="container">
    <div class="ps-section__content"><br>
      <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
          <?php
            include "sidebar-reseller.php";
          ?>
        </div>

        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
            <figure class="ps-block--vendor-status operasional">
            <?php 
            echo $this->session->flashdata('message'); 
            $this->session->unset_userdata('message');
              echo "<p style='font-size:17px'>Hai <b>$row[nama_lengkap]</b>, Di sini kamu bisa mengatur tokomu secara keseluruhan beroperasi rutin setiap minggunya, agar tokomu bisa memberikan layanan terbaik ke pembeli. </p><br>
              <form action='".base_Url()."members/operasional' method='POST'>
              <div class='form-group row' style='margin-bottom:5px'>";
              $hari = array("Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
              for ($i=1; $i<=7 ; $i++) { 
                $cek_operasional = $this->db->query("SELECT * FROM rb_reseller_operasional where id_reseller='".reseller($row['id_konsumen'])."' AND hari='$i'");
                $co = $cek_operasional->row_array();
                echo "<label class='col-sm-3 col-2 col-form-label' style='margin-bottom:1px'>".$hari[$i-1]."</b> </label>
                        <div class='col-sm-9 col-10'> 
                        
                        
                            <div class='input-group date id_3' style='display:inline'>
                                <input style='border:1px solid #cecece; padding:5px 10px; width: 40%;' type='text'  value='".($co['jam_buka']!=''?$co['jam_buka']:'')."' name='b$i'/>
                            </div>
                             - 
                            <div class='input-group date id_3' style='display:inline'>
                                <input style='border:1px solid #cecece; padding:5px 10px; width: 40%;' type='text'  value='".($co['jam_tutup']!=''?$co['jam_tutup']:'')."' name='t$i'/>
                            </div>
                    
                        </div>";
              }
              echo "</div>
              <div style='clear:both; padding:10px'></div>
              <button type='submit' name='submit' style='padding:9px 30px' class='ps-btn'>Update</button>
              <a href='".base_url()."members/profil_toko' style='padding:9px 30px' class='ps-btn ps-btn--outline'>Batal</a>
              </form>";
            ?>
            <div style='clear:both; padding:20px'></div>
            <h4>Keuntungan menggunakan fitur Jadwal Toko</h4>
            <ul>
                <li>Pembeli dapat mengetahui kapan Chat mereka dibalas dan pesanan mereka akan diproses dan dikirim, sehingga mereka gak perlu harap-harap cemas mengenai orderan.</li>
                <li>Kamu dapat mengelola pesanan lebih efisien berdasarkan jam operasional toko yang telah diatur dengan fitur Jadwal Toko.</li>
                <li>Kamu tidak akan mendapatkan pengurangan Skor Performa Toko apabila ada chat yang masuk di luar jadwal operasional tokomu.</li>
                <li>Kamu tidak perlu terpaksa menolak pesanan yang masuk ketika toko sedang libur.</li>
            </ul>
            </figure>
          </div>
        </div>
      </div>
    </div>
</div>

<script>
(function($){
    $(function(){
        $('#id_0').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY hh:mm:ss A",
        });
        $('.id_1').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY HH:mm:ss",
        });
        $('#id_2').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "hh:mm:ss A",
        });
        $('.id_3').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "HH:mm:ss",
        });
        $('#id_4').datetimepicker({
            "allowInputToggle": true,
            "showClose": true,
            "showClear": true,
            "showTodayButton": true,
            "format": "MM/DD/YYYY",
        });
    });
})(jQuery);
</script>