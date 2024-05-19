<!-- -- 01 categoria -->
<?php
	include("conexion.php");
	session_start();
	if(!isset($_SESSION['id_usuario'])) {
		header("location: index.php");
	}
	$iduser = $_SESSION['id_usuario'];

    // Consulta usuario
	$sql = "SELECT id,usuario
			FROM usuario
			WHERE id = '$iduser'";
	$resultado = $conexion -> query ($sql);
    while ($fila = mysqli_fetch_array($resultado)) {
        $usuarioid = $fila[0];
        $usuario = $fila[1];
    }

    // Consulta categoria
    $sqlcategoria = 
    "   SELECT id,categoria
        FROM categoria
        WHERE estatus = 1
    ";
    $resultadocategoria = $conexion->query($sqlcategoria);
    $filascategoria = $resultadocategoria->num_rows;
    
    if (isset($_POST["registrarcategoria"])) {
        echo "<script> </script>";
        $categoriaclave = mysqli_real_escape_string($conexion, $_POST['clavecategoria']);
        $sqlcategoria = "SELECT categoria FROM categoria WHERE categoria = '$categoriaclave'";
        $resultadocategoria = $conexion->query($sqlcategoria);
        $filas = $resultadocategoria->num_rows;
        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = date('Y-m-d H:i:s');
        $activo = 1;
        if ($filas > 0) {
            echo "
            <script>
                alert('La categoria ya existe');
                window.location = 'categoria.php'; 
            </script>
        ";
        } else {
            // Inserta registro
            $sqlcategoria = "INSERT INTO categoria (
                                    categoria, 
                                    usuario_id,
                                    fecha_alta,
                                    fecha_modificacion,
                                    estatus
                                    )
                            VALUES ('$categoriaclave'
                                    ,'$usuarioid'
                                    ,'$fecha_actual'
                                    ,'$fecha_actual'
                                    , '$activo')";
            $resultadocategoria = $conexion->query($sqlcategoria);
            if($resultadocategoria > 0) {
                echo "
                <script> 
                    alert('Registro exitoso');
                    window.location = 'categoria.php';     
                </script>
                ";
            } else {
                echo "
                <script> 
                    alert('No se registro la categoria');
                    window.location = 'categoria.php';     
                </script>
                ";
            }
        }
    }
    // Regresar
    if (isset($_POST["regresar"])) {
        echo "
        <script>
            window.location = 'admin.php';
        </script>
        ";
    }
?>	
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>

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
        <div >
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="center">
                        <h1>
                            <i class="ace-icon fa fa-dot-circle-o green"></i>
                            <span class="green">Categoria</span>
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
                                    <label class="col-sm-2 col-form-label">Clave</label>
                                    <div class="col-sm-10">
                                        <input type="text"  class="form-control-plaintext" name="clavecategoria" id="clavecategoria" value="clavecategoria">
                                    </div>
                                </div>
                                <button type="submit" name ="registrarcategoria" class="btn btn-primary">
                                    <span class="bigger-110">Registrar</span>
                                    <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                </button>
                                <button type="submit"  name ="regresar" class="btn btn-primary">
                                <span class="bigger-110">Regresar</span>
                                </button>
                                <div>
                                    <br>
                                </div>
                                <!-- 02 categoria -->
                                <?php
                                    if ($filascategoria > 0) {
                                        echo "<div class='table-responsive'>";
                                        echo "<table class='table table-striped'>";
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th>";
                                        echo "ID Categoria";
                                        echo "</th>";
                                        echo "<th>";
                                        echo "Clave categoria";
                                        echo "</th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "</tbody>";
                                        while ($filascategoria = mysqli_fetch_array($resultadocategoria)) {
                                            echo "<tr>";
                                            echo "<td>" . $filascategoria[0] . "</td>";
                                            echo "<td>" . $filascategoria[1] . "</td>";
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