<?php

/**
 * Script d'installation de PrestaShop (start2020)
 * 
 * Ce script facilite le téléchargement et l'installation de PrestaShop en :
 * 1. Vérifiant la compatibilité du serveur
 * 2. Téléchargeant la version choisie depuis GitHub
 * 3. Décompressant les fichiers
 * 4. Préparant l'installation
 * 
 * @author Thierry Laval
 * @version 2.0.4
 * @link https://github.com/thierry-laval
 */

// Messages constants pour la vérification des extensions
define('MSG_EXTENSION_ACTIVE', "est activée");
define('MSG_EXTENSION_INACTIVE', "n\'est pas activée");

/**
 * Vérifie si une extension PHP est chargée et retourne un message formaté
 * 
 * @param string $extension Nom de l'extension à vérifier (ex: 'curl', 'zip')
 * @return string Message HTML formaté avec une classe CSS selon le statut
 * @example verifierExtension('curl') retourne '<li class="extension-success">curl est activée</li>'
 */
function verifierExtension(string $extension): string
{
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
    <style>
        /* 
        * Variables CSS pour les thèmes
        * Ces variables définissent les couleurs et styles de base pour l'application.
        * Elles sont utilisées dans tout le CSS pour maintenir une cohérence visuelle
        * et faciliter les changements de thème (clair/sombre).
        */
        :root {
            /* Couleurs principales */
            --background-color: #41A1E8;
            /* Bleu de fond principal */
            --container-background: white;
            /* Fond des conteneurs */
            --button-background: #28a745;
            /* Vert pour les boutons */
            --button-hover: #218838;
            /* Vert foncé au survol */
            /* Couleurs utilitaires */
            --border-color: #ccc;
            /* Gris pour les bordures */
            --alert-color: red;
            /* Rouge pour les erreurs */
            --info-background: #f8f9fa;
            /* Gris clair pour les infos */
            /* Couleurs des messages */
            --attention-background: #fff3cd;
            /* Jaune pâle pour alertes */
            --attention-border: #ffeeba;
            /* Bordure des alertes */
            /* Typographie */
            --font-size-base: 14px;
            /* Taille de texte de base */
            /* Couleur primaire */
            --primary-color: #1E90FF;
            /* Bleu primaire */
            --primary-color-dark: #0077cc;
            /* Bleu primaire foncé */
        }

        /* 
        * Thème clair
        * Définit les couleurs pour le thème clair.
        */
        :root[data-theme="light"] {
            --text-color: #333;
            /* Couleur du texte */
            --bg-color: #41A1E8;
            /* Couleur de fond */
            --card-bg: #ffffff;
            /* Fond des cartes */
            --hover-bg: #f5f9ff;
            /* Couleur de survol */
            --container-bg: #ffffff;
            /* Fond des conteneurs */
            --info-bg: #ffffff;
            /* Fond des infos */
            --border-color: #1E90FF;
            /* Couleur des bordures */
            --compatibility-text: #333;
            /* Texte de compatibilité */
            --primary-color: #1E90FF;
            /* Bleu primaire */
        }

        /* 
        * Thème sombre
        * Définit les couleurs pour le thème sombre.
        */
        :root[data-theme="dark"] {
            --text-color: #f8f9fa;
            /* Couleur du texte */
            --bg-color: #1a1a1a;
            /* Couleur de fond */
            --card-bg: #2d2d2d;
            /* Fond des cartes */
            --hover-bg: #3d3d3d;
            /* Couleur de survol */
            --container-bg: #2d2d2d;
            /* Fond des conteneurs */
            --info-bg: #1f1f1f;
            /* Fond des infos */
            --border-color: #404040;
            /* Couleur des bordures */
            --compatibility-text: #e1e1e1;
            /* Texte de compatibilité */
            --primary-color: #4dabf7;
            /* Bleu primaire */
        }

        /* 
        * Styles globaux
        * Applique des styles de base à l'ensemble de la page.
        */
        body {
            background-color: var(--bg-color);
            /* Couleur de fond */
            color: var(--text-color);
            /* Couleur du texte */
            padding: 30px;
            /* Espacement intérieur */
            font-size: var(--font-size-base);
            /* Taille de police */
            margin: 0;
            /* Supprime la marge par défaut */
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Transition fluide */
        }

        /* 
        * Conteneur principal
        * Utilisé pour centrer et structurer le contenu.
        */
        .container {
            background: var(--container-bg);
            /* Fond du conteneur */
            padding: 15px;
            /* Espacement intérieur */
            border-radius: 8px;
            /* Coins arrondis */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Ombre légère */
            width: 90%;
            /* Largeur du conteneur */
            max-width: 400px;
            /* Largeur maximale */
            margin: 0 auto;
            /* Centrage horizontal */
            border: 1px solid var(--border-color);
            /* Bordure */
        }

        /* 
        * Styles pour les éléments de formulaire (select, boutons, etc.)
        */
        select,
        input[type="submit"],
        button {
            flex: 1;
            /* Équilibrage de l'espace */
            margin: 0 5px;
            /* Espacement horizontal */
            padding: 12px;
            /* Espacement intérieur */
            border-radius: 6px;
            /* Coins arrondis */
            border: 1px solid var(--border-color);
            /* Bordure */
            transition: background-color 0.3s;
            /* Transition fluide */
        }

        /* 
        * Styles spécifiques pour les boutons de soumission
        */
        input[type="submit"] {
            background-color: var(--button-background);
            /* Fond du bouton */
            color: white;
            /* Couleur du texte */
            border: none;
            /* Supprime la bordure */
            cursor: pointer;
            /* Curseur en forme de main */
        }

        /* 
        * Effet de survol pour les boutons de soumission
        */
        input[type="submit"]:hover {
            background-color: var(--button-hover);
            /* Changement de couleur */
        }

        /* 
        * Boîtes d'information
        * Utilisées pour afficher des informations système.
        */
        .info-box {
            background-color: var(--info-bg);
            /* Fond de la boîte */
            border: 1px solid var(--border-color);
            /* Bordure */
            border-radius: 6px;
            /* Coins arrondis */
            padding: 10px;
            /* Espacement intérieur */
            margin-top: 10px;
            /* Espacement supérieur */
            font-size: 0.85rem;
            /* Taille de police */
        }

        /* 
        * Conteneur des extensions PHP
        * Affiche la liste des extensions requises.
        */
        .extensions {
            text-align: left;
            /* Alignement du texte à gauche */
            font-size: 12px;
            /* Taille de police */
        }

        /* 
        * Grille responsive pour la liste des extensions
        */
        .extensions ul,
        .extensions-list {
            display: grid;
            /* Utilisation d'une grille */
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            /* Colonnes adaptatives */
            gap: 6px;
            /* Espacement entre les éléments */
            padding: 10px;
            /* Espacement intérieur */
            margin: 0;
            /* Supprime la marge */
        }

        /* 
        * Informations de version
        * Utilisé pour afficher la version PHP et autres détails techniques.
        */
        .version-info {
            margin-bottom: 8px;
            /* Espacement inférieur */
            font-size: 0.85rem;
            /* Taille de police */
        }

        /* 
        * Numéro de version en gras et coloré
        */
        .version-number {
            font-weight: bold;
            /* Texte en gras */
            color: var(--primary-color);
            /* Couleur du texte */
            margin-left: 4px;
            /* Espacement à gauche */
        }

        /* 
        * Accessibilité : masquer un élément visuellement
        */
        .sr-only,
        [role="status"] {
            position: absolute;
            /* Position absolue */
            width: 1px;
            /* Largeur minimale */
            height: 1px;
            /* Hauteur minimale */
            padding: 0;
            /* Supprime l'espacement */
            margin: -1px;
            /* Déplace hors de l'écran */
            overflow: hidden;
            /* Cache le contenu */
            clip: rect(0, 0, 0, 0);
            /* Masque l'élément */
            white-space: nowrap;
            /* Empêche le retour à la ligne */
            border: 0;
            /* Supprime la bordure */
        }

        /* 
        * Bouton de thème
        * Permet de basculer entre les thèmes clair et sombre.
        */
        .theme-toggle {
            position: absolute;
            /* Position absolue */
            top: 20px;
            /* Distance du haut */
            right: 20px;
            /* Distance de la droite */
            z-index: 1000;
            /* Superposition élevée */
            display: flex;
            /* Affichage en flex */
            align-items: center;
            /* Centrage vertical */
            gap: 8px;
            /* Espacement entre les éléments */
        }

        /* 
        * Texte du bouton de thème
        */
        .theme-text {
            font-size: 0.9rem;
            /* Taille de police */
            color: var(--text-color);
            /* Couleur du texte */
            margin-right: 8px;
            /* Espacement à droite */
        }

        /* 
        * Bouton de thème (icône)
        */
        .theme-button {
            background: var(--card-bg);
            /* Fond du bouton */
            border: 1px solid var(--primary-color);
            /*Bordure */
            padding: 0.25rem;
            /* Espacement intérieur */
            font-size: 1rem;
            /* Taille de police */
            cursor: pointer;
            /* Curseur en forme de main */
            border-radius: 50%;
            /* Forme ronde */
            width: 32px;
            /* Largeur */
            height: 32px;
            /* Hauteur */
            display: flex;
            /* Affichage en flex */
            align-items: center;
            /* Centrage vertical */
            justify-content: center;
            /* Centrage horizontal */
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
            /* Ombre légère */
            transition: all 0.3s ease;
            /* Transition fluide */
            color: var(--text-color);
            /*Couleur du texte */
        }

        /* 
        * Effet de survol pour le bouton de thème
        */
        .theme-button:hover {
            transform: scale(1.1);
            /* Agrandissement */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Ombre plus prononcée */
        }

        /* 
        * Effet de focus pour le bouton de thème
        */
        .theme-button:focus {
            outline: 2px solid var(--primary-color);
            /* Contour visible */
            outline-offset: 2px;
            /* Espacement du contour */
        }

        /* 
        * Animation d'apparition des éléments
        */
        @keyframes fadeIn {
            from {
                opacity: 0;
                /* Transparent */
                transform: translateY(10px);
                /* Déplacement vers le bas */
            }

            to {
                opacity: 1;
                /* Visible */
                transform: translateY(0);
                /* Pas de déplacement */
            }
        }

        /* 
        * Styles pour les éléments de la liste des extensions
        */
        .extensions-list li {
            display: flex;
            /* Affichage en flex */
            align-items: center;
            /* Centrage vertical */
            padding: 4px 6px;
            /* Espacement intérieur */
            border-radius: 4px;
            /* Coins arrondis */
            background-color: var(--card-bg);
            /* Fond */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            /* Ombre légère */
            transition: all 0.2s ease;
            /* Transition fluide */
            animation: fadeIn 0.3s ease-out;
            /* Animation d'apparition */
            animation-fill-mode: both;
            /* Applique les styles avant et après l'animation */
            position: relative;
            /* Position relative */
            overflow: hidden;
            /* Cache le débordement */
            font-size: 0.8rem;
            /* Taille de police */
        }

        /* 
        * Effet de survol pour les éléments de la liste des extensions
        */
        .extensions-list li:hover {
            transform: translateY(-3px);
            /* Déplacement vers le haut */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            /* Ombre plus prononcée */
            background-color: var(--hover-bg);
            /* Changement de couleur */
        }

        /* 
        * Style pour les extensions valides
        */
        .extension-success {
            color: var(--success-color, #28a745);
            /* Couleur du texte */
        }

        /* 
        * Style pour les extensions non valides
        */
        .extension-error {
            color: var(--error-color, #dc3545);
            /* Couleur du texte */
        }

        /* 
        * Icônes dans la liste des extensions
        */
        .icon {
            display: inline-flex;
            /* Affichage en ligne */
            align-items: center;
            /*Centrage vertical */
            justify-content: center;
            /* Centrage horizontal */
            width: 10px;
            /* Largeur */
            height: 10px;
            /* Hauteur */
            margin-right: 6px;
            /* Espacement à droite */
            border-radius: 50%;
            /* Forme ronde */
            font-weight: bold;
            /* Texte en gras */
            transition: all 0.3s ease;
            /*Transition fluide */
            position: relative;
            /* Position relative */
            font-size: 0.6rem;
            /* Taille de police */
        }

        /* 
        * Animation des icônes
        */
        .icon::before {
            content: '';
            /* Contenu vide */
            position: absolute;
            /* Position absolue */
            inset: -2px;
            /* Débordement */
            border-radius: 50%;
            /* Forme ronde */
            background: conic-gradient(from 0deg, currentColor, transparent);
            /* Dégradé conique */
            animation: rotate 2s linear infinite;
            /* Animation de rotation */
            opacity: 0;
            /* Transparent */
            transition: opacity 0.3s ease;
            /* Transition fluide */
        }

        /* 
        * Affichage de l'animation des icônes au survol
        */
        .extensions-list li:hover .icon::before {
            opacity: 0.2;
            /* Opacité légère */
        }

        /* 
        * Animation de rotation pour les icônes
        */
        @keyframes rotate {
            from {
                transform: rotate(0deg);
                /* Rotation initiale */
            }

            to {
                transform: rotate(360deg);
                /* Rotation complète */
            }
        }

        /* 
        * Style pour les icônes des extensions valides
        */
        .extension-success .icon {
            background-color: var(--success-color, #28a745);
            /* Fond */
            color: white;
            /* Couleur du texte */
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
            /* Ombre légère */
        }

        /* 
        * Style pour les icônes des extensions non valides
        */
        .extension-error .icon {
            background-color: var(--error-color, #dc3545);
            /* Fond */
            color: white;
            /* Couleur du texte */
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
            /* Ombre légère */
        }

        /* 
        * Accessibilité : mise en évidence des éléments au focus
        */
        .extensions-list li:focus-within {
            outline: 2px solid var(--primary-color);
            /* Contour visible */
            outline-offset: 2px;
            /* Espacement du contour */
        }

        /* 
        * Footer
        * Section en bas de la page.
        */
        .footer {
            text-align: center;
            /* Centrage du texte */
            margin-top: 10px;
            /* Espacement supérieur */
            font-size: 0.9em;
            /* Taille de police */
            padding: 5px 0;
            /* Espacement intérieur */
            border-top: 1px solid var(--border-color);
            /* Bordure en haut */
        }

        /* 
        * Liens dans le footer
        */
        .copyright a {
            color: var(--primary-color);
            /* Couleur du texte */
            text-decoration: none;
            /* Supprime le soulignement */
            transition: color 0.2s ease;
            /* Transition fluide */
        }

        /* 
        * Effet de survol pour les liens dans le footer
        */
        .copyright a:hover {
            color: var(--primary-color-dark);
            /* Changement de couleur */
        }

        /* 
        * Section de soutien
        */
        .support {
            margin: 15px 0;
            /* Espacement vertical */
            font-weight: bold;
            /* Texte en gras */
        }

        /* 
        * Bouton de don
        */
        .donate-button {
            display: inline-block;
            /* Affichage en ligne */
            padding: 8px 16px;
            /* Espacement intérieur */
            margin-top: 5px;
            /* Espacement supérieur */
            background-color: var(--button-background);
            /* Fond */
            color: white;
            /* Couleur du texte */
            text-decoration: none;
            /* Supprime le soulignement */
            border-radius: 20px;
            /* Coins arrondis */
            transition: all 0.2s ease;
            /* Transition fluide */
        }

        /* 
        * Effet de survol pour le bouton de don
        */
        .donate-button:hover {
            background-color: var(--button-hover);
            /* Changement de couleur */
            transform: scale(1.05);
            /* Agrandissement */
        }

        /* 
        * Section de validation
        */
        .validation {
            margin-top: 10px;
            /* Espacement supérieur */
        }

        /* 
        * Formulaire
        */
        form {
            display: flex;
            /* Affichage en flex */
            justify-content: space-between;
            /* Espacement entre les éléments */
            align-items: center;
            /* Centrage vertical */
            margin-bottom: 15px;
            /* Espacement inférieur */
        }

        /* 
        * Messages d'alerte
        */
        .alert {
            color: var(--alert-color);
            /*Couleur du texte */
            font-weight: bold;
            /* Texte en gras */
            margin-top: 10px;
            /* Espacement supérieur */
        }

        /* 
        * Titres de premier niveau
        */
        h1 {
            font-size: 20px;
            /*Taille de police */
            margin: 10px;
            /* Espacement */
        }

        /* 
        * Titres de second niveau
        */
        h2 {
            font-size: 15px;
            /* Taille de police */
            margin: 10px;
            /* Espacement */
        }

        /* 
        * Messages d'attention
        */
        .attention {
            padding: 10px;
            /* Espacement intérieur */
            background-color: var(--attention-background);
            /* Fond */
            border: 1px solid var(--attention-border);
            /* Bordure */
            border-radius: 5px;
            /* Coins arrondis */
            margin: 15px 0;
            /* Espacement vertical */
        }

        /* 
        * Liens stylisés
        */
        .link-button {
            display: inline-block;
            /* Affichage en ligne */
            padding: 2px 10px;
            /* Espacement intérieur */
            margin: 1px 0;
            /* Espacement vertical */
            color: #41A1E8;
            /* Couleur du texte */
            text-decoration: none;
            /* Supprime le soulignement */
            border: 1px solid transparent;
            /* Bordure transparente */
            border-radius: 4px;
            /* Coins arrondis */
            transition: background-color 0.3s;
            /* Transition fluide */
        }

        /* 
        * Effet de survol pour les liens stylisés
        */
        .link-button:hover {
            background-color: #e9ecef;
            /* Changement de couleur */
        }

        /* 
        * Bouton d'accordéon
        */
        .accordion-button {
            cursor: pointer;
            /* Curseur en forme de main */
            padding: 12px;
            /* Espacement intérieur */
            font-size: 16px;
            /* Taille de police */
            background-color: #007bff;
            /* Fond */
            color: white;
            /* Couleur du texte */
            border: none;
            /* Supprime la bordure */
            text-align: center;
            /* Centrage du texte */
            width: 100%;
            /* Largeur complète */
            outline: none;
            /* Supprime le contour */
            transition: background-color 0.3s;
            /* Transition fluide */
            border-radius: 5px;
            /* Coins arrondis */
        }

        /* 
        * Effet de survol pour le bouton d'accordéon
        */
        .accordion-button:hover {
            background-color: #0056b3;
            /* Changement de couleur */
        }

        /* 
        * Contenu de l'accordéon
        */
        .accordion-content {
            display: none;
            /* Masqué par défaut */
            padding: 15px;
            /* Espacement intérieur */
            border: 1px solid var(--border-color);
            /* Bordure */
            border-radius: 6px;
            /* Coins arrondis */
            background-color: var(--info-bg);
            /* Fond */
            margin-top: 10px;
            /* Espacement supérieur */
        }

        /* 
        * Texte de compatibilité
        */
        .compatibility-text {
            color: var(--compatibility-text);
            /* Couleur du texte */
            line-height: 1.5;
            /* Hauteur de ligne */
        }

        .compatibility-text p,
        .compatibility-text ul,
        .compatibility-text li {
            color: var(--compatibility-text);
            /* Couleur du texte */
            margin-bottom: 10px;
            /* Espacement inférieur */
        }

        /* 
        * Liens dans le texte de compatibilité
        */
        .compatibility-text a {
            color: var(--primary-color);
            /* Couleur du texte */
            text-decoration: none;
            /* Supprime le soulignement */
        }

        /* 
        * Effet de survol pour les liens dans le texte de compatibilité
        */
        .compatibility-text a:hover {
            text-decoration: underline;
            /* Soulignement */
        }

        /* 
        * Icônes de validation
        */
        .icon-ok {
            color: green;
            /* Couleur verte */
        }

        .icon-not-ok {
            color: red;
            /* Couleur rouge */
        }

        /* 
        * Centrage de texte
        */
        .centered-text {
            text-align: center;
            /* Centrage horizontal */
            display: flex;
            /* Affichage en flex */
            flex-direction: column;
            /* Alignement en colonne */
            align-items: center;
            /* Centrage horizontal */
            justify-content: center;
            /* Centrage vertical */
            height: 100%;
            /* Hauteur complète */
        }

        /* 
        * Effet d' agrandissement sur le logo
        */
        .zoom-effect {
            transition: transform 0.3s ease;
            /* Transition fluide */
        }

        .zoom-effect:hover {
            transform: scale(1.1);
            /* Agrandissement */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="centered-text">
            <a href="https://thierrylaval.dev" target="_blank">
                <img src="https://raw.githubusercontent.com/thierry-laval/archives/master/images/logo-portfolio.png" alt="Logo Développeur Web de Thierry Laval" width="300" height="100" style="max-width: 100%; height: auto; display: block; margin: 0 auto;" loading="lazy" class="zoom-effect">
            </a>
            <h1>Assistant PrestaShop</h1>
            <h2>Télécharger | Dézipper | Installer</h2>
            <p>Ce script facilite le téléchargement et l'installation de PrestaShop depuis GitHub.<strong> Veuillez choisir une version</strong></p>
        </div>
        <!-- PHP code pour télécharger et décompresser -->
        <?php
        // Obtenir les versions disponibles de PrestaShop avec cURL
        /**
         * Récupère la liste des versions disponibles de PrestaShop depuis GitHub
         * 
         * Utilise l'API GitHub pour obtenir les tags du dépôt PrestaShop.
         * Chaque tag représente une version publiée.
         * 
         * @return array Liste des noms de versions disponibles
         * @throws Exception Si la requête à l'API GitHub échoue
         */
        function getPrestaShopVersions()
        {
            // Configuration de la requête à l'API GitHub
            $url = 'https://api.github.com/repos/PrestaShop/PrestaShop/tags';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: request']);

            // Exécution de la requête
            $json = curl_exec($ch);
            curl_close($ch);

            // Vérification de la réponse
            if (!$json) {
                return [];
            }

            // Extraction des noms de versions
            $tags = json_decode($json, true);
            return array_map(fn($tag) => $tag['name'], $tags);
        }

        /**
         * Télécharge une version spécifique de PrestaShop depuis GitHub
         * 
         * Cette fonction effectue les opérations suivantes :
         * 1. Construit l'URL de téléchargement pour la version demandée
         * 2. Télécharge le fichier ZIP en streaming pour économiser la mémoire
         * 3. Vérifie les erreurs potentielles durant le téléchargement
         * 4. Valide l'intégrité du fichier téléchargé
         * 
         * @param string $version Numéro de version de PrestaShop (ex: '8.1.0')
         * @return string|false Chemin du fichier ZIP en cas de succès, false en cas d'échec
         */
        function downloadPrestaShop($version)
        {
            // Construction des chemins
            $url = "https://github.com/PrestaShop/PrestaShop/releases/download/{$version}/prestashop_{$version}.zip";
            $zipFile = "prestashop_{$version}.zip";

            // Initialisation du téléchargement
            $ch = curl_init($url);
            $fp = fopen($zipFile, 'w');

            // Vérification des permissions d'écriture
            if (!$fp) {
                echo "Erreur : Impossible de créer le fichier {$zipFile}. Vérifiez les permissions.";
                return false;
            }

            // Configuration de cURL pour le streaming
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);

            // Exécution du téléchargement
            $success = curl_exec($ch);

            // Gestion des erreurs de téléchargement
            if (!$success) {
                $error_msg = curl_error($ch);
                curl_close($ch);
                fclose($fp);
                unlink($zipFile); // Nettoyage du fichier incomplet
                echo "Erreur de téléchargement de PS : $error_msg";
                return false;
            }

            // Fermeture des ressources
            curl_close($ch);
            fclose($fp);

            // Vérification de l'intégrité du fichier
            if (!file_exists($zipFile) || filesize($zipFile) < 1024) {
                unlink($zipFile);
                echo "Erreur : Le fichier téléchargé est invalide ou corrompu.";
                return false;
            }

            return $zipFile;
        }

        /**
         * Décompresse l'archive PrestaShop et propose l'installation
         * 
         * Cette fonction :
         * 1. Ouvre et extrait l'archive ZIP
         * 2. Nettoie le fichier ZIP après extraction
         * 3. Affiche un formulaire pour lancer l'installation
         * 
         * @param string $zipFile Chemin vers le fichier ZIP à décompresser
         * @return void
         */
        function unzipPrestaShop($zipFile)
        {
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === TRUE) {
                // Extraction des fichiers
                $zip->extractTo('.');
                $zip->close();
                unlink($zipFile); // Nettoyage du ZIP

                echo "Décompression réussie !<br>";

                // Formulaire pour lancer l'installation
                echo "<form method='post' class='install-form'>";
                echo "<p>Vous êtes prêt ?</p>";
                echo "<input type='hidden' name='redirect' value='index.php'>";
                echo "<div class='button-group'>";
                echo "<input type='submit' value='Cliquez pour installer Prestashop' name='reponse' class='button-primary'>";
                echo "</div>";
                echo '</form>';
            } else {
                echo "Échec de la décompression.";
            }
        }

        /**
         * Vérifie si une extension PHP est disponible sur le serveur
         * 
         * Cette fonction vérifie la présence d'une extension PHP et retourne
         * un message formaté en HTML indiquant son statut.
         * 
         * @deprecated Utiliser verifierExtension() à la place pour un meilleur formatage
         * @param string $extension Nom de l'extension à vérifier
         * @return string Message HTML formaté avec le statut de l'extension
         */
        function checkExtension($extension)
        {
            return extension_loaded($extension)
                ? "<b><font color=green>extension $extension : OK</font><br></b>"
                : "<b><font color=red>extension $extension : NOK</font><br></b>";
        }
        // Fonction pour arrêter le script proprement
        function endScript()
        {
            echo 'Fin du script. <a href="index.php" class="btn btn-primary">Revenir à la page principale</a>';
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
                    echo " - Changez de version ! <a href='index.php' class='btn btn-primary'>Retour à la page d'accueil</a>";
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
            <div class="accordion-content compatibility-text" id="accordion-content" style="display: none;">
                <p>Mon script simplifie le déploiement de PrestaShop, mais il est essentiel de vérifier la compatibilité de votre version PHP. Une version incompatible peut entraîner des bugs à l'installation ou à l'utilisation de la boutique.</p>
                <ul>
                    <li><strong>PrestaShop 8.x</strong> : PHP 7.2.5 à 8.1</li>
                    <li><strong>PrestaShop 1.7.x</strong> : PHP 5.6 à 7.2 (selon la sous-version)</li>
                </ul>
                <p>Consultez la <a href="https://devdocs.prestashop.com/" target="_blank">documentation officielle</a> pour des informations à jour.</p>
                <p>Avant d'installer PrestaShop, vérifiez la compatibilité de votre hébergement et consultez les prérequis :</p>
                <ul>
                    <li><a href="https://devdocs.prestashop-project.org/8/basics/installation/system-requirements/" target="_blank" title="Pré-requis pour PrestaShop 8.x" class="link-button">Pré-requis pour PrestaShop 8.x</a></li>
                    <li><a href="https://devdocs.prestashop-project.org/1.7/basics/installation/system-requirements/" target="_blank" title="Pré-requis pour PrestaShop 1.7.x" class="link-button">Pré-requis pour PrestaShop 1.7.x</a></li>
                </ul>
                <p>Prenez le temps de lire les instructions et de vérifier la version de PHP avant de télécharger. Les versions 7.2 et inférieures peuvent présenter des failles de sécurité. Si nécessaire, mettre à jour votre hébergement avec une version PHP adaptée.</p>
                <p>Évitez les versions <em>alpha</em>, <em>beta</em> et <em>rc</em> de PrestaShop, conçues pour les tests et instables en production. Optez toujours pour une version stable, sauf besoin spécifique d'une version en développement.</p>
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

                // Vérifie la préférence enregistrée ou utilise la préférence système
                const savedTheme = localStorage.getItem('theme') || (prefersDark.matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', savedTheme);

                // Met à jour l'icône
                updateThemeIcon(savedTheme);

                // Fonction pour mettre à jour l'icône du thème
                function updateThemeIcon(theme) {
                    const icon = themeSwitch.querySelector('.theme-icon');
                    icon.textContent = '🌓';
                }

                // Gestion du clic sur le bouton
                themeSwitch.addEventListener('click', () => {
                    const currentTheme = document.documentElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcon(newTheme);

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

                // Écoute les changements de préférence système
                prefersDark.addEventListener('change', (e) => {
                    const newTheme = e.matches ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcon(newTheme);
                });
            });
        </script>
        <div class="container">
            <div class="theme-toggle">
                <span class="theme-text">Sombre/Clair</span>
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
                <p class="copyright">🇫🇷 &copy; 2025 <a href="https://thierrylaval.dev" target="_blank">thierrylaval.dev</a> - Licence : MIT 🇫🇷</p>
                <p class="support">Pour soutenir mon travail :<br><a href="https://revolut.me/laval96o" target="_blank" title="Un petit don, ça vous dit ? Ça m'aidera à partager mon travail gratuitement ! 😊" class="donate-button">👉🏻 Offrez-moi un café ☕️</a>
                </p>
                <div class="validation">
                    <img src="https://www.w3.org/assets/logos/w3c/w3c-developers-dark.svg" alt="W3C Developers" width="100" height="35">
                </div>
            </div>
        </div>
    </div>
</body>

</html>