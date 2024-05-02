<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Kupon</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart($this->uri->segment(1).'/tambah_kupon_ongkir',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='120px' scope='row'>Nama Kupon</th>    <td><input type='text' class='form-control' name='keterangan' required></td></tr>
                    <tr><th width='120px' scope='row'>Info Detail</th>    <td><input type='text' class='form-control' name='catatan' required></td></tr>
                    <tr><th width='120px' scope='row'>Kode Kupon</th>    <td><input type='text' onkeyup=\"nospaces(this)\" class='form-control' name='kode_kupon' placeholder='* * * * * * *' required></td></tr>
                    <tr><th width='120px' scope='row'>Nilai Kupon</th>    <td><input type='text' class='form-control formatNumber' value='0' name='nilai_kupon' required></td></tr>
                    <tr><th width='120px' scope='row'>Jumlah Kupon</th>    <td><input type='number' class='form-control' name='jumlah_kupon' value='1' required></td></tr>
                    <tr><th width='120px' scope='row'>Min. Order</th>    <td><input type='text' class='form-control formatNumber' value='50,000' name='min_order' required></td></tr>
                    <tr><th width='120px' scope='row'>Expire Date</th>    <td><input type='text' class='form-control datepicker1' value='".date('d-m-Y')."' name='expire_date' required></td></tr>
                    </tbody>
                  </table>
                </div>
              
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Tambahkan</button>
                    <a href='".base_url().$this->uri->segment(1)."/kupon_ongkir'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div></div></div>";
            echo form_close();
