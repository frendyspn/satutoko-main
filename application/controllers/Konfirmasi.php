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
class Konfirmasi extends CI_Controller {
	function index(){
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/files/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '10000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
				$data = array('kode_transaksi'=>cetak($this->input->post('id', TRUE)),
			        		  'total_transfer'=>cetak($this->input->post('b', TRUE)),
			        		  'id_rekening'=>cetak($this->input->post('c', TRUE)),
			        		  'nama_pengirim'=>cetak($this->input->post('d', TRUE)),
			        		  'tanggal_transfer'=>cetak($this->input->post('e', TRUE)),
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran_konsumen',$data);
			}else{
				$data = array('kode_transaksi'=>cetak($this->input->post('id', TRUE)),
								'total_transfer'=>cetak($this->input->post('b', TRUE)),
								'id_rekening'=>cetak($this->input->post('c', TRUE)),
								'nama_pengirim'=>cetak($this->input->post('d', TRUE)),
								'tanggal_transfer'=>cetak($this->input->post('e', TRUE)),
			        		  'bukti_transfer'=>$hasil['file_name'],
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran_konsumen',$data);
			}
			$data1 = array('proses'=>'2');
			$where = array('kode_transaksi' => cetak($this->input->post('id', TRUE)));
			$this->model_app->update('rb_penjualan', $data1, $where);
			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Sukses Melakukan Konfirmasi Pembayaran Pesanan!</center></div>');
			redirect('konfirmasi/index?success');
		}else{
			$data['title'] = 'Konfirmasi Pesanan';
			$data['description'] = description();
			$data['keywords'] = keywords();
			if (isset($_POST['submit1']) OR $_GET['kode']){
				if ($_GET['kode']!=''){
					$kode_transaksi = filter($this->input->get('kode'));
				}else{
					$kode_transaksi = filter($this->input->post('a'));
				}
				$row = $this->db->query("SELECT a.id_penjualan, b.id_reseller FROM `rb_penjualan` a jOIN rb_reseller b ON a.id_penjual=b.id_reseller where status_penjual='reseller' AND a.kode_transaksi='$kode_transaksi'")->row_array();
				$data['record'] = $this->model_app->view('rb_rekening');
				$data['kode'] = $kode_transaksi;
				$data['total'] = $this->db->query("SELECT nominal FROM rb_penjualan_otomatis where kode_transaksi='".$kode_transaksi."'")->row_array();
				$data['rows'] = $this->model_app->view_where('rb_penjualan',array('id_penjualan'=>$row['id_penjualan']))->row_array();
				$this->template->load(template().'/template',template().'/reseller/view_konfirmasi_pembayaran',$data);
			}else{
				$this->template->load(template().'/template',template().'/reseller/view_konfirmasi_pembayaran',$data);
			}
		}
	}

