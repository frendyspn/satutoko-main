<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require APPPATH.'libraries/phpmailer/src/Exception.php';
require APPPATH.'libraries/phpmailer/src/PHPMailer.php';
require APPPATH.'libraries/phpmailer/src/SMTP.php';

function cek_session_members(){
    $ci = & get_instance();
    $session = $ci->session->userdata('level');
    if ($session != 'konsumen'){
        echo $ci->session->set_flashdata('message', '<div class="alert alert-danger"><center>Anda harus login untuk akses halaman tersebut!</center></div>');
        redirect('auth/login');
    }

    if (config('otp')=='aktif'){
        $session_otp = $ci->session->userdata('pin_used');
        if ($session_otp == ''){
            echo $ci->session->set_flashdata('message', '<div class="alert alert-danger"><center>Masukkan Kode verifikasi terlebih dahulu!</center></div>');
            if ($ci->session->pin_resend==''){
                redirect('members/otp?send');
            }else{
                redirect('members/otp');
            }
        }
    }
}


function premium($id_konsumen){
$ci = & get_instance();
$cek_paket = $ci->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='".$id_konsumen."' AND users='konsumen'");
  if ($cek_paket->num_rows()>=1){
    foreach ($cek_paket->result_array() as $rowp) {
      if ($rowp['status']=='Y'){
        $akhir  = strtotime($rowp['expire_date']); //Waktu awal
        $awal = time(); // Waktu sekarang atau akhir
        $diff  = $akhir - $awal;
        return "<span class='text-success fa fa-check-square'></span> $rowp[nama_paket]";
      }else{
        return "<a class='text-danger m-1 font-weight-bold' href='".base_url()."members/upgrade?p'><i class='fa fa-star text-yellow'></i> <span class='blink_me'>Upgrade Premium</span></a>";
      }
    }
  }else{
    return "<a class='text-danger m-1 font-weight-bold' href='".base_url()."members/upgrade?p'><i class='fa fa-star text-yellow'></i> <span class='blink_me'>Upgrade Premium</span></a>";
  }
}
                                  

function google_login(){
    $google_client = new Google_Client();
    $google_client->setClientId(config('google_client_id'));
    $google_client->setClientSecret(config('google_client_secret'));
    $google_client->setRedirectUri(config('redirect_uri').'google_login');
    $google_client->addScope('email');
    $google_client->addScope('profile');
    return $google_client->createAuthUrl();
}

function kosong($text){
    return strip_tags($text,"<p><b><i><u><a><h1><h2><h3><h4><h5><h6><img><iframe>");
}

function url_redirect(){
    return (isset($_SERVER['HTTPS']) ? "https://" : "http://") . "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function selisih_jam($tgl1,$tgl2){
    $akhir = strtotime($tgl2);
    $awal = strtotime($tgl1); // waktu sekarang
    $lama = $awal-$akhir;
    return floor($lama / (60 * 60));   
}

function selisih_waktu_run($tgl1,$tgl2){
    $akhir = str_replace('-','',$tgl2);
    $awal = str_replace('-','',$tgl1);
    $lama = $akhir-$awal;
    return $lama; 
}

function sensor_email($email){
    $p = strpos($email, '@');
    $email_sensor = substr_replace($email, str_repeat('*', $p-2), 1, $p-2);
    return $email_sensor; 
}

function format_telpon($no_telpon){
    $angka_awal = substr($no_telpon,0,1);
    if ($angka_awal==0){
        $telpon = "62".substr($no_telpon,1,15);
    }elseif ($angka_awal=='62'){
        $telpon = substr($no_telpon,0,15);
    }elseif ($angka_awal=='6'){
        $telpon = "62".substr($no_telpon,1,15);
    }elseif ($angka_awal!='0'){
        $telpon = "62".substr($no_telpon,0,15);
    }else{
        $telpon = substr($no_telpon,0,15);
    }
    return $telpon; 
}

function verifikasi($id_konsumen){
    $ci = & get_instance();
    if (config('mode')=='marketplace'){
        $res = $ci->db->query("SELECT id_reseller FROM rb_reseller where id_reseller='$id_konsumen' AND verifikasi='N'");
        if ($res->num_rows()>=1){
            echo $ci->session->set_flashdata('message', '<div class="alert alert-danger"><b>PENTING</b> - Toko anda belum ter-verifikasi, silahkan menunggu hingga diproses admin,..</div>');
            redirect("members/profil_toko");
        }
    }else{
        echo $ci->session->set_flashdata('message', '<div class="alert alert-danger"><b>GAGAL</b> - Saat ini System beralih ke mode E-Comerce...</div>');
        redirect("members/profile");
    }
}

function verifikasi_cek($id_reseller){
    $ci = & get_instance();
    $res = $ci->db->query("SELECT id_reseller FROM rb_reseller where id_reseller='$id_reseller' AND verifikasi='N'");
    return $res->num_rows();
}

function verifikasi_icon($id_reseller){
    $ci = & get_instance();
    $res = $ci->db->query("SELECT verifikasi FROM rb_reseller where id_reseller='$id_reseller'")->row_array();
    if ($res['verifikasi']=='Y'){ $verfikasi = 'Verified '; $color = 'green'; $icon = "check-square"; }else{ $verfikasi = 'Unverified'; $color = 'red'; $icon = "remove"; }
    $icon = "<small style='color:$color'><u><span class='fa fa-$icon'></span> $verfikasi</u></small>";
    return $icon;
}

function cek_session_reseller(){
    $ci = & get_instance();
    $session = $ci->session->userdata('level');
    if ($session != 'reseller'){
        redirect(base_url());
    }
}

function replace_url($data){
    return str_replace("https://","",$data);
}

function filter($str){
    $ci = & get_instance();
    return $ci->db->escape_str(strip_tags(htmlentities($str, ENT_QUOTES, 'UTF-8')));
}

function rupiah($total){
    return number_format(($total==''?'0':$total),0);
}

function terbilang($x){
    $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($x < 12)
    return " " . $abil[$x];
    elseif ($x < 20)
    return Terbilang($x - 10) . " Belas";
    elseif ($x < 100)
    return Terbilang($x / 10) . " Puluh" . Terbilang($x % 10);
    elseif ($x < 200)
    return " Seratus" . Terbilang($x - 100);
    elseif ($x < 1000)
    return Terbilang($x / 100) . " Ratus" . Terbilang($x % 100);
    elseif ($x < 2000)
    return " Seribu" . Terbilang($x - 1000);
    elseif ($x < 1000000)
    return Terbilang($x / 1000) . " Ribu" . Terbilang($x % 1000);
    elseif ($x < 1000000000)
    return Terbilang($x / 1000000) . " Juta" . Terbilang($x % 1000000);
}

function cetak($str){
    $ci = & get_instance();
    return $ci->db->escape_str(strip_tags(htmlentities($str, ENT_QUOTES, 'UTF-8')));
}

function cetak_meta($str,$mulai,$selesai){
    return strip_tags(html_entity_decode(substr(str_replace('"','',$str),$mulai,$selesai), ENT_COMPAT, 'UTF-8'));
}

function sensor($teks){
    $ci = & get_instance();
    $query = $ci->db->query("SELECT * FROM katajelek");
    foreach ($query->result_array() as $r) {
        $teks = str_replace($r['kata'], $r['ganti'], $teks);       
    }
    return $teks;
}  

