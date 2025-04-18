<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="./css/styles.css" id="light-theme"/>
    <link rel="stylesheet" href="./css/alternative.css" id="dark-theme" disabled/>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.jpg"/>
    <title>Page d'accueil</title>
    <style>

       figure img {
         max-width: 80%;
         height: auto;
         border-radius: 12px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
         display: block; /* Ajouté */
         margin: 0 auto; /* Ajouté */
       }

       figcaption {
        margin-top: 12px;
        font-style: italic;
        color: #999;
        text-align: center;
      } 

    </style>
</head>
<body>
    <header>
      <a href="index.php" class="header-logo">
            <img src="images/logosite.png" alt="Logo-Météo France" class="header-img" style="height: 120px !important;">
      </a>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="meteo.php">Meteo</a></li>
                <li><a href="statistiques.php">Statistiques</a></li>
                <li><a href="nous.php">À propos de nous</a></li>
            </ul>
        </nav>
        <div id="theme-switcher" style="position: absolute; top: 20px; right: 20px;">
      <button onclick="setTheme('light')">☀️ Mode clair</button>
      <button onclick="setTheme('dark')">🌙 Mode sombre</button>
    </div>
    </header>

    <h1>Bienvenue sur la page d'accueil</h1>

    <main>
    <section>
     <h2 id="titre-index">
      La météo en un clic 
     </h2>
     <div style="width: 80px; height: 4px; background-color: #9c6500; margin: 0 auto 30px auto; border-radius: 2px;"></div>

       <p>
          <strong>MétéoChecker</strong> vous offre une expérience météo inégalée, simplifiant vos prévisions climatiques au quotidien.
          Plongez dans une interface intuitive et pratique qui vous permet d’accéder rapidement aux informations météo les plus fiables pour toute la France.
          Grâce à notre outil de recherche, découvrez les prévisions détaillées pour votre ville en quelques clics seulement.
          Suivez en temps réel les évolutions des conditions climatiques et adaptez vos déplacements en fonction des dernières données.
          MeteoChecker va au-delà de la simple consultation de la météo, en vous fournissant également des statistiques approfondies pour mieux comprendre les tendances météorologiques dans les endroits les plus populaires.
          Que vous planifiiez votre journée ou que vous souhaitiez anticiper les conditions de demain, MeteoChecker vous aide à prendre les meilleures décisions pour vos activités quotidiennes.
          Découvrez comment notre plateforme rend la météo plus accessible et fiable que jamais. C’est parti !
       </p>
    </section>

        

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
    </main>
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
     <a href="plan.php">Plan du site</a>
    </div>

    <div class="footer-section">
      <h4>Organisme</h4>
      <p>CY Cergy Paris Université © 2025</p>
      <p>Mis à jour le : <strong>20/04/2025</strong></p>
    </div>
  </div>
</footer>


<script>
function setTheme(mode) {
    const light = document.getElementById('light-theme');
    const dark = document.getElementById('dark-theme');

    if (mode === 'dark') {
        light.disabled = true;
        dark.disabled = false;
    } else {
        light.disabled = false;
        dark.disabled = true;
    }

    // Store choice in cookie (valid for 30 days)
    document.cookie = `theme=${mode}; path=/; max-age=${30 * 24 * 60 * 60}`;
}

// Get cookie value
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    return parts.length === 2 ? parts.pop().split(';').shift() : null;
}

// On page load, set theme from cookie
window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = getCookie('theme');
    if (savedTheme === 'dark') {
        setTheme('dark');
    } else {
        setTheme('light'); // Default to light
    }
});
</script>

<div id="cookie-modal">
  <div id="cookie-box">
    <p>This website uses cookies to ensure you get the best experience.</p>
    <button id="accept-cookie">Accept</button>
    <button id="decline-cookie">Decline</button>
  </div>
</div>

<script>
  const cookieModal = document.getElementById('cookie-modal');
  const acceptBtn = document.getElementById('accept-cookie');
  const declineBtn = document.getElementById('decline-cookie');

  if (!localStorage.getItem('cookieConsent')) {
    cookieModal.style.display = 'flex';
  }

  acceptBtn.addEventListener('click', () => {
    localStorage.setItem('cookieConsent', 'accepted');
    cookieModal.style.display = 'none';
  });

  declineBtn.addEventListener('click', () => {
    localStorage.setItem('cookieConsent', 'declined');
    cookieModal.style.display = 'none';
  });
</script>
</body>
</html>