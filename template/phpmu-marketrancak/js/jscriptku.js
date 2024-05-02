const flashData = $('.flash-data').data('flashdata');
const dataJudul = $('.flash-judul').data('judul');
if (flashData){
    swal(
        dataJudul,
        flashData,
        'success'
    )
}

$('.btn-hapus').on('click', function (e){
    e.preventDefault();
    const href = $(this).attr('href');
    const dataJudul = $('.flash-judul').data('judul');
    swal({
        title: 'Anda yakin?',
        text: "Untuk menghapus "+dataJudul+ " ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin Banget!'
    },
    function(){
        document.location.href = href;
    });
});

$('.btn-batal').on('click', function (e){
    e.preventDefault();
    const href = $(this).attr('href');
    const dataJudul = $('.flashjudul').data('judul');
    swal({
        title: 'Anda yakin?',
        text: ""+dataJudul+ "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin Banget!'
    },
    function(){
        document.location.href = href;
    });
});

$('.btn-konfirmasi').on('click', function (e){
    e.preventDefault();
    const href = $(this).attr('href');
    const dataJudul = $('.konfirmasi').data('judul');
    swal({
        title: 'Konfirmasi',
        text: ""+dataJudul+ "",
        icon: 'warning',
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yakin Banget!'
    },
    function(){
        document.location.href = href;
    });
});

$('.btn-belanja').on('click', function (e){
    e.preventDefault();
    const href = $(this).attr('href');
    const dataJudul = $('.flashjudul').data('judul');
    swal({
        title: 'Dapatkan Diskon!',
        text: ""+dataJudul+ "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#db0202 !important',
        cancelButtonColor: '#db0b0b',
        cancelButtonText: 'Upgrade Akun',
        confirmButtonText: 'Lanjut Tanpa Diskon!'
    },
    function(inputValue){
        if (inputValue===true) {
            document.location.href = href;
        }else{
            window.location.href = "http://localhost/members/members/welcome";
        }
    });
});