<script>
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
</script>
<?php 
$angka_acak = substr($judul,-3);
echo "<form action='".base_url()."komplain/ulasan/".cetak($this->input->post('id'))."' method='POST'>
<div style='padding:5px; font-size:16px; font-weight:bold; background:#f4f4f4; border-bottom:1px solid #ab0534; margin-bottom:10px;'>Yuk, Berikan Ulasan Produk.</div>
<div style='height:260px; overflow-y: scroll;'>";
$no = 1;
foreach ($record as $row){
$cek = $this->db->query("SELECT * FROM rb_produk_ulasan where id_penjualan='".cetak($this->input->post('id'))."' AND id_produk='$row[id_produk]'")->row_array();
$sub_total = (($row['harga_jual']-$row['diskon'])*$row['jumlah']);
$ex = explode(';', $row['gambar']);
if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ $foto_produk = $ex[0]; }
echo "<div style='border-bottom:1px dotted'>
        <a style='font-size:17px; display:inline-block' target='_BLANK' href='".base_url()."produk/detail/$row[produk_seo]'>$row[nama_produk]</a>
      </div>
<div class='ps-product--cart'>
    <div class='ps-product__thumbnail'>
        <div style='height:60px; overflow:hidden'><a href='".base_url()."produk/detail/$row[produk_seo]'><img style='padding-right:10px' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a></div>
    </div>
    
    <div class='ps-product__content' style='text-align:left; padding-left:0px'>
    <div class='form-group__rating'>
        <div class='star-rating$no'>
            <span class='fa divya fa-star-o nomor$no' id='1' data-rating$no='1' onclick=\"rating('$no',this.id)\" style='font-size:20px; cursor:pointer'></span>
            <span class='fa fa-star-o nomor$no' id='2' data-rating$no='2' onclick=\"rating('$no',this.id)\" style='font-size:20px; cursor:pointer'></span>
            <span class='fa fa-star-o nomor$no' id='3' data-rating$no='3' onclick=\"rating('$no',this.id)\" style='font-size:20px; cursor:pointer'></span>
            <span class='fa fa-star-o nomor$no' id='4' data-rating$no='4' onclick=\"rating('$no',this.id)\" style='font-size:20px; cursor:pointer'></span>
            <span class='fa fa-star-o nomor$no' id='5' data-rating$no='5' onclick=\"rating('$no',this.id)\" style='font-size:20px; cursor:pointer'></span>
            <input type='hidden' name='rating[]' class='rating-value$no' value='".($cek['rating']!=''?$cek['rating']:'5')."'>
        </div>
    </div>
    <input type='hidden' name='id_produk[]' value='$row[id_produk]'>
    <textarea class='form-control' name='ulasan[]' rows='2' style='padding:5px' placeholder='Tuliskan Ulasan disini,..'>".($cek['ulasan']!=''?$cek['ulasan']:'')."</textarea>
    </div>
</div><br>";
    $no++;
}
echo "</div><hr><div class='float-right'>
<button type='button' style='width:130px' class='ps-btn ps-btn--outline' data-dismiss='modal'>Kembali</button>
<button type='submit' style='width:160px' name='submit' id='spinnerButton' class='ps-btn'>Kirimkan</button>
</div>
</form>";
?>

<script>
rating_show();
function rating(produk,value){
    $('.rating-value'+produk).val(value);
    rating_show();
}

function rating_show(){
    for(var i=1; i<=2; i++){
        var star_rating = $('.star-rating'+i+' .fa');
        var SetRatingStar = function(){
        return star_rating.each(function() {
            if (parseInt(star_rating.siblings('input.rating-value'+i).val()) >= parseInt($(this).data('rating'+i))) {
                return $(this).removeClass('fa fa-star-o nomor'+i).addClass('fa fa-star nomor'+i);
            }else{
                return $(this).removeClass('fa fa-star nomor'+i).addClass('fa fa-star-o nomor'+i);
            }
        });
        };
        SetRatingStar();
    }
}
</script>
                        




                        