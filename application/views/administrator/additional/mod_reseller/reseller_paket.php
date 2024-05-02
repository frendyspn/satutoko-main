            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Permintaan Paket Star Seller</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Nama Reseller</th>
                        <th>Tagihan</th>
                        <th>Paket Pilihan</th>
                        <th>Running</th>
                        <th>Users</th>
                        <th>Waktu Request</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){
                    if ($row['status']=='Y'){ 
                      $color = 'green'; 
                      $status = 'Aktif'; 
                      $stat = 'N'; 
                      $tombol = 'default'; 
                      $info = "onclick=\"return confirm('Yakin ingin mengubah status jadi Non Aktif??')\""; 
                    }else{ 
                      $color = 'red'; 
                      $status = 'Non Aktif'; 
                      $stat = 'Y'; 
                      $tombol = 'success'; 
                      $info = "onclick=\"return confirm('Yakin ingin Aktifkan paket ini??')\""; 
                    }

                    if ($row['users']=='toko'){
                      $ress = $this->db->query("SELECT * FROM rb_reseller where id_reseller='$row[id_reseller]'")->row_array();
                      $nama = $ress['nama_reseller'];
                      $url = "detail_reseller/$ress[id_reseller]";
                    }else{
                      $kon = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='$row[id_reseller]'")->row_array();
                      $nama = $kon['nama_lengkap'];
                      $url = "detail_konsumen/$kon[id_konsumen]";
                    }
                    echo "<tr><td>$no</td>
                              <td><a href='".base_url()."administrator/$url'>$nama</a></td>
                              <td><b>".rupiah($row['tagihan'])."</b></td>
                              <td>$row[nama_paket], Durasi <b>$row[durasi]</b> Hari</td>
                              <td style='color:$color'>$status</td>
                              <td>$row[users]</td>
                              <td>".jam_tgl_indo($row['waktu_paket'])."</td>
                              <td><center>";
                              if ($row['users']=='toko'){
                                if ($row['paket']=='on'){
                                  echo "<a class='btn btn-default btn-xs' title='Sukses Data' href='#' onclick=\"return alert('Paket ini telah digunakan.')\"><span class='glyphicon glyphicon-ok'></span></a>";
                                }else{
                                  echo "<a class='btn btn-$tombol btn-xs' title='Sukses Data' href='".base_url()."administrator/reseller?paket=$stat&id=$row[id_reseller_paket]&durasi=$row[durasi]&p=toko' $info><span class='glyphicon glyphicon-ok'></span></a>";
                                }
                                  echo " <a class='btn btn-danger btn-xs' title='Batalkan Data' href='".base_url()."administrator/reseller?paket=$stat&id=$row[id_reseller_paket]&p=toko' onclick=\"return confirm('Apa anda yakin untuk Hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>";
                              }else{
                                if ($row['paket']=='on'){
                                  echo "<a class='btn btn-default btn-xs' title='Sukses Data' href='#' onclick=\"return alert('Paket ini telah digunakan.')\"><span class='glyphicon glyphicon-ok'></span></a>";
                                }else{
                                  echo "<a class='btn btn-$tombol btn-xs' title='Sukses Data' href='".base_url()."administrator/reseller?paket=$stat&id=$row[id_reseller_paket]&durasi=$row[durasi]&p=konsumen' $info><span class='glyphicon glyphicon-ok'></span></a>";
                                }
                                  echo " <a class='btn btn-danger btn-xs' title='Batalkan Data' href='".base_url()."administrator/reseller?paket=$stat&id=$row[id_reseller_paket]&p=konsumen' onclick=\"return confirm('Apa anda yakin untuk Hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>";
                              }
                                echo "</center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>