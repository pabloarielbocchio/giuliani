var requestSent = false;
var codigo;
var fecha_modif;

$(document).ready(function () {
    $("#busqueda-icono").click();
    $(".navbar-minimalize").click();
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
    $('#descripcionUpdate').focus();
    $('#descripcionUpdate').select();
});

$("#add").click(function () {
    $("#nivelAdd").change();
    $('#dataRegister').modal('show');
});

$('#dataRegister').on('shown.bs.modal', function () {
    $('#descripcionAdd').focus();
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
            rol: $("#select_rol").val()
        }
        $.ajax({
            type: "POST",
            url: 'controller/menus.controller.php',
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
        var descripcion = $("#descripcionUpdate").val();
        var nivel = $("#nivelUpdate").val();
        var niveles = $("#nivelesUpdate").val();
        var subniveles = $("#subnivelesUpdate").val();
        var visible = $("#visibleUpdate").val();
        var destino = $("#destinoUpdate").val();
        var icono = $("#iconoUpdate").val();
        var orden = $("#ordenUpdate").val();
        var parametros = {
            funcion: "updateMenus",
            codigo: codigo,
            fecha_modif: fecha_modif,
            descripcion: descripcion,
            nivel: nivel,
            niveles: niveles,
            subniveles: subniveles,
            visible: visible,
            destino: destino,
            icono: icono,
            orden: orden
        }
        $.ajax({
            type: "POST",
            url: 'controller/menus.controller.php',
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

$("#guardarDatosUsuario").submit(function (event) {
    if (!requestSent) {
        requestSent = true;
        var descripcion = $("#descripcionAdd").val();
        var nivel = $("#nivelAdd").val();
        var niveles = $("#nivelesAdd").val();
        var subniveles = $("#subnivelesAdd").val();
        var visible = $("#visibleAdd").val();
        var destino = $("#destinoAdd").val();
        var icono = $("#iconoAdd").val();
        var orden = $("#ordenAdd").val();
        var parametros = {
            funcion: "addMenus",
            descripcion: descripcion,
            nivel: nivel,
            niveles: niveles,
            subniveles: subniveles,
            visible: visible,
            destino: destino,
            icono: icono,
            orden: orden
        }
        $.ajax({
            type: "POST",
            url: 'controller/menus.controller.php',
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

$("#nivelAdd").change(function () {
    var opc = $(this).val();
    $("#nivelesAdd").attr("disabled", "disabled");
    $("#subnivelesAdd").attr("disabled", "disabled");
    if (opc == 3){
        $("#nivelesAdd").removeAttr("disabled");
        $("#subnivelesAdd").removeAttr("disabled");
    }
    if (opc == 2){
        $("#nivelesAdd").removeAttr("disabled");
    }
    $("#nivelesAdd").val(0);
    $("#subnivelesAdd").val(0);    
    $("#ordenAdd").val(0); 
    updateOrdenAdd();
});

$("#nivelesAdd").change(function () {
    $(".subnivelAdd").each(function () {
        var subnivel = $(this).attr("nivel") * -1;
        if (subnivel == $("#nivelesAdd").val()){
            $(this).css("display", "block");
        } else {
            $(this).css("display", "none");
        }
    });
    updateOrdenAdd();
}); 

$("#subnivelesAdd").change(function () {
    updateOrdenAdd();
}); 

function updateOrdenAdd() {
    var nivel_select = $("#nivelAdd").val();
    var nivel = $("#nivelesAdd").val();
    var subnivel = $("#subnivelesAdd").val();
    $(".opcionAdd").each(function () {
        var v = $(this).val();
        var n = $(this).attr("nivel");
        var s = $(this).attr("subnivel");
        if (nivel_select == 1){
            if (n >= 0){
                $(this).css("display", "block");
            } else {
                $(this).css("display", "none");
            }
        }
        if (nivel_select == 2){
            if (n == nivel * -1){
                $(this).css("display", "block");
            } else {
                $(this).css("display", "none");
            }
        }
        if (nivel_select == 3){
            if (n == nivel * -1){
                if (s == subnivel * -1){
                    $(this).css("display", "block");
                } else {
                    $(this).css("display", "none");
                }
            } else {
                $(this).css("display", "none");
            }
        }
    });
}

$("#nivelUpdate").change(function () {
    var opc = $(this).val();
    $("#nivelesUpdate").attr("disabled", "disabled");
    $("#subnivelesUpdate").attr("disabled", "disabled");
    if (opc == 3){
        $("#nivelesUpdate").removeAttr("disabled");
        $("#subnivelesUpdate").removeAttr("disabled");
    }
    if (opc == 2){
        $("#nivelesUpdate").removeAttr("disabled");
    }
    $("#nivelesUpdate").val(0);
    $("#subnivelesUpdate").val(0);    
    $("#ordenUpdate").val(0); 
    updateOrdenUpdate();
});

$("#nivelesUpdate").change(function () {
    $(".subnivelUpdate").each(function () {
        var subnivel = $(this).attr("nivel") * -1;
        if (subnivel == $("#nivelesUpdate").val()){
            $(this).css("display", "block");
        } else {
            $(this).css("display", "none");
        }
    });
    updateOrdenUpdate();
}); 

$("#subnivelesUpdate").change(function () {
    updateOrdenUpdate();
}); 

function updateOrdenUpdate() {
    var nivel_select = $("#nivelUpdate").val();
    var nivel = $("#nivelesUpdate").val();
    var subnivel = $("#subnivelesUpdate").val();
    $(".opcionUpdate").each(function () {
        var v = $(this).val();
        var n = $(this).attr("nivel");
        var s = $(this).attr("subnivel");
        if (nivel_select == 1){
            if (n >= 0){
                $(this).css("display", "block");
            } else {
                $(this).css("display", "none");
            }
        }
        if (nivel_select == 2){
            if (n == nivel * -1){
                $(this).css("display", "block");
            } else {
                $(this).css("display", "none");
            }
        }
        if (nivel_select == 3){
            if (n == nivel * -1){
                if (s == subnivel * -1){
                    $(this).css("display", "block");
                } else {
                    $(this).css("display", "none");
                }
            } else {
                $(this).css("display", "none");
            }
        }
    });
}

$("#select_rol").change(function () {
    $("#busqueda-icono").click();
});