function getSearchTermToBold($text, $words){
    preg_match_all('~[A-Za-z0-9_äöüÄÖÜ]+~', $words, $m);
    if (!$m)
        return $text;
    $re = '~(' . implode('|', $m[0]) . ')~i';
    return preg_replace($re, '<b style="color:red">$0</b>', $text);
}

function tgl_indo($tgl){
        $tanggal = substr($tgl,8,2);
        $bulan = getBulan(substr($tgl,5,2));
        $tahun = substr($tgl,0,4);
        return $tanggal.' '.$bulan.' '.$tahun;       
} 

function tgl_flashdeal($tgl){
    $tanggal = substr($tgl,8,2);
    $bulan = getBulan(substr($tgl,5,2));
    $tahun = substr($tgl,0,4);
    return $bulan.' '.$tanggal.', '.$tahun;       
} 

function jam_tgl_indo($tgl){
    $ex = explode(' ',$tgl);
    $tanggal = substr($ex[0],8,2);
    $bulan = getBulan(substr($ex[0],5,2));
    $tahun = substr($ex[0],0,4);
    return $tanggal.' '.$bulan.' '.$tahun.', '.$ex[1];       
} 

function jam_tgl_indo_day($tgl){
    $ex = explode(' ',$tgl);
    $tanggal = substr($ex[0],8,2);
    $bulan = getBulan(substr($ex[0],5,2));
    $tahun = substr($ex[0],0,4);
    $day = date('D', strtotime($ex[0]));
    $dayList = array(
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'Kamis',
        'Fri' => 'Jumat',
        'Sat' => 'Sabtu'
    );
    return $dayList[$day].', '.$tanggal.' '.$bulan.' '.$tahun.', '.$ex[1];       
} 

function jam_tgl_indox($tgl){
    $ex = explode(' ',$tgl);
    $tanggal = substr($ex[0],8,2);
    $bulan = (substr($ex[0],5,2));
    $tahun = substr($ex[0],0,4);
    return $tanggal.'-'.$bulan.'-'.$tahun.', '.$ex[1];       
} 

function tgl_simpan($tgl){
        $tanggal = substr($tgl,0,2);
        $bulan = substr($tgl,3,2);
        $tahun = substr($tgl,6,4);
        return $tahun.'-'.$bulan.'-'.$tanggal;       
}

function tgl_view($tgl){
        $tanggal = substr($tgl,8,2);
        $bulan = substr($tgl,5,2);
        $tahun = substr($tgl,0,4);
        return $tanggal.'-'.$bulan.'-'.$tahun;       
}

function tgl($tgl){
    $tanggal = substr($tgl,8,2);
    $bulan = substr($tgl,5,2);
    $tahun = substr($tgl,0,4);
    return $bulan.'/'.$tanggal.'/'.$tahun;       
}

function tgls($tgl){
    $tanggal = substr($tgl,3,2);
    $bulan = substr($tgl,0,2);
    $tahun = substr($tgl,6,4);
    return $tahun.'-'.$bulan.'-'.$tanggal;       
}

function tgl_grafik($tgl){
        $tanggal = substr($tgl,8,2);
        $bulan = getBulan(substr($tgl,5,2));
        $tahun = substr($tgl,0,4);
        return $tanggal.'_'.$bulan;       
}   

function generateRandomString($length = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
} 

function seo_title($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+','–');
    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
    $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $s;
}

function hari_ini($w){
    $seminggu = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
    $hari_ini = $seminggu[$w];
    return $hari_ini;
}

function getBulan($bln){
            switch ($bln){
                case 1: 
                    return "Jan";
                    break;
                case 2:
                    return "Feb";
                    break;
                case 3:
                    return "Mar";
                    break;
                case 4:
                    return "Apr";
                    break;
                case 5:
                    return "Mei";
                    break;
                case 6:
                    return "Jun";
                    break;
                case 7:
                    return "Jul";
                    break;
                case 8:
                    return "Agu";
                    break;
                case 9:
                    return "Sep";
                    break;
                case 10:
                    return "Okt";
                    break;
                case 11:
                    return "Nov";
                    break;
                case 12:
                    return "Des";
                    break;
            }
        } 

function bulan($bln){
    switch ($bln){
        case 1: 
            return "Januari";
            break;
        case 2:
            return "Februari";
            break;
        case 3:
            return "Maret";
            break;
        case 4:
            return "April";
            break;
        case 5:
            return "Mei";
            break;
        case 6:
            return "Juni";
            break;
        case 7:
            return "Juli";
            break;
        case 8:
            return "Agustus";
            break;
        case 9:
            return "September";
            break;
        case 10:
            return "Oktober";
            break;
        case 11:
            return "November";
            break;
        case 12:
            return "Desember";
            break;
    }
}

