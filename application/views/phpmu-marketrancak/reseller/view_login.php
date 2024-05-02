<style>
.inputx {
    width: 50px;
    height: 50px;
    background-color: lighten(#0f0f1a, 5%);
    line-height: 50px;
    text-align: center;
    font-size: 24px;
    font-family: 'Raleway', sans-serif;
    font-weight: 200;
    margin: 0 2px;
    border:1px solid #cecece;
    margin-bottom:5px
}
.ps-tab-list{
    background: #f1f1f1;
}
.ps-footer {
    padding-top: 0px;
}
.ps-footer__copyright p {
    margin: 0 auto;
}
.ps-form--account .ps-tab {
    border-radius: 10px 10px 0px 0px;
}
.ps-form--account .ps-tab-list li.active{
    color:#000;
}
</style>
<script>
$(document).ready(function(){
$('.digit-group').find('input').each(function() {
	$(this).attr('maxlength', 1);
	$(this).on('keyup', function(e) {
		var parent = $($(this).parent());
		
		if(e.keyCode === 8 || e.keyCode === 37) {
			var prev = parent.find('input#' + $(this).data('previous'));
			if(prev.length) {
				$(prev).select();
			}
		} else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
			var next = parent.find('input#' + $(this).data('next'));
			
			if(next.length) {
				$(next).select();
			} else {
				if(parent.data('autosubmit')) {
					parent.submit();
				}
			}
		}
	});
});
});
</script>

