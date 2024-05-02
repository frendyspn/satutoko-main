
<?php 
    $wp = $this->db->query("SELECT a.waktu_proses FROM `rb_penjualan_otomatis` a JOIN rb_penjualan b ON a.kode_transaksi=b.kode_transaksi where a.nominal='$unik[nominal]' AND b.proses='0' AND SUBSTR(timediff(now(), a.waktu_proses),1,2)<'24' GROUP BY b.kode_transaksi")->row_array();
    $date1 = str_replace('-', '/', $wp['waktu_proses']);
    $tomorrow = date('Y-m-d H:i:s',strtotime($date1 . "+1 days"));
    $ss = $this->db->query("SELECT a.*, b.proses, timediff('$tomorrow', now()) as sisa_waktu, SUBSTR(timediff(now(), a.waktu_proses),1,2) as durasi FROM `rb_penjualan_otomatis` a JOIN rb_penjualan b ON a.kode_transaksi=b.kode_transaksi where a.nominal='$unik[nominal]' AND b.proses='0' AND SUBSTR(timediff(now(), a.waktu_proses),1,2)<'24' GROUP BY b.kode_transaksi")->row_array();
?>
<script type="text/javascript">
     $(document).ready(function() {
         var detik   = <?php echo substr($ss['sisa_waktu'],6,2); ?>;
         var menit   = <?php echo substr($ss['sisa_waktu'],3,2); ?>;
         var jam     = <?php echo substr($ss['sisa_waktu'],0,2); ?>;
         var hari    = 0;
         var bulan   = 0;

         function padDigits(number, digits) {
            return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
        }

         function hitung() {
             /** setTimout(hitung, 1000) digunakan untuk 
                 * mengulang atau merefresh halaman selama 1000 (1 detik) 
             */
             setTimeout(hitung,1000);

             /** Jika waktu kurang dari 10 menit maka Timer akan berubah menjadi warna merah */
             if(menit < 10 && jam == 0 && hari == 0 && bulan == 0){
                  var peringatan = 'style="color:red;"';
             };

             /** Menampilkan Waktu Timer pada Tag #Timer di HTML yang tersedia */
             $('#timer').html(
                 '<div align="center">Selesaikan Pembayaran dalam <br /><b style="color:red">' + padDigits(jam,2) + ':' + padDigits(menit,2) + ':' + padDigits(detik,2) + '</b><br>'
             );

             /** Melakukan Hitung Mundur dengan Mengurangi variabel detik - 1 */
             detik --;

             /** Jika var detik < 0
                 * var detik akan dikembalikan ke 59
                 * Menit akan Berkurang 1
             */
             if(detik < 0) {
                 detik = 59;
                 menit --;

                 /** Jika menit < 0
                     * Maka menit akan dikembali ke 59
                     * Jam akan Berkurang 1
                 */
                 if(menit < 0) {
                     menit = 59;
                     jam --;
                      
                     /** Jika jam < 0
                         * Maka jam akan dikembali ke 23
                         * Jam akan Berkurang 1
                     */
                     if(jam < 0) {
                         jam = 23;
                         hari --;
                          
                         /** Jika hari < 0
                             * Maka hari akan dikembali ke 29
                             * Jam akan Berkurang 1
                         */                             
                         if(hari < 0) {
                             hari = 29
                             bulan --;
                              
                             /** Jika var bulan < 0
                                 * clearInterval() Memberhentikan Interval dan submit secara otomatis
                             */
                             if(bulan < 0){
                                 clearInterval(); 
                                 /** Variable yang digunakan untuk submit secara otomatis di Form */
                                 var sub = document.getElementById("sub"); 
                                 alert('Waktu Pembayaran Otomatis telah habis, Silahkan konfirmasi manual jika sudah transfer...');
                             }
                         }
                     }
                 } 
             } 
         }           
         /** Menjalankan Function Hitung Waktu Mundur */
         hitung();
   }); 
   // ]]>
 </script>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li><a href="<?php echo base_url()."konfirmasi/tracking"; ?>">Tracking</a></li>
            <li><?php echo $judul; ?></li>
        </ul>
    </div>
