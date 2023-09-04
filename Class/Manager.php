<?php
    include 'class/Database.php';
    include 'class/Images.php';

    // RELATION A LA BDD 
    class DbConnect extends Database {
    private $dbConnect;    

    public function __construct()
    {
        $this->dbConnect = parent::dbConnect(); 
    }
// ************************GESTION DES COMPTES ************************//
    // CREATION COMPTE
    public function creationCompte($email, $password, $nom, $prenom) {
        $insertUserQuery = "INSERT INTO `admin` (email_admin, mdp_admin, nom_admin, prenom_admin, `role`) 
                            VALUES (:email_admin, :mdp_admin, :nom_admin, :prenom_admin, :role)";
        $insertUserStmt = $this->dbConnect->prepare($insertUserQuery);
        $insertUserStmt->bindValue(':email_admin', $email);
        $insertUserStmt->bindValue(':mdp_admin', $password);
        $insertUserStmt->bindValue(':nom_admin', $nom);
        $insertUserStmt->bindValue(':prenom_admin', $prenom);
        $insertUserStmt->bindValue(':role', "0");
        $insertUserStmt->execute();
        return 'Le compte a été créé avec succès!';
    }


    //AFFICHAGE DE TOUS LES COMPTES
    public function readCompte() {
        $checkCompteQuery = "SELECT * FROM `admin` WHERE `role`=0 ORDER BY nom_admin ";
        $checkCompteStmt = $this->dbConnect->prepare($checkCompteQuery);
        $checkCompteStmt ->execute();
        return $checkCompteStmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    // CONNEXION
    public function login($username){
        $checkUserQuery = "SELECT * FROM `admin` WHERE email_admin = :email_admin";
        $checkUserStmt = $this->dbConnect->prepare($checkUserQuery);
        $checkUserStmt->bindValue(':email_admin', $username);
        $checkUserStmt->execute();
        return $checkUserStmt->fetch(PDO::FETCH_ASSOC);
    }   

    public function setAdmin($user) {
        $_SESSION['id_admin'] = $user['id_admin'];
        $_SESSION['prenom_admin'] = $user['prenom_admin'];
        $_SESSION['nom_admin'] = $user['nom_admin'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email_admin'] = $user['email_admin'];
    }

    // DECONNEXION    
    public function deconnexion() {
        session_destroy();
        echo "<h1>Merci de votre visite !</h1>";
        echo "<p>Nous vous remercions d'avoir visité notre site. Nous espérons vous revoir bientôt !</p>";
        header("refresh:1;url=index.php");
        exit();
    }

    // MODIFICATION COMPTE
    public function modifCompte
    ($idAdmin, $newEmail, $newPassword, $newNom , $newPrenom) {
        $updateUserQuery = "UPDATE `admin`
                            SET email_admin = :new_email,  
                                mdp_admin = :new_password,
                                nom_admin= :new_nom,
                                prenom_admin= :new_prenom
                            WHERE id_admin = :admin_id";
        $updateUserStmt = $this->dbConnect->prepare($updateUserQuery);
        $updateUserStmt->bindValue(':new_email', $newEmail);
        $updateUserStmt->bindValue(':new_password', $newPassword);
        $updateUserStmt->bindValue(':new_nom', $newNom);
        $updateUserStmt->bindValue(':new_prenom', $newPrenom);
        $updateUserStmt->bindValue(':admin_id', $idAdmin);
        $updateUserStmt->execute();
        return 'les modifications ont bien été prises en compte!';
    }

    // SUPPRESSION DE COMPTE
    public function suppCompte($adminId){
        $sql = "DELETE FROM `admin` WHERE id_admin<>1 AND id_admin=:id_admin";
        $stmt = $this->dbConnect->prepare($sql);
        $stmt->bindValue(":id_admin", $adminId, PDO::PARAM_INT);
        $stmt->execute();
        header("refresh:30");
        return 'Le compte a bien été supprimé!';
       
    }

    // ************************ AJOUT D'UN CHANTIER ************************//
// INSERT INTO `chantier`(`nom_chantier`, `photo_av_chantier`, `photo_ap_chantier`, `description_chantier`, `position_chantier`, `id_travaux`)
    public function ajouterChantier($nomChantier, $imageAvChantier, $imageApChantier, $descriptionChantier, $travauxId) {
        $sqlChantier = "INSERT INTO chantier(nom_chantier, photo_av_chantier, photo_ap_chantier, description_chantier, position_chantier, id_travaux) 
                    VALUES (:nomChantier, :imageAvChantier, :imageApChantier, :descriptionChantier, :position_chantier, :travauxId )";
        $stmtChantier = $this->dbConnect->prepare($sqlChantier);
        $stmtChantier->bindValue(':nomChantier', $nomChantier);
        $stmtChantier->bindValue(':imageAvChantier', $imageAvChantier);
        $stmtChantier->bindValue(':imageApChantier', $imageApChantier);        
        $stmtChantier->bindValue(':travauxId', $travauxId);
        $stmtChantier->bindValue(':descriptionChantier', $descriptionChantier);
        $stmtChantier->bindValue(':position_chantier', "0");
        //$stmtChantier->bindValue(':imageAvChantier', $imageAvChantier);
        //$stmtChantier->bindValue(':imageApChantier', $imageApChantier);
        $stmtChantier->execute();
        return 'L\'ajout du chantier a bien été réalisé';
    }

    // LISTE CATEGORIE
    public function readCategorie() {
        $checkCategoriesQuery = "SELECT * FROM travaux ORDER BY type_travaux";
        $checkCategorieStmt = $this->dbConnect->prepare($checkCategoriesQuery);
        $checkCategorieStmt ->execute();
        return $checkCategorieStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //AJOUT D'IMAGE
    public function ajoutImage($dossierImage, $fichier, $nomFichierUpload){
        
        var_dump($fichier);
        $target_file = $dossierImage . basename($fichier[$nomFichierUpload]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Vérification que ce soit bien image
        if(isset($_POST["submit"])) {
		$check = getimagesize($fichier[$nomFichierUpload]["tmp_name"]);
        if($check !== false) {
            echo "Le fichier n'est pas une image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "Le fichier n'est pas une image, veuillez ressayer avec une image.";
            $uploadOk = 0;
        }
		}
        // Vérification que le fichier n'est pas déjà existant
        if (file_exists($target_file)) {
        echo "Désolé,le fichier existe déjà.";
        $uploadOk = 0;
        }
        // Verification de la taille du fichier
        if ($fichier[$nomFichierUpload]["size"] > 500000) {
        echo "Désolé,le fichier est trop important.";
        $uploadOk = 0;
        }
        // Verification du format de l'image 
        if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "les formats acceptés sont : JPG, JPEG & PNG.";
        $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        echo "Désolé, le fichier n'a pas été téléchargé";
        // if everything is ok, try to upload file
        } else {
        if (move_uploaded_file($fichier[$nomFichierUpload]["tmp_name"], $target_file)) {
            echo "Le fichier ". htmlspecialchars( basename( $fichier[$nomFichierUpload]["name"])). " a été téléchargé avec succès.";
            //header("refresh:2");
        } else {
            echo "Erreur lors du téléchargement, veuillez réessayer";
        }
        }
    }
    }