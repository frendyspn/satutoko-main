<?php 
/*
-- ---------------------------------------------------------------
-- MARKETPLACE MULTI BUYER MULTI SELLER + SUPPORT RESELLER SYSTEM
-- CREATED BY : ROBBY PRIHANDAYA
-- COPYRIGHT  : Copyright (c) 2018 - 2019, PHPMU.COM. (https://phpmu.com/)
-- LICENSE    : http://opensource.org/licenses/MIT  MIT License
-- CREATED ON : 2019-03-26
-- UPDATED ON : 2019-03-27
-- ---------------------------------------------------------------
*/
class Model_reseller extends CI_model{
    function top_menu(){
        return $this->db->query("SELECT * FROM menu where position='Top' ORDER BY urutan ASC");
    }
    
    function testimoni(){
        return $this->db->query("SELECT a.*, b.nama_lengkap, b.id_konsumen FROM testimoni a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen ORDER BY a.id_testimoni DESC");
    }

    function testimoni_update(){
        $datadb = array('isi_testimoni'=>$this->input->post('b'),
                            'aktif'=>$this->input->post('f'));
        $this->db->where('id_testimoni',$this->input->post('id'));
        $this->db->update('testimoni',$datadb);
    }

    function testimoni_edit($id){
        return $this->db->query("SELECT a.*, b.nama_lengkap, b.id_konsumen FROM testimoni a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen where a.id_testimoni='$id'");
    }

    function testimoni_delete($id){
        return $this->db->query("DELETE FROM testimoni where id_testimoni='$id'");
    }

    function public_testimoni($sampai, $dari){
        return $this->db->query("SELECT a.*, b.nama_lengkap, b.foto, b.id_konsumen, b.jenis_kelamin FROM testimoni a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen  where a.aktif='Y' ORDER BY a.id_testimoni DESC LIMIT $dari, $sampai");
    }

    function hitung_testimoni(){
        return $this->db->query("SELECT * FROM testimoni where aktif='Y'");
    }

    function insert_testimoni(){
            $datadb = array('id_konsumen'=>$this->session->id_konsumen,
                            'isi_testimoni'=>$this->input->post('testimoni'),
                            'aktif'=>'N',
                            'waktu_testimoni'=>date('Y-m-d H:i:s'));
        $this->db->insert('testimoni',$datadb);
    }

    function cari_reseller($kata){
        $pisah_kata = explode(" ",$kata);
        $jml_katakan = (integer)count($pisah_kata);
        $jml_kata = $jml_katakan-1;
        $cari = "SELECT * FROM rb_reseller a LEFT JOIN rb_kota b ON a.kota_id=b.kota_id WHERE a.nama_reseller LIKE '%".$kata."%' OR b.nama_kota LIKE '%".$kata."%' ORDER BY a.id_reseller DESC LIMIT 36";
        return $this->db->query($cari);
    }

    public function view_join_rows($table1,$table2,$field,$where,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        return $this->db->get();
    }

    function penjualan_list_konsumen($id,$level){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.status_penjual='$level' AND a.id_penjual='$id' ORDER BY a.id_penjualan DESC");
    }

    function menunggu_pembayaran($id,$level){
        return $this->db->query("SELECT a.*, b.*, z.pembayaran FROM rb_penjualan_otomatis z JOIN `rb_penjualan` a ON z.kode_transaksi=a.kode_transaksi JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where (z.pembayaran is NULL OR a.proses='2') AND a.status_penjual='$level' AND a.id_penjual='$id' ORDER BY a.id_penjualan DESC");
    }

    function group_order($id,$level,$kode){
        return $this->db->query("SELECT a.*, b.*, z.pembayaran FROM rb_penjualan_otomatis z JOIN `rb_penjualan` a ON z.kode_transaksi=a.kode_transaksi JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.status_penjual='$level' AND a.id_penjual='$id' AND a.group_order='$kode' ORDER BY a.id_penjualan DESC");
    }

    function penjualan_status($id,$level,$status){
        return $this->db->query("SELECT a.*, b.*, z.pembayaran FROM rb_penjualan_otomatis z JOIN `rb_penjualan` a ON z.kode_transaksi=a.kode_transaksi JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where z.pembayaran='1' AND a.proses='$status' AND a.status_penjual='$level' AND a.id_penjual='$id' ORDER BY a.id_penjualan DESC");
    }

    function penjualan_status_tanpabayar($id,$level,$status){
        return $this->db->query("SELECT a.*, b.*, z.pembayaran FROM rb_penjualan_otomatis z JOIN `rb_penjualan` a ON z.kode_transaksi=a.kode_transaksi JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.proses='$status' AND a.status_penjual='$level' AND a.id_penjual='$id' ORDER BY a.id_penjualan DESC");
    }


