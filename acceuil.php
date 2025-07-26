<?php

date_default_timezone_set('America/Anchorage'); 
$currentDate = date('l, F j, Y'); // Format: Day, Month Date, Year
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Chris Kindle</title>
    <style>
        /* Mise en page générale */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('fond.jpg');
            background-size: cover;
            background-position: center;
            overflow-x: hidden;
        }

        /* Logo textuel  */
        #logo {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%); 
            font-size: 4em; 
            font-weight: bold;
            color: RED;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            font-family: 'Courier New', Courier, monospace;
        }

        /* Bouton Paramètres */
        #settings-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 100;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #settings-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: rotate(45deg);
        }

        /* Panneau Paramètres */
        #settings-panel {
            position: fixed;
            top: 0;
            right: -250px;
            width: 250px;
            height: 100%;
            background: #fff;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2);
            transition: right 0.3s;
            z-index: 99;
            padding: 20px;
            box-sizing: border-box;
            color: #555;
        }

        #settings-panel.show {
            right: 0;
        }

        #settings-panel h2 {
            color: RED;
            border-bottom: 2px solid RED;
            padding-bottom: 10px;
        }

        #settings-panel a {
            display: block;
            padding: 12px;
            margin: 10px 0;
            background: #f9f9f9;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.2s;
            border: 1px solid #ccc;
        }

        #settings-panel a:hover {
            background: RED;
            color: #fff;
        }

        /* Contenu principal */
        .main-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding-top: 80px;
            animation: fadeIn 0.6s ease-out;
        }

        /* Grille des options */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            width: 80%;
            max-width: 900px;
            margin-top: 40px;
        }

        .option-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e74c3c;
        }

        .option-card:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .option-card h3 {
            margin: 0;
            color: RED;
        }

        /* Animation de fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Fenêtre de confirmation de déconnexion */
        #logout-confirmation {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 200;
        }
       /*confirmation de deconnexion*/ 
        #logout-confirmation .confirmation-box {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #logout-confirmation h3 {
            color: RED;
            margin-bottom: 20px;
        }

        #logout-confirmation button {
            background-color: RED;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
            font-size: 16px;
        }

        #logout-confirmation button:hover {
            background-color: #c0392b;
        }

        /* Style pour la section info contact */
        #contact-info {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            text-align: center;
        }

    </style>
</head>
<body>

    <div id="logo">
        Chris Kindle
    </div>

    <button id="settings-btn">⚙️</button>

    <div id="settings-panel">
        <h2>Paramètres</h2>
        <a href="modifier-coordonnees.php">Modifier coordonnées</a>
        <a href="logout.php" id="logout-btn">Se déconnecter</a>
    </div>

    <!-- Fenêtre de confirmation de déconnexion -->
    <div id="logout-confirmation">
        <div class="confirmation-box">
            <h3>Voulez-vous vraiment vous déconnecter ?</h3>
            <button id="confirm-logout">Oui</button>
            <button id="cancel-logout">Non</button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="main-content">
        <div id="welcome-message">
            <h2>Bienvenue sur l'administration de Chris Kindle</h2>
            <p>Date :<?php echo $currentDate; ?>. Un jour magique de Noël !</p>
            <p>Heure actuelle : <span id="current-time"></span></p>
        </div>

        <div class="options-grid">
            <div class="option-card" onclick="window.location.href='elfe.php'">
                <h3>Elfe</h3>
            </div>
            <div class="option-card" onclick="window.location.href='traineau.php'">
                <h3>Traineau</h3>
            </div>
            <div class="option-card" onclick="window.location.href='renne.php'">
                <h3>Renne</h3>
            </div>
            <div class="option-card" onclick="window.location.href='tournee.php'">
                <h3>Tournée</h3>
            </div>
            <div class="option-card" onclick="window.location.href='equipe.php'">
                <h3>Equipe</h3>
            </div>
            <div class="option-card" onclick="window.location.href='entrepot.php'">
                <h3>Entrepôt</h3>
            </div>
            <div class="option-card" onclick="window.location.href='intermittent.php'">
                <h3>Intermittent</h3>
            </div>
            <div class="option-card" onclick="window.location.href='jouet.php'">
                <h3>Jouet</h3>
            </div>
            <div class="option-card" onclick="window.location.href='cadeau.php'">
                <h3>Cadeau</h3>
            </div>
            <div class="option-card" onclick="window.location.href='enfant.php'">
                <h3>Enfant</h3>
            </div>
            <div class="option-card" onclick="window.location.href='atelier.php'">
                <h3>Atelier</h3>
            </div>

            <div class="option-card" onclick="window.location.href='requete.php'">
                <h3>Boite aux questions</h3>
            </div>
        </div>

        <!-- Section Contact -->
        <div id="contact-info">
            <h3>Contactez-nous :</h3>
            <p><strong>Numéro de téléphone:</strong> +2137553539095</p>
            <p><strong>Adresse:</strong> 1234 North Pole Blvd, Fairbanks, Alaska, USA</p>
            <p><strong>Email:</strong> <a href="mailto:Chris.Kindle@gmail.com">Chris.Kindle@gmail.com</a></p>
        </div>
    </div>

    <script>
        // Fonction pour afficher l'heure en temps réel
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('current-time').textContent = timeString;
        }

        // Appeler la fonction pour mettre à jour l'heure toutes les secondes
        setInterval(updateClock, 1000);

        // Initialiser l'heure au chargement de la page
        updateClock();
    </script>

    <script>
        // Gestion du panneau paramètres
        document.getElementById('settings-btn').addEventListener('click', function() {
            document.getElementById('settings-panel').classList.toggle('show');
        });

        // Affichage de la fenêtre de confirmation pour la déconnexion
        document.getElementById('logout-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-confirmation').style.display = 'flex';
        });

        // Annuler la déconnexion
        document.getElementById('cancel-logout').addEventListener('click', function() {
            document.getElementById('logout-confirmation').style.display = 'none';
        });

        // Confirmer la déconnexion
        document.getElementById('confirm-logout').addEventListener('click', function() {
            window.location.href = 'index.php'; // Redirige vers la page index.php
        });
    </script>

</body>
</html>

