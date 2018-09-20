$(document).ready(function(){
    var shouldUpdateAllSorteioDates = true;
    var shouldUpdateAllCompromissoDates = true;
    var form = $("#cadastro-conselho-form");
    form.steps({
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex)
        {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (currentIndex === 0 && $('#nome-conselho-dropdown').val() === 'null') {
                toastr.error('Selecione o tipo do novo conselho.', 'Erro!');
                $('#nome-conselho-dropdown').parent().addClass('has-error');
                return false;
            } else if ($('#nome-conselho-dropdown').val() !== 'null') {
                $('#nome-conselho-dropdown').parent().removeClass('has-error');
                $('#nome-conselho-dropdown').parent().addClass('has-sucesso');
            }

            if(currentIndex === 2 && $("#presidente-conselho-dropdown").val() === 'null') {
                toastr.error('Selecione o presidente do conselho.', 'Erro!');
                $("#presidente-conselho-dropdown").next().addClass('data-erro');
                return false;
            } else if (currentIndex === 2) {
                $("#presidente-conselho-dropdown").next().removeClass('data-erro');
                $("#presidente-conselho-dropdown").next().addClass('data-sucesso');
            }

            if(currentIndex === 2 && $("#suplente-conselho-dropdown").val() === 'null') {
                toastr.error('Selecione o suplente do presidente.', 'Erro!');
                $("#suplente-conselho-dropdown").next().addClass('data-erro');
                return false;
            } else if (currentIndex === 2) {
                $("#suplente-conselho-dropdown").next().removeClass('data-erro');
                $("#suplente-conselho-dropdown").next().addClass('data-sucesso');
            }

            if (currentIndex === 2 && $("#juizes-conselho-dropdown :selected").length != 3) {
                toastr.error('Selecione 3 juizes militares.', 'Erro!');
                return false;
            }

            if(currentIndex === 2 && $("#suplente-juizes-conselho-dropdown").val() === 'null') {
                toastr.error('Selecione o suplente dos juizes.', 'Erro!');
                $("#suplente-juizes-conselho-dropdown").next().addClass('data-erro');
                return false;
            } else if (currentIndex === 2) {
                $("#suplente-juizes-conselho-dropdown").next().removeClass('data-erro');
                $("#suplente-juizes-conselho-dropdown").next().addClass('data-sucesso');
            }

            if (newIndex === 3) {
                $("#nome_presidente").val($("#presidente-conselho-dropdown :selected").text());
                $("#nome_suplente").val($("#suplente-conselho-dropdown :selected").text());
                $('#juizes-conselho-dropdown :selected').text().split(")").forEach(function (item, index) {
                    item += ")";
                    switch (index) {
                        case 0:
                            $("#nome_juiz_1").val(item);
                            break
                        case 1:
                            $("#nome_juiz_2").val(item);
                            break
                        case 2:
                            $("#nome_juiz_3").val(item);
                            break
                    }
                });
                $("#nome_juiz_suplente").val($("#suplente-juizes-conselho-dropdown :selected").text());
            }

            return form.valid();
        },
        onFinishing: function (event, currentIndex)
        {
            var flag = false;

            var datas = [$("#data_sorteio_presidente input"), $("#data_sorteio_suplente input"),
                         $("#data_sorteio_juiz_1 input"), $("#data_sorteio_juiz_2 input"),
                         $("#data_sorteio_juiz_3 input"), $("#data_sorteio_juiz_suplente input")];

            datas.forEach(function (item) {
                if (item.val() === "") {
                    flag = true;
                }
            });

            if (flag) {
                toastr.error('Todas as datas de sorteio devem ser selecionadas.', 'Erro!');
                flag = false;
                return false;
            }

            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex)
        {
            // form.submit();
        },
        labels: {
            cancel: "Cancelar",
            current: "passo atual:",
            pagination: "Paginação",
            finish: "Pronto",
            next: "Próximo",
            previous: "Anterior",
            loading: "Carregando ..."
        }
    });

    $("#data_sorteio_presidente input").change(function () {
        if (shouldUpdateAllSorteioDates) {
            $("#data_sorteio_suplente input").val($("#data_sorteio_presidente input").val());
            $("#data_sorteio_juiz_1 input").val($("#data_sorteio_presidente input").val());
            $("#data_sorteio_juiz_2 input").val($("#data_sorteio_presidente input").val());
            $("#data_sorteio_juiz_3 input").val($("#data_sorteio_presidente input").val());
            $("#data_sorteio_juiz_suplente input").val($("#data_sorteio_presidente input").val());
            shouldUpdateAllSorteioDates = false;
        }
    });

    $("#data_compromisso_presidente input").change(function () {
        if (shouldUpdateAllCompromissoDates) {
            $("#data_compromisso_suplente input").val($("#data_compromisso_presidente input").val());
            $("#data_compromisso_juiz_1 input").val($("#data_compromisso_presidente input").val());
            $("#data_compromisso_juiz_2 input").val($("#data_compromisso_presidente input").val());
            $("#data_compromisso_juiz_3 input").val($("#data_compromisso_presidente input").val());
            $("#data_compromisso_juiz_suplente input").val($("#data_compromisso_presidente input").val());
            shouldUpdateAllCompromissoDates = false;
        }
    });

    $.fn.datepicker.defaults.language = 'pt-BR';
    $("#data_sorteio_presidente .input-group.date, #data_compromisso_presidente .input-group.date," +
        "#data_sorteio_suplente .input-group.date, #data_compromisso_suplente .input-group.date," +
        "#data_sorteio_juiz_1 .input-group.date, #data_compromisso_juiz_1 .input-group.date," +
        "#data_sorteio_juiz_2 .input-group.date, #data_compromisso_juiz_2 .input-group.date," +
        "#data_sorteio_juiz_3 .input-group.date, #data_compromisso_juiz_3 .input-group.date," +
        "#data_sorteio_juiz_suplente .input-group.date, #data_compromisso_juiz_suplente .input-group.date").datepicker({
        todayBtn: "linked",
        todayHighlight: true,
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: "dd/mm/yyyy"
    });

    $(".dual_select").bootstrapDualListbox({
        selectorMinimalHeight: 160,
        moveOnSelect: false,
        infoTextFiltered: "<span class=\"label label-warning\">Filtrado</span> {0} de {1}",
        infoTextEmpty: "Lista vazia",
        infoText: "Mostrando {0} opções",
        moveSelectedLabel: "Mover os selecionados",
        moveAllLabel: "Mover todos",
        removeSelectedLabel: "Remover os selecionados",
        removeAllLabel: "Remover todos",
        filterPlaceHolder: "Buscar",
        filterTextClear: "Mostrar tudo",
        nonSelectedListLabel: 'Militares disponíveis',
        selectedListLabel: 'Militares selecionados'
    }).on('change', function(){
        var size = $(this).find(":selected").length;
        if(size > 3){
            $(this).find(":selected").each(function(ind, sel){
                if(ind > 2)
                    $(this).prop("selected", false)
            });
            $(this).bootstrapDualListbox('refresh', true);
        }
    });

    var presidentes;
    var suplentes;
    var juizes;
    var option = "";
    // Carrega os militares baseado no conselho escolhido.
    $('#nome-conselho-dropdown').change(function () {
        var id = $(this).val();
        if (id != null){
            $("#presidente-conselho-dropdown").html("<option>Carregando ...</option>");
            $.post("../controller/loadMilitares.php", {id_nome_sigla: id}, function (data, status) {
                var militares = JSON.parse(data);
                presidentes = militares['presidentes'];
                suplentes = militares['suplentes'];
                juizes = militares['juizes'];

                option = '<option value=\"null\"></option>';
                for (var i = 0; i < presidentes.length; i++) {
                    option += '<option value="'+ presidentes[i][0] + '">' + presidentes[i][1] + ' (' + presidentes[i][2] + ')</option>';
                }
                $("#presidente-conselho-dropdown").html(option);

                option = '<option value=\"null\"></option>';
                for (var i = 0; i < suplentes.length; i++) {
                    option += '<option value="'+ suplentes[i][0] + '">' + suplentes[i][1] + ' (' + suplentes[i][2] + ')</option>';
                }
                $("#suplente-conselho-dropdown").html(option);

                option = '';
                for (var i = 0; i < juizes.length; i++) {
                    option += '<option value="'+ juizes[i][0] + '">' + juizes[i][1] + ' (' + juizes[i][2] + ')</option>';
                }
                $("#juizes-conselho-dropdown").html(option);

                option = '<option value=\"null\"></option>';
                for (var i = 0; i < juizes.length; i++) {
                    option += '<option value="'+ juizes[i][0] + '">' + juizes[i][1] + ' (' + juizes[i][2] + ')</option>';
                }
                $("#suplente-juizes-conselho-dropdown").html(option);

                $('.chosen-select').chosen({width: "100%"});
                $(".dual_select").bootstrapDualListbox('refresh', true);
            });
        }
    });

    $("#presidente-conselho-dropdown").change(function () {
        var id = $(this).val();
        option = '<option value=\"null\"></option>';
        for (var i = 0; i < suplentes.length; i++) {
            if (suplentes[i][0] != id) {
                option += '<option value="'+ suplentes[i][0] + '">' + suplentes[i][1] + ' (' + suplentes[i][2] + ')</option>';
            }
        }
        $('#suplente-conselho-dropdown').empty().html(option).trigger("chosen:updated");
    });

    $("#juizes-conselho-dropdown").change(function () {
        var ids = $(this).val();
        var selected_suplente = $("#suplente-juizes-conselho-dropdown").val();
        option = '<option value=\"null\"></option>';
        for (var i = 0; i < juizes.length; i++) {
            if (jQuery.inArray(juizes[i][0], ids) === -1) {
                option += '<option value="'+ juizes[i][0] + '">' + juizes[i][1] + ' (' + juizes[i][2] + ')</option>';
            }
        }
        $("#suplente-juizes-conselho-dropdown").empty().html(option).trigger("chosen:updated");
        $("#suplente-juizes-conselho-dropdown").val(selected_suplente).trigger("chosen:updated");
    });

    $("#suplente-juizes-conselho-dropdown").change(function () {
        var id = $(this).val();
        var selected = $("#juizes-conselho-dropdown :selected");
        var arr = jQuery.makeArray(selected);
        option = '';
        for (var i = 0; i < juizes.length; i++) {
            if (juizes[i][0] !== id) {
                option += '<option value="'+ juizes[i][0] + '">' + juizes[i][1] + ' (' + juizes[i][2] + ')</option>';
            }
        }
        $("#juizes-conselho-dropdown").empty().html(option).bootstrapDualListbox('refresh', true);
        arr.forEach(function (item, index) {
            $("#juizes-conselho-dropdown option[value=\""+item.value+"\"]").prop("selected", true);
        });
        $("#juizes-conselho-dropdown").bootstrapDualListbox('refresh', true);
    });

    $("#numero-processo").inputmask("9999999-99.9999.7.0\\9.00\\9");

});