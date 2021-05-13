<?php
    //Incluyendo el archivo de conexion a la base de datos
    require_once("db/dbcon.php");
    
    //Evaluando si esta definido un parametro "acc" y que su valor sea vacio
    if(isset($_REQUEST['acc'])=='')
    {
        //file_get_contents � Reads entire file into a string
        $pagina = file_get_contents("templates/proveedores.html");
        //preg_replace($pattern, $replacement, $string);
        $pagina = preg_replace('/--nav--/', getNav(), $pagina);
        $pagina = preg_replace('/--alert--/', '', $pagina);
        $pagina = preg_replace('/--options--/', getOptions(), $pagina);
        $pagina = preg_replace('/--data--/', getData(), $pagina);
        $pagina = preg_replace('/--year--/', date("Y"), $pagina);

        echo $pagina;
    }
    else if(isset($_REQUEST['acc'])=='delete')
    {
        //file_get_contents � Reads entire file into a string
        $pagina = file_get_contents("templates/proveedores.html");
        //preg_replace($pattern, $replacement, $string);
        $pagina = preg_replace('/--nav--/', getNav(), $pagina);
        $pagina = preg_replace('/--alert--/', getAlert(), $pagina);
        $pagina = preg_replace('/--options--/', getOptions(), $pagina);
        $pagina = preg_replace('/--data--/', getData(), $pagina);
        $pagina = preg_replace('/--year--/', date("Y"), $pagina);

        echo $pagina;
    }

    function getNav()
    {
        $active = '<li class="active"><a href="proveedores.php">Lista de Proveedores</a></li>
        <li><a href="agregarEmpleado.php">Agregar</a></li>';

        $nav = file_get_contents("./nav.php", FILE_USE_INCLUDE_PATH);
        $nav = preg_replace('/--active--/', $active, $nav);
        return $nav;
    }

    function getOptions()
    {
        $options;
        //Operador ternario
        $filtro = (isset($_REQUEST['filtro']) ? strtolower($_REQUEST['filtro']) : NULL);

        //Evaluando el valor del filtro
        if($filtro == 1)
        {
            $options = '<option value="0">Filtros de datos del Proveedor</option><option value="1" selected>Internet</option><option value="2">Telefonia</option><option value="3">Cable</option>';
        }
        else if($filtro == 2)
        {
            $options = '<option value="0">Filtros de datos del Proveedor</option><option value="1">Internet</option><option value="2" selected>Telefonia</option><option value="3"> Cable</option>';
        }
        else if($filtro == 3)
        {
            $options = '<option value="0">Filtros de datos del Proveedor</option><option value="1">Internet</option><option value="2">Telefonia</option><option value="3">Cable </option>';
        }
		
		else
		{
			 $options = '<option value="0" selected>Filtros del Proveedor</option><option value="1">Internet</option><option value="2">Telefonia</option><option value="3"> Cable</option>';
			
		}
        return $options;
    }
	
	
		function getData()
    {
        global $con;
        $data;

        //Operador ternario
        $filtro = (isset($_REQUEST['filtro']) ? strtolower($_REQUEST['filtro']) : NULL);

        if($filtro==1){                                                        //estado
            $sql = mysqli_query($con, "SELECT * FROM proveedores WHERE codigo='P001'  ORDER BY idProveedor ASC"); //Si la opci�n seleccionada es Internet, entonces mostrar� toda la info., del proveedor que proporciona el internet
        }else if($filtro ==2){
            $sql = mysqli_query($con, "SELECT * FROM proveedores  WHERE codigo='P002' ORDER BY idProveedor ASC"); //Si la opci�n seleccionada es Telefonia, entonces mostrar� toda la info., de ese proveedor
        }
		else if($filtro ==3){
            $sql = mysqli_query($con, "SELECT * FROM proveedores  WHERE codigo='P003' ORDER BY idProveedor ASC");//Si la opci�n seleccionada es cable, entonces mostrar� toda la info., de ese proveedor
        }	 
		
		else if($filtro==0){
			
			 $sql = mysqli_query($con, "SELECT * FROM proveedores ORDER BY idProveedor ASC"); //Sino se selecciona ninguna de las anteriores, entonces mostrar� el listado de todos los proveedores
		}
        if(mysqli_num_rows($sql) == 0){
            $data.= '<tr><td colspan="8">No hay Proveedores registrados.</td></tr>';
        }else{
            
            while($row = mysqli_fetch_assoc($sql)){
       
                $data .= '
                <tr>
                    <td>'.$row['idProveedor'].'</td>

                    <td>'.$row['codigo'].'</td>
              
                    <td>'.$row['RazonSocial'].'</td>
                 
                    <td>'.$row['representante'].'</td>

                    <td>'.$row['telefono'].'</td>

                    <td>'.$row['movil'].'</td>				
                 
                    <td>';
                    if($row['estado'] == "1"){
                        $data .= '<span class="label label-success">Internet</span>';
                    }
                    else if ($row['estado'] == "2" ){
                        $data .= '<span class="label label-info">Telefonia</span>';
                    }
                    else if ($row['estado'] == "3" ){
                        $data .= '<span class="label label-warning">Cable</span>';
                    }
					                                        //idEmp
				
                    $data .= '
                    </td>
                    <td>
                        <a href="editarEmpleado.php?idProveedor='.$row['idProveedor'].'" title="Editar datos" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                        <a href="proveedores.php?acc=delete&idProv='.$row['idProveedor'].'" title="Eliminar" onclick="return confirm(\'&iquest;Está seguro de borrar al Proveedor '.$row['RazonSocial'].'?\')" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                    </td>
                </tr>
                ';
            }}
            return $data;     }

        
    
function getAlert()
    {
        global $con;
        $alert;

        $idProv = mysqli_real_escape_string($con,(strip_tags($_REQUEST["idProv"],ENT_QUOTES)));
        $query = mysqli_query($con, "SELECT * FROM proveedores WHERE idProveedor='$idProv'");
        if(mysqli_num_rows($query) == 0){
            $alert .= '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> No se encontraron datos.</div>';
        }else{
            $delete = mysqli_query($con, "DELETE FROM proveedores WHERE idProveedor='$idProv'");
            if($delete){
                $alert .= '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Empleado eliminado correctamente.</div>';
            }else{
                $alert .= '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error, no se pudo eliminar al empleado.</div>';
            }
        }

        return $alert;
    }
	
?>