	function tracking(){
	    if ($this->uri->segment('1')=='t'){
	        $kode_transaksi = filter($this->uri->segment(2));
	    }else{
			if ($this->uri->segment(3)!=''){
				$kode_transaksi = filter($this->uri->segment(3));
			}else{
				$kode_transaksi = filter($this->input->post('a'));
			}
	    }
    	    
		if (isset($_GET['trx_id'])){
			$strx = $this->db->query("SELECT status_trx FROM rb_penjualan_otomatis where kode_transaksi='$kode_transaksi'")->row_array();
			if ($strx['status_trx']==''){
				$data = array('catatan'=>cetak($_GET['trx_id']),'status_trx'=>cetak($_GET['status']));
				$where = array('kode_transaksi' => $kode_transaksi);
				$this->model_app->update('rb_penjualan_otomatis', $data, $where);
			}
		}

		if (isset($_POST['submit1']) OR $kode_transaksi!=''){
			$cek = $this->model_app->view_where('rb_penjualan',array('kode_transaksi'=>$kode_transaksi));
			if ($cek->num_rows()>=1){
				$data['title'] = 'Tracking Order '.$kode_transaksi;
				$data['judul'] = $kode_transaksi;
				$data['kode'] = $kode_transaksi;
				$data['description'] = description();
				$data['keywords'] = keywords();
				$data['rows'] = $this->db->query("SELECT * FROM rb_penjualan a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen JOIN rb_kota c ON b.kota_id=c.kota_id where a.kode_transaksi='$kode_transaksi'")->row_array();
				$data['record'] = $this->db->query("SELECT a.kode_transaksi, b.*, c.nama_produk, c.gambar, c.satuan, c.berat, c.produk_seo, d.nama_reseller FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan JOIN rb_produk c ON b.id_produk=c.id_produk JOIN rb_reseller d ON c.id_reseller=d.id_reseller where a.kode_transaksi='".$kode_transaksi."'");
				$data['total'] = $this->db->query("SELECT a.kode_transaksi, a.kurir, a.service, a.proses, sum(a.ongkir) as ongkir, sum(b.harga_jual*b.jumlah) as total, sum(b.diskon*b.jumlah) as diskon_total, sum(c.berat*b.jumlah) as total_berat FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan JOIN rb_produk c ON b.id_produk=c.id_produk where a.kode_transaksi='".$kode_transaksi."'")->row_array();
				$data['unik'] = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='".$kode_transaksi."'")->row_array();
			
				if (isset($_POST['submit1'])){
					notif_tracking_order($kode_transaksi);
				}
				
				if (isset($_GET['print'])){
					$this->load->view(template().'/reseller/view_tracking_print',$data);
				}else{
					$this->template->load(template().'/template',template().'/reseller/view_tracking_view',$data);
				}
			}else{
				redirect('konfirmasi/tracking');
			}
		}else{
			$data['title'] = 'Tracking Order';
			$data['description'] = description();
			$data['keywords'] = keywords();
			$this->template->load(template().'/template',template().'/reseller/view_tracking',$data);
		}
	}
	
	public function xendit_disbursement_webhook(){
	    Xendit::setApiKey(config('xendit_api'));
	    $datas = json_decode(file_get_contents('php://input'), true);
	    foreach ($datas as $dat){
            $data = array('keterangan'=>$dat->external_id,
				'status'=>'xendit_disbursement_webhook',
				'waktu'=>date('Y-m-d H:i:s'));
			$this->model_app->insert('rb_webhook',$data);
	    }
	}
	
	public function xendit_disbursement_cronjobs(){
	    Xendit::setApiKey(config('xendit_api'));
	    $withdraw = $this->db->query("SELECT * FROM rb_withdraw where transaksi='debit' AND status='Pending'");
	    foreach($withdraw->result_array() as $rowx){
    	    $getDisbursementsByExt = \Xendit\Disbursements::retrieveExternal($rowx['id_withdraw']);
            foreach($getDisbursementsByExt as $row){
                if ($row['status']=='COMPLETED'){
                    $status = 'Sukses';
                }elseif ($row['status']=='FAILED'){
                    $status = 'Batal';
                }else{
                    $status = 'Pending';
                }
                
                $datax = array('status'=>$status);
				$wherex = array('id_withdraw' =>$row['external_id']);
				$this->model_app->update('rb_withdraw', $datax, $wherex);
            }
	    }
	}
	
	public function xendit_disbursement(){
		Xendit::setApiKey(config('xendit_api'));
		if (isset($_GET['cek'])){
		    $external_id = $_GET['cek'];
            $getDisbursementsByExt = \Xendit\Disbursements::retrieveExternal($external_id);
            var_dump($getDisbursementsByExt);   
		}elseif (isset($_GET['bank'])){
            $getDisbursementsBanks = \Xendit\Disbursements::getAvailableBanks();
            foreach($getDisbursementsBanks as $row){
                echo "$row[code] $row[name]<br>";
                $data = array('code'=>$row['code'],
                                'nama_bank'=>$row['name']);
				$this->model_app->insert('rb_bank',$data);
            }
            // echo "<pre>";
            // print_r($getDisbursementsBanks);
            // echo "</pre>";
		}else{
		    // Pindahkan ke Halaman Penarikan dana / Withdraw
    		$key = md5(rand());
    		$params = [
                'external_id' => '12345',
                'amount' => 90000,
                'bank_code' => 'BCA',
                'account_holder_name' => 'MICHAEL CHEN',
                'account_number' => '1234567890',
                'description' => 'Disbursement from Example',
                'X-IDEMPOTENCY-KEY' => $key
              ];
            
              $createDisbursements = \Xendit\Disbursements::create($params);
              var_dump($createDisbursements);
		}
	}
	
	
	
