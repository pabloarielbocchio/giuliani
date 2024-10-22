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
    /*
    if ($(".container").attr("cod_nivel_1") == 0){
        $("#select_n1").change();
    } else {
        if ($(".container").attr("cod_nivel_2") == 0){
            $("#select_n2").change();
        }
        if ($(".container").attr("cod_nivel_3") == 0){
            $("#select_n3").change();
        }
        if ($(".container").attr("cod_nivel_4") == 0){
            $("#select_n4").change();
        }*/

    $("#select_n1").select2({
        placeholder: 'Seleccione una opción',
        allowClear: true
    });
    $("#select_n2").select2({
        placeholder: 'Seleccione una opción',
        allowClear: true
    });
    $("#select_n3").select2({
        placeholder: 'Seleccione una opción',
        allowClear: true
    });

    // Manejar el evento de cambio en select_n1
    $("#select_n1").on("change", function() {
        var selectedValue = $(this).val();

        // Limpiar selects dependientes
        $("#select_n2").empty().append('<option value=""></option>').val(null).trigger("change"); // Añadir opción vacía
        $("#select_n3").empty().append('<option value=""></option>').val(null).trigger("change");
        $("#select_n4").empty().append('<option value=""></option>').val(null).trigger("change");

        // Si se selecciona una opción válida
        if (selectedValue) {
            // Realizar solicitud AJAX para obtener las opciones de select_n2
            $.ajax({
                url: 'controller/productos.controller.php',
                type: 'POST',  // Método de la solicitud
                data: {
                    funcion: "getProductosBOnly",
                    codigo: selectedValue  // Enviar el valor seleccionado en nivel 1
                },
                dataType: 'json',  // Especificar que esperamos una respuesta en JSON
                success: function(response) {
                    // Limpiar el select_n2 antes de agregar las nuevas opciones
                    $("#select_n2").empty();
                    $("#select_n2").empty().append('<option value=""></option>');

                    // Agregar las nuevas opciones a select_n2
                    $.each(response, function(index, option) {
                        $("#select_n2").append('<option value="' + option.codigo + '">' + option.codigo + " - " + option.descripcion + '</option>');
                    });

                    // Refrescar Select2 para que muestre las nuevas opciones
                    $("#select_n2").select2({
                        placeholder: 'Seleccione una opción',
                        allowClear: true
                    });

                    // Simular clic en el botón de búsqueda
                    $("#busqueda-icono").click();
                },
                error: function() {
                    alert('Hubo un error al cargar las opciones.');
                }
            });
        }
    });

    // Manejar el evento de cambio en select_n2
    $("#select_n2").on("change", function() {
        var selectedValue = $(this).val();

        // Limpiar selects dependientes
        $("#select_n3").val(null).trigger("change");
        $("#select_n4").val(null).trigger("change");

        // Si se selecciona una opción válida
        if (selectedValue) {
            // Realizar solicitud AJAX para obtener las opciones de select_n2
            $.ajax({
                url: 'controller/productos.controller.php',
                type: 'POST',  // Método de la solicitud
                data: {
                    funcion: "getProductosCOnly",
                    codigo: selectedValue  // Enviar el valor seleccionado en nivel 1
                },
                dataType: 'json',  // Especificar que esperamos una respuesta en JSON
                success: function(response) {
                    // Limpiar el select_n2 antes de agregar las nuevas opciones
                    $("#select_n3").empty();
                    $("#select_n3").empty().append('<option value=""></option>');

                    // Agregar las nuevas opciones a select_n2
                    $.each(response, function(index, option) {
                        $("#select_n3").append('<option value="' + option.codigo + '">' + option.codigo + " - " + option.descripcion + '</option>');
                    });

                    // Refrescar Select2 para que muestre las nuevas opciones
                    $("#select_n3").select2({
                        placeholder: 'Seleccione una opción',
                        allowClear: true
                    });

                    // Simular clic en el botón de búsqueda
                    $("#busqueda-icono").click();
                },
                error: function() {
                    alert('Hubo un error al cargar las opciones.');
                }
            });
        }
    });

    // Manejar el evento de cambio en select_n3
    $("#select_n3").on("change", function() {
        var selectedValue = $(this).val();

        // Limpiar selects dependientes
        $("#select_n4").val(null).trigger("change");

        // Si se selecciona una opción válida
        if (selectedValue) {
            // Realizar solicitud AJAX para obtener las opciones de select_n2
            $.ajax({
                url: 'controller/productos.controller.php',
                type: 'POST',  // Método de la solicitud
                data: {
                    funcion: "getProductosDOnly",
                    codigo: selectedValue  // Enviar el valor seleccionado en nivel 1
                },
                dataType: 'json',  // Especificar que esperamos una respuesta en JSON
                success: function(response) {
                    // Limpiar el select_n2 antes de agregar las nuevas opciones
                    $("#select_n4").empty();
                    $("#select_n4").empty().append('<option value=""></option>');

                    // Agregar las nuevas opciones a select_n2
                    $.each(response, function(index, option) {
                        $("#select_n4").append('<option value="' + option.codigo + '">' + option.codigo + " - " + option.descripcion + '</option>');
                    });

                    // Refrescar Select2 para que muestre las nuevas opciones
                    $("#select_n4").select2({
                        placeholder: 'Seleccione una opción',
                        allowClear: true
                    });

                    // Simular clic en el botón de búsqueda
                    $("#busqueda-icono").click();
                },
                error: function() {
                    alert('Hubo un error al cargar las opciones.');
                }
            });
        }
    });
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
    $('#productoUpdate').focus();
});

$("#add").click(function () {
    $('#dataRegister').modal('show');
});

$('#dataRegister').on('shown.bs.modal', function () {
    $('#productoAdd').focus();
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
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val()
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

$("#actualidarDatosProducto").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "updateProducto",
            codigo: codigo,
            descripcion: $("#descripcionUpdate").val(),
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
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

$("#guardarDatosProducto").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var parametros = {
            funcion: "addProducto",
            descripcion: $("#descripcionAdd").val(),
            select_n1: $('#select_n1').val(),
            select_n2: $('#select_n2').val(),
            select_n3: $('#select_n3').val(),
            select_n4: $('#select_n4').val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/productos.controller.php',
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
/*
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
*/