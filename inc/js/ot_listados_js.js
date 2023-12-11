var requestSent = false;
var codigo;

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

$('#myModal').on('shown.bs.modal', function () {
    $('#btn-eliminar').focus();
})

$('#dataUpdate').on('shown.bs.modal', function () {
    $('#ot_listadoUpdate').focus();
});

$("#add").click(function () {
    $('#dataRegister').modal('show');
});

$('#dataRegister').on('shown.bs.modal', function () {
    $('#ot_listadoAdd').focus();
});

$('#select_tipo').change(function () {
    $("#busqueda-icono").click();
});

function getRegistros(orderby, sentido, registros, pagina, busqueda, objeto) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "getRegistrosFiltro",
            orderby: orderby,
            sentido: sentido,
            registros: registros,
            pagina: pagina,
            busqueda: busqueda,
            estado: $("#select_tipo").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
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

$("#actualidarDatosOt_listado").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "updateOt_listado",
            codigo: codigo,
            nroserie: $("#nroserieUpdate").val(),
            cliente: $("#clienteUpdate").val(),
            tipo: $("#tipoUpdate").val(),
            prioridad: $("#prioridadUpdate").val(),
            fecha: $("#fechaUpdate").val(),
            entrega: $("#fechaEntregaUpdate").val(),
            observaciones: $("#observacionesUpdate").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function () {
                alert("Error");
            }
        });
        event.preventDefault();
    }
});

$("#guardarDatosOt_listado").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "addOt_listado",
            nroserie: $("#nroserieAdd").val(),
            cliente: $("#clienteAdd").val(),
            prioridad: $("#prioridadAdd").val(),
            tipo: $("#tipoAdd").val(),
            fecha: $("#fechaAdd").val(),
            entrega: $("#fechaEntregaAdd").val(),
            observaciones: $("#observacionesAdd").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function () {
                alert("Error");
            }
        });
        event.preventDefault();
    }
});

$("#seguimientos").click(function () {
    window.location.href = "ot_seguimientos.php";
});

$("#btn-estado-ot_listado").click(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "estadoOt_listado",
            codigo: codigo,
            estado: $("#estadoAdd").val(),
            avance: $("#avanceAdd").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function () {
                alert("Error");
            }
        });
        event.preventDefault();
    }
});

$("#btn-estado-ot_listado_all").click(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "estadoOt_listado_all",
            codigo: codigo,
            estadoing: parseInt($("#estadoIngAdd").val()) || -99,
            estadodespacho: parseInt($("#estadoDespachoAdd").val()) || -99,
            estadoprod: parseInt($("#estadoProdAdd").val()) || -99
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function () {
                alert("Error");
            }
        });
        event.preventDefault();
    }
});
