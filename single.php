<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connexion.php";

// open the $_SESSION
session_start(); // Démarre une session PHP

// check if $_GET is empty
if (empty($_GET["id"]) || !ctype_digit($_GET["id"])) {
  echo "ID invalide ou non fourni.";
  exit;
}

$id = (int) $_GET["id"]; // Assure que c'est bien un entier

if(isset($_GET['id'])) {

  $new = null;

  // Interact with the database
  try {
    require_once "connexion.php";
    //1. Prepare the query
    $statement = $db->prepare("select * from news where id = :id");

    //2. BindParam
    $statement->bindParam(":id", $_GET['id'], PDO::PARAM_INT);

    //3. Execute
    $statement->execute();

    //4. Store data in a $variable
    $new = $statement->fetch(PDO::FETCH_ASSOC);

    //Vérifier si l'utilisateur existe
    if (!$new) {
      echo "News not found";
      exit;
    }

  } catch (PDOException $e) {
    // We catch the error from PDO
    echo $e->getMessage();
    exit;
  }
}

// HTML part
include "includes/header.php";

?>
<main>
<h1>A news</h1>

<div class="form-container">
    <div class="form-group">
        <label>Name :</label>
        <input type="text" value="<?= htmlspecialchars($new['name']) ?>" disabled />
    </div>

    <div class="form-group">
      <label for="category">Category :</label>
      <select name="category" id="category" disabled>
          <option value="" <?=($new['category'] == '') ? 'selected' : '' ?>>None</option>
          <option value="tech" <?=($new['category'] == 'tech') ? 'selected' : '' ?>>Tech</option>
          <option value="sport" <?=($new['category'] == 'sport') ? 'selected' : '' ?>>Sport</option>
          <option value="economy" <?=($new['category'] == 'economy') ? 'selected' : '' ?>>Économie</option>
      </select>
    </div>

    <div class="form-group">
        <label>Description :</label>
        <textarea rows="10" disabled><?= htmlspecialchars($new['description']) ?></textarea>
    </div>

    <div class="form-group-action">
        <button type="button" onclick="window.location.href='index.php'">Back</button>
    </div>
</div>
</main>

<?php
    //include "includes/footer.php";
?>
