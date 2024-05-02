<style>
	.user1{
		width:40px;
		height:40px;
		float:left;
		position:absolute;
		animation: pulse 1s infinite ease-in-out;
		-webkit-animation: pulse 1s infinite ease-in-out;
	}

	.line1{
		height:12px;
		margin:10px 10px 10px 0px;
		animation: pulse 1s infinite ease-in-out;
		-webkit-animation: pulse 1s infinite ease-in-out;
	}
	.isi_pesan1{
		height:39px;
		margin:10px 10px 10px 0px;
		animation: pulse 1s infinite ease-in-out;
		-webkit-animation: pulse 1s infinite ease-in-out;
	}

	@keyframes pulse
	{
		0%{
			background-color: rgba(165,165,165,.1);
		}
		50%{
			background-color: rgba(165,165,165,.3);
		}
		100%{
			background-color: rgba(165,165,165,.1);
		}
	}
	@-webkit-keyframes pulse
	{
		0%{
			background-color: rgba(165,165,165,.1);
		}
		50%{
			background-color: rgba(165,165,165,.3);
		}
		100%{
			background-color: rgba(165,165,165,.1);
		}
	}
</style>
<style>
.menu-message {
    max-height: 650px;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow-x: hidden;
}
.menu-message>li>a {
    margin: 0;
    padding: 10px 10px;
}
.menu-message>li>a {
    color: #444;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding: 10px;
}
.menu-message>li>a {
    display: block;
    white-space: nowrap;
    border-bottom: 1px solid #f4f4f4;
}
.menu-message>li>a>h4 {
    padding: 0;
    margin: 0 0 0 45px;
    color: #444;
    font-size: 15px;
    position: relative;
}
.menu-message>li>a>p {
    margin: 0 0 0 45px;
    font-size: 14px;
    color: #888;
}
.menu-message>li>a>h4>small {
    color: #999;
    font-size: 10px;
    position: absolute;
    top: 0;
    right: 0;
}
.menu-message>li>a:hover{background:#f4f4f4;text-decoration:none !important}
.percakapan{
	border-radius: 0px;
    border-color: #cecece;
    font-weight: bold;
    font-size: 18px;
    border-bottom: 2px solid #8a8a8a;
    background: #fff !important;
	padding-left: 30px;
}
.tampilkan_pesan a{
	color:green
}
.tampilkan_pesan {
  height: 500px;
  padding: 10px;
  overflow: auto;
  display: flex;
  flex-direction: column-reverse;
}
</style>
<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li><?= $title; ?></li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content" style='min-height:100%'>
            <?php include "menu-members.php"; ?>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 d-none d-sm-block">
                    <?php
                      echo "<a href='#' class='ps-btn ps-btn--outline btn-block percakapan'><i class='icon-bubble'></i> Daftar Percakapan</a>
					  <ul class='menu-message' style='min-height:333px; border:1px solid #e3e3e3; padding:10px'>";
					  foreach ($recordx->result() as $r) {
					  $mgs = $this->model_reseller->tampilmessagescontenthome($r->id_konsumen)->row();
						  if ($mgs->stat == '1'){ $bg = '#dcf9e4'; }else{ $bg = ''; }
					  if (trim($mgs->message) == '' AND $mgs->file_upload != ''){ 
						  $message = '<i class="fa fa-link fa-fw"></i> Melampirkan Sebuah File,..'; 
					  }else{ 
					  $message = substr($mgs->message,0,90); 
					  }
					  $tglex = cek_terakhir($mgs->date_time);
					  if ($r->foto==''){
						  $foto_members = 'blank.png';
					  }else{
						  if (file_exists("asset/foto_user/".$r->foto)){ $foto_members = $r->foto; }else{ $foto_members = 'blank.png'; }
					  }
					  $email_gravatar = md5(strtolower(trim($r->email))); 
					  echo "<li style='background:$bg'>
						  <a href='".base_url()."members/read/".$r->id_konsumen."/0'>
							  <div class='float-left'>
							  <img width='40px' height='40px' src='".base_url()."asset/foto_user/$foto_members' class='rounded-circle' alt='User Image'>
							  </div>
							  <h4> ".$r->nama_lengkap." <small><i class='fa fa-clock-o'></i> $tglex</small></h4>
							  <p style='overflow:hidden; text-overflow:ellipsis'>";
							  if ($mgs->file_upload != ''){ 
								  echo "<i style='color:red' class='fa fa-files-o fa-fw'></i> ";
							  }
							  echo strip_tags($message)."...</p>
						  </a>
						  </li>";
					  } 

					  if ($record->num_rows()<=0){
						  echo "<center style='margin:50px 0px'>Maaf, tidak ada Data...</center>";
					  }
					  echo "</ul>";
                    ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 col-comment">

	<?php			  
	echo "<table style='background:#fff; border-radius:6px;' class='table table-hover dont-break-out'>";
			for ($i=1; $i<=7; $i++) { 
				echo "<tr class='lazy'><td width='50px'><div class='user1'></div></td><td><div class='line1'></div><div class='isi_pesan1'></div></td></tr>";
			}
			echo "<div class='tampilkan_pesan'>
			</div>
			<tr><td colspan='2' id='bottom'></td></tr>
			<tr>
				<td colspan='2' valign=top>";
				if ($this->uri->segment(4)=='0' OR $this->uri->segment(4)==''){
					echo "<div id='form-comment'>
						<textarea class='required textarea komentarx' id='comment' placeholder='Tuliskan Pesan Disini...' onkeyup=\"auto_grow(this)\" required>".($_GET['text']!=''?($_GET['text']):'')."</textarea>
						<button class='ps-btn ps-btn--outline refresh-btn float-right' style='padding:4px 10px'><span class='fa fa-refresh'></span></button>
						<button id='sendMessage' class='btn btn-primary btn-sm spinnerButton-xs submitx' style='float:right; height:30px; margin-right:5px; padding:0px 20px'><span class='fa fa-send'></span></button>
						<div id='mulitplefileuploader' class='mt-2'>Choose files</div>
								<div id='status'></div>
					</div>";
				}else{ 
					echo "<a class='btn btn-success btn-block' href='".base_url()."members/read/".$this->uri->segment(3)."/0'><center>Buka Form Kirim Pesan</center></a>"; 
				}
				echo "</td>
				
			</tr>
		</table>";

		if ($this->uri->segment(4)=='0' OR $this->uri->segment(4)==''){
			$next = $this->uri->segment(4)+10;
			$prev = 0;
			$prev_tombol = "style='pointer-events:none; color:#a7a7a7'";
		}else{
			$next = $this->uri->segment(4)+10;
			$prev = $this->uri->segment(4)-10;
			$prev_tombol = '';
		}

		$hitung_pesan = $this->db->query("SELECT id FROM `messages` where (user1='".$this->session->id_konsumen."' AND user2='".$this->uri->segment(3)."') OR (user2='".$this->session->id_konsumen."' AND user1='".$this->uri->segment(3)."')");	
		if ($hitung_pesan->num_rows()>10){
			echo "<div class='ps-pagination'>
					<ul class='pagination messageatt'>
						<li><a href='".base_url()."members/read/".$this->uri->segment(3)."/$prev' $prev_tombol>&lt; Selanjutnya</a></li>
						<li><a href='".base_url()."members/read/".$this->uri->segment(3)."/$next' >Sebelumnya &gt;</a></li>
					</ul>
				  </div>";
		}
?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
var settings = {
    url: "<?= base_url(); ?>members/uploadm",
    formData: {
        id: "<?php echo $this->uri->segment(3); ?>"
    },
	dragDrop: false,
	maxFileCount:5,
	multiple: true,
    fileName: "uploadFile",
	maxFileSize:30000*1024,
    allowedTypes:"jpg,png,jpeg,txt,pdf,gif,zip,rar,tar",		
    returnType:"json",
    showDone:false,
    showDelete:true,
    deleteCallback: function(data,pd) {
        for(var i=0;i<data.length;i++) {
            $.post("<?= base_url(); ?>members/deleteFilem",{op:"delete",name:data[i]},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");  
            });
        }   
        pd.statusbar.hide();
    }   
}
$("#mulitplefileuploader").uploadFile(settings);
});
</script>