	public function xendit(){
		$cek_kodetrx = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='".cetak($this->input->get('inv',TRUE))."' AND pembayaran is NULL");
		$kons = $this->db->query("SELECT b.email FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.kode_transaksi='".cetak($this->input->get('inv',TRUE))."' GROUP BY a.kode_transaksi")->row_array();
		if ($cek_kodetrx->num_rows()>=1){
			$data['title'] = 'Xendit Order';
			$data['description'] = description();
			$data['keywords'] = keywords();

			$rc = $cek_kodetrx->row_array();

			$extId = cetak($this->input->get('inv',TRUE));
			$email = $kons['email'];
			$description = "Total Tagihan #".$this->input->get('inv',TRUE);
			$amount = clean_rupiah($rc['nominal']);

			// https://github.com/xendit/xendit-php/blob/master/examples/InvoiceExample.php
			Xendit::setApiKey(config('xendit_api'));
			$key = md5(rand());
			$params = [
				"external_id" => $extId,
				"payer_email" => $email,
				"description" => $description,
				"amount" => $amount,

				// redirect url if the payment is successful
				"success_redirect_url"=> base_url()."konfirmasi/xendit_success?key=$key",

				// redirect url if the payment is failed
				"failure_redirect_url"=> base_url()."konfirmasi/tracking/$extId?xendit=fail",
			];

			$data = array('catatan'=>$key,'status_trx'=>NULL);
			$where = array('kode_transaksi' => $this->input->get('inv',TRUE));
			$this->model_app->update('rb_penjualan_otomatis', $data, $where);

			$invoice = \Xendit\Invoice::create($params);
			if (config('debug_xendit')=='Y'){
				print_r($invoice);
			}else{
				header("Location: ".$invoice["invoice_url"]);	
			}
		}
	}
	
	public function flip(){
		$cek_kodetrx = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='".cetak($this->input->get('inv',TRUE))."' AND pembayaran is NULL");
		$kons = $this->db->query("SELECT b.email FROM `rb_penjualan` a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen where a.kode_transaksi='".cetak($this->input->get('inv',TRUE))."' GROUP BY a.kode_transaksi")->row_array();
		
		if ($cek_kodetrx->num_rows()>=1){
			$data['title'] = 'Flip Order';
			$data['description'] = description();
			$data['keywords'] = keywords();

			$rc = $cek_kodetrx->row_array();

			$extId = cetak($this->input->get('inv',TRUE));
			$email = $kons['email'];
			$description = "Total Tagihan #".$this->input->get('inv',TRUE);
			$amount = clean_rupiah($rc['nominal']);
            $key = md5(rand());

            $getKonsumen = $this->db->query("SELECT nama_lengkap FROM rb_konsumen where email = '".$email."'")->row_array();

			$getApi = $this->db->query("SELECT value FROM rb_config where field = 'flip_api'")->row_array();
            $secret_key = base64_encode($getApi['value']."::");
            
            $getApiLink = $this->db->query("SELECT value FROM rb_config where field = 'flip_api_link'")->row_array();
            
            $payloads = [
                "title" => $description,
                "amount" => $amount,
                "type" => "SINGLE",
                "redirect_url" => base_url()."konfirmasi/xendit_success?key=$key",
                "is_address_required" => 0,
                "is_phone_number_required" => 0,
                "sender_name" => $getKonsumen['nama_lengkap'],
                "sender_email" => $email,
                "step" => 2
            ];
            
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $getApiLink['value'].'v2/pwf/bill',
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

			$data = array('catatan'=>$key,'status_trx'=>NULL);
			$where = array('kode_transaksi' => $this->input->get('inv',TRUE));
			$this->model_app->update('rb_penjualan_otomatis', $data, $where);

			header("Location: https://".$invoice["link_url"]);	
		}
	}

