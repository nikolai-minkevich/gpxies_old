

let DEBUG = true;
let history = [];
let secretPlace = { coords: [], street: '' }
let scores = 0;
let resultString = '';
let myMap;

ymaps.ready(init);
function init() {
    myMap = new ymaps.Map("map", {
        center: [60, 30],
        zoom: 8,
        controls: []
    });

    myMap.events.add('click', function (e) {
        console.log(secretPlace.coords);
        console.log(typeof secretPlace.coords);
        console.log(e.get('coords'));
        console.log(typeof e.get('coords'));

        let distance = ymaps.formatter.distance(ymaps.coordSystem.geo.getDistance(secretPlace.coords, e.get('coords')));



        ymaps.geocode(e.get('coords'))
            .then(function (res) {
                // проверяем, есть ли привязка к улице у точки
                if (res.geoObjects.get(0).getThoroughfare()) {
                    // если привязка есть, проверяем на точное совпадение
                    if (res.geoObjects.get(0).getThoroughfare() == secretPlace.street) {
                        resultString = 'В яблочко!';
                        setInnerCode('game-result', resultString);

                        myMap.geoObjects.add(new ymaps.Placemark(e.get('coords'), {},
                            {
                                preset: 'islands#icon',
                                iconColor: '#00ff00'
                            }));
                    } else {
                        // Если привязки не совпадают, проверяем расстояние
                        resultString = 'Мимо! До загаданной улицы ' + distance;
                        setInnerCode('game-result', resultString);


                        myMap.geoObjects.add(new ymaps.Polyline([
                            secretPlace.coords,
                            e.get('coords')
                        ]))
                            .add(new ymaps.Placemark(secretPlace.coords, {},
                                {
                                    preset: 'islands#icon',
                                    iconColor: '#00ff00'
                                }))
                            .add(new ymaps.Placemark(e.get('coords'), {},
                                {
                                    preset: 'islands#icon',
                                    iconColor: '#ff0000'
                                }));

                    }

                } else {
                    // Если привязок нет, проверяем расстояние
                    resultString = 'Мимо! До загаданной улицы ' + distance;
                    setInnerCode('game-result', resultString);

                    myMap.geoObjects.add(new ymaps.Polyline([
                        secretPlace.coords,
                        e.get('coords')
                    ]))
                        .add(new ymaps.Placemark(secretPlace.coords, {},
                            {
                                preset: 'islands#icon',
                                iconColor: '#00ff00'
                            }))
                        .add(new ymaps.Placemark(e.get('coords'), {},
                            {
                                preset: 'islands#icon',
                                iconColor: '#ff0000'
                            }));
                }



            });

    });

}




function getRandomCoords() {

    setInnerCode('game-result', '');
    setInnerCode('btn-start', 'Дальше');
    myMap.geoObjects.removeAll();
    /*
    [60.05360495484524, 30.175781249999986] ---- [60.05360495484524, 30.530090332031214]
    |                                                                                  |
    [59.83313116619863, 30.175781249999986] ---- [59.83313116619863, 30.530090332031214]
    */

    latMin = 59.83313116619863;
    latMax = 60.05360495484524;
    lonMin = 30.175781249999986;
    lonMax = 30.530090332031214;

    let lat = Math.random() * (latMax - latMin) + latMin;
    let lon = Math.random() * (lonMax - lonMin) + lonMin;

    if (DEBUG) console.log('Загаданы: ' + [lat, lon].join(', '));

    ymaps.geocode([lat, lon])
        .then(function (res) {

            if (res.geoObjects.get(0).getThoroughfare()) {
                setInnerCode('game-streetname', res.geoObjects.get(0).getThoroughfare());

                secretPlace.coords = res.geoObjects.get(0).geometry._coordinates;
                secretPlace.street = res.geoObjects.get(0).getThoroughfare();

                var myGeocoder = ymaps.geocode(secretPlace.street + ', 1');
                myGeocoder.then(
                    function (res) {
                        console.log (res);
                        alert('Координаты объекта :' + res.geoObjects.get(0).geometry.getCoordinates());
                    },
                    function (err) {
                        alert('Ошибка');
                    }
                );

                var myGeocoder = ymaps.geocode(secretPlace.street + ', 999');
                myGeocoder.then(
                    function (res) {
                        console.log (res);
                        alert('Координаты объекта :' + res.geoObjects.get(0).geometry.getCoordinates());
                    },
                    function (err) {
                        alert('Ошибка');
                    }
                );


            } else {
                getRandomCoords();
            }


        });

    return [lat, lon];
} // getRandomCoords

function setInnerCode(id, content) {
    document.getElementById(id).innerHTML = content;
}

function refreshList(elem, arr) {
    document.getElementById(elem).innerHTML = '';
    for (let i = 0; i < arr.length; i++) {
        document.getElementById(elem).innerHTML += '<li>' + arr[i] + '</li>'
    }
}

function toggleHistory() {
    if (document.getElementById('history').style.visibility == "hidden") {
        document.getElementById('history').style.visibility = "visible"
    } else {
        document.getElementById('history').style.visibility = "hidden"
    }
}