<!-- 01 unidad_medida -->
<?php
    include("conexion.php");
	session_start();
	if(!isset($_SESSION['id_usuario'])) {
		header("location: index.php");
	}
	$iduser = $_SESSION['id_usuario'];

    // Consulta unidadmedida
    $sqlunidadmedida = "SELECT id,unidad_medida
                        FROM unidad_medida
                        WHERE estatus = 1
    ";
    $resultado_unidad_medida = $conexion->query($sqlunidadmedida);
    $filas_unidad_medida = $resultado_unidad_medida->num_rows;

    // Consulta usuario
    $sql = "SELECT id,usuario
            FROM usuario
            WHERE id = '$iduser'
    ";
    $resultado = $conexion->query($sql);
    while ($fila = mysqli_fetch_array($resultado)) {
        $usuarioid  = $fila[0];
        $usuario    = $fila[1];
    }

    // 01 unidad medida
    if (isset($_POST["registrar_unidad_medida"])) {
        $unidadmedida = mysqli_real_escape_string($conexion, $_POST['unidadmedida']);
        $sqlunidadmedida = "SELECT unidad_medida
                            FROM unidad_medida
                            WHERE unidad_medida = '$unidadmedida'
        ";
        $resultado_unidad_medida = $conexion->query($sqlunidadmedida);
        $filas = $resultado_unidad_medida->num_rows;
        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = date('Y-m-d H:i:s');
        $activo = true;
        if($filas > 0 ) {
            echo "
            <script> 
                alert('La unidad de medida ya existe');
                window.location = 'unidadmedida.php';
            </script>
            ";
        } else {
            // Inserta registro
            $sqlunidadmedida = "INSERT INTO unidad_medida (unidad_medida,usuario_id,fecha_alta,fecha_modificacion,estatus)
                                VALUES ('$unidadmedida', '$usuarioid', '$fecha_actual', '$fecha_actual','$activo')
            ";
            $resultado_unidad_medida = $conexion->query($sqlunidadmedida);
            if ($resultado_unidad_medida > 0 ) {
                echo "
                <script>
                    alert('Registro exitoso');
                    window.location = 'unidad_medida.php';
                </script>
                ";
            } else {
                echo "
                <script>
                    alert('No se registro la unidad e medida');
                    window.location = 'unidad_medida.php';
                </script>
                ";

            }
        }
    
    }
    // Regresar
    if(isset($_POST["regresar"])) {
        echo "
        <script>
            window.location = 'admin.php'
        </script>
        ";
    }
?>
<!-- -- 01 unidad_medida -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unidades de Medida</title>

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

<body>
    <div class="main-container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="ace-icon fa fa-dot-circle-o green"></i>
                            <span class="green">Unidades de Medida</span>
                        </h1>
                        <h4 class="blue" id="id-company-text">&copy;Proyecto Modulo 8 - PHP</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="login-container">
                        <div class="lefth">
                            <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">Unidad</label>
                                    <div class="col-sm-10">
                                        <input type="text"  class="form-control-plaintext" name="unidadmedida" id="unidadmedida" value="unidad de medida">
                                    </div>
                                </div>
                                <button type="submit" name ="registrar_unidad_medida" class="btn btn-primary">
                                    <span class="bigger-110">Registrar</span>
                                    <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                </button>
                                <button type="submit"  name ="regresar" class="btn btn-primary">
                                    <span class="bigger-110">Regresar</span>
                                </button>
                                <div>
                                    <br>
                                    <br>
                                </div>
                                <!-- -- 02 unidad_medida -->
                                <?php
                                    if ($filas_unidad_medida > 0) {
                                        echo "<div class='table-responsive'>";
                                        echo "<table class='table table-striped'>";
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th>";
                                        echo "Id";
                                        echo "</th>";
                                        echo "<th>";
                                        echo "Unidad de Medida";
                                        echo "</th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "</tbody>";
                                        while ($filas_unidad_medida = mysqli_fetch_array($resultado_unidad_medida)) {
                                            echo "<tr>";
                                            echo "<td>" . $filas_unidad_medida[0] . "</td>";
                                            echo "<td>" . $filas_unidad_medida[1] . "</td>";
                                            echo "</tr>";
                                        }
                                        echo "<tr>";
                                        echo "</tr>";

                                        echo "</tbody>";
                                        echo "</table>";
                                        echo "</div>";
                                    }
                                ?>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>