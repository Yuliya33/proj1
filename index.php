<?php

session_start(); 
   
/*
    index.php est le CONTRÔLEUR de notre application de type MVC. Toutes les requêtes vers notre application devront passer par ce fichier. Le coeur du contrôleur sera sa structure décisionnelle qui traite un paramètre que l'on va nommer commande. C'est la valeur de ce paramètre commande qui déterminera les actions posées par le contrôleur.
*/

    //recevoir le paramètre commande en GET ou en POST (et donc on utilisera $_REQUEST)
    if(isset($_REQUEST["commande"]))
    {
        $commande = $_REQUEST["commande"];
    }
    else
    {
        //assigner une commande par défaut - la commande qui mène à votre page d'accueil
        $commande = "Accueil";
    }

    //obtenir le modèle - connexion à la BD, accès aux fonctions, etc.
    require_once("modele.php");

    //structure décisionnelle du contrôleur
    switch($commande)
    {
        case "Accueil":
            //afficher la page d'accueil
            if(!isset($_GET["page"])){

                $donnees["livres_tout"] = obtenir_livres();
                $donnees["livres"] = obtenir_livres_par_page(1);

            }else{

                $donnees["livres_tout"] = obtenir_livres();
                $donnees["livres"] = obtenir_livres_par_page($_GET["page"]);

            }

            if(isset($_GET["recherche"])){

                $donnees["livres_tout"] = obtenir_livres($_GET["recherche"]);
                $donnees["livres"] = obtenir_livres($_GET["recherche"]);

            }

            require_once("vues/Livres.php");
            
            break;
        case "FormulaireConnexion":
             //afficher le formulaire de connexion
            require_once("vues/FormulaireConnexion.php");
            break;
        case "SeConnecter":
            //Validation d'un utilisateur
            if(trim($_REQUEST["courriel"]) != "" && trim($_REQUEST["mot_de_passe"]) != ""){
                // recherche de login dans la base de données
                $courriel_trouve = authentification($_REQUEST["courriel"]);

                if(mysqli_num_rows($courriel_trouve)>0){
                    // verification du mot de passe dans la base de données
                    $rangee = mysqli_fetch_assoc($courriel_trouve);
                    if(password_verify($_REQUEST["mot_de_passe"], $rangee["mot_de_passe"])){
                        // Création des variables de la session
                        $_SESSION["usager_id"] = $rangee["id"];
                        $_SESSION["usager_nom"] = $rangee["nom"];
                        $_SESSION["usager_categorie"] = $rangee["categorie"];
                        
                        header("Location: index.php");                                            
        
                    }else{
                        // Affichage le formulaire de connexion avec une erreur
                        $donnees["messageErreur"] = "Login ou le mot de passe est incorrect";
                        require_once("vues/FormulaireConnexion.php");

                    }
        
                }else{
                    // Affichage le formulaire de connexion avec une erreur
                    $donnees["messageErreur"] = "Login ou le mot de passe est incorrect";
                    require_once("vues/FormulaireConnexion.php");

                }                

            }else{
                // Affichage le formulaire de connexion avec une erreur
                $donnees["messageErreur"] = "Il faut entrer des valeurs dans les champs.";
                require_once("vues/FormulaireConnexion.php");

            }
            break;


        case "Deconnecter":
            // Suppression des variables de la session
            session_destroy();
            header("Location: index.php");
            break;

        case "FormulaireEmprunter":

            if(isset($_SESSION["usager_id"])){


                $donnees["livre"] = obtenir_livre_par_id($_GET["id"]);
                require_once("vues/FormulaireEmprunter.php");

            }else{

                $donnees["messageErreur"] = "Il faut se connecter pour emprunter le livre";
                require_once("vues/FormulaireConnexion.php");

            }     

            break;

        case "EmprunterLivre":

            $emprunte = enregistrer_livre_emprunte($_GET["id"],$_SESSION["usager_id"]);
            
            if($emprunte){

                $donnees["livre"] = obtenir_livre_par_id($_GET["id"]);
                $donnees["messagePositif"] = "Livre a été emprunté avec succès";
                require_once("vues/FormulaireEmprunter.php");

            }else{

                $donnees["livre"] = obtenir_livre_par_id($_GET["id"]);
                $donnees["messageErreur"] = "Livre n'a pas été emprunté";
                require_once("vues/FormulaireEmprunter.php");

            }
            
            break;
        case "FormulaireAjouterLivre":

            if(isset($_SESSION["usager_id"])){

                $donnees["editeurs"] = obtenir_editeurs();
                $donnees["auteurs"] = obtenir_auteurs();
                require_once("vues/FormulaireAjouterLivre.php");

            }else{

                $donnees["confirmation"] = "Il faut se connecter pour ajouter des livres";
                $donnees["livres_tout"] = obtenir_livres();
                $donnees["livres"] = obtenir_livres_par_page(1);
                require_once("vues/Livres.php");

            }

            break;

        case "AjouterLivre":

            if(trim($_POST["titre"]) != "" && trim($_POST["description"]) != "" && trim($_POST["ISBN"]) != "" 
          && trim($_POST["editeur"]) != "" && isset($_POST["auteur"]) && trim($_FILES["image"]["name"]) != ""){

               $livre_id = enregistrer_livre($_POST["titre"],$_POST["description"],$_POST["ISBN"],$_POST["editeur"]);     
               chargerImage($livre_id);
               foreach ($_POST["auteur"] as $auteur_id) {

                    enregistrer_livre_auteur($livre_id, $auteur_id);
                   
               }

               $donnees["livres_tout"] = obtenir_livres();
               $donnees["livres"] = obtenir_livres_par_page(1);
               $donnees["confirmation"] = "Livre '" . $_POST["titre"] . "' a été ajouté avec succès";
               require_once("vues/Livres.php");

            }else{

                $donnees["editeurs"] = obtenir_editeurs();
                $donnees["auteurs"] = obtenir_auteurs();
                $donnees["messageErreur"] = "Il faut entrer des valeurs dans les champs.";
                require_once("vues/FormulaireAjouterLivre.php");

            }

            break;
        case "FormulaireModifierLivre":

            $donnees["editeurs"] = obtenir_editeurs();
            $donnees["auteurs"] = obtenir_auteurs();
            $donnees["livre"] = obtenir_livre_par_id($_GET["id"]);
            $donnees["auteurs_selectionne"] = obtenir_auteurs_par_livre_id($_GET["id"]);
            require_once("vues/FormulaireModifierLivre.php");


            break;

        case "ModifierLivre":

            if(trim($_POST["titre"]) != "" && trim($_POST["description"]) != "" && trim($_POST["ISBN"]) != "" 
          && trim($_POST["editeur"]) != "" && isset($_POST["auteur"]) && trim($_FILES["image"]["name"]) != ""){

               $livre_id = $_GET["id"];
               modifier_livre($livre_id,$_POST["titre"],$_POST["description"],$_POST["ISBN"],$_POST["editeur"]);     
               chargerImage($livre_id);
               supprimer_auteurs_par_livre($livre_id);
               foreach ($_POST["auteur"] as $auteur_id) {

                    enregistrer_livre_auteur($livre_id, $auteur_id);
                   
               }
               
               $donnees["livres_tout"] = obtenir_livres();
               $donnees["livres"] = obtenir_livres_par_page(1);
               $donnees["confirmation"] = "Livre '" . $_POST["titre"] . "' a été modifié avec succès";
               require_once("vues/Livres.php");

            }else{

                $donnees["editeurs"] = obtenir_editeurs();
                $donnees["auteurs"] = obtenir_auteurs();
                $donnees["livre"] = obtenir_livre_par_id($_GET["id"]);
                $donnees["messageErreur"] = "Il faut entrer des valeurs dans les champs.";
                require_once("vues/FormulaireModifierLivre.php");

            }
            
            break;

        case "FormulaireAjouterAuteur":

            require_once("vues/FormulaireAjouterAuteur.php");

            break;

        case "AfficherAuteurs":

            $donnees["auteurs"] = obtenir_auteurs();
            require_once("vues/Auteurs.php");
    
            break;            

        case "AjouterAuteur":

            if(trim($_POST["nom"]) != "" && trim($_POST["prenom"]) != "" && trim($_POST["date"]) != ""){

                enregistrer_auteur($_POST["nom"], $_POST["prenom"],$_POST["date"]);

                $donnees["auteurs"] = obtenir_auteurs();
                $donnees["confirmation"] = "Auteur '".$_POST["nom"]." ".$_POST["prenom"]. "' a été ajouté avec succès";
                require_once("vues/Auteurs.php");

            }else{

                $donnees["messageErreur"] = "Il faut entrer des valeurs dans les champs.";
                require_once("vues/FormulaireAjouterAuteur.php");

            }

            break;

        case "FormulaireModifierAuteur":

            $donnees["auteur"] = obtenir_auteur_par_id($_GET["id"]);
            require_once("vues/FormulaireModifierAuteur.php");

            break;

        case "ModifierAuteur":

            if(trim($_POST["nom"]) != "" && trim($_POST["prenom"]) != "" && trim($_POST["date"]) != ""){

                modifier_auteur($_GET["id"],$_POST["nom"], $_POST["prenom"],$_POST["date"]);

                $donnees["auteurs"] = obtenir_auteurs();
                $donnees["confirmation"] = "Auteur '".$_POST["nom"]." ".$_POST["prenom"]. "' a été modifié avec succès";
                require_once("vues/Auteurs.php");
                

            }else{

                $donnees["messageErreur"] = "Il faut entrer des valeurs dans les champs.";
                $donnees["auteur"] = obtenir_auteur_par_id($_GET["id"]);
                require_once("vues/FormulaireModifierAuteur.php");

            }

            break;

        case "AfficherEditeurs":

            $donnees["editeurs"] = obtenir_editeurs();
            require_once("vues/Editeurs.php");

            break;

        case "FormulaireAjouterEditeur":

            require_once("vues/FormulaireAjouterEditeur.php");

            break;

        case "AjouterEditeur":

            if(trim($_POST["nom"]) != "" && trim($_POST["adresse"]) != "" ){

                enregistrer_editeur($_POST["nom"], $_POST["adresse"]);

                $donnees["confirmation"] = "Editeur '".$_POST["nom"]. "' a été ajouté avec succès";
                $donnees["editeurs"] = obtenir_editeurs();
                require_once("vues/Editeurs.php");


            }else{

                $donnees["messageErreur"] = "Il faut entrer des valeurs dans les champs.";
                require_once("vues/FormulaireAjouterEditeur.php");

            }

            break;

        case "FormulaireModifierEditeur":

            $donnees["editeur"] = obtenir_editeur_par_id($_GET["id"]);
            require_once("vues/FormulaireModifierEditeur.php");

            break;

        case "ModifierEditeur":

            if(trim($_POST["nom"]) != "" && trim($_POST["adresse"]) != "" ){

                modifier_editeur($_GET["id"],$_POST["nom"], $_POST["adresse"]);

                $donnees["editeurs"] = obtenir_editeurs();
                $donnees["confirmation"] = "Editeur '".$_POST["nom"]. "' a été modifié avec succès";
                require_once("vues/Editeurs.php");                

            }else{

                $donnees["messageErreur"] = "Il faut entrer des valeurs dans les champs.";
                $donnees["editeur"] = obtenir_editeur_par_id($_GET["id"]);
                require_once("vues/FormulaireModifierEditeur.php");

            }

            break;

        case "FormulaireSupprimerLivre":

            require_once("vues/FormulaireConfirmationLivre.php");
            
            break;

        case "SupprimerLivre":

            $livreEmprunte = livre_emprunte($_GET["id"]);
            if(isset($_POST["bouton_oui"])){

                $livre = mysqli_fetch_assoc(obtenir_livre_par_id($_GET["id"]));               

                if(!$livreEmprunte){
                    supprimer_auteurs_par_livre($_GET["id"]);
                    supprimer_livre_par_id($_GET["id"]); 
                    unlink("images/livres/".$_GET["id"].".jpg");             
                    $donnees["confirmation"] = "Livre '" . $livre["titre"] . "' a été supprimé";
                }else{

                    $donnees["confirmation"] = "Livre '" . $livre["titre"] . "' ne peut pas être supprimé car il est emprunté";

                }

            }

            $donnees["livres_tout"] = obtenir_livres();
            $donnees["livres"] = obtenir_livres_par_page(1);
            require_once("vues/Livres.php");

            break;

        case "FormulaireSupprimerAuteur":

            require_once("vues/FormulaireConfirmationAuteur.php");
                
            break;
    
        case "SupprimerAuteur":
    
            if(isset($_POST["bouton_oui"])){
    
                $auteur = mysqli_fetch_assoc(obtenir_auteur_par_id($_GET["id"]));
                supprimer_livres_par_auteur($_GET["id"]);
                supprimer_auteur_par_id($_GET["id"]);

                $donnees["confirmation"] = "Auteur '". $auteur["nom"]." ". $auteur["prenom"] ."' a été supprimé";
    
            }
    

            $donnees["auteurs"] = obtenir_auteurs();
            require_once("vues/Auteurs.php");
    
            break;     
                
        case "FormulaireSupprimerEditeur":

            require_once("vues/FormulaireConfirmationEditeur.php");
                    
            break;
        
        case "SupprimerEditeur":
        
            if(isset($_POST["bouton_oui"])){
        
                $editeur = mysqli_fetch_assoc(obtenir_editeur_par_id($_GET["id"]));
                supprimer_editeur_dans_livres($_GET["id"]);
                supprimer_editeur_par_id($_GET["id"]);
    
                $donnees["confirmation"] = "Editeur '". $editeur["nom"] ."' a été supprimé";
        
            }
        
    
            $donnees["editeurs"] = obtenir_editeurs();
            require_once("vues/Editeurs.php");
        
            break;                 

        default:
            //action non traitée - redirection
            echo "<h1>Page non trouvée</h1>";
            break;
    }
    
?>