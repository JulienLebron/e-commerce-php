<?php
require_once 'inc/init.inc.php';
//-------------------------------- TRAITEMENTS PHP ------------------------------//
$categories_des_produits = executeRequete("SELECT DISTINCT categorie FROM produit");
$contenu .= '<div class="container mt-4">';
$contenu .= '<div class="row">';
$contenu .= '<div class="col-md-2 text-center boutique-gauche">';
$contenu .= '<ul>';
while($cat = $categories_des_produits->fetch_assoc())
{
    $contenu .= "<li><a href='?categorie=" . $cat['categorie'] . "'>" . $cat['categorie'] . "</a></li>"; 
}
$contenu .= '</ul>';
$contenu .= '</div>';

$contenu .= '<div class="col-md-10 boutique-droite">';
if(isset($_GET['categorie']))
{
    $donnees = executeRequete("SELECT id_produit, reference, titre, photo, prix FROM produit WHERE categorie = '$_GET[categorie]'");
    while($produit = $donnees->fetch_assoc())
    {
        $contenu .= '<div class="card text-center mb-4 boutique-produit" style="width: 18rem;">';
        $contenu .= "<img src=\"$produit[photo]\" class=\"card-img-top\" alt=\"photo du produit\" max-height=\"300\">";
        $contenu .= '<div class="card-body">';
        $contenu .= "<h5 class='card-title'>$produit[titre]</h5>";
        $contenu .= "<p class='card-text'>$produit[prix] €</p>";
        $contenu .= '<button class="btn btn-info"><a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche du produit</a></button>';
        $contenu .= '</div></div>';
    }
}
$contenu .= '</div></div></div>';
//-------------------------------- AFFICHAGE HTML ------------------------------//
require_once 'inc/haut.inc.php';
echo $contenu;
require_once 'inc/bas.inc.php';