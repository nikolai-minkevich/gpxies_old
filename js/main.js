var mymap = L.map('mapid').setView([60.0, 30.0], 9);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoibm1pbmtldmljaCIsImEiOiJjazVqeGVnYjEwN2trM29ybWtrdDBvOXFzIn0.0y70HLurlAEtyMY-ahO4CA', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11'
}).addTo(mymap);

// current track
var latlngs = [];
var polyline;


function onMapClick(e) {
    console.log([e.latlng.lat, e.latlng.lng]);
    latlngs.push([e.latlng.lat, e.latlng.lng]);
    polyline = L.polyline(latlngs, { color: 'red' }).addTo(mymap);

}


function showTrack(trackName) {
    console.log ('yep',trackName);
    var gpx = 'data/gpx/' + trackName ; 
    new L.GPX(gpx, {
        async: true,
        marker_options: {
            startIconUrl: 'images/pin-icon-start.png',
            endIconUrl: 'images/pin-icon-end.png',
            shadowUrl: 'images/pin-shadow.png'
        }
    }).on('loaded', function (e) {
        mymap.fitBounds(e.target.getBounds());
        document.getElementById('track-info').innerHTML = e.target.get_distance();
    }).addTo(mymap);
}

// event handlers
mymap.on('click', onMapClick);

