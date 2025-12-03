var requestSent = false;
var codigo;
var scrollPosicionGuardada = 0; // Variable para guardar la posición del scroll al abrir el modal
var codigoOtModificada = null; // Variable para guardar el código de la OT modificada

// Funciones para guardar y restaurar estado de la tabla
function guardarEstadoTabla() {
    var estado = {
        orderby: $("#div_tabla").attr("orderby"),
        sentido: $("#div_tabla").attr("sentido"),
        pagina: $("#div_tabla").attr("pagina"),
        cant_reg: $("#cant_reg").val(),
        busqueda: $("#busqueda").val(),
        tipo: $("#select_tipo").val(),
        scrollTop: scrollPosicionGuardada, // Usar la posición guardada al abrir el modal
        codigoOT: codigoOtModificada // Guardar código de la OT modificada
    };
    sessionStorage.setItem('ot_listados_estado', JSON.stringify(estado));
}

function restaurarEstadoTabla() {
    var estadoGuardado = sessionStorage.getItem('ot_listados_estado');
    if (estadoGuardado) {
        var estado = JSON.parse(estadoGuardado);
        $("#div_tabla").attr("orderby", estado.orderby);
        $("#div_tabla").attr("sentido", estado.sentido);
        $("#div_tabla").attr("pagina", estado.pagina);
        $("#cant_reg").val(estado.cant_reg);
        $("#busqueda").val(estado.busqueda);
        $("#select_tipo").val(estado.tipo);
        
        // Restaurar posición del scroll y highlighting después de cargar la tabla
        if (estado.scrollTop || estado.codigoOT) {
            setTimeout(function() {
                // Restaurar scroll
                if (estado.scrollTop) {
                    $(window).scrollTop(estado.scrollTop);
                }
                
                // Resaltar la fila modificada en amarillo
                if (estado.codigoOT) {
                    $("tr[codigo='" + estado.codigoOT + "']").css("background-color", "#FFFFB8");
                }
            }, 100); // Pequeño delay para asegurar que la tabla esté cargada
        }
        
        // Limpiar el estado guardado después de restaurar
        sessionStorage.removeItem('ot_listados_estado');
        return true;
    }
    return false;
}

$(document).ready(function () {
    // Intentar restaurar estado guardado
    var estadoRestaurado = restaurarEstadoTabla();
    
    if (estadoRestaurado) {
        // Cargar la tabla con el estado restaurado
        var orderby = $("#div_tabla").attr("orderby");
        var sentido = $("#div_tabla").attr("sentido");
        var registros = $("#cant_reg").val();
        var pagina = $("#div_tabla").attr("pagina");
        var busqueda = $("#busqueda").val();
        getRegistros(orderby, sentido, registros, pagina, busqueda, null);
    } else {
        // Carga normal
        $("#busqueda-icono").click();
    }
    
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
                $("#div_tabla").attr("orderby", orderby);
                $("#div_tabla").attr("sentido", sentido);
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
                    // Guardar estado antes de recargar
                    guardarEstadoTabla();
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
        var valEstadoIng = $("#estadoIngAdd").val();
        var valEstadoDespacho = $("#estadoDespachoAdd").val();
        var valEstadoProd = $("#estadoProdAdd").val();
        
        var parametros = {
            funcion: "estadoOt_listado_all",
            codigo: codigo,
            estadoing: (valEstadoIng !== null && valEstadoIng !== undefined && valEstadoIng !== '') ? parseInt(valEstadoIng) : -99,
            estadodespacho: (valEstadoDespacho !== null && valEstadoDespacho !== undefined && valEstadoDespacho !== '') ? parseInt(valEstadoDespacho) : -99,
            estadoprod: (valEstadoProd !== null && valEstadoProd !== undefined && valEstadoProd !== '') ? parseInt(valEstadoProd) : -99
        }
        
        // LOG PARA DEBUG - Valores antes de enviar
        console.log("═══════════════════════════════════════");
        console.log("📤 ENVIANDO AL BACKEND:");
        console.log("  Código OT:", codigo);
        console.log("  Estado Ingeniería (raw):", valEstadoIng, "→ (enviado):", parametros.estadoing);
        console.log("  Estado Producción (raw):", valEstadoProd, "→ (enviado):", parametros.estadoprod);
        console.log("  Estado Despacho (raw):", valEstadoDespacho, "→ (enviado):", parametros.estadodespacho);
        console.log("  Parámetros completos:", parametros);
        console.log("═══════════════════════════════════════");
        
        $.ajax({
            type: "POST",
            url: 'controller/ot_listados.controller.php',
            data: parametros,
            success: function (datos) {
                console.log("✅ RESPUESTA DEL SERVIDOR:", datos);
                if (parseInt(datos) == 0) {
                    console.log("✅ Guardado exitoso. Recargando en 1 segundo...");
                    // Guardar estado antes de recargar
                    guardarEstadoTabla();
                    // Delay de 1 segundo para ver los logs
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    console.error("❌ ERROR: El servidor retornó:", datos);
                    alert("Error al guardar");
                }
            },
            error: function (xhr, status, error) {
                console.error("❌ ERROR DE CONEXIÓN:", error);
                alert("Error de conexión");
            }
        });
        event.preventDefault();
    }
});
