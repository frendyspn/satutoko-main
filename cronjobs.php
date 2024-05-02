<?php
date_default_timezone_set('Asia/Jakarta');

$db['host'] = "localhost"; //host
$db['user'] = "root"; //username database
$db['pass'] = ""; //password database
$db['name'] = "db_tajalapak"; //nama database
$koneksi = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);

// Cron Jobs Order Batal setelah 1 x 24
$cek_order = mysqli_query($koneksi,"SELECT b.id_penjualan_otomatis, a.id_penjualan, a.id_pembeli, a.kode_transaksi, b.nominal, b.waktu_proses, SUBSTRING_INDEX(TIMEDIFF(NOW(), b.waktu_proses), ':', 1) as selisih FROM `rb_penjualan` a JOIN rb_penjualan_otomatis b ON a.kode_transaksi=b.kode_transaksi where a.proses='1' AND b.pembayaran='1'");
while($row = mysqli_fetch_array($cek_order)){
    if ((int)$row['selisih']>='24'){
        mysqli_query($koneksi,"UPDATE rb_penjualan set proses='x' where id_penjualan='$row[id_penjualan]'");

        $rek = mysqli_fetch_array(mysqli_query($koneksi,"SELECT id_rekening FROM `rb_rekening` ORDER BY RAND() DESC LIMIT 1"));
        $row = mysqli_fetch_array(mysqli_query($koneksi,"SELECT a.jumlah, a.diskon, a.harga_jual, sum((a.jumlah*a.harga_jual)-a.diskon) as total_belanja FROM rb_penjualan_detail a where a.id_penjualan='$row[id_penjualan]'"));
        $ong = mysqli_fetch_array(mysqli_query($koneksi,"SELECT kode_transaksi, ongkir, id_pembeli FROM rb_penjualan where id_penjualan='$row[id_penjualan]'"));
        $cek_pembayaran = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM rb_penjualan_otomatis where kode_transaksi='$ong[kode_transaksi]'"));
        $cek_pembayaran_saldo = mysqli_query($koneksi,"SELECT * FROM rb_withdraw where keterangan='$ong[kode_transaksi]' AND status='Sukses' AND transaksi='debit'");
        
        if ($cek_pembayaran['saldo']=='0' AND $cek_pembayaran['pembayaran']=='1'){ // Jika Pembayaran dengan Saldo + Transfer
			$cps = mysqli_fetch_array($cek_pembayaran_saldo);
			$dana_kembali = $cek_pembayaran['nominal']+$cps['nominal'];
		}elseif ($cek_pembayaran['saldo']=='1' AND $cek_pembayaran['pembayaran']=='1'){  // Jika Pembayaran dengan Full Saldo
			$dana_kembali = $cek_pembayaran['nominal'];
		}elseif ($cek_pembayaran['saldo']=='1' AND $cek_pembayaran['pembayaran']==''){  // Jika Pembayaran dengan Saldo dan Belum Transfer
			$cps = mysqli_fetch_array($cek_pembayaran_saldo);
			$dana_kembali = $cps['nominal'];
		}elseif ($cek_pembayaran['saldo']=='2' AND $cek_pembayaran['pembayaran']=='1'){ // Jika Pembayaran tanpa Saldo
			$dana_kembali = $cek_pembayaran['nominal'];
		}

        mysqli_query($koneksi, "INSERT INTO rb_withdraw (id_rekening_reseller, id_reseller, nominal, status, transaksi, keterangan, akun, waktu_withdraw) 
                                VALUES('$rek[id_rekening]','$ong[id_pembeli]','$dana_kembali','Sukses','Kredit','Refund Orders $ong[kode_transaksi]','konsumen','".date('Y-m-d H:i:s')."')");
    }
}



// Cron Jobs Cek Paket
$cek_paket_aktif = mysqli_query($koneksi,"SELECT * FROM `rb_reseller_paket` where status='Y' AND expire_date<now()");
while($row = mysqli_fetch_array($cek_paket_aktif)){
    mysqli_query($koneksi,"DELETE FROM rb_reseller_paket where id_reseller_paket='$row[id_reseller_paket]'");
}