<div class="ps-my-account">
    <div class="container">
        <div class="ps-form--account ps-tab-root">
            <ul class="ps-tab-list">
            <div class="row">
                <?php if ($this->session->idx!=''){
                    $active = '';
                    $tokenx = 'active';
                ?>
                    <div class="col-md-12" style='padding:0px'>
                        <li><a href="#">Token</a></li>
                    </div>
                <?php }else{
                    $active = 'active';
                    $tokenx = '';
                ?>
                    <div class="col-6 col-md-6" style='padding:0px; border-right:1px solid #cecece'>
                        <li><a href="#sign-in">Masuk</a></li>
                    </div>
                    <div class="col-6 col-md-6" style='padding:0px'>
                        <li><a href="#register">Daftar</a></li>
                    </div>
                <?php } ?>
            </div>
            </ul>
            <div class="ps-tabs">
                <div style='margin:0px 10px'>
                <?php 
                    echo $this->session->flashdata('message'); 
                    $this->session->unset_userdata('message');
                ?>
                </div>
                <div class="ps-tab" id="sign-in">
                <form action="<?php echo base_url(); ?>auth/login" method="POST">
                    <div class="ps-form__content">
                    <div style="text-align:center;">
                        <h5>Masukkan alamat email atau no Whatsapp anda untuk mengirimkan kode OTP</h5>
                    </div>
                        <div class="form-group">
                            <input class="form-control" style="text-align:center;" name='a' type="text" placeholder='Email / No. Handphone' onkeyup="nospaces(this)" autocomplete='off' autofocus required>
                        </div>
                        <div class="form-group submit">
                            <button type='submit' name='login' class="ps-btn gray-btn ps-btn--fullwidth spinnerButton">Masuk</button>
                            <p style="text-align:center; margin-top:10px">Belum punya akun? <a style='color:#000; text-decoration:underline; font-weight:bold' href="<?php echo base_url(); ?>auth/login" class="btn-register" target="_parent">Daftar sekarang!</a></p>
                            <div class="logtext">metode lainnya</div>
                            <?php
                            $ci = &get_instance();
                            if (config('google_client_id')!=''){
                                echo "<a href='" .google_login(). "' class='ps-btn ps-btn--fullwidth red-btn custom-btn' style='margin: 4px 0px'>Google</a>";
                            }
                            if (config('facebook_app_id')!=''){
                                $ci->load->library('facebook');
                                echo "<a href='" . $ci->facebook->login_url() . "' class='ps-btn ps-btn--fullwidth blue-btn custom-btn'>Facebook</a>";
                            }
                            ?>
                        </div><br>
                    </div>
                </form>
                </div>
                <div class="ps-tab <?= $active; ?>" id="register">
                <form action="<?php echo base_url(); ?>auth/register" class='auth' method="POST">
                    <div class="ps-form__content">
                        <h5><center>Ayo Gabung dan Daftar Sekarang juga!</center></h5>
                        <div class="form-group" style='margin-bottom:10px'>
                            <input class="form-control form-auth" name='a' value='<?php if (isset($email)){ echo $email; } ?>' type="email" onkeyup="nospaces(this)" placeholder="E-mail" autocomplete='off' required>
                        </div>
                        <div class="form-group" style='margin-bottom:10px'>
                            <input class="form-control form-auth" name='b' type="number" placeholder="Nomor Telepon" onkeyup="nospaces(this)" autocomplete='off' required>
                        </div>
                        <?php
                            $row1 = $this->db->query("SELECT * FROM halamanstatis where id_halaman='1'")->row_array();
                            $row2 = $this->db->query("SELECT * FROM halamanstatis where id_halaman='2'")->row_array();
                        ?>
                        <p><input type='checkbox' checked required> Dengan mendaftar, Berarti Anda telah menyetujui <a target='_BLANK' style='color:#000' href='<?php echo base_url()."halaman/detail/$row1[judul_seo]"; ?>'>Perjanjian Pengguna</a> dan <a target='_BLANK' style='color:#000' href='<?php echo base_url()."halaman/detail/$row2[judul_seo]"; ?>'>Kebijakan Privasi</a></p>
                        <div class="form-group submit">
                            <button type='submit' name='submit2' class="ps-btn ps-btn--fullwidth gray-btn custom-btn spinnerButton">Mendaftar</button>
                            <div class='logtext'>metode lainnya</div>
                            <?php 
                                $ci = & get_instance();
                                if (config('google_client_id')!=''){
                                    echo "<a href='".google_login()."' class='ps-btn ps-btn--fullwidth red-btn custom-btn' style='margin: 4px 0px'>Google</a>";
                                }
                                if (config('facebook_app_id')!=''){
                                    $ci->load->library('facebook');
                                    echo "<a href='".$ci->facebook->login_url()."' class='ps-btn ps-btn--fullwidth blue-btn custom-btn'>Facebook</a>";
                                }

                                
                            ?>
                        </div><br>
                    </div>
                </form>
                </div>

                <div class="ps-tab" id="lupapassword">
                <form action="<?php echo base_url(); ?>auth/lupass" method="POST">
                    <div class="ps-form__content">
                        <h5>Lupa Password? Reset disini.</h5>
                        <div class="form-group">
                            <input class="form-control" name='a' type="email" placeholder="Username, Email" onkeyup="nospaces(this)" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" name='b' type="number" placeholder="No Handphone">
                        </div><br>
                        <div class="form-group submit">
                            <button type='submit' name='submit3' class="ps-btn ps-btn--fullwidth">Kirimkan Permintaan</button>
                        </div><br>
                    </div>
                </form>
                </div>

                <div class="ps-tab <?= $tokenx; ?>" id="token">
                <form action="<?php echo base_url(); ?>auth/login" method="POST" class='digit-group' data-group-name="digits" data-autosubmit="true" autocomplete="off">
                    <div class="ps-form__content">
                        <h5><center>Masukkan Token anda.<br>
                                    Belum menerima kode token? <a style='color:#000; text-decoration:underline; font-weight:bold' href='<?php echo base_url(); ?>auth/login?token'>Kirimkan Ulang</a></center></h5>
                        <div class="form-group"><center>
                            <!--substr($this->session->token,0,1);-->
                            <!-- <input class="form-control" name='token' type="number" value='' placeholder="******" onkeyup="nospaces(this)" required> -->
                            
                            <input class='inputx' type="text" id="digit-1" name="tok[]" value='<?php echo substr($this->session->token,0,1); ?>' data-next="digit-2" autofocus/>
                            <input class='inputx' type="text" id="digit-2" name="tok[]" value='<?php echo substr($this->session->token,1,1); ?>' data-next="digit-3" data-previous="digit-1" />
                            <input class='inputx' type="text" id="digit-3" name="tok[]" value='<?php echo substr($this->session->token,2,1); ?>' data-next="digit-4" data-previous="digit-2" />

                            <input class='inputx' type="text" id="digit-4" name="tok[]" value='<?php echo substr($this->session->token,3,1); ?>' data-next="digit-5" data-previous="digit-3" />
                            <input class='inputx' type="text" id="digit-5" name="tok[]" value='<?php echo substr($this->session->token,4,1); ?>' data-next="digit-6" data-previous="digit-4" />
                            <input class='inputx' type="text" id="digit-6" name="tok[]" value='<?php echo substr($this->session->token,5,1); ?>' data-previous="digit-5" />
                            
                            </center></div>
                        <div class="form-group submit">
                            <button type='submit' name='token' class="ps-btn ps-btn--fullwidth spinnerButton">Proses Token</button>
                            <!--<div class="logtext">metode login lainnya</div>-->
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
                        </div><br>
                    </div>
                </form>
                </div><br>

                <p class='text-center'>Kembali Ke <a style='color:#000' href='<?php echo base_url(); ?>'>Halaman awal</a></p>
            </div>
        </div>
    </div>
</div>

