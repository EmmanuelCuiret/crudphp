<?php 


//Remember, when you encode new data to the DB : first sanitize, second filter
//NB: For the password, you have to encrypt it!!

session_start(); // Démarre une session PHP


include "includes/header.php";

if (isset($_GET["success"])) {
    echo "<p style='color: green;'>Compte créé avec succès !</p>";
}

if (
    isset( $_POST["email"], $_POST["login"], $_POST["password"]) &&
    !empty($_POST["email"]) &&
    !empty($_POST["login"]) &&
    !empty($_POST["password"])
) {

    // Nettoyage et sécurisation des entrées utilisateur
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $login = htmlspecialchars(strip_tags($_POST["login"]));
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash du mot de passe

    // Vérification email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email !");
    }

    try {

        require_once "connexion.php";
        
        // Vérification si l'email ou le login existent déjà
        $checkQuery = $db->prepare("SELECT id FROM users WHERE email = :email OR login = :login");
        $checkQuery->bindParam(":email", $email, PDO::PARAM_STR);
        $checkQuery->bindParam(":login", $login, PDO::PARAM_STR);
        $checkQuery->execute();

        if ($checkQuery->rowCount() > 0) {
            die("The email or login already exists !");
        }

         // Préparation de la requête SQL
         $query = $db->prepare("INSERT INTO users (login, `password`, email) VALUES (:login, :password, :email)");
        
         // Liaison des paramètres
         $query->bindParam(":email", $email, PDO::PARAM_STR);
         $query->bindParam(":login", $login, PDO::PARAM_STR);
         $query->bindParam(":password", $password, PDO::PARAM_STR);
 
         // Exécution de la requête
         if (!$query->execute()) {
             print_r($query->errorInfo()); // Affiche les erreurs SQL
             die("Database insert failed.");
         }
         
         // Stocker l'ID utilisateur en session après inscription
         $_SESSION["id"] = $db->lastInsertId();
 
        // Redirection avec un message de confirmation
        $_SESSION['success_signin'] = "Your account has been created successfully!";
        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit;
    }

}
?>
<main>
<div class="subscription-container">
    <h1>User Subscription</h1>

    <?= $message ?>

    <form method="post" action="subscription.php">
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" name="login" id="login" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group-action">
            <button type="submit" class="btn">Subscribe</button>
        </div>
    </form>
</div>
</main>

<?php
// include "includes/footer.php";
?>