<html>
    <head>
        <title>GEO CODING PRACTICE</title>
        <style type="text/css">
            
        </style>
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBotaa_k17b6qJ-KjoeP-Yc8BSdUOtSDlg&sensor=false&language=en">
        </script>
         <script src="jquery-1.7.1.min.js"></script>
        <script>
        /*var saved;
            $.getJSON('getdata.php',{async: false}, function(data) {
                var items = [];
                console.log(data);
               callback(data);

            });
            function callback(data){
                saved=data;
                initialize(saved);
            }
        //console.log(saved);*/
        </script>
        <script type="text/javascript">
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
            var green="image.php?c=green&n=1";
            var red="image.php?c=red&n=2";
            var Routes = [];
            var start;
            var end;
            var request;

           function addMarker(location,array,problem_id) {
               console.log(problem_id);
                  marker = new google.maps.Marker({
                    position: location,
                    map: map
                  });
                  array[problem_id]=marker;
                  map.setCenter(array[problem_id].getPosition());
                  google.maps.event.addListener(marker, 'click', function() {
                      alert(problem_id);
                      //console.log(array[problem_id].getPosition());
                      map.setCenter(array[problem_id].getPosition());
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
           
           var problemID=1;
           //var globalLng=0;
           function theMarker(){
                lat="23.76"+problemID;
               lng="90.40"+problemID;
               
               latlng = new google.maps.LatLng(lat,lng);       
               addMarker(latlng,markerArray3,problemID); 
               problemID++;  
           }
           function placeMarker(location) {
              var markerNew = new google.maps.Marker({
                  position: location,
                  map: map
              });
              google.maps.event.addListener(markerNew, 'click', function() {
                      //console.log(array[problem_id].getPosition());
                      map.setCenter(markerNew.getPosition());
                      alert("Add New Problem");
              })

           } 
            function initialize() {
                //console.log(saved);
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

                map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
                google.maps.event.addListener(map, 'dblclick', function(event) {
                    placeMarker(event.latLng);
                });
               
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
        </script>

</head>

    <body onload="initialize();">
        <div id="geo">
           <!-- Geo Code 2012, Hackathon-->
        </div>

        <div id="map_canvas" style="width:700px; height:300px"></div>    

        
        <a href="#" onclick="theMarker();">Click to aDD marker</a>
        
        Location:<input type="name" value="" id="address" name="address">
        <input type="submit" value="search" onclick="codeAddress();">
        <script>
            /*function search(){
                value=$("#searchBox").val();
            }*/
        </script> 
    </body>

</html>