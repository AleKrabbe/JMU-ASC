$(document).ready(function () {
    // Mascara os campos CPF e telefone.

    $('.cpf').mask('000.000.000-00', {placeholder: "___.___.___-__", reverse: true});
    $('.phone_with_ddd').mask('(00) 00000-0000', {placeholder: "(__) _____-____"});

    //----------------------------------------
    // Valida cpf, email, fname e lname.

    $('.cpf').focusout(function () {
        if($(this).val().length < 14){
            $(this).parent().addClass('has-error');
            if ($(this).val().length > 0) {
                toastr.error('O CPF deve conter 11 números.', 'Erro!');
            }
        } else if ($(this).val().length === 14){
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
            var cpf = $(this).val();
            $.post("../controller/checkCPF.php", {CPF: cpf}, function (data, status) {
                if (data === "1"){
                    toastr.error('CPF já cadastrado.', 'Erro!');
                    $('.cpf').focus().parent().addClass('has-error');
                }
            });
        }
    });

    $('.fname').keypress(function (e) {
        // var regex = "/^[a-zA-Z]+$/";
        var code = e.keyCode || e.which;
        if(code == 32) {
            toastr.error('Digite apenas o primeiro nome neste campo.', 'Erro!');
        }
        // this.value = this.value.replace(/\ +/g, '');
    }).focusout(function () {
        $(this).val($(this).val().trim());
        if($(this).val().length > 45 || $(this).val().length === 0){
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

    $('.lname').keyup(function () {
        var regex = /^[a-zA-Z\ ]+$/;
        this.value = this.value.replace(/[^a-zA-Z\ ]+/, '');
    }).focusout(function () {
        $(this).val($.trim($(this).val()));
        if($(this).val().length > 100 || $(this).val().length === 0){
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        }
    }).one("focusout", function () {
        if ($(this).val().length === 0) {
            toastr.error('O preenchimento do sobrenome é obrigatório.', 'Erro!');
        }
    });

    $('.telefone').focusout(function () {
        if($(this).val().length < 14 && $(this).val().length > 0){
            $(this).focus().parent().addClass('has-error');
            toastr.error('O Celular deve conter entre 10 e 11 números.', 'Erro!');
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
        } else {
            $(this).parent().removeClass('has-error');
            if ($(this).val().length > 0)
            $(this).parent().addClass('has-success');
        }
        $(this).val($.trim($(this).val()));
    });

    $('#om-dropdown').change(function () {
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

    $('#ajaxop').change(function () {
        if($(this).val() == null || $(this).val() === "null"){
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        }
    }).focusout(function () {
        if($(this).val() == null || $(this).val() === "null"){
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
            $(this).parent().addClass('has-success');
        }
    });

    $('#submit-btn').click(function (e) {
        if ($('.cpf').val() === '') {
            toastr.error('Digite o CPF do militar.', 'Erro!');
            $('.cpf').focus().parent().addClass('has-error');
            return false;
        }

        if ($('.fname').val() === '') {
            toastr.error('Digite o nome do militar.', 'Erro!');
            $('.fname').focus().parent().addClass('has-error');
            return false;
        }

        if ($('.lname').val() === '') {
            toastr.error('Digite o sobrenome do militar.', 'Erro!');
            $('.lname').focus().parent().addClass('has-error');
            return false;
        }

        if ($('#ajaxop').val() === 'null') {
            toastr.error('Selecione um posto para o militar.', 'Erro!');
            $('#ajaxop').focus().parent().addClass('has-error');
            return false;
        }
        if ($('#om-dropdown').val() === 'null') {
            toastr.error('Selecione uma organização militar.', 'Erro!');
            $('#om-dropdown').focus().parent().addClass('has-error');
            return false;
        }
        $('#cadastro-militar-form').submit();
    });

    // -------------------------------------
    // Verifica campos em branco.

    $('.shouldValidate').on('change invalid', function() {
        var textfield = $(this).get(0);

        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            if ($(this).attr('name') === 'cpf') {
                textfield.setCustomValidity('Por favor, preencha o campo ' + $(this).attr('name') + ' com o seguinte formato:' + $(this).attr('title'));
            } else {
                textfield.setCustomValidity('Por favor, preencha este campo.');
            }
        }
    });
});

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}