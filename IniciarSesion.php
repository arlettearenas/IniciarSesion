<?php
session_start();
require('conexion.php');

if(isset($_POST['Usuario']) && isset($_POST['Clave'])){
    $Usuario = $_POST['Usuario'];
    $Clave = $_POST['Clave'];

    if(empty($Usuario)){
        header("Location: index.php?error=El usuario es requerido");
        exit();
    } elseif(empty($Clave)) {
        header("Location: index.php?error=La clave es requerida");
        exit();
    } else {
        // No es necesario usar md5, usa métodos seguros de cifrado de contraseñas
        $Sql = "SELECT * FROM usuarios WHERE Usuario = ? LIMIT 1";
        $stmt = $conexion->prepare($Sql);
        $stmt->bind_param("s", $Usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $row = $result->fetch_assoc();
            // Verifica la contraseña utilizando password_verify()
            if (password_verify($Clave, $row['Clave'])){
                $_SESSION['Usuario'] = $row['Usuario'];
                $_SESSION['Nombre_Completo'] = $row['Nombre_Completo'];
                $_SESSION['Id'] = $row['Id'];
                header("Location: inicio.php");
                exit();
            } else {
                header("Location: index.php?error=Usuario o clave incorrecta");
                exit();
            }
        } else {
            header("Location: index.php?error=Usuario o clave incorrecta");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
