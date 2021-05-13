<?php
    require_once("config.php");

    $con = mysqli_connect(host,user,pass,dbname,port);

    if($con->connect_errno)
        die ("lo sentimos, no se ha podido conectar con mysql:" .mysqli_error());
    else {
        $db = mysqli_select_db($con,dbname);
        if ($db == 0)
            die ("lo sentimos, no se ha podido conectar con la base de datos:" .dbname);
    }      

?>