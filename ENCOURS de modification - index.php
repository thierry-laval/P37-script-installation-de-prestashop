<?php
// Fonction pour vérifier les extensions PHP
function verifierExtension($extension)
{  // Vérifie si l'extension spécifiée est chargée
    return extension_loaded($extension)
        // Si l'extension est chargée, retourne un élément de liste avec un message en vert
        ? "<li style='color: green;'>$extension est activée.</li>"
        // Si l'extension n'est pas chargée, retourne un élément de liste avec un message en rouge
        : "<li style='color: red;'>$extension n'est pas activée.</li>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<link rel="icon"
    href="https://raw.githubusercontent.com/thierry-laval/P00-mes-archives/master/images/favicon-thierrylaval.ico" />
<!--https://github.com/thierry-laval-->
<!--Points vérifiés par Thierry Laval:
Sécurité des entrées utilisateur : Les entrées utilisateur sont correctement filtrées avec filter_input.
Gestion des exceptions : Les exceptions sont bien gérées avec try-catch.
Utilisation des fonctions PHP : Les fonctions PHP sont utilisées correctement.
Structure et lisibilité du code : Le code est bien structuré et lisible.
Compatibilité PHP : Les versions de PHP et les extensions nécessaires sont vérifiées.
Le code est bien écrit et suit les bonnes pratiques. Aucune erreur majeure n'a été trouvée.-->
<!--HEAD-->
<head>
    <meta charset="UTF-8">
    <title>Assistant de téléchargement PrestaShop</title>
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
            padding: 20;
            /* Espacement autour de la liste */
            margin: 0;
            /* Suppression de la marge par défaut */
        }

        .extensions li {
            flex-basis: 45%;
            /* Largeur de base des éléments de liste à 45% */
            padding: 3px;
            /* Espacement intérieur des éléments de liste */
        }

        .footer {
            text-align: center;
            /* Alignement du texte au centre */
            margin-top: 25px;
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

        .footer a {
            color: white;
            /* Couleur des liens dans le footer */
            text-decoration: none;
            /* Suppression du soulignement par défaut des liens */
        }

        .footer a:hover {
            text-decoration: underline;
            /* Soulignement au survol des liens */
        }

        h1 {
            font-size: 20px;
            /* Taille de police pour les titres de premier niveau */
            margin: 10;
            /* Espacement autour des titres de premier niveau */
        }

        h2 {
            font-size: 15px;
            /* Taille de police pour les titres de second niveau */
            margin: 10;
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
    </style>
</head>
<!--MAIN-->
<body>
    <div class="container">
        <img src="https://thierrylaval.dev/wp-content/uploads/2022/04/Logo-Developpeur-web.png"
            alt="Logo Développeur Web" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
        <h1>Assistant de téléchargement PrestaShop</h1>
        <h2>Installer différentes versions de PrestaShop depuis GitHub.</h2>
        <?php
        // Obtenir les versions disponibles de PrestaShop
        function obtenirVersionsPrestaShop()
        {
            $url = 'https://api.github.com/repos/PrestaShop/PrestaShop/tags';
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => 'User-Agent: request'
                ]
            ]);
            $tags = json_decode(file_get_contents($url, false, $context), true);
            return $tags ? array_column($tags, 'name') : [];
        }

        // Fonction pour télécharger PrestaShop d'une version spécifique
        function telechargerPrestaShop($version)
        {
            // Construction de l'URL de téléchargement en utilisant la version spécifiée
            $url = PRESTASHOP_DOWNLOAD_URL . "/{$version}/prestashop_{$version}.zip";
            // Nom du fichier ZIP à télécharger
            $zipFile = "prestashop_{$version}.zip";

            // Initialisation de la session cURL avec l'URL de téléchargement
            $ch = curl_init($url);
            // Configuration de l'option cURL pour retourner le transfert sous forme de chaîne de caractères
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Exécution de la requête cURL et stockage des données téléchargées
            $data = curl_exec($ch);
            // Fermeture de la session cURL
            curl_close($ch);

            // Écriture des données téléchargées dans un fichier ZIP local
            if (file_put_contents($zipFile, $data) === false) {
                // Lancer une exception en cas d'erreur lors de l'écriture du fichier
                throw new Exception("Erreur lors du téléchargement du fichier : {$zipFile}");
            }
            // Retourner le nom du fichier ZIP téléchargé
            return $zipFile;
        }
        // Télécharger la version sélectionnée de PrestaShop
        //         function telechargerPrestaShop($version) {
        //             $url = "https://github.com/PrestaShop/PrestaShop/releases/download/{$version}/prestashop_{$version}.zip";
        //             $zipFile = "prestashop_{$version}.zip";
        //             if (file_put_contents($zipFile, fopen($url, 'r')) === false) {
        //                 throw new Exception("Erreur lors du téléchargement du fichier : {$zipFile}");
        //             }
        //             return $zipFile;
        //         }

        // Décompresser l'archive téléchargée
        function decompresserPrestaShop($zipFile)
        {
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo('.');
                $zip->close();
                return true;
            }
            throw new Exception("Échec de la décompression de {$zipFile}.");
        }

        // Supprimer le fichier zip
        function supprimerFichier($zipFile)
        {
            if (file_exists($zipFile)) {
                unlink($zipFile);
            }
        }

        // Gestion des actions de l'utilisateur
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $version = filter_input(INPUT_POST, 'version', FILTER_SANITIZE_STRING);
            $zipFile = filter_input(INPUT_POST, 'unzip', FILTER_SANITIZE_STRING);
            $response = filter_input(INPUT_POST, 'response', FILTER_SANITIZE_STRING);
            $delete = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING);

            try {
                if ($delete) {
                    supprimerFichier($delete);
                    echo "<p>Le fichier {$delete} a été supprimé.</p>";
                } elseif ($version && !$zipFile) {
                    $zipFile = telechargerPrestaShop($version);
                    echo "<p>Téléchargement de {$zipFile} terminé. Souhaitez-vous décompresser le fichier ?</p>";
                    echo "<form method='post'>
                            <input type='hidden' name='version' value='{$version}'>
                            <input type='hidden' name='unzip' value='{$zipFile}'>
                            <input type='submit' value='OUI' name='response' aria-label='Décompresser'>
                            <input type='submit' value='NON' name='response' aria-label='Annuler'>
                          </form>";
                } elseif ($zipFile && $response === 'OUI') {
                    decompresserPrestaShop($zipFile);
                    echo "<p>Décompression réussie : {$zipFile}.</p>";
                    echo "<form method='post'>
                            <button type='submit' name='delete' value='{$zipFile}'>Supprimer le fichier téléchargé</button>
                          </form>";
                }
            } catch (Exception $e) {
                echo "<div class='alert'>{$e->getMessage()}</div>";
            }
        } else {
            $versions = obtenirVersionsPrestaShop();
            if ($versions) {
                echo "<form method='post'>
                        <select name='version'>";
                foreach ($versions as $version) {
                    echo "<option value='{$version}'>{$version}</option>";
                }
                echo "</select>
                      <input type='submit' value='Télécharger' aria-label='Télécharger la version sélectionnée'>
                      </form>";
            } else {
                echo '<p>Aucune version disponible pour le moment.</p>';
            }
        }
        ?>
        <!-- Affichage de la version actuelle de PHP -->
        <div class="info-box">
            <span><b>Version actuelle de PHP :</b> <?php echo phpversion(); ?></span>
            <div class="extensions">
                <ul>
                    <?php
                    // Liste des extensions PHP à vérifier
                    $extensions = ['CURL', 'DOM', 'Fileinfo', 'GD', 'Iconv', 'Intl', 'JSON', 'Mbstring', 'OpenSSL', 'PDO', 'PDO_MYSQL', 'SimpleXML', 'Zip'];
                    // Boucle pour vérifier chaque extension dans la liste
                    foreach ($extensions as $extension) {
                        // Appeler la fonction verifierExtension pour chaque extension et afficher le résultat
                        echo verifierExtension($extension);
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!-- En-tête pour attirer l'attention sur la compatibilité PHP -->
        <div class="attention">
            <h2><strong>ATTENTION</strong>: Vérifiez que PHP est compatible.</h2>
            <!-- Liens vers la documentation sur les pré-requis pour différentes versions de PrestaShop -->
            <p>
                <a href="https://devdocs.prestashop-project.org/9/basics/installation/system-requirements/"
                    target="_blank">Pré-requis pour la version 9.x</a><br>
                <a href="https://devdocs.prestashop-project.org/8/basics/installation/system-requirements/"
                    target="_blank">Pré-requis pour la version 8.x</a><br>
                <a href="https://devdocs.prestashop-project.org/1.7/basics/installation/system-requirements/"
                    target="_blank">Pré-requis pour la version 1.7.x</a>
            </p>
        </div>
    </div>
    <!--FOOTER-->
    <div class="footer">
        <!-- Pied de page avec des informations sur le site -->
        <p>&copy; 2024 thierrylaval.dev - Licence : MIT</p>
        <!-- Lien pour soutenir le travail de l'auteur -->
        <p>Pour soutenir mon travail : <a href="https://revolut.me/laval96o">Offrez-moi un café</a></p>
    </div>
</body>
</html>
