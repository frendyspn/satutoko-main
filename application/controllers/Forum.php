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
class Forum extends CI_Controller {
	public function index(){
		$jumlah= $this->db->query("SELECT * FROM topic where pin is null")->num_rows();
		$config['base_url'] = base_url().'forum/index';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 15; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}
		$data['title'] = "Forum Diskusi";
		$data['description'] = description();
		$data['keywords'] = keywords();
		if (is_numeric($dari)) {
			$data['record'] = $this->db->query("SELECT * FROM topic a JOIN kategori_topic c ON a.id_kategori_topic=c.id_kategori_topic where a.pin is null ORDER BY id_topic DESC LIMIT $dari,$config[per_page]");
		}else{
			redirect('main');
		}
		$this->pagination->initialize($config);
		$this->template->load(template().'/template',template().'/reseller/view_forum',$data);
	}

	public function topic_baru(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_forum."'")->row_array();
			$judul_seo = seo_title($this->input->post('judul')).'-'.date('His');
			$data = $this->db->query("SELECT * FROM topic ORDER BY urut DESC LIMIT 1")->row_array();
        	$urut  = $data['urut'] + 1;

			if ($rows['files']==''){
				$datadb = array('id_kategori_topic'=>cetak($this->input->post('id_kategori_topic')),
								'id_konsumen'=>cetak($this->session->id_konsumen),
								'judul'=>cetak($this->input->post('judul')),
								'judul_seo'=>cetak($judul_seo),
								'isi_topic'=>($this->input->post('isi_topic')),
								'tanggal'=>date("Y-m-d H:i:s"),
								'view'=>'0',
								'aktif'=>'Y',
								'urut'=>$urut);
			}else{
				$datadb = array('id_kategori_topic'=>cetak($this->input->post('id_kategori_topic')),
								'id_konsumen'=>cetak($this->session->id_konsumen),
								'judul'=>cetak($this->input->post('judul')),
								'judul_seo'=>cetak($judul_seo),
								'isi_topic'=>($this->input->post('isi_topic')),
								'file_topic' =>$rows['files'],
								'tanggal'=>date("Y-m-d H:i:s"),
								'view'=>'0',
								'aktif'=>'Y',
								'urut'=>$urut);
			}
			$this->model_app->insert('topic',$datadb);
			$this->session->unset_userdata('sesi_forum');
			redirect("forum/read/$judul_seo");
		}else{
			$data['title'] = "Forum - Buat Topic Baru";
			$data['description'] = description();
			$data['keywords'] = keywords();
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$data['kategori'] = $this->model_app->view('kategori_topic');
			$this->template->load(template().'/template',template().'/reseller/view_forum_tambah',$data);
		}
	}

	public function topic_edit(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_forum."'")->row_array();
			if ($rows['files']==''){
				$datadb = array('id_kategori_topic'=>cetak($this->input->post('id_kategori_topic')),
								'judul'=>cetak($this->input->post('judul')),
								'isi_topic'=>($this->input->post('isi_topic')));
			}else{
				$datadb = array('id_kategori_topic'=>cetak($this->input->post('id_kategori_topic')),
								'judul'=>cetak($this->input->post('judul')),
								'file_topic' =>$rows['files'],
								'isi_topic'=>($this->input->post('isi_topic')));
			}
			$where = array('id_topic'=>cetak($this->input->post('id')),'id_konsumen'=>$this->session->id_konsumen);
			$this->model_app->update('topic', $datadb, $where);

			$this->session->unset_userdata('sesi_forum');
			redirect("forum/read/".$this->uri->segment(3));
		}else{
			$data['title'] = "Forum - Edit Topic Terpilih";
			$data['description'] = description();
			$data['keywords'] = keywords();
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$data['kategori'] = $this->model_app->view('kategori_topic');
			$data['topic'] = $this->model_app->view_where('topic',array('id_topic'=>cetak($this->uri->segment(3)),'id_konsumen'=>$this->session->id_konsumen))->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_forum_edit',$data);
		}
	}

	public function read(){
		$query = $this->db->query("SELECT * FROM topic a JOIN kategori_topic c ON a.id_kategori_topic=c.id_kategori_topic where a.judul_seo='".cetak($this->uri->segment(3))."'");
		$row = $query->row_array();

		if (isset($_GET['hide'])){
			$dataa = array('aktif_komentar'=>'N');
			$where = array('id_komentar' => cetak($_GET['hide']),'id_konsumen'=>$this->session->id_konsumen);
			$this->model_app->update('comment', $dataa, $where);
			redirect("forum/read/$row[judul_seo]");
		}
		
		if (isset($_GET['delete'])){
			$id = array('id_komentar' => cetak($_GET['delete']),'id_konsumen'=>$this->session->id_konsumen);
			$this->model_app->delete('comment',$id);
			redirect("forum/read/$row[judul_seo]");
		}

		if ($query->num_rows()<=0){
			redirect('main');
		}else{
			$data['title'] = cetak($row['judul']);
			$data['description'] = cetak($row['isi_topic']);
			$data['keywords'] = cetak(str_replace(' ',', ',$row['judul']));
			$data['record'] = $row;
			
			$jumlah= $this->model_utama->view_where('comment',array('id_topic'=>$row['id_topic'],'aktif_komentar'=>'Y'))->num_rows();
			$config['base_url'] = base_url().'forum/read/'.$this->uri->segment(3);
			$config['total_rows'] = $jumlah;
			$config['per_page'] = 15; 	
			if ($this->uri->segment('4')==''){
				$dari = 0;
			}else{
				$dari = $this->uri->segment('4');
			}

			$dataa = array('view'=>$row['view']+1);
			$where = array('id_topic' => $row['id_topic']);
			$this->model_utama->update('topic', $dataa, $where);

			$data['komentar'] = $this->db->query("SELECT * FROM comment a where id_topic='$row[id_topic]' AND aktif_komentar='Y' ORDER BY tanggal_komentar ASC LIMIT $dari,$config[per_page]");
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_forum_detail',$data);
		}
	}

	public function sendComment(){
		cek_session_members();
		$uploadData['isi_pesan'] = $this->input->post('comment');
		$uploadData['id_topic'] = $this->input->post('id');
		$uploadData['report'] = '0';
		$uploadData['id_konsumen'] = $this->session->id_konsumen;
		$uploadData['tanggal_komentar'] = date('Y-m-d H:i:s');
		$uploadData['jumlah_report'] = '0';
		$uploadData['aktif_komentar'] = 'Y';

		$topic_seo = $this->model_app->view_where('topic',array('id_topic'=>cetak($this->input->post('id'))));
		$row = $topic_seo->row_array();
		
		$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_forum."'")->row_array();
		$uploadData['file_comment'] = $rows['files'];
		if ($this->input->post('comment')!=''){
			$insert = $this->model_app->insert_data($uploadData);
			$data = $this->db->query("SELECT * FROM topic ORDER BY urut DESC LIMIT 1")->row_array();
			$urut  = $data['urut'] + 1;
			$datadbt = array('urut'=>$urut);
			$this->db->where('id_topic',$this->db->escape_str($this->input->post('id')));
			$this->db->update('topic',$datadbt);
		}
		$this->session->unset_userdata('sesi_forum');
	}

	function topic_delete(){
		cek_session_members();
		$topic = $this->model_app->view_where('topic',array('id_topic'=>cetak($this->uri->segment(3)),'id_konsumen'=>$this->session->id_konsumen))->num_rows();
		$id = array('id_topic' => cetak($this->uri->segment(3)),'id_konsumen'=>$this->session->id_konsumen);
		$data = $this->model_app->delete('topic',$id);
		if ($topic>=1){
			echo $this->session->set_flashdata('message', "<div class='alert alert-success'><b>Berhasil</b> - Menghapus data Topic,..</div>");
		}else{
			echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>Gagal</b> - Menghapus data Topic,..</div>");
		}
		redirect('forum');
	}

	public function deleteFile_forum(){
		cek_session_members();
		$name = $this->input->post('name');
		$filePath = 'asset/files/'.$name;
		if($name){
			if (file_exists($filePath)) 
			{
				unlink($filePath); // delete file from dir
				unlink($thumb_filePath); // delete file from dir
		    }
			$this->db->delete('img_comment', array('file_name' => $name));
		}

		echo "Deleted File ".$name."<br>";
	}

	public function upload_forum(){
        cek_session_members();
        $this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_forum==''){
			$id = $this->session->id_konsumen.'-forum-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_forum'=>$id));
		}else{
			$id = $this->session->sesi_forum;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/files/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg|jpeg|png|txt|pdf|gif|zip|rar|tar';
			$config['max_size']	= '50000'; // kb

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
