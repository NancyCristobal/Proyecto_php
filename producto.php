<?php
    include("conexion.php");
    session_start();
    if(!isset($_SESSION['id_usuario'])) {
        header("location: index.php");
    }
    $iduser = $_SESSION['id_usuario'];

    $sql = "SELECT id,usuario
            FROM usuario
            WHERE id = '$iduser'
    ";
    $resultado = $conexion->query($sql);
    while($fila = mysqli_fetch_array($resultado)) {
        $usuarioid  = $fila[0];
        $usuario    = $fila[1];
    }
    // Consulta categoria
    $sqlcategoria = "SELECT id,categoria
                    FROM categoria
                    WHERE estatus = 1
    ";
    $resultadocategoria = $conexion->query($sqlcategoria); 
    $filascategoria = $resultadocategoria->num_rows; 

    // Consulta unidad medida
    $sqlunidadmedida = "SELECT id,unidad_medida
                        FROM unidad_medida
                        WHERE estatus = 1
    ";
    $resultado_unidad_medida = $conexion->query($sqlunidadmedida);  
    $filas_unidad_medida = $resultado_unidad_medida->num_rows; 

    // Consulta producto
    $sqlproducto = "SELECT id,producto, precio_compra, precio_venta, imagen
                    FROM producto
                    WHERE estatus = 1
    ";
    $resultadoproducto = $conexion->query($sqlproducto);  
    $filasproducto = $resultadoproducto->num_rows; 
    // Registrar producto
    if (isset($_POST["registrarproducto"])) {
        // mysqli_real_escape_string()
        // Esta función se usa para crear una cadena SQL legal que se puede usar en una sentencia SQL
        $productoid = mysqli_real_escape_string($conexion, $_POST['productoid']);
        $codigobarras = mysqli_real_escape_string($conexion, $_POST['codigobarras']);
        $categoriaid = mysqli_real_escape_string($conexion, $_POST['categoriaid']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $preciocompra = mysqli_real_escape_string($conexion, $_POST['preciocompra']);
        $precioventa = mysqli_real_escape_string($conexion, $_POST['precioventa']);
        $unidadmedidaid = mysqli_real_escape_string($conexion, $_POST['unidad_medidaid']);
        $imagen = mysqli_real_escape_string($conexion, $_FILES['imagen']['name']); // Contiene el nombre del archivo
        $archivo = mysqli_real_escape_string($conexion, $_FILES['imagen']['tmp_name']); // Contiene el archivo

        $ruta = "images/" . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

        date_default_timezone_set('America/Mexico_City');
        $fecha_actual = date('Y-m-d H:i:s');
        $activo = true;

        $sqlproducto = "SELECT producto FROM producto WHERE id = '$productoid'";
        $resultadoproducto = $conexion->query($sqlproducto);
        $filas = $resultadoproducto->num_rows;

        if ($filas > 0) {
            echo "
                <script>
                    alert('El producto ya existe');
                    window.location = 'producto.php';
                </script>
            ";
        } else {
            $sqlproducto = 
            "INSERT INTO producto (
                producto,
                codigo_barras,
                categoria_id,
                descripcion,
                precio_compra,
                precio_venta,
                unidad_medida_id,
                imagen,
                usuario_id,
                fecha_alta,
                fecha_modificacion,
                estatus)
                VALUES (
                    '$productoid'
                    ,'$codigobarras'
                    ,'$categoriaid'
                    ,'$descripcion'
                    ,'$preciocompra'
                    ,'$precioventa'
                    ,'$unidadmedidaid'
                    ,'$ruta'
                    ,'$usuarioid'
                    ,'$fecha_actual'
                    ,'$fecha_actual'
                    ,'$activo')
            ";
            $resultadoproducto = $conexion->query($sqlproducto);
            if ($resultadoproducto > 0) {
                echo "
                    <script>
                        alert('Registro exitoso');
                        window.location = 'producto.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('No se registro el producto');
                        window.location = 'producto.php';
                    </script>
                ";
            }

        }

    }
    // Regresar
    if (isset($_POST["regresar"])) {
        echo"
            <script>
                window.location = 'admin.php'
            </script>
        ";
    }
