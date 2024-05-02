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
class Auth extends CI_Controller {
	function city(){
		$state_id = $this->input->post('stat_id');
		$data['kota'] = $this->model_app->view_where_ordering('rb_kota',array('provinsi_id' => $state_id),'kota_id','DESC');
		$this->load->view(template().'/reseller/view_city',$data);		
	}

	public function register(){
		if (isset($_POST['submit2'])){
			if (strlen(cetak($this->input->post('b')))<'10'){
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>GAGAL - No telpon anda tidak valid..</center></div>');
				redirect("auth/login");
			}

			$ex = explode('@',cetak($this->input->post('a')));
            $cek_username = $this->db->query("SELECT * FROM rb_konsumen where username='".cetak($ex[0])."' OR email='".cetak($this->input->post('a'))."' OR no_hp='".cetak($this->input->post('b'))."'");
			if ($cek_username->num_rows()<='0'){
				$ex = explode('@',cetak($this->input->post('a')));
				$data = array('username'=>cetak($ex[0]),
							'password'=>hash("sha512", md5($this->input->post('b'))),
							'nama_lengkap'=>$ex[0],
							'email'=>cetak($this->input->post('a')),
							'jenis_kelamin'=>'Laki-laki',
							'no_hp'=>cetak($this->input->post('b')),
							'token'=>config('token_referral'),
							'referral_id'=>$this->session->referral,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'subdomain'=>time().rand(11,99),
							'tanggal_daftar'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konsumen',$data);
				$id = $this->db->insert_id();
				$this->session->set_userdata(array('id_konsumen'=>$id, 'level'=>'konsumen'));

				$tgl_kirim = date("d-m-Y H:i:s");
				$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
				$subject      = "$iden[pengirim_email] - Pendaftaran Sukses,..";
				$logo = $this->db->query("SELECT * FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
				$kons = $this->model_reseller->profile_konsumen($id)->row_array();

				$message  = "<html><body><span style='font-size:18px; color:green'>Selamat Bergabung di $iden[url]</span><br>
										Hai $kons[nama_lengkap]! Terima kasih telah mendaftar di <a style='text-decoration:none; color:#000' href='$iden[url]'>$iden[url]</a>. <br>
										Silakan untuk melengkapi data diri anda sesuai dengan identitas pada KTP di <a href='".base_url()."members/edit_profile'>Disini</a>.<br><br>

										Akun Anda sudah kami aktifkan. Anda mendaftar menggunakan email: $kons[email].<br> 
										Masukkan email dan password yang Anda daftarkan tersebut setiap kali log in ke $iden[url].<br><br>

										Siap mencari produk dari ribuan<br>
										penjual online di Indonesia? <a href='".base_url()."produk'>Klik Disini</a></body></html> \n";
				
				echo kirim_email($subject,$message,$kons['email']); 
				echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Terima kasih, anda sudah terdaftar di <b>'.$iden['url'].'</b> silahkan cek email untuk verifikasi!</center></div>');
				redirect('members/edit_profile');
			}else{
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Username/Email/No Telpon Telah Digunakan!</center></div>');
				redirect('auth/login');
			}
		}
	}
	
	public function facebook_login(){
		$data = array();
		// Authenticate user with facebook
		$this->load->library('facebook');
		if($this->facebook->is_authenticated()){
			// Get user info from facebook
			$fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,picture');

            // Preparing data for database insertion
            $data['oauth_provider'] = 'facebook';
            $data['oauth_uid']	= !empty($fbUser['id'])?$fbUser['id']:'';
            $data['first_name']	= !empty($fbUser['first_name'])?$fbUser['first_name']:'';
            $data['last_name']	= !empty($fbUser['last_name'])?$fbUser['last_name']:'';
            $data['email']		= !empty($fbUser['email'])?$fbUser['email']:'';
            $data['gender']		= !empty($fbUser['gender'])?$fbUser['gender']:'';
			$data['picture']	= !empty($fbUser['picture']['data']['url'])?$fbUser['picture']['data']['url']:'';
            $data['link']		= !empty($fbUser['link'])?$fbUser['link']:'https://www.facebook.com/';

			$rows = $this->db->query("SELECT * FROM rb_konsumen where email='$fbUser[email]' ORDER BY id_konsumen DESC LIMIT 1")->row_array();
			if ($rows['id_konsumen']!=''){
				$token = date('Hi').rand(10,99);
				$this->session->set_userdata(array('id_konsumen'=>$rows['id_konsumen'], 'level'=>'konsumen','pin_used'=>$token));
				
				if ($this->session->idp!=''){
					$keranjang = $this->db->query("SELECT a.*, c.id_konsumen FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where a.session='".$this->session->idp."'");
					foreach ($keranjang->result_array() as $row) {
						$cek_exist = $this->db->query("SELECT * FROM rb_penjualan_temp where id_produk='$row[id_produk]' AND session='$rows[id_konsumen]'");
						if ($cek_exist->num_rows()<=0){
							if ($row['id_konsumen']!=$rows['id_konsumen']){
								$this->db->query("UPDATE rb_penjualan_temp SET session='$rows[id_konsumen]' where id_penjualan_detail='$row[id_penjualan_detail]'");
							}
						}else{
							$this->db->query("UPDATE rb_penjualan_temp SET jumlah='$row[jumlah]' where id_penjualan_detail='$row[id_penjualan_detail]'");
						}
					}
				}

				$this->session->set_userdata(array('idp'=>$rows['id_konsumen']));

				redirect('main');
			}else{
    			if ($this->session->id_konsumen!=''){
    				redirect('main');
    			}else{
    			    if ($fbUser['first_name']!=''){
    					$ex = explode('@',cetak($fbUser['email']));
    					$dataa = array('username'=>cetak($ex[0]),
    								'password'=>hash("sha512", md5($ex[0])),
    								'nama_lengkap'=>($fbUser['first_name'].' '.$fbUser['last_name']),
    								'email'=>cetak($fbUser['email']),
    								'jenis_kelamin'=>'Laki-laki',
    								'token'=>config('token_referral'),
    								'referral_id'=>$this->session->referral,
    								'tanggal_daftar'=>date('Y-m-d H:i:s'));
    					$this->model_app->insert('rb_konsumen',$dataa);
    					$id = $this->db->insert_id();
    					$this->session->set_userdata(array('id_konsumen'=>$id, 'level'=>'konsumen'));
    
    					$tgl_kirim = date("d-m-Y H:i:s");
    					$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
    					$subject      = "$iden[pengirim_email] - Pendaftaran Sukses,..";
    					$logo = $this->db->query("SELECT * FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
    					$kons = $this->model_reseller->profile_konsumen($id)->row_array();
    
    					$message  = "<html><body><span style='font-size:18px; color:green'>Selamat Bergabung di $iden[url]</span><br>
    											Hai $kons[nama_lengkap]! Terima kasih telah mendaftar di <a style='text-decoration:none; color:#000' href='$iden[url]'>$iden[url]</a>. <br>
    											Silakan untuk melengkapi data diri anda sesuai dengan identitas pada KTP di <a href='".base_url()."members/edit_profile'>Disini</a>.<br><br>
    
    											Akun Anda sudah kami aktifkan. Anda mendaftar menggunakan email: $kons[email].<br> 
    											Masukkan email dan password yang Anda daftarkan tersebut setiap kali log in ke $iden[url].<br><br>
    
    											Siap mencari produk dari ribuan<br>
    											penjual online di Indonesia? <a href='".base_url()."produk'>Klik Disini</a></body></html> \n";
    					
    					echo kirim_email($subject,$message,$kons['email']); 
    					echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Terima kasih, anda sudah terdaftar di <b>'.$iden['url'].'</b> silahkan cek email untuk verifikasi!</center></div>');
    					redirect('members/edit_profile');
        			}else{
    				        echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal mendaftar dengan Facebook...</center></div>');
    				        redirect('auth/login');
    				    }
        			}
			}
		}else{
			if ($this->session->id_konsumen!=''){
				redirect('main');
			}else{
				redirect('auth/login');
			}
        }
	}
	
	public function facebook_logout() {
		$this->facebook->destroy_session();
		$this->session->sess_destroy();
        redirect('auth/login');
    }

	function google_login(){
		  $google_client = new Google_Client();
		  $google_client->setClientId(config('google_client_id')); //masukkan ClientID anda 
		  $google_client->setClientSecret(config('google_client_secret')); //masukkan Client Secret Key anda
		  $google_client->setRedirectUri(config('redirect_uri').'google_login'); //Masukkan Redirect Uri anda
		  $google_client->addScope('email');
		  $google_client->addScope('profile');

		  if(isset($_GET["code"])){
		   $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
		   if(!isset($token["error"])){
		    $google_client->setAccessToken($token['access_token']);
		    $this->session->set_userdata('access_token', $token['access_token']);
		    $google_service = new Google_Service_Oauth2($google_client);
		    $data = $google_service->userinfo->get();
		    $current_datetime = date('Y-m-d H:i:s');
		    $user_data = array(
		      'first_name' => $data['given_name'],
		      'last_name'  => $data['family_name'],
		      'email_address' => $data['email'],
		      'profile_picture'=> $data['picture'],
		      'updated_at' => $current_datetime
		     );
		    
			$rows = $this->db->query("SELECT * FROM rb_konsumen where email='".$data->email."' ORDER BY id_konsumen DESC LIMIT 1")->row_array();
			if ($rows['id_konsumen']!=''){
				$token = date('Hi').rand(10,99);
				$this->session->set_userdata(array('id_konsumen'=>$rows['id_konsumen'], 'level'=>'konsumen','pin_used'=>$token));

				if ($this->session->idp!=''){
					$keranjang = $this->db->query("SELECT a.*, c.id_konsumen FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where a.session='".$this->session->idp."'");
					foreach ($keranjang->result_array() as $row) {
						$cek_exist = $this->db->query("SELECT * FROM rb_penjualan_temp where id_produk='$row[id_produk]' AND session='$rows[id_konsumen]'");
						if ($cek_exist->num_rows()<=0){
							if ($row['id_konsumen']!=$rows['id_konsumen']){
								$this->db->query("UPDATE rb_penjualan_temp SET session='$rows[id_konsumen]' where id_penjualan_detail='$row[id_penjualan_detail]'");
							}
						}else{
							$this->db->query("UPDATE rb_penjualan_temp SET jumlah='$row[jumlah]' where id_penjualan_detail='$row[id_penjualan_detail]'");
						}
					}
				}

				$this->session->set_userdata(array('idp'=>$rows['id_konsumen']));

				redirect('main');
			}else{
				if ($this->session->id_konsumen!=''){
					redirect('main');
				}else{
				    if ($data->email!=''){
    					$ex = explode('@',cetak($data->email));
    					$dataa = array('username'=>cetak($ex[0]),
    								'password'=>hash("sha512", md5($ex[0])),
    								'nama_lengkap'=>$data->given_name.' '.$data->family_name,
    								'email'=>cetak($data->email),
    								'jenis_kelamin'=>'Laki-laki',
    								'token'=>config('token_referral'),
    								'referral_id'=>$this->session->referral,
    								'tanggal_daftar'=>date('Y-m-d H:i:s'));
    					$this->model_app->insert('rb_konsumen',$dataa);
    					$id = $this->db->insert_id();
    					$this->session->set_userdata(array('id_konsumen'=>$id, 'level'=>'konsumen'));
    
    					$tgl_kirim = date("d-m-Y H:i:s");
    					$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
    					$subject      = "$iden[pengirim_email] - Pendaftaran Sukses,..";
    					$logo = $this->db->query("SELECT * FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
    					$kons = $this->model_reseller->profile_konsumen($id)->row_array();
    
    					$message  = "<html><body><span style='font-size:18px; color:green'>Selamat Bergabung di $iden[url]</span><br>
    											Hai $kons[nama_lengkap]! Terima kasih telah mendaftar di <a style='text-decoration:none; color:#000' href='$iden[url]'>$iden[url]</a>. <br>
    											Silakan untuk melengkapi data diri anda sesuai dengan identitas pada KTP di <a href='".base_url()."members/edit_profile'>Disini</a>.<br><br>
    
    											Akun Anda sudah kami aktifkan. Anda mendaftar menggunakan email: $kons[email].<br> 
    											Masukkan email dan password yang Anda daftarkan tersebut setiap kali log in ke $iden[url].<br><br>
    
    											Siap mencari produk dari ribuan<br>
    											penjual online di Indonesia? <a href='".base_url()."produk'>Klik Disini</a></body></html> \n";
    					
    					echo kirim_email($subject,$message,$kons['email']); 
    					echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Terima kasih, anda sudah terdaftar di <b>'.$iden['url'].'</b> silahkan cek email untuk verifikasi!</center></div>');
    					redirect('members/edit_profile');
				    }else{
				        echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal mendaftar dengan Email...</center></div>');
				        redirect('auth/login');
				    }
				}
			}

		   }									
		  }
	}

	public function google_logout(){
		$this->google->revokeToken();
        $this->session->sess_destroy();
		redirect('auth/login');
    }

	public function login(){
		if (isset($_POST['login'])){
		    
			$username = $this->input->post('a');
			if ($username==''){
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal! Akun tidak ditemukan didatabase!</center></div>');
				redirect('auth/login');
			}
			
			$cek = $this->db->query("SELECT * FROM rb_konsumen where email='".cetak($username)."' OR no_hp='".cetak($username)."'");
			$row = $cek->row_array();
			$total = $cek->num_rows();
			if ($total > 0){
				$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
				$token = date('Hi').rand(10,99);
				$this->session->set_userdata(array('idx'=>$row['id_konsumen'], 'token'=>$token,'username'=>$row['username']));
				$message_wa = "*$iden[pengirim_email]* : Hallo! Kode OTP anda *$token* _(Jangan Bagikan kode ini)_
					
Kode ini akan kedaluwarsa dalam 10 menit, Jika permintaan ini bukan dari anda, maka abaikan pesan ini.

_Dari ".base_url()."_";
					$this->model_app->wa(format_telpon($row['no_hp']),$message_wa);

				$subjek = "$iden[pengirim_email] - Token Login";
				$message_email = "Hallo! Kode OTP anda <b style='color:blue'>$token</b> (Jangan Bagikan kode ini)<br><br> 
				Kode ini akan kedaluwarsa dalam 10 menit,<br> 
				Jika permintaan ini bukan dari anda, maka abaikan pesan ini.
				
				<br><br>Dikirim Dari ".base_url();
				kirim_email($subjek,$message_email,$row['email']);
			}else{
				$data['title'] = 'Gagal Login';
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal! Akun tidak ditemukan didatabase!</center></div>');
				redirect('auth/login');
			}
		}

		if (isset($_GET['token'])){
			if ($this->session->username==''){
				$data['title'] = 'Gagal Login';
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal! anda tidak memiliki akses!</center></div>');
				redirect('auth/login');
			}else{
				$username = $this->session->username;
				$cek = $this->db->query("SELECT * FROM rb_konsumen where username='".cetak($username)."'");
				$row = $cek->row_array();
				$total = $cek->num_rows();
				if ($total > 0){
					$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
					$token = date('Hi').rand(10,99);
					$this->session->set_userdata(array('idx'=>$row['id_konsumen'], 'token'=>$token));
					$message_wa = "*$iden[pengirim_email]* : Hallo! Kode OTP anda *$token* _(Jangan Bagikan kode ini)_
					
Kode ini akan kedaluwarsa dalam 10 menit, Jika permintaan ini bukan dari anda, maka abaikan pesan ini.

_Dari ".base_url()."_";
					$this->model_app->wa(format_telpon($row['no_hp']),$message_wa);

					$subjek = "Token Login";
					$message_email = "Hallo! Kode OTP anda <b style='color:blue'>$token</b> (Jangan Bagikan kode ini)<br><br> 
					Kode ini akan kedaluwarsa dalam 10 menit,<br> 
					Jika permintaan ini bukan dari anda, maka abaikan pesan ini.
					
					<br><br>Dikirim Dari ".base_url();
					kirim_email($subjek,$message_email,$row['email']);
					echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Token Berhasil dikirimkan!</center></div>');
					redirect('auth/login');
				}else{
					$data['title'] = 'Gagal Login';
					echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal! Akun tidak ditemukan didatabase!</center></div>');
					redirect('auth/login');
				}
			}
		}

		if (isset($_POST['token'])){
			$token = implode($_POST['tok']);
			if ($token==$this->session->token){
				$this->session->set_userdata(array('id_konsumen'=>$this->session->idx, 'level'=>'konsumen','pin_used'=>$this->session->token));
				
				if ($this->session->idp!=''){
					$keranjang = $this->db->query("SELECT a.*, c.id_konsumen FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk JOIN rb_reseller c ON b.id_reseller=c.id_reseller where a.session='".$this->session->idp."'");
					foreach ($keranjang->result_array() as $rows) {
						$cek_exist = $this->db->query("SELECT * FROM rb_penjualan_temp where id_produk='$rows[id_produk]' AND session='".$this->session->id_konsumen."'");
						if ($cek_exist->num_rows()<=0){
							if ($rows['id_konsumen']!=$this->session->id_konsumen){
								$this->db->query("UPDATE rb_penjualan_temp SET session='".$this->session->id_konsumen."' where id_penjualan_detail='$rows[id_penjualan_detail]'");
							}
						}else{
							$this->db->query("UPDATE rb_penjualan_temp SET jumlah='$rows[jumlah]' where id_penjualan_detail='$rows[id_penjualan_detail]'");
						}
					}
				}

				// Remember To Factor Verification
				$this->session->set_userdata(array('idp'=>$this->session->id_konsumen));
				$cookie_pin_used = get_cookie('tajalapak_pin_used');
				if($cookie_pin_used <> ''){
					if ($cookie_pin_used==md5($this->session->id_konsumen)){
						$this->session->set_userdata(array('pin_used'=>date('Hi').rand(10,99)));
					}
				}

				// Remembers Login
				//if ($this->input->post('remember')=='on'){
					$ks = $this->db->query("SELECT password FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
					$key = $ks['password'];
					//set_cookie('tajalapak_login', $key, 3600*24*30); // set expired 30 hari
					$cookie = array(
                        'name'   => 'tajalapak_login',
                        'value'  => $key,                            
                        'expire' => 3600*24*30,                                                                                   
                        'secure' => TRUE
                        );
						$this->input->set_cookie($cookie);
				//}

				$this->session->unset_userdata('idx');
				$this->session->unset_userdata('username');
				redirect('main');
			}else{
				$data['title'] = 'Gagal Login';
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Maaf, Kode Token salah atau Kadaluarsa!</center></div>');
				redirect('auth/login');
			}
		}

		if ($this->session->id_konsumen!=''){
			echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Maaf, Anda saat ini sudah Terdaftar!</center></div>');
			redirect('main');
		}else{
			$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
			$data['title'] = "Masuk / Daftar di ".replace_url($iden['url']);
			$data['row'] = $this->model_utama->view_where('mod_alamat',array('id_alamat' => 2))->row_array();
			$this->template->load(template().'/template',template().'/reseller/view_login',$data);
		}
	}

	public function lupass(){
		if (isset($_POST['submit3'])){
			$email = cetak($this->input->post('a'));
			$no_telpon = cetak($this->input->post('b'));
			$cek = $this->db->query("SELECT * FROM rb_konsumen where email='$email' OR username='$email' OR no_hp='$no_telpon'");
		    $total = $cek->num_rows();
			if ($total > 0){
				$data = $cek->row_array();
				$tgl_kirim = date("d-m-Y H:i:s");
				$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
				$subject      = "$iden[pengirim_email] - Permintaan pergantian Password,..";
				$logo = $this->db->query("SELECT * FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
				
				$message      = "<html><body>Halooo! <b>".$data['nama_lengkap']."</b> ... <br> 
					Hari ini pada tanggal <span style='color:red'>$tgl_kirim</span> anda meminta akses untuk reset password...<br><br>

					Silahkan klik link berikut untuk mengubah password : <br>
					<a href='".base_url()."auth/resetpassword?token=$data[password].$data[id_konsumen]'>".base_url()."auth/resetpassword?token=$data[password].$data[id_konsumen]</a><br><br>

					Jika permintaan ini bukan dari anda maka silahkan melaporkannya kepada kami dengan mengirim email ke $iden[email].<br><br>

					Jika Anda mencurigai seseorang mungkin memiliki akses tidak sah ke akun Anda, kami sarankan Anda mengubah kata sandi sebagai tindakan pencegahan dengan mengunjungi $iden[url].</body></html> \n";

				echo kirim_email($subject,$message,$data['email']);
				echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Permintaan terkirim!, Cek email anda.</center></div>');
			}else{
				echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Maaf, data anda tidak ditemukan!</center></div>');
			}
			redirect('auth/login');
		}
	}

	function resetpassword(){
		$ex = explode('.',cetak($this->input->get('token')));
		$cek = $this->db->query("SELECT * FROM rb_konsumen where password='".cetak($ex[0])."' AND id_konsumen='".$ex[1]."'");
		if ($cek->num_rows()>=1){
			if (isset($_POST['submit'])){
				if (cetak($this->input->post('pass'))==cetak($this->input->post('repass'))){
					$password = hash("sha512", md5(strip_tags($this->input->post('pass'))));
					$this->db->query("UPDATE rb_konsumen set password='$password' where password='".cetak($ex[0])."' AND id_konsumen='".cetak($ex[1])."'");
					$row = $cek->row_array();
					$this->session->set_userdata(array('id_konsumen'=>$row['id_konsumen'], 'level'=>'konsumen'));
					if ($this->session->idp!=''){
						$data = array('kode_transaksi'=>$this->session->idp,
			        			  'id_pembeli'=>$row['id_konsumen'],
			        			  'id_penjual'=>$this->session->reseller,
			        			  'status_pembeli'=>'konsumen',
			        			  'status_penjual'=>'reseller',
			        			  'waktu_transaksi'=>date('Y-m-d H:i:s'),
			        			  'proses'=>'0');
						$this->model_app->insert('rb_penjualan',$data);
						$id = $this->db->insert_id();

						$query_temp = $this->db->query("SELECT * FROM rb_penjualan_temp where session='".$this->session->idp."'");
						foreach ($query_temp->result_array() as $r) {
							$data = array('id_penjualan'=>$id,
			        			  'id_produk'=>$r['id_produk'],
			        			  'jumlah'=>$r['jumlah'],
			        			  'diskon'=>$r['diskon'],
			        			  'harga_jual'=>$r['harga_jual'],
			        			  'satuan'=>$r['satuan']);
							$this->model_app->insert('rb_penjualan_detail',$data);
						}
						$this->db->query("DELETE FROM rb_penjualan_temp where session='".$this->session->idp."'");

						$this->session->unset_userdata('reseller');
						$this->session->unset_userdata('idp');
						$this->session->set_userdata(array('idp'=>$id));
					}
					redirect('main');
				}else{
					echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Maaf, Password tidak sama!</center></div>');
					redirect('auth/resetpassword?token='.cetak($this->input->get('token')));
				}
			}else{
				$data['title'] = 'Reset Password';
				$this->template->load(template().'/template',template().'/reseller/view_lupass',$data);
			}
		}else{
			echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Maaf, Token sudah kadaluarsa!</center></div>');
			redirect('auth/login');
		}
	}

	function logout(){
		//if ($this->session->level=='konsumen'){
			$this->session->sess_destroy();
			delete_cookie("tajalapak_login");
			redirect('main');
		//}
	}
}
