<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=e, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

  <?php 
  function validarCorreo($str)
  {
    return (false !== strpos($str, "@") && false !== strpos($str, "."));
  }

  function validaPostal ($cadena)
       {
          //Comrpobamos que realmente se ha añadido el formato correcto
         if(preg_match('/^[0-9]{5}$/i', $cadena)) 
             //La instruccion se cumple
             return true;
          else 
             //Contiene caracteres no validos
             return false;
       }
  $errores ="";
  $enviado ="";
  if(isset($_POST['enviar'])){ //si el botón esta setteado 
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $username = $_POST['username'];
    $correo= $_POST['correo'];
    $codigopostal = $_POST['codigopostal'];

      if(!empty($nombre)){ //si hay algun valor en la variable nombre
          $nombre = trim($nombre);
          $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);
              if (!preg_match("/^[a-zA-Z'-]+$/",$nombre)) {
                $errores .= "Tú nombre contiene caracteres inválidos o espacios.<br/>";
              } else if (strlen($nombre) > 18) {
              $errores .=  "Tú nombre solo puede contener hasta 18 caracteres.<br/>";
              } else if (strlen($nombre) < 3) {
              $errores .= "Tú nombre debe tener al menos 3 caracteres.<br/>";
              }
      }else{
        $errores .= "Debes ingresa tus nombres. <br/>";
      }

      if(!empty($apellido)){
              $apellido = trim($apellido);
              $apellido = filter_var($apellido,FILTER_SANITIZE_STRING);
              if (!preg_match("/^[a-zA-Z'-]+$/",$apellido)) {
                $errores.= "Tú apellido contienen caracteres inválidos o espacios. <br/>";
              } else if (strlen($apellido) > 36) {
                $errores.= "Tú apellido solo pueden contener hasta 36 caracteres. <br/>";
              } else if (strlen($apellido) < 3) {
                $errores.= "Tú apellido debe tener al menos 3 caracteres.<br/>";
              }
      }else {
        $errores .= "Debes ingresar tu apellido. <br/>";
      }
        
      if(!empty($username)){
        $username = trim($username);
        $username = htmlspecialchars($username);
        $username = filter_var($username,FILTER_SANITIZE_STRING);
      }else {
        $errores .= "Debes ingresar tu nombre de usuario. <br/>";
      }

      if(!empty($correo)){
        if(validarCorreo($correo)){
          $correo = filter_var($correo,FILTER_SANITIZE_EMAIL);
        }else{
          $errores .="Debes ingresar un correo valido.<br/>";
        }
      }else{
        $errores .="Debes ingresar tu correo.<br/>";
      }

      if(!empty($codigopostal)){
        if(!validaPostal($codigopostal)){
          $errores .="Código postal invalido.<br/>";
        }

      }else{
        $errores .="Debes ingresar tu código postal.<br/>";

      }

      if(!$errores){
        $enviado=true;
        try{
          //metodo query
         $conexion = new PDO('mysql:host=localhost;dbname=usersgame','root','');
       
         $statement= $conexion->prepare("INSERT INTO infousers (Name,Lastname,Username,Mial,Zipcode) values('$nombre','$apellido','$username','$correo','$codigopostal');");
 
         $statement->execute();
 
         $statement->fetchAll(); 


      }catch(PDOException $e){
          echo "ERROR: " .$e->getMessage();
      }
      }
  }
 
  ?>

</head>

<body class='Fondo'>
<img src="img/images.jfif" >
<nav  class="Formulario">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationServer01">Nombre</label>
      <input type="text" class="form-control " id="validationServer01" name="nombre" value="<?php if(!$enviado && isset($nombre)){echo $nombre;}else{echo "";} ?>">
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationServer02">Apellido</label>
      <input type="text" class="form-control " id="validationServer02" name="apellido" value="<?php if(!$enviado && isset($apellido)){echo $apellido;}else{echo "";} ?>">
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationServerUsername">Nombre de Usuario</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend3">@</span>
        </div>
        <input type="text" class="form-control " id="validationServerUsername" aria-describedby="inputGroupPrepend3" name="username" value="<?php if(!$enviado && isset($username)){echo $username;}else{echo "";} ?>"> 
      </div>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="validationServer03">Correo</label>
      <input type="text" class="form-control " id="validationServer03" name="correo" value="<?php if(!$enviado && isset($correo)){echo $correo;}else{echo "";} ?>">

    </div>
    
    <div class="col-md-3 mb-3">
      <label for="validationServer05">Codigo Postal</label>
      <input type="text" class="form-control" id="validationServer05" name="codigopostal" value="<?php if(!$enviado && isset($codigopostal)){echo $codigopostal;}else{echo "";} ?>">
   
    </div>
  </div>
    <?php if (!empty($errores)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $errores; ?>
      </div>
    <?php elseif($enviado):?>
      <div class="alert alert-success" role="alert">
        Felicidades todos tus datos se enviaron a la bdd.
      </div>
    <?php endif ?>


  <button class="btn btn-primary" type="submit" name="enviar" value="true" >Enviar</button>
</form>



</nav>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>