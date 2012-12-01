<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php 
    $data= array();
    $bigData = array();
    $js_array= array();
    $result=getItems(100,"problems",null);;
    
        while($row=mysql_fetch_array($result)){
            $data = array( 
                'id' => $row['id'], 
                'lat' => $row['latitude'], 
                'lng' => $row['longitude'], 
                'name' => $row['name'], 
                'rating' => (int)$row['rating'] 
            );
            array_push($bigData, $data);
        }
        //array_push($js_array, $bigData);
        //$bigData= array(); 
        $encodedData = json_encode($bigData);
        echo $encodedData;
    
?>