<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "geoguessr";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

$id = $_POST['id'];
$userLat = $_POST['lat'];
$userLng = $_POST['lng'];

$sql = "SELECT * FROM gory_zagraniczne WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nazwa = $row['nazwa'];
    $szerokosc = $row['szerokosc'];
    $dlugosc = $row['dlugosc'];
} else {
    echo "Błąd: Brak danych o górze.";
    exit();
}
$conn->close();

function haversine($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Promień Ziemi w km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c; // Odległość w km
}

$distance = round(haversine($szerokosc, $dlugosc, $userLat, $userLng), 2);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wynik lokalizacji</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="css/sprawdzWorld.css">
</head>
<body>
    <h2>Oto wynik!</h2>
    <p>Twoja odległość od prawidłowego miejsca: <strong><?php echo $distance; ?> km</strong></p>
    <p>Prawidłowa góra to: <strong><?php echo $nazwa; ?></strong></p>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        let map = L.map('map').setView([<?php echo $szerokosc; ?>, <?php echo $dlugosc; ?>], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([<?php echo $szerokosc; ?>, <?php echo $dlugosc; ?>]).addTo(map)
            .bindPopup("<b>Prawidłowe miejsce</b>").openPopup();

        L.marker([<?php echo $userLat; ?>, <?php echo $userLng; ?>]).addTo(map)
            .bindPopup("<b>Twoje miejsce</b>").openPopup();

        let latlngs = [
            [<?php echo $szerokosc; ?>, <?php echo $dlugosc; ?>],
            [<?php echo $userLat; ?>, <?php echo $userLng; ?>]
        ];
        L.polyline(latlngs, { color: 'red' }).addTo(map);
    </script>
    <form action="world.php" method="post">
        <button type="submit">Zagraj ponownie</button>
    <footer>
        <p>MountainGuessr &copy; 2025</p>
    </footer>
</body>
</html>