import { Controller } from '@hotwired/stimulus';
import $ from 'jquery';

export default class extends Controller {
    connect() {
      let map;
      let pins = this.element.dataset.positions;

      async function initMap() {
        const position = { lat: 33.7197455, lng: -78.8788075 };
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        var positions;

        map = new Map(document.getElementById("map"), {
          zoom: 12,
          center: position,
          mapId: "DEMO_MAP_ID",
        });
        pins.replace("[", "{").replace("]", "}");
        positions = JSON.parse(pins);
        for(var i=0; i<positions.length; i++) {
          var marker = new AdvancedMarkerElement({
              map: map,
              position: positions[i]['location'],
              title: positions[i]['title'],
          });
        }
      }
      initMap();        
    }
    getDoorCode() {
      let cleanid = document.getElementById("startCleanBtn").dataset.cleanid;
      let targetlat = document.getElementById("startCleanBtn").dataset.lat;
      let targetlng = document.getElementById("startCleanBtn").dataset.lng;
      console.log("Target Latitude: "+targetlat+" Target Longitude: "+targetlng);
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            };
            console.log("User Location Found. Latitude: "+position.coords.latitude+" Longitude: "+position.coords.longitude);
            let targetLocation = new google.maps.LatLng(targetlat, targetlng);
            let userLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
            console.log(targetLocation);
            let distance = google.maps.geometry.spherical.computeDistanceBetween(userLocation, targetLocation);
            console.log('Distance: '+distance);
            let mileInMeters = 1609.34; // 1 mile is approximately 1609.34 meters
            if (distance <= mileInMeters) {
              console.log('The user is within a mile of the target location.');
              window.location = '/clean/'+cleanid+'/start';
            } else {
              console.log('The user is not within a mile of the target location.');
              document.getElementById("startCleanBtn").innerHTML = "Not close enough to Location. Try Again";
            };            
          },
          () => {
            console.log('Error getting Location');
          }
        );
      } else {
        // Browser doesn't support Geolocation
        console.log('Browser doesnt support Geolocation');
      }     
    }
}
