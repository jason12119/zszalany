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

/*
* jquery-match-height master by @liabru
* http://brm.io/jquery-match-height/
* License MIT
*/
!function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], t) : "undefined" != typeof module && module.exports ? module.exports = t(require("jquery")) : t(jQuery)
}(function(t) {
    var e = -1,
        o = -1,
        a = function(t) {
            return parseFloat(t) || 0
        },
        i = function(e) {
            var o = 1,
                i = t(e),
                n = null,
                r = [];
            return i.each(function() {
                var e = t(this),
                    i = e.offset().top - a(e.css("margin-top")),
                    s = r.length > 0 ? r[r.length - 1] : null;
                null === s ? r.push(e) : Math.floor(Math.abs(n - i)) <= o ? r[r.length - 1] = s.add(e) : r.push(e), n = i
            }), r
        },
        n = function(e) {
            var o = {
                byRow: !0,
                property: "height",
                target: null,
                remove: !1
            };
            return "object" == typeof e ? t.extend(o, e) : ("boolean" == typeof e ? o.byRow = e : "remove" === e && (o.remove = !0), o)
        },
        r = t.fn.matchHeight = function(e) {
            var o = n(e);
            if (o.remove) {
                var a = this;
                return this.css(o.property, ""), t.each(r._groups, function(t, e) {
                    e.elements = e.elements.not(a)
                }), this
            }
            return this.length <= 1 && !o.target ? this : (r._groups.push({
                elements: this,
                options: o
            }), r._apply(this, o), this)
        };
    r.version = "master", r._groups = [], r._throttle = 80, r._maintainScroll = !1, r._beforeUpdate = null,
        r._afterUpdate = null, r._rows = i, r._parse = a, r._parseOptions = n, r._apply = function(e, o) {
        var s = n(o),
            h = t(e),
            l = [h],
            c = t(window).scrollTop(),
            p = t("html").outerHeight(!0),
            d = h.parents().filter(":hidden");
        return d.each(function() {
            var e = t(this);
            e.data("style-cache", e.attr("style"))
        }), d.css("display", "block"), s.byRow && !s.target && (h.each(function() {
            var e = t(this),
                o = e.css("display");
            "inline-block" !== o && "flex" !== o && "inline-flex" !== o && (o = "block"), e.data("style-cache", e.attr("style")), e.css({
                display: o,
                "padding-top": "0",
                "padding-bottom": "0",
                "margin-top": "0",
                "margin-bottom": "0",
                "border-top-width": "0",
                "border-bottom-width": "0",
                height: "100px",
                overflow: "hidden"
            })
        }), l = i(h), h.each(function() {
            var e = t(this);
            e.attr("style", e.data("style-cache") || "")
        })), t.each(l, function(e, o) {
            var i = t(o),
                n = 0;
            if (s.target)
                n = s.target.outerHeight(!1);
            else {
                if (s.byRow && i.length <= 1)
                    return void i.css(s.property, "");
                i.each(function() {
                    var e = t(this),
                        o = e.attr("style"),
                        a = e.css("display");
                    "inline-block" !== a && "flex" !== a && "inline-flex" !== a && (a = "block");
                    var i = {
                        display: a
                    };
                    i[s.property] = "", e.css(i), e.outerHeight(!1) > n && (n = e.outerHeight(!1)), o ? e.attr("style", o) : e.css("display", "")
                })
            }
            i.each(function() {
                var e = t(this),
                    o = 0;
                s.target && e.is(s.target) || ("border-box" !== e.css("box-sizing") && (o += a(e.css("border-top-width")) + a(e.css("border-bottom-width")), o += a(e.css("padding-top")) + a(e.css("padding-bottom"))), e.css(s.property, n - o + "px"))
            })
        }), d.each(function() {
            var e = t(this);
            e.attr("style", e.data("style-cache") || null)
        }), r._maintainScroll && t(window).scrollTop(c / p * t("html").outerHeight(!0)),
            this
    }, r._applyDataApi = function() {
        var e = {};
        t("[data-match-height], [data-mh]").each(function() {
            var o = t(this),
                a = o.attr("data-mh") || o.attr("data-match-height");
            a in e ? e[a] = e[a].add(o) : e[a] = o
        }), t.each(e, function() {
            this.matchHeight(!0)
        })
    };
    var s = function(e) {
        r._beforeUpdate && r._beforeUpdate(e, r._groups), t.each(r._groups, function() {
            r._apply(this.elements, this.options)
        }), r._afterUpdate && r._afterUpdate(e, r._groups)
    };
    r._update = function(a, i) {
        if (i && "resize" === i.type) {
            var n = t(window).width();
            if (n === e)
                return;
            e = n;
        }
        a ? -1 === o && (o = setTimeout(function() {
            s(i), o = -1
        }, r._throttle)) : s(i)
    }, t(r._applyDataApi), t(window).bind("load", function(t) {
        r._update(!1, t)
    }), t(window).bind("resize orientationchange", function(t) {
        r._update(!0, t)
    })
});


