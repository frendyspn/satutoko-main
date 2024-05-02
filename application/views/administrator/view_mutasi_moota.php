<div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Mutasi Bank (Moota)</h3>
                  <span class='pull-right' style='font-size:18px'>Point <b><?php echo rupiah($user->point); ?></b></span>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="mutasi" class="table table-bordered table-sm table-striped table-condensed">
                    <thead>
                      <tr>
                        <th>Status</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th>Waktu</th>
                        <th>Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $riwayat = $tes;
                    $no = 1;
                    foreach($riwayat->data as $item){
                        if (strlen($item->description) > 80){ $description = strip_tags(substr($item->description,0,80)).',..';  }else{ $description = strip_tags($item->description); }
                        if ($item->type=='DB'){ 
                            $color = 'danger'; 
                            $text = 'red';
                        }else{ 
                            $color = 'success'; 
                            $text = 'green';
                        }
                        $ex = explode('.',$item->amount);
                        $bl = explode('.',$item->balance);
                        echo "<tr>
                            <td class='$color'><b style='padding-left:10px; color:$text'>".$item->type."</b></td>
                            <td style='color:blue; font-weight:bold'><span style='padding-left:10px'>".rupiah($ex[0])."</span></td>
                            <td><i>$description</i></td>
                            <td>".($item->transaction_date)."</td>
                            <td style='color:green; font-weight:bold'>".rupiah($bl[0])."</td>
                        </tr>";
                        $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>