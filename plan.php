<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Arkam Céline, Ayash Waseel" />
    <meta name="description" content="Plan du site de prévisions météorologiques pour la France" />
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg" />
    <title>Plan du site - Météo France</title>

    <style>
.plan-lien {
    text-align: center;
    margin-top: 40px;
}

.plan-lien h2 {
    font-size: 2em;
    margin-bottom: 20px;
    color: #14394f;
}

.plan-lien ul {
    list-style: none;
    padding: 0;
}

.plan-lien li {
    margin: 12px 0;
}

.plan-lien a {
    text-decoration: none;
    color: #14394f;
    font-size: 1.2em;
    transition: color 0.3s ease;
}

.plan-lien a:hover {
    color: #2e7d32; 
}


    </style>
</head>

<body>
    <header>
        <a href="index.php"><img src="images/logosite.png" alt="Logo-Météo France" /></a>
        <nav>
            <ul>
                <li><p><a href="index.php">Accueil</a></p></li>
                <li><p><a href="meteo.php">Météo</a></p></li>
                <li><p><a href="statistiques.php">Statistiques</a></p></li>
                <li><p><a href="nous.php">À propos de nous</a></p></li>
                <li><p><a href="tech.php">Page tech</a></p></li>
            </ul>
        </nav>
    </header>

    <main>
    <h1>Plan du site</h1>

    <section class="plan-lien">
        <h2>Sommaire</h2>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="meteo.php">Météo</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li><a href="nous.php">À propos de nous</a></li>
            <li><a href="tech.php">Page tech</a></li>
            <li><a href="plan.php">Plan du site</a></li>
        </ul>
    </section>
</main>



    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Aide ?</h4>
                <p>Réalisé par Céline ARKAM - Waseel AYASH ®</p>
                <a href="nous.php">À propos de nous</a>
            </div>

            <div class="footer-section">
                <h4>Informations</h4>
                <a href="tech.php">Page tech</a>
                <a href="plan.php">Plan du site</a>
            </div>

            <div class="footer-section">
                <h4>Organisme</h4>
                <p>CY Cergy Paris Université © 2025</p>
                <p>Mis à jour le : <strong>20/04/2025</strong></p>
            </div>
        </div>
    </footer>
</body>

</html>
