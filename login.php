<?php
session_start();
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}


$conn = new mysqli('localhost', 'root', '', 'geoguessr');
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();
    
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['message'] = "Nieprawidłowa nazwa użytkownika lub hasło.";
        header("Location: login.php");
        exit();
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
    <link rel="stylesheet" href="css/styl3.css">
    <title>MountainGuessr - Logowanie</title>
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



       
       
    </nav>

    <div class="form-box">
            <div class="login-container" id="login">
            <div class="top">
                <span>Nie masz konta? <a href="register.php">Zarejestruj się</a></span>
                <header>Logowanie</header>
            </div>
                <?php if (isset($_SESSION['message'])): ?>
                    <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
                <?php endif; ?>
                <form action="login.php" method="POST">
                <div class="input-box">
                    <input type="text" name="username" placeholder="Nazwa użytkownika" class="input-field" required>
                    <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                    <input type="password" name="password" placeholder="Hasło" class="input-field" required>
                    <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                    <button type="submit" name="login" class="submit">Zaloguj się</button>
           
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