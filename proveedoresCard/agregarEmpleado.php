<?php
    //Incluyendo el archivo de conexion a la base de datos
    require_once("db/dbcon.php");
    
    //Evaluando si esta definido un parametro "acc" y que su valor sea vacio
    if(isset($_REQUEST['add'])=='')
    {
        //file_get_contents — Reads entire file into a string
        $pagina = file_get_contents("templates/agregarEmpleado.html");
        //preg_replace($pattern, $replacement, $string);
        $pagina = preg_replace('/--nav--/', getNav(), $pagina);
        $pagina = preg_replace('/--alert--/', '', $pagina);

        echo $pagina;
    }
    else
    {
        //file_get_contents — Reads entire file into a string
        $pagina = file_get_contents("templates/agregarEmpleado.html");
        //preg_replace($pattern, $replacement, $string);
        $pagina = preg_replace('/--nav--/', getNav(), $pagina);
        $pagina = preg_replace('/--alert--/', getAlert(), $pagina);

        echo $pagina;
    }

    function getNav()
    {
        $active = '<li><a href="proveedores.php">Lista de empleados</a></li>
        <li class="active"><a href="agregarEmpleado.php">Agregar Empleado</a></li>';

        $nav = file_get_contents("./nav.php", FILE_USE_INCLUDE_PATH);
        $nav = preg_replace('/--active--/', $active, $nav);
        return $nav;
    }

    function getAlert()
    {
        global $con;
        $alert;

        $codigo		     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["codigo"],ENT_QUOTES)));//Escanpando caracteres 
        $RazonSocial		     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["RazonSocial"],ENT_QUOTES)));//Escanpando caracteres 
        $representante	 = mysqli_real_escape_string($con,(strip_tags($_REQUEST["representante"],ENT_QUOTES)));//Escanpando caracteres 
        $telefono	 = mysqli_real_escape_string($con,(strip_tags($_REQUEST["telefono"],ENT_QUOTES)));//Escanpando caracteres 
        $movil	     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["movil"],ENT_QUOTES)));//Escanpando caracteres 
        $estado		 = mysqli_real_escape_string($con,(strip_tags($_REQUEST["estado"],ENT_QUOTES)));//Escanpando caracteres 
        

        $query = mysqli_query($con, "SELECT * FROM proveedores WHERE codigo='$codigo'");
        if(mysqli_num_rows($query) == 0){
                $insert = mysqli_query($con, "INSERT INTO proveedores(codigo, RazonSocial, representante, telefono, movil, estado)
                                                    VALUES('$codigo','$RazonSocial', '$representante', '$telefono', '$movil',  '$estado')") or die(mysqli_error());
                if($insert){
                    $alert.= '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bien hecho! Los datos han sido guardados con éxito.</div>';
                }else{
                    $alert.= '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
                }                
        }else{
            $alert.= '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. código existe!</div>';
        }

        return $alert;
    }

?>