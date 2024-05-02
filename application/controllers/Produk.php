<?php
/*
-- ---------------------------------------------------------------
-- TAJALAPAK MARKETPLACE PRO MULTI BUYER MULTI SELLER + SUPPORT RESELLER SYSTEM
-- CREATED BY : ROBBY PRIHANDAYA (0812-6777-1344)
-- COPYRIGHT  : Copyright (c) 2018 - 2021, PHPMU.COM. (https://phpmu.com/)
-- LICENSE    : Commercial Software, (Hanya untuk 1 domain)
-- CREATED ON : 2019-03-26
-- UPDATED ON : 2023-10-01
-- ---------------------------------------------------------------
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Produk extends CI_Controller {
	function index(){
		if (isset($_GET['s'])){
			$darii = (int)clean_rupiah(cetak($this->input->get('dari')));
			$sampaii = (int)clean_rupiah(cetak($this->input->get('sampai')));
			$provinsi = (int)clean_rupiah(cetak($this->input->get('provinsi')));
			$kota = (int)clean_rupiah(cetak($this->input->get('kota')));
			$kecamatan = (int)clean_rupiah(cetak($this->input->get('kecamatan')));
			if (cetak($_GET['s'])=='group'){
				if (isset($_POST['submit'])){
					$jumlah= $this->model_reseller->cari_produk_group(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,0,$provinsi,$kota,$kecamatan)->num_rows();
				}else{
					$jumlah= $this->model_reseller->cari_produk_group(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,0,$provinsi,$kota,$kecamatan)->num_rows();
				}
			}else{
				$jumlah= $this->model_reseller->cari_produk(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,0,$provinsi,$kota,$kecamatan)->num_rows();
			}
		}else{
			$jumlah= $this->db->query("SELECT a.* FROM rb_produk a where a.id_reseller!='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y'")->num_rows();
		}
		
		$data['jumlah'] = $jumlah;
		$config['base_url'] = base_url().'produk/index';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 32; 	

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}


		if (is_numeric($dari)) {
			if (isset($_GET['s'])){
				$data['description'] = description();
				$data['keywords'] = keywords();
				if (cetak($_GET['s'])=='group'){
					$data['title'] = "Produk - Group Order";
					if (isset($_POST['submit'])){
						$data['record'] = $this->model_reseller->cari_produk_kode_group(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,$config['per_page'],$provinsi,$kota,$kecamatan,cetak($this->input->post('group')));
					}else{
						$data['record'] = $this->model_reseller->cari_produk_group(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,$config['per_page'],$provinsi,$kota,$kecamatan);
					}
				}else{
					$data['title'] = "Pencarian Produk";
					$data['record'] = $this->model_reseller->cari_produk(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,$config['per_page'],$provinsi,$kota,$kecamatan);
				}
				$data['rekomendasi'] = $this->model_reseller->cari_produk_rekomendasi(cetak($this->input->get('s')),cetak($this->input->get('f')));
			}else{
				$data['title'] = title();
				$data['judul'] = 'Semua Produk Kami';
				$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
									LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller!='0' AND a.aktif='Y' ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
			}
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_produk',$data);
		}else{
			redirect('main');
		}
	}
	
	function video(){
	    cek_session_members();
	    $this->load->library('user_agent');
        $mobile=$this->agent->is_mobile();
        if($mobile){
    		if (isset($_GET['watch'])){
    		    $data['records'] = $this->db->query("SELECT a.*, b.produk_seo, b.nama_produk FROM rb_produk_video a JOIN rb_produk b ON a.id_produk=b.id_produk where id_produk_video='".cetak($_GET['watch'])."'");
    		    $row = $data['records']->row_array();
    		    $data['title'] = "Video - ".$row['nama_produk'];
        		$data['description'] = description();
        		$data['keywords'] = keywords();
    		}else{
    		    $data['records'] = $this->db->query("SELECT a.*, b.produk_seo FROM rb_produk_video a JOIN rb_produk b ON a.id_produk=b.id_produk ORDER BY RAND() DESC LIMIT 10");
    		    $data['title'] = 'Video Produk';
        		$data['description'] = description();
        		$data['keywords'] = keywords();
    		}
    		$this->template->load(template().'/template',template().'/video',$data);
        }else{
            echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>GAGAL - Halaman tersebut khusus akses mobile!</center></div>');
			redirect('members/profile');
        }
	}

	function mall(){
		if (isset($_GET['s'])){
			$darii = (int)clean_rupiah(cetak($this->input->get('dari')));
			$sampaii = (int)clean_rupiah(cetak($this->input->get('sampai')));
			$provinsi = (int)clean_rupiah(cetak($this->input->get('provinsi')));
			$kota = (int)clean_rupiah(cetak($this->input->get('kota')));
			$kecamatan = (int)clean_rupiah(cetak($this->input->get('kecamatan')));
			if (cetak($_GET['s'])=='group'){
				if (isset($_POST['submit'])){
					$jumlah= $this->model_reseller->cari_produk_group_mall(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,0,$provinsi,$kota,$kecamatan)->num_rows();
				}else{
					$jumlah= $this->model_reseller->cari_produk_group_mall(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,0,$provinsi,$kota,$kecamatan)->num_rows();
				}
			}else{
				$jumlah= $this->model_reseller->cari_produk_mall(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,0,$provinsi,$kota,$kecamatan)->num_rows();
			}
		}else{
			$jumlah= $this->db->query("SELECT a.* FROM rb_produk a where a.id_reseller='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y'")->num_rows();
		}
		
		$data['jumlah'] = $jumlah;
		$config['base_url'] = base_url().'produk/mall';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 32; 	

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}


		if (is_numeric($dari)) {
			if (isset($_GET['s'])){
				$data['description'] = description();
				$data['keywords'] = keywords();
				if (cetak($_GET['s'])=='group'){
					$data['title'] = "Produk Mall - Group Order";
					if (isset($_POST['submit'])){
						$data['record'] = $this->model_reseller->cari_produk_kode_group_mall(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,$config['per_page'],$provinsi,$kota,$kecamatan,cetak($this->input->post('group')));
					}else{
						$data['record'] = $this->model_reseller->cari_produk_group_mall(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,$config['per_page'],$provinsi,$kota,$kecamatan);
					}
				}else{
					$data['title'] = "Pencarian Produk";
					$data['record'] = $this->model_reseller->cari_produk_mall(cetak($this->input->get('s')),cetak($this->input->get('f')),$darii,$sampaii,$dari,$config['per_page'],$provinsi,$kota,$kecamatan);
				}
				$data['rekomendasi'] = $this->model_reseller->cari_produk_rekomendasi_mall(cetak($this->input->get('s')),cetak($this->input->get('f')));
			}else{
				$data['title'] = title();
				$data['judul'] = 'Produk Mall';
				$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
									LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller='0' AND a.aktif='Y' ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
			}
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_produk_mall',$data);
		}else{
			redirect('main');
		}
	}

	function perusahaan(){
		if (reseller($this->session->id_konsumen)==''){
			echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Halaman tersebut khusus untuk Reseller/Pelapak,..</center></div>');
			redirect('main');
		}else{
			if ($this->input->get('kata')){
				$jumlah= $this->db->query("SELECT a.* FROM rb_produk a where a.id_reseller='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' AND a.harga_reseller>0 AND a.nama_produk LIKE '%".cetak($this->input->get('kata'))."%'")->num_rows();
			}else{
				$jumlah= $this->db->query("SELECT a.* FROM rb_produk a where a.id_reseller='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' AND a.harga_reseller>0")->num_rows();
			}
			
			
			$config['base_url'] = base_url().'produk/perusahaan';
			$config['total_rows'] = $jumlah;
			$config['per_page'] = 12; 	
			if ($this->uri->segment('3')==''){
				$dari = 0;
			}else{
				$dari = $this->uri->segment('3');
			}

			if (is_numeric($dari)) {
				if (isset($_GET['kata'])){
					$data['title'] = "Pencarian - ''".cetak($this->input->get('kata'))."''";
					$data['description'] = description();
					$data['keywords'] = keywords();
					$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
										LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' AND a.harga_reseller>0 AND a.nama_produk LIKE '%".cetak($this->input->get('kata'))."%' ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
				}else{
					$data['description'] = description();
					$data['keywords'] = keywords();
					$data['title'] = title();
					$data['judul'] = 'Semua Produk Perusahaan';
					$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
										LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' AND a.harga_reseller>0 ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
					
				}
				$this->pagination->initialize($config);
				$this->template->load(template().'/template',template().'/reseller/view_produk_perusahaan',$data);
			}else{
				redirect('main');
			}
		}
	}

	function kategori(){
		$cek = $this->model_app->edit('rb_kategori_produk',array('kategori_seo'=>$this->uri->segment(3)))->row_array();
		$jumlah= $this->model_app->view_where('rb_produk',array('id_kategori_produk'=>$cek['id_kategori_produk']))->num_rows();
		$config['base_url'] = base_url().'produk/kategori/'.$this->uri->segment(3);
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 32; 	
		if ($this->uri->segment('4')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('4');
		}

		if (is_numeric($dari)) {
			$data['title'] = "Kategori / $cek[nama_kategori]";
			$data['judul'] = "Kategori / $cek[nama_kategori]";
			if ($cek['mkolom']=='' OR $cek['mkolom']=='0'){
				$data['kolom'] = "6";
			}else{
				$data['kolom'] = $cek['mkolom'];
			}
			$data['description'] = description();
			$data['keywords'] = keywords();
			$this->pagination->initialize($config);
			$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller!='0' AND a.id_produk_perusahaan='0' AND a.id_kategori_produk='$cek[id_kategori_produk]' AND a.aktif='Y' ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_kategori_all',$data);
		}else{
			redirect('main');
		}
	}

	function subkategori(){
		$cek = $this->model_app->edit('rb_kategori_produk_sub',array('kategori_seo_sub'=>$this->uri->segment(3)))->row_array();
		
		$jumlah= $this->model_app->view_where('rb_produk',array('id_kategori_produk_sub'=>$cek['id_kategori_produk_sub']))->num_rows();
		$config['base_url'] = base_url().'produk/subkategori/'.$this->uri->segment(3);
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 32; 	
		if ($this->uri->segment('4')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('4');
		}

		if (is_numeric($dari)) {
			$data['title'] = "Subkategori / $cek[nama_kategori_sub]";
			$data['judul'] = "Subkategori / $cek[nama_kategori_sub]";
			if ($cek['mkolom_sub']=='' OR $cek['mkolom_sub']=='0'){
				$data['kolom'] = "6";
			}else{
				$data['kolom'] = $cek['mkolom_sub'];
			}
			$data['description'] = description();
			$data['keywords'] = keywords();
			$this->pagination->initialize($config);
			$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
                                    LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller!='0' AND a.id_produk_perusahaan='0' AND a.id_kategori_produk_sub='$cek[id_kategori_produk_sub]' AND a.aktif='Y' ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_kategori_all',$data);
		}else{
			redirect('main');
		}
	}
	
	function merek(){
		$jumlah= $this->model_app->view('tagpro')->num_rows();
		$config['base_url'] = base_url().'produk/merek';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 40; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}

		if (is_numeric($dari)) {
			$data['title'] = "Semua Merek";
			$data['judul'] = "Semua Merek";
			$data['description'] = description();
			$data['keywords'] = keywords();
			$this->pagination->initialize($config);
			$data['record'] = $this->db->query("SELECT * FROM tagpro ORDER BY nama_tag ASC LIMIT $dari,$config[per_page]");
			$data['records'] = $this->db->query("SELECT * FROM tagpro");
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_merek',$data);
		}else{
			redirect('main');
		}
	}


	function reseller(){
		if (config('mode')=='marketplace'){
			$jumlah= $this->db->query("SELECT * FROM (SELECT a.*,b.jumproduk FROM (SELECT * FROM rb_reseller where id_konsumen!='0') as a LEFT JOIN
			(SELECT id_reseller, COUNT(*) jumproduk FROM rb_produk where aktif='Y' GROUP BY id_reseller HAVING COUNT(id_reseller)) as b on a.id_reseller=b.id_reseller) x where x.jumproduk>0")->num_rows();
			$config['base_url'] = base_url().'produk/reseller';
			$config['total_rows'] = $jumlah;
			$config['per_page'] = 15; 	
			if ($this->uri->segment('3')==''){
				$dari = 0;
			}else{
				$dari = $this->uri->segment('3');
			}

			if (is_numeric($dari)) {
				$data['title'] = 'Semua Daftar Pelapak';
				$data['description'] = description();
				$data['keywords'] = keywords();
				$this->pagination->initialize($config);
				if (isset($_POST['submit'])){
					$data['record'] = $this->model_reseller->cari_reseller(filter($this->input->post('cari_reseller')));
				}elseif (isset($_GET['s'])){
					$data['record'] = $this->model_reseller->cari_reseller(filter($this->input->get('s')));
				}else{
					$data['record'] = $this->db->query("SELECT * FROM (SELECT a.*,b.jumproduk FROM (SELECT * FROM rb_reseller where id_konsumen!='0' AND verifikasi='Y') as a LEFT JOIN
					(SELECT id_reseller, COUNT(*) jumproduk FROM rb_produk where aktif='Y' GROUP BY id_reseller HAVING COUNT(id_reseller)) as b on a.id_reseller=b.id_reseller) x where x.jumproduk>0 ORDER BY id_reseller DESC LIMIT $dari,$config[per_page]");
				}
				$this->template->load(template().'/template',template().'/reseller/view_reseller',$data);
			}else{
				redirect('main');
			}
		}else{
			echo "Akses untuk Halaman ini ditutup...";
		}
	}

	

	function detail_reseller(){
		$data['title'] = 'Detail Profile Reseller';
		$data['description'] = description();
		$data['keywords'] = keywords();
		$id = $this->uri->segment(3);
		$data['rows'] = $this->model_app->edit('rb_reseller',array('id_reseller'=>$id))->row_array();
		$data['record'] = $this->model_reseller->penjualan_list_konsumen($id,'reseller');
		$data['rekening'] = $this->model_app->view_where('rb_rekening_reseller',array('id_reseller'=>$id));
		$this->template->load(template().'/template',template().'/reseller/view_reseller_detail',$data);
	}

	function produk_reseller(){
		$query = $this->db->query("SELECT id_reseller, nama_reseller, keterangan FROM rb_reseller where user_reseller='".cetak($this->uri->segment(2))."' AND hapus_toko='N'");
		if ($query->num_rows()<=0){
			$qr = $this->db->query("SELECT nama_reseller FROM rb_reseller where user_reseller='".cetak($this->uri->segment(2))."'")->row_array();
			echo $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible' style='padding:5px; border-left:0px solid'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><center>$qr[nama_reseller] Non-aktif..</center></div>");
			redirect('produk');
		}

		if (isset($_GET['k'])){
			$kt = $this->model_app->view_where('rb_kategori_produk',array('kategori_seo'=>cetak($_GET['k'])))->row_array();
			$data['kategorix'] = "''$kt[nama_kategori]''";
		}else{
			$data['kategorix'] = "";
		}

		$row = $query->row_array();
		$id = cetak($row['id_reseller']);
		if (isset($_GET['s'])){
			if (isset($_GET['k'])){
				$jumlah = $this->db->query("SELECT * FROM rb_produk where id_reseller='$id' AND id_kategori_produk='$kt[id_kategori_produk]' AND nama_produk LIKE '%".cetak($_GET['s'])."%'")->num_rows();
			}else{
				$jumlah = $this->db->query("SELECT * FROM rb_produk where id_reseller='$id' AND nama_produk LIKE '%".cetak($_GET['s'])."%'")->num_rows();
			}
		}else{
			if (isset($_GET['k'])){
				$jumlah= $this->model_app->view_where('rb_produk',array('id_reseller'=>$id,'id_kategori_produk'=>$kt['id_kategori_produk']))->num_rows();
			}else{
				$jumlah= $this->model_app->view_where('rb_produk',array('id_reseller'=>$id))->num_rows();
			}
		}
		$config['base_url'] = base_url().'u/'.$this->uri->segment('2');
		$config['total_rows'] = $jumlah;
		if ($id=='0'){
			$config['per_page'] = 24; 	
		}else{
			$config['per_page'] = 16; 
		}

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}

		$data['title'] = "$row[nama_reseller]";
		$data['description'] = cetak($row['keterangan']);
		$data['keywords'] = keywords();

		if (is_numeric($dari)) {
			$data['rows'] = $this->db->query("SELECT a.*, b.nama_kota FROM rb_reseller a JOIN rb_kota b ON a.kota_id=b.kota_id where a.id_reseller='$id'")->row_array();
			if (isset($_GET['s'])){
				if (isset($_GET['k'])){
					$data['record'] = $this->db->query("SELECT * FROM rb_produk where id_reseller='$id' AND id_kategori_produk='$kt[id_kategori_produk]' AND nama_produk LIKE '%".cetak($_GET['s'])."%' ORDER BY id_produk DESC LIMIT $dari,$config[per_page]");
					$data['record_hitung'] = $this->db->query("SELECT * FROM rb_produk where id_reseller='$id' AND id_kategori_produk='$kt[id_kategori_produk]' AND nama_produk LIKE '%".cetak($_GET['s'])."%'");
				}else{
					$data['record'] = $this->db->query("SELECT * FROM rb_produk where id_reseller='$id' AND nama_produk LIKE '%".cetak($_GET['s'])."%' ORDER BY id_produk DESC LIMIT $dari,$config[per_page]");
					$data['record_hitung'] = $this->db->query("SELECT * FROM rb_produk where id_reseller='$id' AND nama_produk LIKE '%".cetak($_GET['s'])."%'");
				}
			}else{
				if (isset($_GET['k'])){
					$data['record'] = $this->model_app->view_where_ordering_limit('rb_produk',array('id_reseller'=>$id,'id_kategori_produk'=>$kt['id_kategori_produk'],'aktif'=>'Y'),'id_produk','DESC',$dari,$config['per_page']);
					$data['record_hitung'] = $this->model_app->view_where('rb_produk',array('id_reseller'=>$id,'id_kategori_produk'=>$kt['id_kategori_produk'],'aktif'=>'Y'));
				}else{
					$data['record'] = $this->model_app->view_where_ordering_limit('rb_produk',array('id_reseller'=>$id,'aktif'=>'Y'),'id_produk','DESC',$dari,$config['per_page']);
					$data['record_hitung'] = $this->model_app->view_where('rb_produk',array('id_reseller'=>$id,'aktif'=>'Y'));
				}
			}
			$this->pagination->initialize($config);
			if ($id=='0'){
				$this->template->load(template().'/template',template().'/reseller/view_reseller_mall',$data);
			}else{
				$this->template->load(template().'/template',template().'/reseller/view_reseller_produk',$data);
			}
		}else{
			redirect('main');
		}
	}

	function reseller_cari_produk(){
		$keyword = cetak($this->input->post('query'));
		$data['record'] = $this->db->query("SELECT * FROM rb_produk where id_reseller='".cetak($this->input->post('id'))."' AND aktif='Y' AND nama_produk LIKE '%$keyword%'");
		$this->load->view(template().'/reseller/view_reseller_produk_cari',$data);
	}

	function checked_produk(){
		$data = array('checked'=>$this->input->post('valuex'));
		$where = array('id_penjualan_detail' => $this->input->post('id'));
		$this->model_app->update('rb_penjualan_temp', $data, $where);
	}

	function qty_produk(){
		$prd = $this->db->query("SELECT b.* FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_level='0' AND a.id_penjualan_detail='".cetak($this->input->post('id'))."'")->row_array();
		if (cetak($this->input->post('qty'))<=stok($prd['id_reseller'],$prd['id_produk'])){
			$data = array('jumlah'=>$this->input->post('qty'));
			$where = array('id_penjualan_detail' => $this->input->post('id'));
			$this->model_app->update('rb_penjualan_temp', $data, $where);
			echo json_encode(array('pesan'=>'Berhasil update Data','value'=>'1'));
		}else{
			echo json_encode(array('pesan'=>'Maaf Stok Tidak mencukupi.','value'=>'0'));
		}
	}

	function qty_produkx(){
		$prd = $this->db->query("SELECT * FROM rb_produk where id_produk='".$this->input->post('id')."'")->row_array();
		$krj = $this->db->query("SELECT sum(jumlah) as jumlah FROM rb_penjualan_temp where id_level='0' AND session='".cetak($this->session->idp)."' AND id_produk='".$this->input->post('id')."'")->row_array();
		if (cetak($this->input->post('qty')+$krj['jumlah'])<=stok($prd['id_reseller'],$prd['id_produk'])){
			echo json_encode(array('pesan'=>'Berhasil update Data','value'=>'1'));
		}else{
			echo json_encode(array('pesan'=>'Maaf Stok Tidak mencukupi.','value'=>'0'));
		}
	}

	function qty_produk_perusahaan(){
		$p = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>$this->input->post('id')))->row_array();
		$resp = $this->model_app->view_where('rb_produk',array('id_produk'=>$p['id_produk']))->row_array();
		if (cetak($this->input->post('qty'))>=$resp['minimum']){
			$jumlah = cetak($this->input->post('qty'));
			echo json_encode(array('pesan'=>"x"));
		}else{
			$jumlah = $resp['minimum'];
			echo json_encode(array('pesan'=>"Minimal Order Produk ini $resp[minimum] $resp[satuan]"));
		}

		$ex = explode('||',$p['keterangan_order']);
		if ($ex[1]!=''){
			$keterangan_order = $this->input->post('ctt').'||'.$ex[1];
		}else{
			$keterangan_order = $this->input->post('ctt').'||';
		}

		$data = array('jumlah'=>$jumlah,'keterangan_order'=>$keterangan_order);

		$where = array('id_penjualan_detail' => $this->input->post('id'));
		$this->model_app->update('rb_penjualan_detail', $data, $where);
	}

	function keranjang(){
		$ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
		$fv = explode('|',$ref['keterangan']);

		if (isset($_GET['del'])){
			$this->db->query("DELETE FROM rb_penjualan_temp where id_penjualan_detail='".cetak($this->input->get('del'))."' AND session='".$this->session->idp."'");
			redirect('produk/keranjang');
		}

		// if ($fv[2]=='enable'){
		// 	if ($this->session->id_konsumen==''){
		// 	echo $this->session->set_flashdata('message', '<div class="alert alert-info"><center><b>INFORMASI</b> - Anda Harus Login untuk Order Produk.</center></div>');
		// 	redirect('auth/login');
		// 	}
		// }

		$id_reseller = cetak($this->uri->segment(3));
		if ($this->session->id_konsumen!=''){
			$cek_penjual = $this->db->query("SELECT id_konsumen, user_reseller FROM rb_reseller where id_reseller='$id_reseller'")->row_array();
			if ($cek_penjual['id_konsumen']==$this->session->id_konsumen){
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center><b>Woiii</b> - Jangan Belanja ditoko Sendiri!</center></div>');
				redirect('u/'.$cek_penjual['user_reseller']);
			}
		}
		
		$id_produk   = cetak($this->uri->segment(4));
		$j = $this->model_reseller->jual_reseller($id_reseller,$id_produk)->row_array();
        $b = $this->model_reseller->beli_reseller($id_reseller,$id_produk)->row_array();
        $stok = stok($id_reseller,$id_produk);
		//$stok = $b['beli']-$j['jual'];

		$cek_minimum = $this->db->query("SELECT nama_produk, minimum, satuan FROM rb_produk where id_produk='$id_produk'")->row_array();
		if ($cek_minimum['minimum']<=cetak($this->input->post('qty'))){
			$qty = $this->input->post('qty');
		}else{
			if ($cek_minimum['minimum']>1){
				echo $this->session->set_flashdata('message', '<div class="alert alert-info"><center><b>PENTING</b> - Minimal Order untuk <b>'.$cek_minimum['nama_produk'].'</b> adalah '.$cek_minimum['minimum'].' '.$cek_minimum['satuan'].'</center></div>');
			}
			$qty = $cek_minimum['minimum'];
		}
		
		if (isset($_POST['update'])){
			$i = 1;
			$keranjang = $this->db->query("SELECT * FROM rb_penjualan_temp where session='".$this->session->idp."' AND id_level='0' ORDER BY id_penjualan_detail DESC");
			foreach ($keranjang->result_array() as $row){
				$cek_minimumx = $this->db->query("SELECT id_reseller, nama_produk, minimum, satuan FROM rb_produk where id_produk='".cetak($this->input->post('idp'.$i))."'")->row_array();
				if ($cek_minimumx['minimum']<=cetak($this->input->post('qty'.$i))){
					$qtyx = cetak($this->input->post('qty'.$i));
				}else{
					$qtyx = $cek_minimumx['minimum'];
				}

				if (stok($cek_minimumx['id_reseller'],cetak($this->input->post('idp'.$i)))>=$qtyx){
					$qtyx_set = $qtyx;
				}else{
					$qtyx_set = stok($cek_minimumx['id_reseller'],cetak($this->input->post('idp'.$i)));
				}

				$dataa = array('jumlah'=>$qtyx_set,
							  'catatan'=>cetak($this->input->post('keterangan'.$i)));
                $wheree = array('id_penjualan_detail' => cetak($this->input->post('id'.$i)));
				$this->model_app->update('rb_penjualan_temp',$dataa,$wheree);
				
				$i++;
			}
			redirect('produk/checkouts');
		}

		if ($id_produk!=''){
			$harga = $this->model_app->view_where('rb_produk',array('id_produk'=>$id_produk))->row_array();
			$cek_keranjang = $this->db->query("SELECT a.session, b.jenis_produk FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->id_konsumen."' AND b.jenis_produk!='$harga[jenis_produk]'");
			if ($cek_keranjang->num_rows()<=0){
				if ($stok < $qty){
					$produk = $this->model_app->edit('rb_produk',array('id_produk'=>$id_produk))->row_array();
					$produk_cek = filter($produk['nama_produk']);
					echo "<script>window.alert('Stok untuk Produk $produk_cek pada Pelapak ini telah habis, silahkan menunggu atau order dengan pelapak lain!');
									window.location=('".base_url()."/produk/produk_reseller/$id_reseller')</script>";
				}else{
					$this->session->unset_userdata('produk');
					if ($this->session->idp == ''){
						if ($this->session->id_konsumen!=''){ $idp = $this->session->id_konsumen; }else{ $idp = date('YmdHis'); }
						$this->session->set_userdata(array('idp'=>$idp,'reseller'=>$id_reseller));
					}

					$reseller_cek = $this->db->query("SELECT b.id_reseller FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where session='".$this->session->idp."' AND b.id_reseller!='$id_reseller' GROUP BY b.id_reseller");
					if ($reseller_cek->num_rows()<'3'){
						$var1 = cetak($this->input->post('variasi_1'));
						$var2 = cetak($this->input->post('variasi_2'));
						$var3 = cetak($this->input->post('variasi_3'));
						$variasi_nominal = cetak($this->input->post('warnax')+$this->input->post('ukuranx')+$this->input->post('lainnyax'));

						if (cetak($this->input->post('id_level'))!='0'){
							$level = $this->model_app->view_where('rb_produk_level',array('id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level'))))->row_array();
							$harga_level = ($level['harga_level']/$level['qty_level'])+$variasi_nominal;
							$cek = $this->model_app->view_where('rb_penjualan_temp',array('session'=>$this->session->idp,'id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level')),'harga_jual'=>$harga_level))->num_rows();
						}else{
							$cek = $this->model_app->view_where('rb_penjualan_temp',array('session'=>$this->session->idp,'id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level'))))->num_rows();
						}

						if ($cek >=1){
							if (cetak($this->input->post('id_level'))!='0'){
								$this->db->query("UPDATE rb_penjualan_temp SET jumlah=jumlah+$qty where session='".$this->session->idp."' AND id_produk='$id_produk' AND id_level='".cetak($this->input->post('id_level'))."' AND harga_jual='$harga_level'");
							}else{
								$this->db->query("UPDATE rb_penjualan_temp SET jumlah=jumlah+$qty where session='".$this->session->idp."' AND id_produk='$id_produk' AND id_level='".cetak($this->input->post('id_level'))."'");
							}
						}else{
							$harga = $this->model_app->view_where('rb_produk',array('id_produk'=>$id_produk))->row_array();
							$disk = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$id_produk,'id_reseller'=>$id_reseller))->row_array();
							
							$keterangan_order = ($var1 != '' ? $var1.'; ' : '').($var2 != '' ? $var2.'; ' : '').($var3 != '' ? $var3.'; ' : '');

							if (cetak($this->input->post('group'))!=''){
							$cgroup = $this->db->query("SELECT * FROM rb_produk_group where id_produk='$id_produk' AND id_group='".cetak($this->input->post('group'))."'");
							if ($cgroup->num_rows()>=1){
								$cg = $cgroup->row_array();
								$harga_konsumen = $cg['harga_group'];
								$diskon = 0;
								if ($this->session->group_sesi==''){
									if (cetak($this->input->post('kgroup'))!=''){
										$this->session->set_userdata(array('group_sesi'=>cetak($this->input->post('kgroup'))));
									}else{
										$this->session->set_userdata(array('group_sesi'=>$cg['id_group'].'.'.$cg['jumlah_group'].'.'.date('His')));
									}
								}
							}else{
								if (cek_paket_all($this->session->id_konsumen,'konsumen')=='1'){
									$harga_konsumen = ($harga['harga_premium']==0?$harga['harga_konsumen']:$harga['harga_premium']);
									$diskon = 0;
								}else{
									$harga_konsumen = $harga['harga_konsumen'];
									if ($disk['diskon']==''){ $diskon = 0; }else{ $diskon = $disk['diskon']; }
								}
							}
							}else{
								if (cek_paket_all($this->session->id_konsumen,'konsumen')=='1'){
									$harga_konsumen = ($harga['harga_premium']==0?$harga['harga_konsumen']:$harga['harga_premium']);
									$diskon = 0;
								}else{
									if ($disk['diskon']==''){ $diskon = 0; }else{ $diskon = $disk['diskon']; }
									$harga_konsumen = $harga['harga_konsumen'];
								}
							}

							if (cetak($this->input->post('id_level'))!='0'){
								$level = $this->model_app->view_where('rb_produk_level',array('id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level'))))->row_array();
								$harga_level = $level['harga_level']/$level['qty_level'];
								$result = array('session'=>$this->session->idp,
												'id_produk'=>$id_produk,
												'jumlah'=>$level['qty_level'],
												'diskon'=>0,
												'harga_jual'=>($harga_level+$variasi_nominal),
												'satuan'=>$harga['satuan'],
												'id_level'=>cetak($this->input->post('id_level')),
												'keterangan_order'=>$keterangan_order,
												'reff'=>(get_cookie($id_produk)==''?'0':get_cookie($id_produk)),
												'waktu_order'=>date('Y-m-d H:i:s'));
								$this->model_app->insert('rb_penjualan_temp',$result);
							}else{
								$data = array('session'=>$this->session->idp,
											'id_produk'=>$id_produk,
											'jumlah'=>$qty,
											'diskon'=>$diskon,
											'harga_jual'=>($harga_konsumen+$variasi_nominal),
											'satuan'=>$harga['satuan'],
											'keterangan_order'=>$keterangan_order,
											'reff'=>(get_cookie($id_produk)==''?'0':get_cookie($id_produk)),
											'waktu_order'=>date('Y-m-d H:i:s'));
								$this->model_app->insert('rb_penjualan_temp',$data);
							}
						}

						$ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
						$fv = explode('|',$ref['keterangan']);

						// if ($fv[2]=='enable'){
						// 	if ($this->session->id_konsumen==''){
						// 		echo $this->session->set_flashdata('message', '<div class="alert alert-info"><center><b>INFORMASI</b> - Anda Harus Login untuk Order Produk.</center></div>');
						// 		redirect('auth/login');
						// 	}else{
						// 		if (isset($_POST['keranjang'])){
						// 			$prd = $this->db->query("SELECT * FROM rb_produk where id_produk='$id_produk'")->row_array();
						// 			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Berhasil ditambahkan ke Keranjang Belanja,..</center></div>');
						// 			redirect('produk/detail/'.$prd['produk_seo']);
						// 		}else{
						// 			redirect('produk/keranjang');
						// 		}
						// 	}
						// }else{
							if (isset($_POST['keranjang'])){
								$prd = $this->db->query("SELECT * FROM rb_produk where id_produk='$id_produk'")->row_array();
								echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Berhasil ditambahkan ke Keranjang Belanja,..</center></div>');
								redirect('produk/detail/'.$prd['produk_seo']);
							}else{
								redirect('produk/keranjang');
							}
						// }
						
					}else{
						if ($this->session->idp != ''){
							$data['rows'] = $this->model_app->edit('rb_reseller',array('id_reseller'=>$this->session->reseller))->row_array();
							$data['record'] = $this->model_app->view_join_where('rb_penjualan_temp','rb_produk','id_produk',array('session'=>$this->session->idp),'id_penjualan_detail','ASC');
						}
						$data['title'] = 'Keranjang Belanja';
						$data['description'] = description();
						$data['keywords'] = keywords();
						echo $this->session->set_flashdata('message', '<div class="alert alert-info"><center>Keranjang hanya bisa berisi 3 toko saja, silahkan hapus beberapa toko untuk menambahkan lainnya.</center></div>');
						redirect('produk/keranjang');
					}
				}
			}else{
				$prd = $this->db->query("SELECT * FROM rb_produk where id_produk='$id_produk'")->row_array();
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Jenis produk (Digital dan Fisik) tidak bisa disatukan dalam 1 keranjang.</center></div>');
				redirect('produk/detail/'.$harga['produk_seo']);
			}
		}else{
			if ($this->session->idp != ''){
				$data['rows'] = $this->model_app->edit('rb_reseller',array('id_reseller'=>$this->session->reseller))->row_array();
				$data['total'] = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."' AND a.checked='Y'")->row_array();
				$data['record'] = $this->db->query("SELECT a.*, b.*, c.nama_reseller FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where a.session='".$this->session->idp."' ORDER BY id_penjualan_detail ASC");
				$this->model_app->view_join_where('rb_penjualan_temp','rb_produk','id_produk',array('session'=>$this->session->idp),'id_penjualan_detail','ASC');
			}
				$data['title'] = 'Keranjang Belanja';
				$this->template->load(template().'/template',template().'/reseller/view_keranjang',$data);

		}
	}

	function keranjang_delete(){
		$id = array('id_penjualan_detail' => $this->uri->segment(3));
		$this->model_app->delete('rb_penjualan_temp',$id);
		$isi_keranjang = $this->db->query("SELECT sum(jumlah) as jumlah FROM rb_penjualan_temp where session='".$this->session->idp."'")->row_array();
		if ($isi_keranjang['jumlah']==''){
			$this->session->unset_userdata('idp');
			$this->session->unset_userdata('reseller');
		}
		redirect('produk/keranjang');
	}

	function kurirdata(){
		$iden = $this->model_reseller->penjualan_konsumen_detail($this->session->idp)->row_array();
		$this->load->library('rajaongkir');
		$tujuan=cetak($this->input->get('kota'));
		$dari=$iden['kota_id'];
		$berat=cetak($this->input->get('berat'));
		$kurir=cetak($this->input->get('kurir'));
		$dc=$this->rajaongkir->cost($dari,$tujuan,$berat,$kurir);
		$d=json_decode($dc,TRUE);
		$o='';
		if(!empty($d['rajaongkir']['results'])){
			$data['data']=$d['rajaongkir']['results'][0]['costs'];
			$this->load->view(template().'/reseller/kurirdata',$data);			
		}else{
			$data['ongkir'] = 0;
			$this->load->view(template().'/reseller/kurirdata',$data);	
		}
	}

	function kurirdata_non(){
		$this->load->library('rajaongkir');
		$tujuan=cetak($this->input->get('kota'));
		$dari=cetak($this->input->get('tujuan'));
		$berat=cetak($this->input->get('berat'));
		$kurir=cetak($this->input->get('kurir'));
		$dc=$this->rajaongkir->cost($dari,$tujuan,$berat,$kurir);
		$d=json_decode($dc,TRUE);
		$o='';
		if(!empty($d['rajaongkir']['results'])){
			$data['data']=$d['rajaongkir']['results'][0]['costs'];
			$this->load->view(template().'/reseller/kurirdata',$data);			
		}else{
			$data['ongkir'] = 0;
			$this->load->view(template().'/reseller/kurirdata',$data);	
		}
	}

	function rajaongkir_get_provinsi(){
		$records = $this->db->query("SELECT * FROM tb_ro_provinces ORDER BY province_name ASC");
		$select_prov = '<option value="">Provinsi</option>';
		foreach ($records->result_array() as $row) {
			$select_prov .= "<option value='$row[province_id]'>$row[province_name]</option>";
		}
		/*$row = $this->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
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
        
        $obj = json_decode($response, true);
        $select_prov = '<option value=0>- Pilih Provinsi -</option>';
        for($i=0; $i < count($obj['rajaongkir']['results']); $i++){
             $select_prov .= "<option value='".$obj['rajaongkir']['results'][$i]['province_id']."'>".$obj['rajaongkir']['results'][$i]['province']."</option>";
        }*/
        echo $select_prov;
    }
    
    function rajaongkir_get_kota(){
        //$row = $this->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
        $id_province = cetak($this->input->post('id_province'));
		$records = $this->db->query("SELECT * FROM tb_ro_cities where province_id='$id_province'");
		$select_kotkab = '<option value="">Kota / Kabupaten</option>';
		foreach ($records->result_array() as $row) {
			$select_kotkab .= "<option value='$row[city_id]'>$row[city_name]</option>";
		}
        /*$curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=$id_province",
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
        
        $obj = json_decode($response, true);
        $select_kotkab = '<option value=0>- Pilih Kota / Kabupaten -</option>';
        for($i=0; $i < count($obj['rajaongkir']['results']); $i++){
             $select_kotkab .= "<option value='".$obj['rajaongkir']['results'][$i]['city_id']."'>".$obj['rajaongkir']['results'][$i]['city_name']."</option>";
        }*/
        
        echo $select_kotkab;
        
    }
    
    
    function rajaongkir_get_kecamatan(){
		//$row = $this->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
		$id_kota = cetak($this->input->post('id_kota'));
		$records = $this->db->query("SELECT * FROM tb_ro_subdistricts where city_id='$id_kota'");
		$select_kecamatan = '<option value="">Kecamatan</option>';
		foreach ($records->result_array() as $row) {
			$select_kecamatan .= "<option value='$row[subdistrict_id]'>$row[subdistrict_name]</option>";
		}
        /*$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=$id_kota",
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
        
        $obj = json_decode($response, true);
        $select_kecamatan = '<option value=0>- Pilih Kecamatan -</option>';
        for($i=0; $i < count($obj['rajaongkir']['results']); $i++){
             $select_kecamatan .= "<option value='".$obj['rajaongkir']['results'][$i]['subdistrict_id']."'>".$obj['rajaongkir']['results'][$i]['subdistrict_name']."</option>";
        }*/
        
        echo $select_kecamatan;
        
    }
    
    
    function rajaongkir_get_cost(){
		$row = $this->db->query("SELECT api_mutasibank, api_rajaongkir FROM identitas where id_identitas='1'")->row_array();
        //origin : kota asal ( ini karna kita dari tangerang selatan, kita default aja kota tangerang selatan = 457)
        $kota_asal      = cetak($this->uri->segment(4));
        //$kecamatan_asal      = 6314;
        //destination : kota tujuan
        $kecamatan_tujuan    = cetak($this->input->post('kecamatan_tujuan'));
        //kurir pengiriman
        $kurir          = cetak($this->input->post('kurir_pengiriman'));
        //berat
        $berat          = cetak($this->uri->segment(5));
        //&courier=jne
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
			CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$kota_asal&originType=city&destination=$kecamatan_tujuan&destinationType=subdistrict&weight=$berat&courier=$kurir",
            CURLOPT_HTTPHEADER => array(
              "content-type: application/x-www-form-urlencoded",
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
        
        $obj = json_decode($response, true);
        
        $datas;
        for($i=0; $i < count($obj['rajaongkir']['results']); $i++){
            //$datas .= $obj['rajaongkir']['results'][$i]['name'];
            $serv = 88;
            for($j=0; $j < count($obj['rajaongkir']['results'][$i]['costs']); $j++){
                
                $nama_pengiriman = $obj['rajaongkir']['results'][$i]['name'];
                $service         = $obj['rajaongkir']['results'][$i]['costs'][$j]['service'];
                $biaya           = $obj['rajaongkir']['results'][$i]['costs'][$j]['cost'][0]['value'];
                $biaya_format    = number_format($obj['rajaongkir']['results'][$i]['costs'][$j]['cost'][0]['value']);
            
                $datas .='<li id="serv-'.$serv.'-'.cetak($this->uri->segment(3)).'" onclick="klikongkir'.cetak($this->uri->segment(3)).'(\''.$nama_pengiriman.'\',\''.$service.'\',\''.$biaya.'\',\''.$biaya_format.'\',this.id)" class="list-group-item clearall-kurir" style="cursor:pointer;margin:1px; padding:5px 1.25rem; line-height: 1; border-left: 3px solid #28a745;">'.$nama_pengiriman.'<br><small><b>'.$service.'</b> <b style="color:red">Rp. '.number_format($obj['rajaongkir']['results'][$i]['costs'][$j]['cost'][0]['value']).'</b> ('.($obj['rajaongkir']['results'][$i]['costs'][$j]['cost'][0]['etd']==''?'- ':$obj['rajaongkir']['results'][$i]['costs'][$j]['cost'][0]['etd']).' '.($nama_pengiriman=='POS Indonesia (POS)'?'':'Hari').')</small></li>';
				$serv++;
            }
            
            if (count($obj['rajaongkir']['results'][$i]['costs'])<=0){
				echo "<li class='list-group-item clearall-kurir' style='cursor:pointer;margin:1px; padding:5px 1.25rem; line-height: 1; color:red'><center>Maaf Kurir Tidak tersedia.</center></li>";
			}

        }

		if ($kecamatan_tujuan==''){
			echo "<li class='list-group-item clearall-kurir' style='cursor:pointer;margin:1px; padding:5px 1.25rem; line-height: 1; color:red'><center>Anda Belum mengisi <b>Alamat Kirim</b>?</center></li>";
		}

        echo $datas;
        
	}
	
	function sopir_get_cost(){
		if (config('type_kurir')=='fix'){
			// Jika Ongkir Fix antar lokasi
			$kurir = cetak($this->input->post('kurir_pengiriman'));
			$kecamatan_dari = cetak($this->input->post('kecamatan_dari'));
			$kecamatan_tujuan = cetak($this->input->post('kecamatan_tujuan'));
			$sopir = $this->db->query("SELECT * FROM rb_driver_ongkir where id_jenis_kendaraan='$kurir' AND posisi='$kecamatan_dari' AND tujuan='$kecamatan_tujuan'");
			foreach ($sopir->result_array() as $row) {
				$sopir = $this->db->query("SELECT a.*, b.kecamatan_id FROM rb_sopir a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen where b.kecamatan_id='$kecamatan_dari' AND a.aktif='Y'");
				foreach ($sopir->result_array() as $rows) {
					echo "<li id='serv-99-".$rows['id_sopir']."' onclick=\"klikongkir".$this->uri->segment(3)."('$rows[id_sopir]','SOPIR','$row[ongkir]','".rupiah($row['ongkir'])."',this.id)\" class='list-group-item clearall-kurir' style='cursor:pointer;margin:1px; padding:5px 1.25rem; line-height: 1;'><span style='color:black'>$rows[merek] ($rows[plat_nomor])</span> <br><small><b style='color:red'>Rp. ".rupiah($row['ongkir'])."</b></small> / <small>$row[keterangan]</small></li>";
				}
			}
			if ($kurir!='0'){
				if ($sopir->num_rows()<=0){
					echo "<center style='color:red'>Maaf, tidak ditemukan kurir pada Rute ini.</center>";
				}
			}

		}else{

			$a = $_POST['lokasi_penjual'];
			$b = $_POST['lokasi'];
			$jarak = distHaversine($a, $b);

			$kurir = cetak($this->input->post('kurir_pengiriman'));
			$kecamatan_dari = cetak($this->input->post('kecamatan_dari'));
			$kecamatan_tujuan = cetak($this->input->post('kecamatan_tujuan'));
			$toko = cetak($this->input->post('toko'));

			if ($b!=''){
				if ($jarak>config('max_jarak_km')){
					echo "<center style='color:red'><b>Yahh Kejauhan!</b> Max. Jarak ".config('max_jarak_km')." KM<br>
													Yuk pilih Kurir Lainnya.</center>";
				}else{
		
					$sopir = $this->db->query("SELECT a.* FROM rb_sopir a where a.kecamatan_id='$kecamatan_dari' AND a.id_jenis_kendaraan='$kurir' AND a.aktif='Y'");
					foreach ($sopir->result_array() as $rows) {
						if ($rows['id_jenis_kendaraan']=='1'){
							$tarif = $jarak * config('ongkir_per_km');
						}else{
							$tarif = $jarak * config('ongkir_per_km_roda4');
						}
						echo "<li id='serv-99-".$rows['id_sopir']."' onclick=\"klikongkir".$this->uri->segment(3)."('$rows[id_sopir]','SOPIR',$tarif,'".rupiah($tarif)."',this.id)\" class='list-group-item clearall-kurir' style='cursor:pointer;margin:1px; padding:5px 1.25rem; line-height: 1; border-left: 3px solid #2340d3;'><span style='color:black;'>$rows[merek] ($rows[plat_nomor])</span> <br><small><b>Tarif.</b> <b style='color:red'>Rp. ".rupiah($tarif)."</b></small> - <small>Jarak $jarak KM</small></li>";
					}
					
					
					if ($kurir!='0'){
						if ($sopir->num_rows()<=0){
							echo "<center style='color:red'>Yahh Kurir pada Rute ini belum ada.</center>";
						}
					}
				}
			}else{
				echo "<center style='color:red'><b>Gagal!</b> Yuk input dulu Kordinat Lokasi-nya.</center>";
			}
		}
		
	}

function selesai_belanja(){
	if (isset($_POST['submit'])){
		$iden = $this->model_app->view_where('identitas',array('id_identitas'=>'1'))->row_array();
		if ($this->session->id_konsumen==''){
			$data = array('username'=>$this->input->post('email'),
						'password'=>hash("sha512", md5($this->input->post('telpon'))),
						'nama_lengkap'=>$this->input->post('nama'),
						'email'=>$this->input->post('email'),
						'alamat_lengkap'=>$this->input->post('alamat'),
						'kecamatan_id'=>$this->input->post('kecamatan'),
						'kota_id'=>$this->input->post('kota'),
						'provinsi_id'=>$this->input->post('provinsi'),
						'no_hp'=>$this->input->post('telpon'),
						'token'=>'-',
						'tanggal_daftar'=>date('Y-m-d'));
			$this->model_app->insert('rb_konsumen',$data);
			$id_konsumen = $this->db->insert_id();
			$keterangan = NULL;
			$this->session->set_userdata(array('pin_used'=>date('Ym')));
		}else{
			$id_konsumen = $this->session->id_konsumen;
			$kon = $this->model_reseller->profile_konsumen($id_konsumen)->row_array();
			if (cetak($this->input->post('idl'))=='0'){
				$keterangan = NULL;
			}else{
				$ceka = $this->db->query("SELECT * FROM rb_konsumen_alamat where id_konsumen='".$this->session->id_konsumen."' AND id_konsumen_alamat='".cetak($this->input->post('idl'))."'");
				if ($ceka->num_rows()>=1){
					$alm = $ceka->row_array();
					$keterangan = $alm['provinsi_id'].'|'.$alm['kota_id'].'|'.$alm['kecamatan_id'].'|'.$alm['alamat_lengkap'].'|'.$alm['kordinat_lokasi'].'|'.$alm['nama_lengkap'].'|'.$alm['no_hp'];
				}else{
					echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center><b>GAGAL</b> Alamat tidak ditemukan!</center></div>');
					redirect('produk/checkouts');
				}
			}
		}
		if ($this->input->post('dropshipp')=='Ya'){
			$dropshipper = cetak($this->input->post('nama_dropshipp')).'||'.cetak($this->input->post('telp_dropshipp'));
		}else{
			$dropshipper = '';
		}

		$kode_transaksi = 'TRX-'.date('YmdHis');
		if ($this->session->idp!=''){
			$reseller_cek = $this->db->query("SELECT b.id_reseller FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where session='".$this->session->idp."' AND a.checked='Y' GROUP BY b.id_reseller");
			$noo = 1;
			$ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
			$fv = explode('|',$ref['keterangan']);
			if ($fv[0]>'0'){
				$fee_admin = $fv[0];
			}else{
				$fee_admin = 0;
			}

			$keranjangx = $this->db->query("SELECT id_kupon_lain FROM `rb_penjualan_temp` where session='".$this->session->idp."' AND checked='Y' AND id_kupon_lain!='' GROUP BY id_kupon_lain");
			if ($keranjangx->num_rows()>=1){
				$rx = $keranjangx->row_array();
				$kp = $this->db->query("SELECT kode_kupon, nilai_kupon, keterangan FROM rb_produk_kupon where id_kupon='$rx[id_kupon_lain]'")->row_array();
				$dataa = array('id_penjualan_detail'=>0,
						'id_kupon'=>$rx['id_kupon_lain'],
						'kode'=>$kp['kode_kupon'],
						'nilai'=>$kp['nilai_kupon'],
						'status_kupon'=>$kode_transaksi,
						'keterangan_kupon'=>$kp['keterangan'],
						'waktu_pakai'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_penjualan_kupon',$dataa);
				$kupon_pusat = $kp['nilai_kupon'];

				$this->db->query("UPDATE rb_penjualan_temp SET id_kupon_lain=NULL where session='".$this->session->idp."'");
			}else{
				$kupon_pusat = 0;
			}

			foreach ($reseller_cek->result_array() as $row) {
				if ($this->input->post('kurirx'.$noo)=='cod'){
					$kode_kurir = '';
					$proses = '1';
				}else{
				    if ($this->input->post('kurir'.$noo)=='Marketplace Lainnya'){
						$kode_kurir = NULL;
					}else{
    					if ($this->input->post('kode_kurir'.$noo)=='0'){
    						$kode_kurir = $this->input->post('kode_sopir'.$noo);
    					}else{
    						if ($this->input->post('kode_kurir'.$noo)==''){
    							$kode_kurir = '-';
    						}else{
    							$kode_kurir = $this->input->post('kode_kurir'.$noo);
    						}
    					}
					}
					$proses = '0';
				}

				if ($this->session->group_sesi!=''){
					$group_sesi = $this->session->group_sesi;
				}else{
					$group_sesi = NULL;
				}
				
				if ($this->session->sesi_resi!=''){
					$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_resi."'")->row_array();
					$fileName = $rows['files'];
				}else{
					$fileName = NULL;
				}

				$session_ongkir = "ongkir".$noo;
				$data = array('kode_transaksi'=>$kode_transaksi,
							'id_pembeli'=>$id_konsumen,
							'id_penjual'=>$row['id_reseller'],
							'status_pembeli'=>'konsumen',
							'status_penjual'=>'reseller',
							'kode_kurir'=>$kode_kurir,
							'kurir'=>$this->input->post('kurir'.$noo),
							'service'=>$this->input->post('service'.$noo),
							'ongkir'=>$this->session->$session_ongkir,
							'keterangan'=>$keterangan,
							'waktu_transaksi'=>date('Y-m-d H:i:s'),
							'proses'=>$proses,
							'fee_admin'=>$fee_admin,
							'dropshipper'=>$dropshipper,
							'group_order'=>$group_sesi);
				$this->model_app->insert('rb_penjualan',$data);
				$idp = $this->db->insert_id();

				$no = 1;
				$keranjang = $this->db->query("SELECT a.*, b.id_reseller, b.fee_produk, b.pre_order FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where session='".$this->session->idp."' AND b.id_reseller='$row[id_reseller]' AND a.checked='Y'");
				foreach ($keranjang->result_array() as $row) {
					if (config('fee_produk')=='0'){ 
						$fee_produk = $row['fee_produk']/100*$row['harga_jual'];
					}elseif (config('fee_produk')>'0'){ 
						$fee_produk = config('fee_produk')/100*$row['harga_jual'];
					}else{ 
						$fee_produk = 0; 
					}
					$keterangan_order = ($row['catatan']!=""?"$row[catatan]||":"").$row['keterangan_order'];
					$dataa = array('id_penjualan'=>$idp,
								'id_produk'=>$row['id_produk'],
								'jumlah'=>$row['jumlah'],
								'diskon'=>$row['diskon'],
								'harga_jual'=>$row['harga_jual'],
								'id_level'=>$row['id_level'],
								'fee_produk_end'=>$fee_produk,
								'preorder'=>$row['pre_order'],
								'keterangan_order'=>$keterangan_order,
								'reff'=>$row['reff'],
								'satuan'=>$row['satuan']);
					$this->model_app->insert('rb_penjualan_detail',$dataa);
					$idp_detail = $this->db->insert_id();

					if ($row['id_kupon']!=''){
						$kp = $this->db->query("SELECT kode_kupon, nilai_kupon FROM rb_produk_kupon where id_kupon='$row[id_kupon]'")->row_array();
						$dataa = array('id_penjualan_detail'=>$idp_detail,
								'id_kupon'=>$row['id_kupon'],
								'kode'=>$kp['kode_kupon'],
								'nilai'=>$kp['nilai_kupon'],
								'waktu_pakai'=>date('Y-m-d H:i:s'));
						$this->model_app->insert('rb_penjualan_kupon',$dataa);
					}

					
					$no++;
				}
				$noo++;
			}
			
			$this->db->query("DELETE FROM rb_penjualan_temp where session='".$this->session->idp."' AND checked='Y'");
			$this->session->unset_userdata('reseller');
			$this->session->set_userdata(array('idp'=>$id_konsumen));
			$this->session->unset_userdata('group_sesi');
			$this->session->unset_userdata('sesi_resi');
			$this->session->unset_userdata('ongkir1');
			$this->session->unset_userdata('ongkir2');
			$this->session->unset_userdata('ongkir3');
			//$this->session->unset_userdata('idp');
		}

		$tong = $this->db->query("SELECT sum(ongkir) as ongkir FROM `rb_penjualan` where kode_transaksi='".$kode_transaksi."'")->row_array();
		$ttal = $this->db->query("SELECT a.kode_transaksi, a.kurir, a.service, a.proses, sum(a.ongkir) as ongkir, sum(b.harga_jual*b.jumlah) as total, sum(b.diskon*b.jumlah) as diskon_total, sum(c.berat*b.jumlah) as total_berat FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan JOIN rb_produk c ON b.id_produk=c.id_produk where a.kode_transaksi='".$kode_transaksi."'")->row_array();
		$kupon = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
										JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
											where b.kode_transaksi='$kode_transaksi'")->row_array();

		$total_tagihan = ((($ttal['total']-$ttal['diskon_total'])+($tong['ongkir']+$fee_admin))-$kupon['diskon'])-$kupon_pusat;
		
		// Cek Duplikat Transfer Otomatis rentang waktu < 24 Jam
		$cek_duplikat = $this->db->query("SELECT a.*, b.proses, SUBSTR(timediff(now(), a.waktu_proses),1,2) as durasi FROM `rb_penjualan_otomatis` a JOIN rb_penjualan b ON a.kode_transaksi=b.kode_transaksi where a.nominal='$total_tagihan' AND b.proses='0' AND SUBSTR(timediff(now(), a.waktu_proses),1,2)<'24' GROUP BY b.kode_transaksi");
		
		$coxx = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$kode_transaksi' AND kode_kurir=''")->num_rows();
		if ($coxx>=1){
		    $angka_acak = 0;
		    $total_tagihan_akhir = $total_tagihan;
		}else{
    		if ($cek_duplikat->num_rows()>=1){
    		    if ($co>=1){
    			    $angka_acak = rand(1,50);
    		    }else{
    		        $angka_acak = 0;
    		    }
    			$total_tagihan_akhir = $total_tagihan-$angka_acak;
    		}else{
    		    if ($co>=1){
    			    $angka_acak = substr($kode_transaksi,-2);
    		    }else{
    		        $angka_acak = 0;
    		    }
    			$total_tagihan_akhir = $total_tagihan-$angka_acak;
    		}
		}

		if ($this->input->post('metode')!=''){
			if (saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen)>=$total_tagihan){
				// Bayar full dengan saldo
				$data = array('id_rekening_reseller'=>0,
					'id_reseller'=>$this->session->id_konsumen,
					'nominal'=>$total_tagihan_akhir,
					'status'=>'Sukses',
					'transaksi'=>'Debit',
					'keterangan'=>$kode_transaksi,
					'waktu_withdraw'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_withdraw',$data);
				
				$data_otomatis = array('kode_transaksi'=>$kode_transaksi,
						'nominal'=>$total_tagihan_akhir,
						'saldo'=>'1',
						'pembayaran'=>'1',
						'waktu_proses'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_penjualan_otomatis',$data_otomatis);

				$datas = array('proses'=>'1');
				$wheres = array('kode_transaksi'=>$kode_transaksi);
				$this->model_app->update('rb_penjualan', $datas, $wheres);
			}else{
				// Bayar sebagian dengan aldo
				$total_tagihanx = $total_tagihan_akhir-saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen);
				$data = array('id_rekening_reseller'=>0,
					'id_reseller'=>$this->session->id_konsumen,
					'nominal'=>saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen),
					'status'=>'Sukses',
					'transaksi'=>'Debit',
					'keterangan'=>$kode_transaksi,
					'waktu_withdraw'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_withdraw',$data);

				$data_otomatis = array('kode_transaksi'=>$kode_transaksi,
						'nominal'=>$total_tagihanx,
						'saldo'=>'0',
						'waktu_proses'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_penjualan_otomatis',$data_otomatis);
			}
		}else{
			// Bayar tanpa saldo
			$data_otomatis = array('kode_transaksi'=>$kode_transaksi,
						'nominal'=>$total_tagihan_akhir,
						'saldo'=>'2',
						'waktu_proses'=>date('Y-m-d H:i:s'));
			$this->model_app->insert('rb_penjualan_otomatis',$data_otomatis);
		}
		
		notif_selesai_belanja($kode_transaksi,$id_konsumen,$total_tagihan_akhir);
		notif_penjual($kode_transaksi);

		$this->session->set_userdata(array('id_konsumen'=>$id_konsumen, 'level'=>'konsumen'));
		redirect('konfirmasi/tracking/'.$kode_transaksi.'?sukses');
	}else{
		redirect('produk/checkouts');
	}
	
}

	function checkouts(){
		if ($this->session->idp==''){
			redirect('produk');
		}

		if ($this->input->post('ongkir')!=''){
			$this->session->set_userdata(array('ongkir1'=>$this->input->post('ongkir1'), 
											   'ongkir2'=>$this->input->post('ongkir2'),
											   'ongkir3'=>$this->input->post('ongkir3')));
		}

		$ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
		$fv = explode('|',$ref['keterangan']);
		if ($fv[2]=='enable'){
			if ($this->session->id_konsumen==''){
			echo $this->session->set_flashdata('message', '<div class="alert alert-info"><center><b>INFORMASI</b> - Anda Harus Login untuk Melanjutkan...</center></div>');
				redirect('auth/login');
			}
		}

		if (isset($_POST['submit'])){
			// Tidak ada aksi
		}else{
			$cek_keranjang = $this->db->query("SELECT * FROM rb_penjualan_temp where session='".$this->session->idp."' AND checked='Y'");
			if ($cek_keranjang->num_rows()<=0){
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center><b>GAGAL!</b> Pilih Barang terlebih dahulu sebelum beli.</center></div>');
				redirect('produk/keranjang');
			}

			$data['title'] = 'Checkout Order';
			$data['provinsi'] = $this->model_app->view_ordering('rb_provinsi','provinsi_id','DESC');
			$data['rows'] = $this->model_app->view_where('rb_reseller',array('id_reseller'=>$this->session->reseller))->row_array();
			$data['record'] = $this->db->query("SELECT a.*, b.nama_produk, b.gambar, b.produk_seo, b.pre_order, c.nama_reseller, GROUP_CONCAT(d.nama SEPARATOR '||') as nama, GROUP_CONCAT(d.variasi SEPARATOR '||') as variasi FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller 
			LEFT JOIN rb_produk_variasi d ON a.id_produk=d.id_produk
				where a.session='".$this->session->idp."' GROUP BY id_penjualan_detail ORDER BY id_penjualan_detail ASC")->result_array();
				
			if ($this->session->id_konsumen==''){
				$this->template->load(template().'/template',template().'/reseller/view_checkouts_option',$data);
			}else{
			    $row = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
			    if (trim(kecamatan($row['kecamatan_id'],$row['kota_id']))==', ,'){
			        echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center><b>GAGAL!</b> Lengkapi data anda untuk mulai belanja!</center></div>');
			        
			        redirect('members/edit_profile?error=1');
			    }else{
			        $this->template->load(template().'/template',template().'/reseller/view_checkouts_konsumen',$data);
			    }
			}
		}
	}

	function order(){
		$this->session->set_userdata(array('produk'=>cetak($this->uri->segment(3))));
		redirect('produk/reseller');
	}

	public function detail(){
		$ids = cetak($this->uri->segment(3));
		$dat = $this->db->query("SELECT a.*, b.hapus_toko FROM rb_produk a LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller where a.produk_seo='$ids' AND a.aktif='Y' AND b.hapus_toko='N'");
		$row = $dat->row();
		if ($dat->num_rows()<=0){
			echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>GAGAL</b> - Produk tidak ditemukan,..</div>");
			redirect('produk'); 
		}

		if (isset($_GET['reff'])){
			$datx = $this->db->query("SELECT * FROM rb_konsumen where username='".cetak($_GET['reff'])."'");
			if ($datx->num_rows()>=1){
				$rx = $datx->row_array();
				set_cookie($row->id_produk, $rx['id_konsumen'], 3600*24*1);
			}
		}

		if (isset($_POST['submit_pertanyaan'])){
			$data = array('id_produk'=>$row->id_produk,
							'id_konsumen'=>$this->session->id_konsumen,
							'reply'=>'0',
							'isi_pesan'=>cetak($this->input->post('pesan',TRUE)),
							'tanggal_komentar'=>date('Y-m-d'),
							'jam_komentar'=>date('H:i:s'));
			$this->model_app->insert('tbl_comment',$data);
			notif_diskusi($row->id_produk,$this->session->id_konsumen);
			redirect('produk/detail/'.$row->produk_seo);

		}elseif (isset($_POST['submit_balasan'])){
			$data = array('id_produk'=>$row->id_produk,
							'id_konsumen'=>$this->session->id_konsumen,
							'reply'=>cetak($this->input->post('reply',TRUE)),
							'isi_pesan'=>cetak($this->input->post('pesan',TRUE)),
							'tanggal_komentar'=>date('Y-m-d'),
							'jam_komentar'=>date('H:i:s'));
			$this->model_app->insert('tbl_comment',$data);
			redirect('produk/detail/'.$row->produk_seo);
		}else{
			// $total = $dat->num_rows();
			// if ($total == 0){ 
			// 	echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>GAGAL</b> - Produk tidak ditemukan atau Belum aktif,..</div>");
			// 	redirect('produk'); 
			// }

			$dataa = array('dilihat'=>$row->dilihat+1);
			$where = array('id_produk' => $row->id_produk);
			$this->model_utama->update('rb_produk', $dataa, $where);

			$tag_seox = explode(',',$row->tag);
			for ($i=0; $i <count($tag_seox); $i++){ 
				$tagc = $this->db->query("SELECT count FROM tagpro where tag_seo='".$tag_seox[$i]."'")->row_array();
				$data_tag = array('count'=>$tagc['count']+1);
				$where_tag = array('tag_seo' => $tag_seox[$i]);
				$this->model_app->update('tagpro', $data_tag, $where_tag);
			}

			$data['title'] = cetak($row->nama_produk);
			$data['description'] = cetak($row->tentang_produk);
			$data['keywords'] = cetak($row->tag);
			$data['record'] = $this->model_app->view_where('rb_produk',array('id_produk'=>$row->id_produk))->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_produk_detail',$data);
		}
	}

	public function perusahaan_detail(){
		if (reseller($this->session->id_konsumen)==''){
			echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Halaman tersebut khusus untuk Reseller/Pelapak,..</center></div>');
			redirect('main');
		}else{
			$ids = cetak($this->uri->segment(3));
			$dat = $this->db->query("SELECT * FROM rb_produk where produk_seo='$ids' AND id_reseller='0'");
			$row = $dat->row();
			if (isset($_POST['submit_rating'])){
				$data = array('id_konsumen'=>$this->session->id_konsumen,
								'id_produk'=>$row->id_produk,
								'rating'=>cetak($this->input->post('rating')),
								'ulasan'=>cetak($this->input->post('ulasan')),
								'waktu_kirim'=>date('Y-m-d H:i:s'));
								
				$this->model_app->insert('rb_produk_ulasan',$data);
				echo $this->session->set_flashdata('message_ulasan', '<div class="alert alert-success"><center>Berhasil Mengirimkan Ulasan,..</center></div>');
				redirect('produk/perusahaan_detail/'.$row->produk_seo);
			}else{
				$total = $dat->num_rows();
					if ($total == 0){
						redirect('main');
					}

				$dataa = array('dilihat'=>$row->dilihat+1);
				$where = array('id_produk' => $row->id_produk);
				$this->model_utama->update('rb_produk', $dataa, $where);

				$data['title'] = $row->nama_produk;
				$data['record'] = $this->model_app->view_where('rb_produk',array('id_produk'=>$row->id_produk))->row_array();
				$this->template->load(template().'/template',template().'/reseller/view_produk_perusahaan_detail',$data);
			}
		}
	}

	function save(){
        $result = array('id_konsumen'=>$this->session->id_konsumen,
				'id_produk'=>cetak($this->input->post('id')),
				'waktu_simpan'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konsumen_simpan',$result);
        echo json_encode($result);
	}

	function cart_update(){
		$id_produk = cetak($this->input->post('id'));
		$qty = cetak($this->input->post('qty'));
		$result = $this->db->query("UPDATE rb_penjualan_temp SET jumlah=jumlah+$qty where session='".$this->session->idp."' AND id_produk='$id_produk'");
		echo json_encode($result);
	}

	function cart(){
		$id_produk = cetak($this->input->post('id'));
		$var1 = cetak($this->input->post('var1'));
		$var2 = cetak($this->input->post('var2'));
		$var3 = cetak($this->input->post('var3'));

		$variasi_nominal = cetak($this->input->post('varx1')+$this->input->post('varx2')+$this->input->post('varx3'));

		$keterangan_order = ($var1 != '' ? $var1.'; ' : '').($var2 != '' ? $var2.'; ' : '').($var3 != '' ? $var3.'; ' : '');

		$harga = $this->model_app->view_where('rb_produk',array('id_produk'=>$id_produk))->row_array();

		$id_reseller = $harga['id_reseller'];

		$ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
		$fv = explode('|',$ref['keterangan']);

		if ($this->session->idp == ''){
			if ($this->session->id_konsumen!=''){ $idp = $this->session->id_konsumen; }else{ $idp = date('YmdHis'); }
			$this->session->set_userdata(array('idp'=>$idp,'reseller'=>$id_reseller));
		}

		$cek_minimum = $this->db->query("SELECT nama_produk, minimum, satuan FROM rb_produk where id_produk='$id_produk'")->row_array();
		if (cetak($this->input->post('qty'))==''){ $tqty = 1; }else{ $tqty = cetak($this->input->post('qty')); }

		if ($cek_minimum['minimum']<=$tqty){
			$qty = $tqty;
		}else{
			$qty = $cek_minimum['minimum'];
		}


			$cek_keranjang = $this->db->query("SELECT a.session, b.jenis_produk FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->id_konsumen."' AND b.jenis_produk!='$harga[jenis_produk]'");
			if ($cek_keranjang->num_rows()<=0){
				$cek_penjual = $this->db->query("SELECT id_konsumen, user_reseller FROM rb_reseller where id_reseller='$id_reseller'")->row_array();
				if ($cek_penjual['id_konsumen']!=$this->session->id_konsumen){
					$reseller_cek = $this->db->query("SELECT b.id_reseller FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where session='".$this->session->idp."' AND b.id_reseller!='$id_reseller' GROUP BY b.id_reseller");
					if ($reseller_cek->num_rows()<'3'){

						if (cetak($this->input->post('id_level'))!='0'){
							$level = $this->model_app->view_where('rb_produk_level',array('id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level'))))->row_array();
							$harga_level = ($level['harga_level']/$level['qty_level'])+$variasi_nominal;
							$cek = $this->model_app->view_where('rb_penjualan_temp',array('session'=>$this->session->idp,'id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level')),'keterangan_order'=>$keterangan_order,'harga_jual'=>$harga_level))->num_rows();
						}else{
							$cek = $this->model_app->view_where('rb_penjualan_temp',array('session'=>$this->session->idp,'id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level')),'keterangan_order'=>$keterangan_order))->num_rows();
						}

						if ($cek >=1){
							if (cetak($this->input->post('id_level'))!='0'){
								$result = $this->db->query("UPDATE rb_penjualan_temp SET jumlah=jumlah+$qty where session='".$this->session->idp."' AND id_produk='$id_produk' AND id_level='".$this->input->post('id_level')."' AND harga_jual='$harga_level'");
							}else{
								$result = $this->db->query("UPDATE rb_penjualan_temp SET jumlah=jumlah+$qty where session='".$this->session->idp."' AND id_produk='$id_produk' AND id_level='".$this->input->post('id_level')."'");
							}
						}else{
							$disk = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$id_produk,'id_reseller'=>$id_reseller))->row_array();
							if (cetak($this->input->post('group'))!=''){
							$cgroup = $this->db->query("SELECT * FROM rb_produk_group where id_produk='$id_produk' AND id_group='".cetak($this->input->post('group'))."'");
								if ($cgroup->num_rows()>=1){
									$cg = $cgroup->row_array();
									$harga_konsumen = $cg['harga_group'];
									$diskon = 0;
									if ($this->session->group_sesi==''){
										if (cetak($this->input->post('kgroup'))!=''){
											$this->session->set_userdata(array('group_sesi'=>cetak($this->input->post('kgroup'))));
										}else{
											$this->session->set_userdata(array('group_sesi'=>$cg['id_group'].'.'.$cg['jumlah_group'].'.'.date('His')));
										}
									}
								}else{
									if (cek_paket_all($this->session->id_konsumen,'konsumen')=='1'){
										$harga_konsumen = ($harga['harga_premium']==0?$harga['harga_konsumen']:$harga['harga_premium']);
										$diskon = 0;
									}else{
										$harga_konsumen = $harga['harga_konsumen'];
										if ($disk['diskon']==''){ $diskon = 0; }else{ $diskon = $disk['diskon']; }
									}
								}
							}else{
							 //   echo cek_paket_all($this->session->id_konsumen,'konsumen'); return;
							    $harga_konsumen = $harga[cek_paket_all($this->session->id_konsumen,'konsumen')];
							    
								// if (cek_paket_all($this->session->id_konsumen,'konsumen')=='1'){
								// 	$harga_konsumen = ($harga['harga_premium']==0?$harga['harga_konsumen']:$harga['harga_premium']);
									$diskon = 0;
								// }else{
								// 	$harga_konsumen = $harga['harga_konsumen'];
								// 	if ($disk['diskon']==''){ $diskon = 0; }else{ $diskon = $disk['diskon']; }
								// }
							}

							if (cetak($this->input->post('id_level'))!='0'){
								$level = $this->model_app->view_where('rb_produk_level',array('id_produk'=>$id_produk,'id_level'=>cetak($this->input->post('id_level'))))->row_array();
								$harga_level = $level['harga_level']/$level['qty_level'];
								$result = array('session'=>$this->session->idp,
												'id_produk'=>$id_produk,
												'jumlah'=>$level['qty_level'],
												'diskon'=>0,
												'harga_jual'=>($harga_level+$variasi_nominal),
												'satuan'=>$harga['satuan'],
												'id_level'=>cetak($this->input->post('id_level')),
												'keterangan_order'=>$keterangan_order,
												'waktu_order'=>date('Y-m-d H:i:s'));
								$this->model_app->insert('rb_penjualan_temp',$result);
							}else{
								$result = array('session'=>$this->session->idp,
												'id_produk'=>$id_produk,
												'jumlah'=>$qty,
												'diskon'=>$diskon,
												'harga_jual'=>($harga_konsumen+$variasi_nominal),
												'satuan'=>$harga['satuan'],
												'keterangan_order'=>$keterangan_order,
												'waktu_order'=>date('Y-m-d H:i:s'));
								$this->model_app->insert('rb_penjualan_temp',$result);
							}

						}
						$prd = $this->model_app->view_where('rb_produk',array('id_produk'=>$id_produk))->row_array();
						$ex = explode(';', $prd['gambar']);
						if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
						echo json_encode("true|$prd[nama_produk] $keterangan_order|$foto_produk");
					}
				}else{
					echo json_encode('Anda tidak diizinkan untuk membeli produk anda sendiri.');
				}
			}else{
				echo json_encode('Jenis produk (Digital dan Fisik) tidak bisa disatukan dalam 1 keranjang');
			}
		
		
	}

	function cart_remove(){
		$id_penjualan_detail = cetak($this->input->post('id'));
		$id = array('id_penjualan_detail' => $id_penjualan_detail, 'session'=>$this->session->idp);
		$result = $this->model_app->delete('rb_penjualan_temp',$id);
		$isi_keranjang = $this->db->query("SELECT sum(jumlah) as jumlah FROM rb_penjualan_temp where session='".$this->session->idp."'")->row_array();
		if ($isi_keranjang['jumlah']==''){
			$this->session->unset_userdata('idp');
			$this->session->unset_userdata('reseller');
		}
		echo json_encode($result);
	}

	function read_query(){
		$data = $this->db->query("SELECT a.*, b.nama_produk, b.gambar, b.produk_seo, b.pre_order, c.nama_reseller, GROUP_CONCAT(d.nama SEPARATOR '||') as nama, GROUP_CONCAT(d.variasi SEPARATOR '||') as variasi FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller 
									LEFT JOIN rb_produk_variasi d ON a.id_produk=d.id_produk
										where a.session='".$this->session->idp."' GROUP BY a.id_penjualan_detail ORDER BY a.id_penjualan_detail ASC")->result();
		echo json_encode($data);
	}

	function delete_stok(){
		$cek = $this->db->query("SELECT b.id_pembeli FROM rb_penjualan_detail a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan where b.id_pembeli='".reseller($this->session->id_konsumen)."' AND a.id_penjualan_detail='".cetak($this->input->post('id'))."'");
		if ($cek->num_rows()>=1){
			$result = $this->db->query("DELETE FROM rb_penjualan_detail where id_penjualan_detail='".cetak($this->input->post('id'))."'");
			echo json_encode($result);
		}
	}
	
	function quick_view(){
		$data['record'] = $this->db->query("SELECT * FROM rb_produk where id_produk='".cetak($this->input->post('id'))."'")->row_array();
		$this->load->view(template().'/reseller/view_produk_quick',$data);
	}

	function download(){
		$name = cetak($this->uri->segment(3));
		$data = file_get_contents("asset/foto_produk/".$name);
		force_download($name, $data);
	}
	
	public function deleteFile(){
		cek_session_members();
		$name = $this->input->post('name');
		$filePath = 'asset/files/'.$name;
		if($name){
			if (file_exists($filePath)) 
			{
				unlink($filePath); // delete file from dir
		    }
			$this->db->delete('img_comment', array('file_name' => $name));
		}

		echo "Deleted File ".$name."<br>";
	}

	public function upload(){
        cek_session_members();
        $this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_resi==''){
			$id = $this->session->id_konsumen.'-resi-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_resi'=>$id));
		}else{
			$id = $this->session->sesi_resi;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/files/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg|jpeg|png|txt|pdf|gif|webp|WEBP|zip|rar|tar';
			$config['max_size']	= '10000'; // kb

            // Load and initialize upload library
            $this->load->library('upload', $config);
            $this->upload->initialize($config);	

	 	 	$fileName = $_FILES["uploadFile"]["name"];

            // Upload file to server
            if($this->upload->do_upload('uploadFile')){
				$fileData = $this->upload->data();
                $uploadData['file_name'] = $fileData['file_name'];
                $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
                $uploadData['id_comment'] = $id;
            }

	    	if(!empty($uploadData)){
                $insert = $this->imgComment->insert($uploadData);
                $data[] = $uploadData['file_name'];
                echo json_encode($data);
            }
        }else{
        	echo json_encode('param is empty.');
        }
	}
}
