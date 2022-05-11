<?php
require_once 'inc/init.inc.php';

//-------------------------------- TRAITEMENTS PHP ------------------------------//
if(isset($_GET['action']) && $_GET['action'] == "deconnexion")
{
    if(isset($_COOKIE[session_name()]))
    {
        // setcookie permet de créer un cookie sauf si la durée de vie est négative
        //     nom_du_cookie  valeur  durée_de_vie  path
        setcookie(session_name(), '', time()-42000, '/');
    }
    // détruit le fichier de session (dossier tmp)
    session_destroy();
}
if(internauteEstConnecte())
{
    header("location: profil.php");
}
if($_POST)
{
    // debug($_POST);
    // on commence par vérifier si le pseudo existe en bdd
    $resultat = executeRequete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
    // debug($resultat);
    // ici on regarde dans la propriété num_rows si la valeur n'est pas égale à 0, le pseudo a bien été trouvé
    if($resultat->num_rows != 0)
    {
        // on applique un fetch sur la réponse de la bdd pour rendre les résultat exploitable sous forme de tableau associatif
        $membre = $resultat->fetch_assoc();
        // debug($membre);
        // ici on compare le mdp crypté avec le mot de passe taper dans le formulaire
        if(password_verify($_POST['mdp'], $membre['mdp']))
        {
            // on boucle sur les informations du membre et on enregistre les infos dans la session (sauf le mdp)
            foreach($membre AS $indice => $valeur)
            {
                if($indice != 'mdp')
                {
                    $_SESSION['membre'][$indice] = $valeur;
                }
            }
            header("location:profil.php");
        }
        else
        {
            // en cas de mot passe incorrect = erreur
           $contenu .=  "<div class='alert alert-danger text-center'>🛑 Erreur d'identifiant : Le mot de passe est incorrect.</div>";
        }
    }
    else
    { 
        // en cas de pseudo incorrect = erreur
        $contenu .= "<div class='alert alert-danger text-center'>🛑 Erreur d'identifiant : Ce pseudo n'existe pas.</div>";
    }
}

//-------------------------------- AFFICHAGE HTML ------------------------------//

require_once 'inc/haut.inc.php';
echo $contenu;
?>

<div class="jumbotron text-center mt-4">
    <h2>Connexion</h2>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" class="form-control" name="pseudo" id="pseudo"
                        placeholder="💬 Indiquer votre Pseudo">
                </div>
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="mdp" id="mdp"
                        placeholder="🔑 Indiquer votre Mot de passe">
                </div>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg">Se connecter ✅</button>
                </div>
            </form>
        </div>
    </div>
</div>





<?php

require_once 'inc/bas.inc.php';