//suppression des entreprises
// if (isset($_POST['submitDeleteEntreprise'])){ 
//     $idEntreprise = $_POST['idEntreprise']; 
//     $db->suppEntreprises($idEntreprise); 
// }  
// Gestion des visibilites fournisseurs
// Etape 1: voir l'état actuel de la visibilite





// Etape 2: pouvoir modifier l'état de la visibilité

// Etape 3: Affichage des visibilites (valeur 0 ou 1)
// if (isset($_POST['fournisseurSubmit'])) {
//     $idCollabs = $_POST['idCollabs'];
     // FAIRE REQUETE SQL POUR RECUP L ID DU FOURNISSEURS
    // $db->idFournisseur ();
// COMPARAISON DE LA VISBILITE DE LA BDD
    // $db->readVu ();
// FAIRE UPDATE DE SI 1=0 ET 0=1 
// if ($fournisseur['visibilite_collab'] == 1) {$newVisibility = 0;}
// elseif ($fournisseur['visibilite_collab'] == 0) {$newVisibility = 1;}
//     // $db->vu($idCollabs, $newVisibility);
// } 



<fieldset class="entreprises">
        <legend>Toutes les entreprises </legend>
        <form action="" method="POST">
            <?php foreach ($entreprises as $entreprise){ ?>
                <!-- <input type="checkbox" name="film_checkbox" value="0" checked><br>  -->
                
                <input type="checkbox" id=" ' . $IdEntreprise . '" name="entrepriseSelec[][]" value="' . $ .IdEntreprise '" >'
                <input type="hidden" name="idEntreprise" value="<?= $entreprise['id_collab']; ?>">
                <input type="submit" name="submitDeleteEntreprise" value="SUPPRIMER"><br>
                <?= $entreprise['id_collab']; ?><br> 
                    <?= $entreprise['nom_collab']; ?><br> 
                    <img src= "<?= $entreprise['logo_collab']; ?>" >
            <?php } ?>    
        </form>
    </fieldset><br><br 