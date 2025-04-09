<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Arkam Céline, Ayash Waseel" />
    <meta name="description" content="À propos des créateurs du site de prévisions météorologiques pour la France" />
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>À propos de nous - Météo France</title>
    <style>
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        header img {
            height: 100px; 
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }

        nav ul li {
            display: inline;
        }
    </style>
</head>

<body>
    <header>
        <a href="index.php"><img src="images/logosite.png" alt="Logo-Météo France" /></a>
        <nav>
        <ul>
            <li> <p><a href="index.php">Accueil</a></p> </li>
            <li> <p><a href="tech.php">Page tech</a></p></li>
            <li> <p><a href="meteo.php">Meteo</a></p></li>
            <li> <p><a href="statistiques.php">Statistiques</a></p></li>
            <li>  <p><a href="nous.php">à propos de nous</a></p></li>
        </ul>
    </nav>
    </header>

    <main>
        <h1>Bienvenue sur notre site de prévisions météorologiques. Merci pour votre visite !</h1>

        <section>
            <h2>À propos de nous</h2>
            <p>Nous sommes Arkam Céline et Ayash Waseel, deux étudiants en L2 Informatique passionnés par les sciences et déterminés à fournir des informations météorologiques fiables et accessibles à tous...</p>
        </section>

        <section>
            <h2>Avantages</h2>
            <ul>
                <li>Consultation facile des prévisions météo pour plusieurs jours</li>
                <li>Informations détaillées sur la température, l'humidité et la pression atmosphérique</li>
                <li>Affichage des prévisions heure par heure pour les jours à venir</li>
                <li>Carte interactive des conditions météorologiques en temps réel</li>
            </ul>
        </section>

        <section>
            <h2>Contact & Informations</h2>
            <p>Si vous avez des questions ou des suggestions, n'hésitez pas à nous contacter...</p>
        </section>
    </main>

    <footer>
        <div>
            <span>Réalisé par Arkam Céline & Ayash Waseel</span>
        </div>
    </footer>
</body>

</html>