function cek_terakhir($datetime, $full = false) {
	 $today = time();    
     $createdday= strtotime($datetime); 
     $datediff = abs($today - $createdday);  
     $difftext="";  
     $years = floor($datediff / (365*60*60*24));  
     $months = floor(($datediff - $years * 365*60*60*24) / (30*60*60*24));  
     $days = floor(($datediff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));  
     $hours= floor($datediff/3600);  
     $minutes= floor($datediff/60);  
     $seconds= floor($datediff);  
     //year checker  
     if($difftext=="")  
     {  
       if($years>1)  
        $difftext=$years." Tahun";  
       elseif($years==1)  
        $difftext=$years." Tahun";  
     }  
     //month checker  
     if($difftext=="")  
     {  
        if($months>1)  
        $difftext=$months." Bulan";  
        elseif($months==1)  
        $difftext=$months." Bulan";  
     }  
     //month checker  
     if($difftext=="")  
     {  
        if($days>1)  
        $difftext=$days." Hari";  
        elseif($days==1)  
        $difftext=$days." Hari";  
     }  
     //hour checker  
     if($difftext=="")  
     {  
        if($hours>1)  
        $difftext=$hours." Jam";  
        elseif($hours==1)  
        $difftext=$hours." Jam";  
     }  
     //minutes checker  
     if($difftext=="")  
     {  
        if($minutes>1)  
        $difftext=$minutes." Menit";  
        elseif($minutes==1)  
        $difftext=$minutes." Menit";  
     }  
     //seconds checker  
     if($difftext=="")  
     {  
        if($seconds>1)  
        $difftext=$seconds." Detik";  
        elseif($seconds==1)  
        $difftext=$seconds." Detik";  
     }  
     return $difftext;  
    }

    function operasional($id_reseller){
        $ci = & get_instance();
        $seminggu = array("7","1","2","3","4","5","6");
        $hari_ini = $seminggu[date('w')];
        $cjs = $ci->db->query("SELECT * FROM `rb_reseller_operasional` where id_reseller='$id_reseller'");
        if ($cjs->num_rows()>'0'){
            $cj = $ci->db->query("SELECT * FROM `rb_reseller_operasional` where id_reseller='$id_reseller' AND hari='$hari_ini'")->row_array();
            if ($cj['jam_buka']!=''){
                $cek = $ci->db->query("SELECT * FROM `rb_reseller_operasional` where id_reseller='$id_reseller' AND hari='$hari_ini' AND '".date('H:i')."'>jam_buka AND '".date('H:i')."'<jam_tutup");
                if ($cek->num_rows()<='0'){
                    return "<div class='alert alert-success'><b>INFO OPERASIONAL</b> - Toko Sedang Libur dan ada Potensi Keterlambatan dalam pengiriman</div>";
                }else{
                    return '';
                }
            }else{
                return '';
            }
        }else{
            return '';
        }
    }

    function rating_produk($id_reseller){
        $ci = & get_instance();
        $hu = $ci->db->query("SELECT sum(rating)/count(*) as rating FROM `rb_produk_ulasan` a JOIN rb_produk b ON a.id_produk=b.id_produk where b.id_reseller='$id_reseller'")->row_array();
        if ($hu['rating']==''){
            return "-";
        }else{
            return "<span style='color:#ffc400' class='fa fa-star'></span> <span>".number_format($hu['rating'],1)."</span>";
        }
    }

    function rate_bintang($id_produk){
        $ci = & get_instance();
        $rates = $ci->db->query("SELECT sum(rating) as rating, count(*) as jumlah, sum(rating)/count(*) as total FROM rb_produk_ulasan where id_produk='$id_produk'")->row_array();
        $rates_cek = $ci->db->query("SELECT * FROM rb_produk_ulasan where id_produk='$id_produk'")->num_rows();
        $row = $ci->db->query("SELECT dilihat FROM rb_produk where id_produk='$id_produk'")->row_array();
        $jl = $ci->db->query("SELECT sum(jumlah) as total FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where b.status_penjual='reseller' AND b.proses>2 AND a.id_produk='$id_produk'")->row_array();
        if ($rates_cek<='0'){
            $rate .= "<option value='0'>$i</option>
                      <option value='2'>$i</option>
                      <option value='2'>$i</option>
                      <option value='2'>$i</option>
                      <option value='2'>$i</option>
                      <option value='2'>$i</option>";
        }else{
            for ($i=1; $i <= 5; $i++) { 
                if ($i<=number_format($rates['total'],0)){
                    $rate .= "<option value='1'>$i</option>";
                }else{
                    $rate .= "<option value='2'>$i</option>";
                }   
            }
        }
        if ($rates['total']==0){
            if (number_format($row['dilihat'],0)==0){
                return "<span style='color:#ffa617'>Produk Terbaru</span>";
            }else{
                return "<i style='color:#cecece'>Dilihat ".number_format($row['dilihat'],0)." kali</i>";
            }
        }else{
            return "<span style='color:#ffc400' class='fa fa-star'></span> <span style='color:#8a8a8a'>".number_format($rates['total'],1)." | Terjual ".(number_format($jl['total'],0)==0?1:number_format($jl['total'],0))."</span>";
        }
    }

    function rate_bintang_ulasan($bintang){
        $ci = & get_instance();
        if ($bintang<='0'){
            $rate .= "<option value='1'>$i</option>
                      <option value='2'>$i</option>
                      <option value='2'>$i</option>
                      <option value='2'>$i</option>
                      <option value='2'>$i</option>";
        }else{
            for ($i=1; $i <= 5; $i++) { 
                if ($i<=number_format($bintang,0)){
                    $rate .= "<option value='1'>$i</option>";
                }else{
                    $rate .= "<option value='2'>$i</option>";
                }   
            }


        }
        return $rate;
    }

    function reseller($id_konsumen){
        $ci = & get_instance();
        $res = $ci->db->query("SELECT id_reseller FROM rb_reseller where id_konsumen='$id_konsumen'")->row_array();
        if ($res['id_reseller']==''){
            return '';
        }else{
            return $res['id_reseller'];
        }
    }

    function status_akun($id_konsumen){
        $ci = & get_instance();
        $ver = $ci->db->query("SELECT * FROM rb_konsumen_verifikasi where id_konsumen='".$id_konsumen."'");
        if ($ver->num_rows()>=1){
            $verif = $ver->row_array();
            $status_akun = $verif['status_verifikasi'];
        }else{
            $status_akun = "Unverified";
        }
        return $status_akun;
    }

    function reseller_kota($id_reseller){
        $ci = & get_instance();
        $res = $ci->db->query("SELECT b.nama_kota FROM rb_reseller a JOIN rb_kota b ON a.kota_id=b.kota_id where a.id_reseller='$id_reseller'")->row_array();
        if ($res['nama_kota']==''){
            return "Not Found";
        }else{
            return $res['nama_kota'];
        }
    }

    // Nama Kecamatan
    // function reseller_kota($id_reseller){
    //     $ci = & get_instance();
    //     $res = $ci->db->query("SELECT b.subdistrict_name as nama_kota FROM rb_reseller a JOIN tb_ro_subdistricts b ON a.kecamatan_id=b.subdistrict_id where a.id_reseller='$id_reseller'")->row_array();
    //     if ($res['nama_kota']==''){
    //         return "Not Found";
    //     }else{
    //         return $res['nama_kota'];
    //     }
    // }

    function konsumen($id_reseller){
        $ci = & get_instance();
        $res = $ci->db->query("SELECT id_konsumen FROM rb_reseller where id_reseller='$id_reseller'")->row_array();
        return $res['id_konsumen'];
    }

    function config($field){
        $ci = & get_instance();
        $res = $ci->db->query("SELECT value FROM rb_config where field='$field'")->row_array();
        return $res['value'];
    }
    
    function bank($id){
        $ci = & get_instance();
        $res = $ci->db->query("SELECT nama_bank FROM rb_bank where id_bank='$id'")->row_array();
        return $res['nama_bank'];
    }

    function stok($id_reseller,$id_produk){
        $ci = & get_instance();
        if ($id_reseller>0){
            $jual = $ci->model_reseller->jual_reseller($id_reseller,$id_produk)->row_array();
            $beli = $ci->model_reseller->beli_reseller($id_reseller,$id_produk)->row_array();
        }else{
            $jual = $ci->model_reseller->jual($id_produk)->row_array();
            $beli = $ci->model_reseller->beli($id_produk)->row_array();
        }
        return $beli['beli']-$jual['jual'];
    }

    function user_reseller($id_reseller){
        $ci = & get_instance();
        $res = $ci->db->query("SELECT user_reseller FROM rb_reseller where id_reseller='$id_reseller'")->row_array();
        return $res['user_reseller'];
    }

