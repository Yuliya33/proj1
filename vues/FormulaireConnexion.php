<?php

if(isset($_REQUEST["courriel"])){

    $courriel = $_REQUEST["courriel"];

}else{

    $courriel = "";

}


?>

<?php require_once("modeles/header.php");?>

<main>

    <form action="index.php?commande=SeConnecter" method="post" class="formulaire">

        <div>
            <label for="courriel">Courriel</label>
            <input type="email" name="courriel" value="<?=$courriel?>">        
        </div>

        <div>
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" name="mot_de_passe">        
        </div>

        <div>
            <input type="submit" value="Se connecter">
        </div>

        <?php if(isset($donnees["messageErreur"])){?>
        <div>
            <h4 class="erreur">*<?=$donnees["messageErreur"]?></h4>            
        </div>
        <?php }?>

    </form>

</main>

<?php require_once("modeles/footer.php");?>