?>
<!-- -- 01 producto -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>

    <meta name="description" content="User login page" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="assets/css/ace.min.css" />

    <link rel="stylesheet" href="assets/css/ace-rtl.min.css"/>
</head>

<body>
    <div class="main-container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="ace-icon fa fa-dot-circle-o green"></i>
                            <span class="green">Productos</span>
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
                            <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                                <div id="signup-box" class="signup-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header green lighter bigger">
                                                <i class="ace-icon fa fa-users blue"></i>
                                                Registro de Nuevos Productos
                                            </h4>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Clave</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control-plaintext" name="productoid" id="productoid" value="clave producto">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Codigo Barras</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control-plaintext" name="codigobarras" id="codigobarras" value="codigo de barras">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Categoria</label>
                                                <div class="col-sm-9">
                                                    <span class="block input-icon input-icon-right">
                                                        <select name="categoriaid">
                                                            <option value="0" class="form-control" placeholder="Categoria de Producto">Categoria de producto:</option>
                                                            <!-- -- 02 producto -->
                                                            <?php
                                                                while($fila = mysqli_fetch_array($resultadocategoria)) {
                                                                    echo '<option value="' .$fila[0] .'">' . $fila[1] . '</option>';
                                                                }
                                                            ?> 
                                                        </select>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Descripción</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control-plaintext" name="descripcion" id="descripcion" value="Descripcion">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Precio Compra</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control-plaintext" name="preciocompra" id="preciocompra" value="precio de compra">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Precio Venta</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control-plaintext" name="precioventa" id="precioventa" value="precio de venta">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Unidad Medida</label>
                                                <div class="col-sm-9">
                                                    <span class="block input-icon input-icon-right">
                                                        <select name="unidad_medidaid">
                                                            <option value="0" class="form-control" placeholder="Unidad de Medida">Unidad de Medida:</option>
                                                            <!-- -- 03 producto -->
                                                            <?php
                                                                while($fila = mysqli_fetch_array($resultado_unidad_medida)) {
                                                                    echo '<option value="' .$fila[0] .'">' . $fila[1] . '</option>';
                                                                }
                                                            ?>  
                                                        </select>
                                                    </span>
                                                </div>
                                                </label>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Imagen</label>
                                                <div class="col-sm-9">
                                                    <input type="file" class="form-control-plaintext" name="imagen" id="imagen" value="Imagen">
                                                </div>
                                            </div>
                                            <button type="submit" name="registrarproducto" class="btn btn-primary">
                                                <span class="bigger-110">Registrar</span>
                                                <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                            </button>
                                            <button type="submit" name="regresar" class="btn btn-primary">
                                                <span class="bigger-110">Regresar</span>
                                            </button>
                                            <div>
                                                <br>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -- 04 producto -->
                                    <?php
                                        if ($filasproducto > 0) {
                                            echo "<div class='table-responsive'>";
                                            echo    "<table class='table table-striped'>";
                                            echo        "<thead>";
                                            echo            "<tr>";
                                            echo            "<th>";
                                            echo                "Clave";
                                            echo            "</th>";
                                            echo            "<th>";
                                            echo                "Descripcion";
                                            echo            "</th>";
                                            echo            "<th>";
                                            echo                "Precio compra";
                                            echo            "</th>";
                                            echo            "<th>";
                                            echo                "Precio venta";
                                            echo            "</th>";
                                            echo            "<th>";
                                            echo                "Imagen";
                                            echo            "</th>";
                                            echo            "</tr>";
                                            echo        "</thead>";
                                            echo        "</tbody>";
                                            while ($filasproducto  = mysqli_fetch_array($resultadoproducto)) {
                                                echo        "<tr>";
                                                echo           "<td>" . $filasproducto[0] . "</td>";
                                                echo           "<td>" . $filasproducto[1] . "</td>";
                                                echo           "<td>" . $filasproducto[2] . "</td>";
                                                echo           "<td>" . $filasproducto[3] . "</td>";
                                                echo           "<td><img src='".$filasproducto[4] . "' width='30' heigth='30'></td>";
                                                echo        "</tr>";
                                            }
                                            echo            "<tr>";
                                            echo            "</tr>";

                                            echo        "</tbody>";
                                            echo    "</table>";
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