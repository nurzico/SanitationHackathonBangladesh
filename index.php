<!DOCTYPE html>
<html>
    <head>
        <title>Peer Platform</title>
        <link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBotaa_k17b6qJ-KjoeP-Yc8BSdUOtSDlg&sensor=false&language=en"></script>
        <script src="jquery-1.7.1.min.js"></script>
        <script>
        var saved;
            $.getJSON('getdata.php',{async: false}, function(data) {
                var items = [];
               // console.log(data[0]);
               callback(data);

            });
            function callback(data){
                saved=data;
               initialize(addAllMarker);
            }
        //console.log(saved);*/
        </script>
        <script type="text/javascript">
        var iframe=false;
           // var directionsService = new google.maps.DirectionsService(); 
            var directionsService;
            var stepDisplay;
            var markerArray1 = [];
            var markerArray2 = [];
            var markerArray3 = [];
            var geocoder;
            var map;
            var directionDisplay;
            var newRoute;
            var green="solution.png";
            var red="problem.png";
            var Routes = [];
            var start;
            var end;
            var request;

           function addMarker(location,array,problem_id) {
               console.log(problem_id);
                  marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    icon:green
                  });
                  array[problem_id]=marker;
                  map.setCenter(array[problem_id].getPosition());
                  google.maps.event.addListener(marker, 'click', function() {
                      //alert(problem_id);
                      //console.log(array[problem_id].getPosition());
                      map.setCenter(array[problem_id].getPosition());
                      makePopup(problem_id);
                   })
           }
           function clearOverlays(array) {
              if (array) {
                for (i in array) {
                  array[i].setMap(null);
                }
              }
           }
           function showOverlays(array) {
              if (array) {
                for (i in array) {
                  array[i].setMap(map);
                }
              }
           }
           
          /* var problemID=1;
           //var globalLng=0;
           function theMarker(){
                lat="23.76"+problemID;
               lng="90.40"+problemID;
               
               latlng = new google.maps.LatLng(lat,lng);       
               addMarker(latlng,markerArray3,problemID); 
               problemID++;  
           }*/
           function placeMarker(location) {
              var markerNew = new google.maps.Marker({
                  position: location,
                  map: map,
                  icon:red
              });
              google.maps.event.addListener(markerNew, 'click', function() {
                      location=markerNew.getPosition();
                      latt=location.$a;
                      llong=location.ab;
                      $("#latt").val(latt);
                      $("#long").val(llong);
                      map.setCenter(markerNew.getPosition());
                      //alert("Add New Problem");
                      $("#click").trigger("click");
              })

           } 
            function initialize(addAllMarker) {
               // alert("ASfd");
                geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(23.762511,90.39835000000005);
                var rendererOptions = {
                    routeIndex:1 
                }
                directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
                var myOptions = {
                    zoom: 14,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false
                };

                map = new google.maps.Map(document.getElementById("mapbox"),myOptions);
                google.maps.event.addListener(map, 'dblclick', function(event) {
                    placeMarker(event.latLng);
                }); 
                //console.log("before");
                addAllMarker();
               
            }
            
            function codeAddress() {
                var address = document.getElementById("address").value;
                if (geocoder) {
                  geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                      map.setCenter(results[0].geometry.location);
                    } else {
                      alert("Geocode was not successful for the following reason: " + status);
                    }
                  });
                }
            }
            function addAllMarker(){
                    var i;
                    var problemID=1;  
                for(i=0;i<saved.length;i++){
                     problemID=saved[i].id;
                      lat=saved[i].lat;
                      lng=saved[i].lng;
                      latlng = new google.maps.LatLng(lat,lng);       
                      addMarker(latlng,markerArray3,problemID);
                }
            }
            function makePopup(problemID){
                $.post('solution_ajax.php',{id:problemID},function(data){
                     $('#sol-list').html(data);
                    $("#sols").trigger("click");
                });
            
               //console.log(problemID); 
            }
            function rateIt(id){
                    var table="solutions";
                    var star=$("#stars_"+id).val();
                     $.post('photoservice.php',{id:id,table:table,star:star},function(data){
                            alert(data);
                     });
                    
            };
        </script>
    </head>
    <body>
        <div id="container">
            <div id="solutionL">
                <div id="fade">
                    
                    <div id="sol-list">
                      
                    </div>
                </div>
                
            </div>
             <div id="solution">
                <div id="fade">
                    <div id="solution-form">
                        <center><span style="font-size:20px;">সমস্যাটির জন্য সমাধান</span></center>
                        <table style="color:white;">
                            <tr>
                                <td>নাম</td>
                                <td><input style="width: 300px" id="nameS" type="text" name="name" /></td>
                            </tr>
                            <tr>
                                <td>বর্ণনা</td>
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
                            <button id="submitS">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="problem">
                <div id="fade">
                    <div id="problem-form">
                        <center><span style="font-size:20px;">সমসসার বিবরণ দিন</span></center>
                        <table>
                            <tr>
                                <td>নাম</td>
                                <td><input style="width: 300px" id="nameP" type="text" name="name" /></td>
                            </tr>
                            <tr>
                                <td>বর্ণনা</td>
                                <td><textarea id="descP" style="width: 300px; height: 60px; max-width: 300px; max-height: 60px;" name="desc"> </textarea></td>
                            </tr>
                            <tr>
                                
                                <input type="hidden" id="latt" name="lattitude" />
                                <input type="hidden" id="long" name="longitude" />
                            </tr>
                        </table>
                        <br/><br/><br/><br/><br/><br/><br/>
                        <div style="margin-left: 220px">
                            <a style="color: white" href="#">I've solution for this problem</a><br/>
                            <button style="border: none" id="submit">Submit</button>
                            
                        </div>
                    </div>                    
                </div>
            </div>
            
            <input id="click" type="hidden" />
            <input id="sol" type="hidden" />
            <input id="sols" type="hidden" />
            <div id="header">
             <span style="float: right;color: white;font-size: 65px;">
                      সমস্যা? সমাধান এখানে!  
                    </span>
                <div id="topbar">
                    
                    <ul>
                        <a href="problem-list.php"><li> সমস্যা গুলো</li></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="solution-list.php"><li> সমাধান গুলো</li></a>
                        
                    </ul>
                   
                </div>
               
            </div>
             <div class="searchbox">
                        <input style="color: white;text-align: center;" id="address" name="address" value="" title="খুঁজুন" />
                </div>
            <div id="mapbox">
            </div>
            <div id="footer">
              
                 <img src="problem.png">
                কোন এলাকায় সমস্যা জানাতে ক্লিক করুন
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [   সমস্যা জনিত এলাকার উপর ডাবল ক্লিক করুন]
                <span style="float: right; margin-right: 15px;">
                সমাধান দেখতে এবং জানাতে ক্লিক করুন<img src="solution.png">
                </span>
            </div>
        </div>
        <script>
        $('#address').keypress(function(e) {
            if(e.which == 13) {
                codeAddress();
            }
        });
        function hint(f) { 
                if(f.length) {
                    var dval = f.attr('title'); 
                    if(dval) { 
                        if(!f.val().length) { 
                            f.val(dval); 
                        } 
                        f.focus(function() { 
                            if(f.val() == dval) { 
                                f.val('');
                                f.css("color","#FFFFFF;");
                            } 
                        }).blur(function() { 
                            if(!f.val().length) { 
                                f.val(dval);
                                f.css("color","#000000;");  
                            } 
                        }); 
                    } 
                } 
        }
        hint($('#address'));
        </script>
        <script>
            
            $(document).ready(function(){
                $("#submit").click(function(){
                    var name=$("#nameP").val();
                    var desc=$("#descP").val();
                    var latt=$("#latt").val();
                    var llong=$("#long").val();
                    var stars=0;
                     $.post('saveProblem.php',{name:name,description:desc,stars:stars,longitude:llong,latitude:latt},function(data){
                           $("#problem").fadeOut();
                           var iframe=false;
                     });
                    
                });
                $("#submitS").click(function(){
                    var name=$("#nameS").val();
                    var desc=$("#descS").val();
                    var cost=$("#cost").val();
                    var man=$("#man").val();
                    var pid=$("#pid").val();
                    var duration=$("#duration").val();
                     $.post('saveSolution.php',{name:name,description:desc,cost:cost, man:man, pid:pid, duration:duration},function(data){
                           
                           $("#solution").fadeOut();
                           var iframe=false;
                     });
                    
                });
                $("#click").click(function(){
                    
                    $("#problem").fadeIn(function(){
                        iframe=true;
                    });
                });
                
                $("#sols").click(function(){
                    
                    $("#solutionL").fadeIn(function(){
                        iframe=true;
                    });
                });
                
                
                
                $(document).keyup(function(e) { 
                    if (e.keyCode == 27) { // esc keycode
                        $('#solution').fadeOut();
                        $('#solutionL').fadeOut();
                        $('#problem').fadeOut();
                        iframe=false;
                    }
                });
                $("body").click(function(){
                    
                    if(iframe) {
                        $("#solution").fadeOut();
                        $("#solutionL").fadeOut();
                        $("#problem").fadeOut();
                        iframe=false;
                    }
                });
                
                // Prevent events from getting pass .popup
                $("#problem-form").click(function(e){
                    e.stopPropagation();
                });
                 $("#solution-form").click(function(e){
                    e.stopPropagation();
                });
                $("#sol-list").click(function(e){
                    e.stopPropagation();
                });
            });
            function addSolution(id){
                $("#nameS").val("");
                $("#descS").val("");
                $("#cost").val("");
                $("#man").val("");
                $("#duration").val("");
                    $("#solutionL").fadeOut();
                     $("#solution").fadeIn(function(){
                        iframe=true;
                        $("#pid").val(id);
                    });
                }
        </script>
    </body>
</html>
