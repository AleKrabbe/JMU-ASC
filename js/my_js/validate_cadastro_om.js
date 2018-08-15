$(document).ready(function () {
    //----------------------------------------
    // Valida dados.

    $('.nome').focusout(function () {
        $(this).val($(this).val().trim());
        if($(this).val().length > 100 || $(this).val().length === 0){
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        }
    }).one("focusout", function () {
        if ($(this).val().length === 0) {
            toastr.error('O preenchimento do nome é obrigatório.', 'Erro!');
        }
    });

    $('.sigla').focusout(function () {
        $(this).val($(this).val().trim());
        if($(this).val().length > 30 || $(this).val().length === 0){
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        }
    }).one("focusout", function () {
        if ($(this).val().length === 0) {
            toastr.error('O preenchimento da sigla é obrigatório.', 'Erro!');
        }
    });

    $('.nome-comandante, .cidade').focusout(function () {
        $(this).val($(this).val().trim());
        if($(this).val().length > 45){
            $(this).focus().parent().addClass('has-error');
        } else if ($(this).val().length > 0){
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().removeClass('has-success');
        }
    });

    $('.estado').focusout(function () {
        $(this).val($(this).val().trim());
        if($(this).val().length > 20){
            $(this).focus().parent().addClass('has-error');
        } else if ($(this).val().length > 0){
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().removeClass('has-success');
        }
    });

    $('.telefone, .fax').mask('(00) 0000-0000', {placeholder: "(__) ____-____"}).focusout(function () {

        if($(this).val().length < 14 && $(this).val().length > 0){
            $(this).focus().parent().addClass('has-error');
            toastr.error('O ' + $(this).attr('name') + ' deve conter 10 números.', 'Erro!');
        } else if ($(this).val().length > 0){
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        } else {
            $(this).parent().removeClass('has-error');
        }
    });

    $('.email').focusout(function () {
        if ($(this).val() && !validateEmail($(this).val())) {
            $(this).parent().addClass('has-error');
            toastr.error('Email inválido.', 'Erro!');
        } else if ($(this).val().length > 0){
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().removeClass('has-success');
        }
        $(this).val($.trim($(this).val()));
    });

    $('#om-dropdown, #estado-dropdown, #cidade-ajaxop').change(function () {
        if($(this).val() === "null"){
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        }
    }).focusout(function () {
        if($(this).val() === "null"){
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        }
    });

    $('#submit-btn').click(function (e) {
        if ($('.nome').val() === '') {
            toastr.error('Digite o nome da organização militar.', 'Erro!');
            $('.nome').focus().parent().addClass('has-error');
            return false;
        }

        if ($('.sigla').val() === '') {
            toastr.error('Digite a sigla da organização militar.', 'Erro!');
            $('.sigla').focus().parent().addClass('has-error');
            return false;
        }

        if ($('#om-dropdown').val() === 'null') {
            toastr.error('Selecione um vínculo para a organização militar.', 'Erro!');
            $('#om-dropdown').focus().parent().addClass('has-error');
            return false;
        }

        if ($('#estado-dropdown').val() === 'null') {
            toastr.error('Selecione um estado.', 'Erro!');
            $('#estado-dropdown').focus().parent().addClass('has-error');
            return false;
        }

        if ($('#cidade-ajaxop').val() === 'null') {
            toastr.error('Selecione uma cidade.', 'Erro!');
            $('#cidade-ajaxop').focus().parent().addClass('has-error');
            return false;
        }

        $('#cadastro-om-form').submit();
    });

    // -------------------------------------
    // Verifica campos em branco.

    $('.shouldValidate').on('change invalid', function() {
        var textfield = $(this).get(0);

        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            textfield.setCustomValidity('Por favor, preencha este campo.');
        }
    });
});

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}