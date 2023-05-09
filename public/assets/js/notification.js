$(document).ready(function(){


    var locale = $('.noty-nav').data('local');
    let id = $('.noty-nav').data('id');

    // Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('new-notification');
    // Bind a function to a Event (the full Laravel class)

    channel.bind('notification-event', function (data) {

        console.log(data);

        if(id == data.user_id){
            var count = $('.noty-count').html();
            console.log(count);
            $('.noty-count').html(parseInt(count) + 1);

            var data =
                        `<div class="list-group-item">
                        <a class="notification notification-flush notification-unread noty"
                            href="`+data.url+`"
                            data-url="`+data.change_status+`">
                            <div class="notification-avatar">
                                <div class="avatar avatar-2xl me-3">
                                    <img class="rounded-circle"
                                        src="http://localhost:8000/assets/img/fevicon.png" alt="" />

                                </div>
                            </div>
                            <div class="notification-body">
                                <p class="mb-1">
                                    <strong>`+((locale == 'ar') ? data.title_ar : data.title_en)+`</strong>
                                    `+((locale == 'ar') ? data.body_ar : data.body_en)+`
                                </p>
                                <span class="notification-time"><span class="me-2" role="img"
                                        aria-label="Emoji">💬</span>`+data.date+`</span>

                            </div>
                        </a>

                    </div>`;

                $('.noty-list').prepend(data);
        }

    });



        $(document).on('click', '.noty', function (e) {
            e.preventDefault();
            let url = $(this).data('url');
            let link_target = $(this).attr('href');
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    window.location.href = link_target;
                }
            });//end of ajax call
        });//end of on click fav icon

});
