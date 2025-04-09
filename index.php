<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Page d'accueil</title>
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

        div { max-width: 600px; margin: auto; padding: 20px; background: white; border-radius: 10px; }
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
<h1>Bienvenue sur la page d'accueil</h1> 
</body>
</html>
