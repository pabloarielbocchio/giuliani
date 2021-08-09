var requestSent = false;
var codigo;

$(document).ready(function () {
    $("#busqueda-icono").click();
    $("#select_n1").val($(".container").attr("cod_nivel_1"));
    $("#select_n1").change();
    $("#select_n2").val($(".container").attr("cod_nivel_2"));
    $("#select_n2").change();
    $("#select_n3").val($(".container").attr("cod_nivel_3"));
    $("#select_n3").change();
    $("#select_n4").val($(".container").attr("cod_nivel_4"));
    $("#select_n4").change();
    $(".navbar-minimalize").click();
});

$(document).on({
    ajaxStart: function () {
        $("#loading").css("display", "block");
    },
    ajaxStop: function () {
        $("#loading").css("display", "none");
    }
});

function getRegistros(orderby, sentido, registros, pagina, busqueda, objeto) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "getRegistrosFiltroFiles",
            orderby: orderby,
            sentido: sentido,
            registros: registros,
            pagina: pagina,
            busqueda: busqueda,            
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val(),
            opc: $(".container").attr("opc"),
            grupo: $(".container").attr("grupo")
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
            data: parametros,
            success: function (datos) {
                $("#paginacion_paginas").html($("#paginas_aux").html());
                $("#div_tabla").attr("pagina", pagina);
                $("#div_tabla").html(datos);
                $("#leyenda_paginacion").html($("#leyenda_paginacion_aux").html());

                var pag = $("#div_tabla").attr("pagina");

                var first = $("#first");
                var before = $("#before");
                var actual = $("#actual");
                var next = $("#next");
                var last = $("#last");

                var cant_reg = $("#tabla").attr("registros");
                var totales = $("#tabla").attr("totales");

                last.attr("ultimo", Math.ceil(totales / cant_reg));

                if (pag == "<<") {
                    pag = 1;
                }

                if (pag == ">>") {
                    pag = parseInt(last.attr("ultimo"));
                }

                $("#div_tabla").attr("pagina", pag);
                //$("#busqueda-icono").click();

                first.css("display", "inline-block");
                before.css("display", "inline-block");
                if (parseInt(pag) == 1) {
                    first.css("display", "none");
                    before.css("display", "none");
                }
                if (parseInt(pag) == 2) {
                    first.css("display", "none");
                    before.css("display", "inline-block");
                }

                first.text("<<");
                before.text(parseInt(pag) - 1);
                actual.text(pag);
                next.text(parseInt(pag) + 1);
                last.text(">>");

                next.css("display", "inline-block");
                last.css("display", "inline-block");
                if (parseInt(pag) == parseInt(last.attr("ultimo"))) {
                    next.css("display", "none");
                    last.css("display", "none");
                }
                if (parseInt(pag) == parseInt(last.attr("ultimo")) - 1) {
                    next.css("display", "inline-block");
                    last.css("display", "none");
                }

                if (parseInt($("#cant_reg").val()) == -1) {
                    first.css("display", "none");
                    before.css("display", "none");
                    next.css("display", "none");
                    last.css("display", "none");
                }

            },
            error: function () {
                alert("Error");
            },
            complete: function () {
                requestSent = false;
            }
        });
    }
}

$("#select_n1").change(function () {

    $("#select_n2").val(null);
    $("#select_n3").val(null);
    $("#select_n4").val(null);
    $(".opt_nivel2").css("display","none");
    $(".opt_nivel3").css("display","none");
    $(".opt_nivel4").css("display","none");
    
    $('.opt_nivel2[nivel_anterior="'+$(this).val()+'"').css("display","block");
    
    $("#busqueda-icono").click();
});

$("#select_n2").change(function () {

    $("#select_n3").val(null);
    $("#select_n4").val(null);
    $(".opt_nivel3").css("display","none");
    $(".opt_nivel4").css("display","none");
    
    $('.opt_nivel3[nivel_anterior="'+$(this).val()+'"').css("display","block");
    $("#busqueda-icono").click();
});

$("#select_n3").change(function () {

    $("#select_n4").val(null);
    $(".opt_nivel4").css("display","none");
    
    $('.opt_nivel4[nivel_anterior="'+$(this).val()+'"').css("display","block");
    $("#busqueda-icono").click();
});

$("#select_n4").change(function () {

    $("#busqueda-icono").click();
});