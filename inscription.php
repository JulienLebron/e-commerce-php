<?php
require_once 'inc/init.inc.php';
//-------------------------------- TRAITEMENTS PHP ------------------------------//
if($_POST)
{
    // debug($_POST);
    // preg_match verifier les caractères utilisés dans le pseudo
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);
    // Si le pseudo contient des mauvais caractère ou si le pseudo ne respacte pas une certainer longeur = erreur 
    if(!$verif_caractere || iconv_strlen($_POST['pseudo']) < 3 || iconv_strlen($_POST['pseudo']) > 30)
    {
        $contenu .= "<div class='alert alert-danger text-center'>🛑 Une erreur s'est produite ! Le Pseudo doit contenir entre 3 et 30 caractères inclus.<br> Caractères acceptés : lettres de A à Z et chiffres de 0 à 9</div>";
    }
    else
    {
        // On fait une requete de sélection pour voir si le pseudo existe déja en bdd
        $membre = executeRequete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
        // si num_rows supérieur a 0 un pseudo à été trouvé en base dc erreur
        if($membre->num_rows > 0)
        {
            $contenu .= "<div class='alert alert-danger text-center'>🛑 Le Pseudo est déjà utilisé ! Veuillez choisir un autre Pseudo svp. </div>";
        }
        else
        {
            // on boucle sur le tableau $_POST et on applique un addslashes et un htmlentities sur les valeurs
            foreach($_POST AS $indice => $valeur)
            {
                $_POST[$indice] = htmlentities(addslashes($valeur));  
            }
            // ici on crypte le mot de passe
            // doc mdp = https://www.php.net/manual/fr/faq.passwords.php
            $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            // ici on exécute la requête d'insertion du membre en bdd
            executeRequete("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES ('$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]', '$_POST[civilite]', '$_POST[ville]', '$_POST[code_postal]', '$_POST[adresse]')");
            // ici on félicite l'utilisateur et on l'informe que l'inscription est un succès
            $contenu .= "<div class='alert alert-success text-center'>✅ Félicitation ! Vous êtes maintenant inscrit sur le site. Vous pouvez vous connecter en     <a href=\"connexion.php\" class=\"btn btn-warning\">Cliquant ici</a></div>";
        }
    }
}
//-------------------------------- AFFICHAGE HTML ------------------------------//
require_once 'inc/haut.inc.php';
?>

<div class="jumbotron text-center mt-4">
    <h2>Inscription</h2>
</div>
<?php
echo $contenu;
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" class="form-control" name="pseudo" id="pseudo"
                        placeholder="🐱‍👤 Veuillez choisir un pseudo" pattern="[a-zA-Z0-9-_.]{1,30}"
                        title="caractères autorisés : a-zA-Z0-9-_." required="required">
                </div>
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="mdp" id="mdp" required="required"
                        placeholder="🔑 Veuillez choisir un mot de passe">
                </div>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" name="nom" id="nom" placeholder="💬 Indiquer votre nom">
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" name="prenom" id="prenom"
                        placeholder="💬 Indiquer votre prénom">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email"
                        placeholder="💬 Exemple : exemple-site@gmail.com">
                </div>
                <div class="mb-3">
                    <label for="civilite" class="form-label">Civilité</label><br>
                    <input type="radio" name="civilite" value="m" checked=""> 🤵 Homme <br>
                    <input type="radio" name="civilite" value="f"> 👩‍💼 Femme <br>
                </div>
                <div class="mb-3">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" class="form-control" name="ville" id="ville"
                        placeholder="🏡 Indiquer votre ville">
                </div>
                <div class="mb-3">
                    <label for="code_postal" class="form-label">Code Postal</label>
                    <input type="text" class="form-control" name="code_postal" id="code_postal"
                        placeholder="🏡 Indiquer le code postal de votre ville" pattern="[0-9]{5}"
                        title="5 chiffres requi : de 0 à 9">
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea name="adresse" id="adresse" cols="30" rows="5" class="form-control"
                        placeholder="🏡 Indiquer votre adresse"></textarea>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">S'inscrire ✅</button>
                </div>
            </form>
        </div>
    </div>
</div>









<?php
require_once 'inc/bas.inc.php';