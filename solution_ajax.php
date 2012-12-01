<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<div id="coloumns" style="width:500px;height:400px; padding-top: 30px;padding-left: 30px;" > 
    <span style="font-size:20px;"><center>   সমস্যাটির জন্য সবার দেয়া সমাধান গুলো</center></span>
        <?php 
            $re2=getItem($_POST['id'],"problems");
            echo "<div style='height:50px;width:730px;'><div style='float:right;font-size:25px;' class='problemRating'>Rating : ".$re2['rating']."</div>";
            echo "<div style='width:730px;font-size:21px;float:left;position:absolute;clear:both;' class='problemName'>Problem : ".$re2['name']."</div></div>";
            
            $result=getItems(10,"solutions",$_POST['id']);
            while($row=mysql_fetch_array($result)){
            ?>
            <div style="width: 730px; line-height: 20px; float: left;border-bottom:1px solid #585858;padding-bottom: 5px;margin-bottom: 3px;">
                
                <div style="float: left;">
                    <div><b>Solution Name:<?php echo $row['name']; ?></b></div>
                    <div class="problemDesc">Details:<?php echo $row['description']; ?></div>
                    <div class="problemDesc">Cost:<?php echo $row['cost']; ?></div>
                    <div class="problemDesc">man power:<?php echo $row['man_power']; ?></div>
                    <div class="problemDesc">Duration:<?php echo $row['duration']; ?></div>
                </div>
                <div style="float: right;">
                    <div class="problemRating"><?php echo $row['rating']; ?></div>
                    <select id="stars_<?php echo $row['id']; ?>" name="stars">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select> <br/>
                    <button id="rate" onclick="rateIt('<?php echo $row['id']; ?>')">Rate it!</button>
                </div>
            </div>
        <?php    
            }
        ?>  
        <div>
        <button onclick="addSolution(<?php echo $_POST['id']; ?>)" id="add">Add Solution!</button>
        </div>
 </div>