    function penjualan_list_konsumen_admin($level){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.status_penjual='$level' ORDER BY a.id_penjualan DESC");
    }

    function penjualan_list_konsumen_bulan($level,$bulan){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.status_penjual='$level' AND MONTH(a.waktu_transaksi)='$bulan' AND YEAR(a.waktu_transaksi)='".date('Y')."' ORDER BY a.id_penjualan DESC");
    }

    function jual($id){
        return $this->db->query("SELECT sum(a.jumlah) as jual FROM rb_penjualan_detail a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where a.id_produk='$id' AND b.status_penjual='admin' AND b.proses='4'");
    }

    function beli($id){
        return $this->db->query("SELECT sum(a.jumlah_pesan) as beli FROM rb_pembelian_detail a where a.id_produk='$id'");
    }

    function jual_reseller($penjual, $produk){
        return $this->db->query("SELECT sum(jumlah) as jual FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan where a.status_pembeli='konsumen' AND a.status_penjual='reseller' AND a.id_penjual='$penjual' AND b.id_produk='$produk' AND (a.proses>='3' AND a.proses!='x')");
    }

    function beli_reseller($pembeli, $produk){
        return $this->db->query("SELECT sum(jumlah) as beli FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan where a.status_pembeli='reseller' AND a.status_penjual='admin' AND a.id_pembeli='$pembeli' AND b.id_produk='$produk' AND (a.proses>='3' AND a.proses!='x')");
    }

    function penjualan_konsumen_detail($id){
        return $this->db->query("SELECT * FROM `rb_penjualan` a LEFT JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_penjualan='$id'");
    }

    function profile_konsumen($id){
        return $this->db->query("SELECT a.kordinat_lokasi, a.id_konsumen, a.username, a.nama_lengkap, a.email, a.jenis_kelamin, a.tanggal_lahir, a.tempat_lahir, a.alamat_lengkap, a.kecamatan_id, a.no_hp, a.tanggal_daftar, a.referral_id, a.token, b.kota_id, b.nama_kota as kota, c.provinsi_id, c.nama_provinsi as propinsi, a.foto FROM `rb_konsumen` a LEFT JOIN rb_kota b ON a.kota_id=b.kota_id LEFT JOIN rb_provinsi c ON b.provinsi_id=c.provinsi_id where a.id_konsumen='$id'");
    }

    function orders_report($id,$level){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.status_penjual='$level' AND a.id_pembeli='$id' ORDER BY a.id_penjualan DESC");
    }

    function agenda_terbaru($limit){
        return $this->db->query("SELECT * FROM agenda ORDER BY id_agenda DESC LIMIT $limit");
    }

    public function view_join_where_one($table1,$table2,$field,$where){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        return $this->db->get();
    }

    function modupdatefoto(){
        $config['upload_path'] = 'asset/foto_user/';
        $config['allowed_types'] = 'gif|jpg|png|JPG|gif|JPEG|jpeg';
        $config['max_size']     = '1000'; // kb
        $this->load->library('upload', $config);
        $this->upload->do_upload();
        $hasil=$this->upload->data();

        $config['image_library'] = 'gd2';
        $config['source_image'] = 'asset/foto_user/'.$hasil['file_name'];
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['height']       = 622;
        $this->load->library('image_lib', $config);
        $this->image_lib->crop();

        $datadb = array('foto'=>$hasil['file_name']);
        $this->db->where('id_konsumen',$this->session->id_konsumen);
        $this->db->update('rb_konsumen',$datadb);
    }

    function modupdatefotoreseller(){
        $config['upload_path'] = 'asset/foto_user/';
        $config['allowed_types'] = 'gif|jpg|png|JPG|gif|JPEG|jpeg';
        $config['max_size']     = '1000'; // kb
        $this->load->library('upload', $config);
        $this->upload->do_upload();
        $hasil=$this->upload->data();

        $config['image_library'] = 'gd2';
        $config['source_image'] = 'asset/foto_user/'.$hasil['file_name'];
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['height']       = 622;
        $this->load->library('image_lib', $config);
        $this->image_lib->crop();

        $datadb = array('foto'=>$hasil['file_name']);
        $this->db->where('id_reseller',$this->session->id_reseller);
        $this->db->update('rb_reseller',$datadb);
    }

