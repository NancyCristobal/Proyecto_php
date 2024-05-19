<!-- -- index 01 -->
<?php
	include("conexion.php");
	//Valida Session
	$_SESSION = array();
	session_start();
	if (isset($_SESSION['id_usuario'])) {
		header("location: admin.php");
	}
	// Registrar usuario
	if (isset($_POST["registrar"])) {
		$rolid = mysqli_real_escape_string($conexion, $_POST['rolid']);
		$usuario = mysqli_real_escape_string($conexion, $_POST['user']);
		$password = mysqli_real_escape_string($conexion, $_POST['pass']);
		$password_encriptada = sha1($password);

		$sqluser = "SELECT id FROM usuario WHERE usuario = '$usuario'";
		$resultadouser = $conexion->query($sqluser);
		$filas = $resultadouser->num_rows;
		
		date_default_timezone_set('America/Mexico_City');
		$fecha_actual = date('Y-m-d H:i:s');
		$activo = 1;

		if ($filas > 0) {
			echo "
			<script>
				alert('El usuario ya existe') 
				window.location = 'index.php';
			</script>";
		} else {
			// Inserta registro
			$sqlusuario = "INSERT INTO usuario (
									usuario,
									clave,
									rolid,
									fecha_alta,
									fecha_modificacion,
									estatus)
							VALUES (
									'$usuario'
									,'$password_encriptada'
									,'$rolid'
									,'$fecha_actual'
									,'$fecha_actual'
									,'$activo')";
			$resultadousuario = $conexion->query($sqlusuario);
			if ($resultadousuario > 0) {

				echo "
				<script> 
					alert('Registro exitoso');
					window.location = 'index.php';
				</script>";
			} else {
				echo"
				<script>
					alert('No se registro el usuario');
					window.location ='index.php';
				</script>";
				echo "Falló la creación de la tabla: (" . $conexion->errno . ") " . $conexion->error;
			}
		}
	}
	//CONSULTAS ROLES
	$sqlrol = "SELECT id, rol FROM rol ";
	$resultadorol = $conexion->query($sqlrol);
	$filasrol = $resultadorol->num_rows;
	if($filasrol > 0) {
		// echo "consulta exitosa";
	} else {
		echo "
		<script>
			alert('No hay Roles');
			window.location = 'index.php';
		</script>";
	}
	
	//login usuario
	//if (!empty($_POST)) {
	if (isset($_POST["ingresar"])) {
		$usuario = mysqli_real_escape_string($conexion, $_POST['user']);
		$password = mysqli_real_escape_string($conexion, $_POST['pass']);
		$password_encriptada = sha1($password);
		$sql = "SELECT id
				FROM usuario
				WHERE usuario = '$usuario'
				AND clave = '$password_encriptada'";
		$resultado = $conexion->query($sql);
		$rows = $resultado->num_rows;
		if ($rows > 0) {
			// fetch_asoc(): Retorna un aray asociativo correspondiente a la fila obtenida o null si no hubiera más filas.
			$row = $resultado->fetch_assoc();
			$_SESSION['id_usuario'] = $row["id"];
			header("Location:admin.php");
		} else {
			echo "
			<script>
				alert('No existe el usuario');
				window.location = 'index.php';
			</script>";
		}		
	}	
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>Login - Sistema de Usuarios</title>

	<meta name="description" content="User login page" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

	<!-- text fonts -->
	<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

	<!-- ace styles -->
	<link rel="stylesheet" href="assets/css/ace.min.css" />

	<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
</head>

