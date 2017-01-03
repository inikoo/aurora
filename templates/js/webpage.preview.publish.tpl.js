 function publish(element){


        var icon=$(element).find('i')

        if(icon.hasClass('fa-spin')) return;


        icon.removeClass('fa-rocket').addClass('fa-spinner fa-spin')

        var request = '/ar_edit_website.php?tipo=publish_webpage&parent_key=' + $('#webpage_preview').attr('webpage_key')
        console.log(request)
        $.getJSON(request, function (data) {

            icon.addClass('fa-rocket').removeClass('fa-spinner fa-spin')
            $(element).removeClass('changed valid')

        })


    }