</div>
<?php 
$cp = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='$judul' AND nominal='$unik[nominal]'")->row_array();
if ($cp['status_trx']=='tripay' AND $cp['pembayaran']!='1'){
    $kolom1 = 6;
    $kolom2 = '';
    $sidebar = 'd-none';
    $style = 'margin:0 auto; border:radius:10px; border:1px solid #e3e3e3; padding:30px; box-shadow: 0px 0px 2px 1px #e3e3e3;';
}else{
    $kolom1 = 8;
    $kolom2 = 4;
    $sidebar = '';
    $style='';
}
?>
<div class="ps-section--shopping ps-shopping-cart">
    <div class="container">
        <div class="ps-section__content" style='min-height:450px'>
            <div class="table-responsive">
                <div class="row">
                <div style='<?= $style; ?>' class="col-xl-<?= $kolom1; ?> col-lg-<?= $kolom1; ?> col-md-12 col-sm-12 col-12 ">
                    
                <?php 
                echo "<table class='table table-sm'>
                <tbody>
                <tr><td style='width:120px'><strong>No. Invoice</strong></td>  <td class='text-success'><b>$judul</b></td></tr>";
                    if ($rows['keterangan']!=''){
                        $exal = explode('|',$rows['keterangan']);
                        echo "<tr><td><strong>Data Pembeli</strong></td> <td><b>".$exal[5]."</b> (".substr($exal[6], 0, -2)."xx)<br>
                                                                        ".alamat($judul)."</td></tr>";
                    }else{
                        echo "<tr><td><strong>Data Pembeli</strong></td> <td><b>$rows[nama_lengkap]</b> (".substr($rows['no_hp'], 0, -2)."xx)<br>
                                                                        ".alamat($judul)."</td></tr>";
                    }
                echo "</tbody>
                </table><hr>";
                $cek_cod = $this->db->query("SELECT * FROM rb_penjualan where kode_kurir='0' AND kode_transaksi='$judul'");
                if ($cek_cod->num_rows()>=1){ // Cek Transaksi COD
                    echo "<p style='font-size:17px'> <div class='alert alert-danger'><strong>PENTING</strong> - Khusus Pesanan dengan COD (Cash on delivery) bisa bayar saat serah terima Produk...</div></p>";
                }

                $cek_digital = $this->db->query("SELECT * FROM rb_penjualan where kode_kurir is NULL AND kode_transaksi='$judul'");
                $cek_onl = $this->db->query("SELECT * FROM rb_penjualan where kode_kurir!='0' AND kode_transaksi='$judul'");
                $cek_pembayaran = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='$judul' AND nominal='$unik[nominal]' AND pembayaran='1'");
                if ($cp['status_trx']=='tripay' AND $cp['pembayaran']!='1'){
                    $cpd = $this->db->query("SELECT * FROM rb_tripay where kode_transaksi='$judul'")->row_array();

                        //echo "Bayar disini : https://tripay.co.id/checkout/$cp[catatan]";
                        echo "<p style='font-size:18px' class='text-center'>Pembayaran Dengan <b>$cpd[payment_name]</b></p>
                        <div class='alert alert-danger text-center'><strong>PENTING</strong> - Pastikan anda melakukan pembayaran sebelum melewati batas
                                                                pembayaran dan dengan nominal yang tepat</div>";
                                                                
                        
                        if ($cpd['pay_url']!=''){
                            echo "<center><div class='col-12 mb-3' style='margin:30px 0px'>
                                    <label style='color:#878787; margin-bottom: 0px;'>Batas Pembayaran </label><br>
                                        <span style='color:#FF5A92; font-size:18px'>".jam_tgl_indo_day(date('Y-m-d H:i:s', $cpd['expired_time']))."</span>
                            </div>
                            <br><br>
                            
                            <div class='row'>
                                <div class='col-12 col-md-6'>
                                    <a target='_BLANK' style='margin-bottom:10px' class='ps-btn ps-btn--fullwidth' href='$cpd[pay_url]'>Bayar Sekarang</a>
                                </div>";
                                if ($this->session->id_konsumen!=''){
                                    echo "<div class='col-12 col-md-6'>
                                        <a target='_BLANK' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='#' data-toggle='modal' data-target='#pembayaran'>Ganti Channel Pembayaran</a>
                                    </div>";
                                }
                            echo "</div></center>";
                        }else{
                            echo "<form>
                            <div class='form-row align-items-center'>
                                <div class='col-12 mb-3'>
                                    <label style='color:#878787'>Nomor Referensi</label>
                                    <div class='input-group'>
                                    <input type='text' style='border-right:none;font-size:16px' class='form-control' value='$cpd[reference]'>
                                    <div class='input-group-prepend'>
                                        <div class='input-group-text' style='background:transparent;border-left:none'><i style='font-size:22px;color: #bdbdbd;' class='fa fa-copy'></i></div>
                                    </div>
                                    </div>
                                </div>
    
                                <div class='col-12 mb-3'>
                                    <label style='color:#878787'>Kode Bayar/Nomor VA</label>
                                    <div class='input-group'>
                                    <input type='text' style='border-right:none;font-size:16px' class='form-control' value='$cpd[pay_code]'>
                                    <div class='input-group-prepend'>
                                        <div class='input-group-text' style='background:transparent;border-left:none'><i style='font-size:22px;color: #bdbdbd;' class='fa fa-copy'></i></div>
                                    </div>
                                    </div>
                                </div>
    
                                <div class='col-12 mb-3'>
                                    <label style='color:#878787'>Jumlah Tagihan + Fee Rp ".rupiah($cpd['amount']-$unik['nominal'])."</label>
                                    <div class='input-group'>
                                    <div class='input-group-prepend'>
                                        <div class='input-group-text' style='background:transparent;border-left:none'><span style='font-size:20px;color: #bdbdbd;'>Rp</span></div>
                                    </div>
                                    <input type='text' style='border-right:none;font-size:16px' class='form-control' value='".rupiah($cpd['amount'])."'>
                                    <div class='input-group-prepend'>
                                        <div class='input-group-text' style='background:transparent;border-left:none'><i style='font-size:22px;color: #bdbdbd;' class='fa fa-copy'></i></div>
                                    </div>
                                    </div>
                                </div>
    
                                <div class='col-12 mb-3'>
                                    <label style='color:#878787'>Batas Pembayaran </label>
                                    <div class='input-group'>
                                        <span style='color:#FF5A92; font-size:18px'>".jam_tgl_indo_day(date('Y-m-d H:i:s', $cpd['expired_time']))."</span>
                                    </div>
                                </div>
                            </div><br>
                            
                            <div class='row'>
                                <div class='col-12 col-md-6'>
                                    <a target='_BLANK' style='margin-bottom:10px' class='ps-btn ps-btn--fullwidth' href='#' data-toggle='modal' data-target='#petunjuk'>Cara Pembayaran</a>
                                </div>";
                                if ($this->session->id_konsumen!=''){
                                    echo "<div class='col-12 col-md-6'>
                                        <a target='_BLANK' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='#' data-toggle='modal' data-target='#pembayaran'>Ganti Channel Pembayaran</a>
                                    </div>";
                                }
                            echo "</div>";
                        
                        }

                        
                        //var_dump(json_decode($cpd['instructions'], true));
                        echo "<div style='z-index: 9999;' class='modal fade bd-example-modal-lg' id='petunjuk' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered' role='document'>
                                <div class='modal-content' style='padding:10px'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title h4' style='font-size:1.7rem' id='myModalLabel'>Petunjuk Pembayaran $cpd[payment_name]</h5>
                                        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                                    </div>
                                    <div class='modal-body'>
                                            <div id='accordion'>";
                                            $obj = json_decode($cpd['instructions'], true);
                                            $num = 1;
                                            foreach ($obj as $key => $val) {
                                                echo "<div class='card'>
                                                    <div class='card-header' id='heading$num'>
                                                        <h5 class='mb-0'>
                                                        <a class='btn btn-link' data-toggle='collapse' data-target='#collapse$num' aria-expanded='true' aria-controls='collapse$num'>
                                                            $val[title]
                                                        </a>
                                                        </h5>
                                                    </div>";

                                                    echo "<div id='collapse$num' class='collapse ".($num==1?'show':'')."' aria-labelledby='heading$num' data-parent='#accordion'>
                                                    <div class='card-body'>
                                                        <ol>";
                                                        for($i=0; $i < count($val['steps']); $i++){
                                                            echo "<li>".$val['steps'][$i]."</li>";
                                                        }
                                                        echo "</ol>
                                                    </div></div>
                                                </div>";
                                                $num++;
                                            }
                                            echo "</div>
                                            </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";

                }elseif ($cp['status_trx']=='tripay' AND $cp['pembayaran']=='1'){
                    if ($rows['proses']=='0'){
                        echo "<div class='alert alert-success' role='alert'><center style='font-size:16px'>Terima kasih, pembayaran untuk order ini telah kami terima, 
                                Dan kami telah menginformasikan kepada seller untuk segera diproses dalam 1 x 24 Jam.</center></div>";
                    }
                }else{
                if ($cek_onl->num_rows()>=1 OR $cek_digital->num_rows()>=1){ // Cek Transaksi Online
                    if ($rows['proses']=='0'){
                        if ($cek_pembayaran->num_rows()>='1'){
                            echo "<div class='alert alert-success' role='alert'><center style='font-size:16px'>Terima kasih, pembayaran untuk order ini telah kami terima, 
                            Dan kami telah menginformasikan kepada seller untuk segera diproses dalam 1 x 24 Jam.</center></div>";
                        }else{
                            $cek_payment = $this->db->query("SELECT catatan FROM rb_penjualan_otomatis where kode_transaksi='$judul' AND nominal='$unik[nominal]'")->row_array();
                            if ((int)$ss['durasi']<='24' AND trim($ss['durasi'])!=''){
                                echo "<div class='alert alert-warning' style='margin-bottom:20px; padding:2rem'>
                                        <strong style='font-size:18px'>Yuk, buruan selesaikan pembayaranmu</strong> <br>
                                        Stok barang di pesananmu tinggal sedikit. Segera bayar biar nggak kehabisan barangnya!
                                    </div>

                                        <div style='font-size:18px; line-height:initial' id='timer'></div>
                                
                                        <div style='line-height:initial; text-align:center; margin-top:20px'>Batas Akhir Pembayaran<br>
                                        <b style='font-size:18px;'>".jam_tgl_indo_day($tomorrow)."</b></div><br>";
                            }else{
                                echo "<div class='alert alert-danger' style='margin-bottom:20px; padding:2rem'>
                                    <strong style='font-size:20px'>Pesanan telah dibatalkan!</strong> <br>
                                    Harap tidak membayar pesanan ini, Apabila Anda menerima email ini namun pembayaran telah Anda lakukan, mohon lampirkan bukti bayar pada halaman konfirmasi pembayaran.
                                </div>";
                            }

                            if (is_numeric($cek_payment['catatan'])!='1'){
                                
                            }else{
                                echo "<div class='alert alert-info' role='alert'><center style='font-size:15px'><b>PENTING</b> - Kami Telah Mengirim Sebuah pesan ke email anda <b>".sensor_email($rows['email'])."</b> untuk menyelesaikan pembayaran pesanan anda ini, Terima kasih...</center></div>";
                            }
                        }
                    }elseif ($rows['proses']=='x'){
                            echo "<div class='alert alert-danger' style='margin-bottom:20px; padding:2rem'>
                                <strong style='font-size:20px'>Pesanan telah dibatalkan!</strong> <br>
                                Harap tidak membayar pesanan ini, Apabila Anda menerima email ini namun pembayaran telah Anda lakukan, mohon lampirkan bukti bayar pada halaman konfirmasi pembayaran.
                            </div>";
                    }else{
                        $cek_trx = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$judul'")->num_rows();
                        $cek_kurir = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$judul' AND kode_kurir=''")->num_rows();
                        if ($cek_trx==$cek_kurir){
                            echo "<p style='font-size:17px'> 
                                    <div class='alert alert-warning' style='margin-bottom:20px; padding:2rem'>
                                        <strong style='font-size:18px'>Terima kasih, Pesanan telah kami terima.</strong> <br>
                                        Driver kami sedang menuju toko untuk mengambil Pesanan untuk segera diantarkan ke alamat anda.
                                    </div>
                            </p>";
                        }else{
                            $orderss = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$judul' ORDER BY kode_kurir ASC");
                            foreach($orderss->result_array() as $rx){
                                $cek_kurirx = $this->db->query("SELECT * FROM rb_penjualan where id_penjualan='$rx[id_penjualan]' AND kode_kurir=''");
                                $cek_kurir_all = $this->db->query("SELECT a.*, b.nama_reseller, b.kecamatan_id, b.kota_id, b.provinsi_id FROM rb_penjualan a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.id_penjualan='$rx[id_penjualan]'");
                                $tt = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$rx[id_penjualan]'")->row_array();
                                $prd = $this->db->query("SELECT * FROM `rb_penjualan_detail` a where a.id_penjualan='$rx[id_penjualan]'")->num_rows();
                                $kpn = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                                JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                                    where b.id_penjualan='$rx[id_penjualan]'")->row_array();
                                    
                                $rowd = $cek_kurir_all->row_array();
                                
                                if ($cek_kurirx->num_rows()>=1){
                                    echo "<p style='font-size:17px'> 
                                        <div class='alert alert-success' style='margin-bottom:10px; padding:2rem'>
                                            <strong style='font-size:18px'>
                                                <u><i class='icon-store' style='font-size: 1.3em'></i> $rowd[nama_reseller]</u> <small class='badge badge-secondary float-right' style='width:90px'>".strip_tags(status($rx['proses']))."</small><br>
                                            </strong>
                                            SubTotal <b>Rp ".rupiah($tt['total']+$rx['ongkir']-$kpn['diskon'])."</b> ($prd Produk) <br>
                                            
                                            <span style='font-size:18px'>Terima kasih, Pesanan telah kami terima.</span> <br>
                                            Driver kami sedang menuju toko untuk mengambil Pesanan untuk segera diantarkan ke alamat anda.
                                        </div>
                                    </p>";
                                }else{
                                    $cek_payment = $this->db->query("SELECT catatan FROM rb_penjualan_otomatis where kode_transaksi='$judul' AND nominal='$unik[nominal]'")->row_array();
                                    if (is_numeric($cek_payment['catatan'])!='1'){ $alert = 'default'; $css = 'border:1px solid #e3e3e3; background:#fff; margin-bottom:5px; padding:1rem 2rem; '; }else{ $alert = 'info'; $css = 'margin-bottom:10px; padding:2rem; '; }
                                    echo "<div class='alert alert-$alert' style='$css'>
                                        <strong style='font-size:18px'>
                                            <u><i class='icon-store' style='font-size: 1.3em'></i> $rowd[nama_reseller]</u> <small class='badge badge-secondary float-right' style='width:90px'>".status($rx['proses'])."</small><br>
                                        </strong>
                                        SubTotal <b>Rp ".rupiah($tt['total']+$rx['ongkir']-$kpn['diskon'])."</b> ($prd Produk) <br>";
                                        
                                        if ((int)$ss['durasi']<='24' AND trim($ss['durasi'])!=''){
                                            // 
                                        }else{
                                            if (is_numeric($cek_payment['catatan'])!='1'){
                                                echo "Hai, pembayaran untuk pesanan ini belum dilakukan...";
                                            }else{
                                                echo "<span style='font-size:18px'>Pesanan telah di-".status($rx['proses']).".</span> <br>";
                                                if ($rx['proses']=='2'){
                                                    echo "Hai, Pesanan kamu saat ini telah di-".status($rx['proses']).", Silahkan menunggu untuk kami cek pembayarannya.";
                                                }elseif ($rx['proses']=='1'){
                                                    echo "Hai, Pesanan kamu telah di-".status($rx['proses']).", Saat ini sedang Pengemasan untuk selanjutnya dikirimkan.";
                                                }else{
                                                    echo "Hai, Pesanan kamu saat ini telah di-".status($rx['proses'])." dan kemungkinan sedang perjalanan ketempatmu.";
                                                }
                                            }
                                        }
                                    echo "</div>";
                                }
                            }
                            
                            if ((int)$ss['durasi']<='24' AND trim($ss['durasi'])!=''){
                                echo "<div class='alert alert-warning mt-4' style='margin-bottom:20px; padding:2rem'>
                                    <strong style='font-size:18px'>Yuk, buruan selesaikan pembayaranmu</strong> <br>
                                    Stok barang di pesananmu tinggal sedikit. Segera bayar biar nggak kehabisan barangnya!
                                </div>

                                <div style='font-size:18px; line-height:initial' id='timer'></div>
                        
                                <div style='line-height:initial; text-align:center; margin-top:20px'>Batas Akhir Pembayaran<br>
                                <b style='font-size:18px;'>".jam_tgl_indo_day($tomorrow)."</b></div><br>";
                            }
                            
                                                    
                        }
                    }

                    echo "<div class='row'>
                            <div class='col-12 col-md-6'>
                                <a target='_BLANK' style='margin-bottom:10px' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='".base_url()."konfirmasi/tracking/".$this->uri->segment(3)."?print'>Lihat dan Cetak Tagihan</a>
                            </div>
                            <div class='col-12 col-md-6'>";
                            if ($ss['durasi']<='24' AND trim($ss['durasi'])!=''){
                                echo "<a class='ps-btn ps-btn--fullwidth' href='#' data-toggle='modal' data-target='#pembayaran'>Pilih Metode Pembayaran</a>";
                            }else{
                                echo "<a target='_BLANK' class='ps-btn ps-btn--fullwidth' href='".base_url()."konfirmasi/index?kode=$judul'>Konfirmasi Pembayaran</a>";
                            }
                            echo "</div>
                        </div><br>";
                }
                }
                ?>
                
                </div>

                <div class="col-xl-<?= $kolom2; ?> col-lg-<?= $kolom2; ?> col-md-12 col-sm-12 col-12 <?= $sidebar; ?>">
                    <div class="ps-block--shopping-total">
                        <div class="ps-block__header">
                            <?php 
                            $cek_jml_kurir = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$judul'")->num_rows();
                            
                            $co = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$judul' AND kode_kurir!=''")->num_rows();
                            if ($co>=1){
                                $angka_acak = substr($judul,-2);
                            }else{
                                $angka_acak = 0;
                            }
                            
                                $ong = $this->db->query("SELECT sum(ongkir) as ongkir, sum(fee_admin)/count(*) as fee_admin FROM rb_penjualan where kode_transaksi='$kode'")->row_array();
                                $cekk = $this->db->query("SELECT jenis_produk FROM rb_penjualan_detail a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='$rows[id_penjualan]' GROUP BY b.jenis_produk")->row_array();
                            
                            if ($cek_jml_kurir==1){
                                echo "<p style='margin-bottom:0px'><b>Status Order<span class='text-danger'>".status($total['proses'])."</span></b></p><hr>";
                            }
                            
                            if ($cekk['jenis_produk']=='Fisik'){ ?>
                                <p style='margin-bottom:0px'>Berat<span> <?php echo ($total['total_berat']>1000?number_format($total['total_berat']/1000,1).' Kg':$total['total_berat'].' Gram'); ?></span></p>
                                <p style='margin-bottom:0px'>Ongkos Kirim <span> <?php echo "Rp ".rupiah($ong['ongkir']); ?></span></p>
                            <?php } ?>

                            <?php 
                                if ($ong['fee_admin']>'0'){
                                    echo "<p style='margin-bottom:0px'>Fee Admin <span>Rp ".rupiah($ong['fee_admin'])."</span></p>";
                                }
                            ?>
                            <p style='margin-bottom:0px'>Subtotal <span> <?php echo "Rp ".rupiah($total['total']-$total['diskon_total']); ?></span></p>
                            <hr>
                            <?php 
                                $cek_withdraw = $this->db->query("SELECT * FROM rb_withdraw where keterangan='$judul'");
                                if ($cek_withdraw->num_rows()>=1){
                                    $cw = $cek_withdraw->row_array();
                                    echo "<p style='margin-bottom:0px'>Pakai Saldo <span>- Rp ".rupiah($cw['nominal'])."</span></p>";
                                }

                                $data_kupon = $this->db->query("SELECT c.* FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                                JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                                where b.kode_transaksi='$kode'");
                                foreach ($data_kupon->result_array() as $kup) {
                                    echo "<p style='margin-bottom:0px'>
                                         <b style='color:green; font-weight:500'>Diskon Produk</b>
                                         <span>- Rp ".rupiah($kup['nilai'])."</span></p>";
                                }

                                $cek_kup = $this->db->query("SELECT * FROM rb_penjualan_kupon where status_kupon='$judul'");
                                foreach ($cek_kup->result_array() as $kup) {
                                    echo "<p style='margin-bottom:0px'>
                                         <b style='color:green; font-weight:500'>$kup[keterangan_kupon]</b>
                                         <span>- Rp ".rupiah($kup['nilai'])."</span></p>";
                                }
                            ?>
                            <p style='margin-bottom:0px'>Kode Unik Transfer<span> - <?php echo $angka_acak; ?></span></p>
                        </div>
                        <div class="ps-block__content">
                            <h3>Total <span><b class='nominaltrx2'><?php echo rupiah($unik['nominal']); ?></b> <button style='float:right; margin-left:10px; border:1px solid #ababab' type='button' id='copyx' data-toggle='button' title='Copy Nominal Transfer' aria-pressed='false' autocomplete='off' class='btn btn-default myButtonL' onclick="copyToClipboard('.nominaltrx2')"><span style='font-size:18px' class='fa fa-copy'></span></button></span></h3>
                        </div>
                    </div>
                    <?php 
                        if ($total['proses']<'2' AND $total['proses']!='x'){
                            echo "<a class='ps-btn ps-btn--fullwidth ps-btn--black' href='".base_url()."konfirmasi/batalkanpesanan88dshweu8dh78785ufhd8?kode=$judul' onclick=\"return confirm('Apa anda yakin untuk Batalkan Pesanan ini?')\">Batalkan Pesanan</a>";
                        }

                        if ($this->session->id_konsumen==$rows['id_pembeli']){
                            if ($total['proses']>='3' AND $total['proses']!='x'){
                                echo "<a target='_BLANK' class='ps-btn ps-btn--fullwidth ps-btn--black' style='color:red' href='".base_url()."members/keranjang_detail/$rows[id_penjualan]'>Lacak Pesanan</a>";
                            }
                        }
                    ?>
                </div>
                </div>
            </div>
        </div>
        <hr>

    </div>