    function profile_update($id){
        $ro = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='$id'")->row_array();
        $config['upload_path'] = 'asset/foto_user/';
        $config['allowed_types'] = 'gif|jpg|png|JPG|gif|JPEG|jpeg';
        $config['max_size']     = '1000'; // kb
        $this->load->library('upload', $config);
        $this->upload->do_upload('foto');
        $hasil=$this->upload->data();
        
        if (strlen(str_replace(' ','',cetak($this->input->post('aa'))))>=5){
            $username        = cetak(trim($this->input->post('aa')));
        }else{
            $username        = 'USR'.date('His');
        }
        
        if (strlen(str_replace(' ','',cetak($this->input->post('b'))))>=5){
            $nama_lengkap        = cetak(trim($this->input->post('b')));
        }else{
            $nama_lengkap        = 'NM'.date('YmdHis');
        }
            
        if (trim($this->input->post('a')) != ''){
            if ($hasil['file_name']==''){
                $datadbd = array('username'=>$username,
                            'password'=>hash("sha512", md5($this->input->post('a'))),
                            'nama_lengkap'=>$nama_lengkap,
                            'email'=>cetak(strip_tags($this->input->post('c'))),
                            'jenis_kelamin'=>cetak($this->input->post('d')),
                            'tanggal_lahir'=>tgls($this->input->post('e')),
                            'tempat_lahir'=>cetak(strip_tags($this->input->post('f'))),
                            'alamat_lengkap'=>cetak(strip_tags($this->input->post('g'))),
                            'provinsi_id'=>cetak(strip_tags($this->input->post('provinsi_id'))),
                            'kecamatan_id'=>cetak(strip_tags($this->input->post('kecamatan_id'))),
                            'kota_id'=>cetak(strip_tags($this->input->post('kota_id'))),
                            'no_hp'=>cetak(strip_tags($this->input->post('l'))),
                            'kordinat_lokasi'=>cetak($this->input->post('lokasi')));
            }else{
                unlink('asset/foto_user/'.$ro['foto']);
                $datadbd = array('username'=>$username,
                            'password'=>hash("sha512", md5($this->input->post('a'))),
                            'nama_lengkap'=>$nama_lengkap,
                            'email'=>cetak(strip_tags($this->input->post('c'))),
                            'jenis_kelamin'=>cetak($this->input->post('d')),
                            'tanggal_lahir'=>tgls($this->input->post('e')),
                            'tempat_lahir'=>cetak(strip_tags($this->input->post('f'))),
                            'alamat_lengkap'=>cetak(strip_tags($this->input->post('g'))),
                            'provinsi_id'=>cetak(strip_tags($this->input->post('provinsi_id'))),
                            'kecamatan_id'=>cetak(strip_tags($this->input->post('kecamatan_id'))),
                            'kota_id'=>cetak(strip_tags($this->input->post('kota_id'))),
                            'foto'=>$hasil['file_name'],
                            'no_hp'=>cetak(strip_tags($this->input->post('l'))),
                            'kordinat_lokasi'=>cetak($this->input->post('lokasi')));
            }
        }else{
            if ($hasil['file_name']==''){
                $datadbd = array('username'=>$username,
                            'nama_lengkap'=>$nama_lengkap,
                            'email'=>cetak(strip_tags($this->input->post('c'))),
                            'jenis_kelamin'=>cetak($this->input->post('d')),
                            'tanggal_lahir'=>tgls($this->input->post('e')),
                            'tempat_lahir'=>cetak(strip_tags($this->input->post('f'))),
                            'alamat_lengkap'=>cetak(strip_tags($this->input->post('g'))),
                            'provinsi_id'=>cetak(strip_tags($this->input->post('provinsi_id'))),
                            'kecamatan_id'=>cetak(strip_tags($this->input->post('kecamatan_id'))),
                            'kota_id'=>cetak(strip_tags($this->input->post('kota_id'))),
                            'no_hp'=>cetak(strip_tags($this->input->post('l'))),
                            'kordinat_lokasi'=>cetak($this->input->post('lokasi')));
            }else{
                unlink('asset/foto_user/'.$ro['foto']);
                $datadbd = array('username'=>$username,
                            'nama_lengkap'=>$nama_lengkap,
                            'email'=>cetak(strip_tags($this->input->post('c'))),
                            'jenis_kelamin'=>cetak($this->input->post('d')),
                            'tanggal_lahir'=>tgls($this->input->post('e')),
                            'tempat_lahir'=>cetak(strip_tags($this->input->post('f'))),
                            'alamat_lengkap'=>cetak(strip_tags($this->input->post('g'))),
                            'provinsi_id'=>cetak(strip_tags($this->input->post('provinsi_id'))),
                            'kecamatan_id'=>cetak(strip_tags($this->input->post('kecamatan_id'))),
                            'kota_id'=>cetak(strip_tags($this->input->post('kota_id'))),
                            'foto'=>$hasil['file_name'],
                            'no_hp'=>cetak(strip_tags($this->input->post('l'))),
                            'kordinat_lokasi'=>cetak($this->input->post('lokasi')));
            }
        }
        $this->db->where('id_konsumen',$id);
        $this->db->update('rb_konsumen',$datadbd);
    }

