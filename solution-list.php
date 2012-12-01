<?php require_once('DataAccess.php');
    require_once('includes/functions.php'); 
?>
<html>
    <head>
    <style>
        .smallBox{
            width:auto;
            height: auto;
            float:left;
        }
        #wrapper {
    width: 90%;
    max-width: 1366px;
    min-width: 800px;
    margin: 50px auto;
}

#columns {
    -webkit-column-count: 4;
    -webkit-column-gap: 10px;
    -webkit-column-fill: auto;
    -moz-column-count: 3;
    -moz-column-gap: 10px;
    -moz-column-fill: auto;
    column-count: 3;
    column-gap: 15px;
    column-fill: auto;
}

.pin {
    display: inline-block;
    background: #FEFEFE;
    border: 2px solid #FAFAFA;
    box-shadow: 0 1px 2px rgba(34, 25, 25, 0.4);
    margin: 0 2px 15px;
    -webkit-column-break-inside: avoid;
    -moz-column-break-inside: avoid;
    column-break-inside: avoid;
    padding: 15px;
    padding-bottom: 5px;
    background: -webkit-linear-gradient(45deg, #FFF, #F9F9F9);
    opacity: 0.8;
    
    -webkit-transition: all .5s ease;
    -moz-transition: all .5s ease;
    -o-transition: all .5s ease;
    transition: all .5s ease;
}
.pin:hover {
background: -webkit-linear-gradient(45deg, #FFF, #F9F9F9); 
opacity: 1;  
}
    </style>
    <script src="jquery-1.7.1.min.js"></script>
    <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
    </head>
    <body>  
    <div id="container" style="background: white;">
    <div id="topbar" style="width: 100%;">
                    <ul>
                                 <a  style="color: black;" href="index.php"><li> মানচিত্রে খুঁজুন</li></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a style="color: black;" href="problem-list.php"><li> সমস্যা গুলো</li></a> 
                        
                    </ul>
                </div>
        <div id="solution">
                <div id="fade">
                    <div id="solution-form">
                        <table style="color:white;">
                            <tr>
                                <td>Name</td>
                                <td><input style="width: 300px" id="nameS" type="text" name="name" /></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><textarea id="descS" style="width: 300px; height: 60px; max-width: 300px; max-height: 60px;" name="desc"> </textarea></td>
                            </tr>
                            <tr>
                                <td>Cost</td>
                                <td><input id="cost" style="width: 300px;" type="text" name="cost" /></td>
                            </tr>
                            <tr>
                                <td>Man Power</td>
                                <td><input id="man" style="width: 300px;" type="text" name="man" /></td>
                            </tr>
                           
                            <tr>
                                <td>Duration (Days)</td>
                                <td><input id="duration" style="width: 300px;" type="text" name="duration" /></td>
                                <input type="hidden" id="pid" />
                            </tr>
                            
                        </table>
                        
                        <div style="margin-left: 220px">
                            <button id="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        <div id="wrapper" > 
        <div id="coloumns" > 
        <?php 
            $result=getItems(30,"solutions",null);
            while($row=mysql_fetch_array($result)){
                    $prb=getItem($row['problem_id'],"problems");
                    
            ?>
            <div class="pin">
                <div class="problemName"> সমস্যাঃ<?php echo $prb['name']; ?></div>
                <div>নামঃ<?php echo $row['name']; ?></div>
                <div class="problemDesc">বর্ণনাঃ<?php echo $row['description']; ?></div>
                <div class="problemRating">কত খানি ভালঃ <?php echo $row['rating']; ?></div>
                <div class="problemRating">খরচঃ<?php echo $row['cost']; ?></div>
                <div class="problemRating">জনশক্তিঃ<?php echo $row['man_power']; ?></div>
                <div class="problemRating">সময়ঃ <?php echo $row['duration']; ?></div>
                <select id="stars_<?php echo $row['id']; ?>" name="stars">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <br/>
                <button id="rate" onclick="rateIt('<?php echo $row['id']; ?>')">ভোট দিন</button>
                
            </div>
        <?php    
            }
        ?>  

        </div>
        </div>
    </div>
    <script> 
        var iframe=false; 
            function rateIt(id){
                    var table="solutions";
                    var star=$("#stars_"+id).val();
                     $.post('photoservice.php',{id:id,table:table,star:star},function(data){
                           alert(data);
                           $("#solution").fadeOut();
                           iframe=false; 
                     });
                    
                };
                function sol(id){
                     $("#solution").fadeIn(function(){
                        iframe=true;
                        $("#pid").val(id);
                    });
                }
        $(document).ready(function(){
                  $("#submit").click(function(){
                    var name=$("#nameS").val();
                    var desc=$("#descS").val();
                    var cost=$("#cost").val();
                    var man=$("#man").val();
                    var pid=$("#pid").val();
                    var duration=$("#duration").val();
                     $.post('saveSolution.php',{name:name,description:desc,cost:cost, man:man, pid:pid, duration:duration},function(data){
                           alert(data);
                     });
                    
                });
                
                $(document).keyup(function(e) { 
                    if (e.keyCode == 27) { // esc keycode
                        $('#solution').fadeOut();
                        iframe=false;
                    }
                });
                $("body").click(function(){
                    
                    if(iframe) {
                        $("#solution").fadeOut();
                        iframe=false;
                    }
                });
                
                // Prevent events from getting pass .popup
                $("#solution-form").click(function(e){
                    e.stopPropagation();
                }); 
        });
    </script>
    </body>
</html>