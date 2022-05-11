<?php
//------------------------- BDD
$mysqli = new mysqli("localhost", "root", "", "site");
if($mysqli->connect_error)
{
    // Affiche un message et termine le script en cours
    die('🛑 Un problème est survenu lors de la tentative de connexion à la Base De Données : ' . $mysqli->connect_error );
}
// $mysqli->set_charset("utf8");
//------------------------- SESSION
// Démarrage de la session
session_start();
//------------------------- CHEMIN
// Création d'une constante
define("RACINE_SITE", "/site/");
//------------------------- VARIABLES
// On initialise la variable contenu vide pour éviter les erreurs
$contenu = '';
//------------------------- AUTRES INCLUSIONS
// Ici on inclu le fichier des fonctions
require_once("fonction.inc.php");



// debug($mysqli);