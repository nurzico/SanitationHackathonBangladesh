<?php
    require_once("DataAccess.php");
    
    function mysql_prep( $value ) {
        $magic_quotes_active = get_magic_quotes_gpc();
        $new_enough_php = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
        if( $new_enough_php ) { // PHP v4.3.0 or higher
            // undo any magic quote effects so mysql_real_escape_string can do the work
            if( $magic_quotes_active ) { $value = stripslashes( $value ); }
            $value = mysql_real_escape_string( $value );
        } else { // before PHP v4.3.0
            // if magic quotes aren't already on then add slashes manually
            if( !$magic_quotes_active ) { $value = addslashes( $value ); }
            // if magic quotes are active, then the slashes already exist
        }
        return $value;
    }
    
    function redirect_to( $location = NULL ) {
        if ($location != NULL) {
            header("Location: {$location}");
            exit;
        }
    }
    function confirm_query($result_set) {
        if (!$result_set) {
            die("Database query failed: " . mysql_error());
        }
    }
  function get_all_locations($public = true) {
        global $connection;
        $query = "SELECT * 
                FROM locations ";
        $query .= "ORDER BY id ASC";
        $location_set = mysql_query($query, $connection);
        confirm_query($location_set);
        return $location_set;
    }
    function get_count_pinbox(){
         global $connection;
        $query = "SELECT count(*) ";
        $query .= "FROM pinbox ";
        $query .= "WHERE visible=" ."0";
       // $query .= "LIMIT 1";
       $result_set = mysql_query($query, $connection);
       confirm_query($result_set);
        // REMEMBER:
        // if no rows are returned, fetch_array will return false
        if ($news = mysql_fetch_array($result_set)) {
            return $news;
        } else {
            return NULL;
        }
    }
    function getItemCount(){
        $data = new DataAccess('photo');
        $sql = "SELECT COUNT(*) FROM items WHERE status='Active' AND type='photo' ";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        if($row)
        {
            return $row['COUNT(*)'];
        }
        else
        {
            return null;
        }  
    }
    function getItemCountRobi(){
        $data = new DataAccess('photo');
        $sql = "SELECT COUNT(*) FROM items WHERE status='Active' AND type='photo' AND robi_user=1 ";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        if($row)
        {
            return $row['COUNT(*)'];
        }
        else
        {
            return null;
        }  
    }
    function getItem($itemID,$table){
        $data = new DataAccess('photo');
        $sql = "SELECT * FROM $table WHERE id=$itemID";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        if($row)
        {
            return $row;
        }
        else
        {
            return null;
        }  
    }
    function getUser($userID){
        $data = new DataAccess('photo');
        $sql = "SELECT * FROM users WHERE id='$userID'";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        if($row)
        {
            return $row;
        }
        else
        {
            return null;
        }  
    }
    function check_facebook_user($user_id){
        global $connection;
        $query = "SELECT id, email, name ";
            $query .= "FROM users ";
            $query .= "WHERE fbid = '{$user_id}' ";
            $query .= "LIMIT 1";
            $result_set = mysql_query($query,$connection);
            confirm_query($result_set);
            if (mysql_num_rows($result_set) == 1) {
                $found_user = mysql_fetch_array($result_set);
                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['name'] = $found_user['name'];
                $_SESSION['email'] =  $found_user['email'];
                $_SESSION['type'] = 'facebook';
                return $found_user['id'];
            }else {
                return NULL;
            }
    }
    function check_if_robi_user($email){
         global $connection;
        $query = "SELECT * ";
        $query .= "FROM robi_user ";
        $query .= "WHERE email=" ."'{$email}'";
        $query .= "LIMIT 1";
       $result_set = mysql_query($query, $connection);
       confirm_query($result_set);
        if ($news = mysql_fetch_array($result_set)) {
            return $news['email'];
        } else {
            return NULL;
        }
    } 
    function put_facebook_user($user_id,$name, $email){
        global $connection;
        $now = getCurrentDateTime();
        $query = "INSERT INTO users (
                            email,fbid, name, verified, last_checkin
                        ) VALUES (
                            '{$email}', '{$user_id}', '{$name}', 1,'{$now}'
                            )";
        $result = mysql_query($query, $connection);
        if ($result) {
            $message = "The user was successfully created.";
            return check_facebook_user($user_id);
        } else {
            return NULL;
        }
        
    }
    function get_active_user($userID){
       $data = new DataAccess('photo');
        $sql = "SELECT * FROM users WHERE id='$userID'";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        $data->dispose();
        
        if($row)
        {
            return $row;
        }
        else
        {
            return null;
        } 
    }
    function getFaceBookName($userID)
    {
        $data = new DataAccess('photo');
        $sql = "SELECT * FROM users WHERE fbid='$userID'";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        $data->dispose();
        
        if($row)
        {
            return $row['name'];
        }
        else
        {
            return null;
        }
    }
    function likeItem($userID, $itemID)
    {
        $data = new DataAccess('photo');
        
        $sql = "SELECT like_count FROM items WHERE item_id=$itemID";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        $newitemtotal = $row['like_count'] + 1;

        $sql = "SELECT like_count FROM users WHERE fbid='$userID'";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        $newusertotal = $row['like_count'] + 1;

        $newtime = getCurrentDateTime();
        
        $sql = "UPDATE items SET like_count=$newitemtotal, last_liked='$newtime' WHERE item_id=$itemID";
        $data->executeQuery($sql);

        $sql = "UPDATE users SET like_count=$newusertotal WHERE fbid='$userID'";
        $data->executeQuery($sql);
        
        $sql = "INSERT INTO users_likes VALUES ('$userID', $itemID, '$newtime')";
        $data->executeQuery($sql);
        $data->dispose();
    }
    
    function getItems($limit,$table,$problemID){
        $data = new DataAccess('photo'); 
        $sql = "SELECT * FROM $table ORDER BY RATING DESC LIMIT $limit";
        if($problemID!=null){
            $sql = "SELECT * FROM $table WHERE problem_id=$problemID ORDER BY RATING DESC LIMIT $limit";                                                                        
        }
        $resultset = $data->getResultSet($sql);
        if($resultset){
            return $resultset;
        }else{
            return mysql_error();
        }
        
    }
    function getItemsByUser($type,$user){
        $data = new DataAccess('photo');
        $sql = "SELECT * FROM items WHERE status = 'Active' AND type='$type' AND uploader_id=$user ";
         $resultset = $data->getResultSet($sql);
            if($resultset){
                return $resultset;
            }else{
                return mysql_error();
            }
    }
    function getMyVoteCount($user){
      $data = new DataAccess('photo');
        $sql = "SELECT count(*) FROM photo_likes WHERE fbid=$user ";
         $result_set = $data->getResultSet($sql);
         if ($news = mysql_fetch_array($result_set)) {
            return $news[0];
         } else {
            return NULL;
        }
    }
    function unlikeItem($userID, $itemID)
    {
        $data = new DataAccess('photo');
        
        $sql = "SELECT like_count FROM items WHERE item_id=$itemID";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        $newitemtotal = $row['like_count'] - 1;

        $sql = "SELECT like_count FROM users WHERE fbid='$userID'";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        $newusertotal = $row['like_count'] - 1;

        $newtime = getCurrentDateTime();
        
        $sql = "UPDATE items SET like_count=$newitemtotal WHERE item_id=$itemID";
        $data->executeQuery($sql);

        $sql = "UPDATE users SET like_count=$newusertotal WHERE fbid='$userID'";
        $data->executeQuery($sql);
        
        $sql = "DELETE FROM users_likes WHERE fbid='$userID' AND item_id=$itemID";
        $data->executeQuery($sql);
        $data->dispose();
    }
    
    function getUserLikes($userID)
    {
        $data = new DataAccess('photo');
        $sql = "SELECT * FROM users WHERE fbID='$userID'";
        $resultset = $data->getResultSet($sql);
        $row = $data->getRow($resultset);
        $data->dispose();
        
        return $row['like_count'];
    }
    
    function getLikedItems($userID, $votedStr = 'Yes', $unvotedStr = 'No')
    {
        $data = new DataAccess('photo');
        $sql = "SELECT tabA.item_id, tabB.liking_time FROM items AS tabA LEFT OUTER JOIN (SELECT item_id, liking_time FROM users_likes WHERE fbid='$userID') AS tabB ON tabA.item_id = tabB.item_id";
        $resultset = $data->getResultSet($sql);
        
        $arrID = array();
        $arrValue = array();
        
        while($row = $data->getRow($resultset))
        {
            if(!$row['liking_time'])
            {
                array_push($arrValue, $unvotedStr);
            }
            else
            {
                array_push($arrValue, $votedStr);
            }
            
            array_push($arrID, $row['item_id']);
        }
        
        $data->dispose();
        
        return array_combine($arrID, $arrValue);
    }
    function getNotVotedItems($userID, $votedStr = 'Yes', $unvotedStr = 'No')
    {
        $data = new DataAccess('photo');
        $sql = "SELECT tabA.item_id, tabB.liking_time FROM items AS tabA LEFT OUTER JOIN (SELECT item_id, liking_time FROM photo_likes WHERE fbid='$userID') AS tabB ON tabA.item_id = tabB.item_id WHERE tabA.type='photo' AND tabA.status='Active' ";
        $resultset = $data->getResultSet($sql);
        if(!$resultset){
            echo mysql_error();
        }
        
        
        $arrID = array();
       // $arrValue = array();
        
        while($row = $data->getRow($resultset))
        {
            if(!$row['liking_time'])
            {
                array_push($arrID, $row['item_id']);
            }
          /*  else
            {
                array_push($arrValue, $votedStr);
            } */
            
           // array_push($arrID, $row['item_id']);
        }
        
        $data->dispose();
        
        return $arrID;
    }

    function arrayItems(){
        $count=0;
        $data = new DataAccess('photo');
        $sql="SELECT * FROM items WHERE status = 'Active' ORDER BY entry_date DESC ";
        $resultset = $data->getResultSet($sql);
        while($row = $data->getRow($resultset)){
            $list[$count]=$row['item_id'];
            $count++;
        }
        return $list;
    }

    function arrayItemsTop(){
        $count=0;
        $data = new DataAccess('photo');
        $sql="SELECT * FROM items WHERE status = 'Active' ORDER BY like_count DESC ";
        $resultset = $data->getResultSet($sql);
        while($row = $data->getRow($resultset)){
            $list[$count]=$row['item_id'];
            $count++;
        }
        return $list;
    }
    
    
    function getLocationList($divId = 0)
    {
        $sql = "";
        if($divId == 0)
        {
            $sql = "SELECT * FROM (SELECT @rownum:=@rownum+1 AS rank, places.place_id, places.place_name, divisions.div_name, districts.dist_name, thanas.thana_name, places.description, places.vote_count, users.name FROM (SELECT @rownum:=0) r, places, divisions, districts, thanas, users WHERE divisions.div_id = places.div_id AND districts.dist_id = places.dist_id AND thanas.thana_id = places.thana_id AND users.fbid = places.suggested_by AND places.status='Active' ORDER BY places.vote_count DESC) AS tempTable ORDER BY tempTable.place_name ASC";
        }
        else
        {
            $sql = "SELECT * FROM (SELECT @rownum:=@rownum+1 AS rank, places.place_id, places.place_name, divisions.div_name, districts.dist_name, thanas.thana_name, places.description, places.vote_count, users.name FROM (SELECT @rownum:=0) r, places, divisions, districts, thanas, users WHERE divisions.div_id = places.div_id AND districts.dist_id = places.dist_id AND thanas.thana_id = places.thana_id AND users.fbid = places.suggested_by AND divisions.div_id=$divId AND places.status='Active' ORDER BY places.vote_count DESC) AS tempTable ORDER BY tempTable.place_name ASC";
        }
        
        return $sql;
    }
    
    function getRankList()
    {
        $sql = "SELECT place_id, place_name, div_name, dist_name, thana_name, vote_count FROM places, divisions, districts, thanas WHERE divisions.div_id = places.div_id AND districts.dist_id = places.dist_id AND thanas.thana_id = places.thana_id AND places.status = 'Active' ORDER BY places.vote_count DESC";
        return $sql;
    }
    
    function getRankArray()
    {
        $data = new DataAccess('photo');
        $sql = "SELECT place_id, vote_count FROM places ORDER BY vote_count DESC";
        $resultset = $data->getResultSet($sql);
        
        $i = 0;
        $v = -1;
        $placeID = array();
        $rank = array();
        
        while($row = $data->getRow($resultset))
        {
            if($v != $row['vote_count'])
            {
                $i++;
                $v = $row['vote_count'];
            }
            array_push($placeID, $row['place_id']);
            array_push($rank, $i);            
        }
        $data->dispose();
        return array_combine($placeID, $rank);
    }
    
    function getSanitizedString( $str )
    {
        //global $con;
        $data = new DataAccess('photo');
    $con = $data->getConnection();
        $magic_quotes_active = get_magic_quotes_gpc();
        $new_enough_php = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
        if( $new_enough_php )
        {   
            // PHP v4.3.0 or higher
            // undo any magic quote effects so mysql_real_escape_string can do the work
            if( $magic_quotes_active )
            {
                $str = stripslashes( $str );
            }
            $str = mysql_real_escape_string( $str, $con );
        }
        else
        {
            // before PHP v4.3.0
            // if magic quotes aren't already on then add slashes manually
            if( !$magic_quotes_active )
            {
                $str = addslashes( $str );
            }
            // if magic quotes are active, then the slashes already exist
        }
        return $str;
    }

  
    function getCurrentDateTime()
    {
        date_default_timezone_set('Asia/Dhaka');
        $now = date('Y').'-'. date('m').'-'.date('d').' '.date('H').':'. date('i').':'.date('s');
        return $now;
    }
    
    function getRandomString($length = 10)
    {
        $valid = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for($i = 0; $i < $length; $i++)
        {
            $pos = mt_rand(0, 61);
            $str .= substr($valid, $pos, 1);
        }
        
        return $str;
    }
    
    function sendMail($itemid, $email, $subject, $message)
    {
        $uploadername = $itemid;
        $from_name = "Robi Photography Contest";
        $from_email = "talenthub@robi.com.bd";
        
        $mail = new PHPMailer(); 
        $mail->Host = 'mail.robi.com.bd';
        $mail->Port = 25; 
        $mail->Username="talenthub@robi.com.bd";  
        $mail->Password ="Robi4Robi";           
        $mail->SetFrom($from_email, $from_name);
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        
       // $currentdir = getcwd();
       // $setdir = '/home/hacker/public_html/photocontest';
        
        $mail->AddAttachment("http://www.ice9apps.com/robi/email.png");
        $mail->AddEmbeddedImage('http://www.ice9apps.com/robi/email.png', 'bannerimage','http://www.ice9apps.com/robi/email.png'); // attach file logo.jpg, and later link to it using identfier logoimg
        $mailheader = "<img src='http://www.ice9apps.com/robi/email.png' width=350 height=200 /><br/><br/><br/>Dear $uploadername,<br/><br/>";
        $mail->Body = $mailheader . $message;
        $mail->AddAddress($email);
        if(!($mail->Send())) {
           // $error = 'Mail error: '.$mail->ErrorInfo; 
            return $mail->ErrorInfo;
        } else {
            return "success";
        }
        
    }
    function save_and_send_email($email,$verification_code){
        global $connection;
        $query = "INSERT INTO email_verification (
                            email,verification_code
                        ) VALUES (
                            '{$email}', '{$verification_code}'
                            )";
        $result = mysql_query($query, $connection);
        if ($result) {
            return sendCode($email,$verification_code);
        } else {
            return NULL;
        }
        
    }
    function sendCode($email,$verification_code){
        $from_name = "Robi Talent Hunt";
        $from_email = "ataur.awesome@gmail.com";
        $subject= "verification email";
        $message = "<a href='http://localhost/robix/verification.php?email=".urlencode($email)."&code=".urlencode($verification_code)."'>Click On this Link To Verify</a>";
        $mail = new PHPMailer(); 
        $mail->IsSMTP(); 
        $mail->SMTPDebug = 0;  
        $mail->SMTPAuth = true;  
        $mail->SMTPSecure = 'ssl'; 
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->IsHTML(true);  
        $mail->Username="ataur.awesome@gmail.com";  
        $mail->Password ="DHAKABANGLADESH";           
        $mail->SetFrom($from_email, $from_name);
        $mail->Subject = $subject;
      

         // attach file logo.jpg, and later link to it using identfier logoimg
        $mail->Body = $message;
        $mail->AddAddress($email);
        if(!($mail->Send())) {
           // $error = 'Mail error: '.$mail->ErrorInfo; 
            return $mail->ErrorInfo;
        } else {
            return "success";
        }
        
    }
    
    function update_verification($email){
        global $connection;
            $sql = "UPDATE users SET verified = 1 WHERE email = '{$email}' AND verified = 0 ";
            $result_set = mysql_query($sql, $connection);
            confirm_query($result_set);
            return "success";
    }
    
     function topVoted(){
        $data=  new DataAccess('photo');
          $sql ="SELECT * FROM users WHERE robi_id is NULL ORDER BY photo_likes DESC, last_checkin ASC LIMIT 50";
          
          $resultset = $data->getResultSet($sql);
            if($resultset){
                return $resultset;
            }else{
                return mysql_error();
            } 
     }
     function topPhotoUploader(){
          $data=  new DataAccess('photo');
          $sql ="SELECT * FROM items WHERE type='photo' AND status='Active' ORDER BY percentage DESC, like_count DESC, entry_date DESC";
          
          $resultset = $data->getResultSet($sql);
            if($resultset){
                return $resultset;
            }else{
                return mysql_error();
            }
     }
     function topPhotoUploaderRobi(){
          $data=  new DataAccess('photo');
          $sql ="SELECT * FROM items WHERE type='photo' AND status='Active' AND robi_user=1 ORDER BY percentage DESC, like_count DESC, entry_date DESC";
          
          $resultset = $data->getResultSet($sql);
            if($resultset){
                return $resultset;
            }else{
                return mysql_error();
            }
     } 
?>