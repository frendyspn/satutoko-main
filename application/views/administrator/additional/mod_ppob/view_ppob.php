            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">PPOB</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Nama PPOB</th>
                        <th>Icon Kode</th>
                        <th>Icon Image</th>
                        <th>aktif</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record as $row){
                    echo "<tr><td>$no</td>
                              <td style='text-transform:capitalize'>$row[nama_ppob]</td>
                              <td>".($row['icon_kode']==''?'-':$row['icon_kode'])."</td>
                              <td><a target='_BLANK' href='".base_url()."asset/images/$row[icon_image]'>$row[icon_image]</a></td>
                              <td>".($row['aktif']=='1'?'<i style="color:green">Aktif</i>':'<i style="color:red">Non-aktif</i>')."</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url()."administrator/edit_ppob/$row[id_ppob]'><span class='glyphicon glyphicon-edit'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>