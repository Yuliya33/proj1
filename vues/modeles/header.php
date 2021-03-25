<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Saira+Stencil+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Salsa&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rhodium+Libre&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<header>

    <nav class="menu_secondaire">
        <ul>
            <li><a href="">?</a></li>
            <?php if(isset($_SESSION["usager_nom"])){?>
                <?php if($_SESSION["usager_categorie"]=="visiteur"){?>
                    <li class="li_menu_compte"><a href=""><?=$_SESSION["usager_nom"]?>(<?=$_SESSION["usager_categorie"]?>)</a>
                        <ul class="menu_compte">
                            <li><a href="">Mon compte</a></li>
                            <li><a href="">Mes livres empruntés</a></li>
                            <li class="separateur"><a href="index.php?commande=Deconnecter">Fermer la session</a></li>
                        </ul>                        
                    </li>
                <?php }else if($_SESSION["usager_categorie"]=="gestionnaire"){?>
                    <li class="li_menu_compte"><a href=""><?=$_SESSION["usager_nom"]?>(<?=$_SESSION["usager_categorie"]?>)</a>
                        <ul class="menu_compte">
                            <li><a href="">Mon compte</a></li>
                            <li class="separateur"><a href="index.php?commande=FormulaireAjouterLivre">Ajouter un livre</a></li>
                            <li><a href="index.php?commande=FormulaireAjouterAuteur">Ajouter un auteur</a></li>
                            <li><a href="index.php?commande=FormulaireAjouterEditeur">Ajouter un editeur</a></li>
                            <li class="separateur"><a href="index.php?commande=AfficherAuteurs">Afficher tous les auteurs</a></li>
                            <li><a href="index.php?commande=AfficherEditeurs">Afficher tous les editeurs</a></li>
                            <li class="separateur"><a href="index.php?commande=Deconnecter">Fermer la session</a></li>
                        </ul>                        
                    </li>
                <?php }else{?>
                    <li class="li_menu_compte"><a href=""><?=$_SESSION["usager_nom"]?>(<?=$_SESSION["usager_categorie"]?>)</a>
                        <ul class="menu_compte">
                            <li><a href="">Mon compte</a></li>
                            <li class="separateur"><a href="index.php?commande=FormulaireAjouterLivre">Ajouter un livre</a></li>
                            <li><a href="index.php?commande=FormulaireAjouterAuteur">Ajouter un auteur</a></li>
                            <li><a href="index.php?commande=FormulaireAjouterEditeur">Ajouter un editeur</a></li>
                            <li class="separateur"><a href="index.php?commande=AfficherAuteurs">Afficher tous les auteurs</a></li>
                            <li><a href="index.php?commande=AfficherEditeurs">Afficher tous les editeurs</a></li>
                            <li class="separateur"><a href="index.php?commande=Deconnecter">Fermer la session</a></li>
                        </ul>                        
                    </li>                   
                <?php }?>

            <?php }else{?>

            <li><a href="index.php?commande=FormulaireConnexion">Se connecter</a></li>    

            <?php }?>    
            <li><a href="">EN</a></li>
        </ul>
    </nav>

    <div class="LogoRecherche">
        <div>
            <picture class="logo">
                <a href="index.php"><img src="/proj1/images/logo.png" alt="logo"></a>
            </picture>
            <form class="champDeRecherche">
                <select name="Categories" id="Categories">
                    <option value="Tout">Tout</option>
                </select>
                <input type="text" placeholder="Recherche.." name="recherche">
                <button type="submit"><img src="images/loupe.png" alt="loupe"></button>
            </form>
        </div>
    </div>  

     <nav class="menu_principale">
            <ul>
                <li><a href="">SERVICES</a></li>          
                <li><a href="">COLLECTIONS</a></li>
                <li><a href="">VISITES</a></li>
                <li><a href="">ACTIVITÉS</a></li>
            </ul>
        </nav>  

</header>
