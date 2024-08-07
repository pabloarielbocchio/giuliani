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
    $('#ot_detalleUpdate').focus();
});

$("#add").click(function () {
    $('#dataRegister').modal('show');
});

$('#dataRegister').on('shown.bs.modal', function () {
    $('#ot_detalleAdd').focus();
});

$('#select_ot').change(function () {

    window.location.href = "detalles.php?cod_ot=" + $("#select_ot").val();
        var valor=('select_ot').val();
  
    
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
            ot: $("#select_ot").val()
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

$("#actualidarDatosOt_detalle").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "updateOt_detalle",
            codigo: codigo,
            item: $("#itemUpdate").val(),
            cantidad: $("#cantidadUpdate").val(),
            seccion: $("#seccionUpdate").val(),
            sector: $("#sectorUpdate").val(),
            estado: $("#estadoUpdate").val(),
            prioridad: $("#prioridadUpdate").val(),
            //ot: $("#otUpdate").val(),
            ot: $("#select_ot").val(),
            pu: $("#puUpdate").val(),
            pucant: $("#pucantUpdate").val(),
            puneto: $("#punetoUpdate").val(),
            clasificacion: $("#clasificacionUpdate").val(),
            observaciones: $("#observacionesUpdate").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_detalles.controller.php',
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

$("#guardarDatosOt_detalle").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "addOt_detalle",
            item: $("#itemAdd").val(),
            cantidad: $("#cantidadAdd").val(),
            seccion: $("#seccionAdd").val(),
            sector: $("#sectorAdd").val(),
            //estado: $("#estadoAdd").val(),
            estado: 1,
            prioridad: $("#prioridadAdd").val(),
            //ot: $("#otAdd").val(),
            ot: $("#select_ot").val(),
            pu: $("#puAdd").val(),
            pucant: $("#pucantAdd").val(),
            puneto: $("#punetoAdd").val(),
            clasificacion: $("#clasificacionAdd").val(),
            observaciones: $("#observacionesAdd").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/ot_detalles.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
                } else {
                    alert("Error: "+datos);
                }
            },
            error: function () {
                alert("Error");
            }
        });
        event.preventDefault();
    }
});


$("#guardarDatosOt_prodperso").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "addProducto",
            descripcion: $("#prodpersoAdd").val(),
            oracle: $("#oracleAdd").val(),
            unidad: $("#unidadAdd").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos_p.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) < 0) {
                    var parametros = {
                        funcion: "addOt_produccion",
                        avance: 0,
                        standard: 0,
                        prod_id: 0,
                        personalizado_id: parseInt(datos)*-1,
                        estado: 1,
                        detalle: codigo,
                        observaciones: $("#observacionespersoAdd").val(),
                        prioridad: $("#prioridadpersoAdd").val(),
                        cantidad: $("#cantidadpersoAdd").val(),
                        unidad: $("#unidadAdd").val()
                    }
                    $.ajax({
                        type: "POST",
                        url: 'controller/ot_produccions.controller.php',
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
                } else {
                    alert("Error: " + datos);
                }
            },
            error: function () {
                alert("Error");
            }
        });
        event.preventDefault();
    }
});

$("#descargar").click(function () {
    window.location.href = 'uploads/model_ot.xls';
});