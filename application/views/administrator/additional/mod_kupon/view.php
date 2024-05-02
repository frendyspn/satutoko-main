            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Kupon Gratis Ongkir</h3>
                  <a class='pull-right btn btn-primary btn-sm' href='<?php echo base_url(); ?>administrator/tambah_kupon_ongkir'>Tambahkan Data</a>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Nama Kupon</th>
                        <th>Informasi Detail</th>
                        <th>Kode Kupon</th>
                        <th>Nilai Kupon</th>
                        <th>Digunakan</th>
                        <th>Min Order</th>
                        <th>Expired</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record as $row){
                    $digunakan = $this->db->query("SELECT a.*, b.pembayaran FROM `rb_penjualan_kupon` a JOIN rb_penjualan_otomatis b ON a.status_kupon=b.kode_transaksi where a.id_kupon='$row[id_kupon]' AND a.id_penjualan_detail='0'");
                    echo "<tr><td>$no</td>
                              <td>$row[keterangan]</td>
                              <td>$row[catatan]</td>
                              <td><b>$row[kode_kupon]</b></td>
                              <td>".rupiah($row['nilai_kupon'])."</td>
                              <td>".$digunakan->num_rows()." dari $row[jumlah_kupon]</td>
                              <td>".rupiah($row['min_order'])."</td>
                              <td>".tgl_indo($row['expire_date'])."</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url().$this->uri->segment(1)."/edit_kupon_ongkir/$row[id_kupon]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url().$this->uri->segment(1)."/delete_kupon_ongkir/$row[id_kupon]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>