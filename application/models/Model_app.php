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
class Model_app extends CI_model{
    public function view($table){
        return $this->db->get($table);
    }

    public function insert($table,$data){
        return $this->db->insert($table, $data);
    }

    public function insert_data($data){
        $insert = $this->db->insert('comment', $data);
        return $insert ? true : false;
    }

    public function edit($table, $data){
        return $this->db->get_where($table, $data);
    }
 
    public function update($table, $data, $where){
        return $this->db->update($table, $data, $where); 
    }

    public function delete($table, $where){
        return $this->db->delete($table, $where);
    }

    public function view_where($table,$data){
        $this->db->where($data);
        return $this->db->get($table);
    }

    public function view_ordering_limit($table,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get($table);
    }

    public function view_where_ordering_limit($table,$data,$order,$ordering,$baris,$dari){
        $this->db->select('*');
        $this->db->where($data);
        $this->db->order_by($order,$ordering);
        $this->db->limit($dari, $baris);
        return $this->db->get($table);
    }
    
    public function view_ordering($table,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    public function view_where_ordering($table,$data,$order,$ordering){
        $this->db->where($data);
        $this->db->order_by($order,$ordering);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function view_join_one($table1,$table2,$field,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    public function view_join_where($table1,$table2,$field,$where,$order,$ordering){
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $table1.'.'.$field.'='.$table2.'.'.$field);
        $this->db->where($where);
        $this->db->order_by($order,$ordering);
        return $this->db->get()->result_array();
    }

    function umenu_akses($link,$id){
        return $this->db->query("SELECT * FROM modul,users_modul WHERE modul.id_modul=users_modul.id_modul AND users_modul.id_session='$id' AND modul.link='$link'")->num_rows();
    }

    public function cek_login($username,$password,$table){
        return $this->db->query("SELECT * FROM $table where username='".$this->db->escape_str($username)."' AND password='".$this->db->escape_str($password)."' AND blokir='N'");
    }

    function grafik_kunjungan(){
        return $this->db->query("SELECT count(*) as jumlah, tanggal FROM statistik GROUP BY tanggal ORDER BY tanggal DESC LIMIT 10");
    }

    function kategori_populer($limit){
        return $this->db->query("SELECT * FROM (SELECT a.*, b.jum_dibaca FROM
                                    (SELECT * FROM kategori) as a left join
                                    (SELECT id_kategori, sum(dibaca) as jum_dibaca FROM berita GROUP BY id_kategori) as b on a.id_kategori=b.id_kategori) as c 
                                        where c.aktif='Y' ORDER BY c.jum_dibaca DESC LIMIT $limit");
    }

    function slide(){
        return $this->db->query("SELECT * FROM slide ORDER BY id_slide DESC");
    }

    function slide_tambah(){
        $config['upload_path'] = 'asset/foto_slide/';
        $config['allowed_types'] = 'gif|jpg|png|JPG';
        $config['max_size'] = '3000'; // kb
        $this->load->library('upload', $config);
        $this->upload->do_upload('b');
        $hasil=$this->upload->data();
        if ($hasil['file_name']==''){
            $datadb = array('keterangan'=>$this->input->post('url').'||'.$this->input->post('a'),
                            'posisi'=>$this->input->post('posisi'),
                            'waktu'=>date('Y-m-d H:i:s'));
        }else{
            $datadb = array('keterangan'=>$this->input->post('url').'||'.$this->input->post('a'),
                            'posisi'=>$this->input->post('posisi'),
                            'gambar'=>$hasil['file_name'],
                            'waktu'=>date('Y-m-d H:i:s'));
        }
        $this->db->insert('slide',$datadb);
    }

    function slide_update(){
        $config['upload_path'] = 'asset/foto_slide/';
        $config['allowed_types'] = 'gif|jpg|png|JPG';
        $config['max_size'] = '3000'; // kb
        $this->load->library('upload', $config);
        $this->upload->do_upload('b');
        $hasil=$this->upload->data();
        if ($hasil['file_name']==''){
            $datadb = array('keterangan'=>$this->input->post('url').'||'.$this->input->post('a'),
                            'posisi'=>$this->input->post('posisi'),);
        }else{
            $datadb = array('keterangan'=>$this->input->post('url').'||'.$this->input->post('a'),
                            'posisi'=>$this->input->post('posisi'),
                            'gambar'=>$hasil['file_name']);
        }
        $this->db->where('id_slide',$this->input->post('id'));
        $this->db->update('slide',$datadb);
    }

    function slide_edit($id){
        return $this->db->query("SELECT * FROM slide where id_slide='$id'");
    }

    function slide_delete($id){
        return $this->db->query("DELETE FROM slide where id_slide='$id'");
    }

    function wa($telepon,$message){
        $row = $this->model_app->edit('identitas', array('id_identitas' => 1))->row_array();
        $maps = explode('|',$row['maps']);
        if (trim($maps[2])!=''){
        if (strlen($telepon)>='10'){
            $token = trim($maps[2]);
            if (config('wa_gateway')=='wablas'){
                $curl = curl_init();
                $data = [
                    'phone' => "$telepon",
                    'message' => "$message",
                ];

                curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array(
                        "Authorization: $token",
                    )
                );
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($curl, CURLOPT_URL, config('wa_domain')."/api/send-message");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $result = curl_exec($curl);
                curl_close($curl);

            }else{
                $param = array(
                    "to" => "$token",
                    "data" => array(
                        "message"   => "$message",
                        "schedule"  => date('Y-m-d H:i:s'), // opsional
                        "number"    => "$telepon",
                        "whatsapp"  => "personal" // bisnis, auto
                    )
                );
                $data_json=json_encode($param, 1);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_json);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.config('wa_domain'))); //woowandroid
                $response = curl_exec($ch);
                curl_close($ch);
            }
        }
        }
    }

    public function pusat_bantuan_diskusi($data){
        $insert = $this->db->insert('rb_pusat_bantuan_diskusi', $data);
        return $insert ? true : false;
    }

    public function import_excel_produk($directory,$filename){
        ini_set('memory_limit', '-1');
        $inputFileName = './asset/'.$directory.'/'.$filename;
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file :' . $e->getMessage());
        }

        $worksheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $numRows = count($worksheet);

        for ($i=2; $i < ($numRows+1) ; $i++) { 
            $data1 = $worksheet[$i]['A'];
            $data2 = $worksheet[$i]['B'];
            $data3 = trim($worksheet[$i]['C']);
            $data4 = trim($worksheet[$i]['D']);
            $data5 = trim($worksheet[$i]['E']);
            $data6 = trim($worksheet[$i]['F']);
            $data7 = trim($worksheet[$i]['G']);
            $data8 = trim($worksheet[$i]['H']);
            $data9 = trim($worksheet[$i]['I']);
            $data10 = trim($worksheet[$i]['J']);
            $data11 = trim($worksheet[$i]['K']);
            $data12 = trim($worksheet[$i]['L']);
            $data13 = trim($worksheet[$i]['M']);
            $data14 = trim($worksheet[$i]['N']);
            $data15 = trim($worksheet[$i]['O']);
            $data16 = trim($worksheet[$i]['P']);
            $data17 = trim($worksheet[$i]['Q']);
            $data18 = trim($worksheet[$i]['R']);

            $diskon = trim($worksheet[$i]['S']);
            $stok = trim($worksheet[$i]['T']);
            $produk_file = trim($worksheet[$i]['U']);

            $toko = $this->db->query("SELECT b.id_reseller FROM rb_konsumen a JOIN rb_reseller b ON a.id_konsumen=b.id_konsumen where a.email='$data1'")->row_array();

            if ($toko['id_reseller']==''){
                $id_reseller = 0;
            }else{
                $id_reseller = $toko['id_reseller'];
            }
        
            if ($data2!=''){
                $data_produk = array('id_reseller'=>$id_reseller,
                        'id_kategori_produk'=>$data2,
                        'id_kategori_produk_sub'=>$data3,
                        'nama_produk'=>$data4,
                        'produk_seo'=>seo_title($data4),
                        'satuan'=>$data5,
                        'harga_beli'=>simpan_rupiah($data6),
                        'harga_reseller'=>simpan_rupiah($data7),
                        'harga_konsumen'=>simpan_rupiah($data8),
                        'minimum'=>$data9,
                        'sku'=>$data10,
                        'berat'=>$data11,
                        'tentang_produk'=>$data12,
                        'keterangan'=>$data13,
                        'aktif'=>$data14,
                        'jenis_produk'=>$data15,
                        'gambar'=>$data16,
                        'tag'=>$data17,
                        'pre_order'=>$data18,
                        'produk_file'=>($produk_file==''?NULL:$produk_file),
                        'username'=>$this->session->username,
                        'waktu_input'=>date('Y-m-d H:i:s'));
                $this->db->insert('rb_produk', $data_produk);
                $id_produk = $this->db->insert_id();

                if (simpan_rupiah(cetak($diskon)) > 0){
                    $cek = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='$id_produk' AND id_reseller='$id_reseller'");
                    if ($cek->num_rows()>=1){
                        $datadis = array('diskon'=>simpan_rupiah(cetak($diskon)));
                        $wheredis = array('id_produk' => cetak($id_produk),'id_reseller' => cetak($id_reseller));
                        $this->db->update('rb_produk_diskon', $datadis, $wheredis);
                    }else{
                        $datadis = array('id_produk'=>cetak($id_produk),
                                        'id_reseller'=>cetak($id_reseller),
                                        'diskon'=>simpan_rupiah(cetak($diskon)));
                        $this->db->insert('rb_produk_diskon',$datadis);
                    }
                }

                if ($stok!='' OR $stok!='0'){
                if ($id_reseller=='0'){
                    $kode_transaksi = "PO-".date('YmdHis');
                    $datax1 = array('kode_pembelian'=>$kode_transaksi,
                                    'id_supplier'=>1,
                                    'waktu_beli'=>date('Y-m-d H:i:s'));
                    $this->db->insert('rb_pembelian',$datax1);
                    $idp = $this->db->insert_id();

                    $datax2 = array('id_pembelian'=>$idp,
                                    'id_produk'=>$id_produk,
                                    'jumlah_pesan'=>$stok,
                                    'harga_pesan'=>simpan_rupiah($data6),
                                    'satuan'=>cetak($data5));
                    $this->db->insert('rb_pembelian_detail',$datax2);
                }else{
                    $kode_transaksi = "TRX-".date('YmdHis');
                    $dataxx = array('kode_transaksi'=>$kode_transaksi,
                                'id_pembeli'=>$id_reseller,
                                'id_penjual'=>'1',
                                'status_pembeli'=>'reseller',
                                'status_penjual'=>'admin',
                                'service'=>'Stok Otomatis (Pribadi)',
                                'waktu_transaksi'=>date('Y-m-d H:i:s'),
                                'proses'=>'4');
                    $this->db->insert('rb_penjualan',$dataxx);
                    $idp = $this->db->insert_id();

                    $datae = array('id_penjualan'=>$idp,
                                'id_produk'=>$id_produk,
                                'jumlah'=>cetak($stok),
                                'harga_jual'=>cetak($data7),
                                'satuan'=>cetak($data5));
                    $this->db->insert('rb_penjualan_detail',$datae);
                }
                }
            }

        }
    }
    
    
    // batrisyia
    function batrisyia_link(){
        return $this->db->query("SELECT * FROM banner_apps where posisi = 'batrisyia' ORDER BY tgl_posting DESC");
    }

    function batrisyia_link_tambah(){
        $config['upload_path'] = 'asset/batrisyia_link/';
        $config['allowed_types'] = 'gif|jpg|png|JPG';
        $config['max_size'] = '3000'; // kb
        $this->load->library('upload', $config);
        $this->upload->do_upload('b');
        $hasil=$this->upload->data();
        if ($hasil['file_name']==''){
            $datadb = array('keterangan'=>$this->input->post('a'),
                            'url'=>$this->input->post('url'),
                            'posisi'=>'batrisyia',
                            'tgl_posting'=>date('Y-m-d H:i:s'));
        }else{
            $datadb = array('keterangan'=>$this->input->post('a'),
                            'url'=>$this->input->post('url'),
                            'posisi'=>'batrisyia',
                            'gambar'=>$hasil['file_name'],
                            'tgl_posting'=>date('Y-m-d H:i:s'));
        }
        $this->db->insert('banner_apps',$datadb);
    }

    function batrisyia_link_update(){
        $config['upload_path'] = 'asset/batrisyia_link/';
        $config['allowed_types'] = 'gif|jpg|png|JPG';
        $config['max_size'] = '3000'; // kb
        $this->load->library('upload', $config);
        $this->upload->do_upload('b');
        $hasil=$this->upload->data();
        if ($hasil['file_name']==''){
            $datadb = array('keterangan'=>$this->input->post('a'),
                            'url'=>$this->input->post('url'),);
        }else{
            $datadb = array('keterangan'=>$this->input->post('a'),
                            'url'=>$this->input->post('url'),
                            'gambar'=>$hasil['file_name']);
        }
        $this->db->where('id_banner',$this->input->post('id'));
        $this->db->update('banner_apps',$datadb);
    }

    function batrisyia_link_edit($id){
        return $this->db->query("SELECT * FROM banner_apps where id_banner='$id'");
    }

    function batrisyia_link_delete($id){
        return $this->db->query("DELETE FROM banner_apps where id_banner='$id'");
    }
}