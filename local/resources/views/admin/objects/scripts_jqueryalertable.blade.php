<script src="{{asset('assets/admin/vendor/jquery-alertable/js/jquery.alertable.js')}}"></script>
<script>
    $(".remove.confirm").on('click',function(e){
        e.preventDefault();
        var do_link = $(this).attr('data-link');
        var do_title = $(this).attr('data-title');
        var do_token = $(this).attr('data-token');
        var do_remove = $(this).attr('data-remove');
        $.alertable.confirm(do_title).then(function() {
            $.ajax({
                method: "POST",
                url: do_link,
                data: { _method: "DELETE",_token:do_token }
            })
                    .done(function( response ) {
                        if(response == "DESTORY")
                            $("tr#"+do_remove).fadeOut('medium');
                        else if(response == "ERROR")
                            $.alertable.alert('Faild To Destory');
                    });
        }, function() {
            console.log('Cancel');
        });
    })
</script>
