<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start(); // Démarre une session PHP

if (
    isset($_POST["name"], $_POST["description"]) &&
    !empty($_POST["name"]) &&
    !empty($_POST["description"])
) {

    // Nettoyage et sécurisation des entrées utilisateur
    $name = htmlspecialchars(strip_tags($_POST["name"]));
    $description = htmlspecialchars(strip_tags($_POST["description"]));
    $category = htmlspecialchars(strip_tags($_POST["category"]));

    try {
        require_once "connexion.php";

        // Préparation de la requête SQL
        $query =  $db->prepare("INSERT INTO news (name, description, category) values (:name, :description, :category)");
        
        // Liaison des paramètres
        $query->bindParam(":name", $name, PDO::PARAM_STR);
        $query->bindParam(":description", $description, PDO::PARAM_STR);
        $query->bindParam(":category", $category, PDO::PARAM_STR);

        // Exécution de la requête
        if (!$query->execute()) {
            print_r($query->errorInfo()); // Affiche les erreurs SQL
            die("Database insert failed.");
        }
        
        // Redirection avec un message de confirmation
        //header("Location: index.php?success=1");
        $_SESSION['success'] = "The news has been successfully added!";
        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        exit;
    }
}

include "includes/header.php";

?>
<main>
<h1>Add a news</h1>

<form method="post" action="create.php" class="form-container">
    <div class="form-group">
        <label for="name">Name :</label>
        <input type="text" name="name" id="name" placeholder="Enter the name" required />
    </div>

    <div class="form-group">
        <label for="category">Category :</label>
        <select name="category" id="category">
          <option value="">None</option>
          <option value="tech">Tech</option>
          <option value="sport">Sport</option>
          <option value="economy">Économie</option>
        </select>
    </div>

    <div class="form-group">
        <label for="description">Description :</label>
        <textarea type="texte" rows="10" name="description" id="description" placeholder="Enter the description" required></textarea>
    </div>

    <div class="form-group-action">
        <input type="submit" value="Add">
        <button type="button" onclick="window.location.href='index.php'">Cancel</button>
    </div>
</form>
</main>
<?php
    //include "includes/footer.php";
?>
