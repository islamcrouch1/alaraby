$(document).ready(function(){


    // function to calculate affiliate commission when change price
    $('input[type=number].product-price').change(function(){

        var min = $(this).data('min');
        var max = $(this).data('max');
        var price = $(this).val() ;
        var product_id = $(this).data('product_id');
        var span = '#aff_comm' + product_id;
        var alarm = '.alarm-' + product_id ;
        var alarm_text = '.alarm-text-' + product_id ;
        var locale = $(this).data('locale');

        if(price < min){

            $(this).val(min);
            $(span).html(0);
            if(locale == 'ar'){
                $(alarm_text).html("يجب أن يكون سعر البيع من " + min + " إلى " +  max)
            }else{
                $(alarm_text).html("Selling price must be between " + min + " to " +  max)
            }
            $(alarm).css('display', 'flex');

        }else if(price > max){

            $(alarm).css('display', 'none');
            $(this).val(max);
            $(span).html(max - min);
            if(locale == 'ar'){
                $(alarm_text).html("يجب أن يكون سعر البيع من " + min + " إلى " +  max)
            }else{
                $(alarm_text).html("Selling price must be between " + min + " to " +  max)
            }
            $(alarm).css('display', 'flex');

        }else{

            $(alarm).css('display', 'none');
            $(span).html(price - min);
        }
    });

    // function to calculate affiliate commission when change price in add to my store
    $('input[type=number].product-price-store').change(function(){

        var min = $(this).data('min');
        var max = $(this).data('max');
        var price = $(this).val() ;
        var product_id = $(this).data('product_id');
        var span = '#aff_comm_store' + product_id;
        var alarm = '.alarm-' + product_id ;
        var alarm_text = '.alarm-text-' + product_id ;
        var locale = $(this).data('locale');

        if(price < min){

            $(this).val(min);
            $(span).html(0);
            if(locale == 'ar'){
                $(alarm_text).html("يجب أن يكون سعر البيع من " + min + " إلى " +  max)
            }else{
                $(alarm_text).html("Selling price must be between " + min + " to " +  max)
            }
            $(alarm).css('display', 'flex');

        }else if(price > max){

            $(alarm).css('display', 'none');
            $(this).val(max);
            $(span).html(max - min);
            if(locale == 'ar'){
                $(alarm_text).html("يجب أن يكون سعر البيع من " + min + " إلى " +  max)
            }else{
                $(alarm_text).html("Selling price must be between " + min + " to " +  max)
            }
            $(alarm).css('display', 'flex');

        }else{

            $(alarm).css('display', 'none');
            $(span).html(price - min);
        }
    });

    // display related sizes for each color
    $('input[type=radio].color-select').change(function(){
        // get data from color input
        var product_id = $(this).data('product_id');
        var color_id = $(this).data('color_id');
        var stock_id = $(this).data('stock_id');

        // select sizes input to show
        var selected_label = '.selected-labels-' + product_id + '-' + color_id;
        var all_label = '.all-labels-' + product_id;
        $(all_label).hide();
        $(selected_label).show();

        // get slide index number
        var slide = '#slide-' + stock_id;
        slide = $(slide).data('swiper-slide-index');
        // slide to image related to the selected color
        const swiper = document.querySelector('.swiper-container').swiper;
        swiper.slideTo(slide, 300, false);
    });

    // display quantity for size
    $('input[type=radio].stock-select').change(function(){

        var product_id = $(this).data('product_id');
        var locale = $(this).data('locale');
        var limit = $(this).data('limit');
        var stock_id = $(this).data('stock_id');

        // get quantity and display it in span
        var quantity = $(this).data('quantity');
        var av  = '.av-qu-' + product_id + '-' + stock_id;
        if(limit == 'unlimited'){
            if(locale == 'ar'){
                $(av).html('غير محدودة');
            }else{
                $(av).html('Unlimited');
            }
        }else{
            $(av).html(quantity);
        }

        // reset quantity input on change event
        var input = '.quantity-' + product_id;
        $(input).val(1);
        $(input).prop('max', quantity);

    });

    // not used till now --- function for send verification sms in withdrawal page
    $('#send-conf').on('click' , function(e){

        e.preventDefault();
        var loader = '.spinner';
        var url = $(this).data('url');

        $(loader).show();
        $('.fa-plus').hide()
        $("#send-conf").attr("disabled", true);

        var startMinute = 1;
        var time = startMinute * 60;

        var intervalId = setInterval(updateCountdown , 1000);

        function updateCountdown(){
            var minutes = Math.floor(time / 60);
            var seconds = time % 60 ;
            seconds < startMinute ? '0' + seconds : seconds;
            $('.counter_down1').html(minutes + ':' + seconds)
            if(minutes == 0 && seconds == 0){
                $("#send-conf").attr("disabled", false);
                $('.counter_down1').html('');
                clearInterval(intervalId);
            }else{
                time--;
            }
        }

        $.ajax({
            url: url,
            method: 'GET',
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                if(data == 1){
                    $(loader).hide();
                    $('.fa-plus').show()
                }
            }
        });
    });

    // add to cart function
    $('.add-cart').on('click' , function(e){
      e.preventDefault();

      var url = $(this).data('url');
      var loader = '.spinner';
      var locale = $(this).data('locale');
      var product_id = $(this).data('product_id');
      var product_price = $(this).data('product_price');
      var product_type = $(this).data('product_type');

      var selected_stock = 'stock-select';
      var stock =  $("input[type='radio'][name=" + selected_stock + "]:checked").val();
      var stock_id = $("input[type='radio'][name=" + selected_stock + "]:checked").data('stock_id');

      var alarm = '.alarm-' + product_id ;
      var alarm_text = '.alarm-text-' + product_id ;
      var alarm_success = '.alarm-success-' + product_id ;
      var alarm_success_text = '.alarm-success-text-' + product_id ;

      var quantity_input = '.quantity-' + product_id ;
      var max_quantity = parseInt($("input[type='radio'][name=" + selected_stock + "]:checked").data('quantity'));
      var quantity = parseInt($(quantity_input).val());

     var price = '.product-price';
     var max_price = parseInt($(price).attr('max'));
     var min_price = parseInt($(price).attr('min'));
     var price_value = parseInt($(price).val());

     $(alarm_success).css('display', 'none');

     if(!stock){

        if(locale == 'ar'){
            $(alarm_text).html("يرجى تحديد المقاس واللون")
        }else{
            $(alarm_text).html("Please Select Size And Color")
        }

        $(alarm).css('display', 'flex');

     }else{

        $(alarm).css('display', 'none');

        if(quantity <= 0) {

            if(locale == 'ar'){
                $(alarm_text).html("يرجى ادخال كمية أكبر من الصفر")
            }else{
                $(alarm_text).html("Please enter an amount greater than zero")
            }

            $(alarm).css('display', 'flex');

        }else{

            $(alarm).css('display', 'none');

            if(quantity > max_quantity){

                if(locale == 'ar'){
                    $(alarm_text).html("لا يمكنك طلب كمية أكبر من " + max_quantity + " قطعة من هذا المنتج")
                }else{
                    $(alarm_text).html("You cannot order a quantity greater than " + max_quantity + " from this product")
                }

                $(alarm).css('display', 'flex');

            }else{

                $(alarm).css('display', 'none');

                if (price_value < min_price || price_value > max_price) {

                    if(locale == 'ar'){
                        $(alarm_text).html("يجب أن يكون سعر البيع من " + min_price + " إلى " +  max_price)
                    }else{
                        $(alarm_text).html("Selling price must be between " + min_price + " to " +  max_price)
                    }

                    $(alarm).css('display', 'flex');

                }else{

                    $('.add-cart').prop( "disabled", true );
                    $(alarm).css('display', 'none');
                    $(loader).show();
                    $('.cart-icon').hide()
                    var formData = new FormData();
                    formData.append('product_id' , product_id);
                    formData.append('stock_id' , stock_id);
                    formData.append('quantity' , quantity );
                    formData.append('selected_price' , price_value );
                    formData.append('product_price' , product_price );
                    formData.append('product_type' , product_type );

                    $.ajax({
                        url: url,
                        data: formData,
                        method: 'POST',
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function(data) {

                            $('.add-cart').prop( "disabled", false );

                            console.log(data);

                            if(data == 1){

                            $(loader).hide();
                            $('.cart-icon').show()

                            if(locale == 'ar'){
                                $(alarm_success_text).html("تم إضافة المنتج في السلة بنجاح")
                            }else{
                                $(alarm_success_text).html("Product added to the cart successfully")
                            }

                            $(alarm_success).css('display', 'flex');

                            $(".cart-count").text(parseInt($(".cart-count").text()) + 1);

                            }else if(data == 0){

                                $(loader).hide();
                                $('.cart-icon').show()

                                // if(locale == 'ar'){
                                //     $(alarm_text).html("المقاس واللون المحدد موجود بالفعل في سلة المشتريات")
                                // }else{
                                //     $(alarm_text).html("The specified size and color is already in the cart")
                                // }

                                if(locale == 'ar'){
                                    $(alarm_success_text).html("تم إضافة المنتج في السلة بنجاح")
                                }else{
                                    $(alarm_success_text).html("Product added to the cart successfully")
                                }

                                $(alarm_success).css('display', 'flex');

                            }else if(data == 2){

                                $(loader).hide();
                                $('.cart-icon').show()

                                if(locale == 'ar'){
                                    $(alarm_text).html("الكمية المطلوبة غير متاحه حاليا")
                                }else{
                                    $(alarm_text).html("The requested quantity not available")
                                }

                                $(alarm).css('display', 'flex');

                            }else if(data == 3){

                                $(loader).hide();
                                $('.cart-icon').show()

                                if(locale == 'ar'){
                                    $(alarm_text).html("السعر المطلوب غير مناسب يجب ان يتراوح السعر المدخل بين الحد الادنى والحد الاقصى للسعر ")
                                }else{
                                    $(alarm_text).html("The requested price is not suitable. The entered price must be between a minimum and a maximum price")
                                }

                                $(alarm).css('display', 'flex');

                            }else if(data == 4){

                                $(loader).hide();
                                $('.cart-icon').show()

                                if(locale == 'ar'){
                                    $(alarm_text).html("تم تحديث سعر هذا المنتج .. يرجى إعادة تحميل الصفحة لمعرفة السعر الحالى للمنتج ")
                                }else{
                                    $(alarm_text).html("The price of this product has been updated.. Please reload the page to see the current price of the product")
                                }

                                $(alarm).css('display', 'flex');
                            }
                        }
                    })
                }
            }
        }
     }
    });

    $('body').on('keyup change', '.product-quantity', function() {

        var quantity = Number($(this).val()); //2

        var stock = $(this).data('stock');

        if ( quantity > stock){

            $('#exampleModalCenter1').modal({
                keyboard: false
            });

            $('.available-quantity').empty();
            $('.available-quantity').html(stock);

            $(this).val(stock);

        }else {

            var unitPrice = parseFloat($(this).data('price')); //150
            $(this).closest('tr').find('.product-price').html((quantity * unitPrice).toFixed(2));
            calculateTotal();
        }



    });//end of product quantity change

    $('body').on('keyup change', '.product-quantity-stock', function() {

        var quantity = Number($(this).val()); //2

        var stock = $(this).data('stock');

        var price = $(this).data('price');

        var quant = document.getElementsByName('quantity[]');

        var all_quan = 0;

        for (var i = 0; i < quant.length; i++) {
            all_quan += parseInt(quant[i].value);
        }

        console.log(all_quan)

        var total = '#total_order';

        if ( quantity > stock){

            $(this).val(stock);

        }else if (quantity < 0 ) {

            $(this).val(0);


        }else{
            $(total).html(all_quan * price);
        }

    });//end of product quantity change


    $('.order-products').on('click', function(e) {

        e.preventDefault();



        var url = $(this).data('url');

        var method = $(this).data('method');

        var loader = $(this).data('loader');

        console.log(loader);

        loader = '#' + loader;

        console.log(loader);


        $(loader).css('display', 'flex');



        $.ajax({
            url: url,
            method: method,
            success: function(data) {


                $('#exampleModalCenter2').modal({
                    keyboard: false
                });

                $(loader).css('display', 'none');
                $('#order-product-list').empty();
                $('#order-product-list').append(data);

            }
        })

    });//end of order products click

    $(".img").change(function() {

        if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
        $('.img-prev').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]); // convert to base64 string
        }

    });

    $('body').on('keyup change', '.used_balance1', function() {


        var used_balance = Number($(this).val());

        var wallet_balance = $(this).data('wallet_balance');

        if(used_balance > wallet_balance){

            $('#balance_alert').modal({
                keyboard: false
            });

            $('.available-quantity').empty();
            $('.available-quantity').html(wallet_balance);

            $(this).val(wallet_balance);


        }

        if(used_balance < 0 ){

            $(this).val(0);
        }


        calculateTotal();





    });//end of product quantity change


    $('#rate').on('click' , function(e){
        var rating = $(this).data('rating');
        $('#rating').val(rating)
    });

});
