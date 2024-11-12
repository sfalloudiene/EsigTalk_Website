<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Tuteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="tdb_tuteur.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-light p-3">
            <h4 class="text-primary">Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="afficherSection('messages-recus', this)">Messages Reçus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('messages-envoyes', this)">Messages Envoyés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('messages-non-lus', this)">Messages Non Lus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('equipes', this)">Equipes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="afficherSection('nouveau-message', this)">Nouveau Message</a>
                </li>
            </ul>
            <hr>
            <a href="deconnexion.php" class="btn btn-danger mt-3">Déconnexion</a>
        </nav>

        <!-- Contenu Principal -->
        <div class="content p-4">
            <h1>Bienvenue, [Nom du Tuteur]</h1>

            <!-- Section des Messages Reçus -->
            <section id="messages-recus">
                <h2>Vos Messages Reçus</h2>
                <div class="message non-lu">
                    <h3>Titre du message (Non lu)</h3>
                    <p><strong>Expéditeur:</strong> Apprenti</p>
                    <p><strong>Date:</strong> 01/11/2024</p>
                    <p><strong>Catégorie:</strong> Suivi</p>
                    <p>Contenu du message: Voici un message qui n'a pas encore été lu.</p>
                    <p><strong>Pièce jointe:</strong> <a href="document.pdf" target="_blank">document.pdf</a></p>
                    <div class="message-actions">
                        <a href="repondre.php?message_id=1" class="btn btn-primary btn-sm">Répondre</a>
                        <a href="supprimer.php?message_id=1" class="btn btn-danger btn-sm">Supprimer</a>
                    </div>
                </div>
            </section>

            <!-- Section des Messages Envoyés -->
            <section id="messages-envoyes" style="display: none;">
                <h2>Vos Messages Envoyés</h2>
                <div class="message">
                    <h3>Titre du message envoyé</h3>
                    <p><strong>Destinataire:</strong> Apprenti</p>
                    <p><strong>Date:</strong> 27/10/2024</p>
                    <p><strong>Catégorie:</strong> Info</p>
                    <p>Contenu du message: Voici le contenu du message que vous avez envoyé.</p>
                    <p><strong>Pièce jointe:</strong> <a href="document_envoye.pdf" target="_blank">document_envoye.pdf</a></p>
                </div>
            </section>

            <!-- Section des Messages Non Lus -->
            <section id="messages-non-lus" style="display: none;">
                <h2>Vos Messages Non Lus</h2>
                <div class="message non-lu">
                    <h3>Titre du message non lu</h3>
                    <p><strong>Expéditeur:</strong> Apprenti</p>
                    <p><strong>Date:</strong> 29/10/2024</p>
                    <p><strong>Catégorie:</strong> Info</p>
                    <p>Contenu du message: Voici un message qui n'a pas encore été lu.</p>
                </div>
            </section>

            <!-- Section Equipes -->
            <section id="equipes" style="display: none;">
                <h2>Vos Equipes</h2>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="#" onclick="afficherDetailsEquipe('equipe1')">Jean Dupont</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#" onclick="afficherDetailsEquipe('equipe2')">Marie Leroy</a>
                    </li>
                </ul>

                <div id="details-equipe" style="display: none; margin-top: 20px;">
                    <h3>Détails de l'Equipe</h3>
                    <p><strong>Apprenti:</strong> <span id="apprenti-nom"></span></p>
                    <p><strong>Email Apprenti:</strong> <span id="apprenti-email"></span></p>
                    <p><strong>Tuteur Entreprise:</strong> <span id="tuteur-entreprise-nom"></span></p>
                    <p><strong>Email Tuteur Entreprise:</strong> <span id="tuteur-entreprise-email"></span></p>
                    <p><strong>Tuteur Ecole:</strong> <span id="tuteur-ecole-nom"></span></p>
                    <p><strong>Email Tuteur Ecole:</strong> <span id="tuteur-ecole-email"></span></p>
                </div>
            </section>

            <!-- Section Nouveau Message -->
            <section id="nouveau-message" style="display: none;">
                <h2>Nouveau Message</h2>
                <form method="POST" action="envoyer_message.php" enctype="multipart/form-data">
                    <!-- Expéditeur -->
                    <div class="mb-3">
                        <label for="expediteur" class="form-label">Expéditeur</label>
                        <input type="email" class="form-control" id="expediteur" name="expediteur" placeholder="Votre email" required>
                    </div>

                    <!-- Destinataire(s) -->
                    <div class="mb-3">
                        <label for="destinataire1" class="form-label">Destinataire</label>
                        <input type="email" class="form-control" id="destinataire1" name="destinataire1" placeholder="Email du destinataire principal" required>
                        <label for="destinataire2" class="form-label">Destinataire secondaire (optionnel)</label>
                        <input type="email" class="form-control" id="destinataire2" name="destinataire2" placeholder="Email du destinataire secondaire">
                    </div>

                    <!-- Titre -->
                    <div class="mb-3">
                        <label for="sujet" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="sujet" name="sujet" placeholder="Sujet du message" required>
                    </div>

                    <!-- Catégorie -->
                    <div class="mb-3">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <select id="categorie" name="categorie" class="form-control" required>
                            <option value="Suivi">Suivi</option>
                            <option value="Info">Info</option>
                        </select>
                    </div>

                    <!-- Texte -->
                    <div class="mb-3">
                        <label for="contenu" class="form-label">Message</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="5" placeholder="Écrivez votre message ici" required></textarea>
                    </div>

                    <!-- Document (PDF) -->
                    <div class="mb-3">
                        <label for="document" class="form-label">Pièce jointe (PDF uniquement)</label>
                        <input type="file" class="form-control" id="document" name="document" accept=".pdf">
                    </div>

                    <!-- Date et heure d’envoi -->
                    <div class="mb-3">
                        <label for="date_heure" class="form-label">Date et heure d'envoi</label>
                        <input type="datetime-local" class="form-control" id="date_heure" name="date_heure" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </div>

    <script>
        function afficherSection(sectionId, element) {
            document.querySelectorAll('.content section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            element.classList.add('active');
        }

        function afficherDetailsEquipe(equipeId) {
            let apprentiNom, apprentiEmail, tuteurEntrepriseNom, tuteurEntrepriseEmail, tuteurEcoleNom, tuteurEcoleEmail;

            if (equipeId === 'equipe1') {
                apprentiNom = "Jean Dupont";
                apprentiEmail = "jean.dupont@exemple.com";
                tuteurEntrepriseNom = "Pierre Martin";
                tuteurEntrepriseEmail = "pierre.martin@entreprise.com";
                tuteurEcoleNom = "Sophie Durant";
                tuteurEcoleEmail = "sophie.durant@esigelec.fr";
            } else if (equipeId === 'equipe2') {
                apprentiNom = "Marie Leroy";
                apprentiEmail = "marie.leroy@exemple.com";
                tuteurEntrepriseNom = "Alice Robert";
                tuteurEntrepriseEmail = "alice.robert@entreprise.com";
                tuteurEcoleNom = "Michel Blanc";
                tuteurEcoleEmail = "michel.blanc@esigelec.fr";
            }

            document.getElementById("apprenti-nom").textContent = apprentiNom;
            document.getElementById("apprenti-email").textContent = apprentiEmail;
            document.getElementById("tuteur-entreprise-nom").textContent = tuteurEntrepriseNom;
            document.getElementById("tuteur-entreprise-email").textContent = tuteurEntrepriseEmail;
            document.getElementById("tuteur-ecole-nom").textContent = tuteurEcoleNom;
            document.getElementById("tuteur-ecole-email").textContent = tuteurEcoleEmail;

            document.getElementById("details-equipe").style.display = "block";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



