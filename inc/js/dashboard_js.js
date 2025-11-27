var loadedTabs = {};

$(document).ready(function () {
    $(".navbar-minimalize").click();
    
    // Cargar el dashboard inicial
    loadDashboard();
    
    // Event listener para aplicar filtros
    $("#aplicarFiltros").click(function() {
        loadDashboard();
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

function loadDashboard() {
    var parametros = {
        funcion: 'getDashboardEjecutivo',
        fecha_desde: $("#fecha_desde").val(),
        fecha_hasta: $("#fecha_hasta").val(),
        proyecto: 0,
        usuario: '0'
    };
    
    $.ajax({
        type: "POST",
        url: 'controller/dashboard.controller.php',
        data: parametros,
        success: function (datos) {
            $("#content-dashboard").html(datos);
        },
        error: function () {
            $("#content-dashboard").html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar los datos del dashboard</div>');
        }
    });
}
