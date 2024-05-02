            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Halaman Baru</h3>
                  <a class='pull-right btn btn-primary btn-sm' href='<?php echo base_url().$this->uri->segment(1); ?>/tambah_halamanbaru'>Tambahkan Data</a>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Judul</th>
                        <th>Link</th>
                        <th>Tgl Posting</th>
                        <th>Landing Page</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record as $row){
                    $tgl_posting = tgl_indo($row['tgl_posting']);
                    echo "<tr><td>$no</td>
                              <td>$row[judul]</td>";

                              if ($row['landing_page']=='Y'){
                                echo "<td><a target='_BLANK' href='".base_url()."p/$row[id_halaman]'>".base_url()."p/$row[id_halaman]</a></td>";
                              }else{
                                echo "<td><a target='_BLANK' href='".base_url()."halaman/detail/$row[judul_seo]'>".base_url()."halaman/detail/$row[judul_seo]</a></td>";
                              }

                              echo "<td>$tgl_posting</td>
                              <td>".($row['landing_page']=='Y'?'Ya':'Tidak')."</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url().$this->uri->segment(1)."/edit_halamanbaru/$row[id_halaman]'><span class='glyphicon glyphicon-edit'></span></a>";
                                if ($row['id_halaman']>'4'){
                                  echo " <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url().$this->uri->segment(1)."/delete_halamanbaru/$row[id_halaman]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>";
                                }
                              echo "</center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>