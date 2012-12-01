<?php 
    require_once('DataAccess.php');
    require_once('includes/functions.php');
    
    $name=$_POST['name'];
    //user info
    $id=rand(1,5);
    $userName="sdf";
    $email="email@email.com";
    $description=$_POST['description'];
    $cost=$_POST['cost'];
    $man=$_POST['man'];
    $duration=$_POST['duration'];
    $problemID=$_POST['pid'];
    $now = getCurrentDateTime();
     $sql = "INSERT INTO solutions VALUES (null,'$name', '$id', '$userName', '$email','$description','type', 'fn','$now', 0,0, '$now', 'Active','$problemID','$cost','$man','$duration')";
                $data = new DataAccess('photo');
                $result = $data->executeQuery($sql);
                if(!$result){
                   echo mysql_error();
                   return; 
                }
                $data->dispose();
    
?>