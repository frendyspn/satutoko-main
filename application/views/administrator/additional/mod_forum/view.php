            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Forum Diskusi</h3>
                  <a class='pull-right btn btn-primary btn-sm' href='<?php echo base_url(); ?>administrator/topic_baru'>Tambahkan Data</a>
                </div><!-- /.box-header -->
                <div class="box-body">
                <table class="table tblphpmu table-hover">
                  <thead>
                      <tr>
                      <th>No</th>
                      <th>Judul Topic</th>
                      <th>Balasan</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                      // Pinned Post
                      $pinned = $this->db->query("SELECT * FROM topic a JOIN kategori_topic c ON a.id_kategori_topic=c.id_kategori_topic where pin='1' ORDER BY id_topic DESC");
                      foreach ($pinned->result() as $r) {
                          if ($r->akun=='konsumen'){
                              $kons = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$r->id_konsumen."'")->row_array();
                              $nama = $kons['nama_lengkap'];
                          }else{
                              $kons = $this->db->query("SELECT * FROM users where username='".$r->id_konsumen."'")->row_array();
                              $nama = $kons['nama_lengkap'].' (Admin)';
                          }

                          if ($r->id_kategori_topic=='1'){ $colorr = 'green'; }elseif($r->id_kategori_topic=='2'){ $colorr = 'blue'; }elseif($r->id_kategori_topic=='3'){ $colorr = 'red'; }else{ $colorr = 'purple'; }
                          if (strlen($r->judul) > 75){ $judul = strip_tags(substr($r->judul,0,75)).',..';  }else{ $judul = strip_tags($r->judul); }
                          $reply = $this->db->query("SELECT id_komentar FROM comment where id_topic='".$r->id_topic."'")->num_rows();
                          if ($r->pin==''){ $pin = 'pin'; }else{ $pin = 'unpin'; }
                          echo "<tr>
                                  <td>#</td>
                                  <td><b>[PINNED] - <a title='".strip_tags($r->judul)."' style='color:#000; font-size:18px' href='".base_url()."administrator/read/$r->judul_seo'>$judul</a></b>
                                      <small style='color:#7b7b7b; display:block'><a target='_BLANK' style='color:#7b7b7b' href='#'><b>".strip_tags($nama)."</b></a> | Dibuat ".cek_terakhir($r->tanggal).", Telah Dilihat ".$r->view." Kali</small></td>
                                  <td><i class='fa fa-comment-o fa-fw'></i> <b>$reply</b> Comment</td>
                                  <td align=center><a href='".base_url()."administrator/topic_pin/$r->id_topic?$pin=0' class='btn btn-default btn-sm'><i class='fa fa-flag-o fa-fw'></i></a>
                                                  ".anchor('administrator/topic_edit/'.$r->id_topic,'<button class="btn btn-warning btn-sm"><i class="fa fa-edit fa-fw"></i></button> ')."
                                                  <a href='".base_url()."administrator/topic_delete/$r->id_topic' class='btn btn-danger btn-sm' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-remove fa-fw'></i></a></td>
                                </tr></tbody>";
                          $no++;
                      }
                      // End Pinned Post


                      if ($this->uri->segment(4) != ''){ $no = $this->uri->segment(4) + 1; }else{ $no = $this->uri->segment(3) + 1; }
                      foreach ($record->result() as $r) {
                          if ($r->akun=='konsumen'){
                              $kons = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$r->id_konsumen."'")->row_array();
                              $nama = $kons['nama_lengkap'];
                          }else{
                              $kons = $this->db->query("SELECT * FROM users where username='".$r->id_konsumen."'")->row_array();
                              $nama = $kons['nama_lengkap'].' (Admin)';
                          }
                          if ($r->id_kategori_topic=='1'){ $colorr = 'green'; }elseif($r->id_kategori_topic=='2'){ $colorr = 'blue'; }elseif($r->id_kategori_topic=='3'){ $colorr = 'red'; }else{ $colorr = 'purple'; }
                          if (strlen($r->judul) > 75){ $judul = strip_tags(substr($r->judul,0,75)).',..';  }else{ $judul = strip_tags($r->judul); }
                          $reply = $this->db->query("SELECT id_komentar FROM comment where id_topic='".$r->id_topic."'")->num_rows();
                          if ($r->pin==''){ $pin = 'pin'; }else{ $pin = 'unpin'; }
                          echo "<tr>
                                  <td>$no</td>
                                  <td>[<b style='color:$colorr'>".$r->nama_kategori_topic."</b>] - <a title='".strip_tags($r->judul)."' style='color:#000; font-size:18px' href='".base_url()."administrator/read/$r->judul_seo'>$judul</a>
                                  <small style='color:#7b7b7b; display:block'><a target='_BLANK' style='color:#7b7b7b' href='#'><b>".strip_tags($nama)."</b></a> | Dibuat ".cek_terakhir($r->tanggal).", Telah Dilihat ".$r->view." Kali</small></td>
                                  <td><i class='fa fa-comment-o fa-fw'></i> <b>$reply</b> Comment</td>
                                  <td align=center><a href='".base_url()."administrator/topic_pin/$r->id_topic?$pin=1' class='btn btn-success btn-sm'><i class='fa fa-flag fa-fw'></i></a>
                                                  ".anchor('administrator/topic_edit/'.$r->id_topic,'<button class="btn btn-warning btn-sm"><i class="fa fa-edit fa-fw"></i></button> ')."
                                                  <a href='".base_url()."administrator/topic_delete/$r->id_topic' class='btn btn-danger btn-sm' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-remove fa-fw'></i></a></td>
                                </tr></tbody>";
                          $no++;
                      }
                  ?>
              </table>

              <div class="ps-pagination">
                  <center><?php echo $this->pagination->create_links(); ?></center>
              </div>
              </div>