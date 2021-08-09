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
    $('#archivoUpdate').focus();
});

$("#add").click(function () {
    $('#dataRegister').modal('show');
});

$('#dataRegister').on('shown.bs.modal', function () {
    $('#archivoAdd').focus();
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
            busqueda: busqueda
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivos.controller.php',
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

$("#actualidarDatosArchivo").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "updateArchivo",
            codigo: codigo,
            descripcion: $("#descripcionUpdate").val(),
            ruta: $("#rutaUpdate").val(),
            fecha_hora: $("#fechaUpdate").val(),
            activo: $("#activoUpdate").val(),
            cod_prod_na: $("#prodaUpdate").val(),
            cod_prod_nb: $("#prodbUpdate").val(),
            cod_prod_nc: $("#prodcUpdate").val(),
            cod_prod_nd: $("#proddUpdate").val(),
            cod_prod_ne: $("#prodeUpdate").val(),
            cod_prod_nf: $("#prodfUpdate").val(),
            cod_prod_personalizado_id: $("#prodpUpdate").val(),
            cod_prod_estandar_id: $("#prodsUpdate").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivos.controller.php',
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

$("#guardarDatosArchivo").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "addArchivo",
            descripcion: $("#descripcionAdd").val(),
            ruta: $("#rutaAdd").val(),
            fecha_hora: $("#fechaAdd").val(),
            activo: $("#activoAdd").val(),
            cod_prod_na: $("#prodaAdd").val(),
            cod_prod_nb: $("#prodbAdd").val(),
            cod_prod_nc: $("#prodcAdd").val(),
            cod_prod_nd: $("#proddAdd").val(),
            cod_prod_ne: $("#prodeAdd").val(),
            cod_prod_nf: $("#prodfAdd").val(),
            cod_prod_personalizado_id: $("#prodpAdd").val(),
            cod_prod_estandar_id: $("#prodsAdd").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/archivos.controller.php',
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

