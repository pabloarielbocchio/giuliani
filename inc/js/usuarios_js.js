var requestSent = false;
var codigo;
var sucursales_add = null;
var sucursales_update = null;

$(document).ready(function () {
    $("#busqueda-icono").click();
    $('#sucursalAdd').select2();
    $(".navbar-minimalize").click();
});

$('#sucursalAdd').change(function() {
    var limite = $(".container").attr("cant_sucursales");
    if ($(this).val().length > limite) {
        $(this).val(sucursales_add);
    } else {
        sucursales_add = $(this).val();
    }
});

$('#sucursalUpdate').change(function() {
    var limite = $(".container").attr("cant_sucursales");
    if ($(this).val().length > limite) {
        $(this).val(sucursales_update);
    } else {
        sucursales_update = $(this).val();
    }
});

/*$(document).on({
    ajaxStart: function () {
        $("#loading").css("display", "block");
    },
    ajaxStop: function () {
        $("#loading").css("display", "none");
    }
});*/

$('#myModal').on('shown.bs.modal', function () {
    $('#btn-eliminar').focus();
})

$('#dataUpdate').on('shown.bs.modal', function () {
    $('#usuarioUpdate').focus();
});

$("#add").click(function () {
    $('#dataRegister').modal('show');
});

$('#dataRegister').on('shown.bs.modal', function () {
    $('#usuarioAdd').focus();
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
            url: 'controller/usuarios.controller.php',
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

$("#actualidarDatosUsuario").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var sucursales = $("#sucursalUpdate").val();
        var parametros = {
            funcion: "updateUsuario",
            codigo: codigo,
            cliente: $("#clienteUpdate").val(),
            cargo: $("#rolUpdate").val(),
            nombre: $("#nombreUpdate").val(),
            usuario: $("#usuarioUpdate").val(),
            password: $("#passwordUpdate").val(),
            apellido: $("#apellidoUpdate").val(),
            mail: $("#mailUpdate").val(),
            sucursales: sucursales
        }
        $.ajax({
            type: "POST",
            url: 'controller/usuarios.controller.php',
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

$("#guardarDatosUsuario").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var sucursales = $("#sucursalAdd").val();
        var parametros = {
            funcion: "addUsuario",
            cliente: $("#clienteAdd").val(),
            cargo: $("#rolAdd").val(),
            nombre: $("#nombreAdd").val(),
            usuario: $("#usuarioAdd").val(),
            password: $("#passwordAdd").val(),
            apellido: $("#apellidoAdd").val(),
            mail: $("#mailAdd").val(),
            sucursales: sucursales
        }
        $.ajax({
            type: "POST",
            url: 'controller/usuarios.controller.php',
            data: parametros,
            success: function (datos) {
                if (parseInt(datos) == 0) {
                    location.reload();
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

