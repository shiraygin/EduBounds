<?php 
session_start();
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Saudi Arabia Universities Map</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom, #b8d6f5, #68c3a3);
      margin: 0;
      padding: 0;
    }
    .container {
      width: 90%;
      margin: auto;
      padding: 20px;
    }
    .search, .button-bar {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
      flex-wrap: wrap;
    }
    .search input {
      flex: 1;
      padding: 10px;
      border-radius: 20px;
      border: 1px solid #ccc;
    }
    .search button, .button-bar a {
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      background: #007bff;
      color: white;
      text-decoration: none;
      cursor: pointer;
      transition: 0.3s;
    }
    .button-bar a:hover, .search button:hover {
      background-color: #0056b3;
    }
    #map {
      width: 100%;
      height: 600px;
      border-radius: 10px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="search">
      <input type="text" id="place-search" placeholder="Search here...">
      <button onclick="searchPlace()">Search</button>
    </div>
    <div class="button-bar">
      <a href="#" onclick="showMyLocation()">My Location</a>
      <a href="#" onclick="showWorkplace()">Workplace</a>
      <a href="#" onclick="showUniversities()">Universities</a>
    </div>
    <div id="map"></div>
  </div>

  <!-- Google Maps Script -->
  <script>
    let map;
    let service;
    let markers = []; // To store all markers for clearing them later

    function initMap() {
      const saudiCenter = { lat: 24.7136, lng: 46.6753 };

      map = new google.maps.Map(document.getElementById("map"), {
        center: saudiCenter,
        zoom: 6,
      });

      service = new google.maps.places.PlacesService(map);
    }

    // Function to show universities
    function showUniversities() {
      clearMarkers();

      const request = {
        location: map.getCenter(),
        radius: '300000',
        type: ['university'],
      };

      service.nearbySearch(request, (results, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          results.forEach(place => {
            createMarker(place);
          });
        } else {
          console.error("Places search failed:", status);
        }
      });
    }

    // Create a marker for a place
    function createMarker(place) {
      const marker = new google.maps.Marker({
        map,
        position: place.geometry.location,
        title: place.name
      });

      const infowindow = new google.maps.InfoWindow({
        content: `<strong>${place.name}</strong><br>${place.vicinity || ''}`
      });

      marker.addListener("click", () => {
        infowindow.open(map, marker);
      });

      markers.push(marker);
    }

    // Clear markers
    function clearMarkers() {
      for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
      }
      markers = [];
    }

    // Search place from input
    function searchPlace() {
      const input = document.getElementById("place-search").value;
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ address: input }, (results, status) => {
        if (status === 'OK') {
          map.setCenter(results[0].geometry.location);
          map.setZoom(12);
        } else {
          alert("Place not found: " + status);
        }
      });
    }

    // Show user's current location
    function showMyLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            const userLocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };
            clearMarkers();
            map.setCenter(userLocation);
            map.setZoom(14);

            const marker = new google.maps.Marker({
              map,
              position: userLocation,
              title: "Your Location",
            });

            markers.push(marker);
          },
          function () {
            alert("Failed to get your location.");
          }
        );
      } else {
        alert("Geolocation not supported.");
      }
    }

    // Show workplace location on the map
    function showWorkplace() {
      clearMarkers();
      const workplaceLatLng = { lat: 24.7742658, lng: 46.7385864 }; // This is the location from your shared link

      map.setCenter(workplaceLatLng);
      map.setZoom(16);

      const marker = new google.maps.Marker({
        map,
        position: workplaceLatLng,
        title: "Workplace",
      });

      const infowindow = new google.maps.InfoWindow({
        content: `<strong>Workplace</strong><br><a href="https://maps.app.goo.gl/3mzXunFSAcodNG1WA" target="_blank">Open in Google Maps</a>`
      });

      marker.addListener("click", () => {
        infowindow.open(map, marker);
      });

      markers.push(marker);
    }

    window.initMap = initMap;
  </script>

  <!-- Google Maps API -->
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMKnloFbBkBcVtg-FF5s5_CmvRVJqnYBY&libraries=places&callback=initMap">
  </script>
</body>
</html>
<?php include 'footer.php'; ?>