function saldo($id_reseller,$id_konsumen){
    $ci = & get_instance();
    $cek_sopir = $ci->db->query("SELECT id_sopir FROM rb_sopir where id_konsumen='$id_konsumen'");

    // Saldo Jika Jadi Kurir
    if ($cek_sopir->num_rows()>=1){
        $sop = $cek_sopir->row_array();
        $sopir = $ci->db->query("SELECT sum(ongkir) as total FROM rb_penjualan a JOIN rb_penjualan_otomatis d ON a.kode_transaksi=d.kode_transaksi WHERE a.kurir='$sop[id_sopir]' AND a.proses='4' AND a.kode_kurir='1' AND d.pembayaran='1'")->row_array();
        $saldo_sopir = $sopir['total'];
    }else{
        $saldo_sopir = 0;
    }

    // Saldo Jika memiliki Toko/Lapak
    if ($id_reseller!=''){
        $penjualan_perusahaan = $ci->model_reseller->penjualan_perusahaan($id_reseller)->row_array();
        $kupon = $ci->db->query("SELECT sum(a.nilai) as diskon FROM `rb_penjualan_kupon` a JOIN rb_penjualan_detail b ON a.id_penjualan_detail=b.id_penjualan_detail
                                    JOIN rb_penjualan c ON b.id_penjualan=c.id_penjualan  JOIN rb_penjualan_otomatis d ON c.kode_transaksi=d.kode_transaksi where c.id_penjual='$id_reseller' AND c.status_penjual='reseller' AND c.proses='4' AND c.proses!='x' AND d.pembayaran='1'")->row_array();
        $ongkir = $ci->db->query("SELECT sum(z.ongkir) as ongkir FROM (SELECT sum(c.ongkir) as ongkir FROM rb_penjualan c JOIN rb_penjualan_otomatis d ON c.kode_transaksi=d.kode_transaksi where c.status_penjual='reseller' AND c.id_penjual='$id_reseller' AND c.kode_kurir!='0' AND c.kode_kurir!='1' AND c.proses>'3' AND c.proses!='x' AND d.pembayaran='1' GROUP BY c.kode_transaksi) as z")->row_array();
        $reseller = $ci->db->query("SELECT SUM(((c.jumlah*c.harga_jual)-(c.jumlah*c.diskon))-c.fee_produk_end) as reseller_order FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_pembeli=b.id_reseller 
                                        JOIN rb_penjualan_detail c ON a.id_penjualan=c.id_penjualan
                                            where a.status_penjual='admin' AND a.id_pembeli='$id_reseller' AND SUBSTRING(a.service,1,5)='TRX-R' AND a.proses='4'")->row_array();
        $saldo_pelapak = (($penjualan_perusahaan['total']-$kupon['diskon'])-$reseller['reseller_order'])+$ongkir['ongkir'];
    }else{
        $saldo_pelapak = 0;
    }

    // Saldo Konsumen
    $batal = $ci->db->query("SELECT (sum((a.harga_jual-a.diskon)*a.jumlah)-COALESCE(sum(c.fee/100*(((a.harga_jual-a.diskon)-b.harga_beli)*a.jumlah)),0))-sum(a.fee_produk_end*a.jumlah) as total, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a LEFT JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan JOIN rb_penjualan_otomatis d ON c.kode_transaksi=d.kode_transaksi where c.status_penjual='reseller' AND id_pembeli='$id_konsumen' AND (c.kode_kurir!='0' OR c.kode_kurir is NULL) AND c.proses='x' AND d.pembayaran='1'")->row_array();
    $tarik = $ci->db->query("SELECT sum(nominal) as total FROM rb_withdraw WHERE id_reseller='$id_konsumen' AND status!='Batal' AND transaksi='debit' AND akun='konsumen'")->row_array();
    $deposit = $ci->db->query("SELECT sum(nominal) as total FROM rb_withdraw WHERE id_reseller='$id_konsumen' AND status='Sukses' AND transaksi='kredit' AND akun='konsumen'")->row_array();
    $pulsa = $ci->db->query("SELECT sum(total) as total FROM rb_pembelian_pulsa WHERE id_reseller='$id_konsumen'")->row_array();
    $saldo_konsumen = (($deposit['total']-$tarik['total'])-$pulsa['total']);

    return (($saldo_pelapak+$saldo_konsumen)+$saldo_sopir);
}

    function simpan_rupiah($total){
        $total1 = str_replace(".","",$total);
        $total2 = str_replace(",","",$total1);
        return $total2;
    }

    function rate_jumlah($id_produk){
        $ci = & get_instance();
        $rate = $ci->db->query("SELECT sum(rating) as rating, count(*) as jumlah, sum(rating)/count(*) as total FROM rb_produk_ulasan where id_produk='$id_produk'")->row_array();
        return $rate['jumlah'];
    }

    function rate_total($id_produk){
        $ci = & get_instance();
        $rate = $ci->db->query("SELECT sum(rating) as rating, count(*) as jumlah, sum(rating)/count(*) as total FROM rb_produk_ulasan where id_produk='$id_produk'")->row_array();
        return number_format($rate['total']);
    }

    function clean_rupiah($total){
        $total1 = str_replace(".","",$total);
        $total2 = str_replace(",","",$total1);
        return $total2;
    }

    function alamat($kode_transaksi){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT id_pembeli, keterangan FROM rb_penjualan where kode_transaksi='$kode_transaksi' GROUP BY kode_transaksi")->row_array();
        if ($row['keterangan']!=''){
            $ex = explode('|',$row['keterangan']);
            return $ex[3].' ('.$ex[4].')<br>'.kecamatan($ex[2],$ex[1]);
        }else{
            $cek = $ci->db->query("SELECT * FROM rb_konsumen where id_konsumen='$row[id_pembeli]'")->row_array();
            return $cek['alamat_lengkap'].' '.($cek['kordinat_lokasi']==''?'':"$cek[kordinat_lokasi]").'<br>'.kecamatan($cek['kecamatan_id'],$cek['kota_id']);
        }
    }
    
    function alamat_nokor($kode_transaksi){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT id_pembeli, keterangan FROM rb_penjualan where kode_transaksi='$kode_transaksi' GROUP BY kode_transaksi")->row_array();
        if ($row['keterangan']!=''){
            $ex = explode('|',$row['keterangan']);
            return $ex[3].' ('.$ex[4].')<br>'.kecamatan($ex[2],$ex[1]);
        }else{
            $cek = $ci->db->query("SELECT * FROM rb_konsumen where id_konsumen='$row[id_pembeli]'")->row_array();
            return $cek['alamat_lengkap'].', '.kecamatan($cek['kecamatan_id'],$cek['kota_id']);
        }
    }

    function status($status){
        if ($status=='0'){ 
            $proses = '<span class="text">Pending</span>'; 
        }elseif($status=='1'){ 
            $proses = '<span class="text-danger">Proses</span>'; 
        }elseif($status=='2'){ 
            $proses = '<span class="text-info">Konfirmasi</span>'; 
        }elseif($status=='3'){ 
            $proses = '<span class="text-success">Dikirim</span>'; 
        }elseif($status=='4'){ 
            $proses = '<span class="text-success">Selesai</span>'; 
        }else{ 
            $proses = '<i>Batal</i>'; 
        }
        return $proses;
    }

    function status_pembayaran($status,$kode_transaksi){
        $ci = & get_instance();
        if ($status==2){
            $cek_payment = $ci->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='$kode_transaksi' AND pembayaran='1'");
            if ($cek_payment->num_rows()>=1){
                return "(<i>Menunggu Diproses...</i>)";
            }else{
                return "(<i>Proses Pengecekan...</i>)";
            }
        }
    }

    function cek_paket($id_reseller){
        $ci = & get_instance();
        return $ci->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='$id_reseller' AND status='Y'")->num_rows();
    }

    function cek_paket_bintang($id_reseller){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='$id_reseller' AND status='Y'")->row_array();
        return $row['id_paket'];
    }

    function cek_paket_icon($id_reseller){
        $ci = & get_instance();
        $rows = $ci->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='$id_reseller' AND status='Y'")->row_array();
        if ($rows['icon_kode']!=''){
            $icon = "<i style='font-size: 16px;' class='$rows[icon_kode] float-left'></i>";
        }elseif ($rows['icon_image']!=''){
            $icon = "<img style='width:18px; float:left' src='".base_url()."asset/foto_produk/$rows[icon_image]'>";
        }else{
            $icon = "<span class='fa fa-user' style='font-size:16px'></span>";
        }
        return $icon;
    }
    

    function berakhir($tanggal,$status){
        $akhir  = strtotime($tanggal); //Waktu awal
        $awal = time(); // Waktu sekarang atau akhir
        $diff  = $akhir - $awal;
        if ($status=='1'){
            return floor($diff / (60 * 60 * 24));
        }else{
            if (floor($diff / (60 * 60 * 24))<0){
                return "Promo ini telah berakhir!";
            }else{
                return "Berakhir ".floor($diff / (60 * 60 * 24)) ." hari lagi";
            }
        }
    }

    function cek_status_paket($id_reseller){
        $ci = & get_instance();
        $rowp = $ci->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='$id_reseller'")->row_array();
        if ($rowp['status']=='Y'){
            $akhir  = strtotime($rowp['expire_date']); //Waktu awal
            $awal = time(); // Waktu sekarang atau akhir
            $diff  = $akhir - $awal;
            return "<span style='color:green'>$rowp[nama_paket]</b>, Berakhir ".tgl_indo($rowp['expire_date'])." (".floor($diff / (60 * 60 * 24)) ." hari lagi)</span>";
        }elseif ($rowp['status']=='N'){
            return "<span style='color:red'>PENDING PAYMENT <b>$rowp[nama_paket]</b>, <b style='color:#000; text-decoration:underline'>Rp ".rupiah($rowp['harga']+$rowp['id_reseller_paket'])."</b></span>";
        }else{
            return "<span style='color:red'>Pelapak Free</span>";
        }
    }

    function cek_status_payment($id_reseller){
        $ci = & get_instance();
        $rowp = $ci->db->query("SELECT a.*, b.nama_paket, b.durasi FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='$id_reseller'")->row_array();
        if ($rowp['status']=='Y'){
            $akhir  = strtotime($rowp['expire_date']); //Waktu awal
            $awal = time(); // Waktu sekarang atau akhir
            $diff  = $akhir - $awal;
            return "<a class='btn btn-xs btn-danger' href='".base_url().$ci->uri->segment(1)."/reseller?paket=N&id=$rowp[id_reseller]' onclick=\"return confirm('Apa anda yakin untuk Non-Aktifkan Paket untuk Pelapak ini?')\"><span class='fa fa-remove'></span></a> <span style='color:green'>$rowp[nama_paket]</b>, <small>Exp. ".tgl_indo($rowp['expire_date'])." (".floor($diff / (60 * 60 * 24)) ." hari lagi)</small></span>";
        }elseif ($rowp['status']=='N'){
            return "<a class='btn btn-xs btn-success' href='".base_url().$ci->uri->segment(1)."/reseller?paket=Y&id=$rowp[id_reseller]&durasi=$rowp[durasi]' onclick=\"return confirm('Apa anda yakin untuk Aktifkan Paket untuk Pelapak ini?')\"><span class='fa fa-check'></span></a> <span>$rowp[nama_paket]</b> <small>(Pending Payment)</small></span>";
        }else{
            return "<a class='btn btn-xs btn-default' href='#'><span class='fa fa-minus-square'></span></a> <span style='color:#cecece'>Pelapak Free</span>";
        }
    }

    function cek_paket_all($id_reseller,$jenis){
        $ci = & get_instance();
        if($jenis == 'konsumen'){
            $now = date('Y-m-d');
            $rowp = $ci->db->query("SELECT a.*, b.nama_paket, b.durasi, b.lable_harga FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='$id_reseller' AND a.users='$jenis' AND a.status = 'Y' AND a.expire_date >= '$now'")->row_array();
            if(count($rowp) > 0){
                return $rowp['lable_harga'];
            } else {
                return 'harga_level_newbie';
            }
        } else {
            $rowp = $ci->db->query("SELECT a.*, b.nama_paket, b.durasi FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='$id_reseller' AND a.users='$jenis'")->row_array();    
            if ($rowp['status']=='Y'){
                return 1;
            }else{
                return 0;
            }
        }
        
        
    }

    function jenis_produk($id_penjualan){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT jenis_produk FROM rb_penjualan_detail a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='$id_penjualan' GROUP BY b.jenis_produk")->row_array();
        return $row['jenis_produk'];
    }
    
    function total_order($status,$id_konsumen){
        $ci = & get_instance();
        return $ci->db->query("SELECT * FROM rb_penjualan where id_pembeli='$id_konsumen' AND status_pembeli='konsumen' AND proses='$status'")->num_rows();
    }

    function order_masuk($id_konsumen){
        $ci = & get_instance();
        return $ci->db->query("SELECT * FROM rb_penjualan where id_penjual='$id_konsumen' AND status_penjual='reseller' AND proses!='x'")->num_rows();
    }

    function total_penjualan($status,$id_konsumen){
        $ci = & get_instance();
        return $ci->db->query("SELECT * FROM rb_penjualan where id_penjual='$id_konsumen' AND status_penjual='reseller' AND proses='$status'")->num_rows();
    }

    function total_penjualan_pending($status,$id_konsumen){
        $ci = & get_instance();
        return $ci->db->query("SELECT a.* FROM rb_penjualan a JOIN rb_penjualan_otomatis b ON a.kode_transaksi=b.kode_transaksi where a.id_penjual='$id_konsumen' AND a.status_penjual='reseller' AND b.pembayaran is null")->num_rows();
    }

    function kurir($kode_kurir,$kurir,$service){
        $ci = & get_instance();
        if ($kode_kurir=='1'){
        $ceks = $ci->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$kurir."'")->row_array();
            return "$service - $ceks[merek]";
        }elseif ($kode_kurir=='0'){
        $ceks = $ci->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$kurir."'")->row_array();
            return "COD - $service";
        }else{
            return "<span style='text-transform:uppercase'>$kurir</span> - $service";
        }
    }

    function ppob($value){
        switch ($value){
            case 'pulsa': 
                return "1";
                break;
            case 'token':
                return "19";
                break;
            case 'data':
                return "2";
                break;
            case 'game':
                return "11";
                break;
            case 'emoney':
                return "25";
                break;
        }
    }

    function kirim_email($subjek,$message,$tujuan){
        $ci = & get_instance();
        $data['subjek'] = $subjek;
        $data['message'] = $message;
        $message_send = $ci->load->view('email_template',$data,TRUE);
        
        $iden = $ci->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
        $ci->load->library('email');

        // PHPMailer object
        $response = false;
        $mail = new PHPMailer();
        
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host     = config('email_server'); //sesuaikan sesuai nama domain hosting/server yang digunakan
        $mail->SMTPAuth = true;
        $mail->Username = "$iden[email]"; // user email
        $mail->Password = "$iden[password]"; // password email
        $mail->SMTPSecure = config('smtp_secure');
        $mail->Port     = config('email_port');
        //$mail->SMTPDebug = 2;

        $mail->setFrom("$iden[email]", "$iden[pengirim_email]"); // user email
        $mail->addReplyTo("$iden[email]", "$iden[pengirim_email]"); //user email
        $mail->addAddress($tujuan); //email tujuan pengiriman email
        $mail->Subject = $subjek;
        $mail->isHTML(true);
        $mail->Body = $message_send;
        $mail->send();
        // if(!$mail->send()){
        //     echo "Message could not be sent.<br>
        //          ".config('email_server')." / ".config('smtp_secure')." / ".config('email_port')."<br>";
        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
        // }
    }

    function penjualan($id_reseller,$proses){
        $ci = & get_instance();
        $kupon = $ci->db->query("SELECT sum(a.nilai) as diskon FROM `rb_penjualan_kupon` a JOIN rb_penjualan_detail b ON a.id_penjualan_detail=b.id_penjualan_detail
        JOIN rb_penjualan c ON b.id_penjualan=c.id_penjualan where c.id_penjual='$id_reseller' AND c.status_penjual='reseller' AND c.proses='4'")->row_array();
        $row = $ci->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.status_penjual='reseller' AND id_penjual='$id_reseller' AND c.kode_kurir!='0' AND c.proses>'3'")->row_array();
        return $row['total']-$kupon['diskon'];
    }

    function modal($id_reseller,$proses){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT sum(b.harga_beli*a.jumlah) as modal, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.status_penjual='reseller' AND id_penjual='$id_reseller' AND c.kode_kurir!='0' AND c.proses>'3'")->row_array();
        return $row['modal'];
    }

    function penjualanx($id_reseller,$proses,$tgl1,$tgl2){
        $ci = & get_instance();
        $kupon = $ci->db->query("SELECT sum(a.nilai) as diskon FROM `rb_penjualan_kupon` a JOIN rb_penjualan_detail b ON a.id_penjualan_detail=b.id_penjualan_detail
        JOIN rb_penjualan c ON b.id_penjualan=c.id_penjualan where c.id_penjual='$id_reseller' AND c.status_penjual='reseller' AND c.proses='4' AND date(c.waktu_transaksi) BETWEEN '$tgl1' AND '$tgl2'")->row_array();
        $row = $ci->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.status_penjual='reseller' AND id_penjual='$id_reseller' AND c.kode_kurir!='0' AND c.proses>'3' AND date(c.waktu_transaksi) BETWEEN '$tgl1' AND '$tgl2'")->row_array();
        return $row['total']-$kupon['diskon'];
    }

    function modalx($id_reseller,$proses,$tgl1,$tgl2){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT sum(b.harga_beli*a.jumlah) as modal, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.status_penjual='reseller' AND id_penjual='$id_reseller' AND c.kode_kurir!='0' AND c.proses>'3' AND date(c.waktu_transaksi) BETWEEN '$tgl1' AND '$tgl2'")->row_array();
        return $row['modal'];
    }

    function kecamatan_kota($kecamatan,$kota){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT a.subdistrict_name, b.city_name, c.province_name FROM `tb_ro_subdistricts` a JOIN tb_ro_cities b ON a.city_id=b.city_id JOIN tb_ro_provinces c ON b.province_id=c.province_id where a.subdistrict_id='$kecamatan'")->row_array();
        /*$row = $ci->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=$kota",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: $row[api_rajaongkir]"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $obj = json_decode($response, true);
        for($i=0; $i < count($obj['rajaongkir']['results']); $i++){
            if ($obj['rajaongkir']['results'][$i]['subdistrict_id'] == $kecamatan){
                $select_kecamatan =  $obj['rajaongkir']['results'][$i]['subdistrict_name'].', '.$obj['rajaongkir']['results'][$i]['city'];
            }
        }*/
        return $row['subdistrict_name'].', '.$row['city_name'];
    }

    function kecamatanx($kecamatan,$kota){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT a.subdistrict_name, b.city_name, c.province_name FROM `tb_ro_subdistricts` a JOIN tb_ro_cities b ON a.city_id=b.city_id JOIN tb_ro_provinces c ON b.province_id=c.province_id where a.subdistrict_id='$kecamatan'")->row_array();
        return $row['city_name'].', '.$row['province_name'];
    }
    
    
    function kecamatan($kecamatan,$kota){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT a.subdistrict_name, b.city_name, c.province_name FROM `tb_ro_subdistricts` a JOIN tb_ro_cities b ON a.city_id=b.city_id JOIN tb_ro_provinces c ON b.province_id=c.province_id where a.subdistrict_id='$kecamatan'")->row_array();
        /*$row = $ci->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=$kota",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: $row[api_rajaongkir]"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $obj = json_decode($response, true);
        for($i=0; $i < count($obj['rajaongkir']['results']); $i++){
            if ($obj['rajaongkir']['results'][$i]['subdistrict_id'] == $kecamatan){
                $select_kecamatan =  $obj['rajaongkir']['results'][$i]['subdistrict_name'].', '.$obj['rajaongkir']['results'][$i]['city'].', '.$obj['rajaongkir']['results'][$i]['province'];
            }
        }*/
        return $row['subdistrict_name'].', '.$row['city_name'].', '.$row['province_name'];
    }
    
    function kecamatan_noprov($kecamatan,$kota){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT a.subdistrict_name, b.city_name, c.province_name FROM `tb_ro_subdistricts` a JOIN tb_ro_cities b ON a.city_id=b.city_id JOIN tb_ro_provinces c ON b.province_id=c.province_id where a.subdistrict_id='$kecamatan'")->row_array();
        return $row['subdistrict_name'].', '.$row['city_name'];
    }

    function get_provinsi(){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: $row[api_rajaongkir]"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
        }
        return json_decode($response, true);
    }

    function get_kota($id){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=$id",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: $row[api_rajaongkir]"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
        }
    
        return json_decode($response, true);
    }

    function get_kecamatan($id){
        $ci = & get_instance();
        $row = $ci->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=$id",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "key: $row[api_rajaongkir]"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          //echo $response;
        }
    
        return json_decode($response, true);
    }

    function cek_resi($no_resi,$kurir){
        $ci = & get_instance();
        if(config('api_resi_aktif')=='rajaongkir'){
            if ($kurir=='jne' OR $kurir=='tiki'){
                $url = "https://api.binderbyte.com/v1/track?api_key=".config('api_resi')."&courier=$kurir&awb=$no_resi";
                $response = file_get_contents($url);
            }else{
                $row = $ci->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "waybill=$no_resi&courier=$kurir",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: $row[api_rajaongkir]"
                ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            }
        }else{
            $url = "https://api.binderbyte.com/v1/track?api_key=".config('api_resi')."&courier=$kurir&awb=$no_resi";
            $response = file_get_contents($url);
        }
        return json_decode($response, true);
    }

    function main_menux($id_kategori_produk) {
        $ci = & get_instance();
        $query = $ci->db->query("SELECT icon_kode, icon_image, id_kategori_produk_sub as id_menu, nama_kategori_sub as nama_menu, kategori_seo_sub as link, id_parent FROM rb_kategori_produk_sub where id_kategori_produk='$id_kategori_produk' order by nama_kategori_sub");
        $menu = array('items' => array(),'parents' => array());
        foreach ($query->result() as $menus) {
            $menu['items'][$menus->id_menu] = $menus;
            $menu['parents'][$menus->id_parent][] = $menus->id_menu;
        }
        if ($menu) {
            $result = build_main_menux(0, $menu);
            return $result;
        }else{
            return FALSE;
        }
    }
    
    function build_main_menux($parent, $menu) {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            if ($parent=='0'){
                $html .= "<ul class='mega-menu__list'>";
            }else{
                $html .= "<div class='mega-menu'><div class='mega-menu__column'><ul class='mega-menu__list'>";
            }
            foreach ($menu['parents'][$parent] as $itemId) {

                if ($menu['items'][$itemId]->icon_kode!=''){
                    $icon = "<i class='".$menu['items'][$itemId]->icon_kode."'></i>";
                }elseif ($menu['items'][$itemId]->icon_image!=''){
                    $icon = "<img style='width:18px; height:18px; margin-right:10px' src='".base_url()."asset/foto_produk/".$menu['items'][$itemId]->icon_image."'>";
                }else{
                    $icon = "";
                }

                if (isset($menu['parents'][$itemId])) {
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<li class='current-menu-item menu-item-has-children has-mega-menu'><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'>$icon ".$menu['items'][$itemId]->nama_menu."</a><span class='sub-toggle'></span>";
                        $html .= build_main_menux($itemId, $menu);
                        $html .= "</li>";
                    }else{
                        $html .= "<li class='current-menu-item menu-item-has-children has-mega-menu'><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'><span>$icon ".$menu['items'][$itemId]->nama_menu."</span></a>";
                        $html .= build_main_menux($itemId, $menu);
                        $html .= "</li>";
                    }
                }else{
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<li class='current-menu-item'><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'>$icon ".$menu['items'][$itemId]->nama_menu."</a><span class='sub-toggle'></span></li>";
                    }else{
                        $html .= "<li class='current-menu-item'><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'>$icon ".$menu['items'][$itemId]->nama_menu."</a><span class='sub-toggle'></span></li>";
                    }
                }
            }
            if ($parent=='0'){
                $html .= "</ul>";
            }else{
                $html .= "</ul></div></div>";
            }
        }
        return $html;
    }


    function main_menuxx($id_kategori_produk) {
        $ci = & get_instance();
        $query = $ci->db->query("SELECT icon_kode, icon_image, id_kategori_produk_sub as id_menu, nama_kategori_sub as nama_menu, kategori_seo_sub as link, id_parent FROM rb_kategori_produk_sub where id_kategori_produk='$id_kategori_produk' order by id_kategori_produk_sub");
        $menu = array('items' => array(),'parents' => array());
        foreach ($query->result() as $menus) {
            $menu['items'][$menus->id_menu] = $menus;
            $menu['parents'][$menus->id_parent][] = $menus->id_menu;
        }
        if ($menu) {
            $result = build_main_menuxx(0, $menu);
            return $result;
        }else{
            return FALSE;
        }
    }
    
    function build_main_menuxx($parent, $menu) {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            $no = 1;
            $noo = 1;
            foreach ($menu['parents'][$parent] as $itemId) {
                if (isset($menu['parents'][$itemId])) {
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<option class='level-2' value='subkategori|".$menu['items'][$itemId]->id_menu."'>-- ".$menu['items'][$itemId]->nama_menu;
                        $html .= build_main_menuxx($itemId, $menu);
                        $html .= "</option>";
                        $noo++;
                    }else{
                        $html .= "<option class='level-1' value='subkategori|".$menu['items'][$itemId]->id_menu."'>- ".$menu['items'][$itemId]->nama_menu;
                        $html .= build_main_menuxx($itemId, $menu);
                        $html .= "</option>";
                        $no++;
                    }
                }else{
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<option class='level-2' value='subkategori|".$menu['items'][$itemId]->id_menu."'>-- ".$menu['items'][$itemId]->nama_menu."</option>";
                        $noo++;
                    }else{
                        $html .= "<option class='level-1' value='subkategori|".$menu['items'][$itemId]->id_menu."'>- ".$menu['items'][$itemId]->nama_menu."</option>";
                        $no++;
                    }
                }
                
            }
        }
        return $html;
    }

    function main_menuxxx($id_kategori_produk) {
        $ci = & get_instance();
        $query = $ci->db->query("SELECT icon_kode, icon_image, id_kategori_produk_sub as id_menu, nama_kategori_sub as nama_menu, kategori_seo_sub as link, id_parent FROM rb_kategori_produk_sub where id_kategori_produk='$id_kategori_produk' order by id_kategori_produk_sub");
        $menu = array('items' => array(),'parents' => array());
        foreach ($query->result() as $menus) {
            $menu['items'][$menus->id_menu] = $menus;
            $menu['parents'][$menus->id_parent][] = $menus->id_menu;
        }
        if ($menu) {
            $result = build_main_menuxxx(0, $menu);
            return $result;
        }else{
            return FALSE;
        }
    }
    
    function build_main_menuxxx($parent, $menu) {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            foreach ($menu['parents'][$parent] as $itemId) {
                if (isset($menu['parents'][$itemId])) {
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<li class='current-menu-item '><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'>-- ".$menu['items'][$itemId]->nama_menu."</a>";
                        $html .= build_main_menuxxx($itemId, $menu);
                        $html .= "</li>";
                    }else{
                        $html .= "<li class='current-menu-item '><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'>- ".$menu['items'][$itemId]->nama_menu."</a>";
                        $html .= build_main_menuxxx($itemId, $menu);
                        $html .= "</li>";
                    }
                }else{
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<li class='current-menu-item'><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'>-- ".$menu['items'][$itemId]->nama_menu."</a></li>";
                    }else{
                        $html .= "<li class='current-menu-item'><a href='".base_url().'produk/subkategori/'.$menu['items'][$itemId]->link."'>- ".$menu['items'][$itemId]->nama_menu."</a></li>";
                    }
                }
            }
        }
        return $html;
    }

    function main_menuxxxx($id_kategori_produk) {
        $ci = & get_instance();
        $query = $ci->db->query("SELECT icon_kode, icon_image, id_kategori_produk_sub as id_menu, nama_kategori_sub as nama_menu, kategori_seo_sub as link, id_parent FROM rb_kategori_produk_sub where id_kategori_produk='$id_kategori_produk' order by id_kategori_produk_sub");
        $menu = array('items' => array(),'parents' => array());
        foreach ($query->result() as $menus) {
            $menu['items'][$menus->id_menu] = $menus;
            $menu['parents'][$menus->id_parent][] = $menus->id_menu;
        }
        if ($menu) {
            $result = build_main_menuxxxx(0, $menu);
            return $result;
        }else{
            return FALSE;
        }
    }
    
    function build_main_menuxxxx($parent, $menu) {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            $no = 1;
            $noo = 1;
            foreach ($menu['parents'][$parent] as $itemId) {
                if (isset($menu['parents'][$itemId])) {
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<option class='level-2' value='".$menu['items'][$itemId]->id_menu."'>-- ".$menu['items'][$itemId]->nama_menu;
                        $html .= build_main_menuxxxx($itemId, $menu);
                        $html .= "</option>";
                        $noo++;
                    }else{
                        $html .= "<option class='level-1' value='".$menu['items'][$itemId]->id_menu."'>- ".$menu['items'][$itemId]->nama_menu;
                        $html .= build_main_menuxxxx($itemId, $menu);
                        $html .= "</option>";
                        $no++;
                    }
                }else{
                    if ($menu['items'][$itemId]->id_parent!='0'){
                        $html .= "<option class='level-2' value='".$menu['items'][$itemId]->id_menu."'>-- ".$menu['items'][$itemId]->nama_menu."</option>";
                        $noo++;
                    }else{
                        $html .= "<option class='level-1' value='".$menu['items'][$itemId]->id_menu."'>- ".$menu['items'][$itemId]->nama_menu."</option>";
                        $no++;
                    }
                }
                
            }
        }
        return $html;
    }

    function rad($x){
        return $x * M_PI / 180;
    }
    
    function distHaversine($coord_a, $coord_b){
        # jarak kilometer dimensi (mean radius) bumi
        $R = 8071;
        $coord_a = explode(",", $coord_a);
        $coord_b = explode(",", $coord_b);
        $dLat = rad(($coord_b[0]) - ($coord_a[0]));
        $dLong = rad($coord_b[1] - $coord_a[1]);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(rad($coord_a[0])) * cos(rad($coord_b[0])) * sin($dLong / 2) * sin($dLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        # hasil akhir dalam satuan kilometer
        return number_format($d, 0, '.', '');
    }

    function autolink($text){
    $pattern = '/(((http[s]?:\/\/(.+(:.+)?@)?)|(www\.))[a-z0-9](([-a-z0-9]+\.)*\.[a-z]{2,})?\/?[a-z0-9.,_\/~#&=:;%+!?$-]+)/is';
      $text = preg_replace($pattern, ' <a target="_BLANK" href="$1">$1</a>', $text);
      // fix URLs without protocols
      $text = preg_replace('/href="www/', 'href="http://www', $text);
      return $text;
    }

    function tripay($payload,$url){
        $ci = & get_instance();
        $rows = $ci->db->query("SELECT maps FROM identitas where id_identitas='1'")->row_array();
        if ($rows['maps']!='|'){
            $maps = explode('|',$rows['maps']);
            $url1 = $url.'?'.http_build_query($payload);
            $header = array(
            'Accept: application/json',
            'Authorization: Bearer '.$maps[0], // Ganti [apikey] dengan API KEY Anda
            );
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $url1);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch1, CURLOPT_HTTPHEADER, $header);
            $data = curl_exec($ch1);
            if ($data!=''){
                if(curl_errno($ch1)){
                    return 'Request Error:' . curl_error($ch1);
                }
                return json_decode($data);
            }else{
                return "Data tidak ada";
            }
        }
    }

    function tripay_pascabayar($payload,$url,$method){
        $ci = & get_instance();
        $rows = $ci->db->query("SELECT maps FROM identitas where id_identitas='1'")->row_array();
        if ($rows['maps']!='|'){
            $maps = explode('|',$rows['maps']);
            $url1 = $url.'?'.http_build_query($payload);
            $header = array(
            'Accept: application/json',
            'Authorization: Bearer '.$maps[0], // Ganti [apikey] dengan API KEY Anda
            );
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch1, CURLOPT_URL, $url1);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
            
            if ($method=='POST'){
                curl_setopt($ch1, CURLOPT_HEADER, false);
                curl_setopt($ch1, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch1, CURLOPT_POST, 1);
                curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($payload));
            }else{
                curl_setopt($ch1, CURLOPT_HTTPHEADER, $header);
            }
            
            $data = curl_exec($ch1);
            $err      = curl_error($ch1);
            
            if ($data!=''){
                if(curl_errno($ch1)){
                    return 'Request Error:' . curl_error($ch1);
                }
                return json_decode($data);
                //return !empty($err) ? $err : $data;
            }else{
                return "Data tidak ada";
            }
        }
    }
    
    
