<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "geoguessr";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

$sql = "SELECT * FROM gory ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row['id'];
    $nazwa = $row['nazwa'];
    $wysokosc = $row['wysokosc'];
    $zdjecie = $row['zdjecie_szczytu'];
    $szerokosc = $row['szerokosc'];
    $dlugosc = $row['dlugosc'];
} else {
    echo "Brak danych w bazie.";
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zgadnij lokalizację góry</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="css/poland.css">
</head>
<body>
    <h2>Zgadnij lokalizację góry!</h2>
    <img src="img/<?php echo $zdjecie; ?>" alt="Zdjęcie góry">
    
    <form action="sprawdz.php" method="post" id="locationForm">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="lat" id="lat" value="">
        <input type="hidden" name="lng" id="lng" value="">
        <button type="submit">Sprawdź lokalizację</button>
    </form>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;
        let defaultPosition = [51.5, 19.0];

        function initMap() {
            map = L.map('map').setView(defaultPosition, 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            marker = L.marker(defaultPosition, { draggable: true }).addTo(map);

            marker.on('dragend', function(e) {
                let latLng = marker.getLatLng();
                document.getElementById('lat').value = latLng.lat;
                document.getElementById('lng').value = latLng.lng;
            });

            map.on('click', function(e) {
                let lat = e.latlng.lat;
                let lng = e.latlng.lng;

                marker.setLatLng([lat, lng]);
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>