    function penjualan_list_konsumen_top($id,$level){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.status_penjual='$level' AND a.id_penjual='$id' ORDER BY a.id_penjualan DESC LIMIT 10");
    }

    function reseller_pembelian($id,$level){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_pembeli=b.id_reseller where a.status_penjual='$level' AND a.id_pembeli='$id' AND SUBSTRING(a.service,1,5)='TRX-R' ORDER BY a.id_penjualan DESC");
    }

    function penjualan_detail($id){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_pembeli=b.id_reseller where a.id_penjualan='$id'");
    }

    function penjualan_konsumen_detail_reseller($id){
        return $this->db->query("SELECT a.*, b.*, c.nama_reseller FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen JOIN rb_reseller c ON a.id_penjual=c.id_reseller where a.id_penjualan='$id'");
    }

    function penjualan_list($id,$level){
        return $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_pembeli=b.id_reseller where a.status_penjual='$level' AND a.id_penjual='$id' AND SUBSTRING(a.service,1,5)='TRX-R' ORDER BY a.id_penjualan DESC");
    }

    function pembelian($id_reseller){
        return $this->db->query("SELECT sum((b.jumlah*b.harga_jual)-b.diskon) as total FROM rb_penjualan a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan where a.status_penjual='admin' AND a.id_pembeli='".$id_reseller."' AND a.proses='1'");
    }

    function penjualan_perusahaan($id_reseller){
        return $this->db->query("SELECT (sum((a.harga_jual-a.diskon)*a.jumlah)-COALESCE(sum(c.fee/100*(((a.harga_jual-a.diskon)-b.harga_beli)*a.jumlah)),0))-sum(a.fee_produk_end*a.jumlah) as total, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a LEFT JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan JOIN rb_penjualan_otomatis d ON c.kode_transaksi=d.kode_transaksi where c.status_penjual='reseller' AND id_penjual='$id_reseller' AND (c.kode_kurir!='0' OR c.kode_kurir is NULL) AND c.proses='4' AND c.proses!='x' AND d.pembayaran='1'");
    }

    function penjualan_perusahaan_pending($id_reseller){
        return $this->db->query("SELECT (sum((a.harga_jual-a.diskon)*a.jumlah)-COALESCE(sum(c.fee/100*(((a.harga_jual-a.diskon)-b.harga_beli)*a.jumlah)),0))-sum(a.fee_produk_end*a.jumlah) as total, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a LEFT JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan JOIN rb_penjualan_otomatis d ON c.kode_transaksi=d.kode_transaksi where c.status_penjual='reseller' AND id_penjual='$id_reseller' AND (c.kode_kurir!='0' OR c.kode_kurir is NULL) AND c.proses='4' AND c.proses!='x' AND d.pembayaran=null");
    }

