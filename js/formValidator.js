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


