<?php

  require_once('DataAccess.php');
  require_once('includes/functions.php');
  
 
  $star=$_POST['star'];  
  $data = new DataAccess('photo');
  $userID=rand(1,10);
  $itemID = $_POST['id'];
  $table = $_POST['table'];
  $newtime = getCurrentDateTime();
  
        $theItem= getItem($itemID,$table);

        $totStar = $theItem['stars']+$star;
        $total = $theItem['like_count']+1;
        $rating = (int)$totStar/$total;
        $table2=$table."_votes";
        $sql = "UPDATE $table SET like_count='$total', stars='$star', last_liked='$newtime',  rating=$rating WHERE id=$itemID";
        $result = $data->executeQuery($sql);
        if($result){
            $sql = "INSERT INTO $table2 VALUES ($userID, $star, '$newtime',$itemID )";
            $result2 = $data->executeQuery($sql);
            if($result2){
                echo "success";
            }else{
                echo "fail3";
            }
        }
        else{
                echo "fail2";
            }
        $data->dispose();

  
?>
