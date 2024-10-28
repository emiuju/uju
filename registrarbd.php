<?php
    include("conexion.php"); /* conectamos la base de datos */

    /*La variable $_SERVER es una variable de entorno superglobal que contiene toda la información sobre los encabezados, rutas y ubicaciones de los scripts.
    $_SERVER['REQUEST_METOD'], almacena el metodo HTTP con el cual se hizo la solicitud acutal, los más comunes son POST Y GET
    $_POST es una variable super global, la cual especifíca que almacena los datos enviados a través del formulario */

    if ($_SERVER['REQUEST_METHOD']==='POST') {
        $username_registro=$_POST['username_registro']; 
        $email_registro=$_POST['email_registro'];
        $pass_registro=$_POST['pass_registro'];
        $confirm_pass=$_POST['confirm_pass'];

    // verificar que las contraseñas coincidan//
        
    if ($pass_registro===$confirm_pass) {
            $password=password_hash($pass_registro, PASSWORD_BCRYPT); //ESTA FUNCION PERMITE ENCRIPTAR EL PASSWORD.

        /* prueba imprimir los datos antes de insertarlos a la BD
        echo "username: $username_registro <br>";
        echo "email: $email_registro <br>";
        echo "contraseña: $password <br>";*/

        $chequear_User=$conn->prepare("SELECT * FROM registro WHERE username_registro=?");
        $chequear_User->bind_param("s",$
        $username_registro); //vincular el parametro $username_registro
        $chequear_User->execute(); //ejecuta la consulta
        $resultado= $chequear_User->get_result(); //obtiene el resultado de la consulta

        if ($resultado->num_rows>0) {
            //si el nombre de usuario ya existe
            echo '<script>alert("El nombre de usuario ya existe. Por favor elija otro. ");</script>';
            echo '<script>window.locatio.href="form_registrarse.php";</script>';
            
        }//cierra el if cuando existe el nombre de usuario
        else {
            //Si no existe el usuario, inserta a la base de datos


            $insertar_bd=$conn->prepare("INSERT INTO registro ( username_registro, email_registro, pass_registro) VALUES (?,?,?)");

            if ($insertar_bd->execute ([$username_registro, $email_registro, $password])) {
                header("location:form_login.php");
         } //redirecciona a la pagina del logeo
            else {
                echo "Error".$insertar_bd->error;
            }
            {
            else {
                echo '<script>alert("Las contraseñas no coinciden")</script>';
                echo '<script>window.location.href="form_registrarse.php"</script>';
            }
    } /*Cierra el if de comparación de las contraseñas*/




    } /*Cierra el if principal de la comparación del método POST */

?>