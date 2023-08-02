import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        let map;
        let pins = this.element.dataset.positions;
        async function initMap() {
          const position = { lat: 33.7197455, lng: -78.8788075 };
          const { Map } = await google.maps.importLibrary("maps");
          const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
          var positions;
          map = new Map(document.getElementById("map"), {
            zoom: 12,
            center: position,
            mapId: "map",
          });
          pins.replace("[", "{").replace("]", "}");
          positions = JSON.parse(pins);
          for(var i=0; i<positions.length; i++) {
            window['marker'+i] = new AdvancedMarkerElement({
                map: map,
                position: positions[i]['location'],
                title: positions[i]['title'],
                content: (positions[i]['needsclean'] == true)?new PinElement({background: "green",borderColor: "green",glyphColor: "white",}).element:new PinElement({background: "lightgrey",borderColor: "lightgrey",glyphColor: "white",}).element,
                collisionBehavior: 'REQUIRED',
            });
          }
        }
        initMap();        
    }
}