</div>

    <div style='z-index: 9999;' class="modal bd-example-modal-lg" id="pembayaran" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style='padding:10px'>
                <div class="modal-header">
                    <h5 class="modal-title h4" style='font-size:1.7rem' id="myModalLabel">Pilih Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                    <div style='padding:10px 10px'>
                    <dl style='border:1px solid #e3e3e3; padding:10px; background: #e7e7e7;'>
                        <dt>No Invoice</dt>
                        <dd style='color:green'><u><?= $judul; ?></u></dd>
                        <dt>Total Pembayaran</dt>
                        <dd style='color:red'>Rp <b style='font-size:18px'><?php echo rupiah($unik['nominal']); ?></b> </dd>
                    </dl>
                    <?php 
                    echo "<div class='opsiPembayaran'>";
                    
                    if (config('transfer_manual')=='Y'){
                        //if ($rows['proses']=='0'){
                            echo "<a class='ps-btn ps-btn--outline ps-btn--fullwidth pembayaran' style='margin-bottom:5px; text-align:left' href='#' data-id='1' data-dismiss='modal'>Transfer Manual <i class='icon-arrow-right pull-right'></i></a><br>";  
                        //}
                    }
                    
                    if (config('ipaymu_aktif')=='Y'){
                        //if ($rows['proses']=='0'){
                            if ($cek_pembayaran->num_rows()<='0'){
                                $cp = $this->db->query("SELECT status_trx FROM rb_penjualan_otomatis where kode_transaksi='$judul' AND nominal='$unik[nominal]'")->row_array();
                                if ($cp['status_trx']=='pending'){
                                    echo "<a target='_BLANK' style='margin-bottom:5px; text-align:left' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='".base_url()."konfirmasi/bayar?inv=$kode'>Proses Ulang Pembayaran <i class='icon-arrow-right pull-right'></i></a><br>";
                                }else{
                                    echo "<a target='_BLANK' style='margin-bottom:5px; text-align:left' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='".base_url()."konfirmasi/bayar?inv=$kode'>Bayar via Ipaymu <small>(VA, Gopay, OVO, Alfamart dll)</small> <i class='icon-arrow-right pull-right'></i></a><br>";   
                                }
                            }
                        //}
                    }
                    
                    if (config('xendit_aktif')=='Y'){
                        //if ($rows['proses']=='0'){
                            if ($cek_pembayaran->num_rows()<='0'){
                                echo "<a target='_BLANK' style='margin-bottom:5px; text-align:left' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='".base_url()."konfirmasi/xendit?inv=$kode'>Bayar via Xendit <small>(VA, QR, CC/DC, Retail Outlets dll)</small> <i class='icon-arrow-right pull-right'></i></a><br>";   
                            }
                        //}
                    }

                    if (config('tripay_aktif')=='Y'){
                        //if ($rows['proses']=='0'){
                            if ($cek_pembayaran->num_rows()<='0'){
                                echo "<a class='ps-btn ps-btn--outline ps-btn--fullwidth pembayaran' style='margin-bottom:5px; text-align:left' href='#' data-id='2' data-id1='$kode' data-dismiss='modal'>Bayar via Tripay <small>(VA, Gopay, OVO, Alfamart dll)</small> <i class='icon-arrow-right pull-right'></i></a><br>";   
                            }
                        //}
                    }
                    
                    echo "<a target='_BLANK' style='margin-bottom:5px; text-align:left' class='ps-btn ps-btn--outline ps-btn--fullwidth' href='".base_url()."konfirmasi/flip?inv=$kode'>Bayar via Flip <small>(VA, QR, CC/DC, Retail Outlets dll)</small> <i class='icon-arrow-right pull-right'></i></a><br>";     
                    
                    echo "</div>";

                    ?>
                    </div><br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style='z-index: 9999;' class="modal bd-example-modal-lg" id="tripay" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style='padding:10px'>
                <div class="modal-header">
                    <h5 class="modal-title h4" style='font-size:1.7rem' id="myModalLabel">Pilih Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                
                </div>
            </div>
        </div>
    </div>

<div style='z-index: 9999;' class="modal bs-example-modal-lg" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" style='font-size:1.7rem' id="myModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
          <div class="modal-body">
            <div style='padding:10px 10px'>
                <dl style='border:1px solid #e3e3e3; padding:10px; background: #e7e7e7;'>
                    <dt>No Invoice</dt>
                    <dd style='color:green'><u><?= $judul; ?></u></dd>
                    <dt>Total Pembayaran</dt>
                    <dd style='color:red'>Rp <b style='font-size:18px'><?php echo rupiah($unik['nominal']); ?></b> </dd>
                </dl>
            </div>

            <div class="content-body"></div>

          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
    $(function(){
        $(document).on('click','.pembayaran',function(e){
            e.preventDefault();
            $("#myModalDetail").modal('show');
            $.post("<?php echo site_url()?>konfirmasi/pembayaran",
                {id:$(this).attr('data-id'),trx:$(this).attr('data-id1')},
                function(html){
                    //console.log(html);
                    $(".content-body").html(html);
                }   
            );
        });
    });
</script>