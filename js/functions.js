$(function () {


    // Cookie bar
    $('#agree').click(function () {
        var expires;
        var date = new Date();
        date.setMonth(date.getMonth() + 1);
        expires = "; expires=" + date.toUTCString();
        document.cookie = 'cookies-allowed' + "=" + true + expires + "; path=/";
        $('#cookie_bar').remove()
    });

    // Scroll to element
    $(document).on('click', '.scrollTo', function () {
        var target = $(this).data('target');
        $('html, body').animate({
            scrollTop: $(target).offset().top - $('#header').outerHeight()
        }, 800);

    });

    // Toggle menu
    $('#toggle-menu').click(function () {
        $('#navibutton').toggleClass('show');
        $('#nav').css({top: $('#header').height()}).slideToggle();
    });

    // Scroll top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#top').fadeIn();
            $('body').addClass('scroll')
        } else {
            $('#top').fadeOut();
            $('body').removeClass('scroll')
        }
    });

    $('#top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });

    // Send contact form
    $('#send-form').click(function () {
        if (validateFormData(event, false, false)) {
            var data = $('#form').serialize();

            $.ajax({
                type: 'POST',
                url: 'ajax/ajax.php',
                data: data,
                beforeSend: function () {
                    preloader('white', 'show', 200)
                },
                success: function (data) {
                    alert('Děkujeme, Váš dotaz byl odeslán!');
                },
                complete: function () {
                    preloader('white', 'hide', 200);
                    $('.custom-input, textarea').val('');
                    $('.input-ux .fa').hide();
                }
            })
        }

    })
});

function preloader(type, action, speed) {

    let overlay = 'overlay';
    if (type === 'white') {
        overlay = 'overlay-white';
    }

    if (action === 'show') {
        $('#' + overlay + ',#spinner').fadeIn(speed);
    }
    if (action === 'hide') {
        $('#' + overlay + ',#spinner').fadeOut(speed);
    }
}
