<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Page d'accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            margin: 0;
            padding: 0;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background-color: #fff;
        }

        header img {
            height: 100px; 
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 15px;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        main {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 20px;
        }

        /* Style mis à jour pour la div dans main */
        .meteo-checker {
            max-width: 600px;
            padding: 20px;
            background-color: #1a2a44; /* Fond bleu foncé */
            color: #ffffff; /* Texte blanc */
            border-radius: 10px; /* Coins arrondis */
            text-align: center;
        }

        .meteo-checker strong {
            color: #00c4b4; /* Turquoise pour le texte en gras (MeteoChecker) */
            font-size: 24px;
        }

        .meteo-checker .divider {
            width: 50px;
            height: 2px;
            background-color: #f5a623; /* Ligne orange */
            margin: 15px auto;
        }

        .meteo-checker p {
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        section {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        figure {
            margin: 20px 0 0 0;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        figcaption {
            margin-top: 10px;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php"><img src="images/logosite.png" alt="Logo-Météo France" /></a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="meteo.php">Meteo</a></li>
                <li><a href="statistiques.php">Statistiques</a></li>
                <li><a href="nous.php">À propos de nous</a></li>
            </ul>
        </nav>
    </header>

    <h1>Bienvenue sur la page d'accueil</h1>

    <main>
        <div class="meteo-checker">
            <p><strong>MeteoChecker</strong> vous offre une expérience météo inégalée, simplifiant vos prévisions climatiques au quotidien.</p>
            <div class="divider"></div>
            <p>Plongez dans une interface intuitive et pratique qui vous permet d’accéder rapidement aux informations météo les plus fiables pour toute la France. 
                Grâce à notre outil de recherche, découvrez les prévisions détaillées pour votre ville en quelques clics seulement.
                Suivez en temps réel les évolutions des conditions climatiques et adaptez vos déplacements en fonction des dernières données. MeteoChecker va au-delà de la simple consultation de la météo, en vous fournissant également des statistiques approfondies pour mieux comprendre les tendances météorologiques dans les endroits les plus populaires.
                Que vous planifiiez votre journée ou que vous souhaitiez anticiper les conditions de demain, MeteoChecker vous aide à prendre les meilleures décisions pour vos activités quotidiennes. Découvrez comment notre plateforme rend la météo plus accessible et fiable que jamais. C’est parti !</p>
        </div>
    </main>

    <section>
        <p>Laissez ces images illustrer les différents visages du temps qui fait notre quotidien.</p>
        <?php
        $dossier = 'images/images-aleatoires/';
        $fichiers = array_diff(scandir($dossier), ['.', '..']);

        $images = array_filter($fichiers, function($file) use ($dossier) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            return in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        $legendes = [
            'image1.jpg' => 'Ciel gris et éolienne en mouvement.',
            'image2.jpg' => 'Un ciel bleu , promesse d’une belle journée.',
            'image3.jpg' => 'Fleurs estivales sous un ciel d’été éclatant.',
            'image4.jpg' => 'Gouttes de pluie.',
            'image5.jpg' => 'Brouillard en forêt silencieuse.'
        ];

        if (!empty($images)) {
            $imageAleatoire = $images[array_rand($images)];
            echo '<figure>';
            echo '<img src="' . $dossier . $imageAleatoire . '" alt="Image météo">';
            if (isset($legendes[$imageAleatoire])) {
                echo '<figcaption>' . $legendes[$imageAleatoire] . '</figcaption>';
            }
            echo '</figure>';
        } else {
            echo "<p>Aucune image trouvée.</p>";
        }
        ?>
    </section>
    <footer>
  <div class="footer-container">
    <div class="footer-section">
      <h4>Aide ?</h4>
      <p>Réalisé par Céline ARKAM - Waseel AYASH ®</p>
      <a href="nous.php">à propos de nous</a>
    </div>

    <div class="footer-section">
      <h4>Informations</h4>
     <a href="tech.php">Page tech</a>
      <a href="#">Plan du Site</a>
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