$(function () {
    $(document).on('keypress','.input-ux .validation',function () {
        if ($(this).hasClass('input-wrong')) {
            $(this).removeClass('input-wrong').parent().find('.fa').hide();
            $(this).removeClass('input-wrong').parent().find('.help').hide();
        }
    });
});

// Validace emailu
function validateEmail(email) {
    let regexEmail = /^([a-z0-9_\-\.])+\@([a-z0-9_\-\.])+\.([a-z]{2,4})$/i;
    if (regexEmail.test(email)) {
        return true;
    }
}

// Validace PSČ
function validatePostCode(postcode) {
    let regexPostCodeNoSpace = /[0-9]{5}/;
    if (regexPostCodeNoSpace.test(postcode) && postcode.length===5) {
        return true
    }

}

// Validace telefonu
function validatePhone(phone) {
    let regexPhone;
    let plusReg = /^\+/;

    if (plusReg.test(phone)) {

        regexPhone = /^\+[0-9]{12}$/;
    }
    else {
        regexPhone = /^[0-9]{9}$/;
    }

    if (regexPhone.test(phone)) {
        return true
    }

}

// Označí chybně vyplněný input
function highlightErrors(selector) {
    selector.addClass('input-wrong');
    if (selector.parent().find('.fa-check').is(':visible')) {
        selector.parent().find('.fa-check').hide();
    }
    selector.parent().find('.fa-times').show();
    selector.parent().find('.help').show();
}

// Označí správně vyplněný input
function setInputOk(selector) {
    if (selector.parent().find('.fa-times').is(':visible')) {
        selector.parent().find('.fa-times').hide();
        selector.parent().find('.help').hide();
        selector.removeClass('input-wrong')
    }
    selector.parent().find('.fa-check').show();
}

// Validuje všechna povinná pole při odeslání
function validateFormData(e,scroll, modal) {
    let inputErrors = 0;

    let selector = '';
    if(modal===true){
        selector = '#modal ';
    }
    $(selector+'.validation').each(function () {
        let input = $(this);
        let value = input.val();
        let inputType = $(this).data('type');

        // Pokud je pole text
        if (inputType === 'text') {

            if (value !== '') {
                setInputOk(input);
            }
            else {
                highlightErrors(input);
                inputErrors++;
            }
        }

        // Pokud je pole email
        if (inputType === 'email') {

            if (validateEmail(value)) {
                setInputOk(input);
            }
            else {
                highlightErrors(input);
                inputErrors++;
            }
        }

        // Pokud je pole postcode
        if (inputType === 'postcode') {

            if (validatePostCode(value)) {
                setInputOk(input);
            }
            else {
                highlightErrors(input);
                inputErrors++;
            }
        }

        // Pokud je pole tel
        if (inputType === 'tel') {

            if (validatePhone(value)) {
                setInputOk(input);
            }
            else {
                highlightErrors(input);
                inputErrors++;
            }
        }

    });
    if (inputErrors) {

        if(scroll===true) {
            let firstError = $(document).find('.input-wrong').first();
            $('html, body').animate({
                scrollTop: $(firstError).offset().top - 50
            }, 300);
        }
        return false;

    }
    else {
        return true;
    }

}


