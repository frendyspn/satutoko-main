<?php 
if ($this->session->level==''){
    redirect(base_url());
}else{
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WELCOME ADMINISTRATOR</title>
    <meta name="author" content="phpmu.com">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>asset/images/<?php echo favicon(); ?>" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/plugins/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/dist/css/style.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/admin/plugins/daterangepicker/daterangepicker-bs3.css">
    <style type="text/css"> .files{ position:absolute; z-index:2; top:0; left:0; filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; opacity:0; background-color:transparent; color:transparent; } </style><script src="https://tajalapak.com/asset/core_1.2.1.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>/asset/admin/plugins/jQuery/jquery-1.12.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/summernote/summernote.css">
    <link href="<?php echo base_url(); ?>asset/admin/plugins/combobox/bootstrap-combobox.css" media="screen" rel="stylesheet" type="text/css">
    <style type="text/css">
        body { font-size: 16px; }
        .modal-body { padding: 20px 34px; } .no-margin{ font-weight:bold } .textarea { padding: 10px 10px; resize: none; overflow: hidden; min-height: 60px; max-height: 300px; width: 100%; } #example thead tr, #table1 thead tr, #col-sm-9 thead tr, #example2 thead tr{ background-color: #e3e3e3; } .checkbox-scroll { border:1px solid #ccc; width:100%; height: 120px; padding:8px; overflow-y: scroll; } .combobox{ color:red} .ajax-file-upload input{ width: 260px !important; height: 30px !important; cursor:pointer !important } .skin-black .sidebar-menu>li>a { text-transform: uppercase; } .sidebar-menu .treeview-menu>li>a { font-size: 16px; } .main-header .logo{ font-family: unset; } .skin-black .wrapper, .skin-black .main-sidebar, .skin-black .left-side { background-color: #343a40; } .box-header { color: #000; background: #e5e5e5; } 
        .box.box-success, .box.box-info, .box.box-warning, .box.box-danger { border-top-color: #d2d6de; }
        .skin-black .sidebar-menu>li>.treeview-menu { background: #3c4248; } .skin-black .sidebar a, .skin-black .treeview-menu>li>a { color: #c2c7d0; }
        .sidebar-menu>li>a {
            padding: 5px 5px 5px 15px;
        }

        .table>tbody>tr>th, .form .table>tbody>tr>td:first-child{
            text-align:right;
        }

        .form-horizontal .control-label { padding-left:3px; padding-right:3px }
        
        .skin-black .sidebar-menu>li>a {
            border: 1px solid transparent;
            border-radius: 5px;
            background: transparent;
            margin: 5px;
        }
        .skin-black .sidebar-menu>li>a:hover {
            border: 1px solid transparent;
            border-radius: 5px;
            background: rgba(255,255,255,.1);
            margin: 5px;
        }
        .sidebar-menu li>a>.pull-right {
            font-weight: 900;
            font-size: larger;
        }

        .skin-black .sidebar-menu>li.header {
            color: #ffffff;
            background: #343a40;
            font-size: 16px;
            border-bottom: 1px solid #434d56;
            text-transform: uppercase;
            font-weight: 700;
        }
        .skin-black .sidebar-menu>li:hover>a, .skin-black .sidebar-menu>li.active>a {
            color: #fff;
            background: rgba(255,255,255,.1);
            border-left:none;
        }
        .skin-black .main-header>.logo, .skin-black .main-header>.logo:hover {
            color: #fff;
            background-color: #343a40;
            border-bottom: 1px solid #434d56;
            border-right: 0px;
        }
        .content-wrapper, .right-side {
            background-color: #f3f3f3;
        }
        .form-group {
            margin-bottom: 10px;
        }

        .form-control{
            border-radius: 0.25rem;
            border-color: #e9e9e9;
        }
        .radio {
            display: none;
        }
        .radio:not(:checked) + label {
            color: #333;
            background-color: #e7e7e7;
            border-color: #adadad;
            display: inline-block;
            padding: 1px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
        }
        .radio:checked + label {
            color:#fff;
            background-color: #2ca924;
            border-color: #36a93f;
            display: inline-block;
            padding: 1px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
        }
        .titlepg{
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #ffffff;
            color: #8a8a8a;
            border-left: 0px solid;
            background: linear-gradient(#ffffff,#f3f3f3);
            padding: 4px 10px;
        }

        .titlepg span{
            padding-right:10px;
            color:#3c3c3c
        }

        .alert .close{ left: -5px; top: 0px; }
    </style>
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/uploadfile.css">
    <script src="<?php echo base_url(); ?>asset/phpmu_scripts.js"></script>
    <script type="text/javascript">
    function nospaces(t){
        if(t.value.match(/\s/g)){
            alert('Maaf, Tidak Boleh Menggunakan Spasi,..');
            t.value=t.value.replace(/\s/g,'');
        }
    }
    $(".formatNumber").on('keyup', function(){
        var n = parseInt($(this).val().replace(/\D/g,''),10);
        $(this).val(n.toLocaleString());
    });
    
    function toDuit(number) {
            var number = number.toString(),
                duit = number.split('.')[0],
                duit = duit.split('').reverse().join('')
                .replace(/(\d{3}(?!$))/g, '$1,')
                .split('').reverse().join('');
            return 'Rp ' + duit;
    }
    </script>
  </head>
  <body class="hold-transition sidebar-mini skin-black">
    <div class="wrapper">
      <header class="main-header">
          <?php include "main-header.php"; ?>
      </header>

      <aside class="main-sidebar">
          <?php include "menu-admin.php"; ?>
      </aside>

      <div class="content-wrapper">
        <section class="content-header">
          <h1> Dashboard <small>Control panel </small> </h1>
        </section>

        <section class="content">
            <?php echo $contents; ?>
        </section>
        <div style='clear:both'></div>
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
          <?php include "footer.php"; ?>
      </footer>

      <div class="modal fade bs-example-modal-lg" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  <h5 class="modal-title" id="myModalLabel">Detail Data Donasi</h5>
              </div>
              <div class="modal-body">
                <div class="content-body"></div>
              </div>
          </div>
      </div>
      </div>

    </div><!-- ./wrapper -->
 
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/jquery.uploadfile.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>$.widget.bridge('uibutton', $.ui.button);</script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url(); ?>asset/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/plugins/pace/pace.js"></script>
    <!-- DataTables -->
    <script src="<?php echo base_url(); ?>asset/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url(); ?>asset/admin/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="<?php echo base_url(); ?>asset/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo base_url(); ?>asset/admin/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo base_url(); ?>asset/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo base_url(); ?>asset/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>asset/admin/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>asset/admin/dist/js/app.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/dist/js/jquery.nestable.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/plugins/combobox/bootstrap-combobox.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
          $('.combobox').combobox()
        });
        $(".formatNumber").on('keyup', function(){
            var n = parseInt($(this).val().replace(/\D/g,''),10);
            $(this).val(n.toLocaleString());
        });
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('#editor1').summernote({
            height: "300px",
            callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0]);
                },
                onMediaDelete : function(target) {
                    deleteImage(target[0].src);
                }
            }
        });

        $('#editor2').summernote({
            height: "300px",
            callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0]);
                },
                onMediaDelete : function(target) {
                    deleteImage(target[0].src);
                }
            }
        });

    function uploadImage(image) {
        var data = new FormData();
        data.append("image", image);
        $.ajax({
            url: "<?php echo site_url($this->uri->segment(1).'/upload_image')?>",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "POST",
            success: function(url) {
                $('#editor1').summernote("insertImage", url);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function deleteImage(src) {
        $.ajax({
            data: {src : src},
            type: "POST",
            url: "<?php echo site_url($this->uri->segment(1).'/delete_image')?>",
            cache: false,
            success: function(response) {
                console.log(response);
            }
        });
    }
    });
    </script>
    <script>
      $(function(){
          $(document).on('click','.lihat-donasi',function(e){
              e.preventDefault();
              $("#myModalDetail").modal('show');
              $.post("<?php echo site_url()?>administrator/lihat_donasi",
                  {id:$(this).attr('data-id')},
                  function(html){
                      $(".content-body").html(html);
                  }   
              );
          });
      });
    </script>

    <script src="<?php echo base_url(); ?>asset/summernote/summernote.js"></script>
    <script>
      $(function () {
        // Summernote
        $('#editor1').summernote()
      })
    </script>

    <script>
    $('#rangepicker').daterangepicker();
    $('.datepicker').datepicker();
    $('.datepicker1').datepicker({ 
        format: 'dd-mm-yyyy'
    });
      $(function () { 
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        $("#example3").DataTable();
      });
      $('#mutasi').DataTable({
        "aaSorting": [[ 3, "desc" ]]
      });
    </script>
    <script>
$(document).ready(function(){
//* select Provinsi */
var base_url    = "<?php echo base_url();?>";
$("#list_provinsi").change(function(){
    var id_province = this.value;
    kota(id_province);
    $("#div_kota").show();
});

/* select Kota */
kota = function(id_province){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kota',
    data: {id_province:id_province},
    dataType  : 'html',
    success: function (data) {
        $("#list_kotakab").html(data);
    },
    beforeSend: function () {
        
    },
    complete: function () {
      
    }
});
}

$("#list_kotakab").change(function(){
    var id_kota = this.value;
    kecamatan(id_kota);
    $("#div_kecamatan").show();
});

kecamatan = function(id_kota){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kecamatan',
    data: {id_kota:id_kota},
    dataType  : 'html',
    success: function (data) {
        $("#list_kecamatan").html(data);
    }
});
}
});
</script>
  <script type="text/javascript">
  // To make Pace works on Ajax calls
  $(document).ajaxStart(function() { Pace.restart(); });
    $('.ajax').click(function(){
        $.ajax({url: '#', success: function(result){
            $('.ajax-content').html('<hr>Ajax Request Completed !');
        }});
    });


    /** add active class and stay opened when selected */
    var url = window.location;

    // for sidebar menu entirely but not cover treeview
    $('ul.sidebar-menu a').filter(function() {
       return this.href == url;
    }).parent().addClass('active');

    // for treeview
    $('ul.treeview-menu a').filter(function() {
       return this.href == url;
    }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
  </script>
  </body>
</html>
<?php } ?>