	public function xendit_success(){
		$status_trx = '1'; 
		$id = cetak($this->input->get('key')); 
		$query_cekx = $this->db->query("SELECT kode_transaksi FROM rb_penjualan_otomatis where catatan='$id'");
		if ($query_cekx->num_rows()>=1){
			$cekx = $query_cekx->row_array();
			$cek_digital = $this->db->query("SELECT if(c.produk_file is null,'0','1') as pf FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
												JOIN rb_produk c ON a.id_produk=c.id_produk
													where b.kode_transaksi='$cekx[kode_transaksi]' AND c.jenis_produk='Digital' GROUP BY pf")->num_rows();
			if ($cek_digital=='1'){
				$proses = '3'; 
			}else{
				$proses = '1'; 
			}

			$datax = array('pembayaran'=>$status_trx,'status_trx'=>'xendit');
			$where = array('catatan' =>$id);
			$this->model_app->update('rb_penjualan_otomatis', $datax, $where);

			$idp = $this->db->query("SELECT kode_transaksi FROM rb_penjualan_otomatis where catatan='$id'")->row_array();
			$data_idp = array('proses'=>$proses);
			$where_idp = array('kode_transaksi'=>$idp['kode_transaksi'],'status_pembeli'=>'konsumen');
			$this->model_app->update('rb_penjualan', $data_idp, $where_idp);

			notif_pembayaran_sukses($idp['kode_transaksi']);
		}
		redirect('konfirmasi/tracking/'.$idp['kode_transaksi']);
	}
	
	
	public function deposit_xendit(){
		$row = $this->db->query("SELECT * FROM rb_withdraw where id_withdraw='".cetak($this->input->get('inv',TRUE))."'")->row_array();
		$kons = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
		if ($row['status']!='Sukses'){
			$data['title'] = 'Xendit Deposit';
			$data['description'] = description();
			$data['keywords'] = keywords();

			$extId = cetak($this->input->get('inv',TRUE));
			$email = $kons['email'];
			$description = "Deposit  ID-".date('YmdHis')."/$kons[id_konsumen]/".$this->input->get('inv',TRUE);
			$amount = clean_rupiah($row['nominal']);

			// https://github.com/xendit/xendit-php/blob/master/examples/InvoiceExample.php
			Xendit::setApiKey(config('xendit_api_depo'));
			$key = md5(rand());
			$params = [
				"external_id" => $extId,
				"payer_email" => $email,
				"description" => $description,
				"amount" => $amount,

				// redirect url if the payment is successful
				"success_redirect_url"=> base_url()."konfirmasi/unotify_deposit_xendit?key=$key",

				// redirect url if the payment is failed
				"failure_redirect_url"=> base_url()."members/withdraw/$extId?xendit=fail",
			];
			
			$data = array('keterangan'=>$key,'status'=>'Proses');
			$where = array('id_withdraw' => $this->input->get('inv',TRUE));
			$this->model_app->update('rb_withdraw', $data, $where);

			$invoice = \Xendit\Invoice::create($params);
			if (config('debug_xendit')=='Y'){
				print_r($invoice);
			}else{
				header("Location: ".$invoice["invoice_url"]);	
			}
			
		}else{
			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Maaf, Deposit ini sudah Dibayarkan!</center></div>');
			redirect('members/withdraw');
		}
	}

	public function unotify_deposit_xendit(){
		$id = cetak($this->input->get('key')); 
		$idp = $this->db->query("SELECT id_withdraw FROM rb_withdraw where keterangan='$id'")->row_array();
		$data = array('status'=>'Sukses');
		$where = array('id_withdraw' => $idp['id_withdraw']);
		$this->model_app->update('rb_withdraw', $data, $where);
	}

	public function bayar(){
		$cek_kodetrx = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='".cetak($this->input->get('inv',TRUE))."' AND pembayaran is NULL");
		if ($cek_kodetrx->num_rows()>=1){
            $rc = $cek_kodetrx->row_array();
            $produk[] = "Total Tagihan #".$this->input->get('inv',TRUE);
			$jumlah[] = '1';
			$harga[] = $rc['nominal'];
			$catatan_order[] = '';

			
			// Fee Transaksi Dibebankan ke Pembeli Jika menggunakan Pembayaran via Payment Gateway
			if (config('ipaymu_fee')>0){
				array_push($produk,"Fee Transaksi");
				array_push($harga,config('ipaymu_fee'));
				array_push($jumlah,"1");
				array_push($catatan_order,"");
			}

			/** Initialize Config */
			$conf['product'] = $produk;
			$conf['price'] = $harga;
			$conf['quantity'] = $jumlah;
			$conf['comments'] = $catatan_order;

			$conf['ureturn'] = site_url('konfirmasi/tracking/'.$this->input->get('inv',TRUE));
			$conf['unotify'] = site_url('konfirmasi/unotify');
        	$conf['ucancel'] = site_url('konfirmasi/ucancel');
			$conf['uniqid'] = uniqid();
			
			/** Load Lib and Init */
			$this->load->library('ipaymu', $conf);
			/** Call Response */
			$response = $this->ipaymu->response();

			/** Result Response */
			$resp = json_decode($response);
			$data = array('catatan'=>$resp->sessionID,'status_trx'=>NULL);
			$where = array('kode_transaksi' => $this->input->get('inv',TRUE));
			$this->model_app->update('rb_penjualan_otomatis', $data, $where);
			if (config('debug_ipaymu')=='Y'){ 
			    print_r($resp); 
			}else{
			    redirect($resp->url);
			}
		}else{
			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Maaf, Status Pembayaran untuk Orderan ini sudah Lunas!</center></div>');
			redirect('konfirmasi/tracking/'.$this->input->get('inv',TRUE));
		}
	}
	
	public function unotify(){
		if ($this->input->post('status')=='berhasil'){ 
			$status_trx = '1'; 
			$id = cetak($this->input->post('trx_id')); 
			$cekx = $this->db->query("SELECT kode_transaksi FROM rb_penjualan_otomatis where catatan='$id'")->row_array();
			$cek_digital = $this->db->query("SELECT if(c.produk_file is null,'0','1') as pf FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
												JOIN rb_produk c ON a.id_produk=c.id_produk
													where b.kode_transaksi='$cekx[kode_transaksi]' AND c.jenis_produk='Digital' GROUP BY pf")->num_rows();
			if ($cek_digital=='1'){
				$proses = '3'; 
			}else{
				$proses = '2'; 
			}
		}else{ 
			$status_trx = ''; 
			$id = cetak($this->input->post('sid')); 
			$proses = 0; 
		}
		$datax = array('pembayaran'=>$status_trx,'status_trx'=>cetak($this->input->post('status')),'catatan'=>cetak($this->input->post('trx_id')),'penampung'=>cetak($this->input->post('via')));
		$where = array('catatan' =>$id);
		$this->model_app->update('rb_penjualan_otomatis', $datax, $where);

		$idp = $this->db->query("SELECT kode_transaksi FROM rb_penjualan_otomatis where catatan='$id'")->row_array();
		$data_idp = array('proses'=>$proses);
   		$where_idp = array('kode_transaksi'=>$idp['kode_transaksi'],'status_pembeli'=>'konsumen');
   		$this->model_app->update('rb_penjualan', $data_idp, $where_idp);

		if ($this->input->post('status')=='berhasil'){
			$mut = $this->db->query("SELECT kode_transaksi FROM rb_penjualan_otomatis where catatan='".cetak($this->input->post('trx_id'))."'")->row_array();
			notif_pembayaran_sukses($mut['kode_transaksi']);
		}
	}


	public function deposit(){
		$row = $this->db->query("SELECT * FROM rb_withdraw where id_withdraw='".cetak($this->input->get('inv',TRUE))."'")->row_array();
		if ($row['status']!='Sukses'){
			$produk = "Kredit (Deposit Saldo Akun)";
			$harga = $row['nominal'];
			$jumlah = 1;
			$catatan_order = "";
			
			// Fee Transaksi Dibebankan ke Pembeli Jika menggunakan Pembayaran via Payment Gateway
// 			if (config('ipaymu_fee')>0){
			    
// 				array_push($produk,"Fee Transaksi");
// 				array_push($harga,config('ipaymu_fee'));
// 				array_push($jumlah,"1");
// 				array_push($catatan_order,"");
// 			}

			/** Initialize Config */
			$conf['product'] = $produk;
			$conf['price'] = $harga;
			$conf['quantity'] = $jumlah;
			$conf['comments'] = $catatan_order;

			$conf['ureturn'] = site_url('members/withdraw?id='.$this->input->get('inv',TRUE));
			$conf['unotify'] = site_url('konfirmasi/unotify_deposit');
        	$conf['ucancel'] = site_url('konfirmasi/ucancel_deposit');
			$conf['uniqid'] = uniqid();
			
			/** Load Lib and Init */
			$this->load->library('ipaymu', $conf);
			/** Call Response */
			$response = $this->ipaymu->response();

			/** Result Response */
			$resp = json_decode($response);
			$data = array('keterangan'=>$resp->sessionID,'status'=>'Proses');
			$where = array('id_withdraw' => $this->input->get('inv',TRUE));
			$this->model_app->update('rb_withdraw', $data, $where);
			if (config('debug_ipaymu')=='Y'){ 
			    print_r($resp); 
			}else{
			    redirect($resp->url);
			}
		}else{
			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Maaf, Deposit ini sudah Dibayarkan!</center></div>');
			redirect('members/withdraw');
		}
	}

	public function unotify_deposit(){
		if ($this->input->post('status')=='berhasil'){ $status_trx = 'Sukses'; $id = cetak($this->input->post('sid')); $proses = 2; }else{ $status_trx = 'Pending'; $id = cetak($this->input->post('sid')); $proses = 0; }
		$idp = $this->db->query("SELECT id_withdraw FROM rb_withdraw where keterangan='$id'")->row_array();
		$data = array('status'=>$status_trx);
		$where = array('id_withdraw' => $idp['id_withdraw']);
		$this->model_app->update('rb_withdraw', $data, $where);
	}

	public function pembayaran(){
		$data['invoice'] = $this->input->post('trx');
		$this->load->view(template().'/reseller/pembayaran',$data);
	}

	public function batalkanpesanan88dshweu8dh78785ufhd8(){
		cek_session_members();
		$kode_transaksi = cetak($this->input->get('kode'));
		$idp = $this->db->query("SELECT * FROM rb_penjualan where kode_transaksi='$kode_transaksi' AND id_pembeli='".$this->session->id_konsumen."'")->row_array();
		if ($idp['proses']=='0'){
			// Langsung batal karena masih pending
			$data = array('proses'=>'x');
			$where = array('kode_transaksi' => $kode_transaksi);
			$this->model_app->update('rb_penjualan', $data, $where);

			$rek = $this->db->query("SELECT id_rekening FROM `rb_rekening` ORDER BY RAND() DESC LIMIT 1")->row_array();
			$row = $this->db->query("SELECT a.jumlah, a.diskon, a.harga_jual, sum((a.jumlah*a.harga_jual)-a.diskon) as total_belanja FROM rb_penjualan_detail a where a.id_penjualan='$idp[id_penjualan]'")->row_array();
			$ong = $this->db->query("SELECT kode_transaksi, ongkir, id_pembeli FROM rb_penjualan where id_penjualan='$idp[id_penjualan]'")->row_array();
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

			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Berhasil Membatalkan Pesanan!</center></div>');
		
		}elseif ($idp['proses']=='1'){
			// Cek Pembayaran Pesanan yang diproses dan masuk ke form alasan pembatalan untuk menunggu konfirmasi seller
			
		}else{
			echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Gagal Membatalkan Pesanan!</center></div>');
		}
		redirect('konfirmasi/tracking/'.$kode_transaksi);
	}
}
