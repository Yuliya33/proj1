<?php

    $editeurs = $donnees["editeurs"];
    $auteurs = $donnees["auteurs"];

    if(isset($_POST["titre"])){

        $titre = $_POST["titre"];

    }else{

        $titre = "";

    }

    if(isset($_POST["description"])){

        $description = $_POST["description"];
        
    }else{

        $description = "";

    }

    if(isset($_POST["ISBN"])) $ISBN = $_POST["ISBN"]; else $ISBN = "";

    if(isset($_POST["editeur"])) $id_editeur = $_POST["editeur"]; else $id_editeur = "";

    if(isset($_POST["auteur"])) $auteurs_selectionne = $_POST["auteur"]; else $auteurs_selectionne = null;




?>

<?php require_once("modeles/header.php");?>

<main>
    
    <form action="index.php?commande=AjouterLivre#erreur" method="post" class="formulaire" enctype="multipart/form-data">
        <div>
            <label for="titre">Titre</label>    
            <input type="text" name="titre" value="<?=$titre?>">
        </div>

        <div>
            <label for="description">Description</label>
            <textarea name="description" cols="30" rows="10"><?=$description?></textarea>
        </div>

        <div>
            <label for="ISBN">ISBN</label>
            <input type="text" name="ISBN" value="<?=$ISBN?>">
        </div>

        <div>
            <label for="editeur">Éditeur</label>
            <select name="editeur">
                <option value="">Sélectionnez...</option>
                <?php while($rangee = mysqli_fetch_assoc($editeurs)){?>

                <?php if($id_editeur == $rangee["id"]){?>
                <option value="<?=$rangee["id"]?>" selected><?=$rangee["nom"]?></option>
                <?php }else{?>
                <option value="<?=$rangee["id"]?>"><?=$rangee["nom"]?></option>
                <?php }?>

                <?php }?>

            </select>

        </div>

        <div>
            <label for="auteur">Auteur</label>
            <label> *Il faut appuyer sur CTRL pour sélectionner deux ou plus éléments</label>
            <select name="auteur[]" multiple size="7">
                <option value="">Sélectionnez...</option>
                <?php while($rangee = mysqli_fetch_assoc($auteurs)){?>

                <?php if(in_array($rangee["id"],$auteurs_selectionne)){?>
                <option value="<?=$rangee["id"]?>" selected><?=$rangee["nom"]. " " . $rangee["prenom"]?></option>
                <?php }else{?>
                <option value="<?=$rangee["id"]?>"><?=$rangee["nom"]. " " . $rangee["prenom"]?></option>
                <?php }?>

                <?php }?>

            </select>

        </div>        

        <div>
            <label for="image">Sélectionnez l'image</label>
            <input type="file" name="image">        
        </div>
        
        <div>
            <input type="submit" name="bouton_ajouter" value="Ajouter" id="test">

        </div>

        <?php if(isset($donnees["messageErreur"])){?>
        <div id="erreur">
            <h4>*<?=$donnees["messageErreur"]?></h4>                   
        
        </div>

        <?php }?>
    
    </form>

</main>

<?php require_once("modeles/footer.php");?>