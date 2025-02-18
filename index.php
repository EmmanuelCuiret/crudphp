<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connexion.php";

// open the $_SESSION
session_start(); // Démarre une session PHP


$category = isset($_GET['category']) && !empty($_GET['category']) ? $_GET['category'] : '';
$sql = "select * from news where erased is null";
$params = [];

// Interact with the database
try {

    if($category){
        $sql .= " AND category = ?";
        $params[] = $category;
    }
  
    $statement = $db->prepare($sql);
    $statement->execute($params);
    $news = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  // We catch the error from PDO
  echo $e->getMessage();
  exit;
}

include "includes/header.php";
?>

<script>

function applyFilter() {
    
    const category = document.querySelector("#category").value;
    window.location.href = "index.php?category=" + encodeURIComponent(category);
}

function chooseDelete(newsId) {
    const modal = document.createElement("div");
    modal.innerHTML = `
        <div class="modal-overlay">
            <div class="modal">
                <p>Choose the type of deletion :</p>
                <button class="delete-soft" onclick="deleteNews(${newsId}, 'soft')">Logical deletion</button>
                <button class="delete-hard" onclick="deleteNews(${newsId}, 'hard')">Physical deletion</button>
                <button class="delete-cancel" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function deleteNews(newsId, type) {
    window.location.href = "delete.php?id=" + newsId + "&type=" + type;
}

function closeModal() {
    document.querySelector(".modal-overlay").remove();
}
</script>

<!-- The HTML begins here -->
<main>
	<h1>News list</h1>

    <label for="category">Filter by Category:</label>
    <select name="category" id="category" onchange="applyFilter()">
        <option value="" <?=($category == '') ? 'selected' : '' ?>>All Categories</option>
        <option value="tech" <?=($category == 'tech') ? 'selected' : '' ?>>Tech</option>
        <option value="sport" <?=($category == 'sport') ? 'selected' : '' ?>>Sport</option>
        <option value="economy" <?=($category == 'economy') ? 'selected' : '' ?>>Économie</option>
    </select>
    <!--
    <div style="text-align: right;">
        <a href="/create.php">Add</a>
    </div>
    -->
    <table>
        <thead>
            <th>Date</th>
            <th>Name</th>
            <th>Modified at</th>
            <?php  if($isConnected){ ?>
                <th>Updated at</th>
            <?php } ?>
        </thead>
        <!--display the datas -->
        <?php if (count($news) > 0) {
            foreach($news as $new) : ?>
            <tr>
                <td style="width: 200px">
                    <?php if (!empty($new['date'])): ?>
                        <?= htmlspecialchars($new['date']) ?>
                    <?php endif; ?>
                </td>
                <td><a href="/single.php?id=<?=$new['id']?>"><?= $new['name'] ?></td>
                <td style="width: 200px">
                    <?php  if (!empty($new['updated_at'])): ?>
                        <?= htmlspecialchars($new['updated_at']) ?>
                    <?php endif; ?>
               </td>
                <?php  if($isConnected){ ?>
                    <td>
                        <a href="/edit.php?id=<?=$new['id']?>">Edit</a>
                        <a href="#" onclick="chooseDelete(<?= $new['id'] ?>)">Delete</a>
                    </td>
                <?php } ?>
            </tr>
            <?php endforeach; ?>
        <?php } else { ?>
            <tr><td colspan="4" style="text-align:center">No news available</td></tr>
        <?php } ?>
    </table>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<p class='success-message'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']); // Supprime le message après affichage
    }

    if (isset($_SESSION['success_delete'])) {
        echo "<p class='success-message'>" . $_SESSION['success_delete'] . "</p>";
        unset($_SESSION['success_delete']); 
    }

    if (isset($_SESSION['success_signin'])) {
        echo "<p class='success-message'>" . $_SESSION['success_signin'] . "</p>";
        unset($_SESSION['success_signin']); 
    }


    ?>

</main>

<?php
   // include "includes/footer.php";
?>