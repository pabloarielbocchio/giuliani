				<br /><br /><br />					
                                <div id="footer" name="footer" class="footer hidden-xs" style="position: fixed;">
					<div class="pull-right">
                                            
						<strong>Giuliani Hermanos S.A.</strong>
						<strong>&copy; <?php echo date("Y"); ?> | </strong>Todos los derechos reservados
					</div>
					<div>
					</div>
				</div>
			
			</div>
		</div>
	
		<!-- Mainly scripts -->
                <?php 
                if ($_SESSION['timeline'] == 1) { 
                    unset($_SESSION['timeline']); 
                } else { 
                    echo '<script src="inc/js/jquery-3.2.1.js"></script>';
                } ?>
		<script src="inc/bootstrap/js/select2.min.js"></script>
		<script src="inspinia/js/bootstrap.min.js"></script>
		<script src="inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="inspinia/js/plugins/slick/slick.min.js"></script>

		<!-- Custom and plugin javascript -->
		<script src="inspinia/js/inspinia.js"></script>
		<script src="inspinia/js/plugins/pace/pace.min.js"></script>
                
                <!-- Flot -->
                <script src="inspinia/js/plugins/flot/jquery.flot.js"></script>
                <script src="inspinia/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
                <script src="inspinia/js/plugins/flot/jquery.flot.spline.js"></script>
                <script src="inspinia/js/plugins/flot/jquery.flot.resize.js"></script>
                <script src="inspinia/js/plugins/flot/jquery.flot.pie.js"></script>
                <script src="inspinia/js/plugins/flot/jquery.flot.symbol.js"></script>
                <script src="inspinia/js/plugins/flot/jquery.flot.time.js"></script>

                <!-- Peity -->
                <script src="inspinia/js/plugins/peity/jquery.peity.min.js"></script>
                
                <script src="inspinia/js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script>
                <script src="inspinia/js/plugins/jasny/jasny-bootstrap.min.js"></script>
                
		<!-- Timer plugin javascript -->
		<script src="inspinia/js/plugins/idle-timer/idle-timer.min.js"></script>
                
                <script src="inspinia/js/plugins/select2/select2.full.min.js"></script>
                
                <!-- iCheck -->
                <script src="inspinia/js/plugins/iCheck/icheck.min.js"></script>
                
                <script>
                    $(".select2").select2({
                        dropdownParent: $("#dataUpdate")
                    });
                    $(document).ready(function (){
                        //updateBell(1);
                        $('.i-checks').iCheck({
                            checkboxClass: 'icheckbox_square-green',
                            radioClass: 'iradio_square-green',
                        });
                    });
                    /*
                    function updateBell(inicio = 0) {
                        var parametros = {
                            funcion: "updateBell"
                        }
                        $.ajax({
                            type: "POST",
                            url: 'controller/index.controller.php',
                            data: parametros,
                            success: function (datos) {
                                $('#audio').get(0).pause();
                                if (datos > 0){
                                    $('#audio').get(0).loop = true;
                                    $('#audio').get(0).play();
                                    if ($(".bell_nuevos_pedidos").html() !== datos){
                                        if (inicio === 0){
                                            $(".cartel_nuevos_pedidos").css("display", "block");
                                        }
                                    }
                                    $(".bell_nuevos_pedidos").css("display", "block");
                                    $(".bell_nuevos_pedidos").html(datos);
                                } else {
                                    $(".bell_nuevos_pedidos").css("display", "none");
                                    $(".bell_nuevos_pedidos").html("");
                                }
                            },
                            error: function () {
                                alert("Error");
                            }
                        });
                    }
            
                    $(".bell_nuevos_pedidos").click(function () {
                        window.location.href = "pedidos.php";
                    });
    
                    setInterval(function(){
                        updateBell();
                    }, 15000);
                     */
                </script>
    </body>
</html>