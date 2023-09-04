<?php
session_start(); 
include 'class/Manager.php';

$db = new DbConnect;
$Categories= $db->readCategorie();
$comptes = $db->readCompte();  


//Répertoire image 
$dossierImageUpload = "";

// ***************************************BLOC DE CONNEXION*************************************************
    // Pour valider l'ajout d'un compte
    if(isset($_POST['creationCompte'])){
        $email = $_POST['email'];
        $password = $_POST['password']; 
        $nom = $_POST['nom']; 
        $prenom = $_POST['prenom'];
        $db->creationCompte($email, $password, $nom, $prenom);
    }
    // *********************************************  
        // pour se connecter
        if(isset($_POST['login'])){
            $username = $_POST['username'];
            $password = $_POST['password'];  

            $user= $db->login($username);

            if ($user && $password === $user['mdp_admin']) {
                $db->setAdmin($user);
            } 
        //var_dump($user);
        // var_dump($_SESSION);
        }
    // *********************************************  
        //  DECONNEXION  
        if(isset($_POST['deconnexion'])) {
            $Deconnexion=$db->deconnexion();
        }
    // *********************************************        
        // MODIFICATION COMPTE
        if (isset($_POST['modifCompte'])){
            $idAdmin=$_SESSION['id_admin'];
            $prenom=$_POST['prenom_admin'];
            $nom=$_POST['nom_admin'];
            $email=$_POST['email_admin'];
            $motDePasse=$_POST['mdp_admin'];
            $confirmationMotDePasse=$_POST['confirmationMotDePasse'];
            
            if($motDePasse==$confirmationMotDePasse){
                $user= $db->modifCompte($idAdmin, $email, $motDePasse, $nom, $prenom);
                $_SESSION['id_admin']=$userId;
                $_SESSION['email_admin'] = $email;
                $_SESSION['nom_admin']=$nom;
                $_SESSION['prenom_admin']=$prenom;
                header('Location: ./index.php');
            }else{
                echo 'mot de passe et confirmation mot de passe différent ! Veuillez re-saisir les mots de passe';
            }
        }   
        // *********************************************        
        // SUPPRESSION DE COMPTE
        if (isset($_POST['supprimerCompte'])){ 
            $adminId = $_POST['adminId']; 
            var_dump($_POST);
            $db->suppCompte($adminId); 
        }      
        
// ******************************************************************************************
        //  Ajout d'un chantier
        if(isset($_POST['envoyerNouveauChantier'])){ 
            if(isset($_POST['categorieChantierCreate'])){
                if($_POST['categorieChantierCreate']=="1"){
                    $dossierImageUpload = "./images/imgRenovationMurs/";
                } elseif ($_POST['categorieChantierCreate']=="2"){
                    $dossierImageUpload = "./images/imgPeintureInterieur/";
                }elseif ($_POST['categorieChantierCreate']=="3"){
                    $dossierImageUpload = "./images/imgPapierPeint/"; 
                }elseif ($_POST['categorieChantierCreate']=="4"){
                    $dossierImageUpload = "./images/imgSols/";

                }
            }
            // var_dump($_POST);
            // var_dump($_FILES);
            $nomChantier=$_POST['nomChantierCreate'];
            $categorieId=$_POST['categorieChantierCreate'];
            $descriptionChantier=$_POST['descriptionChantierCreate'];
            //Ajout des images dans le répertoire
            $db->ajoutImage($dossierImageUpload, $_FILES, "fileToUploadAvant");
            $db->ajoutImage($dossierImageUpload, $_FILES, "fileToUploadApres");
            $imageAvChantier = $dossierImageUpload . $_FILES["fileToUploadAvant"]["name"];
            $imageApChantier = $dossierImageUpload . $_FILES["fileToUploadApres"]["name"];
    
            $db->ajouterChantier($nomChantier, $imageAvChantier, $imageApChantier, $descriptionChantier, $categorieId);
        }

// ******************************************************************************************//
?>

<!DOCTYPE html>
<html>
<head>
    <title>jessica</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 </head>
