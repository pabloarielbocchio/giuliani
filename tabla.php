<?php
session_start();

$_SESSION['menu'] = "inicio.php";

$_SESSION['breadcrumb'] = "Inicio";

$titlepage = "Giuliani - Inicio";

include 'inc/html/encabezado.php';

?>

<style>

.divespecial {
  overflow: scroll;
  position: relative;
}

table {
  position: relative;
  border-collapse: collapse;
}

td,
th {
  padding: 0.25em;
}

thead th {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  top: 0;
  background: #000;
  color: #FFF;
}

thead th:first-child {
  left: 0;
  z-index: 1;
}

tbody th {
  position: -webkit-sticky; /* for Safari */
  position: sticky;
  left: 0;
  background: #FFF;
  border-right: 1px solid #CCC;
}

</style>

<?php
include 'inc/html/footer.php';
?>