function getHargaJual($id_produk, $type='', $diskon = 'NO'){
    $diskon_value = 0;
    $ci = & get_instance();
    $dataProduk = $ci->db->query("select * from rb_produk where id_produk = '".$id_produk."'")->result_array();
    $disk = $ci->db->query("SELECT * FROM rb_produk_diskon where id_produk='".$id_produk."'")->row_array();
    if($diskon == 'YES'){
        $diskon_value = $disk['diskon'];
    }
    
    $id_konsumen = $ci->session->userdata('id_konsumen');
    
    if($id_konsumen == ''){
        $formatNominal = number_format(((int)$dataProduk[0]['harga_konsumen']-$diskon_value), 0, ',', '.'); // Format nominal sesuai dengan digit 'x'

        // Mengganti '0' menjadi 'x' di awal string (kecuali digit pertama)
        $harga = "Rp. ".substr(($dataProduk[0]['harga_konsumen']-$diskon_value), 0, 1).preg_replace('/\d/', 'x', substr($formatNominal, 1));
        // if($type == 'nonformat'){
        //     $harga = 0;
        // }
    } else {
        $cek_paket = $ci->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='".$id_konsumen."' AND users='konsumen' AND status != 'N' order by a.expire_date");
        
        if ($cek_paket->num_rows()>=1){
            $get_paket = $cek_paket->result_array();
            $harga = $dataProduk[0][$get_paket[0]['lable_harga']]-$diskon_value;
            
            // if($get_paket[0]['id_paket'] == 6){
            //     $harga = $dataProduk[0]['harga_level_newbie']-$diskon_value;
            // } else if($get_paket[0]['id_paket'] == 7){
            //     $harga = $dataProduk[0]['harga_level_pedagang']-$diskon_value;
            // } else if($get_paket[0]['id_paket'] == 8){
            //     $harga = $dataProduk[0]['harga_level_juragan']-$diskon_value;
            // } else if($get_paket[0]['id_paket'] == 9){
            //     $harga = $dataProduk[0]['harga_level_big']-$diskon_value;
            // } else if($get_paket[0]['id_paket'] == 10){
            //     $harga = $dataProduk[0]['harga_level_bos']-$diskon_value;
            // }
            
            if($harga <= 0){
                // $harga = $dataProduk[0]['harga_konsumen']-$diskon_value;
                $harga = $dataProduk[0]['harga_level_newbie']-$diskon_value;
            }
        } else {
            // $harga = $dataProduk[0]['harga_konsumen']-$diskon_value;
            $harga = $dataProduk[0]['harga_level_newbie']-$diskon_value;
        }
        
        if($type == ''){
            $harga = "Rp. ".number_format($harga);    
        } else if($type == 'nonformat'){
            $harga = $harga;
        } else {
            $harga = $harga;
        }
        
        
    }
    return $harga;

}


function getHargaJualCek($value, $type=''){
    return ($value);
    if(($value*1) > 0){
        return $value;
        $id_konsumen = $ci->session->userdata('id_konsumen');
    
        if($id_konsumen == ''){
            if($type == ''){
                $value = number_format(((int)$value), 0, ',', '.'); // Format nominal sesuai dengan digit 'x'
                $harga = "Rp. ".substr(($formatNominal), 0, 1).preg_replace('/\d/', 'x', substr($formatNominal, 1));
            } else {
                // Mengganti '0' menjadi 'x' di awal string (kecuali digit pertama)
                $harga = substr(($value), 0, 1).preg_replace('/\d/', 'x', substr($formatNominal, 1));
            }
            
        } else {
            $harga = $value;
        }
        return $harga;
    } else {
        return 0;
    }
    
    
}



function textRekomenJual(){
    return "Rekomendasi Jual ";
}