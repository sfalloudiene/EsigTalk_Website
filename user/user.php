<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page - Apprenti ou Tuteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-light p-3">
            <h4 class="text-primary">Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="afficherSection('accueil', this)">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('boite-reception', this)">Boîte de réception</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('nouveau-message', this)">Nouveau message</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('messages-favoris', this)">Messages favoris</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('messages-envoyes', this)">Messages envoyés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('profil', this)">
                        <img src="user-icon.png" alt="User Icon" style="width: 20px; height: 20px; margin-right: 8px;">
                        Profil
                    </a>
                </li>
            </ul>
            <hr>
            <a href="deconnexion.php" class="btn btn-danger mt-3">Déconnexion</a>
        </nav>

        <!-- Contenu Principal -->
        <div class="content p-4">
            <!-- Accueil -->
            <section id="accueil">
                <h2>Accueil</h2>
                <p>Bienvenue dans votre espace personnel.</p>
            </section>

            <!-- Boîte de réception -->
            <section id="boite-reception" style="display: none;">
                <h2>Boîte de réception</h2>
                <p>Voici tous vos messages reçus.</p>
            </section>

            <!-- Nouveau message -->
            <section id="nouveau-message" style="display: none;">
                <h2>Nouveau message</h2>
                <p>Rédigez un nouveau message ici.</p>
            </section>

            <!-- Messages favoris -->
            <section id="messages-favoris" style="display: none;">
                <h2>Messages favoris</h2>
                <p>Liste de vos messages favoris.</p>
            </section>

            <!-- Messages envoyés -->
            <section id="messages-envoyes" style="display: none;">
                <h2>Messages envoyés</h2>
                <p>Voici tous les messages que vous avez envoyés.</p>
            </section>

            <!-- Profil -->
            <section id="profil" style="display: none;">
                <h2>Profil</h2>
                <p>Informations de votre profil.</p>
            </section>
        </div>
    </div>

    <script>
        function afficherSection(sectionId, element) {
            // Masquer toutes les sections
            document.querySelectorAll('.content section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Afficher la section sélectionnée
            document.getElementById(sectionId).style.display = 'block';
            
            // Retirer la classe active de tous les liens
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Ajouter la classe active au lien cliqué
            element.classList.add('active');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
