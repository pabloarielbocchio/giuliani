var loadedTabs = {};

$(document).ready(function () {
    $(".navbar-minimalize").click();
    
    // Cargar el dashboard inicial
    loadDashboard();
    
    // Event listener para aplicar filtros globales
    $("#aplicarFiltrosGlobales").click(function() {
        aplicarFiltrosGlobales();
    });
    
    // Event listener para cambio de tabs - siempre cargar al hacer clic
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
        if (target === '#tab-ejecutivo') {
            loadDashboard();
        } else if (target === '#tab-subidas') {
            loadSubidas('dia');
        }
    });
});

function aplicarFiltrosGlobales() {
    // Recargar el tab activo con los nuevos filtros
    var tabActivo = $('.nav-tabs li.active a').attr('href');
    if (tabActivo === '#tab-ejecutivo') {
        loadDashboard();
    } else if (tabActivo === '#tab-subidas') {
        loadSubidas('dia');
    }
}

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


function loadSubidas(granularidad) {
    var parametros = {
        funcion: 'getActividadSubidas',
        fecha_desde: $("#fecha_desde").val(),
        fecha_hasta: $("#fecha_hasta").val(),
        usuario: '0',
        proyecto: 0,
        tipo_archivo: '',
        granularidad: granularidad || 'dia'
    };
    
    $.ajax({
        type: "POST",
        url: 'controller/dashboard.controller.php',
        data: parametros,
        success: function (datos) {
            $("#content-subidas").html(datos);
        },
        error: function () {
            $("#content-subidas").html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar los datos de subidas</div>');
        }
    });
}
