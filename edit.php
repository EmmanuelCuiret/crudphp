<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start(); // Démarre une session PHP

try {
    require_once "connexion.php";
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérification que l'ID est bien dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
   echo "Erreur : ID non spécifié.";
   exit;
}

$id = (int) $_GET['id']; // Convertir en entier pour éviter les injections SQL

try {

   //1. Prepare the query
   $statement = $db->prepare("select * from news where id = :id");

   //2. BindParam
   $statement->bindParam(":id", $id, PDO::PARAM_INT);

   //3. Execute
   $statement->execute();

   //4. Store data in a $variable
   $new = $statement->fetch(PDO::FETCH_ASSOC);

  // Vérifier si la news existe
   if (!$new) {
      echo "Error: The news with the ID $id does not exist.";
      exit;
   }

} catch (PDOException $e) {
   echo "Erreur : " . $e->getMessage();
   exit;
}

//Vérifications et mise à jour des données
if (
    isset($_POST["name"], $_POST["description"], $_POST["category"]) &&
    !empty($_POST["name"]) &&
    !empty($_POST["description"])
) {
    // Nettoyage et sécurisation des entrées utilisateur
    $name = htmlspecialchars(strip_tags($_POST["name"]));
    $description = htmlspecialchars(strip_tags($_POST["description"]));
    $category = htmlspecialchars(strip_tags($_POST["category"]));
    
    try {

        // Préparation de la requête SQL
        $query =  $db->prepare("UPDATE news SET name = :name, description = :description, category = :category WHERE id=:id");
        
        // Liaison des paramètres
        $query->bindParam(":name", $name, PDO::PARAM_STR);
        $query->bindParam(":description", $description, PDO::PARAM_STR);
        $query->bindParam(":category", $category, PDO::PARAM_STR);
        $query->bindParam(":id", $id, PDO::PARAM_INT);

        // Exécution de la requête
        if (!$query->execute()) {
            print_r($query->errorInfo()); // Affiche les erreurs SQL
            die("Database update failed.");
        }
        
        // Redirection avec un message de confirmation
        //header("Location: index.php?success=1");
        $_SESSION['success'] = "The news has been successfully updated!";
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
<form method="post" action="edit.php?id=<?=$id?>" class="form-container">
    <div class="form-group">
        <label for="name">Name :</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($new['name']) ?>" placeholder="Enter the name" required />
    </div>

    <div class="form-group">
        <label for="category">Category :</label>
        <select name="category" id="category">
          <option value="" <?=($new['category'] == '') ? 'selected' : '' ?>>None</option>
          <option value="tech" <?=($new['category'] == 'tech') ? 'selected' : '' ?>>Tech</option>
          <option value="sport" <?=($new['category'] == 'sport') ? 'selected' : '' ?>>Sport</option>
          <option value="economy" <?=($new['category'] == 'economy') ? 'selected' : '' ?>>Économie</option>
        </select>
    </div>

    <div class="form-group">
        <label for="description">Description :</label>
        <textarea type="texte" rows="10" name="description" id="description" placeholder="Enter the description" required><?= htmlspecialchars($new['description']) ?></textarea>
    </div>

    <div class="form-group-action">
        <input type="submit" value="Save">
        <button type="button" onclick="window.location.href='index.php'">Cancel</button>
    </div>
</form>
</main>

<?php
   //include "includes/footer.php";
?>
