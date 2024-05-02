<div class="ps-breadcrumb">
        <div class="ps-container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <?php 
                    echo "<li>$title</li>";
                ?>
            </ul>
        </div>
    </div>
    <div class="ps-page" id="shop-sidebar">
        <div class="container">
            <div class="ps-layout">
                <div class="ps-layout">
                    <div class="ps-block" style='min-height:400px'><br>
                    <?php echo $this->session->flashdata('message'); 
                        $this->session->unset_userdata('message'); ?>
                        <?php if ($this->session->id_konsumen!=''){ ?>
                            <a href='<?= base_url(); ?>forum/topic_baru' class='btn btn-primary float-right btn-sm'><i class='fa fa-plus fa-fw'></i> Buat Topic Baru</a>
                        <?php }else{ ?>
                            <a href='#' data-toggle='modal' data-target='.bd-example-modal-lg' class='btn btn-primary float-right btn-sm'><i class='fa fa-plus fa-fw'></i> Buat Topic Baru</a>
                        <?php } ?>
                        <table style='border-radius:6px; margin-top:30px' class="table tblphpmu table-hover">
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
                                    $reply = $this->db->query("SELECT id_komentar FROM comment where id_topic='".$r->id_topic."' AND aktif_komentar='Y'")->num_rows();
                                    echo "<tr>
                                            <td>#</td>
                                            <td><b>[PINNED] - <a title='".strip_tags($r->judul)."' style='color:#000; font-size:18px' href='".base_url()."forum/read/$r->judul_seo'>$judul</a></b>
                                                <small style='color:#7b7b7b; display:block'><a target='_BLANK' style='color:#7b7b7b' href='#'><b>".strip_tags($nama)."</b></a> | Dibuat ".cek_terakhir($r->tanggal).", Telah Dilihat ".$r->view." Kali</small></td>
                                            <td><i class='fa fa-comment-o fa-fw'></i> <b>$reply</b> Comment</td>";
                                            if ($this->session->id_konsumen!=''){
                                                if ($this->session->id_konsumen==$r->id_konsumen){
                                                    echo "<td align=center>".anchor('forum/topic_edit/'.$r->id_topic,'<button style="padding: 0 19px" class="btn btn-warning btn-sm"><i class="fa fa-edit fa-fw"></i></button> ');
                                                    echo "<a href='".base_url()."forum/topic_delete/$r->id_topic' class='btn btn-danger btn-sm' style='padding: 0 19px' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-remove fa-fw'></i></a></td>";
                                                }else{
                                                    echo "<td><center><a href='".base_url()."forum/read/$r->judul_seo' class='btn btn-success btn-sm'><i class='fa fa-search fa-fw'></i> Lihat</a></center></td>";
                                                }
                                            }else{
                                                echo "<td><a href='".base_url()."forum/read/$r->judul_seo' class='btn btn-success btn-sm'><i class='fa fa-search fa-fw'></i> Lihat</a></td>";
                                            }
                                        echo "</tr></tbody>";
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
                                    echo "<tr>
                                            <td>$no</td>
                                            <td>[<b style='color:$colorr'>".$r->nama_kategori_topic."</b>] - <a title='".strip_tags($r->judul)."' style='color:#000; font-size:18px' href='".base_url()."forum/read/$r->judul_seo'>$judul</a>
                                            <small style='color:#7b7b7b; display:block'><a target='_BLANK' style='color:#7b7b7b' href='#'><b>".strip_tags($nama)."</b></a> | Dibuat ".cek_terakhir($r->tanggal).", Telah Dilihat ".$r->view." Kali</small></td>
                                            <td><i class='fa fa-comment-o fa-fw'></i> <b>$reply</b> Comment</td>";
                                            if ($this->session->id_konsumen!=''){
                                                if ($this->session->id_konsumen==$r->id_konsumen){
                                                    echo "<td align=center>".anchor('forum/topic_edit/'.$r->id_topic,'<button style="padding: 0 19px" class="btn btn-warning btn-sm"><i class="fa fa-edit fa-fw"></i></button> ');
                                                    echo "<a href='".base_url()."forum/topic_delete/$r->id_topic' class='btn btn-danger btn-sm' style='padding: 0 19px' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><i class='fa fa-remove fa-fw'></i></a></td>";
                                                }else{
                                                    echo "<td><center><a href='".base_url()."forum/read/$r->judul_seo' class='btn btn-success btn-sm'><i class='fa fa-search fa-fw'></i> Lihat</a></center></td>";
                                                }
                                            }else{
                                                echo "<td><a href='".base_url()."forum/read/$r->judul_seo' class='btn btn-success btn-sm'><i class='fa fa-search fa-fw'></i> Lihat</a></td>";
                                            }
                                        echo "</tr></tbody>";
                                    $no++;
                                }
                            ?>
                        </table>

                        <div class="ps-pagination">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                        <div style='clear:both'><br></div>

                </div>
            </div>
        </div>
    </div>
