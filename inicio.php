<?php
session_start();

if (!($_SESSION["permisos"][basename(__FILE__, '.php') . ".php"]["access"])) {
    header("Location: cerrar.php");
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/index.controller.php";
$controlador = IndexController::singleton_index();
$texto_inicio = $controlador->getTextoInicio();

$_SESSION['menu'] = "inicio.php";

$_SESSION['breadcrumb'] = "Inicio";

$titlepage = "Giuliani - Inicio";

include 'inc/html/encabezado.php';

?>

<style>
h2{
  text-align:center;
  padding: 20px;
}
/* Slider */

.slick-slide {
    margin: 0px 20px;
}

.slick-slide img {
    width: 100%;
}

.slick-slider
{
    position: relative;
    display: block;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
            user-select: none;
    -webkit-touch-callout: none;
    -khtml-user-select: none;
    -ms-touch-action: pan-y;
        touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
}

.slick-list
{
    position: relative;
    display: block;
    overflow: hidden;
    margin: 0;
    padding: 0;
}
.slick-list:focus
{
    outline: none;
}
.slick-list.dragging
{
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list
{
    -webkit-transform: translate3d(0, 0, 0);
       -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
         -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
}

.slick-track
{
    position: relative;
    top: 0;
    left: 0;
    display: block;
}
.slick-track:before,
.slick-track:after
{
    display: table;
    content: '';
}
.slick-track:after
{
    clear: both;
}
.slick-loading .slick-track
{
    visibility: hidden;
}

.slick-slide
{
    display: none;
    float: left;
    height: 100%;
    min-height: 1px;
}
[dir='rtl'] .slick-slide
{
    float: right;
}
.slick-slide img
{
    display: block;
}
.slick-slide.slick-loading img
{
    display: none;
}
.slick-slide.dragging img
{
    pointer-events: none;
}
.slick-initialized .slick-slide
{
    display: block;
}
.slick-loading .slick-slide
{
    visibility: hidden;
}
.slick-vertical .slick-slide
{
    display: block;
    height: auto;
    border: 0px solid transparent;
}
.slick-arrow.slick-hidden {
    display: none;
}
</style>

<div class="container" style="text-align: center;">
    <img src="imagenes/inicio.png" height="300">
    <div style="margin-top: 25px; font-size: 20px; font-weight: bolder;">
        <?php echo $texto_inicio; ?>
    </div>
    <!--
    <h2>Algunos de nuestros clientes...</h2>
    <section class="customer-logos slider" style="height: 190px;">
        <div class="slide"><img src="imagenes/logos/l1.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l2.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l3.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l4.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l5.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l6.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l7.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l8.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l9.jpg"></div>
        <div class="slide"><img src="imagenes/logos/l0.jpg"></div>
    </section>
    -->
</div>

<?php
include 'inc/html/footer.php';
?>

<script>
    $(document).ready(function(){
        $('.customer-logos').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2500,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 4
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 3
                }
            }]
        });
        $(".navbar-minimalize").click();
    });
</script>