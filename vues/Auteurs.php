<?php

    $auteurs = $donnees["auteurs"];

?>

<?php require_once("modeles/header.php");?>

<main>

    <?php if(isset($donnees["confirmation"])){?>
        <div class="confirmation">
            <h3><?=$donnees["confirmation"]?></h3>
        </div>

    <?php }?>

    <div class="tableau auteurs">
        <div class="ligne">
            <div class="colonne titre">ID</div>
            <div class="colonne titre">Nom</div>
            <div class="colonne titre">Prenom</div>
            <div class="colonne titre">Date de naissance</div>
            <div class="colonne titre">Actions</div>
      
        </div>
        <?php while($rangee = mysqli_fetch_assoc($auteurs)){?>
        <div class="ligne">
            <div class="colonne"><?=$rangee["id"]?></div>
            <div class="colonne"><?=$rangee["nom"]?></div>
            <div class="colonne"><?=$rangee["prenom"]?></div>
            <div class="colonne"><?=$rangee["date_de_naissance"]?></div>
            <div class="colonne boutons">
                <?php if($_SESSION["usager_categorie"]== "administrateur"){?>
                <a href="index.php?commande=FormulaireModifierAuteur&id=<?=$rangee["id"]?>" class="bouton bouton_modifier">Modifier</a>
                <a href="index.php?commande=FormulaireSupprimerAuteur&id=<?=$rangee["id"]?>" class="bouton bouton_supprimer">Supprimer</a>   
                <?php }else{?>
                <a href="index.php?commande=FormulaireModifierAuteur&id=<?=$rangee["id"]?>" class="bouton bouton_modifier">Modifier</a>    
                <?php }?>
            </div>        
        </div>
        <?php }?>
    </div>

    
    

</main>

<?php require_once("modeles/footer.php");?>