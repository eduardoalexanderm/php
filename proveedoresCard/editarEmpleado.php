<?php
    //Incluyendo el archivo de conexion a la base de datos
    require_once("db/dbcon.php");
    
    //Evaluando si esta definido un parametro "save" y que su valor sea vacio
    if(isset($_REQUEST['save'])=='')
    {
        if(isset($_REQUEST['acc'])=='ok')
        {
            //file_get_contents — Reads entire file into a string
            $pagina = file_get_contents("templates/editarEmpleado.html"); //editarEmpleado
            //preg_replace($pattern, $replacement, $string);
            $pagina = preg_replace('/--nav--/', getNav(), $pagina);
            $pagina = preg_replace('/--alert--/', '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Los datos han sido actualizados con éxito.</div>', $pagina);
            $pagina = preg_replace('/--form--/', getForm(), $pagina);

            echo $pagina;
        }
        else
        {
            //file_get_contents — Reads entire file into a string
            $pagina = file_get_contents("templates/editarEmpleado.html"); //editarEmpleado
            //preg_replace($pattern, $replacement, $string);
            $pagina = preg_replace('/--nav--/', getNav(), $pagina);
            $pagina = preg_replace('/--alert--/', '', $pagina);
            $pagina = preg_replace('/--form--/', getForm(), $pagina);

            echo $pagina;
        }
    }
    else
    {
        //file_get_contents — Reads entire file into a string
        $pagina = file_get_contents("templates/editarEmpleado.html"); //editarEmpleado
        //preg_replace($pattern, $replacement, $string);
        $pagina = preg_replace('/--nav--/', getNav(), $pagina);
        $pagina = preg_replace('/--alert--/', getAlert(), $pagina);
        $pagina = preg_replace('/--form--/', getForm(), $pagina);

        echo $pagina;
    }

    function getNav()
    {
        $active = '<li class="active"><a href="proveedores.php">Lista de proveedores</a></li>
        <li><a href="agregarEmpleado.php">Agregar proveedor</a></li>';

        $nav = file_get_contents("./nav.php", FILE_USE_INCLUDE_PATH);
        $nav = preg_replace('/--active--/', $active, $nav);
        return $nav;
    }

    function getAlert()
    {
        global $con;
        $alert;
        
		$idProv		     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["idProv"],ENT_QUOTES)));//Escanpando caracteres 
        $codigo		     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["codigo"],ENT_QUOTES)));//Escanpando caracteres 
        $RazonSocial     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["RazonSocial"],ENT_QUOTES)));//Escanpando caracteres 
        $representante	 = mysqli_real_escape_string($con,(strip_tags($_REQUEST["representante"],ENT_QUOTES)));//Escanpando caracteres 
        $telefono	     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["telefono"],ENT_QUOTES)));//Escanpando caracteres 
        $movil	         = mysqli_real_escape_string($con,(strip_tags($_REQUEST["movil"],ENT_QUOTES)));//Escanpando caracteres 
        $estado		     = mysqli_real_escape_string($con,(strip_tags($_REQUEST["estado"],ENT_QUOTES)));//Escanpando caracteres 
       

        $update = mysqli_query($con, "UPDATE proveedores SET codigo='$codigo', RazonSocial='$RazonSocial', representante='$representante', telefono='$telefono', movil='$movil', telefono='$telefono', estado='$estado' WHERE idProveedor=$idProv") or die(mysqli_error());
        if($update){
            header("Location: editarEmpleado.php?idEmp=".$idEmp."&acc=ok"); //editarEmpleado
        }else{
            $alert.= '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error, no se pudo actualizar los datos.</div>';
        }


        return $alert;
    }

    function getForm()
    {
        global $con;
        $form ='';

        $idProv = mysqli_real_escape_string($con,(strip_tags($_REQUEST["idProv"],ENT_QUOTES)));
        $sql = mysqli_query($con, "SELECT * FROM proveedores WHERE idProveedor='$idProv'");
        if(mysqli_num_rows($sql) == 0){
            //header("Location: index.php");
        }else{
            $row = mysqli_fetch_assoc($sql);

            $codigo		     = $row['codigo'];
            $RazonSocial		     =  $row['RazonSocial'];
            $representante	 =  $row['representante'];
            $telefono	     = $row['telefono'];
            $movil		 =  $row['movil'];
            $estado		 =  $row['estado'];

            if($row['estado'] == 1)
                $estado = '<option value="">- Selecciona estado -</option>
                <option value="1" selected>Adminstrativo</option>
                <option value="2">Chef</option>
                <option value="3">Mesero</option>';
            else if($row['estado'] == 2)
                 $estado = '<option value="">- Selecciona estado -</option>
                 <option value="1" selected>Adminstrativo</option>
                 <option value="2">Chef</option>
                <option value="3">Mesero</option>';
            else if($row['estado'] == 3)
                $estado = '<option value="">- Selecciona estado -</option>
               <option value="1" selected>Adminstrativo</option>
                <option value="2">Chef</option>
                <option value="3">Mesero</option>';      

            $form = "<form class=\"form-horizontal\" action=\"\" method=\"post\">
            <div class=\"form-group\">
                <label class=\"col-sm-3 control-label\">Código</label>
                <div class=\"col-sm-2\">
                    <input type=\"text\" name=\"codigo\" value=".$codigo." class=\"form-control\" placeholder=\"idProv\" required>
                </div>
            </div>
            <div class=\"form-group\">
                <label class=\"col-sm-3 control-label\">Razón social</label>
                <div class=\"col-sm-4\">
                    <input type=\"text\" name=\"RazonSocial\" value=".$RazonSocial." class=\"form-control\" placeholder=\"Razón Social\" required>
                </div>
            </div>
            <div class=\"form-group\">
                <label class=\"col-sm-3 control-label\">Representante</label>
                <div class=\"col-sm-4\">
                    <input type=\"text\" name=\"representante\" value=".$representante." class=\"form-control\" placeholder=\"Representante\" required>
                </div>
            </div>
            
           
            <div class=\"form-group\">
                <label class=\"col-sm-3 control-label\">Teléfono</label>
                <div class=\"col-sm-3\">
                    <input type=\"text\" name=\"telefono\" value=".$telefono." class=\"form-control\" placeholder=\"Teléfono\" required>
                </div>
            </div>
            <div class=\"form-group\">
                <label class=\"col-sm-3 control-label\">Móvil</label>
                <div class=\"col-sm-3\">
                    
                    <input type=\"text\" name=\"movil\" value=".$movil." class=\"form-control\" placeholder=\"Móvil\" required>
                </div>
                
            </div>
            <div class=\"form-group\">
                <label class=\"col-sm-3 control-label\">Estado</label>
                <div class=\"col-sm-3\">
                    <select name=\"estado\" class=\"form-control\">"
                        .$estado."
                    </select> 
                </div>
               
            </div>
        
            <div class=\"form-group\">
                <label class=\"col-sm-3 control-label\">&nbsp;</label>
                <div class=\"col-sm-6\">
                    <input type=\"submit\" name=\"save\" class=\"btn btn-sm btn-primary\" value=\"Guardar datos\">
                    <a href=\"proveedores.php\" class=\"btn btn-sm btn-danger\">Cancelar</a>
                </div>
            </div>
        </form>";
        
        }

       return $form;
    }
?>