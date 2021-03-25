<?php

    $editeur = mysqli_fetch_assoc($donnees["editeur"]);

    if(isset($_POST["nom"])) $nom = $_POST["nom"]; else $nom = $editeur["nom"];

    if(isset($_POST["adresse"])) $adresse = $_POST["adresse"]; else $adresse = $editeur["adresse"];

?>

<?php require_once("modeles/header.php");?>

<main>

<form action="index.php?commande=ModifierEditeur&id=<?=$editeur["id"]?>" method="post" class="formulaire">

    <div>
        <label for="nom">Nom</label>
        <input type="text" name="nom" value="<?=$nom?>">        
    </div>

    <div>
        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" value="<?=$adresse?>">
    </div>

    <div>
        <input type="submit" name="bouton_modifier" value="Modifier">
    </div>

    <?php if(isset($donnees["messageErreur"])){?>
    <div>
        <h4><?=$donnees["messageErreur"]?></h4>
    
    </div>
    <?php }?>



</form>

</main>

<?php require_once("modeles/footer.php");?>