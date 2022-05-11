<?php
require_once 'inc/init.inc.php';
//-------------------------------- TRAITEMENTS PHP -------------------------------//
if(isset($_GET['id_produit']))
{
    $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]'");
}
if($resultat->num_rows <= 0)
{
    header("location: index.php");
}

$produit = $resultat->fetch_assoc();

$contenu .= "<div class='container text-center mt-4'>";
$contenu .= "<h2>$produit[titre]</h2><hr><br>";
$contenu .= "<div class='container'><div class='row'><div class='col-md-6 text-center'><img src='$produit[photo]' class='img-fiche-produit'>";
$contenu .= "<p class='mt-4'><strong>Description</strong> : $produit[description]</p>";
$contenu .= "<p><strong>Catégorie</strong> : $produit[categorie]</p>";
$contenu .= "<p><strong>Couleur</strong> : $produit[couleur]</p>";
$contenu .= "<p><strong>Taille</strong> : $produit[taille]</p></div>";

if($produit['stock'] > 0)
{
    $contenu .= "<div class='col-md-6 text-center' style='margin: auto 0'><p><strong>Prix</strong> : $produit[prix] €</p>";
    $contenu .= "<div class='alert alert-info'>💬 Nombre de produit(s) disponible : $produit[stock]</div><br><br>";
    $contenu .= '<form method="post" action="panier.php">';
    $contenu .= "<input type='hidden' name='id_produit' value='$produit[id_produit]'>";
    $contenu .= '<label for="quantite" class="form-label">Quantité : </label>';
    $contenu .= '<select class="form-select text-center" id="quantite" name="quantite">';
    for($i = 1; $i <= $produit['stock'] && $i <= 5; $i++)
    {
        $contenu .= "<option>$i</option>";
    }
    $contenu .= '</select>';
    $contenu .= '<br><br><button type="submit" name="ajout_panier" class="btn btn-warning">Ajouter au panier</button></div></div></div></div>';
    $contenu .= '</form><hr>';
}
else
{
    $contenu .= '<div class="alert alert-danger">🛑 Produit en rupture de stock !</div>';
}
$contenu .= "<br><div class='container text-center'><button class='btn btn-primary'><a href='index.php?categorie=" . $produit['categorie'] . "'>Retour vers la sélection de " . $produit['categorie'] . "</a></button></div><br>";
$contenu .= "</div>";
//-------------------------------- AFFICHAGE HTML --------------------------------//
require_once 'inc/haut.inc.php';
echo $contenu;
require_once 'inc/bas.inc.php';