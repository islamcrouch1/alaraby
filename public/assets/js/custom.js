$(document).ready(function(){



    $('.colorpicker').colorpicker();

    $('.btn').on('click' , function(e){

        e.preventDefault

        var check = 0;

        $(this).closest('form').find('input').each(function() {
            if ($(this).prop('required') && $(this).val() == '' ) {
                check++;
            }
        });

          if(check == 0){
            $(this).closest('form').submit(function () {
                $('.btn').attr("disabled", true);
            });
        }


    });


    $('.sonoo-search').change(function(){
        $(this).closest('form').submit();
    });

    $('.sonoo-form').change(function(){
        $(this).closest('form').submit();
    });


    $('#copy').click(function(){
        copyLink();
    });


    function copyLink() {

        /* Get the text field */
        var copyText = document.getElementById("page-link");
        var locale = document.getElementById("locale");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.value);

    }






    $(".img").change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.img-prev').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]); // convert to base64 string
        }
    });



    $(".gummla-search").change(function () {
        $(this).closest("form").submit();
    });


    $(".import-tasks").change(function () {
        $(".form_class").submit();
    });


    $(".imgs").change(function() {
        if (this.files) {
            var filesAmount = this.files.length;
            $('#gallery').empty();
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var image = `
                                <img src="` + event.target.result + `" style="width:100px"  class="img-thumbnail img-prev">
                           `;
                    $('#gallery').append(image);
                }
                reader.readAsDataURL(this.files[i]);
            }
        }
    });



    $("#show-pass").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password svg').addClass( "fa-eye-slash" );
            $('#show_hide_password svg').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password svg').removeClass( "fa-eye-slash" );
            $('#show_hide_password svg').addClass( "fa-eye" );
        }
    });


    var startMinute = 1;
    var time = startMinute * 60;

    setInterval(updateCountdown , 1000);

    function updateCountdown(){
        var minutes = Math.floor(time / 60);
        var seconds = time % 60 ;
        seconds < startMinute ? '0' + seconds : seconds;
        $('.counter_down').html(minutes + ':' + seconds)
        if(minutes == 0 && seconds == 0){
            $('.resend').css('pointer-events' , 'auto');
        }else{
            time--;
        }
    }





    $('.add-fav').on('click' , function(e){

        e.preventDefault();
        var url = $(this).data('url');
        var fav = '.fav-' + $(this).data('id');

        $.ajax({
            url: url,
            method: 'GET',
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                if(data == 1){
                    $(fav).removeClass('far');
                    $(fav).addClass('fas');
                    $(".fav-icon").text(parseInt($(".fav-icon").text()) + 1);
                }
                if(data == 2){
                    $(fav).addClass('far');
                    $(fav).removeClass('fas');
                    $(".fav-icon").text(parseInt($(".fav-icon").text()) - 1);
                }
            }
        });
    });


    $('.cart-quantity').on('click' , function(e){

        e.preventDefault();

        var stock_id = $(this).data('stock_id');
        var input = '.quantity-' + stock_id ;

        var url = $(input).data('url');
        var quantity = $(input).val();

        var formData = new FormData();
        formData.append('quantity' , quantity);

        $.ajax({
            url: url,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                if(data == 1){

                }
                if(data == 2){

                }
            }
        });
    });

});



function downloadAll() {
    var div = document.getElementById("allImages");
    console.log(div);
    var images = div.getElementsByTagName("img");
    console.log(images)

    for (i = 0; i < images.length; i++) {
        console.log(images[i]);
        downloadWithName(images[i].src, images[i].src);
    }
}

function downloadWithName(uri, name) {
    function eventFire(el, etype) {
        if (el.fireEvent) {
            (el.fireEvent('on' + etype));
        } else {
            var evObj = document.createEvent('MouseEvent');
            evObj.initMouseEvent(etype, true, false,
                window, 0, 0, 0, 0, 0,
                false, false, false, false,
                0, null);
            el.dispatchEvent(evObj);
        }
    }

    var link = document.createElement("a");
    link.download = name;
    link.href = uri;
    eventFire(link, "click");
}