<script src="https://js.pusher.com/4.4/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script>
        $(document).ready(function(){
			$(".chat-text").on('click',function(){
				text = $(this).html();
				$('#comment').val('');
				$('#comment').val($('#comment').val() + text);
			});
            tampilkan_pesan();

            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = false;

            var pusher = new Pusher('f01e2f9d32cf09ad0ad2', {
                cluster: 'ap1',
                forceTLS: true
            });

            var channel = pusher.subscribe('pusher_realtime');
            channel.bind('my-event', function(data) {
                if(data.message === 'success'){
                    tampilkan_pesan();
					//var sound='<audio autoplay=true><source src="<?= base_url(); ?>asset/chat.mp3"></audio>';
					//$('body').append(sound);
                }
            });

			function auto_link(str) {
				var re = /(?![^<]*>|[^<>]*<\/)((https?:)\/\/[a-z0-9&#=.\/\-?_]+)/gi; 
				var val = re.exec(str);
				var subst = '<a target="_BLANK" href="$1">$1</a>'; 
				var result = str.replace(re, subst);
				return result;
			}

			function split_data(myStr) {
				if (myStr !== null) {
					//console.log(myStr.split(";"));
					var strArray = myStr.split(";");
					var data_file = '';
					no = 1;
					data_file += '<div style="padding:5px; margin:0px; background:#fff; border-radius:0px; border-left:3px solid" class="alert alert-info"> <i class="fa fa-link fa-fw"></i>Ada '+ strArray.length + ' Lampiran : </div>';
					for(var i = 0; i < strArray.length; i++){
						data_file += '<small style="color:#000; margin-left:20px"><b>' +no +'. <a href="<?php echo base_url(); ?>members/download/'+strArray[i]+'">'+strArray[i]+'</a> </b></small><br>';
						//('+getFileSize('<?php //echo base_url(); ?>members/download/'+strArray[i])+')
						no++;
					}
					return data_file;
				}else{
					return '';
				}
			}

            function tampilkan_pesan(){
				$('.lazy').show();
				$('.messageatt').hide();
                $.ajax({
                    url   : '<?php echo site_url("members/read_query/".$this->uri->segment(3).'/'.$this->uri->segment('4')); ?>',
                    type  : 'GET',
                    async : true,
                    dataType : 'json',
                    success : function(data){
                        var html = '';
                        var count = 1;
                        var i;
                        for(i=0; i<data.length; i++){
							if (data[i].stat=='1'){
								stat = '<i class="fa fa-send fa-fw"></i>';
							}else{
								stat = '<i class="fa fa-check fa-fw"></i>';
							}

							if (data[i].foto==''){
								foto_profile = "blank.png";
							}else{
								foto_profile = data[i].foto;
							}

							if (data[i].nama_reseller==''){
								nama_reseller = "";
							}else{
								nama_reseller = '<small>('+ data[i].nama_reseller +')</small>';
							}

                            html += '<div class="inner" style="margin-bottom:20px">'+
                                    '<td width="35px"> <a href="#"><img width="35px" src="<?php echo base_url(); ?>asset/foto_user/'+ foto_profile +'" class="img-thumbnail float-left mr-2" alt="User Image"></a></td>'+
									'<td><a style="font-weight:bold" href="#">'+ data[i].nama_lengkap +'</a> '+ nama_reseller +
									<?php if ($this->session->id_konsumen=='1'){ ?>
										'<a class="pull-right" href=""><small style="color:green">'+ timeAgo(Date.parse(data[i].date_time)) +' '+stat+' </small></a>'+
									<?php }else{ ?>
										'<span class="pull-right"><small style="color:green">'+ timeAgo(Date.parse(data[i].date_time)) +' '+stat+' </small></span>'+
									<?php } ?>
									'<br><small style="color:red"> Mengatakan :</small> <br><span class="chat-text">'+
									auto_link(nl2br(stripslashes(htmlEntities(data[i].message)))).replaceArray() +
									//split_data('gambar 1; gambar 2; gambar 3') +
									'</span><br>' + split_data(data[i].file_upload) +
									'</td>'+
                                    '</div>';
                        }
                        $('.tampilkan_pesan').html(html);
                    },
					complete: function(){
						$('.lazy').hide();
						$('.messageatt').show();
					}

                });
            } 

			<?php if (isset($_GET['text'])){ ?>
				var id = <?php echo $this->uri->segment(3); ?>;
				var comment = $('#comment').val();
                $.ajax({
                    url    : '<?php echo site_url("members/sendComment");?>',
                    method : 'POST',
                    data   : {id: id, comment: comment},
                    success: function(){
                        $('#comment').val("");
						
						$('#sendMessage').html("<span class='fa fa-send'></span>");
						$(".ajax-file-upload-statusbar").css("display","none");
						tampilkan_pesan();
                    }
                });
			<?php } ?>

            $('#sendMessage').on('click',function(){
                var id = <?php echo $this->uri->segment(3); ?>;
				var comment = $('#comment').val();
                $.ajax({
                    url    : '<?php echo site_url("members/sendComment");?>',
                    method : 'POST',
                    data   : {id: id, comment: comment},
                    success: function(){
                        $('#comment').val("");
						$("#comment").css("height","58px");
						$('#sendMessage').html("<span class='fa fa-send'></span>");
						$(".ajax-file-upload-statusbar").css("display","none");
						tampilkan_pesan();
                    }
                });
            });

			$('.refresh-btn').on('click', function() {
            $(".refresh").hide().load(" .refresh").fadeIn();
            tampilkan_pesan();
      });
        });
    </script>
