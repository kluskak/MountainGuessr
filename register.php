<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'geoguessr');
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Rejestracja udana! Możesz się teraz zalogować.";
        header("Location: login.php");
        exit();
    } else {
        echo "Błąd rejestracji: " . $conn->error;
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="boxicons/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/styl4.css">
    <title>Logowanie</title>
</head>
<body>
 <div class="wrapper">
    <nav class="nav">
        <div class="nav-logo">
            <p>Nazwa</p> 
        </div>
       
        <div class="nav-button">
        <a class="btn btn-primary" id="loginBtn" href="login.php">
    <i class="bi bi-box-arrow-in-right"></i> Zaloguj się
</a>
<a class="btn btn-success" id="registerBtn" href="register.php">
    <i class="bi bi-person-plus"></i> Zarejestruj się
</a>



        </div>
      
    </nav>
    <div class="form-box">

            <div class="register-container" id="register">

            <div class="top">
                <span>Już posiadasz konto? <a href="login.php"
                     >Logowanie</a></span>
                <header>Rejestracja</header>
            </div>
                <form action="register.php" method="POST">
                <div class="input-box">
                    <input type="text" name="username" placeholder="Nazwa użytkownika" class="input-field" required>
                    <i class="bx bx-user"></i>
                    </div>  
                    <div class="input-box">
                    <input type="email" name="email" placeholder="Email" class="input-field" required>
                    <i class="bx bx-envelope"></i>
                    </div>  
                    <div class="input-box">
                    <input type="password" name="password" placeholder="Hasło" class="input-field" required>
                    <i class="bx bx-lock-alt"></i>
                    </div>  
                    <div class="input-box">
                    <button type="submit" name="register" class="submit">Zarejestruj się</button>
                </form>
       
            </div>
        </div>

   
</div>   


<script>
   
   function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if(i.className === "nav-menu") {
        i.className += " responsive";
    } else {
        i.className = "nav-menu";
    }
   }
 
</script>



</body>
</html>