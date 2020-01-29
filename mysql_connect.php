
<?php
//$id = $_GET;
try {
    //metodo query
    $conexion = new PDO('mysql:host=localhost;dbname=prueba_consola', 'root', '');
    $statement = $conexion->prepare("INSERT INTO user VALUES(null,'Luis Calla Mostacero','MasterMas@outlook.com')");
    $statement->execute();
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}

?>