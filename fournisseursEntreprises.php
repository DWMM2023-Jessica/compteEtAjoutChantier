<?php
session_start();
include 'class/Manager.php';
$db = new DbConnect;
$fournisseurs = $db->readFournisseurs();
$entreprises = $db->readEntreprises();
$db->readFournisseurs();

// suppression fournisseur
if (isset($_POST['submitDeleteFournisseur'])) {
    $idFournisseur = $_POST['idFournisseur'];
    var_dump($_POST);
    $db->suppFournisseurs($idFournisseur);
}
// suppression entreprise
if (isset($_POST['submitDeleteEntreprise'])) {
    $idEntreprise = $_POST['idEntreprise'];
    $db->suppEntreprises($idEntreprise);
}
// oeil ouvert/fermé fournisseur
if (isset($_POST['fournisseurSubmit'])) {
    $idCollabs = $_POST['idCollabs'];
    $db->vu($idCollabs);
}
// oeil ouvert/fermé entreprise
if (isset($_POST['entrepriseSubmit'])) {
    $idCollabs = $_POST['idCollabs'];
    $db->vu($idCollabs);
}
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css">
<!-- Visibilite et suppression fournisseurs-->
<h3 class=titrePartenaire>Gestion des partenaires</h3>
<p class=listeFournisseur>Liste de vos partenraires</p>
<section class="partenaire"> <?php foreach ($fournisseurs as $fournisseur) { ?>
        <form action="" method="post">
            <input type="hidden" name="idCollabs" value="<?php echo $fournisseur['id_collab']; ?>">
            <input type="hidden" name="idFournisseur" value="<?php echo $fournisseur['id_collab']; ?>">
            <div class="cardFournisseurEntreprise">
                <div class="card-contenue">
                        <?php if ($fournisseur['visibilite_collab'] == 0) { ?>
                            <button type="submit" name="fournisseurSubmit">
                                <img src="fermer-les-yeux.png" alt="Logo d'un oiel fermé" width="30px">
                            </button>
                        <?php } else { ?>
                            <button type="submit" name="fournisseurSubmit">
                                <img src="visibilite.png" alt="Logo d'un oeil ouvert" width="30px">
                            </button>
                        <?php } ?>
                        <!-- suppression du fournisseur -->
                        <input type="hidden" name="idFournisseur" value="<?= $fournisseur['id_collab']; ?>">
                        <button type="submit" name="submitDeleteFournisseur">
                            <img src="poubelle.png" alt="Poubelle" width="30px">
                        </button><br><br>
                        <?php echo $fournisseur['nom_collab']; ?><br>
                        <img class="cardLogo" src="<?php echo $fournisseur['logo_collab']; ?>" style="width: 400px;">
                    </div>
                </div>
        </form>
    <?php } ?>
</section><br><br><br><br>

<!-- ajout d'un fournisseur -->
<section class="sectionPartenaire">
    <fieldset class="FieldestPartenaire">
        <legend class="LegendPartenaire">Ajouter partenaire</legend>
        <?php
        echo '<form action = ""  method="POST" >';
        echo '<p>Nom du Partenaire</p><input type="text" name="nom_collab">';
        echo '<p>Logo du Partenaire</p><input type="url" name="logo_collab">';
        echo '<br>';
        echo '<br>';
        echo '<input class="ajouterPartenaire" type="submit" name="submitPartenaire" value="Ajouter">';
        echo '</form>';

        if (isset($_POST["submitPartenaire"])) {
            $nomCollab = $_POST['nom_collab'];
            $logoCollab = $_POST['logo_collab'];

            $db->insertPartenaire($nomCollab, $logoCollab);
        }
        ?>
    </fieldset>
</section>

<br><br><br><br><br><br><br><br><br><br><br><br>

<!-- Visibilite et suppression entreprises -->
<h3 class="titrePartenaire">Toutes les entreprises </h3>
<p class=listeFournisseur>Liste des entreprises avec qui vous collaborez</p>
<section class="partenaire">
    <?php foreach ($entreprises as $entreprise) { ?>
        <form action="" method="post">
            <input type="hidden" name="idCollabs" value="<?php echo $entreprise['id_collab']; ?>">
            <input type="hidden" name="idFournisseur" value="<?php echo $entreprise['id_collab']; ?>">
            <div class="cardFournisseurEntreprise">
                <div class="card-contenue">
                    <?php if ($entreprise['visibilite_collab'] == 0) { ?>
                        <button type="submit" name="entrepriseSubmit">
                            <img src="fermer-les-yeux.png" alt="Oeil fermé" width="30px">
                        </button>
                    <?php } else { ?>
                        <button type="submit" name="entrepriseSubmit">
                            <img src="visibilite.png" alt="Oeil ouvert" width="30px">
                        </button>
                    <?php } ?>
                    <!-- suppression du fournisseur -->

                    <input type="hidden" name="idEntreprise" value="<?= $entreprise['id_collab']; ?>">
                    <button type="submit" name="submitDeleteEntreprise">
                        <img src="poubelle.png" alt="Photo d'une poubelle" width="30px">
                    </button><br><br>
                    <?php echo $entreprise['nom_collab']; ?><br>
                    <img class="cardLogo" src="<?php echo $entreprise['logo_collab']; ?>" style="width: 400px;">
                </div>
            </div>
            </div>
        </form>
    <?php } ?>
</section><br><br>
<section class="sectionEntreprise">
    <fieldset class="FieldestEntreprise">
        <legend class="LegendEntreprise">Ajouter une entreprise</legend>
        <!-- ajout d'une entreprise -->
        <?php
        echo '<form class="form" method="POST">';
        echo '<p>Nom Entreprise</p><input type="text" name="entreprise">';
        echo '<p>Logo Entreprise</p><input type="url" name="logo_entreprise">';
        echo '<br>';
        echo '<br>';
        echo '<input class="ajouterEntreprise" type="submit" name="submitEntreprise" value="Ajouter entreprise">';
        echo '</form>';

        if (isset($_POST["submitEntreprise"])) {
            $nomCollab2 = $_POST['entreprise'];
            $logoCollab2 = $_POST['logo_entreprise'];
            $db->insertEntreprise($nomCollab2, $logoCollab2);
        }
        ?>
    </fieldset>
</section>

</html>