<?php
session_start();
include('param.inc.php'); // Inclut la connexion à la base de données

// Initialiser la variable de message d'erreur
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparation de la requête pour récupérer l'utilisateur
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérification
        if (password_verify($password, $user['password'])) {
            // Vérification du rôle de l'utilisateur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirection en fonction du rôle
            switch ($user['role']) {
                case 0:
                    $_SESSION['error_message'] = "Votre compte n'est pas encore activé. Veuillez contacter un administrateur.";
                    header("Location: connexion.php");
                    break;
                case 1:
                    header("Location: user/tdb_apprenti.php");
                    exit();
                case 2:
                case 3:
                    header("Location: user/tdb_tuteur.php");
                    exit();
                case 4:
                    header("Location: admin1.php");
                    exit();
                default:
                    $_SESSION['error_message'] = "Rôle inconnu. Veuillez contacter un administrateur.";
                    header("Location: connexion.php");
                    break;
            }
        } else {
            $_SESSION['error_message'] = "Mot de passe incorrect.";
            header("Location: connexion.php");
        }
    } else {
        $_SESSION['error_message'] = "Aucun utilisateur trouvé avec cet email.";
        header("Location: connexion.php");
    }
}
?>