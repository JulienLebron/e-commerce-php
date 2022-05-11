<?php
require_once 'inc/init.inc.php';
//------------------------------------- TRAITEMENT PHP ---------------------------------------//
//------------------------------------- AJOUT PANIER -----------------------------------------//
if(isset($_POST['ajout_panier']))
{
    $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = '$_POST[id_produit]'");
    $produit = $resultat->fetch_assoc();
    ajouterProduitDansPanier($produit['titre'], $_POST['id_produit'], $_POST['quantite'], $produit['prix']);
}
//------------------------------------- SUPPRIMER ARTICLE ------------------------------------//
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    retirerProduitDuPanier($_GET['id_produit']);
    $contenu .= '<div class="alert alert-info text-center">💬 Le produit à bien été retirer du panier</div>';
}
//------------------------------------- VIDER PANIER -----------------------------------------//
if(isset($_GET['action']) && $_GET['action'] == 'vider')
{
    unset($_SESSION['panier']);
}
//------------------------------------- PAIEMENT PANIER --------------------------------------//
if(isset($_POST['payer']))
{
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $resultat = executeRequete("SELECT * FROM produit WHERE id_produit=" . $_SESSION['panier']['id_produit'][$i]);
        $produit = $resultat->fetch_assoc();
        if($produit['stock'] < $_SESSION['panier']['quantite'][$i])
        {
            $contenu .= '<hr><div class="alert alert-warning" role="alert">💬 Stock restant : ' . $produit['stock'] . '</div>';
            $contenu .= '<div class="alert alert-warning" role="alert">💬 Quantité demandée : ' . $_SESSION['panier']['quantite'][$i] . '</div>';
            if($produit['stock'] > 0)
            {
                $contenu .= '<div class="alert alert-info" role="alert">🛑 La quantité du produit n° : ' . $_SESSION['panier']['id_produit'][$i] . '<br><b>' . $produit['titre'] . '</b> à été réduite car notre stock était insuffisant, veuillez vérifier votre panier à nouveau svp.</div>';
                $_SESSION['panier']['quantite'][$i] = $produit['stock'];
            }
            else
            {
                $contenu .= '<div class="alert alert-info" role="alert">🛑 Le produit n° : ' . $_SESSION['panier']['id_produit'][$i] . '<br><b>' . $produit['titre'] . '</b> à été retiré de votre panier car nous sommes en rupture de stock, veuillez vérifier votre panier à nouveau svp.</div>';
                retirerProduitDuPanier($_SESSION['panier']['id_produit'][$i]);
                $i--;
            }
            $erreur = true;
        }
    }
    if(!isset($erreur))
    {
        executeRequete("INSERT INTO commande (id_membre, montant, date_enregistrement) VALUES (" . $_SESSION['membre']['id_membre'] . "," . montantTotal() . ", NOW())");
        $id_commande = $mysqli->insert_id;
        for($i = 0; $i <count($_SESSION['panier']['id_produit']); $i++)
        {
            executeRequete("INSERT INTO details_commande (id_commande, id_produit, quantite, prix) VALUES ($id_commande, " . $_SESSION['panier']['id_produit'][$i] . "," . $_SESSION['panier']['quantite'][$i] . "," . $_SESSION['panier']['prix'][$i] . ")");
        }
        unset($_SESSION['panier']);
        $contenu .= "<div class='alert alert-success text-center' role='alert'>✅ Merci pour votre commande ! <br>💬 Votre numéro de suivi est le n° $id_commande. Vous aller recevoir un mail récapitulatif de votre commande.</div>";
    }
}
//------------------------------------- AFFICHAGE HTML ---------------------------------------//
require_once 'inc/haut.inc.php';
echo $contenu;
echo "<table class='table table-bordered text-center mt-5'>";
echo "<tr><td colspan='6' style='background: lightgrey;'><b>Panier</b></td></tr>";
echo "<tr><th>Titre</th><th>Produit</th><th>Quantité</th><th>Prix Unitaire</th><th>Action</th></tr>";
if(empty($_SESSION['panier']['id_produit']))
{
    echo "<tr><td colspan='6'>Votre panier est vide</td></tr>";
}
else
{
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        echo '<tr>';
            echo "<td>" . $_SESSION['panier']['titre'][$i] . "</td>";
            echo "<td>" . $_SESSION['panier']['id_produit'][$i] . "</td>";
            echo "<td>" . $_SESSION['panier']['quantite'][$i] . "</td>";
            echo "<td>" . $_SESSION['panier']['prix'][$i] . " €</td>";
            echo '<td class="text-center" style="vertical-align: middle;"><button class="btn btn-dark"><a href="?action=suppression&id_produit=' . $_SESSION['panier']['id_produit'][$i] . '" Onclick="return(confirm(\'🛑 Vous êtes sur le point de supprimer ce produit. En êtes vous certain ?\'));"><i class="far fa-trash-alt"></i></a></button>';
        echo '<tr>';
    }
    echo "<tr><th colspan='4'>Total</th><td colspan='2' style='background: lightgrey;'>" . montantTotal() . " €</td></tr>";
    if(internauteEstConnecte())
    {
        echo '<form method="post" action="">';
        echo '<tr><td colspan="6"><input class="btn btn-primary btn-lg" type="submit" name="payer" value="Valider le paiement ✅"></td></tr>';
        echo '</form>';
    }
    else
    {
        echo '<tr><td colspan="6">Veuillez vous <a href="inscription.php" class="link-primary">inscrire</a> ou vous <a href="connexion.php" class="link-primary">connecter</a> afin de pouvoir finaliser votre commande</td></tr>';
    }
    echo "<tr><td colspan='6'><a class='btn btn-warning' href='?action=vider'>Vider le panier</a</td</tr>";
}
echo "</table><br>";
echo "<div class='alert alert-info text-center'>💬 Règlement par CHEQUE uniquement à l'adresse suivante : 300 rue de vaugirard 75012 PARIS</div><br>";







require_once 'inc/bas.inc.php';