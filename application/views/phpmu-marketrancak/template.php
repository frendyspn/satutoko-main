<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title; ?></title>
    <?php
    
    $blokir = $this->db->query("SELECT * FROM block_ip where ip_address='$_SERVER[REMOTE_ADDR]'")->num_rows();
    if ($blokir>=1){
        echo ("<script LANGUAGE='JavaScript'>
                window.alert('Akses Anda Diblokir!');
                window.location.href='https://wa.me/6281267771344';
                </script>");
    }
    
    
    $tajalapak_login = get_cookie('tajalapak_login');
    if($tajalapak_login <> ''){
        $row = $this->db->query("SELECT id_konsumen FROM rb_konsumen where password='$tajalapak_login'")->row_array();
        $this->session->set_userdata(array('id_konsumen'=>$row['id_konsumen'], 'level'=>'konsumen','pin_used'=>date('Hi').rand(10,99)));
    }

    if ($this->session->id_konsumen==''){
        $cookie_pin_used = get_cookie('tajalapak_login');
        if($cookie_pin_used <> ''){
            $row = $this->db->query("SELECT * FROM rb_konsumen where md5(id_konsumen)='$cookie_pin_used'")->row_array();
            $this->session->set_userdata(array('id_konsumen'=>$row['id_konsumen'], 'level'=>'konsumen'));
        }
    }

    if ($this->uri->segment(1) == 'berita' and $this->uri->segment(2) == 'detail') {
        $rows = $this->model_utama->view_where('berita', array('judul_seo' => $this->uri->segment(3)))->row_array();
        $directory_img = "foto_berita";
        $foto_meta = $rows['gambar'];
        $meta_url = base_url() . $this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3);
    } elseif ($this->uri->segment(1) == 'produk' and $this->uri->segment(2) == 'detail') {
        $rows = $this->model_utama->view_where('rb_produk', array('produk_seo' => $this->uri->segment(3)))->row_array();
        $directory_img = "foto_produk";
        $ex = explode(';', $rows['gambar']);
        $foto_meta = $ex[0];
        $meta_url = base_url() . $this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3);
    }else{
        $rows = $this->model_utama->view_ordering_limit('logo', 'id_logo', 'DESC', 0, 1)->row_array();
        $directory_img = "logo";
        $foto_meta = $rows['gambar'];
        $meta_url = base_url();

        echo "<link rel='apple-touch-icon' sizes='57x57' href='".base_url()."asset/images/apple-icon-57x57.png'>
        <link rel='apple-touch-icon' sizes='60x60' href='".base_url()."asset/images/apple-icon-60x60.png'>
        <link rel='apple-touch-icon' sizes='72x72' href='".base_url()."asset/images/apple-icon-72x72.png'>
        <link rel='apple-touch-icon' sizes='76x76' href='".base_url()."asset/images/apple-icon-76x76.png'>
        <link rel='apple-touch-icon' sizes='114x114' href='".base_url()."asset/images/apple-icon-114x114.png'>
        <link rel='apple-touch-icon' sizes='120x120' href='".base_url()."asset/images/apple-icon-120x120.png'>
        <link rel='apple-touch-icon' sizes='144x144' href='".base_url()."asset/images/apple-icon-144x144.png'>
        <link rel='apple-touch-icon' sizes='152x152' href='".base_url()."asset/images/apple-icon-152x152.png'>
        <link rel='apple-touch-icon' sizes='180x180' href='".base_url()."asset/images/apple-icon-180x180.png'>
        <link rel='icon' type='image/png' sizes='192x192'  href='".base_url()."asset/images/android-icon-192x192.png'>
        <link rel='icon' type='image/png' sizes='32x32' href='".base_url()."asset/images/favicon-32x32.png'>
        <link rel='icon' type='image/png' sizes='96x96' href='".base_url()."asset/images/favicon-96x96.png'>
        <link rel='icon' type='image/png' sizes='16x16' href='".base_url()."asset/images/favicon-16x16.png'>
        <meta name='msapplication-TileColor' content='#ffffff'>
        <meta name='msapplication-TileImage' content='".base_url()."asset/images/ms-icon-144x144.png'>
        <meta name='theme-color' content='#ffffff'>";
    }
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="google-site-verification" content="<?= config('google_site_verification'); ?>" />
    <meta name="robots" content="index, follow">
    <meta name="description" content="<?= $description; ?>">
    <meta name="keywords" content="<?= $keywords; ?>">
    <meta name="author" content="phpmu.com">
    <meta name="robots" content="all,index,follow">
    <meta http-equiv="Content-Language" content="id-ID">
    <meta NAME="Distribution" CONTENT="Global">
    <meta NAME="Rating" CONTENT="General">
    <?php 
    // Schema.org markup for Google+ 
	echo '<meta itemprop="name" content="' . $title . '">
	<meta itemprop="description" content="' . $description . '">
    <meta itemprop="image" content="' . base_url() . 'asset/' . $directory_img . '/' . $foto_meta . '">';
    
	// Twitter Card data
    echo '
    <meta name="twitter:card" content="product">
	<meta name="twitter:site" content="'.config('twitter').'">
	<meta name="twitter:title" content="' . $title . '">
	<meta name="twitter:description" content="' . $description . '">
	<meta name="twitter:creator" content="'.config('twitter').'">
    <meta name="twitter:image" content="' . base_url() . 'asset/' . $directory_img . '/' . $foto_meta . '">';
    
	// Open Graph data
    echo '
    <meta property="fb:app_id" content="'.config('facebook_app_id').'">
    <meta property="og:title" content="' . $title . '" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="' .$meta_url. '" />
    <meta property="og:image" content="' . base_url() . 'asset/' . $directory_img . '/' . $foto_meta . '" />
    <meta property="og:description" content="' . $description . '"/>
    <meta property="og:site_name" content="' . $title . '" />';
    ?>
    
    <link rel="shortcut icon" href="<?php echo base_url(); ?>asset/images/<?php echo favicon(); ?>" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="rss.xml" />
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&amp;amp;subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/fonts/Linearicons/Linearicons/Font/demo-files/demo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/owl-carousel/assets/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/slick/slick/slick.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/lightGallery-master/dist/css/lightgallery.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/jquery-bar-rating/dist/themes/fontawesome-stars.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/css/phpmu_<?php echo ($this->session->theme != '' ? $this->session->theme : background()); ?>.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/css/custom.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/progressive-image.js/dist/progressive-image.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/summernote/summernote-bs4.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>asset/uploadfile.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/css/sweetalert.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    
    <script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/js/jquery-3.4.1.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/progressive-image.js/dist/progressive-image.js"></script>
    <script src="<?php echo base_url(); ?>asset/phpmu_scripts.js"></script>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5e603bd2b0e9af001248abb8&product=inline-share-buttons' async='async'></script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?= config('facebook_pixel'); ?>');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=<?= config('facebook_pixel'); ?>&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->
    <script>
        function copyToClipboard(element) {
	      var $temp = $("<input>");
	      $("body").append($temp);
	      $temp.val($(element).text()).select();
	      document.execCommand("copy");
	      $temp.remove();
        }

        $(document).ready( function() {
                $('.ajax-file-upload-filename').on('load', function() {
                originalString = 'aaa';
                hasil = originalString.replace(/<\/?[^>]+>/gi, '');
                $(".ajax-file-upload-filename").html(hasil);
            });
        });

        $(document).ready(function(){
    	    $('.myButton').on('click', function() {
                var $this = $(this);
                var loadingText = '<i class="fa fa-check"></i>';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 2000);
            });
	    });

        $(document).ready(function(){
    	    $('.myButtonL').on('click', function() {
                var $this = $(this);
                var loadingText = '<i style="font-size:18px; color:green" class="fa fa-check"></i>';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 2000);
            });
	    });

        $(document).ready(function() {
            $('.submitx').attr('disabled', true);
            $('.komentarx').on('keyup',function() {
                var textarea_value = $(".komentarx").val();
                if(textarea_value.trim() != '') {
                    $('.submitx').attr('disabled', false);
                } else {
                    $('.submitx').attr('disabled', true);
                }
            });
        });

        $(document).ready(function(){
    	    $('.spinnerButton').on('click', function() {
                var $this = $(this);
                var loadingText = '<div class="spinner-border" role="status"></div> Loading...';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 10000);
            });
        });

        $(document).ready(function(){
    	    $('.spinnerButton-xs').on('click', function() {
                var $this = $(this);
                var loadingText = '<div class="spinner-border" role="status"></div>';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 10000);
            });
        });

        $(document).ready(function(){
    	    $('#spinnerButton').on('click', function() {
                var $this = $(this);
                var loadingText = '<div class="spinner-border" role="status"></div> Loading...';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 10000);
            });
        });
        
        $(document).ready(function(){
    	    $('.oksimpanx').on('click', function() {
                var $this = $(this);
                var loadingText = '<span class="spinner-grow"></span> <b>Gagal Proses Pembayaran...</b>';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 5000);
            });

            $('#oksimpan').on('click', function() {
                var $this = $(this);
                var loadingText = '<span class="spinner-border"></span> <b>Tunggu Sebentar, Ya...</b>';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 20000);
            });

            $('#oksimpan_digital').on('click', function() {
                var $this = $(this);
                var loadingText = '<span class="spinner-border"></span> <b>Tunggu Sebentar, Ya...</b>';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 20000);
            });
	    });

        function nospaces(t) {
            if (t.value.match(/\s/g)) {
                alert('Tidak Boleh Menggunakan Spasi,..');
                t.value = t.value.replace(/\s/g, '');
            }
        }

        $(".formatNumber").on('keyup', function() {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            n = +n || 0;
            $(this).val(n.toLocaleString());
        });
    </script>

    <script>
        $(document).ready(function() {
            // Select your input element.
            var number = document.getElementsByClassName('qty');
            // Listen for input event on numInput.
            number.onkeydown = function(e) {
                if(!((e.keyCode > 95 && e.keyCode < 106)
                || (e.keyCode > 47 && e.keyCode < 58) 
                || e.keyCode == 8)) {
                    return false;
                }
            }

            $('#operatorx').change(function() {
                var operator_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('main/produk?page=home'); ?>",
                    data: "operator_id=" + operator_id,
                    success: function(response) {
                        $('#produkx').html(response);
                    }
                })
            });

            $('#operator').change(function() {
                var operator_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('main/produk'); ?>",
                    data: "operator_id=" + operator_id,
                    beforeSend: function(){
                        // Show image container
                        $("#loader").show();
                        $(".ppob").hide();
                        $("#historytrx").hide();
                    },
                    success: function(response) {
                        $('#produk').html(response);
                    },
                    complete:function(data){
                        // Hide image container
                        $("#loader").hide();
                        $(".ppob").show();
                    }
                })
            })

            $(document).on('click', '#id_pelanggan', function(e) {
                var operator_id = 133;
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('main/produk'); ?>",
                    data: "operator_id=" + operator_id,
                    beforeSend: function(){
                        // Show image container
                        $("#loader").show();
                        $(".ppob").hide();
                        $("#historytrx").hide();
                    },
                    success: function(response) {
                        $('#produk').html(response);
                    },
                    complete:function(data){
                        // Hide image container
                        $("#loader").hide();
                        $(".ppob").show();
                    }
                })
            });
        });

        function dibaca_all(id){
            $.ajax({
                type : "POST",
                url  : "<?php echo site_url('members/notifikasi_dibaca_all'); ?>",
                dataType : "JSON",
                data : {id:id},
                success: function(data){
                    $(".notifikasi_all").hide().load(" .notifikasi_all").fadeIn();
                    $(".notifikasi_all1").hide().load(" .notifikasi_all1").fadeIn();
                    $(".notifikasi_all2").hide().load(" .notifikasi_all2").fadeIn();
                    $(".notifikasi_count1").hide().load(" .notifikasi_count1").fadeIn();
                    $(".notifikasi_count2").hide().load(" .notifikasi_count2").fadeIn();
                }
            });
            return false;
        }

        function dibaca(data1,id){
            $.ajax({
                type : "POST",
                url  : "<?php echo site_url('members/notifikasi_dibaca'); ?>",
                dataType : "JSON",
                data : {id:id, data1:data1},
                success: function(data){
                    $(".notifikasi-"+id).hide().load(" .notifikasi-"+id).fadeIn();
                    $(".notifikasi1-"+id).hide().load(" .notifikasi1-"+id).fadeIn();
                    $(".notifikasi2-"+id).hide().load(" .notifikasi2-"+id).fadeIn();
                    $(".notifikasi_count1").hide().load(" .notifikasi_count1").fadeIn();
                    $(".notifikasi_count2").hide().load(" .notifikasi_count2").fadeIn();
                }
            });
            return false;
        }

    </script>

    <style>
        body {
            font-family: "Open Sans", Helvetica, Arial, sans-serif;
        }
        .modal{ z-index: 99999 !important ;}
        #mapid { height: 380px; }

        .menu--dropdown > li, .menu--dropdown > li > a {
            width:300px;
            height:25px;
            padding-bottom: 5px;
        }

        .menu--product-categories .menu--dropdown {
            padding-bottom: 10px;
        }

        .mega-menu .mega-menu__list li a {
            line-height: 10px;
        }

        .menu--dropdown > li.has-mega-menu .mega-menu{
            position: relative;
        }

        .menu--dropdown > li.has-mega-menu > .mega-menu{
            margin-top: -36px;
        }
        
        .menu--dropdown .mega-menu__list > li.has-mega-menu > .mega-menu {
            position: absolute;
            top: 0;
            left: 100%;
            width: auto;
            min-width: 260px;
            visibility: hidden;
            opacity: 0;
            border-left: none;
            margin-left: 1px;
        }
        
        .header--sticky .header__back, .header--sticky .header__back i{
            color:#000 !important;
        }

        .notif_header{
            background: #fff;
            padding: 10px 10px 10px 20px;
            border: 1px solid #cecece;
            border-bottom: 0px;
            margin: 0px;
        }

        .ps-container {
            max-width: 1200px;
        }

        .menu--dropdown .mega-menu__list > li:hover.has-mega-menu > .mega-menu {
            visibility: visible;
            opacity: 1;
            margin-left: 0px;
            margin-top: -1px;
        }
        .mega-menu__list_sub{
            margin-left: 15px !important;
        }
        .mega-menu__list_sub li{
            list-style: circle !important;
        }
        .container .ps-section__content {
            margin-bottom: 100px;
        }
        .ps-product--horizontal{
            border:0px;
            border-bottom: 1px solid #e9e9e9;
            border-left: 5px solid #e3e3e3;
            margin-top: 10px;
        }
        .textarea {
            padding: 10px 10px;
            resize: none;
            overflow: hidden;
            min-height: 60px;
            max-height: 300px;
            width: 100%;
        }
        .ps-product--cart{
            align-items: normal !important;
            margin-top: 3px;
        }
        .ajax-file-upload input{
            height:31px !important;
            cursor: pointer !important;
        }
        .ps-product__container .add-to-cart{
            padding: 3px 10px !important;
        }
        .menu > li > a:hover, .sub-menu > li > a:hover{
            text-decoration:none !important;
        }
        .btn{
            font-size:14px; 
            padding:0 30px
        }
        .group-order{
            background: #e3e3e3;
            color: red;
            border: 1px solid #8a8a8a;
            padding: 0px 10px;
            margin-top:5px;
        }
        .group-order i{
            font-weight: bold;
            background: red;
            color: #fff;
            font-size: 17px;
            padding: 3px 7px 6px 7px;
            margin: 3px 0px -10px -10px;
        }
        .form-control.error{
            font-style: normal;
        }
        .error{
            color: red;
            font-style: italic;
        }

        .margin-btn{
            padding: 10px 20px !important;
        }

        .dataTables_wrapper .row {
            width: 100%
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after {
            display: none
        }

        .dataTables_length select,
        .dataTables_filter input[type=search] {
            height: 30px
        }

        .dataTables_length {
            float: left
        }

        .dataTables_filter {
            float: right
        }

        #example1 th,
        #example11 th {
            font-weight: bold
        }

        .modal-content .btn-primary {
            height: 30px;
            font-size: 12px;
        }

        .modal-content input[type=text] {
            height: 30px;
        }

        .iconset .fa {
            font-size: 13px !important;
        }

        .is-invalid{
            color:red;
        }

        .blink_me {
            animation: blinker 1s linear infinite;
            color: red
        }

        .blink_me:hover {
            animation: blinker 0s linear infinite;
            color: red
        }

        @keyframes blinker {
            50% {
                opacity: 0
            }
        }

        .mb-10 {
            margin-bottom: 0px;
        }

        .add-to-cart, .add-to-cart-empty{
            padding: 11px 14px !important;
        }

        .pricing-table-product-box {
            border: solid 2px #f5f5f5;
        }

        .harga {
            font-size: 3em;
            font-weight: 700;
            line-height: .8em;
            display: inline-block;
        }

        .currency {
            font-size: 1em;
            font-weight: 700;
            margin-top: .2em;
            display: inline-block;
        }

        .waktu {
            font-size: .7em;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end;
            margin: .3em;
            display: inline-block;
        }

        .waktu_block {
            display: inline-block;
        }

        #Back-to-top {
            text-align: center;
            z-index: 99999;
            position: fixed;
            bottom: 70px;
            right: 30px;
            cursor: pointer;
            display: none;
            opacity: 0.7;
        }

        #Back-to-top:hover {
            opacity: 1;
        }

        .badge-secondary {
            color: #fff;
            background-color: #959595;
            padding: 5px 7px 4px 7px;
        }

        .notif .nav-tabs .nav-link {
            background: none;
            color: #000;
        }

        .notif .nav-tabs .nav-link:hover {
            text-decoration:none !important;
        }

        .notif .badge-secondary {
            color: #000;
            background-color: #e3e3e3;
            padding: 5px 7px 4px 7px;
        }

        .notif .nav-tabs .nav-item.show .nav-link,
        .notif .nav-tabs .nav-link.active {
            color: #ff7200 !important;
            background-color: #fff !important;
            border: none;
            border-bottom:2px solid #ff7200;
        }

        .penjualan .nav-tabs .nav-link {
            background: #fff;
            color: #000;
            border-bottom:1px solid #e3e3e3;
        }

        .penjualan .nav-tabs .nav-link:hover{
            text-decoration:none !important;
        }

        .penjualan .badge-secondary, .biodata .badge-secondary {
            color: #fff;
            background-color: #ff2e2e;
            padding: 5px 7px 4px 7px;
        }

        .penjualan .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            color: #ff7200 !important;
            background-color: #fff !important;
            border: none;
            border-bottom:2px solid #ff7200;
        }

        .container .ps-section__content {
            min-height: 650px;
        }

        input[type=number]::-webkit-inner-spin-button {
            opacity: 1
        }

        .form-control {
            /* border-bottom: 1px solid #cecece;
            border-top: 0px;
            border-left: 0px;
            border-right: 0px;
            background-color: #f9f9f9; */
        }

        .multiselect-container {
            width: 100%;
            font-size: 13px;
        }

        button.multiselect {
            font-size: 14px;
        }

        .multiselect-container>li {
            border-bottom: 1px dotted #e3e3e3;
        }

        .form-sm .form-group {
            margin-bottom: 5px !important;
        }

        .form-sm i {
            margin-left: 10px;
        }

        .form-sm .ajax-file-upload {
            width: 100%;
        }

        .no-margin{
            margin-bottom: 0px !important;
        }

        .biodata .col-form-label{
            color:#5d5d5d;
            font-weight: bold;
            background: #f9f9f9;
        }

        .checkbox-scroll { 
            border:1px solid #ccc; 
            width:100%; 
            height: 170px; 
            padding-left:8px; 
            overflow-y: scroll; 
        }

        @media (max-width: 479px) {
            .penawaran{
                font-size:20px !important;
            }

            .ps-product-list .ps-section__header h3{
                padding-bottom:0px;
                font-size:16px
            }

            .ps-product-list .ps-section__links li a{
                font-size: 11px;
                text-decoration: underline;
            }

            .ps-block--download-app .ps-block__content{
                padding: 0 15px;
            }

            .widget_contact-us h3{
                font-size:20px;
            }
            .ps-breadcrumb{
                display:none;
            }

            .ps-page--product {
                padding-top: 10px;
                border-top: 1px solid #d5d5d5;
            }
            #homepage-1 .ps-home-banner {
                margin-bottom: 0px;
            }
            .owl-carousel .owl-item img{
                height:auto !important;
            }

            #homepage-1 .ps-home-banner .container1{
                padding: 0px !important;
            }

            #homepage-1 .ps-home-banner .row1{
                margin: 0px !important;
            }

            #homepage-1 .ps-home-banner .col1{
                padding: 0px !important;
            }

            .slidex{
                margin: 0px !important;
            }

            .rekeningx{
                margin-top:0px !important;
                margin-bottom:10px;
                float: none;
                width:100%
            }
        }

        .xxx{
            border:0px !important;
            max-height: 207px;
            overflow: hidden;
        }

        @media (max-width: 998px) {
            .product__header h1 {
                font-size: 25px !important;
                font-weight: 600 !important;
            }

            .xxx .col4 {
                font-size: 24px !important;
                height: 90px !important;
                padding:0px !important;
                padding-top: 18px !important;
                text-align: center !important;
                margin-bottom: 5px !important;
                border-bottom: 0px solid #f4f4f4 !important;
                border-top: 0px solid #f4f4f4 !important;
            }

            .xxx .owl-item{
                width: 80px !important;
                margin-right: 5px !important;
                border:0px !important;
            }

            #homepage-1 .ps-home-banner .ps-container{
                margin-top:0px !important;
            }

            .ps-block--shopping-total {
                padding: 30px 15px !important;
            }

            #alamat_kirim{
                margin: 0px 0px 20px 0px !important;
            }

            .navigation--list .navigation__item span {
                font-size: 12px;
            }

            .ps-footer {
                padding-bottom: 50px;
            }

            .header .header__extra {
                width: 35px;
                height: 35px;
            }
            .header .header__extra span{
                bottom: 0;
                right: 10px;
                top: 5px;
                width: 16px;
                height: 16px;
                border-radius: 30%;
                font-size: 10px;
            }
            .header--mobile .header__extra span {
                background: #ff2e2e;
            }

            .header .header__extra span i {
                font-size: 10px;
            }
            .header--mobile .ps-block--user-header i {
                font-size: 20px;
            }
            .header--mobile .ps-block--user-header a {
                margin-top: 0px;
            }

            .header--mobile.header--sticky .navigation--mobile .navigation__right .header__extra > i {
                color: #000 !important;
            }
            .ps-section--shopping {
                padding: 10px 0;
            }
            .btn {
                padding: 0 10px;
                font-size:12px;
            }
            .margin-btn {
                padding: 5px 10px !important;
                font-size:12px;
            }

            .xxx .owl-carousel .owl-item img {
                width:48px !important;
                height:48px !important;
            }
            .xxx .owl-carousel .owl-item p {
                font-size:12px !important;
            }
            .ps-container {
                padding: 0 20px !important;
            }
        }

        .flashdeal{
            padding: 20px 15px !important;
        }

        .ps-block--countdown-deal figure{
            font-size: 14px !important;
            font-weight: 400 !important;
        }

        .xxx .col4 {
            font-size: 24px;
            padding: 10px 10px 0px 10px;
            text-align: center;
            height: 101px;
            border-top: 1px solid #f4f4f4;
        }

        .xxx .col4:hover{
            background-color: #fff;
            transition: 0.5s;
            border-color: green;
        }

        .xxx .col4 a:hover{
            text-decoration: none !important;
            text-shadow: 0 0 4px #fff, 0 0 0px #fff, 0 0 1px #fff, 0 0 2px #0fa, 0 0 172px #0fa, 0 0 112px #0fa, 0 0 112px #0fa, 0 0 151px #0fa;
        }

        @media (min-width:801px)  {
            .xxx .owl-item {
                width: 112.3px !important;
                margin-right: 0px !important;
                border-right: 1px solid #f4f4f4;
                border-top: 1px solid #f4f4f4;
            }
        }

        .xxx .owl-item:first-child {
            border-left: 1px solid #f4f4f4;
        }
        
        .xxx .owl-controls{
            display:none !important;
        }
        
        .xxx .ps-carousel--nav {
            margin-bottom: 0rem !important;
            padding-bottom: 0px !important;
        }

        input[type=checkbox]{
            height: 1em;
        }
        .selected-ongkir10{ background-color:#cecece; }
        .selected-ongkir11{ background-color:#cecece; }
        .selected-ongkir12{ background-color:#cecece; }

        .btn-custom{
            padding: 3px 10px;
            background: #f7f7f7;
            border: 1px solid #cecece;
            width: 90%;
            display: block;
        }

        .menu--mobile > li > a {
            padding: 7px 10px;
            border-left: 1px solid #fff;
            margin-left: 10px;
        }

        .menu--mobile > li {
            border-bottom: 1px dotted #dedede;
        }

        .menu--mobile > li.menu-item-has-children .sub-toggle{
            top: -5px;
        }

        .menu--mobile .sub-menu > li > a{
            border-bottom: 1px dotted #e3e3e3;
            padding: 3px 20px;
            margin-left: 30px;
        }

        .ps-product--detail .ps-product__price {
            padding:10px 20px;
            background: #eaeaea;
        }

        .show-map{
            display:none;
        }

        .ps-carousel--nav .owl-nav > * i{ border: 1px solid #f1f1f1 !important; }
        #google_translate_element2{ display: none;}
        .logtext{
            text-align: center;
            display: flex;
            margin: 10px 0px;
        }

        .logtext:before{
            content: '';
            -webkit-flex: 1 1;
            -ms-flex: 1 1;
            flex: 1 1;
            border-bottom: 1px solid rgba(0,0,0,0.12);
            margin: auto 18px auto 0;
            padding-right: 10px;
        }

        .logtext:after{
            content: '';
            -webkit-flex: 1 1;
            -ms-flex: 1 1;
            flex: 1 1;
            border-bottom: 1px solid rgba(0,0,0,0.12);
            margin: auto 0 auto 18px;
            padding-right: 10px;
        }

        .toko-list{
            padding:10px;
            margin-bottom:15px
        }

        .toko-name{
            border-bottom:5px solid #ededed;
            background: #f5f5f5;
            padding: 4px 10px;
            margin-bottom:5px
        }

        .container-checkbox {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            width:30px;
            margin:10px 0px;
        }

        /* Hide the browser's default checkbox */
        .container-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #fff;
            border: 2px solid #5c5c5c;
            border-radius: 5px;
        }

        /* On mouse-over, add a grey background color */
        .container-checkbox:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container-checkbox input:checked ~ .checkmark {
            background-color: green;
            border: 2px solid green;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container-checkbox input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container-checkbox .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        @media (min-width:999px){
            .navigation--mobile, .ps-search--mobile{
                display: none !important;
            }
            .header .header__topone .header__left {
                max-width: 40% !important;
            }

            .header .header__topone .header__right {
                text-align: right;
                max-width: 60% !important;
            }

            .ps-container {
                padding: 0 30px !important;
            }

            .container {
                max-width: 1060px;
            }
        }
        .form-group--number .form-control {
            padding: 0 5px !important;
        }
        .ps-product--detail .ps-product__shopping .form-group--number {
            max-width: 95px !important;
        }
        .judulmu{
            padding:5px; font-size:16px; font-weight:bold; background:#f4f4f4; border-bottom:1px solid #fff; margin-bottom:10px;
        }
        .modal-title {
            font-size: 16px !important;
        }
        #list_kurir_div1 li:hover, #list_kurir_div2 li:hover, #list_kurir_div3 li:hover{
            background-color: #dcffdc !important;
        }
        #list_sopir_div1 li:hover, #list_sopir_div2 li:hover, #list_sopir_div3 li:hover{
            background-color: #e7e7ff !important;
        }
        #kurir-list1 li:hover, #kurir-list2 li:hover, #kurir-list3 li:hover{
            background-color: #fbe8e8 !important;
        }
        a.ps-btn:hover{ text-decoration:none! important; color:#fff! important; }
        .unlink:hover{ text-decoration: none !important}

        .ps-block--countdown-deal figure figcaption {
            margin-right: 0px;
        }
        .ps-cart--mini .ps-cart__content a:hover, .notifikasi_all2 a:hover{
            text-decoration: none !important;
        }
        .ps-cart--mini .ps-cart__content .notifikasi_box, .notifikasi_box{
            padding:10px;
        }
        .ps-cart--mini .ps-cart__content .notifikasi_box:hover, .notifikasi_box:hover{
            background:#f7f7f7 !important;
        }
        .badge {
            border-radius: 20px;
        }
        div.scrollmenu {
            overflow: auto;
            white-space: nowrap;
        }

        div.scrollmenu a {
            display: inline-block;
            padding: 5px 15px;
            border: 1px solid #cecece;
            border-radius: 20px;
        }
        div.scrollmenu a.active{
            background: #f0fff3;
            border: 1px solid #41a330;
            color: green;
        }
        .berhasil_notif{
            padding:30px 30px
        }

        figure .form-control {
            height: 30px;
            padding: 0 20px;
            border-radius: 10px;
        }

        .leaflet-routing-container{
            display:none;
        }

        .ps-product--detail .ps-product__shopping > * {
            margin-right: 0px;
        }

        @media (max-width: 998px){
            .ps-product--detail .ps-product__shopping figure .form-group--number {
                max-width: 100% !important;
            }
            .ps-product--detail .ps-product__shopping figure .form-group--number .qty {
                border-color:green;
            }
            .product__header h1{
                font-size:18px !important;
            }
            .ps-product--detail .ps-product__header .ps-product__thumbnail {
                margin-bottom: 0rem !important;
            }
            .product__header {
                border-bottom: 1px solid #fff !important;
                margin-bottom: 0px !important;
            }
            .slick-slide {
                height: auto !important;
                margin-bottom:15px
            }

            figure .form-control {
                height: 25px;
                padding: 0 20px;
                border-radius: 10px;
            }
            .berhasil_notif{
                padding:10px
            }
            .list-produk{
                margin-bottom:20px
            }
            .ps-home-banner .owl-carousel .owl-item img {
                border-radius: 0px !important;
                min-height: auto !important;
            }
            .kontent-kategori, .kontent-kategori2{
                height:85px;
                overflow:hidden;
            }
            .xxx .ps-product {
                border-bottom: 0px solid #fff !important;
            }
            .ps-vendor-store {
                padding: 0px 0 !important;
            }
            #form-comment{
                position: fixed;
                bottom: 60px;
                left: 10px;
                right: 10px;
                background: #fff;
                padding-bottom: 10px;
            }
            
            .menu--mobile i{
                padding: 6px;
                border-radius: 30px;
                font-weight: 900;
                font-size: 20px;
                color: #000000;
                font-weight: 900;
            }
            .fa-square{ color:#26901b; }
        }

        .chat-text:hover { color: green; }
        .chat-text { cursor: pointer; }
        .ps-chat{
            text-transform: capitalize;
            font-size: 14px;
            background: #f9f9f9;
            color: #000 !important;
            float: right;
            border: 1px solid #8a8a8a;
            color: #fff;
            padding: 10px 10px;
        }
        .xxx .owl-carousel .owl-item img {
            border-radius: 50px;
            padding: 5px;
            background: #e3e3e3;
        }
        img:hover {
            -webkit-filter: grayscale(100%);
            filter: grayscale(100%);
        }
        .ps-home-banner .owl-carousel .owl-item img {
            border-radius: 20px 0px 0px 20px;
            min-height: 360px;
        }
        .xxx .ps-product {
            margin-top: 0px !important;
            border-bottom: 1px solid #f4f4f4 !important;
        }
        .ps-product {
            padding: 0px !important;
            margin-top: 10px;
        }
        .ps-product .ps-product__title {
            height: 40px;
            overflow: hidden;
        }

        .ps-block--store-2 .ps-block__author .ps-block__user {
            overflow:hidden !important;
        }
        .ps-block--store-2 .ps-block__author .ps-block__user img {
            border-radius: 0 !important;
        }
        li .icon-heart{ cursor:pointer; }
        textarea.form-control { padding: 1rem; }
        [type="submit"] { padding: 10px 25px !important; }
        [type="time"] { border: 1px solid #cecece; padding: 5px 10px; }
        .operasional .form-group > label { line-height: 1.4em; border-bottom:1px dotted #cecece; font-weight:bold; margin-top:10px !important }
        .operasional .form-group div { padding-top:15px }
        #submitKupon{ padding: 0px 25px !important; }
        .ps-block--vendor-filter [type="submit"] { padding: 0px 25px !important; right:5px }
        .form-group__rating .fa-star{ color:orange; }
        .alert { padding: 1.3rem 1.25rem; }
    </style>
</head>

<body>
    <div class="modal fade bd-example-modal-lg" style='z-index:99999' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style='border-bottom:0px solid #e9ecef'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 d-none d-sm-block" style='padding:0px 40px'>
                            <?php
                            $banner = $this->model_app->view_where_ordering('banner', array('posisi' => 'top'), 'id_banner', 'DESC');
                            foreach ($banner as $row) {
                                echo "<div class='ps-block__item'>
                            <div class='ps-block__left' style='display:block; float:left'><i style='font-size:35px; margin-right:20px' class='$row[icon]'></i></div>
                            <div class='ps-block__right'>
                                <h5 style='margin-bottom:0px'>$row[judul]</h5>
                                <p>$row[keterangan]</p>
                            </div>
                        </div>";
                            }
                            ?>
                            <hr style='padding:15px 0px 5px 0px'>
                            <div class="info--register-bottom" style='margin-bottom:20px'>
                                <center><span>Belum punya akun? </span> <a style='color:#000; text-decoration:underline; font-weight:bold' href="<?php echo base_url(); ?>auth/login" class="btn-register" target="_parent">Daftar sekarang!</a></center>
                            </div>
                        </div>
                        <div class="col-md-6" style='padding:0px 40px'>
                            <h3>Masuk ke Akun anda</h3><br>
                            <form action="<?php echo base_url(); ?>auth/login" method="POST">
                                <div class="ps-form__content">
                                    <div class="form-group" style='margin-bottom: 1.2rem;'>
                                    <label>Masukkan no Whatsapp untuk mengirimkan kode OTP</label>
                                        <input class="form-control" name='a' style='height:40px' placeholder='No. Whatsapp' type="text" onkeyup="nospaces(this)" autofocus autocomplete='off' required>
                                    </div>
                                    <div class="form-group submit" style='margin-bottom:5px'>
                                        <button type='submit' name='login' class="ps-btn ps-btn--fullwidth gray-btn custom-btn spinnerButton">Selanjutnya</button>
                                        <!--<div class="logtext">metode lainnya</div>-->
                                        <?php
                                        $ci = &get_instance();
                                        if (config('google_client_id')!=''){
                                            // echo "<a href='" .google_login(). "' class='ps-btn ps-btn--fullwidth red-btn custom-btn' style='margin: 4px 0px'>Google</a>";
                                        }
                                        if (config('facebook_app_id')!=''){
                                            $ci->load->library('facebook');
                                            // echo "<a href='" . $ci->facebook->login_url() . "' class='ps-btn ps-btn--fullwidth blue-btn custom-btn'>Facebook</a>";
                                        }
                                        ?>
                                        <center class='mt-2 d-block d-sm-none'><small>Belum Punya akun? <a class='text-success' href='<?= base_url() ?>auth/login'>Daftar</a></small></center>
                                    </div><br>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade lupa-example-modal-lg" style='z-index:99999' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style='border-bottom:0px solid #e9ecef'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6" style='padding:0px 40px'>
                            <?php
                            $banner = $this->model_app->view_where_ordering('banner', array('posisi' => 'top'), 'id_banner', 'DESC');
                            foreach ($banner as $row) {
                                echo "<div class='ps-block__item' style='margin-bottom: 10px;'>
                            <div class='ps-block__left' style='display:block; float:left'><i style='font-size:35px; margin-right:20px' class='$row[icon]'></i></div>
                            <div class='ps-block__right'>
                                <h5 style='margin-bottom:0px'>$row[judul]</h5>
                                <p>$row[keterangan]</p>
                            </div>
                        </div>";
                            }
                            ?>
                            <hr style='padding:15px 0px 5px 0px'>
                            <div class="info--register-bottom" style='margin-bottom:20px'>
                                <center><span>Belum punya akun? </span> <a style='color:#000' href="<?php echo base_url(); ?>auth/login" class="btn-register" target="_parent">Daftar sekarang!</a></center>
                            </div>
                        </div>
                        <div class="col-md-6" style='padding:0px 40px'>
                            <h3>LUPA PASSWORD?</h3>
                            <form action="<?php echo base_url(); ?>auth/lupass" method="POST">
                                <div class="ps-form__content">
                                    <div class="form-group" style='margin-bottom: 1.8rem;'>
                                        <label style='margin-bottom:5px' class="col-form-label">Username, Email</label>
                                        <input class="form-control" name='a' style='height:40px' type="text" autofocus required>
                                    </div>
                                    <div class="form-group">
                                        <label style='margin-bottom:5px' class="col-form-label">No. Handphone</label>
                                        <input class="form-control" name='b' style='height:40px' type="text" required>
                                    </div>
                                    <div class="form-group" style='margin-bottom: 1rem;'>
                                        <div class="ps-checkbox">
                                            <a href='#' class='text' data-dismiss="modal" aria-hidden="true">Batalkan?</a>
                                            <a href='#' style='color:#000' class='float-right' data-dismiss="modal" aria-hidden="true" data-toggle='modal' data-target='.bd-example-modal-lg'>Kembali Login?</a>
                                        </div>
                                    </div><br>
                                    <div class="form-group submit" style='margin-bottom:5px'>
                                        <button type='submit' name='submit3' class="ps-btn ps-btn--fullwidth">Kirimkan Permintaan</button>
                                    </div><br>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($this->uri->segment(1) != 'auth') {
        $idn = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
    ?>
        <header class="header header--1" data-sticky="true">
            <div class="header__topone">
                <div class="container">
                    <div class="header__left">
                        <p><?php echo $idn['info_atas']; ?></p>
                    </div>
                    <div class="header__right">
                        <ul class="header__top-links">
                            <!-- GTranslate: https://gtranslate.io/ -->
                            <li><select style='border-color:transparent; color:#686868; background:transparent' onchange="doGTranslate(this);"><option value="">Language</option><option value="en|id">Indonesia</option><option value="id|en">English</option><option value="id|zh-CN">Chinese</option></select><div id="google_translate_element2"></div></li>
                            
                            <script type="text/javascript">
                            function googleTranslateElementInit2() {new google.translate.TranslateElement({pageLanguage: 'id',autoDisplay: false}, 'google_translate_element2');}
                            </script>
                            <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>

                            <script type="text/javascript">
                            /* <![CDATA[ */
                            eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 7(a,b){n{4(2.9){3 c=2.9("o");c.p(b,f,f);a.q(c)}g{3 c=2.r();a.s(\'t\'+b,c)}}u(e){}}6 h(a){4(a.8)a=a.8;4(a==\'\')v;3 b=a.w(\'|\')[1];3 c;3 d=2.x(\'y\');z(3 i=0;i<d.5;i++)4(d[i].A==\'B-C-D\')c=d[i];4(2.j(\'k\')==E||2.j(\'k\').l.5==0||c.5==0||c.l.5==0){F(6(){h(a)},G)}g{c.8=b;7(c,\'m\');7(c,\'m\')}}',43,43,'||document|var|if|length|function|GTranslateFireEvent|value|createEvent||||||true|else|doGTranslate||getElementById|google_translate_element2|innerHTML|change|try|HTMLEvents|initEvent|dispatchEvent|createEventObject|fireEvent|on|catch|return|split|getElementsByTagName|select|for|className|goog|te|combo|null|setTimeout|500'.split('|'),0,{}))
                            /* ]]> */
                            </script>

                            <?php if ($this->session->level == '') { ?>
                            <li><i class="icon-telephone"></i> <a href="#"> Bantuan<strong> : <?php echo $idn['no_telp']; ?></strong></a></li>
                            <?php } ?>

                            <li><span class='d-none d-xl-block'><i class="icon-map-marker"></i> <a href="<?php echo base_url(); ?>konfirmasi/tracking">Telusuri Pesanan</a></span></li>
                            <?php
                            if (config('mode')=='marketplace'){
                                if ($this->session->level == 'konsumen') {
                                    if (reseller($this->session->id_konsumen) != '') {
                                        $komplain_toko = $this->db->query("SELECT * FROM rb_pusat_bantuan where id_terlapor='".$this->session->id_konsumen."' AND putusan='proses'"); 
                                ?>
                                        <li>
                                            <div class="ps-dropdown"><a href="#"><i class='icon-bag'></i> Seller Center <span class="badge badge-secondary"><?php echo (order_masuk(reseller($this->session->id_konsumen))+$komplain_toko->num_rows()); ?></span> </a>
                                                <ul class="ps-dropdown-menu">
                                                    <li><a href="<?php echo base_url(); ?>members/profil_toko"><i class='fa fa-gears'></i> Dashboard</a></li>
                                                    <li><a href="<?php echo base_url(); ?>members/operasional"><i class='fa fa-calendar'></i> Operasional</a></li>
                                                    <li><a href="<?php echo base_url(); ?>members/produk"><i class='fa fa-th'></i> Produk</a></li>
                                                    <li><a href="<?php echo base_url(); ?>members/alamat_cod"><i class='fa fa-map-marker'></i>&nbsp; Kurir Toko</a></li>
                                                    <?php if (config('reseller')=='Y'){ ?><li><a href="<?php echo base_url(); ?>members/pembelian"><i class='fa fa-reorder'></i> Jadi Reseller</a></li> <?php } ?>
                                                    <li><a href="<?php echo base_url(); ?>komplain?s=terlapor"><i class='fa fa-warning'></i> Komplain <span class="badge badge-secondary" style='font-size:85%; background-color: #cecece; color:#000'><?php echo $komplain_toko->num_rows(); ?></span></a></li>
                                                    <li><a href="<?php echo base_url(); ?>members/penjualan"><i class='fa fa-list-alt'></i> Orders <span class="badge badge-secondary" style='font-size:85%; background-color: #cecece; color:#000'><?php echo order_masuk(reseller($this->session->id_konsumen)); ?></span></a></li>
                                                    <li><a href="<?php echo base_url(); ?>members/upgrade"><i class="fa fa-star text-yellow"></i> <span class="blink_me">Upgrade</span></a></li>
                                                </ul>
                                            </div>
                                        </li>
                                <?php
                                    } else {
                                        echo "<li><a href='" . base_url() . "members/buat_toko'><i class='icon-bag'></i> Buat Toko</a></li>";
                                    }
                                }
                            }

                            $komplain_beli = $this->db->query("SELECT * FROM rb_pusat_bantuan where id_pelapor='".$this->session->id_konsumen."' AND putusan='proses'");
                            $jmlpesan_unread = $this->model_reseller->pesanbelumbaca()->num_rows(); 
                            ?>
                            <?php if ($this->session->level == 'konsumen') { ?>
                            <li><i class="icon-bubble"></i> <a href="<?php echo base_url(); ?>members/messages"> Inbox <span class="badge badge-secondary"><?= $jmlpesan_unread; ?></span></a></li>
                            <?php } ?>
                            <li>
                                <div class="ps-block--user-header">
                                    <div class="ps-block__left"><i class="icon-user"></i></div>
                                    <div class="ps-block__right">
                                        <?php
                                        if ($this->session->level == 'konsumen') {
                                            $sopir = $this->db->query("SELECT id_sopir FROM rb_sopir where id_konsumen='".$this->session->id_konsumen."'")->row_array();
                                            $cek_pesanan_sopir = $this->db->query("SELECT * FROM rb_penjualan a WHERE a.kurir='$sopir[id_sopir]' AND a.proses!='4' AND service='SOPIR'")->num_rows();
                                            $pesanan_sopir = '<span class="badge badge-secondary" style="font-size:85%; background-color: #cecece; color:#000">'.$cek_pesanan_sopir.'</span>';
                                            
                                            echo "<div class='ps-dropdown'>
                                                    <a style='padding-right:0px' href='#'>Akun <span class='badge badge-secondary'>".($komplain_beli->num_rows()+$cek_pesanan_sopir)."</span> <span class='fa fa-chevron-down'></span></a>
                                                    <ul class='ps-dropdown-menu'>";
                                                            $data = array('<i class="icon-user"></i> Profile','<i class="icon-couch"></i> Sosmed','<i class="icon-bag-dollar"></i> Data Bank','<i class="fa fa-money"></i> Keuangan','<i class="icon-heart"></i> Wishlist','<i class="icon-bag2"></i> Pembelian','<i class="icon-phone"></i> PPOB','<i class="icon-car"></i> Jadi Kurir '.$pesanan_sopir.'');
                                                            $link = array('profile','sosial_media','rekening_bank','withdraw','wishlist','orders_report','trx_pulsa','sopir');
                                                            for ($i=0; $i < count($data); $i++) { 
                                                                echo "<li><a href='".base_url()."members/".$link[$i]."'>".$data[$i]."</a></li>";
                                                            }
                                                        echo "<li><a href='" . base_url() . "komplain?s=pelapor'><i class='fa fa-warning'></i> Komplain <span class='badge badge-secondary' style='font-size:85%; background-color: #cecece; color:#000'>".$komplain_beli->num_rows()."</span></a></li>
                                                              <li><a href='" . base_url() . "auth/logout'><i class='icon-exit'></i> Logout</a></li>
                                                    </ul>
                                                  </div>";
                                                echo "";
                                        } else {
                                            echo "<a style='margin-right:0px' href='#' data-toggle='modal' data-target='.bd-example-modal-lg'>Login</a>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header__top">
                <div class="ps-container">
                    <div class="header__left">
                        <div class="menu--product-categories">
                            <?php include "inc_kategori.php"; ?>
                        </div>
                        <?php
                        $logo = $this->model_utama->view_ordering_limit('logo', 'id_logo', 'DESC', 0, 1);
                        foreach ($logo->result_array() as $row) {
                            echo "<a class='ps-logo' href='" . base_url() . "'><img src='" . base_url() . "asset/logo/$row[gambar]'/></a>";
                        }
                        ?>
                    </div>

                    <div class="header__center">
                        <form class="ps-form--quick-search" action="<?php echo base_url() ?>produk" method="GET">
                            <input style='border-left: 1px solid #e1e1e1;' class="form-control" name='s' value='<?= cetak($_GET['s']); ?>' type="text" placeholder="Aku mau Belanja..." autocomplete='off' required>
                            <button type='submit'>Cari</button>
                        </form>
                            <?php
                                $jumlah_tampil = 5;
                                $tag_populer = $this->db->query("SELECT group_concat(a.tag_seo separator ',') as tag_populer FROM(SELECT * FROM `tagpro` ORDER BY count DESC LIMIT 15) as a")->row_array();
                                if ($tag_populer['tag_populer']!=''){
                                    $random_keys=array_rand(explode(',',$tag_populer['tag_populer']),$jumlah_tampil);
                                    echo "<p class='populer'><b>Trending : </b> ";
                                    for ($i=0; $i < $jumlah_tampil; $i++) { 
                                        $tag_seo = explode(',',$tag_populer['tag_populer'])[$random_keys[$i]];
                                        echo "<a href='".base_url()."produk?f=0&s=$tag_seo'>#$tag_seo</a>";
                                    }
                                    echo "</p>";
                                }
                            ?>
                    </div>
                    <div class="header__right" style='max-width:160px'>
                        <div class="header__actions">

                            <div class='ps-cart--mini'>
                            <?php 
                            $jml_notifikasi = $this->model_utama->view_where('rb_notifikasi_send',array('id_konsumen'=>$this->session->id_konsumen,'dibaca'=>'N'))->num_rows(); 
                            ?>
                            <a class="header__extra notifikasi_count1" href="#"><i class="fa fa-bell-o"></i><span><i><?= $jml_notifikasi; ?></i></span></a>
                            <div class='ps-cart__content'>
                                <h4 class='notif_header'>Notifikasi</h4>
                                    <div class='ps-cart__items'>
                                        <dl class='notifikasi_all1'>
                                        <?php 
                                            $notifikasi = $this->db->query("SELECT * FROM rb_notifikasi_send a JOIN rb_notifikasi b ON a.id_notifikasi=b.id_notifikasi where a.id_konsumen='".$this->session->id_konsumen."' ORDER BY a.id_notifikasi_send DESC LIMIT 10");
                                            foreach ($notifikasi->result_array() as $row) {
                                                if (strlen($row['konten']) > 65){ $konten = strip_tags(substr($row['konten'],0,65)).',..';  }else{ $konten = strip_tags($row['konten']); }
                                                echo "<div class='m-0 notifikasi1-$row[id_notifikasi_send]'>
                                                    <div class='notifikasi_box' style='background:".($row['dibaca']=='N'?'#dcf9e4':'#ffffff')."'><a href='$row[url]'>
                                                        <dt> $row[judul]</dt>
                                                        <dd style='border-bottom:2px dotted #cecece; margin:0px; padding:5px 0px 10px 0px'>
                                                        <small class='text-success font-weight-bold'><i class='fa fa-clock-o'></i> ".cek_terakhir($row['waktu_kirim'])." lalu</small>
                                                        <input class='float-right' type='checkbox' id='$row[id_notifikasi_send]' onclick=\"dibaca('Y',this.id)\" ".($row['dibaca']=='N'?'':'checked disabled').">
                                                        <p>$konten</p></dd>
                                                    </a>
                                                    </div>
                                                    </div>";
                                            }

                                            if ($notifikasi->num_rows()<=0){
                                                echo "<center style='padding:50px 0px'>Tidak ada data</center>";
                                            }
                                        ?>
                                        </dl>
                                    </div>
                                    <div class='ps-cart__footer'>
                                        <div class="btn-group btn-block" style='border:1px solid #e3e3e3' role="group">
                                            <button class="btn btn-default text-success rounded-0 font-weight-bold" onclick="dibaca_all(<?= $this->session->id_konsumen; ?>)" style='font-size:12px; padding: 10px 5px; border-right:1px solid #e3e3e3'>Tandai Semua dibaca</button>
                                            <a class="btn btn-default text-success rounded-0 font-weight-bold" style='font-size:12px; padding: 10px 5px' href="<?= base_url(); ?>members/notifikasi">Lihat Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            $wishlist = $this->db->query("SELECT * FROM rb_konsumen_simpan where id_konsumen='" . $this->session->id_konsumen . "'")->num_rows();
                            echo "<div class='ps-cart--mini'><a class='header__extra' href='#'><i class='icon-bag2'></i><span><i class='show_cart_count'></i></span></a>
                                <div class='ps-cart__content'>
                                    <div class='ps-cart__items'>
                                        <div class='show_cart'></div>
                                    </div>
                                    <div class='ps-cart__footer'>
                                        <div class='show_cart_button'></div>
                                    </div>
                                </div>
                            </div>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "main-menu.php"; ?>
        </header>
    <?php } ?>
    <?php
    if ($this->uri->segment('1') == 'produk' and $this->uri->segment('2') == 'detail') {
        include "mobile/produk_detail.php";
    } else {
        include "mobile/home.php";
    }

    echo $contents;
    include "footer.php";
    $this->model_utama->kunjungan();

    if ($this->uri->segment(1) == 'main' or $this->uri->segment(1) == '') {
        if (get_cookie('notshow') == '') {
            $pop = $this->db->query("SELECT * FROM iklanatas ORDER BY id_iklanatas DESC LIMIT 1")->row_array();
            if ($pop['username'] == 'Y') {
                if ($this->session->id_konsumen == '') {
    ?>
                    <div class="ps-popup" id="subscribe" data-time="500">
                        <div class="ps-popup__content bg--cover" data-background="<?php echo base_url(); ?>/asset/foto_iklanatas/<?php echo $pop['gambar']; ?>"><a class="ps-popup__close" href="#"><i class="icon-cross"></i></a>
                            <form class="ps-form--subscribe-popup" action="<?php echo base_url() ?>main/subscribe" method="POST">
                                <div class="ps-form__content">
                                    <h4><?php echo $pop['judul']; ?></h4>
                                    <p><?php echo $pop['url']; ?></p>
                                    <div class="form-group">
                                        <input class="form-control" type="email" name='email' placeholder="Email Address" autocomplete='off' required>
                                        <div class="ps-checkbox">
                                            <input class="form-control" type="checkbox" id="not-show" name="notshow">
                                            <label for="not-show">Jangan Tampilkan lagi Form ini.</label>
                                        </div><br>
                                        <button type='submit' name='submit' class="ps-btn">Subscribe</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
    <?php }
            }
        }
    } ?>

    <div id="back2top"><i class="pe-7s-angle-up"></i></div>
    <!-- <div class="ps-site-overlay"></div> -->
    <!--<div id="loader-wrapper">
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>-->
    <div class="ps-search" id="site-search"><a class="ps-btn--close" href="#"></a>
        <div class="ps-search__content">
            <form class="ps-form--primary-search" action="do_action" method="post">
                <input class="form-control" type="text" placeholder="Search for...">
                <button><i class="aroma-magnifying-glass"></i></button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="Modal_Delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <center style='padding:30px 30px'>
                    <h3>Hapus Barang ?</h3>
                    Barang ini akan dihapus dari keranjangmu.
                    <div><br>
                        <input type="hidden" name="product_code_delete" id="product_code_delete" class="form-control">
                        <button type="button" style='width:130px' class="ps-btn ps-btn--outline" data-dismiss="modal">Kembali</button>
                        <button type="button" style='width:130px' type="submit" id="btn_delete" class="ps-btn">Hapus Barang</button>
                    </div>
                </center>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade" id="Modal_Notif" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <center style='padding:30px 30px'>
                    <h3>Peringatan!</h3>
                    <div id='error_notif'></div>
                    <div><br>
                        <button type="button" style='width:130px' class="ps-btn ps-btn--outline" data-dismiss="modal">Kembali</button>
                        <button type="button" style='width:130px' type="button" class="ps-btn" data-dismiss="modal">Coba Lagi!</button>
                    </div>
                </center>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade" id="myModal-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <div style='padding:30px 30px'>
                <div class="content-body"></div>
            </div>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Notifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Produk Berhasil Disimpan!
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style='padding:10px'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>
                <div class="modal-body">
                    <div class="content-body"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/jquery-1.12.4.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/bootstrap4/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/imagesloaded.pkgd.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/masonry.pkgd.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/isotope.pkgd.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/jquery.matchHeight-min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/slick/slick/slick.min.js"></script>

    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/slick-animation.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/lightGallery-master/dist/js/lightgallery-all.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/sticky-sidebar/dist/sticky-sidebar.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/plugins/select2/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

    <script src="<?php echo base_url(); ?>asset/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/jquery.uploadfile.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/js/main.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/js/sweetalert.min.js"></script>
    <script src="<?php echo base_url(); ?>template/<?php echo template(); ?>/js/jscriptku.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxflHHc5FlDVI-J71pO7hM1QJNW1dRp4U&amp;region=GB"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            show_cart();
            show_cart_detail();
            function show_cart(){
                $.ajax({
                    url   : '<?php echo site_url("produk/read_query"); ?>',
                    type  : 'GET',
                    async : true,
                    dataType : 'json',
                    success : function(data){
                        var html = '';
                        var html_button = '';
                        var html_cart = '';
                        var i;
                        for(i=0; i<data.length; i++){
                            var foto_all = data[i].gambar;
                            if (foto_all !== '') {
                                var strArray = foto_all.split(";");
                                var foto_produk = strArray[0];
                            }else{
                                var foto_produk = 'no-image.png';
                            }
                            $sub_total = ((data[i].harga_jual - data[i].diskon) * data[i].jumlah);
                            html += '<div class="ps-product--cart-mobile">'+
                                    '<div class="ps-product__thumbnail"><a href="<?php echo base_url(); ?>produk/detail/'+data[i].produk_seo+'"><img src="<?php echo base_url(); ?>asset/foto_produk/'+foto_produk+'" alt="'+data[i].nama_produk+'"></a></div>'+
                                    '<div class="ps-product__content">'+
                                    '<a href="javascript:void(0);" class="ps-product__remove item_delete" style="cursor:pointer" data-product_code="'+data[i].id_penjualan_detail+'"><i class="icon-cross"></i></a>'+
                                    '<a href="<?php echo base_url(); ?>produk/detail/'+data[i].produk_seo+'">'+data[i].nama_produk+'</a>'+
                                    '<p style="border-bottom:1px dotted #cecece"><b>Qty.</b> <small>'+data[i].jumlah+' x <b>'+toDuit(data[i].harga_jual - data[i].diskon)+'</b></small></p>'+
                                    '</div>'+
                                    '</div>';
                        }

                        if (data.length==0){
                            html += '<center style="padding:10px 15px">'+
                                    '<img style="width:90px" src="<?php echo base_url(); ?>asset/images/shopping-empty.png"><hr>'+
                                    '<h4>Wah keranjang belanjaanmu kosong!</h4>'+
                                    'Daripada dianggurin, mending isi dengan barang-barang impianmu. Yuk, cek sekarang!<br>'+
                                    '</center>';

                            html_button += '<figure><a style="padding:5px 20px; font-size:14px" class="ps-btn ps-btn--fullwidth" href="<?php echo base_url(); ?>produk">Mulai Belanja</a></figure>';
                        }else{
                            html_button += '<figure><a style="padding:5px 20px; font-size:14px" class="ps-btn ps-btn--fullwidth" href="<?php echo base_url(); ?>produk/keranjang">Lihat Sekarang</a></figure>';
                        }

                            html_cart += data.length;

                        $('.show_cart').html(html);
                        $('.show_cart_button').html(html_button);
                        $('.show_cart_count').html(html_cart);
                    }
                });
            } 

            $("#form1").validate({
                rules: {
                    variasi_1: "required",
                    variasi_2: "required",
                    variasi_3: "required",
                },
                messages: {
                    variasi_1: "",
                    variasi_2: "",
                    variasi_3: "",
                }
            })

            $('.add-to-cart').on('click',function(){
                $("#form1").valid();
                if ($("#form1").valid()==true){
                    var id = $(this).attr("id");
                    var var1 = $('#var1').val();
                    var var2 = $('#var2').val();
                    var var3 = $('#var3').val();

                    var varx1 = $('#warnax').val();
                    var varx2 = $('#ukuranx').val();
                    var varx3 = $('#lainnyax').val();

                    var id_level = $('#id_level').val();

                    var qty = $('#qty').val();
                    var group = $('#group').val();
                    var kgroup = $('#kgroup').val();
                    var $this = $(this);
                    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Process...';
                    if ($(this).html() !== loadingText) {
                        $this.data('original-text', $(this).html());
                        $this.html(loadingText);
                    }
                    setTimeout(function() {
                    $this.html($this.data('original-text'));
                    }, 2000);

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('produk/cart') ?>",
                        dataType: "json",
                        data: {
                            id: id, qty:qty, var1:var1, var2:var2, var3:var3, varx1:varx1, varx2:varx2, varx3:varx3, id_level:id_level, group:group, kgroup:kgroup
                        },
                        success: function(data) {
                            var strArray = data.split("|");
                            if(strArray[0]=='true'){
                                show_cart();
                                $(".m1keranjangx").hide().load(" .m1keranjangx").fadeIn();
                                $('#Keranjang_Notif').modal('show');
                                $('#berhasil_notif').html('<img style="max-width:70px; border-radius:5px; float: left; margin-right: 10px;" src="<?php echo base_url(); ?>asset/foto_produk/'+strArray[2]+'" alt="'+strArray[1]+'"> <div>'+strArray[1]+'</div> <a class="btn btn-success btn-sm" style="margin:inherit" href="<?php echo base_url(); ?>produk/keranjang">Lihat Keranjang</a>');
                            }else{
                                $('#Modal_Notif').modal('show');
                                $('#error_notif').html(data);
                            }
                        },
                    });
                    return false;
                }
            });

            //get data for delete record
            $('.show_cart').on('click','.item_delete',function(){
                var product_code = $(this).data('product_code');
                $('#Modal_Delete').modal('show');
                $('[name="product_code_delete"]').val(product_code);
            });

            //delete record to database
            $('#btn_delete').on('click',function(){
                var id = $('#product_code_delete').val();
                $.ajax({
                    type : "POST",
                    url  : "<?php echo site_url('produk/cart_remove')?>",
                    dataType : "JSON",
                    data : {id:id},
                    success: function(data){
                        $('[name="product_code_delete"]').val("");
                        $('#Modal_Delete').modal('hide');
                        show_cart();
                        show_cart_detail();
                    }
                });
                // $('.item_delete').on('click', function() {
                //     show_cart_detail();
                // });
                return false;
            });

            $('.add-to-cart-empty').on('click', function() {
                var $this = $(this);
                var loadingText = '<i class="fa fa-remove text-danger"></i> Habis';
                if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
                }
                setTimeout(function() {
                $this.html($this.data('original-text'));
                }, 2000);
            });

            function split_data(nama,variasi,nomor) {
				if (nama != null) {
					var strArray = nama.split("||");
                    var variasiArray = variasi.split("||");
                    if (strArray.length>0){
                        var data_file = '';
                        no = 1;
                        for(var i = 0; i < strArray.length; i++){
                            if (no%2 == 1){ bg = '#ececec'; }else{ bg = '#f4f4f4'; }
                            data_file += '<div style="background:'+bg+'"><div style="min-width:50px; display:inline-block"><b>'+strArray[i]+'</b></div> : '; 
                            var variasiArraysplit = variasiArray[i].split(";");
                            for(var ii = 0; ii < variasiArraysplit.length; ii++){
                                data_file += '<input type="checkbox" value="variasi'+no+''+ii+''+nomor+'|'+strArray[i]+':'+variasiArraysplit[ii]+'" name="variasi'+no+''+ii+''+nomor+'" style="height:1em"> '+variasiArraysplit[ii]+' &nbsp; '
                            }
                            data_file += '</div>'; 
                            no++;
                        }
                    }
					return data_file;
				}else{
					return '';
				}
			}
            
            function show_cart_detail(){
                $.ajax({
                    url   : '<?php echo site_url("produk/read_query"); ?>',
                    type  : 'GET',
                    async : true,
                    dataType : 'json',
                    success : function(data){
                        var html = '';
                        var html_button = '';
                        var html_cart = '';
                        var i;
                        for(i=0; i<data.length; i++){
                            var foto_all = data[i].gambar;
                            var catatan = data[i].keterangan_order;

                            if (foto_all !== null) {
                                var strArray = foto_all.split(";");
                                var foto_produk = strArray[0];
                            }else{
                                var foto_produk = 'no-image.png';
                            }

                            if (catatan !== null &&  catatan !== '') {
                                var catatanArray = catatan.split("||");
                                // var catatan_order = catatanArray[0];
                                if (catatanArray[1]==undefined){
                                    variasi = '<b>Variasi :</b> '+catatanArray[0];
                                    variasi1 = '';
                                }else{
                                    variasi = '<b>Variasi :</b> '+catatanArray[1];
                                    variasi1 = catatanArray[0];
                                }
                            }else{
                                // var catatan_order = '';
                                variasi = '';
                                variasi1 = '';
                            }

                            if (data[i].pre_order !== null && data[i].pre_order > 0){
                                var pre_order = '<span class="badge badge-secondary">Pre-order '+data[i].pre_order+' Hari</span>';
                            }else{
                                var pre_order = '';
                            }

                            $sub_total = ((data[i].harga_jual - data[i].diskon) * data[i].jumlah);
                            html += '<input type="hidden" name="id'+(i+1)+'" value="'+data[i].id_penjualan_detail+'"> '+
                                    '<input type="hidden" name="idp'+(i+1)+'" value="'+data[i].id_produk+'"> '+
                                    '<div class="ps-product--cart-mobile" style="padding: 10px 0">'+
                                    '<div class="ps-product__thumbnail"><a href="<?php echo base_url(); ?>produk/detail/'+data[i].produk_seo+'"><img src="<?php echo base_url(); ?>asset/foto_produk/'+foto_produk+'" alt="'+data[i].nama_produk+'"></a></div>'+
                                    '<div class="ps-product__content">'+
                                    '<a href="javascript:void(0);" class="ps-product__remove item_delete" style="cursor:pointer" data-product_code="'+data[i].id_penjualan_detail+'" ><i class="icon-cross"></i></a>'+
                                    '<p style="margin-bottom:0"> '+data[i].nama_reseller+' '+pre_order+' </p>'+
                                    '<a href="<?php echo base_url(); ?>produk/detail/'+data[i].produk_seo+'"><span style="font-size:17px; display:block; border-bottom:1px solid">'+data[i].nama_produk+'</span></a>'+
                                    '<p style="border-bottom:1px dotted #cecece; margin-bottom:0px"><b>Qty.</b> <small><input type="number" class="qty_update qty" min="1" id="'+data[i].id_produk+'" name="qty'+(i+1)+'" value="'+data[i].jumlah+'" style="display:inline-block; margin-bottom:3px; width:50px; text-align: center; " autocomplete="off"> x <b>'+toDuit(data[i].harga_jual - data[i].diskon)+'</b></small></p> '+variasi+
                                    // '<div style="padding:3px 0px">'+split_data(data[i].nama,data[i].variasi,(i+1))+' </div>'+
                                    '<input type="text" name="keterangan'+(i+1)+'" style="display:inline-block; margin-bottom:3px; width:100%; border:1px dotted #cecece" placeholder="Tulis Catatan untuk Penjual ('+data[i].nama_reseller+')..." value="'+variasi1+'" autocomplete="off">'+
                                    '</div>'+
                                    '</div>';
                        }

                        if (data.length<=0){
                            $(".keranjang-all").hide().load(" .keranjang-all").fadeIn();
                        }else{
                            $(".keranjang-page").hide().load(" .keranjang-page").fadeIn();
                        }
                        $('.show_cart_detail').html(html);
                    }
                });
            } 

            //get data for delete record
            $('.show_cart_detail').on('click','.item_delete',function(){
                var product_code = $(this).data('product_code');
                $('#Modal_Delete').modal('show');
                $('[name="product_code_delete"]').val(product_code);
            });

        });

        $(document).ready(function() {
            $(function() {
                $(window).scroll(function() {
                    if ($(this).scrollTop() > 400) {
                        $('#Back-to-top').fadeIn();
                    } else {
                        $('#Back-to-top').fadeOut();
                    }
                });
                $('#Back-to-top').click(function() {
                    $('body,html')
                        .animate({
                            scrollTop: 0
                        }, 300)
                        .animate({
                            scrollTop: 40
                        }, 200)
                        .animate({
                            scrollTop: 0
                        }, 130)
                        .animate({
                            scrollTop: 15
                        }, 100)
                        .animate({
                            scrollTop: 0
                        }, 70);
                });
            });

            $('#editor1').summernote({
                height: "300px",
                callbacks: {
                    onImageUpload: function(image) {
                        uploadImage(image[0]);
                    },
                    onMediaDelete: function(target) {
                        deleteImage(target[0].src);
                    }
                }
            });

            function uploadImage(image) {
                var data = new FormData();
                data.append("image", image);
                $.ajax({
                    url: "<?php echo site_url('members/upload_image') ?>",
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
                    data: {
                        src: src
                    },
                    type: "POST",
                    url: "<?php echo site_url('members/delete_image') ?>",
                    cache: false,
                    success: function(response) {
                        console.log(response);
                    }
                });
            }
        });
    </script>
    <script>
        function removecart(id, data2) {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('produk/cart_remove') ?>",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function(data) {
                    $(".remove-" + id).hide().load(" .remove-" + id).fadeIn();
                    $(".keranjang").hide().load(" .keranjang").fadeIn();
                    $(".keranjangx").hide().load(" .keranjangx").fadeIn();

                    $(".m1keranjangx").hide().load(" .m1keranjangx").fadeIn();
                }
            });
            return false;
        }
    </script>
    <script src="<?php echo base_url(); ?>asset/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            // Summernote
            $('#editor1').summernote()
        })

        $(".formatNumber").on('keyup', function() {
            var n = parseInt($(this).val().replace(/\D/g, ''), 10);
            n = +n || 0;
            $(this).val(n.toLocaleString());
        });
        $(document).ready(function() {
            $('#state').change(function() {
                var state_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('auth/city'); ?>",
                    data: "stat_id=" + state_id,
                    success: function(response) {
                        $('#city').html(response);
                    }
                })
            })
        })

        $(document).ready(function() {
            $('#state_reseller').change(function() {
                var state_id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('auth/city'); ?>",
                    data: "stat_id=" + state_id,
                    success: function(response) {
                        $('#city_reseller').html(response);
                    }
                })
            })
        })

        function toDuit(number) {
            var number = number.toString(),
                duit = number.split('.')[0],
                duit = duit.split('').reverse().join('')
                .replace(/(\d{3}(?!$))/g, '$1,')
                .split('').reverse().join('');
            return 'Rp ' + duit;
        }

        function toRupiah(number) {
            var number = number.toString(),
                duit = number.split('.')[0],
                duit = duit.split('').reverse().join('')
                .replace(/(\d{3}(?!$))/g, '$1,')
                .split('').reverse().join('');
            return duit;
        }


        $(function() {
            $("#example1").DataTable({
                "bSortable": false,
                "lengthChange": false,
                "pageLength": 20,
                "oLanguage": {
                    "sSearch": "Pencarian "
                }
            });
            $("#example11").DataTable({
                "bSortable": false,
                "lengthChange": false,
                "pageLength": 20,
                "oLanguage": {
                    "sSearch": "Pencarian "
                }
            });
            $("#example12").DataTable({
                "bSortable": false,
                "lengthChange": false,
                "pageLength": 20,
                "oLanguage": {
                    "sSearch": "Pencarian "
                }
            });
            $("#example13").DataTable({
                "bSortable": false,
                "lengthChange": false,
                "pageLength": 20,
                "oLanguage": {
                    "sSearch": "Pencarian "
                }
            });
            $("#example14").DataTable({
                "bSortable": false,
                "lengthChange": false,
                "pageLength": 20,
                "oLanguage": {
                    "sSearch": "Pencarian "
                }
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            $('#example3').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 10,
                "order": [
                    [4, "desc"]
                ]
            });
        });

        function save(id, data2) {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('produk/save') ?>",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function(data) {
                    $(".produk-" + id).hide().load(" .produk-" + id).fadeIn();
                    $("#exampleModalCenter").modal('show');
                    $(".wishlistcount").hide().load(" .wishlistcount").fadeIn();
                }
            });
            return false;
        }

        $(function() {
            $(document).on('click', '.quick_view', function(e) {
                e.preventDefault();
                $("#myModalDetail").modal('show');
                $.post("<?php echo site_url() ?>produk/quick_view", {
                        id: $(this).attr('data-id')
                    },
                    function(html) {
                        $(".content-body").html(html);
                    }
                );
            });
        });
    </script>


    <script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#multiple_select').multiselect({
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: false,
                maxHeight: 300,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '99%',
                numberDisplayed: 6
            });

            $('#multiple_select2').multiselect({
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: false,
                maxHeight: 200,
                enableCaseInsensitiveFiltering: true
            });
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

<script>
        $(document).ready(function(){
            //* select Provinsi */
            var base_urlx    = "<?php echo base_url();?>";
            $(".list_provinsi").change(function(){
                var id_provincex = this.value;
                kotax(id_provincex);
                $("#div_kota").show();
            });

            /* select Kota */
            kotax = function(id_provincex){
                $.ajax({
                type: 'post',
                url: base_urlx + 'produk/rajaongkir_get_kota',
                data: {id_province:id_provincex},
                dataType  : 'html',
                success: function (data) {
                    $(".list_kotakab").html(data);
                },
                beforeSend: function () {
                    
                },
                complete: function () {
                
                }
            });
            }

            $(".list_kotakab").change(function(){
                var id_kotax = this.value;
                kecamatanx(id_kotax);
                $("#div_kecamatan").show();
            });

            kecamatanx = function(id_kotax){
                $.ajax({
                type: 'post',
                url: base_urlx + 'produk/rajaongkir_get_kecamatan',
                data: {id_kota:id_kotax},
                dataType  : 'html',
                success: function (data) {
                    $(".list_kecamatan").html(data);
                }
            });
            }
        });
    </script>

</body>
</html>