<body style="background-color:darkgray;">
    <!-- formulaire CONNEXION -->
    <div class="login">
        <div class="panneauLogin" id="connexion" >
            <h2>Connectez-vous</h2>
            <form action="" method="POST">
                <label for="username">E-mail:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" name="login" value="Se connecter">
                <a href>Mot de passe oublié</a>
            </form>
        </div>  
    </div><br>
    <form action="" method="post">
    <!-- formulaire DECONNEXION -->
    <button type="submit" name="deconnexion" class="bouton-deconnexion">clique ici pour partir DECONNEXION</button>
    </form><br><br>
    <!-- MODIFICATION DU COMPTE -->
    <fieldset class="modifCompte">
        <legend class="titremodfiCompt">Modification de votre Compte</legend class="titreFormulaire">
        <a href="" class="retour-button" style="display: inline;"> Créer un compte</a><br><br><br>
        <a href="" class="retour-button"> Retour </a>

        <form action="" method="post">

            <label for="nom">Nom :</label>
            <input type="text" id="nom_admin" value="<?php echo $_SESSION['nom_admin'] ?>" name="nom_admin" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom_admin" value="<?php echo $_SESSION['prenom_admin'] ?>" name="prenom_admin" required>

            <label for="email">Email :</label>
            <input type="email" id="email_admin" value="<?php echo $_SESSION['email_admin'] ?>" name="email_admin" required>

            <label for="motDePasse">Nouveau Mot de Passe :</label>
            <input type="password" id="mdp_admin" name="mdp_admin">

            <label for="confirmationMotDePasse">Confirmer Nouveau Mot de Passe :</label>
            <input type="password" id="confirmationMotDePasse" name="confirmationMotDePasse">

            <input type="submit" name="modifCompte" value="Modifier">
        </form>
    </fieldset>
    <!-- CREATION DU COMPTE -->
    <br>
    <fieldset class="creationCompte">
        <legend>Creation compte </legend>
            <form action="" method="post">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>

                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="motDePasse">Mot de Passe :</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmationMotDePasse">Confirmer Mot de Passe :</label>
                <input type="password" id="confirmationMotDePasse" name="confirmationMotDePasse" required>

                <input type="submit" name="creationCompte" value="Créer le Compte">
            </form>
    </fieldset><br><br>
    <!--SUPPRESSION DE COMPTE  -->
    <fieldset class="gestion des comptes">
        <legend>Gestion des comptes </legend>
        <form action="" method="post">
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comptes as $compte){ ?>
                <tr>
                    <td><?= $compte['id_admin']; ?></td>
                    <td><?= $compte['nom_admin']; ?></td>
                    <td><?= $compte['prenom_admin']; ?></td>
                    <td><?= $compte['email_admin']; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="adminId" value="<?= $compte['id_admin']; ?>">
                            <input type="submit" name="supprimerCompte" value="Supprimer">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </fieldset><br><br>

    <!-- formulaire ajout chantier -->
    <fieldset class="formulaire">
        <legend class="titreFormulaire">Ajout d'un chantier</legend>
        <form action="" method="post" enctype="multipart/form-data"> 
            <label for="chantierCreate">Veuillez noter le nom du chantier:</label>
            <input type="text" name="nomChantierCreate">  <br><br>

            <label for="afficheChantier">Veuillez selectionner la catégorie du chantier :</label>
                <select name="categorieChantierCreate" id="categorieChantierCreate">
                    <option value="">Sélectionner la catégorie</option>
                    <?php
                    foreach ($Categories as $Categorie) {
                        if((isset($_GET['travaux'])) && ($Categorie['id_travaux']==$_GET['travaux'])){
                            echo '<option value="' . $Categorie['id_travaux'] . '" selected="selected">'. $Categorie['nom_travaux']  . '</option>';
                        } else {
                        echo '<option value="' . $Categorie['id_travaux'] . '">'. $Categorie['type_travaux']  . '</option>';
                        }
                    }
                    ?>
                </select><br><br>

                <label for="chantierAV">Sélectionner l'image AVANT du chantier : </label><br>
                <input type="file" name="fileToUploadAvant" id="fileToUploadAvant">

                <label for="chantierAP">Sélectionner l'image APRES du chantier : </label><br>
                <input type="file" name="fileToUploadApres" id="fileToUploadApres">

            <label for="descriptChantier">Veuillez noter la description du chantier:</label>
            <input type="text" name="descriptionChantierCreate">  <br><br>

            <input type="submit" name="envoyerNouveauChantier" value="Ajouter le chantier">
        </form>
    </fieldset>              
</body>
</html>


