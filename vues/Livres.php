<?php

    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }else{
        $page = 1;
    }

?>

<?php require_once("modeles/header.php");?>

<main>

    <?php if(isset($donnees["confirmation"])){?>
        <div class="confirmation">
            <h3><?=$donnees["confirmation"]?></h3>
        </div>

    <?php }?>

    <div class="boite_pagination">
        <?=afficher_numero_page(mysqli_num_rows($donnees["livres_tout"]),$page)?>
    </div>

    <div class="presentationLivres">
    
    
        <?php

        $livres = $donnees["livres"];
        //afficher dynamiquement les articles dans la base de données
        if(mysqli_num_rows($livres)>0){
            while($rangee = mysqli_fetch_assoc($livres)){ 

        ?>

        <article>

            <picture>
                <img src="images/livres/<?=$rangee["id"]?>.jpg" alt="">
            </picture>
            <h2><?=$rangee["titre"]?></h2>

            <h4>Écrit par:
            <?php 

            $auteurs = obtenir_auteurs_par_livre_id($rangee["id"]); 
            $nb_auteur = mysqli_num_rows($auteurs);
            $indice = 1;
            
            while($rangee_auteur = mysqli_fetch_assoc($auteurs)){            
            ?>

             <?php 
             
             echo trim($rangee_auteur["nom"]);            
             if($indice != $nb_auteur){
                
                echo ",";
                
             }

             $indice++;
             
             ?>

            <?php }?>
            </h4>

            <?php if(isset($_SESSION["usager_nom"])){?>
                <?php if($_SESSION["usager_categorie"] == "administrateur"){?>
                    <div class="boutons">
                        <a href="index.php?commande=FormulaireModifierLivre&id=<?=$rangee["id"]?>" class="bouton_modifier">Modifier</a>
                        <a href="index.php?commande=FormulaireSupprimerLivre&id=<?=$rangee["id"]?>">Supprimer</a>
                    </div>

                <?php }else if($_SESSION["usager_categorie"] == "gestionnaire"){?>
                    <div class="boutons">
                        <a href="index.php?commande=FormulaireModifierLivre&id=<?=$rangee["id"]?>" class="bouton_modifier">Modifier</a>
                    </div>

                <?php }else{?>
                    <div class="boutons">
                        <a href="index.php?commande=FormulaireEmprunter&id=<?=$rangee["id"]?>">Emprunter</a>
                    </div>

                <?php }?>                
            

                <?php }else{?>
                    <div class="boutons">
                        <a href="index.php?commande=FormulaireEmprunter&id=<?=$rangee["id"]?>">Emprunter</a>
                    </div>
  

            <?php }?>
        </article>

        <?php } 

        }else{   
        
        ?>
        
        <h2>Aucun livre dans la base de données</h2>

        <?php
        } ?>

    </div>
    <div class="boite_pagination">
        <?=afficher_numero_page(mysqli_num_rows($donnees["livres_tout"]),$page)?>
    </div>
    

</main>

<?php require_once("modeles/footer.php");?>