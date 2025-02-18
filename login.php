<?php

session_start(); // Démarre une session PHP

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    // 1. Check all the inputs exist
    // 2. We check also if the $_POST are not empty because we load the page, the form is empty
    if(!empty($_POST["login"]) && !empty($_POST["password"])) {
   
        //Sanitize the inputs
        $login = trim($_POST["login"]);
        $login = htmlspecialchars(strip_tags($login));
        $password = trim($_POST["password"]);
        
        //SQL part
        try {
            require_once "connexion.php";
          
                //1. Prepare the query (Vérifier si l'utilisateur existe)
                $query = $db->prepare("SELECT * FROM users WHERE login = :login");
       
                //2. BindParam
                $query->bindParam(":login", $login, PDO::PARAM_STR);
                
                //3. Execute
                $query->execute();

                //4. Store the datas in a variable
                $user = $query->fetch(PDO::FETCH_ASSOC);
    
                //5. check the password input with the password in db
                if ($user && password_verify($password, $user["password"])) {
                    // store data of user in $_SESSION
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["success"] = "Welcome, " . htmlspecialchars($user["login"]) . "!";
                    //$_SESSION["user_name"] = $user["name"];
                    header("Location: index.php");
                    exit;
                } else {
                    $_SESSION["error"] = "Invalid login or password.";
                }
          
        } catch (PDOException $e) {
            $_SESSION["error"] = "Database error." . $e->getMessage();
        }
    } else {
        $_SESSION["error"] = "Please fill in all fields.";
    }

    header("Location: login.php");
    exit;
}

// Message d'erreur ou de succès
$message = "";
if (isset($_SESSION["error"])) {
    $message = "<p style='color: red;'>" . $_SESSION["error"] . "</p>";
    unset($_SESSION["error"]);
} elseif (isset($_SESSION["success"])) {
    $message = "<p style='color: green;'>" . $_SESSION["success"] . "</p>";
    unset($_SESSION["success"]);
}

include "includes/header.php";

?>

<main>
<div class="login-container">
    <h1>User Login</h1>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" name="login" id="login" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group-action">
            <button type="submit" class="btn">Login</button>
        </div>
    </form>

    <div class="error-message">
        <?= $message ?>
    </div>
</div>
</main>

<?php
// include "includes/footer.php";
?>