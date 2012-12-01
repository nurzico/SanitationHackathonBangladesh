<?php 
    require_once('DataAccess.php');
    require_once('includes/functions.php');
    
    $name=$_POST['name'];
    //user info
    $id=1;
    $userName="me";
    $email="email@email.com";
    $description=$_POST['description'];
    $stars=$_POST['stars'];
    $latitude=$_POST['latitude'];
    $longitude=$_POST['longitude'];
    
    $now = getCurrentDateTime();
     $sql = "INSERT INTO problems VALUES (null,'$name', '$id', '$userName', '$email','$description',null, 'fn','$now', 0, '$now', 'Active','$stars','$latitude','$longitude')";
                $data = new DataAccess('photo');
                
                $result = $data->executeQuery($sql);
                $pid=mysql_insert_id();
                if(!$result){
                   echo mysql_error();
                   return; 
                }
                
                $data->dispose();
               return $pid;
?>