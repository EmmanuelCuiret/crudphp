<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session (si nécessaire)
session_start();

// Vérifier si l'ID est bien présent dans l'URL
if (!isset($_GET['id'], $_GET['type']) || empty($_GET['id']) || empty($_GET['type'])) {
    die("ID or type missing.");
}

// Nettoyer et sécuriser les données
$id = intval($_GET['id']); // Convertit en entier pour éviter l'injection SQL
$type = $_GET['type'];

try {
    require_once "connexion.php";

    // Vérifier si l'élément existe
    $checkQuery = $db->prepare("SELECT * FROM news WHERE id = :id");
    $checkQuery->bindParam(":id", $id, PDO::PARAM_INT);
    $checkQuery->execute();

    if ($checkQuery->rowCount() === 0) {
        die("L'élément n'existe pas.");
    }

    if($type === 'soft') {
        // Suppression logique : on marque l'élément comme supprimé
        $query = $db->prepare("UPDATE news SET erased = '1' WHERE id = :id");
    } else {
        // Suppression physique : on supprime définitivement
        $query = $db->prepare("DELETE FROM news WHERE id = :id");
    }
     
    $query->bindParam(":id", $id, PDO::PARAM_INT);

    // Exécuter la requête
    if ($query->execute()) {
        // Redirection avec un message de succès
        $_SESSION['success_delete'] = "The news has been successfully deleted!";
        header("Location: index.php");
        exit;
    } else {
        die("Error while deleting.");
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>
