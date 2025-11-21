var requestSent = false;
var codigo;
var code;
var atributo;
var destino;
var estado_id;

$(document).ready(function () {
    $("#busqueda-icono").click();
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

$('#select_ot').change(function () {
    $("#busqueda-icono").click();
});

$('#select_estado').change(function () {
    $("#busqueda-icono").click();
});

$('#select_area').change(function () {
    $("#busqueda-icono").click();
});

function getRegistros(orderby, sentido, registros, pagina, busqueda, objeto) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "getRegistrosFiltroSeguimiento",
            orderby: orderby,
            sentido: sentido,
            registros: registros,
            pagina: pagina,
            busqueda: busqueda,
            ot: $("#select_ot").val(),
            area: $("#select_area").val(),
            estado: -2 //$("#select_estado").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_detalles.controller.php',
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

$("#actualidarDatosOt_produccion").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        parametros = {
            funcion: "updateOt_produccionEstado",
            codigo: codigo,
            code: code,
            atributo: atributo,
            destino: destino,
            avance: $("#avanceUpdate").val(),
            observaciones: $("#observacionesUpdate").val(),
            estado: $("#estadoUpdate").val(),
            ing_alcance: $("#alcanceUpdate").val(),
            ing_planos: $("#planoUpdate").val(),
            quien: '',
            motivo: 0,
            descripcion: ''
        }
        if (estado_id == 3) {
            parametros = {
                funcion: "updateOt_produccionEstado",
                codigo: codigo,
                code: code,
                atributo: atributo,
                destino: destino,
                avance: $("#avanceUpdate").val(),
                observaciones: $("#observacionesUpdate").val(),
                estado: $("#estadoUpdate").val(),
                ing_alcance: $("#alcanceUpdate").val(),
                ing_planos: $("#planoUpdate").val(),
                quien: $("#quienUpdate").val(),
                motivo: $("#motivoUpdate").val(),
                descripcion: $("#descripcionUpdate").val()
            }
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_produccions.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    //$("#busqueda-icono").click();
                    //$(".divespecial").scrollLeft($("#div_tabla").attr("scrollx"));
                    location.reload();
                } else {
                    alert("Error " + datos);
                }
            },
            error: function () {
                alert("Error");
            }
        });
        event.preventDefault();
    }
});

$("#estadoUpdate").change(function () {
    /*var estado = $(this).val();
    $(".div_avance").css("display", "none");
    if (estado == 2){
        $(".div_avance").css("display", "block");
    }*/
});