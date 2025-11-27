var loadedTabs = {};

$(document).ready(function () {
    $(".navbar-minimalize").click();
    
    // Cargar filtros guardados en localStorage
    var fechaDesde = localStorage.getItem('dashboard_fecha_desde');
    var fechaHasta = localStorage.getItem('dashboard_fecha_hasta');
    
    if (fechaDesde) {
        $("#fecha_desde").val(fechaDesde);
    } else {
        // Valor por defecto si no hay guardado
        var fechaDefaultDesde = new Date();
        fechaDefaultDesde.setDate(fechaDefaultDesde.getDate() - 30);
        $("#fecha_desde").val(fechaDefaultDesde.toISOString().split('T')[0]);
    }
    
    if (fechaHasta) {
        $("#fecha_hasta").val(fechaHasta);
    } else {
        // Valor por defecto si no hay guardado
        var fechaDefaultHasta = new Date();
        $("#fecha_hasta").val(fechaDefaultHasta.toISOString().split('T')[0]);
    }
    
    // Cargar el dashboard inicial
    loadDashboard();
    
    // Event listener para aplicar filtros globales
    $("#aplicarFiltrosGlobales").click(function() {
        // Guardar filtros en localStorage
        localStorage.setItem('dashboard_fecha_desde', $("#fecha_desde").val());
        localStorage.setItem('dashboard_fecha_hasta', $("#fecha_hasta").val());
        aplicarFiltrosGlobales();
    });
    
    // Event listener para cambio de tabs - siempre cargar al hacer clic
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
        if (target === '#tab-ejecutivo') {
            loadDashboard();
        } else if (target === '#tab-subidas') {
            loadSubidas('dia');
        } else if (target === '#tab-descargas') {
            loadDescargas('dia');
        } else if (target === '#tab-usuarios') {
            loadUsuarios('dia');
        } else if (target === '#tab-ot') {
            loadOT('dia');
        } else if (target === '#tab-proyectos') {
            loadProyectosTab(0, 'dia');
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
    } else if (tabActivo === '#tab-descargas') {
        loadDescargas('dia');
    } else if (tabActivo === '#tab-usuarios') {
        loadUsuarios('dia');
    } else if (tabActivo === '#tab-ot') {
        loadOT('dia');
    } else if (tabActivo === '#tab-proyectos') {
        loadProyectosTab(0, 'dia');
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

function loadDescargas(granularidad) {
    var parametros = {
        funcion: 'getActividadDescargas',
        fecha_desde: $("#fecha_desde").val(),
        fecha_hasta: $("#fecha_hasta").val(),
        granularidad: granularidad || 'dia'
    };
    
    $.ajax({
        type: "POST",
        url: 'controller/dashboard.controller.php',
        data: parametros,
        success: function (datos) {
            $("#content-descargas").html(datos);
        },
        error: function () {
            $("#content-descargas").html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar los datos de descargas</div>');
        }
    });
}

function loadUsuarios(granularidad) {
    var parametros = {
        funcion: 'getDashboardUsuarios',
        fecha_desde: $("#fecha_desde").val(),
        fecha_hasta: $("#fecha_hasta").val(),
        granularidad: granularidad || 'dia'
    };
    
    $.ajax({
        type: "POST",
        url: 'controller/dashboard.controller.php',
        data: parametros,
        success: function (datos) {
            $("#content-usuarios").html(datos);
        },
        error: function () {
            $("#content-usuarios").html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar los datos de usuarios</div>');
        }
    });
}

function loadOT(granularidad) {
    var parametros = {
        funcion: 'getOrdenesTrabajo',
        fecha_desde: $("#fecha_desde").val(),
        fecha_hasta: $("#fecha_hasta").val(),
        granularidad: granularidad || 'dia'
    };
    
    $.ajax({
        type: "POST",
        url: 'controller/dashboard.controller.php',
        data: parametros,
        success: function (datos) {
            $("#content-ot").html(datos);
        },
        error: function () {
            $("#content-ot").html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar los datos de OT</div>');
        }
    });
}

function loadProyectosTab(proyecto, granularidad) {
    var parametros = {
        funcion: 'getProyectos',
        fecha_desde: $("#fecha_desde").val(),
        fecha_hasta: $("#fecha_hasta").val(),
        proyecto_seleccionado: proyecto || 0,
        granularidad: granularidad || 'dia'
    };
    
    $.ajax({
        type: "POST",
        url: 'controller/dashboard.controller.php',
        data: parametros,
        success: function (datos) {
            $("#content-proyectos").html(datos);
        },
        error: function () {
            $("#content-proyectos").html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error al cargar los datos de proyectos</div>');
        }
    });
}
