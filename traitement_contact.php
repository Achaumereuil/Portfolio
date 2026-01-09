<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données
    $nom = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $sujet = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation simple
    if (!empty($nom) && !empty($email) && !empty($sujet) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                // Préparation de la requête
                $sql = "INSERT INTO messages (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
                $stmt = $pdo->prepare($sql);

                // Liaison des paramètres
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':sujet', $sujet);
                $stmt->bindParam(':message', $message);

                // Exécution
                if ($stmt->execute()) {
                    // Redirection avec succès
                    header("Location: contact.html?status=success");
                    exit();
                } else {
                    header("Location: contact.html?status=error");
                    exit();
                }
            } catch (PDOException $e) {
                // En production, on loggerait l'erreur plutôt que de l'afficher
                header("Location: contact.html?status=db_error");
                exit();
            }
        } else {
            header("Location: contact.html?status=invalid_email");
            exit();
        }
    } else {
        header("Location: contact.html?status=empty_fields");
        exit();
    }
} else {
    // Si quelqu'un essaie d'accéder au fichier directement sans POST
    header("Location: contact.html");
    exit();
}
?>