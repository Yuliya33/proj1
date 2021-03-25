<?php

    if(mysqli_num_rows($donnees["livre"])>0){

        $livre = mysqli_fetch_assoc($donnees["livre"]);

    }


?>

<?php require_once("modeles/header.php");?>

    <main>

        <form action="index.php?commande=EmprunterLivre&id=<?=$livre["id"]?>" method="post" class="formulaire_emprunter">
            <picture>
                <img src="images/livres/<?=$livre["id"]?>.jpg" alt="">
            </picture>
            <div>
                <h2><?=$livre["titre"]?></h2>

                <h4>Écrit par:
                <?php 

                $auteurs = obtenir_auteurs_par_livre_id($livre["id"]); 
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

                <p><?=$livre["description"]?></p>
                <h3>ISBN: <?=$livre["ISBN"]?></h3>
                <h3>Éditeur: <?=$livre["nom"]?></h3>

                <p>*Vous pouvez emprunter ce livre pour une durée de cinq jours maximums!</p>

                <button type="submit"> Emprunter</button>

                <?php if(isset($donnees["messagePositif"])){?>
                    <h4 class="messagePositif">*<?=$donnees["messagePositif"]?></h4>
                <?php }?>

                <?php if(isset($donnees["messageErreur"])){?>
                    <h4 class="messageErreur">*<?=$donnees["messageErreur"]?></h4>
                <?php }?>                

            </div>



        </form>

    </main>

    <?php require_once("modeles/footer.php");?>