    function penjualan($id_reseller){
        return $this->db->query("SELECT sum((a.jumlah*b.harga_beli)-a.diskon) as total, sum(a.jumlah) as produk FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk
                                    JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.status_penjual='reseller' AND id_penjual='".$id_reseller."' AND c.proses>'3'");
    }

    function penjualan_ongkir($id_reseller){
        return $this->db->query("SELECT sum(a.ongkir) as ongkir FROM rb_penjualan a where a.status_penjual='reseller' AND a.id_penjual='".$id_reseller."' AND a.proses>'3'");
    }

    function modal_perusahaan($id_reseller){
        return $this->db->query("SELECT sum(a.jumlah*b.harga_reseller) as total FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.status_pembeli='konsumen' AND c.proses>'3' AND c.id_penjual='".$id_reseller."' AND b.id_produk_perusahaan!='0'");
    }

    function modal_pribadi($id_reseller){
        return $this->db->query("SELECT sum(a.jumlah*b.harga_beli) as total FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.status_pembeli='konsumen' AND c.proses='1' AND c.id_penjual='".$id_reseller."'");
    }

    function produk_perkategori($id_reseller,$id_produk_perusahaan,$id_kategori_produk,$limit){
        return $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                                        LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk
                                        where a.id_reseller!='$id_reseller' AND a.id_kategori_produk='$id_kategori_produk' AND aktif='Y'  GROUP BY a.id_produk ORDER BY RAND() DESC LIMIT $limit");
    }

    function produk_diskon($id_reseller,$id_produk_perusahaan,$limit){
        return $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                                        LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk
                                        where a.id_reseller!='$id_reseller' AND aktif='Y' AND d.diskon>'0' ORDER BY RAND() LIMIT $limit");
    }

    function produk_flashdeal($id_reseller,$id_produk_perusahaan,$limit){
        return $this->db->query("SELECT z.id_produk_penawaran, a.*, b.nama_reseller, c.nama_kota FROM rb_produk_penawaran z JOIN rb_produk a ON z.id_produk=a.id_produk LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                                        where a.id_reseller!='$id_reseller' AND aktif='Y' ORDER BY RAND() LIMIT $limit");
    }

    function produk_terlaris($id_reseller,$id_produk_perusahaan,$limit){
        return $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_penjualan_detail z JOIN rb_produk a ON z.id_produk=a.id_produk
        LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
        LEFT JOIN rb_penjualan x ON z.id_penjualan=x.id_penjualan
            LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk
                where a.id_reseller!='$id_reseller' AND a.id_produk_perusahaan='$id_produk_perusahaan' AND aktif='Y' AND x.status_penjual!='admin' GROUP BY z.id_produk ORDER BY RAND() LIMIT $limit");
    }

    function produk_terbaru($id_reseller,$id_produk_perusahaan,$limit){
        return $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                                        LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk
                                        where a.id_reseller!='$id_reseller' AND aktif='Y' AND a.harga_konsumen!='0' ORDER BY a.waktu_input DESC LIMIT $limit");
    }

    function detail_produk_terbaru($id_reseller,$id_produk_perusahaan,$id_kategori_produk,$limit){
        return $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                                        LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk
                                        where a.id_reseller!='$id_reseller' AND a.id_kategori_produk='$id_kategori_produk' AND aktif='Y' GROUP BY a.id_Produk ORDER BY a.waktu_input DESC LIMIT $limit");
    }
    
    function detail_produk_terkait($id_reseller,$id_produk_perusahaan,$id_kategori_produk,$limit,$id_produk){
        return $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                                        LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk
                                        where a.id_produk!='$id_produk' AND a.id_reseller!='$id_reseller' AND a.id_kategori_produk='$id_kategori_produk' AND aktif='Y' GROUP BY a.id_Produk ORDER BY a.waktu_input DESC LIMIT $limit");
    }

    function produk_terjual($id_produk,$proses){
        return $this->db->query("SELECT * FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where a.id_produk='$id_produk' AND b.proses>3");
    }

    function cari_produk_kode_group($kata,$filter,$darii,$sampaii,$dari,$sampai,$provinsi,$kota,$kecamatan,$kode){
        $urut = cetak($_GET['urut']);
        if ($kata!=''){
            $pisah_kata = explode(" ",$kata);
            $jml_katakan = (integer)count($pisah_kata);
            $jml_kata = $jml_katakan-1;
        }
            $cari = "SELECT * FROM `rb_penjualan` x JOIN rb_penjualan_detail y ON x.id_penjualan=y.id_penjualan JOIN rb_produk a ON y.id_produk=a.id_produk LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller ";
            $cari .= "where x.group_order='$kode' AND a.id_reseller!='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' ";
            
            if (cetak($filter)!='0'){
                $exp = explode('|',cetak($filter));
                if ($exp[0]=='kategori'){
                    $cari .= " AND a.id_kategori_produk='".$exp[1]."'";
                }elseif ($exp[0]=='subkategori'){
                    $cari .= " AND a.id_kategori_produk_sub='".$exp[1]."'";
                }
            }

            if ($provinsi!='0'){ $cari .= " AND b.provinsi_id='$provinsi'"; }
            if ($kota!='0'){ $cari .= " AND b.kota_id='$kota'"; }
            if ($kecamatan!='0'){ $cari .= " AND b.kecamatan_id='$kecamatan'"; }
            if ($darii>'0'){ $cari .= " AND ((a.harga_konsumen-d.diskon) BETWEEN $darii AND $sampaii)"; }
        
            if ($kata!='group'){
                $cari .= " AND";
                for ($i=0; $i<=$jml_kata; $i++){
                $cari .= " a.nama_produk LIKE '%".$pisah_kata[$i]."%' OR a.tag LIKE '%".$pisah_kata[$i]."%'";
                    if ($i < $jml_kata ){
                        $cari .= " OR "; 
                    } 
                }
            }

            if ($urut!=''){
                $cari .= " ORDER BY (a.harga_konsumen-d.diskon) $urut "; 
            }else{
                $cari .= " ORDER BY a.id_produk DESC "; 
            }

            if ($sampai>0){
                $cari .= "LIMIT $dari,$sampai";
            }
            
        return $this->db->query($cari);
    }

    function cari_produk_group($kata,$filter,$darii,$sampaii,$dari,$sampai,$provinsi,$kota,$kecamatan){
        $urut = cetak($_GET['urut']);
        if ($kata!=''){
            $pisah_kata = explode(" ",$kata);
            $jml_katakan = (integer)count($pisah_kata);
            $jml_kata = $jml_katakan-1;
        }
            $cari = "SELECT a.id_reseller, a.id_produk, a.nama_produk, a.produk_seo, a.satuan, x.id_group, x.jumlah_group, x.harga_group as harga_konsumen, a.berat, a.gambar, a.aktif, b.nama_reseller, c.nama_kota FROM rb_produk_group x JOIN rb_produk a ON x.id_produk=a.id_produk LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller LEFT JOIN rb_kota c ON b.kota_id=c.kota_id ";
            $cari .= "where a.id_reseller!='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' ";
            
            if (cetak($filter)!='0'){
                $exp = explode('|',cetak($filter));
                if ($exp[0]=='kategori'){
                    $cari .= " AND a.id_kategori_produk='".$exp[1]."'";
                }elseif ($exp[0]=='subkategori'){
                    $cari .= " AND a.id_kategori_produk_sub='".$exp[1]."'";
                }
            }

            if ($provinsi!='0'){ $cari .= " AND b.provinsi_id='$provinsi'"; }
            if ($kota!='0'){ $cari .= " AND b.kota_id='$kota'"; }
            if ($kecamatan!='0'){ $cari .= " AND b.kecamatan_id='$kecamatan'"; }
            if ($darii>'0'){ $cari .= " AND ((a.harga_konsumen-d.diskon) BETWEEN $darii AND $sampaii)"; }
        
            if ($kata!='group'){
                $cari .= " AND";
                for ($i=0; $i<=$jml_kata; $i++){
                $cari .= " a.nama_produk LIKE '%".$pisah_kata[$i]."%' OR a.tag LIKE '%".$pisah_kata[$i]."%'";
                    if ($i < $jml_kata ){
                        $cari .= " OR "; 
                    } 
                }
            }

            if ($urut!=''){
                $cari .= " ORDER BY (a.harga_konsumen-d.diskon) $urut "; 
            }else{
                $cari .= " ORDER BY a.id_produk DESC "; 
            }

            if ($sampai>0){
                $cari .= "LIMIT $dari,$sampai";
            }
            
        return $this->db->query($cari);
    }

    function cari_produk($kata,$filter,$darii,$sampaii,$dari,$sampai,$provinsi,$kota,$kecamatan){
        $urut = cetak($_GET['urut']);
        if ($kata!=''){
            $pisah_kata = explode(" ",$kata);
            $jml_katakan = (integer)count($pisah_kata);
            $jml_kata = $jml_katakan-1;
        }
            $cari = "SELECT a.*, b.nama_reseller, c.nama_kota, d.diskon FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller LEFT JOIN rb_kota c ON b.kota_id=c.kota_id LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk ";
            $cari .= "where a.id_reseller!='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' ";
            
            if (cetak($filter)!='0'){
                $exp = explode('|',cetak($filter));
                if ($exp[0]=='kategori'){
                    $cari .= " AND a.id_kategori_produk='".$exp[1]."'";
                }elseif ($exp[0]=='subkategori'){
                    $cari .= " AND a.id_kategori_produk_sub='".$exp[1]."'";
                }
            }

            if ($provinsi!='0'){ $cari .= " AND b.provinsi_id='$provinsi'"; }
            if ($kota!='0'){ $cari .= " AND b.kota_id='$kota'"; }
            if ($kecamatan!='0'){ $cari .= " AND b.kecamatan_id='$kecamatan'"; }
            if ($darii>'0'){ $cari .= " AND ((a.harga_konsumen-d.diskon) BETWEEN $darii AND $sampaii)"; }
        
            if ($kata!=''){
                $cari .= " AND";
                for ($i=0; $i<=$jml_kata; $i++){
                $cari .= " a.nama_produk LIKE '%".$pisah_kata[$i]."%' OR a.tag LIKE '%".$pisah_kata[$i]."%'";
                    if ($i < $jml_kata ){
                        $cari .= " OR "; 
                    } 
                }
            }

            if ($urut!=''){
                $cari .= " ORDER BY (a.harga_konsumen-d.diskon) $urut "; 
            }else{
                $cari .= " GROUP BY a.id_produk ORDER BY a.id_produk DESC "; 
            }

            if ($sampai>0){
                $cari .= "LIMIT $dari,$sampai";
            }
            
        return $this->db->query($cari);
    }

    function cari_produk_rekomendasi($kata,$filter){
        $pisah_kata = explode(" ",$kata);
        $jml_katakan = (integer)count($pisah_kata);
        $jml_kata = $jml_katakan-1;
        if (cetak($filter)=='0'){
            $cari = "SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a
            LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                    LEFT JOIN rb_reseller_paket d ON b.id_reseller=d.id_reseller
                    where a.id_reseller!='0' AND  a.id_produk_perusahaan='0' AND a.aktif='Y' AND d.status='Y' AND";
                for ($i=0; $i<=$jml_kata; $i++){
                $cari .= " a.nama_produk LIKE '%".$pisah_kata[$i]."%' OR a.tag LIKE '%".$pisah_kata[$i]."%'";
                    if ($i < $jml_kata ){
                        $cari .= " OR "; 
                    } 
                }
            $cari .= " ORDER BY RAND() DESC LIMIT 20";
        }else{
            $exp = explode('|',cetak($filter));
            if ($exp[0]=='kategori'){
                $cari = "SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a
                LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                        LEFT JOIN rb_reseller_paket d ON b.id_reseller=d.id_reseller
                        where a.id_reseller!='0' AND  a.id_produk_perusahaan='0' AND aktif='Y' AND d.status='Y' AND a.id_kategori_produk='".$exp[1]."' AND";
                for ($i=0; $i<=$jml_kata; $i++){
                $cari .= " a.nama_produk LIKE '%".$pisah_kata[$i]."%'";
                    if ($i < $jml_kata ){
                        $cari .= " OR "; 
                    } 
                }
                $cari .= " ORDER BY RAND() DESC LIMIT 20";
            }elseif ($exp[0]=='subkategori'){
                $cari = "SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a
                LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                        LEFT JOIN rb_reseller_paket d ON b.id_reseller=d.id_reseller
                        where a.id_reseller!='0' AND  a.id_produk_perusahaan='0' AND aktif='Y' AND d.status='Y' AND a.id_kategori_produk_sub='".$exp[1]."' AND";
                for ($i=0; $i<=$jml_kata; $i++){
                $cari .= " a.nama_produk LIKE '%".$pisah_kata[$i]."%'";
                    if ($i < $jml_kata ){
                        $cari .= " OR "; 
                    } 
                }
                $cari .= " ORDER BY RAND() DESC LIMIT 24";
            }else{
                $cari = "SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a
                LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                        LEFT JOIN rb_reseller_paket d ON b.id_reseller=d.id_reseller
                        where a.id_reseller!='0' AND  a.id_produk_perusahaan='0' AND aktif='Y' AND d.status='Y' AND";
                    for ($i=0; $i<=$jml_kata; $i++){
                    $cari .= " a.nama_produk LIKE '%".$pisah_kata[$i]."%'";
                        if ($i < $jml_kata ){
                            $cari .= " OR "; 
                        } 
                    }
                $cari .= " ORDER BY RAND() DESC LIMIT 20";
            }
        }
        return $this->db->query($cari);
    }

    function tampilmessageshome($sampai, $dari){
            return $this->db->query("SELECT MAX(id) AS id, nama_lengkap, id_konsumen, username, foto, email 
                                        from messages join rb_konsumen on messages.user1=rb_konsumen.id_konsumen
                                            where user1!='".cetak($this->session->id_konsumen)."' AND user2='".cetak($this->session->id_konsumen)."' GROUP BY user1 
                                                ORDER BY id DESC LIMIT $dari, $sampai");
    }

    function tampilmessagescontenthome($idm){
        $this->db->select('*');
        $this->db->from('messages');
        $this->db->where('user1',$idm);
        $this->db->where('user2',$this->session->id_konsumen);
        $this->db->order_by('id','DESC');
        $this->db->limit(1, 0);
        return $this->db->get();
    }

    function pesanbelumbaca(){
        $this->db->select('id');
        $this->db->from('messages');
        $this->db->where('stat','1');
        $this->db->where('user1!=',$this->session->id_konsumen);
        $this->db->where('user2',$this->session->id_konsumen);
        return $this->db->get();
    }

    function jumlah(){
        $id_konsumen = $this->session->id_konsumen;
        return $this->db->query("select MAX(id) AS id, MAX(message) AS message, foto, nama_lengkap, email, date_time, user1, user2, sum(stat) as baca, id_konsumen
                                    from messages join rb_konsumen on messages.user1=rb_konsumen.id_konsumen 
                                    where user1!='".$this->db->escape_str($id_konsumen)."' AND user2='".$this->db->escape_str($id_konsumen)."' GROUP BY user1");
    }

    function jumlah_unread(){
        $id_konsumen = $this->session->id_konsumen;
        return $this->db->query("select MAX(id) AS id, MAX(message) AS message, foto, nama_lengkap, email, date_time, user1, user2, sum(stat) as baca, id_konsumen 
                                    from messages join rb_konsumen on messages.user1=rb_konsumen.id_konsumen 
                                    where user1!='".$this->db->escape_str($id_konsumen)."' AND user2='".$this->db->escape_str($id_konsumen)."' AND stat='1' GROUP BY user1");
    }

    function pencarian_messages($cari){
        $id_konsumen = $this->session->id_konsumen;
        return $this->db->query("select MAX(id) AS id, MAX(message) AS message, nama_lengkap, date_time, user1, user2, sum(stat) as baca, id_konsumen, email 
                                    from messages join rb_konsumen on messages.user1=rb_konsumen.id_konsumen 
                                    where user1!='".$this->db->escape_str($id_konsumen)."' AND user2='".$this->db->escape_str($id_konsumen)."' AND nama_lengkap LIKE '%".$this->db->escape_like_str($cari)."%' GROUP BY user1 ORDER BY id DESC LIMIT 10");
    }

    function tampilmessages_unread($sampai, $dari){
        $id_konsumen = $this->session->id_konsumen;
        return $this->db->query("SELECT MAX(id) AS id, nama_lengkap, id_konsumen, username, foto, email 
                                            from messages join rb_konsumen on messages.user1=rb_konsumen.id_konsumen 
                                                where user1!='".$this->db->escape_str($id_konsumen)."' AND user2='".$this->db->escape_str($id_konsumen)."' AND stat='1' GROUP BY user1 
                                                    ORDER BY id DESC LIMIT $dari, $sampai");
    }

    function tampilmessages($sampai, $dari){
        $id_konsumen = $this->session->id_konsumen;
        return $this->db->query("SELECT MAX(id) AS id, nama_lengkap, id_konsumen, username, foto, email 
                                        from messages join rb_konsumen on messages.user1=rb_konsumen.id_konsumen 
                                            where user1!='".$this->db->escape_str($id_konsumen)."' AND user2='".$this->db->escape_str($id_konsumen)."' GROUP BY user1 
                                                ORDER BY id DESC LIMIT $dari, $sampai");
    }

    function jumlahpesan($id){
        $id_konsumen = $this->session->id_konsumen;
        return $this->db->query("SELECT * FROM messages where (user1='".$this->db->escape_str($id_konsumen)."' AND user2='".$this->db->escape_str($id)."')  
                                                        OR (user1='".$this->db->escape_str($id)."' AND user2='".$this->db->escape_str($id_konsumen)."')");
    }

    function modread($id, $sampai, $dari){
    	$id_konsumen = $this->session->id_konsumen;
       		return $this->db->query("SELECT a.user1, a.user2, a.id, a.stat, a.message, a.date_time, a.file_upload, b.id_konsumen, b.foto, b.username, b.nama_lengkap, b.email, c.user_reseller, c.nama_reseller
                                        FROM messages a JOIN rb_konsumen b ON a.user1=b.id_konsumen 
                                        LEFT JOIN rb_reseller c ON b.id_konsumen=c.id_konsumen
                                        where (a.user1='".$this->db->escape_str($id_konsumen)."' AND a.user2='".$this->db->escape_str($id)."') OR (a.user1='".$this->db->escape_str($id)."' AND a.user2='".$this->db->escape_str($id_konsumen)."') 
                                                ORDER BY a.id DESC LIMIT $dari, $sampai");
    }

    public function insert($data){
        $insert = $this->db->insert('messages', $data);
        return $insert ? true : false;
    }












    function produk_terlaris_mall($id_reseller,$id_produk_perusahaan,$limit){
        return $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_penjualan_detail z JOIN rb_produk a ON z.id_produk=a.id_produk
        LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
        LEFT JOIN rb_penjualan x ON z.id_penjualan=x.id_penjualan
            LEFT JOIN rb_kota c ON b.kota_id=c.kota_id 
                LEFT JOIN rb_produk_diskon d ON a.id_produk=d.id_produk
                where a.id_reseller='0' AND a.id_produk_perusahaan='0' AND aktif='Y' AND x.status_penjual='admin' GROUP BY z.id_produk ORDER BY RAND() LIMIT $limit");
    }
}