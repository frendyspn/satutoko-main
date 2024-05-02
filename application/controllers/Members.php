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
use Xendit\Xendit;
class Members extends CI_Controller {
	function foto(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$this->model_reseller->modupdatefoto();
			redirect('members/profile');
		}else{
			redirect('members/profile');
		}
	}

	function messages(){
		cek_session_members();
		if (isset($_GET['unread'])){
			$jumlah= $this->model_reseller->jumlah_unread()->num_rows();
		}else{
			$jumlah= $this->model_reseller->jumlah()->num_rows();
		}

		$config['base_url'] = base_url().'members/messages';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 10; 	
		$config['uri_segment'] = 3 ;
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}

		if (isset($_POST['cari'])){
			$cari = $this->input->post('data');
			$data['record']= $this->model_reseller->pencarian_messages($cari);
		}else{
			if (is_numeric($dari)) {
				if (isset($_GET['unread'])){
					$data['record'] = $this->model_reseller->tampilmessages_unread($config['per_page'],$dari);
				}else{
					$data['record'] = $this->model_reseller->tampilmessageshome($config['per_page'],$dari);
				}
			}
		}

		$data['title'] = 'Inbox';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();

		$this->pagination->initialize($config);
		$this->template->load(template().'/template',template().'/reseller/view_messages',$data);
	}

	function read(){
		cek_session_members();
		$id = $this->uri->segment(3);
		$data['record']= $this->model_reseller->tampilmessages(10,0);

		$this->session->set_userdata(array('messagessend'=>date('YmdHis')));

		$datadbs = array('stat'=>'0');
        $this->db->where('user1',$id);
        $this->db->where('user2',$this->session->id_konsumen);
		$this->db->update('messages',$datadbs);
		
    	$dat = $this->db->query("SELECT nama_reseller, nama_lengkap FROM rb_reseller a LEFT JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen where a.id_konsumen='$id'");
        $row = $dat->row();
    	$data['title'] = 'Inbox';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['recordx'] = $this->model_reseller->tampilmessageshome(10,0);
		$this->template->load(template().'/template',template().'/reseller/view_messages_detail',$data);
	}

	function read_query(){
		cek_session_members();
		$id = $this->uri->segment(3);
		$jumlah= $this->model_reseller->jumlahpesan($id)->num_rows();
		$config['base_url'] = base_url().'members/read/'.$id.'/';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 20; 	
		if ($this->uri->segment('4') == ''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('4');
		}
		if (is_numeric($dari)) {
			$data = $this->model_reseller->modread($id, $config['per_page'], $dari)->result();
			$this->pagination->initialize($config);
		}
		echo json_encode($data);
	}

	public function sendComment(){
		cek_session_members();
		$comment_send = $this->input->post('comment');
		$uploadData['message'] = $comment_send;
		$uploadData['user2'] = $this->input->post('id');
		$uploadData['user1'] = $this->session->id_konsumen;
		$uploadData['date_time'] = date('Y-m-d H:i:s');
		$uploadData['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$uploadData['stat'] = '1';

		$datadbs = array('stat'=>'0');
        $this->db->where('user1',$this->input->post('id'));
        $this->db->where('user2',$this->session->id_konsumen);
		$this->db->update('messages',$datadbs);

		$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_messages."'")->row_array();
		$uploadData['file_upload'] = $rows['files'];
		if ($this->input->post('comment')!='' OR $rows['files']!=''){
			$insert = $this->model_reseller->insert($uploadData);
			if ($this->session->messagessend!=''){
				$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
				$pengirim = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
				$penerima = $this->model_reseller->profile_konsumen($this->input->post('id'))->row_array();

				$message_wa = "*$iden[pengirim_email]* : Hallo, Seseorang baru saja mengirimkan pesan untuk anda di ".base_url()."
					
Silahkan cek segera, Terima kasih.";
					$this->model_app->wa(format_telpon($penerima['no_hp']),$message_wa);
					
					$subjek = "Anda Pesan untuk anda di ".base_url();
					$message_email = "<b>$iden[pengirim_email]</b> : Hallo, Seseorang baru saja mengirimkan pesan untuk anda di ".base_url()."<br><br>
					Silahkan cek segera, Terima kasih.";
					kirim_email($subjek,$message_email,$penerima['email']);
				$this->session->unset_userdata('messagessend');
			}
			$this->session->unset_userdata('sesi_messages');
		}
	}

	public function deleteFilem(){
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

	public function uploadm(){
		cek_session_members();
		$this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_messages==''){
			$id = $this->session->id_konsumen.'-messages-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_messages'=>$id));
		}else{
			$id = $this->session->sesi_messages;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/files/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg|jpeg|png|txt|pdf|gif|webp|WEBP|zip|rar|tar';
			$config['max_size']	= '30000'; // kb

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


	function otp(){
		// if ($this->session->pin_used!=''){
		// 	redirect('members/profile');
		// }
		if ($this->session->level=='konsumen'){
			$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
			$data = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$vpn_network = isset($_POST['kode']) && is_array($_POST['kode']) ? $_POST['kode'] : [];
			$kode = implode('',$vpn_network);
			if (isset($_POST['submit'])){
				if ($this->session->pin==$kode){
					$this->session->set_userdata(array('pin_used'=>$kode));

					if ($this->input->post('remember')=='on'){
						$key = md5($this->session->id_konsumen);
						set_cookie('tajalapak_pin_used', $key, 3600*24*30); // set expired 30 hari
					}

					$this->session->unset_userdata('pin');
					echo $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible" style="padding:5px; border-left:0px solid"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><center><b>SUKSES</b> - Two Factor Verification</center></div>');
					redirect('members/profile');
				}else{
					echo $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" style="padding:5px"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><center><b>GAGAL</b> - Kode yang di input salah atau sudah Kedaluwarsa,..</center></div>');
					redirect('members/otp');
				}
			}else{
				// Kirim email dan WA Two Factor Verification
				if (isset($_GET['send'])){
					$this->session->set_userdata(array('pin'=>date('Hi').rand(10,99),'pin_resend'=>1));
					$token = $this->session->pin;
					$message_wa = "$iden[pengirim_email] : Hello! Your two factor code is *$token*
					
The code will expire in 10 minutes, If you have not tried to login, ignore this message.";
					$this->model_app->wa(format_telpon($data['no_hp']),$message_wa);

					$subjek = "Two Factor Code";
					$message_email = "Hello! Your two factor code is <b style='color:blue'>$token</b><br><br> 
					The code will expire in 10 minutes<br> 
					If you have not tried to login, ignore this message.";
					kirim_email($subjek,$message_email,$data['email']);
					redirect('members/otp'); 

				}elseif (isset($_GET['resend'])){
					$pin_resend = $this->session->pin_resend+1;
					if ($pin_resend<4){
						$this->session->set_userdata(array('pin'=>date('Hi').rand(10,99),'pin_resend'=>$pin_resend));
						$token = $this->session->pin;
						$whatsapp = $data['whatsapp'];
						$message_wa = "$iden[pengirim_email] : Hello! Your two factor code is *$token*
						
The code will expire in 10 minutes, If you have not tried to login, ignore this message.";
						$this->model_app->wa(format_telpon($data['no_hp']),$message_wa);

						$subjek = "Two Factor Code";
						$message_email = "Hello! Your two factor code is <b style='color:blue'>$token</b><br><br> 
						The code will expire in 10 minutes<br> 
						If you have not tried to login, ignore this message.";
						kirim_email($subjek,$message_email,$data['email']);
						echo $this->session->set_flashdata('message', '<div class="alert alert-info alert-dismissible" style="padding:5px; border-left:0px solid"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><center><b>SUKSES</b> - Mengirim ulang kode login dua faktor...</center></div>');
						redirect('members/otp'); 
					}else{
						echo $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" style="padding:5px"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><center><b>PERINGATAN</b> - Kode login dua faktor hanya bisa dikirim max. 3 kali, <a href="'.base_url().'auth/login_verification"><u>Input Kode?</u></a></center></div>');
						redirect('members/otp');
					}
				}else{
					$data['title'] = 'Two Factor Verification';
					$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
					$this->template->load(template().'/template',template().'/reseller/view_otp',$data);
				}
			}
		}
	}

	function profile(){
		cek_session_members();
		if (isset($_POST['verifikasi'])){
			$config['upload_path'] = 'asset/files/';
			$config['allowed_types'] = 'jpg|png|JPG|JPEG|jpeg';
			$config['max_size']     = '1000'; // kb
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->do_upload('file_verifikasi');

			$hasil=$this->upload->data();
			if ($hasil['file_name']!=''){
				$data = array('id_konsumen'=>$this->session->id_konsumen,
								'file_verifikasi'=>$hasil['file_name'],
								'waktu_verifikasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konsumen_verifikasi',$data);
				echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Data Verifikasi Berhasil dikirim.</center></div>');
			}else{
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Data Verifikasi Gagal dikirim.</center></div>');
			}
			redirect('members/profile');
		}else{
			$data['title'] = 'Profile Anda';
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_profile',$data);
		}
	}

	function sopir(){
		cek_session_members();
		$this->session->unset_userdata('sesi_syarat');
		$data['title'] = 'Data Sopir';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$this->template->load(template().'/template',template().'/reseller/view_sopir',$data);
	}

	function daftar_sopir(){
		cek_session_members();
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			if ($this->session->sesi_syarat!=''){
				$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_syarat."'")->row_array();
				$fileName = $rows['files'];
			}else{
				$fileName = '';
			}
			$data = array('id_konsumen'=>$this->session->id_konsumen,
							'provinsi_id'=>cetak(strip_tags($this->input->post('provinsi_id'))),
							'kecamatan_id'=>cetak(strip_tags($this->input->post('kecamatan_id'))),
							'kota_id'=>cetak(strip_tags($this->input->post('kota_id'))),
							'id_jenis_kendaraan'=>cetak($this->input->post('jenis')),
							'plat_nomor'=>cetak($this->input->post('plat_nomor')),
							'merek'=>cetak($this->input->post('merek')),
							'lainnya'=>cetak($this->input->post('lainnya')),
							'lampiran'=>$fileName);
			$cek_sopir = $this->model_app->view_where('rb_sopir',array('id_konsumen'=>$this->session->id_konsumen));
			if ($cek_sopir->num_rows()>=1){
				$where = array('id_konsumen' => $this->session->id_konsumen);
				$this->model_app->update('rb_sopir', $data, $where);
			}else{
				$this->model_app->insert('rb_sopir',$data);
			}
			redirect('members/sopir');
		}else{
			$data['title'] = 'Lengkapi Data Kendaraan';
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$row = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$data['provinsi'] = $this->model_app->view_ordering('rb_provinsi','provinsi_id','ASC');
			$data['rowse'] = $this->db->query("SELECT provinsi_id FROM rb_kota where kota_id='$row[kota_id]'")->row_array();
			$data['rows'] = $this->db->query("SELECT a.*, b.nama_lengkap, b.no_hp, c.jenis_kendaraan FROM rb_sopir a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen 
                                                          JOIN rb_jenis_kendaraan c ON a.id_jenis_kendaraan=c.id_jenis_kendaraan where a.id_konsumen='".$this->session->id_konsumen."'")->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_sopir_edit',$data);
		}
	}

	function download_file(){
        $name = $this->uri->segment(4);
        $data = file_get_contents("asset/".$this->uri->segment(3)."/".$name);
        force_download($name, $data);
    }

	function wishlist(){
		cek_session_members();
		if (isset($_GET['s'])){
			$jumlah = $this->db->query("SELECT * FROM rb_konsumen_simpan a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_konsumen='".$this->session->id_konsumen."' AND b.nama_produk LIKE '%".cetak($_GET['s'])."%'")->num_rows();
		}else{
			$jumlah= $this->model_app->view_where('rb_konsumen_simpan',array('id_konsumen'=>$this->session->id_konsumen))->num_rows();
		}
		$config['base_url'] = base_url().'members/wishlist';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 16; 	

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}

		$data['title'] = 'Wishlist - Produk Tersimpan';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		if (isset($_GET['s'])){
			$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota, z.id_konsumen_simpan FROM rb_konsumen_simpan z JOIN rb_produk a ON z.id_produk=a.id_produk LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
										LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller!='0' AND a.aktif='Y' AND z.id_konsumen='".$this->session->id_konsumen."' AND a.nama_produk LIKE '%".cetak($_GET['s'])."%' ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
		}else{
			$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota, z.id_konsumen_simpan FROM rb_konsumen_simpan z JOIN rb_produk a ON z.id_produk=a.id_produk LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
										LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller!='0' AND a.aktif='Y' AND z.id_konsumen='".$this->session->id_konsumen."' ORDER BY a.id_produk DESC LIMIT $dari,$config[per_page]");
		}
		$this->pagination->initialize($config);
		$this->template->load(template().'/template',template().'/reseller/view_produk_wishlist',$data);
	}

	function wishlist_update(){
		cek_session_members();
		$data['record'] = $this->db->query("SELECT a.*, b.nama_reseller, c.nama_kota, z.id_konsumen_simpan FROM rb_konsumen_simpan z JOIN rb_produk a ON z.id_produk=a.id_produk LEFT JOIN rb_reseller b ON a.id_reseller=b.id_reseller
										LEFT JOIN rb_kota c ON b.kota_id=c.kota_id where a.id_reseller!='0' AND a.id_produk_perusahaan='0' AND a.aktif='Y' AND z.id_konsumen='".$this->session->id_konsumen."' ORDER BY a.id_produk DESC");
		$this->load->view(template().'/reseller/view_produk_wishlist_update',$data);
	}

	function delete_wishlist(){
		cek_session_members();
        $id = array('id_konsumen_simpan' => cetak($this->uri->segment(3)), 'id_konsumen'=>$this->session->id_konsumen);
		$this->model_app->delete('rb_konsumen_simpan',$id);
		redirect($this->uri->segment(1).'/wishlist');
	}

	function edit_profile(){
		cek_session_members();
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$cek_username = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen!='".$this->session->id_konsumen."' AND (username='".cetak($this->input->post('aa'))."' OR email='".cetak($this->input->post('c'))."' OR no_hp='".cetak($this->input->post('l'))."')");
			if ($cek_username->num_rows()<='0'){
				$this->model_reseller->profile_update($this->session->id_konsumen);
				echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Sukses Update Data Profile,..</center></div>');
			}else{
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal Update Profile, Username/E-mail/No HP telah digunakan...</center></div>');
			}
			if ($_GET['redirect']!=''){
			    redirect($_GET['redirect']);
			}else{
			    redirect('members/profile');
			}
		}else{
			$data['title'] = 'Lengkapi Data Profile';
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$row = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$data['provinsi'] = $this->model_app->view_ordering('rb_provinsi','provinsi_id','ASC');
			$data['rowse'] = $this->db->query("SELECT provinsi_id FROM rb_kota where kota_id='$row[kota_id]'")->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_profile_edit',$data);
		}
	}

	function sosial_media(){
		cek_session_members();
		$data['title'] = 'Sosial Media';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['rows'] = $this->model_app->view_where('rb_konsumen_detail',array('id_konsumen'=>$this->session->id_konsumen,'status'=>'sosmed'))->row_array();
		$this->template->load(template().'/template',template().'/reseller/view_sosmed',$data);
	}

	function edit_sosial_media(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$keterangan = cetak($this->input->post('a')).';'.cetak($this->input->post('b')).';'.cetak($this->input->post('c')).';'.cetak($this->input->post('d')).';'.cetak($this->input->post('e')).';'.cetak($this->input->post('f')).';'.cetak($this->input->post('g')).';'.cetak($this->input->post('h'));
			$data = array('id_konsumen'=>$this->session->id_konsumen,
							'keterangan'=>$keterangan,
							'status'=>'sosmed',
							'waktu_input'=>date('Y-m-d H:i:s'));
			$cek_sosmed = $this->model_app->view_where('rb_konsumen_detail',array('id_konsumen'=>$this->session->id_konsumen,'status'=>'sosmed'));
			if ($cek_sosmed->num_rows()>=1){
				$where = array('id_konsumen' => $this->session->id_konsumen,'status'=>'sosmed');
				$this->model_app->update('rb_konsumen_detail', $data, $where);
			}else{
				$this->model_app->insert('rb_konsumen_detail',$data);
			}
			redirect('members/sosial_media');
		}else{
			$data['title'] = 'Sosial Media';
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$data['rows'] = $this->model_app->view_where('rb_konsumen_detail',array('id_konsumen'=>$this->session->id_konsumen,'status'=>'sosmed'))->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_sosmed_edit',$data);
		}
	}

	function rekening_bank(){
		cek_session_members();
		$data['title'] = 'Rekening Bank';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['record'] = $this->model_app->view_where('rb_konsumen_detail',array('id_konsumen'=>$this->session->id_konsumen,'status'=>'rekening'));
		$this->template->load(template().'/template',template().'/reseller/view_rekening',$data);
	}

	function tambah_rekening_bank(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$keterangan = cetak($this->input->post('a')).';'.cetak($this->input->post('b')).';'.cetak($this->input->post('c'));
			$data = array('id_konsumen'=>$this->session->id_konsumen,
							'keterangan'=>$keterangan,
							'status'=>'rekening',
							'waktu_input'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konsumen_detail',$data);
			redirect('members/rekening_bank');
		}else{
			$data['title'] = 'Rekening Bank';
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_rekening_tambah',$data);
		}
	}

	function edit_rekening_bank(){
		cek_session_members();
		if (isset($_POST['submit'])){
			$keterangan = cetak($this->input->post('a')).';'.cetak($this->input->post('b')).';'.cetak($this->input->post('c'));
			$data = array('id_konsumen'=>$this->session->id_konsumen,
							'keterangan'=>$keterangan,
							'status'=>'rekening');
			$where = array('id_konsumen'=>$this->session->id_konsumen,'status'=>'rekening','id_konsumen_detail'=>cetak($this->input->post('id')));
			$this->model_app->update('rb_konsumen_detail', $data, $where);
			redirect('members/rekening_bank');
		}else{
			$data['title'] = 'Rekening Bank';
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$data['rows'] = $this->model_app->view_where('rb_konsumen_detail',array('id_konsumen'=>$this->session->id_konsumen,'status'=>'rekening','id_konsumen_detail'=>cetak($this->uri->segment('3'))))->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_rekening_edit',$data);
		}
	}

	function delete_rekening_bank(){
        cek_session_members();
		$id = array('id_konsumen'=>$this->session->id_konsumen,'status'=>'rekening','id_konsumen_detail'=>cetak($this->uri->segment('3')));
        $this->model_app->delete('rb_konsumen_detail',$id);
		redirect($this->uri->segment(1).'/rekening_bank');
	}

	function reseller(){
		cek_session_members();
		$jumlah= $this->model_app->view('rb_reseller')->num_rows();
		$config['base_url'] = base_url().'members/reseller';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 12; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}

		if (is_numeric($dari)) {
			$data['title'] = 'Semua Daftar Reseller';
			$this->pagination->initialize($config);
			if (isset($_POST['submit'])){
				$data['record'] = $this->model_reseller->cari_reseller(filter($this->input->post('cari_reseller')));
			}elseif (isset($_GET['cari_reseller'])){
				$data['record'] = $this->model_reseller->cari_reseller(filter($this->input->get('cari_reseller')));
				$total = $this->model_reseller->cari_reseller(filter($this->input->get('cari_reseller')));
				if ($total->num_rows()==1){
					$row = $total->row_array();
					redirect('produk/keranjang/'.$row['id_reseller'].'/'.$this->session->produk);
				}
			}else{
				$data['record'] = $this->db->query("SELECT * FROM rb_reseller a LEFT JOIN rb_kota b ON a.kota_id=b.kota_id ORDER BY id_reseller DESC LIMIT $dari,$config[per_page]");
			}
			$this->template->load(template().'/template',template().'/reseller/view_reseller',$data);
		}else{
			redirect('main');
		}
	}

	function detail_reseller(){
		cek_session_members();
		$data['title'] = 'Detail Profile Reseller';
		$id = cetak($this->uri->segment(3));
		$data['rows'] = $this->model_app->edit('rb_reseller',array('id_reseller'=>$id))->row_array();
		$data['record'] = $this->model_reseller->penjualan_list_konsumen($id,'reseller');
		$data['rekening'] = $this->model_app->view_where('rb_rekening_reseller',array('id_reseller'=>$id));
		$this->template->load(template().'/template',template().'/reseller/view_reseller_detail',$data);

	}

	function orders_report(){
		cek_session_members();
		if (isset($_GET['sukses'])){
			$data = array('proses'=>'4');
			$where = array('id_penjualan'=>cetak($this->input->get('sukses')),'id_pembeli'=>$this->session->id_konsumen,'status_pembeli'=>'konsumen');
			$this->model_app->update('rb_penjualan', $data, $where);
			notif_pesanan_selesai(cetak($this->input->get('sukses')),$this->session->id_konsumen);

			// Cek Upline Seller 5 level
			$ref = $this->db->query("SELECT a.kode_transaksi, a.id_penjual, b.nama_reseller, b.referral FROM rb_penjualan a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.id_penjualan='".cetak($this->input->get('sukses'))."' AND a.status_penjual='reseller'")->row_array();
			$tot = $this->db->query("SELECT sum((harga_jual*jumlah)-(diskon*jumlah)) as totalxx FROM rb_penjualan_detail where id_penjualan='".cetak($this->input->get('sukses'))."'")->row_array();

			$x=1; 
			$level = 5;
			$ress = $this->db->query("SELECT id_konsumen FROM rb_reseller WHERE id_reseller='$ref[id_penjual]'")->row_array();
			$sponsore = $ress['id_konsumen'];
			$total = $tot['totalxx'];
			$total_fee = config('fee_trx_total')/100*$total;
			
			do{
				$rowx = $this->db->query("SELECT * FROM rb_konsumen WHERE id_konsumen='$sponsore'")->row_array();
				if ($rowx['referral_id']!=''){
					if ($x==1){
						//echo "Level $x : id_konsumen $rowx[referral_id] = Khusus ".config('fee_trx_level'.$x)/100*$total_fee." (".config('fee_trx_level'.$x)."%)<br>";
						$fee_rupiah = config('fee_trx_level'.$x)/100*$total_fee;
						$fee_persen = config('fee_trx_level'.$x);
					}else{
						if (cek_paket_all($rowx['referral_id'],'konsumen')=='1'){
							//echo "Level $x : id_konsumen $rowx[referral_id] = Premium Akun ".config('fee_trx_level'.$x)/100*$total_fee." (".config('fee_trx_level'.$x)."%)<br>";
							$fee_rupiah = config('fee_trx_level'.$x)/100*$total_fee;
							$fee_persen = config('fee_trx_level'.$x);
						}else{
							//echo "Level $x : id_konsumen $rowx[referral_id] = Free Akun 0<br>";
							$fee_rupiah = 0;
							$fee_persen = 0;
						}
					}

					if ($fee_rupiah>0){
						$data_fee = array('id_rekening_reseller'=>0,
								'id_reseller'=>$rowx['referral_id'],
								'nominal'=>$fee_rupiah,
								'status'=>'Sukses',
								'transaksi'=>'Kredit',
								'keterangan'=>"$ref[kode_transaksi] - Lvl $x Fee $fee_persen%",
								'akun'=>'konsumen',
								'waktu_withdraw'=>date('Y-m-d H:i:s'));
						$this->model_app->insert('rb_withdraw',$data_fee);

						$data_fee_debit = array('id_rekening_reseller'=>0,
								'id_reseller'=>$ref['id_penjual'],
								'nominal'=>$fee_rupiah,
								'status'=>'Sukses',
								'transaksi'=>'Debit',
								'keterangan'=>"$ref[kode_transaksi] - Lvl $x Fee $fee_persen%",
								'akun'=>'reseller',
								'waktu_withdraw'=>date('Y-m-d H:i:s'));
						$this->model_app->insert('rb_withdraw',$data_fee_debit);
					}
					
					$sponsore=$rowx['referral_id'];
				}
				$x++;
			}
			while($x<=$level);

			// Cek Referral, jika ada maka masukkan fee ke referral
			// $ref = $this->db->query("SELECT a.kode_transaksi, a.id_penjual, b.nama_reseller, b.referral FROM rb_penjualan a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.id_penjualan='".cetak($this->input->get('sukses'))."' AND a.status_penjual='reseller'")->row_array();
			// if ($ref['referral']!='' AND $ref['referral']!='0'){
			// 	$row = $this->db->query("SELECT sum(b.harga_beli*a.jumlah) as modal, sum((a.harga_jual-a.diskon)*a.jumlah) as total, (sum((a.harga_jual-a.diskon)*a.jumlah)-sum(b.harga_beli*a.jumlah)) as untung FROM `rb_penjualan_detail` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.id_penjualan='".cetak($this->input->get('sukses'))."'")->row_array();
			// 	if ($row['untung']>0){
			// 		$cekfee = $this->db->query("SELECT * FROM rb_setting where aktif='Y'")->row_array();
			// 		$fee_rupiah = $cekfee['referral']/100*$row['untung'];
			// 		$data = array('id_rekening_reseller'=>0,
			// 				'id_reseller'=>$ref['referral'],
			// 				'nominal'=>$fee_rupiah,
			// 				'status'=>'Sukses',
			// 				'transaksi'=>'Kredit',
			// 				'keterangan'=>"$ref[kode_transaksi] - Fee $cekfee[referral]%",
			// 				'akun'=>'konsumen',
			// 				'waktu_withdraw'=>date('Y-m-d H:i:s'));
			// 		$this->model_app->insert('rb_withdraw',$data);
					
			// 		$data_fee = array('id_rekening_reseller'=>0,
			// 				'id_reseller'=>$ref['id_penjual'],
			// 				'nominal'=>$fee_rupiah,
			// 				'status'=>'Sukses',
			// 				'transaksi'=>'Debit',
			// 				'keterangan'=>"$ref[kode_transaksi] - Fee $cekfee[referral]%",
			// 				'akun'=>'konsumen',
			// 				'waktu_withdraw'=>date('Y-m-d H:i:s'));
			// 		$this->model_app->insert('rb_withdraw',$data_fee);
			// 	}
			// }

			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Terima kasih karena telah mengkonfirmasi Penerimaan pesanan anda,.. ^_^</center></div>');
			if ($this->input->get('detail')=='true'){
				redirect('members//keranjang_detail/'.cetak($this->input->get('sukses')));
			}else{
				redirect('members/orders_report');
			}
		}
		$jumlah= $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.status_penjual='reseller' AND a.id_pembeli='".$this->session->id_konsumen."' ORDER BY a.id_penjualan DESC")->num_rows();
		$config['base_url'] = base_url().'members/orders_report';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 50; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}
		$data['title'] = 'Laporan Pesanan Anda';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['pending'] = $this->db->query("SELECT GROUP_CONCAT(b.nama_reseller SEPARATOR ', ') as nama_reseller, a.* FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.status_penjual='reseller' AND a.id_pembeli='".$this->session->id_konsumen."' AND (a.proses='0' OR a.proses='2') GROUP BY a.kode_transaksi ORDER BY a.id_penjualan DESC LIMIT $dari,$config[per_page]");
		$data['proses'] = $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.status_penjual='reseller' AND a.id_pembeli='".$this->session->id_konsumen."' AND a.proses='1' ORDER BY a.id_penjualan DESC LIMIT $dari,$config[per_page]");
		$data['dikirim'] = $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.status_penjual='reseller' AND a.id_pembeli='".$this->session->id_konsumen."' AND a.proses='3' ORDER BY a.id_penjualan DESC LIMIT $dari,$config[per_page]");
		$data['selesai'] = $this->db->query("SELECT * FROM `rb_penjualan` a JOIN rb_reseller b ON a.id_penjual=b.id_reseller where a.status_penjual='reseller' AND a.id_pembeli='".$this->session->id_konsumen."' AND a.proses='4' ORDER BY a.id_penjualan DESC LIMIT $dari,$config[per_page]");
		$this->pagination->initialize($config);
		$this->template->load(template().'/template',template().'/reseller/view_orders_report',$data);
	}

	function sopir_list(){
		cek_session_members();
		$sopir = $this->db->query("SELECT id_sopir FROM rb_sopir where id_konsumen='".$this->session->id_konsumen."'")->row_array();
		$jumlah= $this->db->query("SELECT * FROM rb_penjualan a WHERE a.kurir='$sopir[id_sopir]' AND a.service='SOPIR'")->num_rows();
		$config['base_url'] = base_url().'members/sopir_list';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 10; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}
		$data['title'] = 'Order Pengantaran Barang';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['pending'] = $this->db->query("SELECT a.*, b.user_reseller, b.alamat_lengkap, b.no_telpon, c.no_hp, c.nama_lengkap, b.nama_reseller, b.kecamatan_id as kecamatan_id_jual, b.kota_id as kota_id_jual, c.kecamatan_id as kecamatan_id_beli, c.kota_id as kota_id_beli FROM rb_penjualan a 
												JOIN rb_reseller b ON a.id_penjual=b.id_reseller
													JOIN rb_konsumen c ON a.id_pembeli=c.id_konsumen
														WHERE a.kurir='$sopir[id_sopir]' AND a.service='SOPIR' ORDER BY a.waktu_transaksi DESC LIMIT $dari,$config[per_page]");
		$this->pagination->initialize($config);
		$this->template->load(template().'/template',template().'/reseller/view_sopir_report',$data);
	}

	function trx_pulsa(){
		cek_session_members();
		$jumlah= $this->db->query("SELECT * FROM `rb_pembelian_pulsa` where id_reseller='".reseller($this->session->id_konsumen)."' ORDER BY waktu_pembelian DESC")->num_rows();
		$config['base_url'] = base_url().'members/trx_pulsa';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 20; 	
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}
		
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['pulsa'] = $this->db->query("SELECT * FROM `rb_pembelian_pulsa` where id_reseller='".$this->session->id_konsumen."' ORDER BY waktu_pembelian DESC LIMIT $dari,$config[per_page]");
		$this->pagination->initialize($config);
		if (isset($_GET['ppob'])){
			$row = $this->db->query("SELECT * FROM rb_ppob where id_api='$_GET[ppob]'")->row_array();
			$data['title'] = $row['nama_ppob']." ($row[jenis])";
			if ($row['jenis']=='pascabayar'){
			    if (isset($_POST['submit'])){
					$rows = $this->db->query("SELECT maps FROM identitas where id_identitas='1'")->row_array();
			        $maps = explode('|',$rows['maps']);
			        $payload = [
                        'product' => cetak($this->input->post('operator')),
                        'phone'   => cetak($this->input->post('tujuan')),
                        'no_pelanggan' => cetak($this->input->post('id_pelanggan')),
                        'api_trxid'    => 'PPOB/'.$this->session->id_konsumen.'/'.date('YmdHis'),
                        'pin'       => $maps[1]
                    ];
                    
                    $operator = tripay_pascabayar($payload,config('ppob_url').'/v2/pembayaran/cek-tagihan','POST');
                    $data['operator'] = $operator; 
                    
                    $cek_exist = $this->db->query("SELECT * FROM rb_ppob_tagihan where id_tagihan='".$operator->data->id."'");
                    //print_r($operator);
                    
                    $datax = $operator->data->tagihan_id.'|'.$operator->data->code.'|'.$operator->data->product_name.'|'.$operator->data->phone.'|'.$operator->data->no_pelanggan.'|'.$operator->data->nama.'|'.$operator->data->periode.'|'.$operator->data->status.'|'.$operator->data->expired.'|'.$operator->data->jumlah_tagihan.'|'.$operator->data->biaya_admin.'|'.$operator->data->jumlah_bayar.'|'.$operator->data->api_trxid.'|'.$operator->data->created_at;
			        if ($operator->data->id!=''){
    			        $data_ppob = array('id_konsumen'=>$this->session->id_konsumen,
    							'id_tagihan'=>$operator->data->id,
    							'message'=>$operator->message,
    							'data'=>$datax);
    					if ($cek_exist->num_rows()>=1){
    					    $where = array('id_tagihan' => $operator->data->id,'id_konsumen' => $this->session->id_konsumen);
    						$this->model_app->update('rb_ppob_tagihan', $data_ppob, $where);
    					}else{
    					    $this->model_app->insert('rb_ppob_tagihan',$data_ppob);
    					}
			        }else{
			            echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center><b>GAGAL</b> - '.$operator->message.'</center></div>');
			            redirect('members/trx_pulsa?ppob='.$_GET['ppob']);
			        }
			    }
			    
			    $this->template->load(template().'/template',template().'/reseller/view_ppob_orders_pascabayar',$data);
			}else{
			    $this->template->load(template().'/template',template().'/reseller/view_ppob_orders',$data);
			}
		}else{
			$data['title'] = 'PPOB - Payment Point Online Banking';
			$this->template->load(template().'/template',template().'/reseller/view_ppob',$data);
		}
	}

	function trx_pulsa_komplain(){
		cek_session_members();
		$cek = $this->db->query("SELECT * FROM rb_pembelian_pulsa where id_pembelian_pulsa='".cetak($this->input->post('id'))."'")->row_array();
		$row = $this->db->query("SELECT maps FROM identitas where id_identitas='1'")->row_array();
		$maps = explode('|',$row['maps']);
		$url = config('ppob_url').'/v2/histori/transaksi/detail';
		$header = array(
			'Accept: application/json',
			'Authorization: Bearer '.$maps[0], // Ganti [apikey] dengan API KEY Anda
		);

		$data = array(
			// 'trxid' => '10305', // Masukkan Transaksi ID
			'api_trxid' => $cek['api_trxid'], // Atau Anda bisa menggunakan ID transaksi dari server Anda (pilih salah satu)
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$data['result'] = json_decode(curl_exec($ch));

		$this->load->view(template().'/reseller/view_ppob_detail',$data);

		if(curl_errno($ch)){
			return 'Request Error:' . curl_error($ch);
		}
	}

	

	function produk_reseller(){
		cek_session_members();
		$jumlah= $this->model_app->view('rb_produk')->num_rows();
		$config['base_url'] = base_url().'members/produk_reseller/'.$this->uri->segment('3');
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 12; 	
		if ($this->uri->segment('4')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('4');
		}

		if (is_numeric($dari)) {
			$data['title'] = 'Data Produk Reseller';
			$id = cetak($this->uri->segment(3));
			$data['rows'] = $this->db->query("SELECT * FROM rb_reseller a JOIN rb_kota b ON a.kota_id=b.kota_id where a.id_reseller='$id'")->row_array();
			$data['record'] = $this->model_app->view_where_ordering_limit('rb_produk',array('id_reseller!='=>'0'),'id_produk','DESC',$dari,$config['per_page']);
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/view_reseller_produk',$data);
		}else{
			redirect('main');
		}
	}

	function keranjang(){
		cek_session_members();
		$id_reseller = cetak($this->uri->segment(3));
		$id_produk   = cetak($this->uri->segment(4));

		$j = $this->model_reseller->jual_reseller($id_reseller,$id_produk)->row_array();
        $b = $this->model_reseller->beli_reseller($id_reseller,$id_produk)->row_array();
        $stok = $b['beli']-$j['jual'];

		if ($id_produk!=''){
			if ($stok <= '0'){
				$produk = $this->model_app->edit('rb_produk',array('id_produk'=>$id_produk))->row_array();
				$produk_cek = filter($produk['nama_produk']);
				echo "<script>window.alert('Stok untuk Produk $produk_cek pada Reseller ini telah habis!');
                                  window.location=('".base_url()."members/reseller')</script>";
			}else{
				$this->session->unset_userdata('produk');
				if ($this->session->idp == ''){
					$kode_transaksi = 'TRX-'.date('YmdHis');
					$data = array('kode_transaksi'=>$kode_transaksi,
				        		  'id_pembeli'=>$this->session->id_konsumen,
				        		  'id_penjual'=>$id_reseller,
				        		  'status_pembeli'=>'konsumen',
				        		  'status_penjual'=>'reseller',
				        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
				        		  'proses'=>'0');
					$this->model_app->insert('rb_penjualan',$data);
					$idp = $this->db->insert_id();
					$this->session->set_userdata(array('idp'=>$idp));
				}

				$qty = cetak($this->input->post('qty'));
				$reseller = $this->model_app->view_where('rb_penjualan',array('id_penjualan'=>$this->session->idp))->row_array();
				$cek = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan'=>$this->session->idp,'id_produk'=>$id_produk))->num_rows();
				if ($reseller['id_penjual']==$id_reseller){
					if ($cek >=1){
						$this->db->query("UPDATE rb_penjualan_detail SET jumlah=jumlah+$qty where id_penjualan='".$this->session->idp."' AND id_produk='$id_produk'");
					}else{
						$harga = $this->model_app->view_where('rb_produk',array('id_produk'=>$id_produk))->row_array();
						$disk = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$id_produk,'id_reseller'=>$id_reseller))->row_array();
	                    $harga_konsumen = $harga['harga_konsumen']-$disk['diskon'];
						$data = array('id_penjualan'=>$this->session->idp,
					        		  'id_produk'=>$id_produk,
					        		  'jumlah'=>$qty,
					        		  'harga_jual'=>$harga_konsumen,
					        		  'satuan'=>$harga['satuan']);
						$this->model_app->insert('rb_penjualan_detail',$data);
					}
					redirect('members/keranjang');
				}else{
					if ($this->session->idp != ''){
						$data['rows'] = $this->model_reseller->penjualan_konsumen_detail($this->session->idp)->row_array();
						$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','ASC');
					}
					$data['title'] = 'Keranjang Belanja';
					$data['error_reseller'] = "<div class='alert alert-danger'>Dalam 1 Transaksi hanya boleh order dari 1 Reseller saja.</div>";
					$this->template->load(template().'/template',template().'/reseller/members/view_keranjang',$data);
				}
			}
		}else{
			if ($this->session->idp != ''){
				$data['rows'] = $this->model_reseller->penjualan_konsumen_detail($this->session->idp)->row_array();
				$data['rowsk'] = $this->model_reseller->view_join_where_one('rb_konsumen','rb_kota','kota_id',array('id_konsumen'=>$this->session->id_konsumen))->row_array();
				$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','ASC');
			}
				$data['title'] = 'Keranjang Belanja';
				$this->template->load(template().'/template',template().'/reseller/members/view_keranjang',$data);

		}
	}

	function keranjang_detail(){
		cek_session_members();
		$data['rows'] = $this->model_reseller->penjualan_konsumen_detail(cetak($this->uri->segment(3)))->row_array();
		$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>cetak($this->uri->segment(3))),'id_penjualan_detail','ASC');
		$data['title'] = 'Detail Belanja';
		$this->template->load(template().'/template',template().'/reseller/members/view_keranjang_detail',$data);
	}

	function keranjang_delete(){
		$id = array('id_penjualan_detail' => cetak($this->uri->segment(3)));
		$this->model_app->delete('rb_penjualan_detail',$id);
		$isi_keranjang = $this->db->query("SELECT sum(jumlah) as jumlah FROM rb_penjualan_detail where id_penjualan='".$this->session->idp."'")->row_array();
		if ($isi_keranjang['jumlah']==''){
			$idp = array('id_penjualan' => $this->session->idp);
			$this->model_app->delete('rb_penjualan',$idp);
			$this->session->unset_userdata('idp');
		}
		redirect('members/keranjang');
	}

	function batalkan_transaksi(){
		echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Anda Telah mebatalkan Transaksi!</center></div>');
		$idp = array('id_penjualan' => $this->session->idp);
		$this->model_app->delete('rb_penjualan',$idp);
		$idp_detail = array('id_penjualan' => $this->session->idp);
		$this->model_app->delete('rb_penjualan_detail',$idp_detail);

		$this->session->unset_userdata('idp');
		redirect('members/profile');
	}

	function order(){
		cek_session_members();
		$this->session->set_userdata(array('produk'=>cetak($this->uri->segment(3))));
		$cek = $this->db->query("SELECT b.nama_kota FROM rb_konsumen a JOIN rb_kota b ON a.kota_id=b.kota_id where a.id_konsumen='".$this->session->id_konsumen."'")->row_array();
		redirect('members/reseller?cari_reseller='.$cek['nama_kota']);
	}

	public function username_check(){
        // allow only Ajax request    
        if($this->input->is_ajax_request()) {
	        // grab the email value from the post variable.
	        $username = cetak($this->input->post('a'));
            if(!$this->form_validation->is_unique($username, 'rb_konsumen.username')) {          
	         	$this->output->set_content_type('application/json')->set_output(json_encode(array('messageusername' => 'Username ini sudah terdaftar,..')));
            }

        }
    }

    public function email_check(){
        // allow only Ajax request    
        if($this->input->is_ajax_request()) {
	        // grab the email value from the post variable.
	        $email = cetak($this->input->post('d'));

	        if(!$this->form_validation->is_unique($email, 'rb_konsumen.email')) {          
	         	$this->output->set_content_type('application/json')->set_output(json_encode(array('message' => 'Email ini sudah terdaftar,..')));
            }
        }
	}
	

	// Controller Modul Produk

	function produk(){
		cek_session_members();
		$data['sitemap'] = $this->model_app->view_ordering_limit('rb_produk','id_produk','DESC',0,50);
		$this->load->view('administrator/sitemap',$data);
		
		verifikasi(reseller($this->session->id_konsumen));
		if (isset($_POST['submit'])){
			$jml = $this->model_app->view('rb_produk')->num_rows();
			for ($i=1; $i<=$jml; $i++){
				$a  = $_POST['a'][$i];
				$b  = $_POST['b'][$i];
				$cek = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$a,'id_reseller'=>reseller($this->session->id_konsumen)))->num_rows();
				if ($cek >= 1){
					if ($b > 0){
						$data = array('diskon'=>$b);
						$where = array('id_produk' => $a,'id_reseller' => reseller($this->session->id_konsumen));
						$this->model_app->update('rb_produk_diskon', $data, $where);
					}else{
						$this->model_app->delete('rb_produk_diskon',array('id_produk'=>$a,'id_reseller'=>reseller($this->session->id_konsumen)));
					}
				}else{
					if ($b > 0){
						$data = array('id_produk'=>$a,
			                          'id_reseller'=>reseller($this->session->id_konsumen),
			                          'diskon'=>$b);
						$this->model_app->insert('rb_produk_diskon',$data);
					}
				}
			}
			redirect($this->uri->segment(1).'/produk');
		}else{
			$this->session->unset_userdata('sesi_produk');
			$this->session->unset_userdata('sesi_produkx');
			if (isset($_GET['s'])){
				$jumlah = $this->db->query("SELECT * FROM rb_produk where id_reseller='".reseller($this->session->id_konsumen)."' AND nama_produk LIKE '%".cetak($_GET['s'])."%'")->num_rows();
			}else{
				$jumlah = $this->model_app->view_where('rb_produk',array('id_reseller'=>reseller($this->session->id_konsumen)))->num_rows();
			}
			$data['jumlah'] = $jumlah;
			$config['base_url'] = base_url().'members/produk';
			$config['total_rows'] = $jumlah;
			$config['per_page'] = 30; 	

			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

			if ($this->uri->segment('3')==''){
				$dari = 0;
			}else{
				$dari = $this->uri->segment('3');
			}

			$data['title'] = 'Produk Anda';

			if (isset($_GET['s'])){
				$data['record'] = $this->db->query("SELECT * FROM rb_produk where id_reseller='".reseller($this->session->id_konsumen)."' AND nama_produk LIKE '%".cetak($_GET['s'])."%' ORDER BY id_produk DESC LIMIT $dari,$config[per_page]");
				$data['record_cek'] = $this->db->query("SELECT * FROM rb_produk where id_reseller='".reseller($this->session->id_konsumen)."' AND nama_produk LIKE '%".cetak($_GET['s'])."%'");
			}else{
				$data['record'] = $this->model_app->view_where_ordering_limit('rb_produk',array('id_reseller'=>reseller($this->session->id_konsumen)),'id_produk','DESC',$dari,$config['per_page']);
				$data['record_cek'] = $this->model_app->view_where('rb_produk',array('id_reseller'=>reseller($this->session->id_konsumen)));
			}
			$this->pagination->initialize($config);
			$this->template->load(template().'/template',template().'/reseller/mod_produk/view_produk',$data);
		}
	}

	function tambah_produk(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
        if (isset($_POST['submit'])){
			if ($this->input->post('tag')!=''){
                $tag_seo = $this->input->post('tag');
                $tag=implode(',',$tag_seo);
            }else{
                $tag = '';
			}
			if (cetak($this->input->post('pre_order_status'))=='Tidak'){ $pre_order = NULL; }else{ $pre_order = cetak($this->input->post('pre_order')); }
			$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
			$cek_produk = $this->db->query("SELECT * FROM rb_produk where id_reseller='".reseller($this->session->id_konsumen)."'");
			$status = cek_paket(reseller($this->session->id_konsumen));
			if ($status>=1){
				$cekpa = $this->db->query("SELECT a.id_reseller, b.max_produk, b.nama_paket FROM `rb_reseller_paket` a JOIN rb_paket b ON a.id_paket=b.id_paket where a.status='Y' AND a.id_reseller='".reseller($this->session->id_konsumen)."'")->row_array();
				if ($cek_produk->num_rows()>=$cekpa['max_produk']){
					echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>PENTING</b> - Paket anda $cekpa[nama_paket] hanya bisa posting maksimal $cekpa[max_produk] Produk.</div>");
					redirect($this->uri->segment(1).'/upgrade');
				}else{
					if ($this->session->sesi_produk!=''){
						$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_produk."'")->row_array();
						$fileName = $rows['files'];
					}else{
						$fileName = '';
					}

					if ($this->session->sesi_produkx!=''){
						$rowsx = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_produkx."'")->row_array();
						$fileNamex = $rowsx['files'];
					}else{
						$fileNamex = NULL;
					}

					$cek_referral = $this->db->query("SELECT id_reseller FROM rb_reseller where id_reseller='".reseller($this->session->id_konsumen)."' AND referral!=''");
					if ($cek_referral->num_rows()>=1){
						if (cetak($this->input->post('d'))<='0'){
							echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>GAGAL</b> - Anda diwajibkan input Harga Modal Produk...</div>");
							redirect($this->uri->segment(1).'/produk');
						}
					}

					$produk_seo = seo_title($this->input->post('b')).'-'.date('His');
					$data = array('id_kategori_produk'=>cetak($this->input->post('a')),
								'id_kategori_produk_sub'=>cetak($this->input->post('aa')),
								'id_reseller'=>reseller($this->session->id_konsumen),
								'nama_produk'=>cetak($this->input->post('b')),
								'produk_seo'=>$produk_seo,
								'satuan'=>cetak($this->input->post('c')),
								'harga_beli'=>simpan_rupiah(cetak($this->input->post('d'))),
                              	'harga_reseller'=>simpan_rupiah(cetak($this->input->post('e'))),
                              	'harga_konsumen'=>simpan_rupiah(cetak($this->input->post('f'))),
								'harga_premium'=>simpan_rupiah(cetak($this->input->post('harga_premium'))),
								'harga_platform'=>simpan_rupiah(cetak($this->input->post('f')))-(simpan_rupiah(cetak($this->input->post('f')))*simpan_rupiah(cetak($this->input->post('flatform')))/100),
								'harga_platform_persen'=>simpan_rupiah(cetak($this->input->post('flatform'))),
								'berat'=>cetak($this->input->post('berat')),
								'gambar'=>$fileName,
								'tentang_produk'=>strip_tags($this->input->post('fff')),
								'keterangan'=>$this->input->post('ff'),
								'aktif'=>config('approve_produk'),
								'tag'=>$tag,
								'username'=>$this->session->username,
								'minimum'=>$this->input->post('minimum'),
								'sku'=>$this->input->post('sku'),
								'pre_order'=>$pre_order,
								'jenis_produk'=>$this->input->post('jenis_produk'),
								'produk_file'=>$fileNamex,
								'waktu_input'=>date('Y-m-d H:i:s'));
					
					$this->model_app->insert('rb_produk',$data);
					$id_produk = $this->db->insert_id();
					if (simpan_rupiah(cetak($this->input->post('diskon'))) > 0){
						$cek = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='".$id_produk."' AND id_reseller='".reseller($this->session->id_konsumen)."'");
						if ($cek->num_rows()>=1){
							$data = array('diskon'=>simpan_rupiah(cetak($this->input->post('diskon'))));
							$where = array('id_produk' => $id_produk,'id_reseller' => reseller($this->session->id_konsumen));
							$this->model_app->update('rb_produk_diskon', $data, $where);
						}else{
							$data = array('id_produk'=>$id_produk,
										'id_reseller'=>reseller($this->session->id_konsumen),
										'diskon'=>simpan_rupiah(cetak($this->input->post('diskon'))));
							$this->model_app->insert('rb_produk_diskon',$data);
						}
					}

					if ($this->input->post('jumlah')[0]!=''){
						for ($i=0; $i < count($this->input->post('jumlah')); $i++) { 
							if (cetak($this->input->post('jumlah')[$i])!=''){
								$warna = array('id_produk'=>$id_produk,
											'jumlah_group'=>cetak($this->input->post('jumlah')[$i]),
											'harga_group'=>simpan_rupiah(cetak($this->input->post('harga')[$i])));
								$this->model_app->insert('rb_produk_group',$warna);
							}
						}
					}

					if ($this->input->post('qtyl')[0]!=''){
						for ($i=0; $i < count($this->input->post('qtyl')); $i++) { 
							if (cetak($this->input->post('qtyl')[$i])!=''){
								$levelx = array('id_produk'=>$id_produk,
											'nama_level'=>cetak($this->input->post('namal')[$i]),
											'qty_level'=>cetak($this->input->post('qtyl')[$i]),
											'harga_level'=>simpan_rupiah(cetak($this->input->post('hargal')[$i])));
								$this->model_app->insert('rb_produk_level',$levelx);
							}
						}
					}

					if ($this->input->post('variasi1')!=''){
					    $i=0;
    				    $harga_potongan_platform = array();
    				    while ($i < count($this->input->post('hargaa'))) {
                            array_push($harga_potongan_platform, $this->input->post('hargaa')[$i]-(($this->input->post('hargaa')[$i]*$this->input->post('flatform'))/100));
                            $i++;
                        }
                        
						$warna = array('id_produk'=>$id_produk,
										'nama'=>cetak($this->input->post('variasi1')),
										'variasi'=>implode(";",$this->input->post('warna')),
										'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargaa'))),
										'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
										'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
									);
						$this->model_app->insert('rb_produk_variasi',$warna);
					}

					if ($this->input->post('variasi2')!=''){
					    $i=0;
    				    $harga_potongan_platform = array();
    				    while ($i < count($this->input->post('hargab'))) {
                            array_push($harga_potongan_platform, $this->input->post('hargab')[$i]-(($this->input->post('hargab')[$i]*$this->input->post('flatform'))/100));
                            $i++;
                        }
                        
						$ukuran = array('id_produk'=>$id_produk,
										'nama'=>cetak($this->input->post('variasi2')),
										'variasi'=>implode(";",$this->input->post('ukuran')),
										'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargab'))),
										'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
										'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
									);
						$this->model_app->insert('rb_produk_variasi',$ukuran);
					}

					if ($this->input->post('variasi3')!=''){
					    $i=0;
    				    $harga_potongan_platform = array();
    				    while ($i < count($this->input->post('hargac'))) {
                            array_push($harga_potongan_platform, $this->input->post('hargab')[$i]-(($this->input->post('hargab')[$i]*$this->input->post('flatform'))/100));
                            $i++;
                        }
                        
						$lainnya = array('id_produk'=>$id_produk,
										'nama'=>cetak($this->input->post('variasi3')),
										'variasi'=>implode(";",$this->input->post('lainnya')),
										'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargac'))),
									    'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
										'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
									);
						$this->model_app->insert('rb_produk_variasi',$lainnya);
					}


					if ((int)$this->input->post('stok') != '0'){
						$kode_transaksi = "TRX-".date('YmdHis');
						$data = array('kode_transaksi'=>$kode_transaksi,
									'id_pembeli'=>reseller($this->session->id_konsumen),
									'id_penjual'=>'1',
									'status_pembeli'=>'reseller',
									'status_penjual'=>'admin',
									'service'=>'Stok Otomatis (Pribadi)',
									'waktu_transaksi'=>date('Y-m-d H:i:s'),
									'proses'=>'4');
						$this->model_app->insert('rb_penjualan',$data);
						$idp = $this->db->insert_id();

						$data = array('id_penjualan'=>$idp,
									'id_produk'=>$id_produk,
									'jumlah'=>cetak($this->input->post('stok')),
									'harga_jual'=>cetak($this->input->post('e')),
									'satuan'=>cetak($this->input->post('c')));
						$this->model_app->insert('rb_penjualan_detail',$data);
					}
					$this->session->unset_userdata('sesi_produk');
					$this->session->unset_userdata('sesi_produkx');
					redirect($this->uri->segment(1).'/produk');
				}
				
			}else{
				if ($cek_produk->num_rows()>=$iden['free_reseller']){
					echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>PENTING</b> - Reseller dengan status FREE hanya bisa posting maksimal $iden[free_reseller] Produk.</div>");
					redirect($this->uri->segment(1).'/produk');
				}else{
					if ($this->session->sesi_produk!=''){
						$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_produk."'")->row_array();
						$fileName = $rows['files'];
					}else{
						$fileName = '';
					}

					if ($this->session->sesi_produkx!=''){
						$rowsx = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_produkx."'")->row_array();
						$fileNamex = $rowsx['files'];
					}else{
						$fileNamex = NULL;
					}

					$cek_referral = $this->db->query("SELECT id_reseller FROM rb_reseller where id_reseller='".reseller($this->session->id_konsumen)."' AND referral!=''");
					if ($cek_referral->num_rows()>=1){
						if (cetak($this->input->post('d'))<='0'){
							echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>GAGAL</b> - Anda diwajibkan input Harga Modal Produk...</div>");
							redirect($this->uri->segment(1).'/produk');
						}
					}

					$produk_seo = seo_title($this->input->post('b')).'-'.date('His');
					$data = array('id_kategori_produk'=>cetak($this->input->post('a')),
								'id_kategori_produk_sub'=>cetak($this->input->post('aa')),
								'id_reseller'=>reseller($this->session->id_konsumen),
								'nama_produk'=>cetak($this->input->post('b')),
								'produk_seo'=>cetak($produk_seo),
								'satuan'=>cetak($this->input->post('c')),
								'harga_beli'=>simpan_rupiah(cetak($this->input->post('d'))),
                              	'harga_reseller'=>simpan_rupiah(cetak($this->input->post('e'))),
                              	'harga_konsumen'=>simpan_rupiah(cetak($this->input->post('f'))),
								'harga_premium'=>simpan_rupiah(cetak($this->input->post('harga_premium'))),
								'harga_platform'=>simpan_rupiah(cetak($this->input->post('f')))-(simpan_rupiah(cetak($this->input->post('f')))*simpan_rupiah(cetak($this->input->post('flatform')))/100),
								'harga_platform_persen'=>simpan_rupiah(cetak($this->input->post('flatform'))),
								'berat'=>cetak($this->input->post('berat')),
								'gambar'=>$fileName,
								'tentang_produk'=>strip_tags($this->input->post('fff')),
								'keterangan'=>$this->input->post('ff'),
								'aktif'=>config('approve_produk'),
								'tag'=>$tag,
								'username'=>$this->session->username,
								'minimum'=>$this->input->post('minimum'),
								'sku'=>$this->input->post('sku'),
								'pre_order'=>$pre_order,
								'jenis_produk'=>$this->input->post('jenis_produk'),
								'produk_file'=>$fileNamex,
								'waktu_input'=>date('Y-m-d H:i:s'));
					
					$this->model_app->insert('rb_produk',$data);
					$id_produk = $this->db->insert_id();
					if (simpan_rupiah(cetak($this->input->post('diskon'))) > 0){
						$cek = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='".$id_produk."' AND id_reseller='".reseller($this->session->id_konsumen)."'");
						if ($cek->num_rows()>=1){
							$data = array('diskon'=>simpan_rupiah(cetak($this->input->post('diskon'))));
							$where = array('id_produk' => $id_produk,'id_reseller' => reseller($this->session->id_konsumen));
							$this->model_app->update('rb_produk_diskon', $data, $where);
						}else{
							$data = array('id_produk'=>$id_produk,
										'id_reseller'=>reseller($this->session->id_konsumen),
										'diskon'=>simpan_rupiah(cetak($this->input->post('diskon'))));
							$this->model_app->insert('rb_produk_diskon',$data);
						}
					}

					if ($this->input->post('jumlah')[0]!=''){
						for ($i=0; $i < count($this->input->post('jumlah')); $i++) { 
							if (cetak($this->input->post('jumlah')[$i])!=''){
								$warna = array('id_produk'=>$id_produk,
											'jumlah_group'=>cetak($this->input->post('jumlah')[$i]),
											'harga_group'=>simpan_rupiah(cetak($this->input->post('harga')[$i])));
								$this->model_app->insert('rb_produk_group',$warna);
							}
						}
					}

					if ($this->input->post('qtyl')[0]!=''){
						for ($i=0; $i < count($this->input->post('qtyl')); $i++) { 
							if (cetak($this->input->post('qtyl')[$i])!=''){
								$levelx = array('id_produk'=>$id_produk,
											'nama_level'=>cetak($this->input->post('namal')[$i]),
											'qty_level'=>cetak($this->input->post('qtyl')[$i]),
											'harga_level'=>simpan_rupiah(cetak($this->input->post('hargal')[$i])));
								$this->model_app->insert('rb_produk_level',$levelx);
							}
						}
					}

					if ($this->input->post('variasi1')!=''){
					    $i=0;
    				    $harga_potongan_platform = array();
    				    while ($i < count($this->input->post('hargaa'))) {
                            array_push($harga_potongan_platform, ($this->input->post('hargaa')[$i]*$this->input->post('flatform'))/100);
                            $i++;
                        }
                        
						$warna = array('id_produk'=>$id_produk,
										'nama'=>cetak($this->input->post('variasi1')),
										'variasi'=>implode(";",$this->input->post('warna')),
										'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargaa'))),
										'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
										'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
									);
						$this->model_app->insert('rb_produk_variasi',$warna);
					}

					if ($this->input->post('variasi2')!=''){
					    $i=0;
    				    $harga_potongan_platform = array();
    				    while ($i < count($this->input->post('hargab'))) {
                            array_push($harga_potongan_platform, ($this->input->post('hargab')[$i]*$this->input->post('flatform'))/100);
                            $i++;
                        }
                        
						$ukuran = array('id_produk'=>$id_produk,
										'nama'=>cetak($this->input->post('variasi2')),
										'variasi'=>implode(";",$this->input->post('ukuran')),
										'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargab'))),
										'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
										'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
									);
						$this->model_app->insert('rb_produk_variasi',$ukuran);
					}

					if ($this->input->post('variasi3')!=''){
					    $i=0;
    				    $harga_potongan_platform = array();
    				    while ($i < count($this->input->post('hargac'))) {
                            array_push($harga_potongan_platform, ($this->input->post('hargac')[$i]*$this->input->post('flatform'))/100);
                            $i++;
                        }
                        
						$lainnya = array('id_produk'=>$id_produk,
										'nama'=>cetak($this->input->post('variasi3')),
										'variasi'=>implode(";",$this->input->post('lainnya')),
										'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargac'))),
										'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
										'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
									);
						$this->model_app->insert('rb_produk_variasi',$lainnya);
					}


					if ((int)$this->input->post('stok') != '0'){
						$kode_transaksi = "TRX-".date('YmdHis');
						$data = array('kode_transaksi'=>$kode_transaksi,
									'id_pembeli'=>reseller($this->session->id_konsumen),
									'id_penjual'=>'1',
									'status_pembeli'=>'reseller',
									'status_penjual'=>'admin',
									'service'=>'Stok Otomatis (Pribadi)',
									'waktu_transaksi'=>date('Y-m-d H:i:s'),
									'proses'=>'4');
						$this->model_app->insert('rb_penjualan',$data);
						$idp = $this->db->insert_id();

						$data = array('id_penjualan'=>$idp,
									'id_produk'=>$id_produk,
									'jumlah'=>cetak($this->input->post('stok')),
									'harga_jual'=>cetak($this->input->post('e')),
									'satuan'=>cetak($this->input->post('c')));
						$this->model_app->insert('rb_penjualan_detail',$data);
					}
					$this->session->unset_userdata('sesi_produk');
					$this->session->unset_userdata('sesi_produkx');
					redirect($this->uri->segment(1).'/produk');
				}
			}
            
        }else{
			$data['title'] = 'Tambah Produk';
			$data['tag'] = $this->model_app->view_ordering('tagpro','id_tag','DESC');
            $data['record'] = $this->model_app->view_ordering('rb_kategori_produk','id_kategori_produk','DESC');
            $this->template->load(template().'/template',template().'/reseller/mod_produk/view_produk_tambah',$data);
        }
	}
	
	function kupon(){
		$id_produk = cetak($this->uri->segment(3));
		$kupon = $this->db->query("SELECT a.*,COALESCE(b.used, 0) as used FROM
		(SELECT * FROM rb_produk_kupon where id_produk='$id_produk') as a LEFT JOIN
		(select id_kupon, COUNT(*) used from rb_penjualan_kupon GROUP BY id_kupon HAVING COUNT(id_kupon)) as b on a.id_kupon=b.id_kupon")->result();
		echo json_encode($kupon);
	}

	function kupon_save(){
		if (cetak($this->input->post('a'))!=''){
			$data = array('id_produk'=>cetak($this->input->post('id_produk')),
						'kode_kupon'=>cetak($this->input->post('a')),
						'nilai_kupon'=>cetak(simpan_rupiah($this->input->post('d'))),
						'jumlah_kupon'=>cetak($this->input->post('b')),
						'min_order'=>cetak($this->input->post('e')),
						'expire_date'=>cetak($this->input->post('c')),
						'created_date'=>date('Y-m-d H:i:s'));
			if ($this->input->post('id_kupon')==''){
				$result = $this->model_app->insert('rb_produk_kupon',$data);
			}else{
				$where = array('id_kupon' => cetak($this->input->post('id_kupon')));
            	$result = $this->model_app->update('rb_produk_kupon', $data, $where);
			}
			echo json_encode($result);
		}
	}

	function kupon_used(){
		$cek_kupon = $this->db->query("SELECT * FROM rb_produk_kupon where kode_kupon='".cetak($this->input->post('kode_kupon'))."'");
		if ($cek_kupon->num_rows()>=1){
			$row = $cek_kupon->row_array();
			if ($row['id_produk']=='0'){
				$cek_keranjang = $this->db->query("SELECT sum((jumlah*harga_jual)-diskon) as jumlah FROM rb_penjualan_temp where session='".$this->session->idp."'");
				$rows = $cek_keranjang->row_array();
				if ($rows['jumlah']>=$row['min_order']){ // Validasi Min Belanja
					$expired = str_replace("-","",$row['expire_date']);
					if ($expired>=date('Ymd')){
						$terpakai = $this->db->query("SELECT * FROM rb_penjualan_kupon where id_kupon='$row[id_kupon]'");
						if ($terpakai->num_rows()<$row['jumlah_kupon']){
							$data = array('id_kupon_lain'=>$row['id_kupon']);
							$where = array('session'=>$this->session->idp);
							$result = $this->model_app->update('rb_penjualan_temp', $data, $where);
							echo json_encode(array('pesan'=>'x','nominal'=>$row['nilai_kupon']));
						}else{
							echo json_encode(array('pesan'=>'Kupon / Voucher yang anda diinput sudah tidak tersedia.'));
						}
					}else{
						echo json_encode(array('pesan'=>'Kupon / Voucher yang anda diinput sudah Kadaluarsa (Expire).'));
					}
				}else{
					echo json_encode(array('pesan'=>'Kupon / Voucher ini hanya bisa Digunakan untuk Minimal Order Rp '.rupiah($row['min_order']).''));
				}
			}else{
				$cek_keranjang = $this->db->query("SELECT * FROM rb_penjualan_temp where id_produk='$row[id_produk]' AND session='".$this->session->idp."'");
				if ($cek_keranjang->num_rows()>=1){
					$rows = $cek_keranjang->row_array();
					if ($rows['jumlah']>=$row['min_order']){
						$expired = str_replace("-","",$row['expire_date']);
						if ($expired>=date('Ymd')){
							$terpakai = $this->db->query("SELECT * FROM rb_penjualan_kupon where id_kupon='$row[id_kupon]'");
							if ($terpakai->num_rows()<$row['jumlah_kupon']){
								$data = array('id_kupon'=>$row['id_kupon']);
								$where = array('id_produk' => $row['id_produk'], 'session'=>$this->session->idp);
								$result = $this->model_app->update('rb_penjualan_temp', $data, $where);
								echo json_encode($result);
							}else{
								echo json_encode(array('pesan'=>'Kupon / Voucher yang anda diinput sudah tidak tersedia.'));
							}
						}else{
							echo json_encode(array('pesan'=>'Kupon / Voucher yang anda diinput sudah Kadaluarsa (Expire).'));
						}
					}else{
						echo json_encode(array('pesan'=>'Kupon / Voucher hanya bisa Digunakan untuk Minimal Order '.$row['min_order'].' Produk'));
					}
				}else{
					echo json_encode(array('pesan'=>'Produk untuk kupon / Voucher yang diinput tidak ada di keranjang anda.'));
				}
			}
		}else{
			echo json_encode(array('pesan'=>'Kode Kupon / Voucher Tidak ditemukan.'));
		}
	}

	function kupon_list(){
		$data = $this->db->query("SELECT a.id_penjualan_detail, b.* FROM rb_penjualan_temp a JOIN rb_produk_kupon b ON a.id_kupon=b.id_kupon where a.session='".$this->session->idp."' AND a.id_kupon!=''")->result();
		echo json_encode($data);
	}

	function kupon_list_pusat(){
		$data = $this->db->query("SELECT b.* FROM rb_penjualan_temp a JOIN rb_produk_kupon b ON a.id_kupon_lain=b.id_kupon where a.session='".$this->session->idp."' GROUP BY a.id_kupon_lain")->result();
		echo json_encode($data);
	}

	function kupon_list_sum_pusat(){
		$data = $this->db->query("SELECT b.nilai_kupon as total_nilai_kupon FROM rb_penjualan_temp a JOIN rb_produk_kupon b ON a.id_kupon_lain=b.id_kupon where a.session='".$this->session->idp."' GROUP BY a.id_kupon_lain")->result();
		echo json_encode($data);
	}

	function kupon_list_sum(){
		$data = $this->db->query("SELECT sum(b.nilai_kupon) as total_nilai_kupon FROM rb_penjualan_temp a JOIN rb_produk_kupon b ON a.id_kupon=b.id_kupon where a.session='".$this->session->idp."' AND a.id_kupon!=''")->result();
		echo json_encode($data);
	}

	function kupon_cart_delete(){
		$data = array('id_kupon'=>null);
		$where = array('id_penjualan_detail ' => cetak($this->input->post('id')));
		$result = $this->model_app->update('rb_penjualan_temp', $data, $where);
		echo json_encode($result);
	}

	function kupon_cart_deletex(){
		$data = array('id_kupon_lain'=>null);
		$where = array('session ' => cetak($this->session->idp));
		$result = $this->model_app->update('rb_penjualan_temp', $data, $where);
		echo json_encode($result);
	}

	function kupon_delete(){
		$cek = $this->db->query("SELECT * FROM rb_penjualan_kupon where id_kupon='".cetak($this->input->post('id'))."'");
		if ($cek->num_rows()<=0){
			$result = $this->db->query("DELETE FROM rb_produk_kupon where id_kupon='".cetak($this->input->post('id'))."'");
			echo json_encode($result);
		}
	}

    function edit_produk(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
        $id = cetak($this->uri->segment(3));
		if (isset($_GET['id'])){
			$idg = array('id_group' => cetak($this->input->get('id')));
			$this->model_app->delete('rb_produk_group',$idg);
			redirect($this->uri->segment(1).'/edit_produk/'.$this->uri->segment(3));
		}

		if (isset($_GET['idx'])){
			$idl = array('id_level' => cetak($this->input->get('idx')));
			$this->model_app->delete('rb_produk_level',$idl);
			redirect($this->uri->segment(1).'/edit_produk/'.$this->uri->segment(3));
		}

        if (isset($_POST['submit'])){
            if ($this->input->post('tag')!=''){
                $tag_seo = $this->input->post('tag');
                $tag=implode(',',$tag_seo);
            }else{
                $tag = '';
			}
			
            if ($this->session->sesi_produk!=''){
				$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_produk."'")->row_array();
				$fileName = $rows['files'];
			}else{
				$fileName = '';
			}

			if ($this->session->sesi_produkx!=''){
				$rowsx = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_produkx."'")->row_array();
				$fileNamex = $rowsx['files'];
			}else{
				$fileNamex = NULL;
			}

			$cek_referral = $this->db->query("SELECT id_reseller FROM rb_reseller where id_reseller='".reseller($this->session->id_konsumen)."' AND referral!=''");
			if ($cek_referral->num_rows()>=1){
				if (cetak($this->input->post('d'))<='0'){
					echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><b>GAGAL</b> - Anda diwajibkan input Harga Modal Produk...</div>");
					redirect($this->uri->segment(1).'/produk');
				}
			}

			if (cetak($this->input->post('pre_order_status'))=='Tidak'){ $pre_order = NULL; }else{ $pre_order = cetak($this->input->post('pre_order')); }

			if ($fileName!=''){
                $data = array('id_kategori_produk'=>cetak($this->input->post('a')),
                			  'id_kategori_produk_sub'=>cetak($this->input->post('aa')),
                              'nama_produk'=>cetak($this->input->post('b')),
                              'satuan'=>cetak($this->input->post('c')),
                              'harga_beli'=>simpan_rupiah(cetak($this->input->post('d'))),
                              'harga_reseller'=>simpan_rupiah(cetak($this->input->post('e'))),
                              'harga_konsumen'=>simpan_rupiah(cetak($this->input->post('f'))),
							  'harga_premium'=>simpan_rupiah(cetak($this->input->post('harga_premium'))),
							  'harga_platform'=>simpan_rupiah(cetak($this->input->post('f')))-(simpan_rupiah(cetak($this->input->post('f')))*simpan_rupiah(cetak($this->input->post('flatform')))/100),
							   'harga_platform_persen'=>simpan_rupiah(cetak($this->input->post('flatform'))),
                              'berat'=>cetak($this->input->post('berat')),
							  'gambar'=>$fileName,
							  'tentang_produk'=>strip_tags($this->input->post('fff')),
							  'keterangan'=>$this->input->post('ff'),
							  'tag'=>$tag,
							  'minimum'=>$this->input->post('minimum'),
							  'sku'=>$this->input->post('sku'),
							  'pre_order'=>$pre_order,
							  'jenis_produk'=>$this->input->post('jenis_produk'),
							  'produk_file'=>$fileNamex,
                              'username'=>reseller($this->session->id_konsumen));
			}else{
				$data = array('id_kategori_produk'=>cetak($this->input->post('a')),
                			  'id_kategori_produk_sub'=>cetak($this->input->post('aa')),
                              'nama_produk'=>cetak($this->input->post('b')),
                              'satuan'=>cetak($this->input->post('c')),
                              'harga_beli'=>simpan_rupiah(cetak($this->input->post('d'))),
                              'harga_reseller'=>simpan_rupiah(cetak($this->input->post('e'))),
                              'harga_konsumen'=>simpan_rupiah(cetak($this->input->post('f'))),
							  'harga_premium'=>simpan_rupiah(cetak($this->input->post('harga_premium'))),
							  'harga_platform'=>simpan_rupiah(cetak($this->input->post('f')))-(simpan_rupiah(cetak($this->input->post('f')))*simpan_rupiah(cetak($this->input->post('flatform')))/100),
							   'harga_platform_persen'=>simpan_rupiah(cetak($this->input->post('flatform'))),
                              'berat'=>cetak($this->input->post('berat')),
							  'tentang_produk'=>strip_tags($this->input->post('fff')),
							  'keterangan'=>$this->input->post('ff'),
							  'tag'=>$tag,
							  'minimum'=>$this->input->post('minimum'),
							  'sku'=>$this->input->post('sku'),
							  'pre_order'=>$pre_order,
							  'jenis_produk'=>$this->input->post('jenis_produk'),
							  'produk_file'=>$fileNamex,
                              'username'=>reseller($this->session->id_konsumen));
			}
            $where = array('id_produk' => cetak($this->input->post('id')),'id_reseller'=>reseller($this->session->id_konsumen));
            $this->model_app->update('rb_produk', $data, $where);

            if (simpan_rupiah(cetak($this->input->post('diskon'))) >= 0){
            	$cek = $this->db->query("SELECT * FROM rb_produk_diskon where id_produk='".cetak($this->input->post('id'))."' AND id_reseller='".reseller($this->session->id_konsumen)."'");
				if ($cek->num_rows()>=1){
					$data = array('diskon'=>simpan_rupiah(cetak($this->input->post('diskon'))));
					$where = array('id_produk' => cetak($this->input->post('id')),'id_reseller' => reseller($this->session->id_konsumen));
					$this->model_app->update('rb_produk_diskon', $data, $where);
				}else{
					$data = array('id_produk'=>cetak($this->input->post('id')),
			                      'id_reseller'=>reseller($this->session->id_konsumen),
			                      'diskon'=>simpan_rupiah(cetak($this->input->post('diskon'))));
					$this->model_app->insert('rb_produk_diskon',$data);
				}
			}


			$id_produk = cetak($this->input->post('id'));
			$id_variasi = array('id_produk' => $id_produk);

			$cek_akses = $this->db->query("SELECT * FROM rb_produk where id_produk='".cetak($this->input->post('id'))."' AND id_reseller='".reseller($this->session->id_konsumen)."'");
			if ($cek_akses->num_rows()>=1){

				$this->model_app->delete('rb_produk_variasi',$id_variasi);

				if ($this->input->post('jumlah')[0]!=''){
					for ($i=0; $i < count($this->input->post('jumlah')); $i++) { 
						if (cetak($this->input->post('jumlah')[$i])!=''){
							$group_data = array('id_produk'=>$id_produk,
											'jumlah_group'=>cetak($this->input->post('jumlah')[$i]),
											'harga_group'=>simpan_rupiah(cetak($this->input->post('harga')[$i])));
							if (cetak($this->input->post('id_group')[$i])=='0'){
								$this->model_app->insert('rb_produk_group',$group_data);
							}else{
								$where_group = array('id_group' => cetak($this->input->post('id_group')[$i]));
            					$this->model_app->update('rb_produk_group', $group_data, $where_group);
							}
						}
					}
				}

				if ($this->input->post('qtyl')[0]!=''){
					for ($i=0; $i < count($this->input->post('qtyl')); $i++) {
						if (cetak($this->input->post('qtyl')[$i])!=''){
							$levelx = array('id_produk'=>$id_produk,
										'nama_level'=>cetak($this->input->post('namal')[$i]),
										'qty_level'=>cetak($this->input->post('qtyl')[$i]),
										'harga_level'=>simpan_rupiah(cetak($this->input->post('hargal')[$i])));
							if (cetak($this->input->post('id_level')[$i])=='0'){
								$this->model_app->insert('rb_produk_level',$levelx);
							}else{
								$where_level = array('id_level' => cetak($this->input->post('id_level')[$i]));
            					$this->model_app->update('rb_produk_level', $levelx, $where_level);
							}
						}
					}
				}

				if ($this->input->post('variasix1')!=''){
				    $i=0;
				    $harga_potongan_platform = array();
				    while ($i < count($this->input->post('hargaa1'))) {
                        array_push($harga_potongan_platform, ($this->input->post('hargaa1')[$i]*$this->input->post('flatform'))/100);
                        $i++;
                    }
				    
					$warna = array('id_produk'=>$id_produk,
									'nama'=>cetak($this->input->post('variasix1')),
									'variasi'=>implode(";",$this->input->post('variasi1')),
									'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargaa1'))),
									'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
									'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
								);
					$this->model_app->insert('rb_produk_variasi',$warna);
				}

				if ($this->input->post('variasix2')!=''){
				    $i=0;
				    $harga_potongan_platform = array();
				    while ($i < count($this->input->post('hargab2'))) {
                        array_push($harga_potongan_platform, ($this->input->post('hargab2')[$i]*$this->input->post('flatform'))/100);
                        $i++;
                    }
					$ukuran = array('id_produk'=>$id_produk,
									'nama'=>cetak($this->input->post('variasix2')),
									'variasi'=>implode(";",$this->input->post('variasi2')),
									'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargab2'))),
								    'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
									'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
								);
					$this->model_app->insert('rb_produk_variasi',$ukuran);
				}

				if ($this->input->post('variasix3')!=''){
				    $i=0;
				    $harga_potongan_platform = array();
				    while ($i < count($this->input->post('hargac3'))) {
                        array_push($harga_potongan_platform, ($this->input->post('hargac3')[$i]*$this->input->post('flatform'))/100);
                        $i++;
                    }
					$lainnya = array('id_produk'=>$id_produk,
									'nama'=>cetak($this->input->post('variasix3')),
									'variasi'=>implode(";",$this->input->post('variasi3')),
									'variasi_harga'=>simpan_rupiah(implode(";",$this->input->post('hargac3'))),
									'harga_platform_persen'=>simpan_rupiah($this->input->post('flatform')),
									'harga_platform'=>simpan_rupiah(implode(";",$harga_potongan_platform))
								);
					$this->model_app->insert('rb_produk_variasi',$lainnya);
				}
			}

			if ($this->input->post('stok') != '0'){
				$kode_transaksi = "TRX-".date('YmdHis');
				$data1 = array('kode_transaksi'=>$kode_transaksi,
			        		  'id_pembeli'=>reseller($this->session->id_konsumen),
			        		  'id_penjual'=>'1',
			        		  'status_pembeli'=>'reseller',
							  'status_penjual'=>'admin',
							  'no_resi'=>'-',
							  'kode_kurir'=>'-',
							  'kurir'=>'-',
							  'service'=>'Stok Otomatis (Pribadi)',
							  'ongkir'=>'0',
							  'keterangan'=>'-',
			        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
			        		  'proses'=>'4');
				$this->model_app->insert('rb_penjualan',$data1);
				$idp = $this->db->insert_id();

				$data2 = array('id_penjualan'=>$idp,
		        			  'id_produk'=>cetak($this->input->post('id')),
							  'jumlah'=>cetak($this->input->post('stok')),
							  'diskon'=>'0',
		        			  'harga_jual'=>cetak($this->input->post('e')),
							  'satuan'=>cetak($this->input->post('c')),
							  'keterangan_order'=>'-');
				$this->model_app->insert('rb_penjualan_detail',$data2);
			}

			$this->session->unset_userdata('sesi_produk');
			$this->session->unset_userdata('sesi_produkx');
            redirect($this->uri->segment(1).'/produk');
			
        }else{
			$data['title'] = 'Edit Produk';
			$data['tag'] = $this->model_app->view_ordering('tagpro','id_tag','DESC');
            $data['record'] = $this->model_app->view_ordering('rb_kategori_produk','id_kategori_produk','DESC');
            $data['rows'] = $this->model_app->edit('rb_produk',array('id_produk'=>$id,'id_reseller'=>reseller($this->session->id_konsumen)))->row_array();
            $this->template->load(template().'/template',template().'/reseller/mod_produk/view_produk_edit',$data);
        }
    }

    private function set_upload_options(){
        $config = array();
        $config['upload_path'] = 'asset/foto_produk/';
        $config['allowed_types'] = 'gif|webp|WEBP|jpg|png|jpeg';
        $config['max_size'] = '5000'; // kb
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);
      return $config;
    }

	function delete_produk(){
		cek_session_members();
        $id = array('id_produk' => cetak($this->uri->segment('3')),'id_reseller'=>reseller($this->session->id_konsumen));
        $this->model_app->delete('rb_produk',$id);
		redirect($this->uri->segment(1).'/produk');
	}

	function delete_group(){
		cek_session_members();
        $id = array('id_group' => cetak($this->input->post('id_group')));
        $this->model_app->delete('rb_produk_group',$id);
	}

	public function deleteFilex(){
		cek_session_members();
		$name = $this->input->post('name');
		$filePath = 'asset/foto_produk/'.$name;
		if($name){
			if (file_exists($filePath)){
				unlink($filePath); // delete file from dir
		    }
			$this->db->delete('img_comment', array('file_name' => $name));
		}

		echo "Deleted File ".$name."<br>";
	}

	public function uploadx(){
        cek_session_members();
        $this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_produkx==''){
			$id = $this->session->id_konsumen.'-produkx-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_produkx'=>$id));
		}else{
			$id = $this->session->sesi_produkx;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/foto_produk/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'zip|rar|tar';
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

	public function deleteFile(){
		cek_session_members();
		$name = $this->input->post('name');
		$filePath = 'asset/foto_produk/'.$name;
		$thumb_filePath = 'asset/foto_produk/thumb_'.$name;
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

	public function upload(){
        cek_session_members();
        $this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_produk==''){
			$id = $this->session->id_konsumen.'-produk-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_produk'=>$id));
		}else{
			$id = $this->session->sesi_produk;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/foto_produk/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg|jpeg|png|txt|pdf|gif|webp|WEBP|zip|rar|tar';
			$config['max_size']	= '50000'; // kb

            // Load and initialize upload library
            $this->load->library('upload', $config);
            $this->upload->initialize($config);	

	 	 	$fileName = $_FILES["uploadFile"]["name"];

            // Upload file to server
            if($this->upload->do_upload('uploadFile')){
				$fileData = $this->upload->data();
				//Compress Image
				$config['image_library']='gd2';
				$config['source_image']='./asset/foto_produk/'.$fileData['file_name'];
				$config['create_thumb']= FALSE;
				$config['maintain_ratio']= FALSE;
				$config['quality']= '50%';
				$config['width']= 191;
				$config['height']= 171;
				$config['new_image']= './asset/foto_produk/thumb_'.$fileData['file_name'];
				$this->load->library('image_lib', $config);
				$this->image_lib->resize();

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
	
	
	public function deleteFile_video(){
		cek_session_members();
		$name = $this->input->post('name');
		$filePath = 'asset/img_video/'.$name;
		if($name){
			if (file_exists($filePath)){
				unlink($filePath); // delete file from dir
		    }
			$this->db->delete('img_comment', array('file_name' => $name));
		}

		echo "Deleted File ".$name."<br>";
	}

	public function upload_video(){
        cek_session_members();
        $this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_video==''){
			$id = $this->session->id_konsumen.'-video-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_video'=>$id));
		}else{
			$id = $this->session->sesi_video;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/img_video/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'mp4|MP4';
			$config['max_size']	= '20000'; // kb

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


	public function deleteFile_paket(){
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

	public function upload_paket(){
        cek_session_members();
        $this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_paket==''){
			$id = $this->session->id_konsumen.'-paket-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_paket'=>$id));
		}else{
			$id = $this->session->sesi_paket;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/files/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size']	= '5000'; // kb

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


	public function deleteFile_syarat(){
		cek_session_members();
		$name = $this->input->post('name');
		$filePath = 'asset/images/'.$name;
		if($name){
			if (file_exists($filePath)) 
			{
		        unlink($filePath); // delete file from dir
		    }
			$this->db->delete('img_comment', array('file_name' => $name));
		}

		echo "Deleted File ".$name."<br>";
	}

	public function upload_syarat(){
        cek_session_members();
        $this->load->model('imgComment');
		$data = array();
		if ($this->session->sesi_syarat==''){
			$id = $this->session->id_konsumen.'-syarat-'.date('Ymdhis');
			$this->session->set_userdata(array('sesi_syarat'=>$id));
		}else{
			$id = $this->session->sesi_syarat;
		}
        if(isset($_FILES['uploadFile'])){
        	// File upload configuration
            $uploadPath = 'asset/images/';
            $config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg|jpeg|png|txt|pdf|gif|webp|WEBP|zip|rar|tar';
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
	
	// Upload image summernote
    function upload_image(){
		cek_session_members();
        if(isset($_FILES["image"]["name"])){
            $config['upload_path'] = 'asset/images/';
            $config['allowed_types'] = 'gif|webp|WEBP|jpg|png|JPG|JPEG|swf';
            $config['max_size'] = '3000'; // kb
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('image')){
                $this->upload->display_errors();
                return FALSE;
            }else{
                $data = $this->upload->data();
                //Compress Image
                $config['image_library']='gd2';
                $config['source_image']='asset/images/'.$data['file_name'];
                $config['create_thumb']= FALSE;
                $config['maintain_ratio']= TRUE;
                $config['quality']= '60%';
                $config['width']= 800;
                $config['height']= 800;
                $config['new_image']= 'asset/images/thumb_'.$data['file_name'];
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                echo base_url().'asset/images/'.$data['file_name'];
            }
        }
	}
	
	// Controller Modul COD

	function alamat_cod(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		$data['title'] = "Alamat Cash on Delivery (COD)";
		$data['record'] = $this->model_app->view_where('rb_reseller_cod',array('id_reseller'=>reseller($this->session->id_konsumen)));
		$this->template->load(template().'/template',template().'/reseller/mod_alamat_cod/view',$data);
	}

	function tambah_cod(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		if (isset($_POST['submit'])){
			$data = array('id_reseller'=>reseller($this->session->id_konsumen),
			              'nama_alamat'=>cetak($this->input->post('a')),
			              'biaya_cod'=>cetak($this->input->post('b')));
						$this->model_app->insert('rb_reseller_cod',$data);
			redirect($this->uri->segment(1).'/alamat_cod');
		}else{
			$data['title'] = "Tambah Alamat Cash on Delivery (COD)";
			$this->template->load(template().'/template',template().'/reseller/mod_alamat_cod/tambah',$data);
		}
	}

	function edit_cod(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		$id = cetak($this->uri->segment(3));
		if (isset($_POST['submit'])){
			$data = array('id_reseller'=>reseller($this->session->id_konsumen),
			              'nama_alamat'=>cetak($this->input->post('a')),
			              'biaya_cod'=>cetak($this->input->post('b')));
			$where = array('id_cod' => cetak($this->input->post('id')),'id_reseller' => reseller($this->session->id_konsumen));
			$this->model_app->update('rb_reseller_cod', $data, $where);
			redirect($this->uri->segment(1).'/alamat_cod');
		}else{
			$data['title'] = "Edit Alamat Cash on Delivery (COD)";
			$data['rows'] = $this->model_app->edit('rb_reseller_cod',array('id_cod'=>$id))->row_array();
			$this->template->load(template().'/template',template().'/reseller/mod_alamat_cod/edit',$data);
		}
	}

	function delete_cod(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		$id = array('id_cod' => cetak($this->uri->segment(3)),'id_reseller' => reseller($this->session->id_konsumen));
		$this->model_app->delete('rb_reseller_cod',$id);
		redirect($this->uri->segment(1).'/alamat_cod');
	}
 
    //Delete image summernote
    function delete_image(){
        $src = cetak($this->input->post('src'));
        $file_name = str_replace(base_url(), '', $src);
        if(unlink($file_name)){
            echo 'File Delete Successfully';
        }
	}
	

	// Controller Modul Pembelian

	function pembelian(){
		cek_session_members();
		if (config('reseller')=='N'){ redirect('main'); }
		verifikasi(reseller($this->session->id_konsumen));
		$this->session->unset_userdata('idp');
		$data['title'] = "Data Pembelian Ke Pusat";
		$data['record'] = $this->model_reseller->reseller_pembelian(reseller($this->session->id_konsumen),'admin');
		$this->template->load(template().'/template',template().'/reseller/mod_pembelian/view_pembelian',$data);
	}

	function detail_pembelian(){
		cek_session_members();
		if (config('reseller')=='N'){ redirect('main'); }
		verifikasi(reseller($this->session->id_konsumen));
		$data['title'] = "Detail Data Pembelian Ke Pusat";
		$data['rows'] = $this->model_reseller->penjualan_detail(cetak($this->uri->segment(3)))->row_array();
		$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>cetak($this->uri->segment(3))),'id_penjualan_detail','DESC');
		$this->template->load(template().'/template',template().'/reseller/mod_pembelian/view_pembelian_detail',$data);
	}

	function tambah_pembelian(){
		cek_session_members();
		if (config('reseller')=='N'){ redirect('main'); }
		verifikasi(reseller($this->session->id_konsumen));
		if(isset($_POST['submit'])){
			if ($this->input->post('aa')!=''){
				$id_produk = cetak($this->input->post('aa'));
				$jumlah = cetak($this->input->post('dd'));
				$harga_jual = cetak($this->input->post('bb'));
				$satuan = cetak($this->input->post('ee'));
			}else{
				$id_produk = cetak($this->uri->segment(3));
				$resp = $this->model_app->view_where('rb_produk',array('id_produk'=>$id_produk))->row_array();
				if (cetak($this->input->post('qty'))>=$resp['minimum']){
					$jumlah = cetak($this->input->post('qty'));
				}else{
					$jumlah = $resp['minimum'];
					echo $this->session->set_flashdata('message', "<div class='alert alert-info'><center><b>INFORMASI</b> - Minimal Order Produk ini $resp[minimum] $resp[satuan].</center></div>");
				}
				$harga_jual = $resp['harga_reseller'];
				$satuan = $resp['satuan'];
			}

			if ($this->session->idpx == ''){
				$kode_transaksixe = "TRX-".date('YmdHis');
				$data = array('kode_transaksi'=>$kode_transaksixe,
			        		  'id_pembeli'=>reseller($this->session->id_konsumen),
			        		  'id_penjual'=>'1',
			        		  'status_pembeli'=>'reseller',
			        		  'status_penjual'=>'admin',
			        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
							  'proses'=>'0',
							  'service'=>'TRX-Reseller Produk (Transfer)');
				$this->model_app->insert('rb_penjualan',$data);
				$idp = $this->db->insert_id();
				$this->session->set_userdata(array('idpx'=>$idp,'kode_transaksi'=>$kode_transaksixe));
			}

			$var1 = cetak($this->input->post('variasi_1'));
			$var2 = cetak($this->input->post('variasi_2'));
			$var3 = cetak($this->input->post('variasi_3'));

			$variasi_nominal = cetak($this->input->post('warnax')+$this->input->post('ukuranx')+$this->input->post('lainnyax'));
			$keterangan_order = "||".($var1 != '' ? $var1.'; ' : '').($var2 != '' ? $var2.'; ' : '').($var3 != '' ? $var3.'; ' : '');

			$ro = $this->db->query("SELECT * FROM rb_penjualan_detail where id_produk='".cetak($this->uri->segment(3))."' AND id_penjualan='".$this->session->idpx."'");
	        if ($ro->num_rows()<=0){
				$data = array('id_penjualan'=>$this->session->idpx,
		        			  'id_produk'=>$id_produk,
		        			  'jumlah'=>$jumlah,
		        			  'harga_jual'=>($harga_jual+$variasi_nominal),
							  'keterangan_order'=>$keterangan_order,
		        			  'satuan'=>$satuan);
				$this->model_app->insert('rb_penjualan_detail',$data);
			}else{
				$rw = $ro->row_array();
		        $data = array('jumlah'=>($rw['jumlah']+$jumlah));
				$where = array('id_produk' => $id_produk,'id_penjualan'=>$this->session->idpx);
				$this->model_app->update('rb_penjualan_detail', $data, $where);
			}
			redirect($this->uri->segment(1).'/tambah_pembelian');

		}elseif(isset($_POST['selesai'])){
			if ($this->input->post('metode')=='saldo'){
				$idp = $this->session->idpx;
				$total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='".$idp."'")->row_array();
				if (saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen)>=$total['total']){
					$kode_transaksi = $this->session->kode_transaksi;

					$datax = array('proses'=>1,'service'=>'TRX-Reseller Produk (Saldo)');
					$wherex = array('id_penjualan' => $idp);
					$this->model_app->update('rb_penjualan', $datax, $wherex);

					$data = array('id_rekening_reseller'=>0,
						  'id_reseller'=>reseller($this->session->id_konsumen),
			              'nominal'=>$total['total'],
						  'status'=>'Sukses',
						  'transaksi'=>'Debit',
						  'keterangan'=>$kode_transaksi,
			              'waktu_withdraw'=>date('Y-m-d H:i:s'));
					$this->model_app->insert('rb_withdraw',$data);
				}else{
					echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center><b>SALDO TIDAK MENCUKUPI</b> - GAGAL Order dengan saldo..</center></div>");	
				}
			}
			$this->session->unset_userdata('idpx');
			redirect($this->uri->segment(1).'/pembelian');

		}else{
			$data['title'] = "Tambah Pembelian Ke Pusat";
			$data['rows'] = $this->model_reseller->penjualan_detail($this->session->idpx)->row_array();
			$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idpx),'id_penjualan_detail','DESC');
			$data['barang'] = $this->model_app->view_where_ordering('rb_produk',array('id_reseller'=>'0','id_produk_perusahaan'=>'0'),'id_produk','ASC');
			$data['reseller'] = $this->model_app->view_ordering('rb_reseller','id_reseller','ASC');
			if ($this->uri->segment(3)!=''){
				$data['row'] = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>$this->uri->segment(3)))->row_array();
			}
			$this->template->load(template().'/template',template().'/reseller/mod_pembelian/view_pembelian_tambah',$data);
		}
	}

	function delete_pembelian(){
		cek_session_members();
		if (config('reseller')=='N'){ redirect('main'); }
		verifikasi(reseller($this->session->id_konsumen));
		$id = array('id_penjualan' => cetak($this->uri->segment(3)));
		$this->model_app->delete('rb_penjualan',$id);
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/pembelian');
	}

	function delete_pembelian_tambah_detail(){
		cek_session_members();
		if (config('reseller')=='N'){ redirect('main'); }
		verifikasi(reseller($this->session->id_konsumen));
		$id = array('id_penjualan_detail' => $this->uri->segment(3));
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/tambah_pembelian');
	}

	function konfirmasi_pembayaran(){
		cek_session_members();
		if (config('reseller')=='N'){ redirect('main'); }
		verifikasi(reseller($this->session->id_konsumen));
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/files/';
            $config['allowed_types'] = 'gif|webp|WEBP|jpg|png|jpeg';
            $config['max_size'] = '10000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
				$data = array('id_penjualan'=>cetak($this->input->post('id')),
			        		  'total_transfer'=>cetak($this->input->post('b')),
			        		  'id_rekening'=>cetak($this->input->post('c')),
			        		  'nama_pengirim'=>cetak($this->input->post('d')),
			        		  'tanggal_transfer'=>cetak($this->input->post('e')),
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran',$data);
			}else{
				$data = array('id_penjualan'=>cetak($this->input->post('id')),
			        		  'total_transfer'=>cetak($this->input->post('b')),
			        		  'id_rekening'=>cetak($this->input->post('c')),
			        		  'nama_pengirim'=>cetak($this->input->post('d')),
			        		  'tanggal_transfer'=>cetak($this->input->post('e')),
			        		  'bukti_transfer'=>$hasil['file_name'],
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran',$data);
			}
				$data1 = array('proses'=>'2');
				$where = array('id_penjualan' => cetak($this->input->post('id')));
				$this->model_app->update('rb_penjualan', $data1, $where);
			redirect($this->uri->segment(1).'/pembelian');
		}else{
			$data['title'] = "Konfirmasi Pembayaran Pembelian Ke Pusat";
			$data['record'] = $this->model_app->view('rb_rekening');
			$data['total'] = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='".cetak($this->uri->segment(3))."'")->row_array();
			$data['rows'] = $this->model_app->view_where('rb_penjualan',array('id_penjualan'=>cetak($this->uri->segment(3))))->row_array();
			$this->template->load(template().'/template',template().'/reseller/mod_pembelian/view_konfirmasi_pembayaran',$data);
		}
	}

	function grouporder(){
		if ($this->session->id_konsumen!=''){
			cek_session_members();
			$id = reseller($this->session->id_konsumen);
			$data['records'] = $this->model_reseller->group_order($id,'reseller',cetak($this->input->post('id')));
			$this->load->view(template().'/reseller/mod_penjualan/view_penjualan_group',$data);
		}else{
			echo "<center style='padding:60px 0px'>Saat ini anda tidak memiliki Akses...<br> Silahkan <a style='font-weight:bold; text-decoration:underline' href='".base_url()."auth/login'>Login</a> Terlebih dahulu..</center>";
		}
	}


	function penjualan(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		$this->session->unset_userdata('idp');
		$id = reseller($this->session->id_konsumen);
		$data['title'] = "Data Penjualan";
		$data['menunggu'] = $this->model_reseller->menunggu_pembayaran($id,'reseller');
		$data['diterima'] = $this->model_reseller->penjualan_status_tanpabayar($id,'reseller','1');
		$data['batal'] = $this->model_reseller->penjualan_status_tanpabayar($id,'reseller','x');
		$data['dikirim'] = $this->model_reseller->penjualan_status_tanpabayar($id,'reseller','3');
		$data['selesai'] = $this->model_reseller->penjualan_status_tanpabayar($id,'reseller','4');
		$this->template->load(template().'/template',template().'/reseller/mod_penjualan/view_penjualan',$data);
	}

	function detail_penjualan(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		if (isset($_POST['submit'])){
			$data1 = array('no_resi'=>cetak($this->input->post('no_resi')),
							'proses'=>'3');
			$where = array('id_penjualan' => cetak($this->uri->segment(3)),'id_penjual'=>reseller($this->session->id_konsumen));
			$this->model_app->update('rb_penjualan', $data1, $where);
			notif_pesanan_dikirim(cetak($this->uri->segment(3)));
			redirect($this->uri->segment(1).'/detail_penjualan/'.$this->uri->segment(3));
		}else{
			$data['title'] = "Detail Data Penjualan";
			$data['rows'] = $this->model_reseller->penjualan_konsumen_detail_reseller(cetak($this->uri->segment(3)))->row_array();
			$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>cetak($this->uri->segment(3))),'id_penjualan_detail','ASC');
			if (isset($_GET['print'])){
				$this->load->view(template().'/reseller/print_label',$data);
			}else{
				$this->template->load(template().'/template',template().'/reseller/mod_penjualan/view_penjualan_detail',$data);
			}
		}
	}

	function tolak_penjualan(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		$data1 = array('proses'=>'x');
		$where = array('id_penjualan' => cetak($this->uri->segment(3)),'id_penjual'=>reseller($this->session->id_konsumen));
		$this->model_app->update('rb_penjualan', $data1, $where);

		$rek = $this->db->query("SELECT id_rekening FROM `rb_rekening` ORDER BY RAND() DESC LIMIT 1")->row_array();
		$row = $this->db->query("SELECT a.jumlah, a.diskon, a.harga_jual, sum((a.jumlah*a.harga_jual)-a.diskon) as total_belanja FROM rb_penjualan_detail a where a.id_penjualan='".cetak($this->uri->segment(3))."'")->row_array();
		$ong = $this->db->query("SELECT kode_transaksi, ongkir, id_pembeli FROM rb_penjualan where id_penjualan='".cetak($this->uri->segment(3))."'")->row_array();
		$cek_pembayaran = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='$ong[kode_transaksi]'")->row_array();
		$cek_pembayaran_saldo = $this->db->query("SELECT * FROM rb_withdraw where keterangan='$ong[kode_transaksi]' AND status='Sukses' AND transaksi='debit'");
		
		if ($cek_pembayaran['saldo']=='0' AND $cek_pembayaran['pembayaran']=='1'){ // Jika Pembayaran dengan Saldo + Transfer
			$cps = $cek_pembayaran_saldo->row_array();
			$dana_kembali = $cek_pembayaran['nominal']+$cps['nominal'];
		}elseif ($cek_pembayaran['saldo']=='1' AND $cek_pembayaran['pembayaran']=='1'){  // Jika Pembayaran dengan Full Saldo
			$dana_kembali = $cek_pembayaran['nominal'];
		}elseif ($cek_pembayaran['saldo']=='1' AND $cek_pembayaran['pembayaran']==''){  // Jika Pembayaran dengan Saldo dan Belum Transfer
			$cps = $cek_pembayaran_saldo->row_array();
			$dana_kembali = $cps['nominal'];
		}elseif ($cek_pembayaran['saldo']=='2' AND $cek_pembayaran['pembayaran']=='1'){ // Jika Pembayaran tanpa Saldo
			$dana_kembali = $cek_pembayaran['nominal'];
		}

		if ($dana_kembali>0){
			$data_refund = array('id_rekening_reseller'=>$rek['id_rekening'],
					'id_reseller'=>$ong['id_pembeli'],
					'nominal'=>$dana_kembali,
					'status'=>'Sukses',
					'transaksi'=>'Kredit',
					'keterangan'=>"Refund Orders $ong[kode_transaksi]",
					'akun'=>'konsumen',
					'waktu_withdraw'=>date('Y-m-d H:i:s'));
			$this->model_app->insert('rb_withdraw',$data_refund);
		}

		notif_order_cancel(cetak($this->uri->segment(3)),'x');
		redirect($this->uri->segment(1).'/penjualan?page=batal');
	}

	function tambah_penjualan(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		if (isset($_POST['submit1'])){
			if ($this->session->idp == ''){
				$data = array('kode_transaksi'=>cetak($this->input->post('a')),
			        		  'id_pembeli'=>cetak($this->input->post('b')),
			        		  'id_penjual'=>reseller($this->session->id_konsumen),
			        		  'status_pembeli'=>'konsumen',
			        		  'status_penjual'=>'reseller',
			        		  'waktu_transaksi'=>date('Y-m-d H:i:s'),
			        		  'proses'=>'0');
				$this->model_app->insert('rb_penjualan',$data);
				$idp = $this->db->insert_id();
				$this->session->set_userdata(array('idp'=>$idp));
			}else{
				$data = array('kode_transaksi'=>cetak($this->input->post('a')),
			        		  'id_pembeli'=>cetak($this->input->post('b')));
				$where = array('id_penjualan' => $this->session->idp);
				$this->model_app->update('rb_penjualan', $data, $where);
			}
				redirect($this->uri->segment(1).'/tambah_penjualan');

		}elseif(isset($_POST['submit'])){
			$jual = $this->model_reseller->jual_reseller(reseller($this->session->id_konsumen), cetak($this->input->post('aa')))->row_array();
            $beli = $this->model_reseller->beli_reseller(reseller($this->session->id_konsumen), cetak($this->input->post('aa')))->row_array();
            $stok = $beli['beli']-$jual['jual'];
            if ($this->input->post('dd') > $stok){
            	echo "<script>window.alert('Stok Tidak Mencukupi!');
                                  window.location=('".base_url().$this->uri->segment(1)."/tambah_penjualan')</script>";
            }else{
		        if ($this->input->post('idpd')==''){
					$data = array('id_penjualan'=>$this->session->idp,
			        			  'id_produk'=>cetak($this->input->post('aa')),
			        			  'jumlah'=>cetak($this->input->post('dd')),
			        			  'harga_jual'=>cetak($this->input->post('bb')),
			        			  'satuan'=>cetak($this->input->post('ee')));
					$this->model_app->insert('rb_penjualan_detail',$data);
				}else{
			        $data = array('id_produk'=>cetak($this->input->post('aa')),
			        			  'jumlah'=>cetak($this->input->post('dd')),
			        			  'harga_jual'=>cetak($this->input->post('bb')),
			        			  'satuan'=>cetak($this->input->post('ee')));
					$where = array('id_penjualan_detail' => cetak($this->input->post('idpd')));
					$this->model_app->update('rb_penjualan_detail', $data, $where);
				}
				redirect($this->uri->segment(1).'/tambah_penjualan');
			}
			
		}else{
			$data['rows'] = $this->model_reseller->penjualan_konsumen_detail_reseller($this->session->idp)->row_array();
			$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','DESC');
			$data['barang'] = $this->model_app->view_ordering('rb_produk','id_produk','ASC');
			$data['konsumen'] = $this->model_app->view_ordering('rb_konsumen','id_konsumen','ASC');
			if ($this->uri->segment(3)!=''){
				$data['row'] = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>cetak($this->uri->segment(3))))->row_array();
			}
			$this->template->load(template().'/template',template().'/reseller/mod_penjualan/view_penjualan_tambah',$data);
		}
	}

	function edit_penjualan(){
		cek_session_members();
		if (isset($_POST['submit1'])){
			$data = array('kode_transaksi'=>cetak($this->input->post('a')),
			        	  'id_pembeli'=>cetak($this->input->post('b')),
			        	  'waktu_transaksi'=>cetak($this->input->post('c')));
			$where = array('id_penjualan' => cetak($this->input->post('idp')));
			$this->model_app->update('rb_penjualan', $data, $where);
			redirect($this->uri->segment(1).'/edit_penjualan/'.cetak($this->input->post('idp')));

		}elseif(isset($_POST['submit'])){
			$cekk = $this->db->query("SELECT * FROM rb_penjualan_detail where id_penjualan='".cetak($this->input->post('idp'))."' AND id_produk='".cetak($this->input->post('aa'))."'")->row_array();
			$jual = $this->model_reseller->jual_reseller(reseller($this->session->id_konsumen), cetak($this->input->post('aa')))->row_array();
            $beli = $this->model_reseller->beli_reseller(reseller($this->session->id_konsumen), cetak($this->input->post('aa')))->row_array();
            $stok = $beli['beli']-$jual['jual']+$cekk['jumlah'];
            if ($this->input->post('dd') > $stok){
            	echo "<script>window.alert('Stok $stok Tidak Mencukupi!');
                                  window.location=('".base_url().$this->uri->segment(1)."/edit_penjualan/".cetak($this->input->post('idp'))."')</script>";
            }else{
				if ($this->input->post('idpd')==''){
					$data = array('id_penjualan'=>cetak($this->input->post('idp')),
			        			  'id_produk'=>cetak($this->input->post('aa')),
			        			  'jumlah'=>cetak($this->input->post('dd')),
			        			  'harga_jual'=>cetak($this->input->post('bb')),
			        			  'satuan'=>cetak($this->input->post('ee')));
					$this->model_app->insert('rb_penjualan_detail',$data);
				}else{
			        $data = array('id_produk'=>cetak($this->input->post('aa')),
			        			  'jumlah'=>cetak($this->input->post('dd')),
			        			  'harga_jual'=>cetak($this->input->post('bb')),
			        			  'satuan'=>cetak($this->input->post('ee')));
					$where = array('id_penjualan_detail' => cetak($this->input->post('idpd')));
					$this->model_app->update('rb_penjualan_detail', $data, $where);
				}
				redirect($this->uri->segment(1).'/edit_penjualan/'.cetak($this->input->post('idp')));
			}
			
		}else{
			$data['rows'] = $this->model_reseller->penjualan_konsumen_detail_reseller(cetak($this->uri->segment(3)))->row_array();
			$data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>cetak($this->uri->segment(3))),'id_penjualan_detail','DESC');
			$data['barang'] = $this->model_app->view_ordering('rb_produk','id_produk','ASC');
			$data['konsumen'] = $this->model_app->view_ordering('rb_konsumen','id_konsumen','ASC');
			if ($this->uri->segment(4)!=''){
				$data['row'] = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>cetak($this->uri->segment(4))))->row_array();
			}
			$this->template->load(template().'/template',template().'/reseller/mod_penjualan/view_penjualan_edit',$data);
		}
	}

	function proses_penjualan(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
        $data = array('proses'=>cetak($this->uri->segment(4)));
		$where = array('id_penjualan' => cetak($this->uri->segment(3)));
		$this->model_app->update('rb_penjualan', $data, $where);
		notif_perubahan_status(cetak($this->uri->segment(4)),cetak($this->uri->segment(3)));
		
		/*$rows = $this->model_app->view_where('rb_penjualan',array('id_penjualan' => $this->uri->segment(3)))->row_array();
		$dataa = array('pembayaran'=>'1');
		$wheree = array('kode_transaksi' => $rows['kode_transaksi']);
		$this->model_app->update('rb_penjualan_otomatis', $dataa, $wheree);*/
		
		redirect($this->uri->segment(1).'/penjualan');
	}

	function proses_penjualan_detail(){
		cek_session_members();
        $data = array('proses'=>cetak($this->uri->segment(4)));
		$where = array('id_penjualan' => cetak($this->uri->segment(3)));
		$this->model_app->update('rb_penjualan', $data, $where);
		redirect($this->uri->segment(1).'/detail_penjualan/'.$this->uri->segment(3));
	}

	function delete_penjualan(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		$id = array('id_penjualan' => cetak($this->uri->segment(3)));
		$this->model_app->delete('rb_penjualan',$id);
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/penjualan');
	}

	function delete_penjualan_detail(){
        cek_session_members();
		$id = array('id_penjualan_detail' => cetak($this->uri->segment(4)));
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/edit_penjualan/'.$this->uri->segment(3));
	}

	function delete_penjualan_tambah_detail(){
        cek_session_members();
		$id = array('id_penjualan_detail' => cetak($this->uri->segment(3)));
		$this->model_app->delete('rb_penjualan_detail',$id);
		redirect($this->uri->segment(1).'/tambah_penjualan');
	}

	function detail_konsumen(){
		cek_session_members();
		$id = cetak($this->uri->segment(3));
		$cek = $this->db->query("SELECT * FROM `rb_penjualan` where id_penjual='".reseller($this->session->id_konsumen)."' AND id_pembeli='$id' AND status_penjual='reseller'");
		if($cek->num_rows()>=1){
			$data['judul'] = "Data Pelanggan anda";
			$data['title'] = "Data Pelanggan anda";
			$data['rows'] = $this->model_app->edit('rb_konsumen',array('id_konsumen'=>$id))->row_array();
			$this->template->load(template().'/template',template().'/reseller/mod_konsumen/view_konsumen_detail',$data);
		}else{
			echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Anda tidak memiliki akses,..</center></div>');
			redirect('members/penjualan');
		}
	}

	function pembayaran_konsumen(){
		cek_session_members();
		$data['record'] = $this->db->query("SELECT a.*, b.*, c.kode_transaksi, c.proses FROM `rb_konfirmasi_pembayaran_konsumen` a JOIN rb_rekening b ON a.id_rekening=b.id_rekening JOIN rb_penjualan c ON a.id_penjualan=c.id_penjualan where c.id_penjual='".reseller($this->session->id_konsumen)."' AND c.status_penjual='reseller'");
		$this->template->load(template().'/template',template().'/reseller/mod_konsumen/view_konsumen_pembayaran',$data);
	}

	function download(){
		$name = cetak($this->uri->segment(3));
		$data = file_get_contents("asset/files/".$name);
		force_download($name, $data);
	}

	function keuangan(){
		cek_session_members();
		$id = reseller($this->session->id_konsumen);
		$record = $this->model_reseller->reseller_pembelian($id,'admin');
		$penjualan = $this->model_reseller->penjualan_list_konsumen($id,'reseller');
		$edit = $this->model_app->edit('rb_reseller',array('id_reseller'=>$id))->row_array();
		$reward = $this->model_app->view_ordering('rb_reward','id_reward','ASC');

		$data = array('rows' => $edit,'record'=>$record,'penjualan'=>$penjualan,'reward'=>$reward);
		if (isset($_GET['print'])){
			$this->load->view($this->uri->segment(1).'/mod_reseller/view_reseller_keuangan_print',$data);
		}else{
			$this->template->load(template().'/template',template().'/reseller/mod_reseller/view_reseller_keuangan',$data);
		}
	}

	function withdraw(){
		cek_session_members();
		$data['title'] = "Deposit dan Tarik Dana (Withdraw)";
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['record'] = $this->db->query("SELECT a.*, a.keterangan as keterangan_order, b.keterangan FROM rb_withdraw a LEFT JOIN rb_konsumen_detail b ON a.id_rekening_reseller=b.id_konsumen_detail where (a.id_reseller='".reseller($this->session->id_konsumen)."' AND a.akun='reseller') OR (a.id_reseller='".$this->session->id_konsumen."' AND a.akun='konsumen') ORDER BY a.id_withdraw DESC");
		$this->template->load(template().'/template',template().'/reseller/view_withdraw',$data);
	}

	function rekening_pilih(){
		cek_session_members();
		$jenis = $this->input->post('jenis');
		if ($jenis=='debit'){
			$rekening = $this->db->query("SELECT * FROM rb_konsumen_detail where id_konsumen='".$this->session->id_konsumen."' AND status='rekening'");
			if ($rekening->num_rows()=='0'){
				echo "<option value=''>Anda Belum memiliki No Rekening</option>";
			}else{
				foreach ($rekening->result_array() as $row) {
					$ex = explode(';',$row['keterangan']);
					echo "<option value='$row[id_konsumen_detail]'>".bank($ex[0]).", $ex[1], A/N : $ex[2]</option>";
				}
			}
		}else{
			$rekening = $this->db->query("SELECT * FROM rb_rekening");
			foreach ($rekening->result_array() as $row) {
				echo "<option value='$row[id_rekening]'>$row[nama_bank], $row[no_rekening], A/N : $row[pemilik_rekening]</option>";
			}
			if (config('ipaymu_aktif')=='Y' OR config('tripay_aktif')=='Y'){
				echo "<option value='0'>Payment Gateway</option>";
			}
		}
	}

	function tambah_withdraw(){
		cek_session_members();
		verifikasi(reseller($this->session->id_konsumen));
		if (isset($_POST['submit'])){
			$id_reseller = $this->session->id_konsumen;
			$akun = 'konsumen';
			if (cetak($this->input->post('jenis'))=='debit'){
				$nominal = clean_rupiah(cetak($this->input->post('b')));
				if ($nominal>=config('withdraw_min')){
					if (saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen)>=$nominal){
						if ($nominal>0){
							$data = array('id_rekening_reseller'=>cetak($this->input->post('a')),
								'id_reseller'=>$id_reseller,
								'nominal'=>$nominal,
								'withdraw_fee'=>config('withdraw_fee'),
								'status'=>'Pending',
								'akun'=>$akun,
								'transaksi'=>cetak($this->input->post('jenis')),
								'waktu_withdraw'=>date('Y-m-d H:i:s'));
							$this->model_app->insert('rb_withdraw',$data);
							$id = $this->db->insert_id();
							
							if (config('xendit_disbursement')=='Y'){
    							$rek = $this->db->query("SELECT * FROM rb_konsumen_detail where id_konsumen_detail='".cetak($this->input->post('a'))."'")->row_array();
    							$exp = explode(';',$rek['keterangan']);
    							$bk = $this->db->query("SELECT * FROM rb_bank where id_bank='".$exp[0]."'")->row_array();
    							
    							Xendit::setApiKey(config('xendit_api'));
                        		$params = [
                                    'external_id' => "$id",
                                    'amount' => $nominal+config('withdraw_fee'),
                                    'bank_code' => $bk['code'],
                                    'account_holder_name' => $exp[2],
                                    'account_number' => $exp[1],
                                    'description' => "Disbursement $id_reseller/$id/".date('YmdHis'),
                                    'X-IDEMPOTENCY-KEY' => "$id_reseller/$id/".date('YmdHis')
                                  ];
                                
                                  $createDisbursements = \Xendit\Disbursements::create($params);
                                  // var_dump($createDisbursements);
							}
							notif_withdraw($nominal,$this->session->id_konsumen);
						}else{
							echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center><b>NOMINAL TIDAK VALID</b> - GAGAL Proses Penarikan dana..</center></div>");	
						}
					}else{
						echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center><b>SALDO TIDAK MENCUKUPI</b> - GAGAL Proses Penarikan dana..</center></div>");	
					}
				}else{
					echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center><b>GAGAL</b> - Minimal Proses Penarikan dana Rp ".rupiah(config('withdraw_min'))."..</center></div>");
				}
			}else{
				$nominal = clean_rupiah(cetak($this->input->post('b')))+rand(1,50);
				if (clean_rupiah(cetak($this->input->post('b')))>0){
					$data = array('id_rekening_reseller'=>cetak($this->input->post('a')),
							'id_reseller'=>$id_reseller,
							'nominal'=>$nominal,
							'withdraw_fee'=>0,
							'status'=>'Pending',
							'akun'=>$akun,
							'transaksi'=>cetak($this->input->post('jenis')),
							'waktu_withdraw'=>date('Y-m-d H:i:s'));
							$this->model_app->insert('rb_withdraw',$data);
					notif_deposit($nominal,$this->session->id_konsumen,cetak($this->input->post('a')));
				}else{
					echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center><b>NOMINAL TIDAK VALID</b> - GAGAL Proses Penarikan dana..</center></div>");	
				}
			}
			redirect($this->uri->segment(1).'/withdraw');
		}else{
			$data['title'] = "Buat Permintaan Tarik Dana (Withdraw)";
			$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_withdraw_tambah',$data);
		}
	}

	function upgrade(){
		cek_session_members();
		if (!isset($_GET['p'])){
			$cek_toko = $this->db->query("SELECT * FROM rb_reseller where id_konsumen='".$this->session->id_konsumen."'");
			if ($cek_toko->num_rows()<=0){
				echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center>Silahkan membuat toko terlebih dahulu.</center></div>");
				redirect('members/buat_toko');
			}
		}

		if (isset($_GET['save'])){
			if ($this->session->sesi_paket!=''){
				$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_paket."'")->row_array();
				$fileName = $rows['files'];
			}else{
				$fileName = NULL;
			}

			if (isset($_GET['p'])){ $users = 'konsumen'; $idr = $this->session->id_konsumen; }else{ $users = 'toko'; $idr = reseller($this->session->id_konsumen); }
			$data = array('bukti_transfer'=>$fileName);
			$where = array('id_reseller_paket' => $_GET['save'],'users' =>$users,'id_reseller'=>$idr);
			$this->model_app->update('rb_reseller_paket', $data, $where);
			$this->session->unset_userdata('sesi_paket');
			if (isset($_GET['p'])){
				redirect('members/upgrade?p');
			}else{
				redirect('members/upgrade');
			}
		}
		
		if (isset($_GET['paket'])){
			if (isset($_GET['p'])){
				$rowp = $this->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='".($this->session->id_konsumen)."' AND a.paket='on' AND users='konsumen'")->row_array();
			}else{
				$rowp = $this->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.id_reseller='".reseller($this->session->id_konsumen)."' AND a.paket='on' AND users='toko'")->row_array();
			}
			if ($rowp['status']=='Y'){
				$akhir  = strtotime($rowp['expire_date']); //Waktu awal
				$awal = time(); // Waktu sekarang atau akhir
				$diff  = $akhir - $awal;
				echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center>GAGAL Memilih Paket, Saat ini paket anda dalam status sudah aktif sampai ".tgl_indo($rowp['expire_date'])." (".floor($diff / (60 * 60 * 24)) ." hari lagi).</center></div>");	
				redirect($this->uri->segment(1).'/upgrade');
			}else{
				$paket = $this->db->query("SELECT * FROM rb_paket where id_paket='".cetak($this->input->get('paket'))."'")->row_array();
				if (isset($_GET['p'])){
					$cekp = $this->db->query("SELECT * FROM rb_reseller_paket where id_reseller='".($this->session->id_konsumen)."' AND status='N' AND paket!='on' AND users='konsumen'");
					$id_reseller = $this->session->id_konsumen;
					$users = 'konsumen';
					$url = '?p';
				}else{
					$cekp = $this->db->query("SELECT * FROM rb_reseller_paket where id_reseller='".reseller($this->session->id_konsumen)."' AND status='N' AND paket!='on' AND users='toko'");
					$id_reseller = reseller($this->session->id_konsumen);
					$users = 'toko';
					$url = '';
				}
				$tagihan = ($paket['harga']+rand(10,99));
				$ceknominal = $this->db->query("SELECT * FROM rb_reseller_paket where tagihan='$tagihan' AND status='N'");
				if ($ceknominal->num_rows()>1){
					$total_tagihan = $tagihan+1;
				}else{
					$total_tagihan = $tagihan;
				}

				$data = array('id_paket'=>cetak($this->input->get('paket')),
							'id_reseller'=>$id_reseller,
							'tagihan'=>$total_tagihan,
							'expire_date'=>date('Y-m-d'),
							'status'=>'N',
							'users'=>$users,
							'waktu_paket'=>date('Y-m-d H:i:s'));
				if ($cekp->num_rows()<='0'){
					$this->model_app->insert('rb_reseller_paket',$data);
				}else{
					$where = array('id_reseller' => $id_reseller,'status' => 'N','paket!='=>'on','users'=>$users);
					$this->model_app->update('rb_reseller_paket', $data, $where);
				}
			}
			redirect($this->uri->segment(1).'/upgrade'.$url);
		}else{
			if (!isset($_GET['p'])){
				$data['title'] = "Pilih Paket (Star Seller)";
				$data['record'] = $this->db->query("SELECT * FROM rb_paket where jenis='toko' ORDER BY id_paket");
			}else{
				$data['title'] = "Pilih Paket Premium akun";
				$data['record'] = $this->db->query("SELECT * FROM rb_paket where jenis='konsumen' ORDER BY id_paket");
			}
			$this->template->load(template().'/template',template().'/reseller/mod_reseller/paket',$data);
		}
	}
	
	function bayar_subscribe_flip(){
	    
	    if(!isset($_GET['id']) || !isset($_GET['level']) || !isset($_GET['amount']) || !isset($_GET['id_paket']) ){
	        echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center>Pembayaran Upgrade Gagal, Silahkan Coba Lagi </center></div>");
			redirect('members/upgrade?p');
	    }
	        $key = md5(rand());

            $getKonsumen = $this->db->query("SELECT nama_lengkap, email FROM rb_konsumen where id_konsumen = '".$this->session->id_konsumen."'")->row_array();

			$getApi = $this->db->query("SELECT value FROM rb_config where field = 'flip_api'")->row_array();
            $secret_key = base64_encode($getApi['value']."::");
            
            $payloads = [
                "title" => 'Upgrade Member '.$_GET['level'],
                "amount" => $_GET['amount'],
                "type" => "SINGLE",
                "redirect_url" => base_url()."members/subscribe_success?key=$key",
                "is_address_required" => 0,
                "is_phone_number_required" => 0,
                "sender_name" => $getKonsumen['nama_lengkap'],
                "sender_email" => $getKonsumen['email'],
                "step" => 2
            ];
            
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://bigflip.id/big_sandbox_api/v2/pwf/bill',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => http_build_query($payloads),
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic '.$secret_key,
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            $invoice = json_decode($response, true);
            
            if(array_key_exists('errors', $invoice)){
                echo $this->session->set_flashdata('message', "<div class='alert alert-danger'><center>Pembayaran Upgrade Gagal </center></div>");
			    redirect('members/upgrade?p');
            } else {
                // header("Location: https://".$pay['link_url']);
                $this->db->query("UPDATE rb_reseller_paket SET kode_transaksi='$key' where id_reseller_paket='".$_GET['id']."'");
                redirect("https://".$invoice['link_url']);
                
            }
	}
    
    function subscribe_success(){
        $cek_paket = $this->db->query("SELECT * FROM rb_reseller_paket a JOIN rb_paket b ON a.id_paket=b.id_paket where a.kode_transaksi='".$_GET['key']."'")->row_array();
        $cek = $this->db->query("SELECT * FROM rb_paket where id_paket='".$cek_paket['id_paket']."'")->row_array();
                
        $date=date_create(date('Y-m-d'));
        date_add($date,date_interval_create_from_date_string("$cek[durasi] days"));
        $expire = date_format($date,"Y-m-d");
            
        $result = $this->db->query("UPDATE rb_reseller_paket SET status='Y', expire_date = '$expire', paket = 'on' where kode_transaksi='".$_GET['key']."'");
        echo $this->session->set_flashdata('message', "<div class='alert alert-info'><center>Pembayaran Upgrade Sukses </center></div>");
		redirect('members/upgrade?p');
    }
    
	function edit_profil_toko(){
		cek_session_members();
		$id = reseller($this->session->id_konsumen);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|webp|WEBP|jpg|png|jpeg';
            $config['max_size'] = '5000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('gg');
			$hasil=$this->upload->data();

            if ($hasil['file_name']==''){
		           $data = array('nama_reseller'=>cetak($this->input->post('c')),
							'alamat_lengkap'=>cetak($this->input->post('e')),
							'keterangan'=>strip_tags($this->input->post('i')),
							'pilihan_kurir'=>cetak($this->input->post('pilihan_kurir')),
							'no_telpon'=>cetak($this->input->post('f')),
							'kecamatan_id'=>cetak($this->input->post('kecamatan_id')),
							'kota_id'=>cetak($this->input->post('kota_id')),
							'provinsi_id'=>cetak($this->input->post('provinsi_id')),
							'tokopedia'=>cetak($this->input->post('tokopedia')),
							'shoopee'=>cetak($this->input->post('shoopee')),
							'kordinat'=>cetak($this->input->post('lokasi')));
		    }else{
				$data = array('nama_reseller'=>cetak($this->input->post('c')),
							'alamat_lengkap'=>cetak($this->input->post('e')),
							'keterangan'=>strip_tags($this->input->post('i')),
							'pilihan_kurir'=>cetak($this->input->post('pilihan_kurir')),
							'no_telpon'=>cetak($this->input->post('f')),
							'foto'=>$hasil['file_name'],
							'kecamatan_id'=>cetak($this->input->post('kecamatan_id')),
							'kota_id'=>cetak($this->input->post('kota_id')),
							'provinsi_id'=>cetak($this->input->post('provinsi_id')),
							'tokopedia'=>cetak($this->input->post('tokopedia')),
							'shoopee'=>cetak($this->input->post('shoopee')),
							'kordinat'=>cetak($this->input->post('lokasi')));
		    }
			$where = array('id_reseller' => reseller($this->session->id_konsumen));
			$this->model_app->update('rb_reseller', $data, $where);
			redirect($this->uri->segment(1).'/profil_toko');
		}else{
			$title = "Edit Identitas Toko";
			$edit = $this->model_app->edit('rb_reseller',array('id_reseller'=>$id))->row_array();
			$data = array('rows' => $edit, 'title'=>$title);
			$this->template->load(template().'/template',template().'/reseller/mod_reseller/view_reseller_edit',$data);
		}
	}

	function buat_toko(){
		cek_session_members();
		$cek_toko = $this->db->query("SELECT * FROM rb_reseller where id_konsumen='".$this->session->id_konsumen."'");
		if ($cek_toko->num_rows()>=1){
		    echo $this->session->set_flashdata('message', "<div class='alert alert-info'><center>Anda telah memiliki toko,.. </center></div>");
			redirect('members/profil_toko');
		}

		$id = $this->session->id_konsumen;
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|webp|WEBP|jpg|png|jpeg';
            $config['max_size'] = '5000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('gg');
			$hasil=$this->upload->data();

			$ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
			$fv = explode('|',$ref['keterangan']);
			if ($fv[1]=='N'){ $verifikasi = 'Y'; }else{ $verifikasi = 'N'; }
			$ref = $this->db->query("SELECT referral_id FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
			
			$cek_user_exist = $this->db->query("SELECT user_reseller FROM rb_reseller where user_reseller='".seo_title(cetak($this->input->post('c')))."'");
			if ($cek_user_exist->num_rows()>=1){ $user_reseller = seo_title(cetak($this->input->post('c'))).rand(100,999); }else{ $user_reseller = seo_title(cetak($this->input->post('c'))); }
			if ($hasil['file_name']==''){
				   $data = array('id_konsumen'=>cetak($id),
				   			'user_reseller'=>cetak($user_reseller),
				   			'nama_reseller'=>cetak($this->input->post('c')),
							'alamat_lengkap'=>cetak($this->input->post('e')),
							'keterangan'=>strip_tags($this->input->post('i')),
							'pilihan_kurir'=>cetak($this->input->post('pilihan_kurir')),
							'no_telpon'=>cetak($this->input->post('f')),
							'kecamatan_id'=>cetak($this->input->post('kecamatan_id')),
							'kota_id'=>cetak($this->input->post('kota_id')),
							'provinsi_id'=>cetak($this->input->post('provinsi_id')),
							'tokopedia'=>cetak($this->input->post('tokopedia')),
							'shoopee'=>cetak($this->input->post('shoopee')),
							'referral'=>$ref['referral_id'],
							'verifikasi'=>cetak($verifikasi),
							'tanggal_daftar'=>date('Y-m-d H:i:s'),
							'kordinat'=>cetak($this->input->post('lokasi')));
		    }else{
				$data = array('id_konsumen'=>cetak($id),
							'user_reseller'=>cetak($user_reseller),
							'nama_reseller'=>cetak($this->input->post('c')),
							'alamat_lengkap'=>cetak($this->input->post('e')),
							'keterangan'=>strip_tags($this->input->post('i')),
							'pilihan_kurir'=>cetak($this->input->post('pilihan_kurir')),
							'no_telpon'=>cetak($this->input->post('f')),
							'foto'=>$hasil['file_name'],
							'kecamatan_id'=>cetak($this->input->post('kecamatan_id')),
							'kota_id'=>cetak($this->input->post('kota_id')),
							'provinsi_id'=>cetak($this->input->post('provinsi_id')),
							'tokopedia'=>cetak($this->input->post('tokopedia')),
							'shoopee'=>cetak($this->input->post('shoopee')),
							'referral'=>$ref['referral_id'],
							'verifikasi'=>cetak($verifikasi),
							'tanggal_daftar'=>date('Y-m-d H:i:s'),
							'kordinat'=>cetak($this->input->post('lokasi')));
		    }
			$this->model_app->insert('rb_reseller',$data);
			redirect($this->uri->segment(1).'/profil_toko');
		}else{
			$title = "Buat Toko";
			$data = array('title'=>$title);
			$this->template->load(template().'/template',template().'/reseller/mod_reseller/view_reseller_tambah',$data);
		}
	}

	function profil_toko(){
		cek_session_members();
		$id = reseller($this->session->id_konsumen);
		$title = "Identitas Toko";
		$edit = $this->model_app->edit('rb_reseller',array('id_reseller'=>$id))->row_array();
		$data = array('rows' => $edit, 'title'=>$title);
		$this->template->load(template().'/template',template().'/reseller/mod_reseller/view_reseller_detail',$data);
	}

	function operasional(){
		cek_session_members();
		if (isset($_POST['submit'])){
			for ($i=1; $i<=7 ; $i++) { 
			    $cek = $this->db->query("SELECT * FROM rb_reseller_operasional where hari='$i' AND id_reseller='".reseller($this->session->id_konsumen)."'");
			    if ($cek->num_rows()>=1){
				    $this->model_app->update('rb_reseller_operasional', array('jam_buka'=>$this->input->post('b'.$i),'jam_tutup'=>$this->input->post('t'.$i)),array('id_reseller'=>reseller($this->session->id_konsumen),'hari'=>$i));
			    }else{
			        $data = array('id_reseller'=>reseller($this->session->id_konsumen),
							'hari'=>$i,
							'jam_buka'=>cetak($this->input->post('b'.$i)),
							'jam_tutup'=>cetak($this->input->post('t'.$i)));
			        $this->model_app->insert('rb_reseller_operasional',$data);
			    }
			}
			redirect('members/operasional');
		}else{
			$id = reseller($this->session->id_konsumen);
			$title = "Jadwal Operasional Toko";
			$edit = $this->model_app->edit('rb_reseller',array('id_reseller'=>$id))->row_array();
			$data = array('rows' => $edit, 'title'=>$title);
			$this->template->load(template().'/template',template().'/reseller/mod_reseller/view_reseller_operasional',$data);
		}
	}

	function detail_keuangan(){
		cek_session_members();
		if (isset($_POST['upload'])){
            $config['upload_path'] = 'asset/bukti_transfer/';
			$config['allowed_types'] = 'gif|webp|WEBP|jpg|png';
			$config['encrypt_name'] = TRUE;
            $config['max_size'] = '5000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('bukti');
            $hasil=$this->upload->data();
            $this->model_app->update('rb_withdraw', array('keterangan'=>$hasil['file_name']),array('id_withdraw'=>$this->input->post('id'),'id_reseller'=>$this->session->id_konsumen));
			redirect('members/withdraw');
		}else{
			$data['row'] = $this->db->query("SELECT a.*, b.keterangan as ket FROM rb_withdraw a LEFT JOIN rb_konsumen_detail b ON a.id_rekening_reseller=b.id_konsumen_detail where id_withdraw ='".cetak($this->input->post('id'))."'")->row_array();
			$this->load->view(template().'/reseller/view_withdraw_detail',$data);
		}
	}

	function location(){
		## cara penggunaannya
		## contoh ada 2 koordinat (latitude dan longitude)
		$a = $_POST['lokasi_penjual'];
		$b = $_POST['lokasi'];
		$jarak = distHaversine($a, $b);
		if ($jarak>config('max_jarak_km')){
			$tarif = 0;
		}else{
			$tarif = $jarak * config('ongkir_per_km');
		}
		$this->session->set_userdata(array('sopir1'=>"$tarif||$jarak"));
		echo "$tarif||$jarak";
	}

	function pilih_alamat(){
		cek_session_members();
		if ($this->input->post('id')!='0' OR $this->input->post('id')!=''){
			$cek = $this->db->query("SELECT * FROM rb_konsumen_alamat where id_konsumen_alamat='".cetak($this->input->post('id'))."' AND id_konsumen='".$this->session->id_konsumen."'");
			if ($cek->num_rows()>=1){
				$this->session->set_userdata(array('sesi_alamat'=>cetak($this->input->post('id'))));
			}else{
				$this->session->set_userdata(array('sesi_alamat'=>0));
			}
		}else{
			$this->session->set_userdata(array('sesi_alamat'=>0));
		}
	}

	function alamat_terpilih(){
		cek_session_members();
		if ($this->session->sesi_alamat=='' OR $this->session->sesi_alamat=='0'){
			$alamat = $this->db->query("SELECT '(Alamat Utama)' as 'alamat_utama', 0 as 'idl', z.nama_lengkap, z.no_hp, z.alamat_lengkap, z.kordinat_lokasi, a.subdistrict_id as kecamatan_id, a.subdistrict_name as kecamatan, b.city_name as kota, c.province_name as provinsi FROM `rb_konsumen` z JOIN `tb_ro_subdistricts` a ON z.kecamatan_id=a.subdistrict_id JOIN tb_ro_cities b ON z.kota_id=b.city_id JOIN tb_ro_provinces c ON z.provinsi_id=c.province_id where z.id_konsumen='".$this->session->id_konsumen."'")->result();
			echo json_encode($alamat);
		}else{
			$alamat = $this->db->query("SELECT '(Alamat Lainnya)' as 'alamat_utama', z.id_konsumen_alamat as idl, z.nama_lengkap, z.no_hp, z.alamat_lengkap, z.kordinat_lokasi, a.subdistrict_id as kecamatan_id, a.subdistrict_name as kecamatan, b.city_name as kota, c.province_name as provinsi FROM `rb_konsumen_alamat` z JOIN `tb_ro_subdistricts` a ON z.kecamatan_id=a.subdistrict_id JOIN tb_ro_cities b ON z.kota_id=b.city_id JOIN tb_ro_provinces c ON z.provinsi_id=c.province_id where z.id_konsumen_alamat='".$this->session->sesi_alamat."'")->result();
			echo json_encode($alamat);
		}
	}

	function tambah_alamat(){
		cek_session_members();
		if (cetak($this->input->post('kecamatan_id'))!=''){
			$data = array('id_konsumen'=>$this->session->id_konsumen,
						'nama_lengkap'=>cetak($this->input->post('nama_lengkap')),
						'no_hp'=>cetak($this->input->post('no_hp')),
						'alamat_lengkap'=>cetak($this->input->post('alamat_lengkap')),
						'provinsi_id'=>cetak($this->input->post('provinsi_id')),
						'kota_id'=>cetak($this->input->post('kota_id')),
						'kecamatan_id'=>cetak($this->input->post('kecamatan_id')),
						'kordinat_lokasi'=>cetak($this->input->post('kordinat_lokasi')));
			$result = $this->model_app->insert('rb_konsumen_alamat',$data);
			$sesi_alamat = $this->db->insert_id();
			$this->session->set_userdata(array('sesi_alamat'=>$sesi_alamat));
			echo json_encode($result);
		}else{
			echo json_encode('0');
		}
	}

	function notifikasi(){
		cek_session_members();
		$jumlah = $this->model_app->view_where('rb_notifikasi_send',array('id_konsumen'=>$this->session->id_konsumen))->num_rows();
		$config['base_url'] = base_url().'members/notifikasi';
		$config['total_rows'] = $jumlah;
		$config['per_page'] = 10; 	
		$config['uri_segment'] = 3 ;
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		if ($this->uri->segment('3')==''){
			$dari = 0;
		}else{
			$dari = $this->uri->segment('3');
		}

		$data['title'] = 'Notif Center';
		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
		$data['notifikasi'] = $this->db->query("SELECT * FROM rb_notifikasi_send a JOIN rb_notifikasi b ON a.id_notifikasi=b.id_notifikasi where a.id_konsumen='".$this->session->id_konsumen."' ORDER BY a.id_notifikasi_send DESC LIMIT $dari,$config[per_page]");
		$this->pagination->initialize($config);
		$this->template->load(template().'/template',template().'/reseller/view_notifikasi',$data);
	}

	function notifikasi_dibaca(){
		cek_session_members();
        $result = $this->db->query("UPDATE rb_notifikasi_send SET dibaca='".cetak($this->input->post('data1'))."' where id_notifikasi_send='".cetak($this->input->post('id'))."' AND id_konsumen='".$this->session->id_konsumen."'");
        echo json_encode($result);
    }

	function notifikasi_dibaca_all(){
		cek_session_members();
        $result = $this->db->query("UPDATE rb_notifikasi_send SET dibaca='Y' where id_konsumen='".$this->session->id_konsumen."'");
        echo json_encode($result);
	}
	
	
	function video(){
		cek_session_members();
		if (isset($_GET['t'])){
		    if (isset($_POST['submit'])){
		        if ($this->session->sesi_video!=''){
					$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_video."'")->row_array();
					$fileName = $rows['files'];
				}else{
				    $fileName = '';
				}
		        $data = array('id_produk'=>cetak($this->input->post('id_produk')),
		                    'judul_video'=>cetak($this->input->post('judul_video')),
							'keterangan_video'=>cetak($this->input->post('keterangan_video')),
							'video'=>$fileName,
							'waktu_video'=>date('Y-m-d H:i:s'));
    			$this->model_app->insert('rb_produk_video', $data);
    			
    			$this->session->unset_userdata('sesi_video');
		        redirect($this->uri->segment(1).'/video?ok');
		    }else{
		        $data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
    			$title = "Tambah Video";
    			$data = array('row' => $row, 'title'=>$title);
    			$this->template->load(template().'/template',template().'/reseller/mod_produk/view_produk_video_tambah',$data);
		    }
		}elseif (isset($_GET['e'])){
		    if (isset($_POST['submit'])){
		        if ($this->session->sesi_video!=''){
					$rows = $this->db->query("SELECT GROUP_CONCAT(file_name SEPARATOR ';') as files FROM `img_comment` where id_comment='".$this->session->sesi_video."'")->row_array();
					$fileName = $rows['files'];
				}else{
				    $rows = $this->db->query("SELECT video FROM `rb_produk_video` where id_produk_video='".cetak($this->input->get('e'))."'")->row_array();
					$fileName = $rows['video'];
				}
		        $data = array('judul_video'=>cetak($this->input->post('judul_video')),
							'keterangan_video'=>strip_tags($this->input->post('keterangan_video')),
							'video'=>$fileName);
    			$where = array('id_produk_video' =>cetak($this->input->get('e')));
    			$this->model_app->update('rb_produk_video', $data, $where);
    			
    			$this->session->unset_userdata('sesi_video');
		        redirect($this->uri->segment(1).'/video');
		    }else{
		        $row = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
    			$title = "Edit Video";
    			$edit = $this->db->query("SELECT * FROM `rb_produk_video` where id_produk_video='".cetak($this->input->get('e'))."'")->row_array();
    			$data = array('row' => $row, 'rows' => $edit, 'title'=>$title);
    			$this->template->load(template().'/template',template().'/reseller/mod_produk/view_produk_video_edit',$data);
		    }
		}elseif (isset($_GET['d'])){
    		$id = array('id_produk_video' => cetak($this->input->get('d')));
    		$this->model_app->delete('rb_produk_video',$id);
    		redirect($this->uri->segment(1).'/video');
		}else{
    		$jumlah= $this->db->query("SELECT * FROM rb_produk_video a JOIN rb_produk b ON a.id_produk=b.id_produk where b.id_reseller='".reseller($this->session->id_konsumen)."'")->num_rows();
    		$config['base_url'] = base_url().'members/video';
    		$config['total_rows'] = $jumlah;
    		$config['per_page'] = 16; 	
    
    		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
    			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
    			
    		if ($this->uri->segment('3')==''){
    			$dari = 0;
    		}else{
    			$dari = $this->uri->segment('3');
    		}
    
    		$data['title'] = 'Video saya';
    		$data['row'] = $this->model_reseller->profile_konsumen($this->session->id_konsumen)->row_array();
    		$data['record'] = $this->db->query("SELECT * FROM rb_produk_video a JOIN rb_produk b ON a.id_produk=b.id_produk where b.id_reseller='".reseller($this->session->id_konsumen)."' ORDER BY a.id_produk_video DESC LIMIT $dari,$config[per_page]");
    		$this->pagination->initialize($config);
    		$this->template->load(template().'/template',template().'/reseller/mod_produk/view_produk_video',$data);
		}
	}
}
