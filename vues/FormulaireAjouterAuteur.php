<?php

    if(isset($_POST["nom"])) $nom = $_POST["nom"]; else $nom = "";

    if(isset($_POST["prenom"])) $prenom = $_POST["prenom"]; else $prenom = "";

    if(isset($_POST["date"])) $date = $_POST["date"]; else $date = "";


?>

<?php require_once("modeles/header.php");?>

<main>

<form action="index.php?commande=AjouterAuteur" method="post" class="formulaire">

    <div>
        <label for="nom">Nom</label>
        <input type="text" name="nom" value="<?=$nom?>">        
    </div>

    <div>
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" value="<?=$prenom?>">
    </div>

    <div>
        <label for="date">Date de naissance</label>
        <input type="date" name="date" value="<?=$date?>">
    </div>

    <div>
        <input type="submit" name="bouton_ajouter" value="Ajouter">
    </div>

    <?php if(isset($donnees["messageErreur"])){?>
    <div>
        <h4><?=$donnees["messageErreur"]?></h4>
    
    </div>
    <?php }?>



</form>

</main>

<?php require_once("modeles/footer.php");?>