<body class="login-layout">
	<div class="main-container">
		<div class="main-content">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<div class="login-container">
						<div class="center">
							<h1>
								<i class="ace-icon fa fa-dot-circle-o green"></i>
								<span class="green">Ingreso de Usuarios </span>
							</h1>
							<h4 class="blue" id="id-company-text">&copy;Proyecto Modulo 8 - PHP</h4>
						</div>

						<div class="space-6"></div>

						<div class="position-relative">
							<div id="login-box" class="login-box visible widget-box no-border">
								<div class="widget-body">
									<div class="widget-main">
										<h4 class="header blue lighter bigger">
											<i class="ace-icon fa fa-key green"></i>
											Ingresa tu Informacion
										</h4>

										<div class="space-6"></div>

										<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
											<fieldset>
												<label class="block clearfix">
													<span class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="user" placeholder="Usuario" required />
														<i class="ace-icon fa fa-user"></i>
													</span>
												</label>

												<label class="block clearfix">
													<span class="block input-icon input-icon-right">
														<input type="password" name="pass" class="form-control" placeholder="Contraseña" required />
														<i class="ace-icon fa fa-lock"></i>
													</span>
												</label>

												<div class="space"></div>

												<div class="clearfix">
													<button type="submit" name="ingresar" class="width-35 pull-right btn btn-sm btn-primary">
														<i class="ace-icon fa fa-key"></i>
														<span class="bigger-110">Ingresar</span>
													</button>
												</div>

												<div class="space-4"></div>
											</fieldset>
										</form>
									</div><!-- /.widget-main -->

									<div class="toolbar clearfix">
										<div>
										</div>

										<div>
											<a href="#" data-target="#signup-box" class="user-signup-link">
												Nuevo Registro
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div>
								</div><!-- /.widget-body -->
							</div><!-- /.login-box -->

							<div id="signup-box" class="signup-box widget-box no-border">
								<div class="widget-body">
									<div class="widget-main">
										<h4 class="header green lighter bigger">
											<i class="ace-icon fa fa-users blue"></i>
											Registro de Nuevos Usuarios
										</h4>
										<div class="space-6"></div>
										<p>Ingresa los datos solicitados acontinuacion: </p>
										<form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
											<fieldset>
												<label class="block clearfix">
													<span class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="user" placeholder="Usuario" required />
														<i class="ace-icon fa fa-user"></i>
													</span>
												</label>
												<label class="block clearfix">
													<span class="block input-icon input-icon-right">
														<select name="rolid">
															<option 
															value="0" 
															class="form-control" 
															placeholder="Rol de Usuario">
																Rol de usuario:
															</option>
															<!-- -- 02 index -->
															<?php
																while ($fila = mysqli_fetch_array($resultadorol)) {
																	echo '<option value="' . $fila[0] . '">' . $fila[1] . '</option>';
																}
															?>
														</select>
													</span>
												</label>
												<label class="block clearfix">
													<span class="block input-icon input-icon-right">
														<input type="password" class="form-control" name="pass" placeholder="Password" required />
														<i class="ace-icon fa fa-lock"></i>
													</span>
												</label>

												<div class="space-24"></div>
												<div class="clearfix">
													<button type="reset" class="width-30 pull-left btn btn-sm">
														<i class="ace-icon fa fa-refresh"></i>
														<span class="bigger-110">Reset</span>
													</button>

													<button type="submit" name="registrar" class="width-65 pull-right btn btn-sm btn-success">
														<span class="bigger-110">Registrar</span>
														<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
													</button>
												</div>
											</fieldset>
										</form>
									</div>

									<div class="toolbar center">
										<a href="#" data-target="#login-box" class="back-to-login-link">
											<i class="ace-icon fa fa-arrow-left"></i>
											Regresar al Login
										</a>
									</div>
								</div><!-- /.widget-body -->
							</div><!-- /.signup-box -->
						</div><!-- /.position-relative -->
						<div class="navbar-fixed-top align-right">
							<br />
							&nbsp;
							<a id="btn-login-dark" href="#">Oscuro</a>
							&nbsp;
							<span class="blue">/</span>
							&nbsp;
							<a id="btn-login-blur" href="#">Azul</a>
							&nbsp;
							<span class="blue">/</span>
							&nbsp;
							<a id="btn-login-light" href="#">Claro</a>
							&nbsp; &nbsp; &nbsp;
						</div>						
					</div>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.main-content -->
	</div><!-- /.main-container -->

	<script src="assets/js/jquery-2.1.4.min.js"></script>

	<script type="text/javascript">
		if ('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
	</script>


	<script type="text/javascript">
		jQuery(function($) {
			$(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible'); //hide others
				$(target).addClass('visible'); //show target
			});
		});



		//Cambio de background
		jQuery(function($) {
			$('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');

				e.preventDefault();
			});
			$('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');

				e.preventDefault();
			});
			$('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');

				e.preventDefault();
			});

		});
	</script>
</body>

</html>