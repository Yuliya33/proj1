<?php require_once("modeles/header.php")?>

<main>

    <form method="post" action="index.php?commande=SupprimerLivre&id=<?=$_GET["id"]?>" class="formulaire">
        <div>
            <label>Êtes-vous sûr que vous voulez supprimer ce livre?</label>        
        </div>

        <div>
            <input type="submit" name="bouton_oui" value="OUI">
            <input type="submit" name="bouton_non" value="NON">
        </div>    
    
    </form>

</main>


<?php require_once("modeles/footer.php")?>