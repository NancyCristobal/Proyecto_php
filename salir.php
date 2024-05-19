<!-- -- 01 salir -->
<?php
    session_start();
    session_destroy();
    header("Location: index.php");
?>  