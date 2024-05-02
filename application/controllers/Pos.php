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
class Pos extends CI_Controller {
	function index(){
        cek_session_members();
        $data['title'] = title();
		$data['judul'] = 'Point Of Sales System';
        
		$id = reseller($this->session->id_konsumen);
		$title = "Point Of Sales System";
		$edit = $this->model_app->edit('rb_reseller',array('id_reseller'=>$id))->row_array();
		$data = array('rows' => $edit, 'title'=>$title);
		$this->template->load(template().'/template',template().'/reseller/mod_reseller/view_reseller_detail',$data);
    }
}