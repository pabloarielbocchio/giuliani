<?php

session_start();

$_SESSION['menu'] = "pdf.php";

$_SESSION['breadcrumb'] = "PDF";

$titlepage = "Visor PDF - Giuliani";

include 'inc/html/encabezado.php';

//include 'inc/html/menu.php';

//include 'inc/html/breadcrumb.php';

$archivo_id = $_GET["archivo"];

include_once $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/index.controller.php";
$controlador = IndexController::singleton_index();
$archivo = $controlador->getArchivo($archivo_id);

?>

<div class="text-center pdf-toolbar">
    <div id="info" codigo="<?php echo $archivo["codigo"]; ?>" ruta="<?php echo $archivo["ruta"]; ?>" archivo="<?php echo $archivo["descripcion"]; ?>"></div>

    <?php if (in_array($_SESSION["rol"], [1,8])) { ?>
        <div style="margin-left: 80%;">
            <button style="background-color: orangered ; color: white;font-weight: bold; width: 100px; border: transparent; border-radius: 5px; vertical-align: middle;" type="submit" name="btnDescargar" id="btnDescargar">DESCARGAR</button> 
        </div>
    <?php } ?>

    <div class="btn-group">
        <button id="prev" class="btn btn-white"><i class="fa fa-long-arrow-left"></i> <span class="hidden-xs">Previous</span></button>
        <button id="next" class="btn btn-white"><i class="fa fa-long-arrow-right"></i> <span class="hidden-xs">Next</span></button>
        <button id="zoomin" class="btn btn-white"><i class="fa fa-search-minus"></i> <span class="hidden-xs">Zoom In</span></button>
        <button id="zoomout" class="btn btn-white"><i class="fa fa-search-plus"></i> <span class="hidden-xs">Zoom Out</span> </button>
        <button id="zoomfit" class="btn btn-white"> 100%</button>
        <span class="btn btn-white hidden-xs">Page: </span>

    <div class="input-group">
        <input type="text" class="form-control" id="page_num">

        <div class="input-group-btn">
            <button type="button" class="btn btn-white" id="page_count">100</button>
        </div>
    </div>

    </div>
</div>

<div class="text-center m-t-md">
    <canvas id="the-canvas" class="pdfcanvas border-left-right border-top-bottom b-r-md"></canvas>
</div>

<script src="inspinia/js/plugins/pdfjs/pdf.js"></script>

<?php

include 'inc/html/footer.php';

?>
<script>
    $(document).ready(function (){
        $(".navbar-minimalize").click();
    })
</script>


<script id="script">
//
// If absolute URL from the remote server is provided, configure the CORS
// header on that server.
//
var url = '<?php echo $archivo["ruta"]; ?>';


var pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null,
        scale = 1,
        zoomRange = 0.25,
        canvas = document.getElementById('the-canvas'),
        ctx = canvas.getContext('2d');

/**
 * Get page info from document, resize canvas accordingly, and render page.
 * @param num Page number.
 */
function renderPage(num, scale) {
    pageRendering = true;
    // Using promise to fetch the page
    pdfDoc.getPage(num).then(function(page) {
        var viewport = page.getViewport(scale);
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        // Render PDF page into canvas context
        var renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        var renderTask = page.render(renderContext);

        // Wait for rendering to finish
        renderTask.promise.then(function () {
            pageRendering = false;
            if (pageNumPending !== null) {
                // New page rendering is pending
                renderPage(pageNumPending);
                pageNumPending = null;
            }
        });
    });

    // Update page counters
    document.getElementById('page_num').value = num;
}

/**
 * If another page rendering in progress, waits until the rendering is
 * finised. Otherwise, executes rendering immediately.
 */
function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num,scale);
    }
}

/**
 * Displays previous page.
 */
function onPrevPage() {
    if (pageNum <= 1) {
        return;
    }
    pageNum--;
    var scale = pdfDoc.scale;
    queueRenderPage(pageNum, scale);
}
document.getElementById('prev').addEventListener('click', onPrevPage);

/**
 * Displays next page.
 */
function onNextPage() {
    if (pageNum >= pdfDoc.numPages) {
        return;
    }
    pageNum++;
    var scale = pdfDoc.scale;
    queueRenderPage(pageNum, scale);
}
document.getElementById('next').addEventListener('click', onNextPage);

/**
 * Zoom in page.
 */
function onZoomIn() {
    if (scale >= pdfDoc.scale) {
        return;
    }
    scale += zoomRange;
    var num = pageNum;
    renderPage(num, scale)
}
document.getElementById('zoomin').addEventListener('click', onZoomIn);

/**
 * Zoom out page.
 */
function onZoomOut() {
    if (scale >= pdfDoc.scale) {
        return;
    }
    scale -= zoomRange;
    var num = pageNum;
    queueRenderPage(num, scale);
}
document.getElementById('zoomout').addEventListener('click', onZoomOut);

/**
 * Zoom fit page.
 */
function onZoomFit() {
    if (scale >= pdfDoc.scale) {
        return;
    }
    scale = 1;
    var num = pageNum;
    queueRenderPage(num, scale);
}
document.getElementById('zoomfit').addEventListener('click', onZoomFit);


/**
 * Asynchronously downloads PDF.
 */
PDFJS.getDocument(url).then(function (pdfDoc_) {
    pdfDoc = pdfDoc_;
    var documentPagesNumber = pdfDoc.numPages;
    document.getElementById('page_count').textContent = '/ ' + documentPagesNumber;

    $('#page_num').on('change', function() {
        var pageNumber = Number($(this).val());

        if(pageNumber > 0 && pageNumber <= documentPagesNumber) {
            queueRenderPage(pageNumber, scale);
        }

    });

    // Initial/first page rendering
    renderPage(pageNum, scale);
});

$("#btnDescargar").click(function () {
    codigo = $('#info').attr("codigo");
    otp = $('#info').attr("otp");
    otd = $('#info').attr("otd");
    ruta = $('#info').attr("ruta");
    archivo = $('#info').attr("archivo");
    var parametros = {
        funcion: "addOt_evento",
        detalle: <?php echo $_SESSION["zip_otd"]; ?>,
        produccion: <?php echo $_SESSION["zip_otp"]; ?>,
        evento: 5,
        destino: 0,
        observaciones: "Descarga archivo " + archivo + " (" + ruta + ")"
    }
    $.ajax({
        type: "POST",
        url: 'controller/ot_eventos.controller.php',
        data: parametros,
        success: function (datos) {
            if (parseInt(datos) == 0) {                            
                var link=document.createElement('a');
                document.body.appendChild(link);
                link.download=archivo;
                link.href=ruta;
                link.click();
                //location.reload();
            } else {
                alert("Error");
            }
        },
        error: function () {
            alert("Error");
        }
    });
});
</script>