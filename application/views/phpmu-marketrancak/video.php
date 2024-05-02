<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
* {
	box-sizing: border-box;
}
html {
	font-family: sans-serif;
	scroll-snap-type: mandatory;
	scroll-snap-points-y: repeat(100vh);
	scroll-snap-type: y mandatory;
}
section {
	border-bottom: 1px solid white;
	padding: 0px;
	height: 100vh;
	scroll-snap-align: start;
	text-align: center;
	position: relative;
}
h1	{
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	text-align: center;
	color: #000;
	width: 100%;
	left: 0;
	font-size: calc(1rem + 3vw);
}
video{
  width: 100%;
  height: 100%;
  margin-top:-30px;
  background: #313131;
}
.komentar{
    position:absolute;
    top:45%;
    right:20px;
    color:
}
.komentar a{
    color:#fff;
    font-size:30px;
    display:block;
    margin-bottom:10px
}
.keterangan{
    bottom: 140px;
    padding:0px 20px;
    position: absolute;
    color:#fff;
    text-align:left;
}
.sub-keterangan{
    display:block;
    padding:3px;
    background:#fff;
    text-align:left;
    background: rgba(0,0,0,0.3);
    border:1px solid #2c2c2c;
    margin-bottom:15px;
}
.sub-keterangan a{
    color:orange;
    text-align:left;
}
.keterangan span{
    color:#fff;
}
.muted{
    position: absolute;
    z-index: 1;
    top: 20px;
    right: 10px;
}
.audio-control {
  cursor:pointer;
  padding: 0px 15px 0px 10px;
  width:50px;
  background: rgba(0,0,0,0.5);
  color: #fff;
  border-radius: 4px;
  font-size:30px;
  display: inline-block;
}

.modal-bottom {
  position:fixed;
  top:auto;
  right:auto;
  left:auto;
  bottom:0;
  
}

</style>

</head>
<body>
    <?php 
        $no = 1;
        foreach($records->result_array() as $row){
            echo "<section>
                <video playsinline autoplay muted class='myVideo$no' loop src='".base_url()."asset/img_video/$row[video]' controls type='video/mp4'></video>
                <div class='komentar'>
                    <a href='#' data-toggle='modal' data-target='.bs-example-modal-lg'><span class='fa fa-comments'></span></a>
                    <a href=''><span data-id='".base_url()."produk/video?watch=$row[id_produk_video]' class='fa fa-share share_button'></span></a>
                </div>
                <div class='keterangan'>
                    <div class='sub-keterangan'><a href='".base_url()."produk/detail/$row[produk_seo]?v=true'>$row[judul_video]</a></div>
                    $row[keterangan_video]
                </div>
            </section>";
            $no++;
        }
    ?>
    
    
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg modal-bottom">  
    <div class="modal-content" style='height:300px; padding: 0px 15px;'>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"></h4>
        </div>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ex ligula, egestas ac purus in, mattis maximus tellus. </p>
        <input class='form-control' style='background:#f4f4f4' placeholder='Tambahkan Komentar' type='text'>
    </div>
  </div>
</div>    
    
    
<script> 
async function AndroidNativeShare(Title,URL,Description){
  if(typeof navigator.share==='undefined' || !navigator.share){
    alert('Your browser does not support Android Native Share, it\'s tested on chrome 63+');
  } else if(window.location.protocol!='https:'){
    alert('Android Native Share support only on Https:// protocol');
  } else {
    if(typeof URL==='undefined'){
      URL = window.location.href;
    }
    if(typeof Title==='undefined'){
      Title = document.title;
    }
    if(typeof Description==='undefined'){
      Description = 'Share your thoughts about '+Title;
    } 
    const TitleConst = Title;
    const URLConst = URL;
    const DescriptionConst = Description;
     
    try{
      await navigator.share({title:TitleConst, text:DescriptionConst, url:URLConst});
    } catch (error) {
    console.log('Error sharing: ' + error);
    return;
    }   
  }
} 
 
$(".share_button").click(function(BodyEvent){
  var meta_desc,meta_title,meta_url
  if(document.querySelector('meta[property="og:description"]')!=null) {
    meta_desc = document.querySelector('meta[property="og:description"]').content;
  }
  if(document.querySelector('meta[property="og:title"]')!=null) {
    meta_title = document.querySelector('meta[property="og:title"]').content;
  }
  if($(this).data("id")!=null) {
    meta_url = $(this).data("id");
  }
  
  //alert($(this).data("id"));
  AndroidNativeShare(meta_title, meta_url,meta_desc); 
});




    function playPauseVideo() {
    let videos = document.querySelectorAll("video");
    i = 1;
    videos.forEach((video) => {
        // We can only control playback without insteraction if video is mute
        video.muted = true;
        
        // Play is a promise so we need to check we have it
        let playPromise = video.play();
        
        console.log(i);
        if (playPromise !== undefined) {
            playPromise.then((_) => {
                let observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach((entry) => {
                            if (entry.intersectionRatio !== 1) {
                                video.pause();
                                video.muted = false;
                            }else if (video.paused) {
                                video.play();
                                video.muted = false;
                            }
                        });
                    },
                    { threshold: 0.2 }
                );
                observer.observe(video);
            });
        }
        i++;
    });
    
}
// And you would kick this off where appropriate with:
playPauseVideo();
</script>    
</body>
</html>