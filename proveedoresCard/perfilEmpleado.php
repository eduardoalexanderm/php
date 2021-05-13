<?php
    //Incluyendo el archivo de conexion a la base de datos
    require_once("db/dbcon.php");
    
    //Evaluando si esta definido un parametro "acc" y que su valor sea vacio
    if(isset($_REQUEST['acc'])=='')
    {
        //file_get_contents — Reads entire file into a string
        $pagina = file_get_contents("templates/perfilEmpleado.html"); //perfil
        //preg_replace($pattern, $replacement, $string);
        $pagina = preg_replace('/--nav--/', getNav(), $pagina);
        $pagina = preg_replace('/--alert--/', '', $pagina);
        $pagina = preg_replace('/--content--/', getInfo(), $pagina);

        echo $pagina;
    }
    else
    {
        //file_get_contents — Reads entire file into a string
        $pagina = file_get_contents("templates/perfilEmpleado.html");
        //preg_replace($pattern, $replacement, $string);
        $pagina = preg_replace('/--nav--/', getNav(), $pagina);
        $pagina = preg_replace('/--alert--/', getAlert(), $pagina);
        $pagina = preg_replace('/--content--/', getInfo(), $pagina);

        echo $pagina;
    }

    function getNav()
    {
        $active = '<li class="active"><a href="empleados.php">Lista de empleados</a></li>
        <li><a href="agregarEmpleado.php">Agregar Empleado</a></li>';

        $nav = file_get_contents("./nav.php", FILE_USE_INCLUDE_PATH);
        $nav = preg_replace('/--active--/', $active, $nav);
        return $nav;
    }

    function getAlert()
    {
        global $con;
        $alert;

        $idEmp = mysqli_real_escape_string($con,(strip_tags($_REQUEST["idEmp"],ENT_QUOTES)));
        $delete = mysqli_query($con, "DELETE FROM empleados WHERE idEmpleado=$idEmp");
        if($delete){
            $alert .= '<div class="alert alert-danger alert-dismissable">><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Empleado borrado con éxito</div>';
        }else{
            $alert .= '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Los datos no se borraron</div>';
        }    

        return $alert;
    }

    function getInfo()
    {
        global $con;
        $info;

        $idEmp = mysqli_real_escape_string($con,(strip_tags($_REQUEST["idEmp"],ENT_QUOTES)));
        $sql = mysqli_query($con, "SELECT * FROM empleados WHERE idEmpleado='$idEmp'");
        if(mysqli_num_rows($sql) == 0){
            header("Location: index.php");
        }else{
            $row = mysqli_fetch_assoc($sql);
            $date = date_create($row['fechaNacimiento']);

            $codigo		     = $row['codigo'];
            $nombre		     =  $row['nombre'];
            $lugarNacimiento	 =  $row['lugarNacimiento'];
            $fechaNacimiento	 =  date_format($date,'d/m/Y');
            $direccion	     = $row['direccion'];
            $telefono		 =  $row['telefono'];
            $cargo		 =  $row['cargo'];

            if($row['estado'] == 1)
                $estado = '<Adminstrativo';
            else if($row['estado'] == 2)
                $estado = 'Docente';
            else if($row['estado'] == 3)
                $estado = 'Outsourcing';

            $info = "<table class=\"table table-striped table-condensed\">
            <tr>
                <th width=\"20%\">Código</th>
                <td>$codigo</td>
            </tr>
            <tr>
                <th>Nombre del empleado</th>
                <td>$nombre</td>
            </tr>
            <tr>
                <th>Lugar y Fecha de Nacimiento</th>
                <td>$lugarNacimiento, $fechaNacimiento</td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td>$direccion</td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td>$telefono</td>
            </tr>
            <tr>
                <th>Puesto</th>
                <td>$cargo</td>
            </tr>
            <tr>
                <th>Estado</th>
                <td>
                    $estado
                </td>
            </tr>
            
        </table>
        
        <a href=\"index.php\" class=\"btn btn-sm btn-info\"><span class=\"glyphicon glyphicon-refresh\" aria-hidden=\"true\"></span> Regresar</a>
        <a href=\"editar.php?idEmp=$idEmp\" class=\"btn btn-sm btn-success\"><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"></span> Editar datos</a>
        <a href=\"perfil.php?acc=delete&idEmp=$idEmp\" class=\"btn btn-sm btn-danger\" onclick=\"return confirm('&iquest;Esta seguro de borrar los datos $nombre?')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span> Eliminar</a>";
        }

        return $info;
    }

?>