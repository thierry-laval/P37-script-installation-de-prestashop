<?php
define('MSG_EXTENSION_ACTIVE', "est activée");
define('MSG_EXTENSION_INACTIVE', "n'est pas activée");

/**
 * Vérifie si une extension PHP est chargée et retourne un message formaté
 * @param string $extension Nom de l'extension à vérifier
 * @return string Message HTML formaté
 */
function verifierExtension(string $extension): string {
    $isLoaded = extension_loaded($extension);
    $status = $isLoaded ? 'success' : 'error';
    $message = $isLoaded ? MSG_EXTENSION_ACTIVE : MSG_EXTENSION_INACTIVE;
    return sprintf('<li class="extension-%s">%s %s</li>', $status, $extension, $message);
}
?>
<!DOCTYPE html>
<html lang="fr">
<!--https://github.com/thierry-laval-->

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assistant de téléchargement PrestaShop</title>
        <link rel="icon" href="https://raw.githubusercontent.com/thierry-laval/P00-mes-archives/master/images/favicon-thierrylaval.ico">
        <meta name="description" content="Simplifiez l'installation de PrestaShop avec mon script. Téléchargez, dézippez et installez la version souhaitée depuis GitHub. Compatibilité PHP requise.">
        <meta http-equiv="Strict-Transport-Security" content="max-age=31536000; includeSubDomains; preload">
        <style>
            /* Déclaration des variables CSS */
            :root {
                --background-color: #41A1E8;
                /* Couleur de fond principale */
                --container-background: white;
                /* Couleur de fond du conteneur */
                --button-background: #28a745;
                /* Couleur de fond des boutons */
                --button-hover: #218838;
                /* Couleur de fond des boutons au survol */
                --border-color: #ccc;
                /* Couleur des bordures */
                --alert-color: red;
                /* Couleur des alertes */
                --info-background: #f8f9fa;
                /* Couleur de fond pour les informations */
                --attention-background: #fff3cd;
                /* Couleur de fond pour les messages d'attention */
                --attention-border: #ffeeba;
                /* Couleur de bordure pour les messages d'attention */
                --font-size-base: 14px;
                /* Taille de police de base */
            }

            /* Styles globaux */
            body {
                background-color: var(--background-color);
                /* Application de la couleur de fond définie */
                padding: 30px;
                /* Espacement intérieur autour du corps de la page */
                font-size: var(--font-size-base);
                /* Taille de police définie dans :root */
                margin: 0;
                /* Réinitialisation de la marge par défaut du body */
            }

            .container {
                background: var(--container-background);
                /* Couleur de fond du conteneur */
                padding: 25px;
                /* Espacement intérieur du conteneur */
                border-radius: 12px;
                /* Arrondissement des coins du conteneur */
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                /* Ombre portée pour donner de la profondeur */
                width: 90%;
                /* Largeur responsive de 90% */
                max-width: 500px;
                /* Largeur maximum pour les écrans larges */
                margin: 0 auto;
                /* Centrage du conteneur sur la page */
            }

            select,
            input[type="submit"],
            button {
                flex: 1;
                /* Équilibrage de l'espace entre les éléments flexibles */
                margin: 0 5px;
                /* Espacement horizontal entre les éléments */
                padding: 12px;
                /* Espacement intérieur des éléments */
                border-radius: 6px;
                /* Arrondissement des coins des éléments */
                border: 1px solid var(--border-color);
                /* Bordure autour des éléments */
                transition: background-color 0.3s;
                /* Transition douce pour le changement de couleur de fond */
            }

            input[type="submit"] {
                background-color: var(--button-background);
                /* Couleur de fond du bouton de soumission */
                color: white;
                /* Couleur du texte du bouton */
                border: none;
                /* Suppression de la bordure par défaut */
                cursor: pointer;
                /* Changement du curseur au survol */
            }

            input[type="submit"]:hover {
                background-color: var(--button-hover);
                /* Changement de couleur de fond au survol */
            }

            .info-box {
                background-color: var(--info-background);
                /* Couleur de fond de la boîte d'informations */
                border-radius: 12px;
                /* Arrondissement des coins de la boîte d'informations */
                padding: 10px 30px;
                /* Espacement intérieur de la boîte */
                margin-top: 15px;
                /* Espacement supérieur de la boîte */
            }

            .extensions {
                text-align: left;
                /* Alignement du texte à gauche */
                font-size: 12px;
                /* Taille de police pour la liste des extensions */
            }

            .extensions ul {
                display: flex;
                /* Affichage en flex pour la liste */
                flex-wrap: wrap;
                /* Permet aux éléments de s'enrouler sur plusieurs lignes */
                padding: 20px;
                /* Espacement autour de la liste */
                margin: 0;
                /* Suppression de la marge par défaut */
            }

            .info-box {
                background-color: var(--info-background);
                border-radius: 12px;
                padding: 20px;
                margin-top: 20px;
            }

            .version-info {
                margin-bottom: 15px;
            }

            .version-number {
                font-weight: bold;
                color: var(--primary-color);
                margin-left: 8px;
            }

            .extensions-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 10px;
                padding: 0;
                margin: 0;
            }

            /* Classes d'accessibilité */
            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            /* Thème sombre/clair */
            :root[data-theme="light"] {
                --text-color: #333;
                --bg-color: #fff;
                --card-bg: #f8f9fa;
                --hover-bg: #e9ecef;
            }

            :root[data-theme="dark"] {
                --text-color: #f8f9fa;
                --bg-color: #1a1a1a;
                --card-bg: #2d2d2d;
                --hover-bg: #3d3d3d;
            }

            /* Bouton de thème */
            .theme-toggle {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 1000;
            }

            .theme-button {
                background: none;
                border: none;
                padding: 0.5rem;
                font-size: 1.5rem;
                cursor: pointer;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: var(--card-bg);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .theme-button:hover {
                transform: scale(1.1);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .theme-button:focus {
                outline: 2px solid var(--primary-color);
                outline-offset: 2px;
            }

            /* Animations améliorées */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .extensions-list li {
                display: flex;
                align-items: center;
                padding: 12px;
                border-radius: 8px;
                background-color: var(--card-bg);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                animation: fadeIn 0.5s ease-out;
                animation-fill-mode: both;
                position: relative;
                overflow: hidden;
            }

            .extensions-list li:hover {
                transform: translateY(-3px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                background-color: var(--hover-bg);
            }

            .extension-success {
                color: var(--success-color, #28a745);
                position: relative;
            }

            .extension-error {
                color: var(--error-color, #dc3545);
                position: relative;
            }

            .icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                margin-right: 12px;
                border-radius: 50%;
                font-weight: bold;
                transition: all 0.3s ease;
                position: relative;
            }

            .icon::before {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: 50%;
                background: conic-gradient(from 0deg, currentColor, transparent);
                animation: rotate 2s linear infinite;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .extensions-list li:hover .icon::before {
                opacity: 0.2;
            }

            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }

            .extension-success .icon {
                background-color: var(--success-color, #28a745);
                color: white;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
            }

            .extension-error .icon {
                background-color: var(--error-color, #dc3545);
                color: white;
                box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
            }

            /* Amélioration de l'accessibilité */
            .extensions-list li:focus-within {
                outline: 2px solid var(--primary-color);
                outline-offset: 2px;
            }

            [role="status"] {
                position: absolute !important;
                width: 1px !important;
                height: 1px !important;
                padding: 0 !important;
                margin: -1px !important;
                overflow: hidden !important;
                clip: rect(0, 0, 0, 0) !important;
                white-space: nowrap !important;
                border: 0 !important;
            }

            .footer {
                text-align: center;
                margin-top: 30px;
                padding: 20px 0;
                border-top: 1px solid var(--border-color);
            }

            .copyright a {
                color: var(--primary-color);
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .copyright a:hover {
                color: var(--primary-color-dark);
            }

            .support {
                margin: 15px 0;
                font-weight: bold;
            }

            .donate-button {
                display: inline-block;
                padding: 8px 16px;
                background-color: var(--button-background);
                color: white;
                text-decoration: none;
                border-radius: 20px;
                transition: all 0.2s ease;
            }

            .donate-button:hover {
                background-color: var(--button-hover);
                transform: scale(1.05);
            }

            .validation {
                margin-top: 20px;
            }

            .footer {
                text-align: center;
                /* Alignement du texte au centre */
                margin-top: 10px;
                /* Espacement supérieur pour le footer */
                font-size: 0.9em;
                /* Taille de police légèrement plus petite pour le footer */
            }

            form {
                display: flex;
                /* Affichage en flex pour le formulaire */
                justify-content: space-between;
                /* Espacement entre les éléments du formulaire */
                align-items: center;
                /* Alignement vertical des éléments au centre */
                margin-bottom: 15px;
                /* Espacement inférieur du formulaire */
            }

            .alert {
                color: var(--alert-color);
                /* Couleur du texte d'alerte */
                font-weight: bold;
                /* Mise en gras du texte d'alerte */
                margin-top: 10px;
                /* Espacement supérieur pour le texte d'alerte */
            }

            h1 {
                font-size: 20px;
                /* Taille de police pour les titres de premier niveau */
                margin: 10px;
                /* Espacement autour des titres de premier niveau */
            }

            h2 {
                font-size: 15px;
                /* Taille de police pour les titres de second niveau */
                margin: 10px;
                /* Espacement autour des titres de second niveau */
            }

            .attention {
                padding: 10px;
                /* Espacement intérieur pour les messages d'attention */
                background-color: var(--attention-background);
                /* Couleur de fond pour les messages d'attention */
                border: 1px solid var(--attention-border);
                /* Bordure pour les messages d'attention */
                border-radius: 5px;
                /* Arrondissement des coins pour les messages d'attention */
                margin: 15px 0;
                /* Espacement vertical pour les messages d'attention */
            }

            /* Styles pour les liens */
            .link-button {
                display: inline-block;
                /* Permet d'ajouter du padding et de la marge */
                padding: 2px 10px;
                /* Ajoute du rembourrage autour du texte */
                margin: 1px 0;
                /* Ajoute de l'espacement entre les liens */
                color: #41A1E8;
                /* Couleur du texte */
                text-decoration: none;
                /* Supprime le soulignement */
                border: 1px solid transparent;
                /* Ajoute une bordure transparente pour l'espacement */
                border-radius: 4px;
                /* Arrondit les coins */
                transition: background-color 0.3s;
                /* Transition pour le changement de couleur de fond */
            }

            /* Style au survol */
            .link-button:hover {
                background-color: #e9ecef;
                /* Change la couleur de fond au survol */
            }

            /* Style de l'accordéon */
            .accordion-button {
                cursor: pointer;
                /* Ajout du curseur main */
                padding: 12px;
                font-size: 16px;
                background-color: #007bff;
                color: white;
                border: none;
                text-align: center;
                width: 100%;
                outline: none;
                transition: background-color 0.3s;
                border-radius: 5px;
            }

            .accordion-button:hover {
                background-color: #0056b3;
            }

            .accordion-content {
                display: none;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f9f9f9;
            }

            /* Style des icônes de validation */
            .icon-ok {
                color: green;
            }

            .icon-not-ok {
                color: red;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <img src="https://thierrylaval.dev/wp-content/uploads/2022/04/Logo-Developpeur-web.png" alt="Logo Développeur Web de Thierry Laval" width="300" height="100" style="max-width: 100%; height: auto; display: block; margin: 0 auto;" loading="lazy">
            <h1>Assistant de téléchargement PrestaShop</h1>
            <h2>Télécharge | Dézippe | Installe</h2>
            <p>Ce script a été créé pour faciliter le téléchargement et l'installation des différentes versions de PrestaShop directement depuis le dépôt GitHub.</p>
            <p><strong>Veuillez choisir une version</strong></p>
            <!-- PHP code pour télécharger et décompresser -->
            <?php
            // Obtenir les versions disponibles de PrestaShop avec cURL
            function getPrestaShopVersions()
            {
                $url = 'https://api.github.com/repos/PrestaShop/PrestaShop/tags';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: request']);
                $json = curl_exec($ch);
                curl_close($ch);

                if (!$json) {
                    return [];
                }

                $tags = json_decode($json, true);
                return array_map(fn($tag) => $tag['name'], $tags);
            }

            // Télécharger une version de PrestaShop avec cURL et gestion des erreurs
            function downloadPrestaShop($version)
            {
                $url = "https://github.com/PrestaShop/PrestaShop/releases/download/{$version}/prestashop_{$version}.zip";
                $zipFile = "prestashop_{$version}.zip";

                $ch = curl_init($url);
                $fp = fopen($zipFile, 'w');

                if (!$fp) {
                    echo "Erreur : Impossible de créer le fichier {$zipFile}. Vérifiez les permissions.";
                    return false;
                }

                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_FAILONERROR, true);

                $success = curl_exec($ch);

                if (!$success) {
                    $error_msg = curl_error($ch); // Récupère l'erreur cURL
                    curl_close($ch);
                    fclose($fp);
                    unlink($zipFile); // Supprime le fichier corrompu
                    echo "Erreur lors du téléchargement de PrestaShop : $error_msg";
                    return false;
                }

                curl_close($ch);
                fclose($fp);

                if (!file_exists($zipFile) || filesize($zipFile) < 1024) {
                    unlink($zipFile);
                    echo "Erreur : Le fichier téléchargé est invalide ou corrompu.";
                    return false;
                }

                return $zipFile;
            }

            // Décompresser le fichier ZIP et proposer l'installation
            function unzipPrestaShop($zipFile)
            {
                $zip = new ZipArchive;
                if ($zip->open($zipFile) === TRUE) {
                    $zip->extractTo('.');
                    $zip->close();
                    unlink($zipFile);
                    echo "Décompression réussie !<br>";

                    echo "<form method='post'>";
                    echo "Voulez-vous démarrer l'installation de PrestaShop maintenant ?";
                    echo "<input type='hidden' name='redirect' value='index.php'>";
                    echo "<input type='submit' value='OUI' name='reponse'>";
                    echo "<input type='submit' value='NON' name='reponse'>";
                    echo '</form>';
                } else {
                    echo "Échec de la décompression.";
                }
            }

            // Vérifier la disponibilité d'une extension PHP
            function checkExtension($extension)
            {
                return extension_loaded($extension)
                    ? "<b><font color=green>extension $extension : OK</font><br></b>"
                    : "<b><font color=red>extension $extension : NOK</font><br></b>";
            }

            // Fonction pour arrêter le script proprement
            function endScript()
            {
                echo 'Fin du script';
                exit();
            }

            // Gestion des requêtes POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $version = htmlspecialchars($_POST['version'] ?? '', ENT_QUOTES, 'UTF-8');
                $unzip = htmlspecialchars($_POST['unzip'] ?? '', ENT_QUOTES, 'UTF-8');
                $redirect = htmlspecialchars($_POST['redirect'] ?? '', ENT_QUOTES, 'UTF-8');
                $reponse = $_POST['reponse'] ?? '';

                if (!empty($version) && empty($unzip) && empty($redirect)) {
                    $zipFile = downloadPrestaShop($version);
                    if ($zipFile) {
                        echo "<div>Téléchargement de {$zipFile} terminé.</div>";
                        echo '<div>Voulez-vous décompresser le fichier téléchargé ?</div><br>';
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='version' value='{$version}'>";
                        echo "<input type='hidden' name='unzip' value='{$zipFile}'>";
                        echo "<input type='submit' style='background-color: green; color: white; padding: 10px 15px; border-radius: 5px;' value='OUI' name='reponse'>";
                        echo "<input type='submit' style='background-color: red; color: white; padding: 10px 15px; border-radius: 5px;' value='NON' name='reponse'>";
                        echo '</form>';
                    } else {
                        echo "Erreur lors du téléchargement de PrestaShop.";
                    }
                } elseif (!empty($unzip) && empty($redirect)) {
                    if ($reponse == 'OUI') {
                        unzipPrestaShop($unzip);
                    } elseif ($reponse == 'NON') {
                        endScript();
                    }
                } elseif (!empty($redirect)) {
                    if ($reponse == 'OUI') {
                        header('Location: ' . $redirect);
                        exit(); // Stoppe immédiatement l'exécution après la redirection
                    } elseif ($reponse == 'NON') {
                        endScript();
                    }
                }
            } else {
                $versions = getPrestaShopVersions();
                if (empty($versions)) {
                    echo "Erreur : Impossible de récupérer les versions de PrestaShop.";
                    exit();
                }
                echo "<form method='post'>";
                echo "<select name='version' id='version'>";
                foreach ($versions as $version) {
                    echo "<option value='{$version}'>" . htmlspecialchars($version) . "</option>";
                }
                echo '</select>';
                echo "<button type='submit' style='background-color: #28A745; color: white; padding: 10px; border-radius: 5px; cursor: pointer;'>Télécharger</button>";
                echo '</form>';
            }
            ?>
            <div class="accordion">
                <button class="accordion-button" onclick="toggleAccordion()">Compatibilité de version PHP</button>
                <div class="accordion-content" id="accordion-content" style="display: none;">
                    <p>Mon script simplifie le déploiement de PrestaShop, mais il est essentiel de vérifier la compatibilité de ta version PHP. Une version incompatible peut entraîner des bugs à l'installation ou à l'utilisation de ta boutique.</p>
                    <ul>
                        <li><strong>PrestaShop 8.x</strong> : PHP 7.2.5 à 8.1</li>
                        <li><strong>PrestaShop 1.7.x</strong> : PHP 5.6 à 7.2 (selon la sous-version)</li>
                    </ul>
                    <p>Consulte la <a href="https://devdocs.prestashop.com/" target="_blank">documentation officielle</a> pour des informations à jour.</p>
                    <p>Avant d'installer PrestaShop, vérifie la compatibilité de ton hébergement et consulte les prérequis :</p>
                    <ul>
                        <li><a href="https://devdocs.prestashop-project.org/8/basics/installation/system-requirements/" target="_blank" title="Pré-requis pour PrestaShop 8.x" class="link-button">Pré-requis pour PrestaShop 8.x</a></li>
                        <li><a href="https://devdocs.prestashop-project.org/1.7/basics/installation/system-requirements/" target="_blank" title="Pré-requis pour PrestaShop 1.7.x" class="link-button">Pré-requis pour PrestaShop 1.7.x</a></li>
                    </ul>
                    <p>Prends le temps de lire les instructions et de vérifier ta version PHP avant de télécharger. Les versions 7.2 et inférieures peuvent présenter des failles de sécurité. Si nécessaire, mets à jour ton hébergement avec une version PHP adaptée.</p>
                    <p>Évite les versions <em>alpha</em>, <em>beta</em> et <em>rc</em> de PrestaShop, conçues pour les tests et instables en production. Opte toujours pour une version stable, sauf besoin spécifique d'une version en développement.</p>
                </div>
            </div>
            <script>
                function toggleAccordion() {
                    const accordionContent = document.getElementById("accordion-content");
                    if (accordionContent.style.display === "none") {
                        accordionContent.style.display = "block";
                    } else {
                        accordionContent.style.display = "none";
                    }
                }
            </script>
            <script>
                // Gestion du thème sombre/clair
                document.addEventListener('DOMContentLoaded', () => {
                    const themeSwitch = document.getElementById('theme-switch');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
                    
                    // Vérifie la préférence enregistrée
                    const savedTheme = localStorage.getItem('theme');
                    if (savedTheme) {
                        document.documentElement.setAttribute('data-theme', savedTheme);
                    }
                    
                    // Gestion du clic sur le bouton
                    themeSwitch.addEventListener('click', () => {
                        const currentTheme = document.documentElement.getAttribute('data-theme');
                        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                        
                        document.documentElement.setAttribute('data-theme', newTheme);
                        localStorage.setItem('theme', newTheme);
                        
                        // Annonce le changement de thème pour les lecteurs d'écran
                        const announcement = document.createElement('div');
                        announcement.setAttribute('role', 'status');
                        announcement.setAttribute('aria-live', 'polite');
                        announcement.className = 'sr-only';
                        announcement.textContent = `Thème ${newTheme === 'dark' ? 'sombre' : 'clair'} activé`;
                        document.body.appendChild(announcement);
                        
                        // Supprime l'annonce après 1 seconde
                        setTimeout(() => announcement.remove(), 1000);
                    });
                });
            </script>
            <div class="theme-toggle">
                <button id="theme-switch" class="theme-button" aria-label="Basculer le thème sombre/clair">
                    <span class="theme-icon">🌓</span>
                </button>
            </div>
            <div class="info-box">
                <div class="version-info">
                    <b>L'hébergement tourne actuellement en PHP :</b>
                    <span class="version-number"><?php echo phpversion(); ?></span>
                </div>
                <div class="extensions">
                    <?php
                    $requiredExtensions = [
                        'CURL' => 'Gestion des requêtes HTTP',
                        'DOM' => 'Manipulation du DOM',
                        'Fileinfo' => 'Information sur les fichiers',
                        'GD' => 'Manipulation d\'images',
                        'Iconv' => 'Conversion de caractères',
                        'Intl' => 'Internationalisation',
                        'JSON' => 'Gestion du format JSON',
                        'Mbstring' => 'Gestion des chaînes multi-octets',
                        'OpenSSL' => 'Sécurité SSL/TLS',
                        'PDO' => 'Accès aux bases de données',
                        'PDO_MYSQL' => 'Support MySQL',
                        'SimpleXML' => 'Manipulation XML',
                        'Zip' => 'Gestion des archives ZIP'
                    ];
                    echo '<ul class="extensions-list">';
                    foreach ($requiredExtensions as $extension => $description) {
                        $isLoaded = extension_loaded($extension);
                        $status = $isLoaded ? 'success' : 'error';
                        $icon = $isLoaded ? '✓' : '×';
                        echo sprintf(
                            '<li class="extension-%s" title="%s"><span class="icon">%s</span> %s</li>',
                            $status,
                            htmlspecialchars($description),
                            $icon,
                            $extension
                        );
                    }
                    echo '</ul>';
                    ?>
                </div>
            </div>
            <div class="footer">
                <p class="copyright">🇫🇷 &copy; 2026 <a href="https://thierrylaval.dev" target="_blank">thierrylaval.dev</a> - Licence : MIT 🇫🇷</p>
                <p class="support">Pour soutenir mon travail : <a href="https://revolut.me/laval96o" target="_blank" title="Offrez un café à l'auteur" class="donate-button">👉🏻 Offrez-moi un café ☕️</a></p>
                <div class="validation">
                    <img src="https://www.w3.org/assets/logos/w3c/w3c-developers-dark.svg" alt="W3C Developers" width="100" height="35">
                </div>
            </div>
        </div>
    </body>

</html>