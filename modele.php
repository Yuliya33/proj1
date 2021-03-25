<?php
    /*
        modele.php est le fichier qui représente notre MODÈLE dans notre architecture MVC. C'est donc dans ce fichier que nous retrouverons TOUTES nos requêtes SQL sans AUCUNE EXCEPTION. C'est aussi ici que se trouvera la connexion à la base de données et les informations nécessaires à celle-ci (username, hostname, password, nom de la base, etc...)
    
    */
    define("server", "localhost");
    define("username", "");
    define("password", "");
    define("dbname", "");

    function connectDB()
    {
        $c = mysqli_connect(server, username, password, dbname);
        
        if(!$c)
            trigger_error("Erreur de connexion:" . mysqli_connect_error());
        
        //s'assurer que la connexion traite du UTF8
        mysqli_query($c, "SET NAMES 'utf8'");
        
        return $c;
    }

    //je me déclare une variable globale qui contient la connexion
    $connexion = connectDB();

    function filtre($chaine)
    {
        global $connexion;

        $chaineFiltre = mysqli_real_escape_string($connexion, $chaine);
        //autres filtres à appliquer?
        //$chaineFiltre = html_entities($chaineFiltre);
        return $chaineFiltre;
    } 

    function obtenir_livres($recherche = null)
    {
        global $connexion;
        
        if($recherche == null){

            $requete = "SELECT * FROM livre";

        }else{

            $requete = "SELECT * FROM livre WHERE titre LIKE '%$recherche%'";

        }

        $resultat = mysqli_query($connexion, $requete);

        return $resultat;

    }

    function obtenir_livre_par_id($livre_id){

        global $connexion;
        
        $requete = "SELECT livre.id, livre.titre, livre.description, livre.ISBN, livre.id_editeur, editeur.nom FROM livre
                    LEFT JOIN editeur ON editeur.id=livre.id_editeur 
                    WHERE livre.id=$livre_id"; 

        $resultat = mysqli_query($connexion, $requete);

        return $resultat; 


    }

    function obtenir_livres_par_page($page_courant, $par_livre = 10){

        global $connexion;
        
        $requete = "SELECT * FROM livre LIMIT ". (($page_courant*$par_livre)-$par_livre).",".$par_livre; 

        $resultat = mysqli_query($connexion, $requete);

        return $resultat;        

    }

    function obtenir_auteurs(){

        global $connexion;
        
        $requete = "SELECT * FROM auteur";

        $resultat = mysqli_query($connexion, $requete);

        return $resultat; 

    }

    function obtenir_auteur_par_id($auteur_id){

        global $connexion;
        
        $requete = "SELECT * FROM auteur WHERE id=$auteur_id";

        $resultat = mysqli_query($connexion, $requete);

        return $resultat; 


    }

    function obtenir_auteurs_par_livre_id($livre_id){

        global $connexion;
        
        $requete = "SELECT auteur_id,concat(nom,' ',prenom)as nom FROM livre_auteur
                    JOIN auteur on livre_auteur.auteur_id = auteur.id
                    WHERE livre_auteur.livre_id = $livre_id";

        $resultat = mysqli_query($connexion, $requete);

        return $resultat;

    }

    function enregistrer_livre_emprunte($livre_id, $usager_id){

        global $connexion;
        
        $requete = "INSERT INTO usager_livre(livre_id, usager_id) VALUES($livre_id, $usager_id)";

        $resultat = mysqli_query($connexion, $requete);

        if(mysqli_affected_rows($connexion)>0){

            return true;

        }else{

            return false;

        }

    }

    function enregistrer_livre($titre, $description, $ISBN, $id_editeur){

        global $connexion;
        
        $requete = "INSERT INTO livre(titre, description, ISBN, id_editeur) VALUES('$titre', '$description', '$ISBN', '$id_editeur')";

        $resultat = mysqli_query($connexion, $requete);

        if(mysqli_affected_rows($connexion)>0){

            return mysqli_insert_id($connexion);

        }else{

            return 0;

        }

    }

    function modifier_livre($livre_id, $titre, $description, $ISBN, $id_editeur){

        global $connexion;
        
        $requete = "UPDATE livre SET titre = '" . filtre($titre) . "', description = '" . filtre($description) . "', ISBN = '" . filtre($ISBN) . "', id_editeur = '" . filtre($id_editeur) ."' WHERE id = " . filtre($livre_id); 

        $resultat = mysqli_query($connexion, $requete);

    }    

    function enregistrer_livre_auteur($livre_id, $auteur_id){

        global $connexion;
        
        $requete = "INSERT INTO livre_auteur(livre_id, auteur_id) VALUES('$livre_id', '$auteur_id')";

        $resultat = mysqli_query($connexion, $requete);


    }

    function enregistrer_auteur($nom, $prenom, $date){

        global $connexion;
        
        $requete = "INSERT INTO auteur(nom, prenom, date_de_naissance) VALUES('$nom', '$prenom', '$date')";

        $resultat = mysqli_query($connexion, $requete);

    }

    function modifier_auteur($auteur_id, $nom, $prenom, $date){

        global $connexion;
        
        $requete = "UPDATE auteur SET nom = '" . filtre($nom) . "', prenom = '" . filtre($prenom) . "', date_de_naissance = '" . filtre($date) ."' WHERE id = " . filtre($auteur_id); 

        $resultat = mysqli_query($connexion, $requete);

    }

    function supprimer_auteurs_par_livre($livre_id){

        global $connexion;
        
        $requete = "DELETE FROM livre_auteur WHERE livre_id = $livre_id";

        $resultat = mysqli_query($connexion, $requete);

    }

    function supprimer_livres_par_auteur($auteur_id){

        global $connexion;
        
        $requete = "DELETE FROM livre_auteur WHERE auteur_id = $auteur_id";

        $resultat = mysqli_query($connexion, $requete);

    }

    function supprimer_auteur_par_id($auteur_id){

        global $connexion;
        
        $requete = "DELETE FROM auteur WHERE id = $auteur_id";

        $resultat = mysqli_query($connexion, $requete);  

    }

    function supprimer_editeur_par_id($editeur_id){

        global $connexion;
        
        $requete = "DELETE FROM editeur WHERE id = $editeur_id";

        $resultat = mysqli_query($connexion, $requete);  

    }

    function supprimer_editeur_dans_livres($editeur_id){

        global $connexion;
        
        $requete = "UPDATE livre SET id_editeur =NULL WHERE id_editeur=$editeur_id"; 

        $resultat = mysqli_query($connexion, $requete);  

    }

    function livre_emprunte($livre_id){

        global $connexion;
        
        $requete = "SELECT * FROM usager_livre WHERE livre_id=$livre_id"; 

        $resultat = mysqli_query($connexion, $requete); 

        if(mysqli_affected_rows($connexion)>0){

            return true;

        }else{

            return false;

        }

    }

    function supprimer_livre_par_id($livre_id){

        global $connexion;
        
        $requete = "DELETE FROM livre WHERE id = $livre_id";

        $resultat = mysqli_query($connexion, $requete);

    }

    function obtenir_editeurs(){

        global $connexion;
        
        $requete = "SELECT * FROM editeur";

        $resultat = mysqli_query($connexion, $requete);

        return $resultat; 

    }

    function obtenir_editeur_par_id($editeur_id){

        global $connexion;
        
        $requete = "SELECT * FROM editeur WHERE id=$editeur_id";

        $resultat = mysqli_query($connexion, $requete);

        return $resultat; 

    }

    function enregistrer_editeur($nom, $adresse){

        global $connexion;
        
        $requete = "INSERT INTO editeur(nom, adresse) VALUES('$nom', '$adresse')";

        $resultat = mysqli_query($connexion, $requete);

    }

    function modifier_editeur($editeur_id, $nom, $adresse){

        global $connexion;
        
        $requete = "UPDATE editeur SET nom = '" . filtre($nom) . "', adresse = '" . filtre($adresse) . "' WHERE id = " . filtre($editeur_id); 

        $resultat = mysqli_query($connexion, $requete);

    }

    function authentification($courriel){

        global $connexion;
        
        $requete = "SELECT usager.id, usager.mot_de_passe, usager.nom, usager.courriel, categorie_usager.nom as categorie FROM usager 
                    JOIN categorie_usager ON categorie_usager.id = usager.id_categorie_usager 
                    WHERE courriel='$courriel'"; 

        $resultat = mysqli_query($connexion, $requete);

        return $resultat;

    }

    function afficher_numero_page($nb_livres, $page_courant, $livre_par_page = 10){

        $pages = ceil($nb_livres / $livre_par_page);

        $page_gauche = $page_courant - 1;
        $page_droite = $page_courant + 1;

        if($page_gauche == 0){
            $html = "<div class='pagination'><a class='carre'>&#9635;</a>";    
        }else{
            $html = "<div class='pagination'><a href='index.php?page=$page_gauche'>&#9665;</a>";
        }

        for($i=1; $i <= $pages; $i++){

            if($i == $page_courant){
                $html = $html . "<a href='index.php?page=$i' class='courant'>" . $i . "</a>";
            }else{
                $html = $html . "<a href='index.php?page=$i'>" . $i . "</a>";
            }

        }

        if($page_droite == $pages+1){
            $html = $html . "<a class='carre'>&#9635;</a></div>";
        }else{
            $html = $html . "<a href='index.php?page=$page_droite'>&#9655;</a></div>";
        }

        if($pages != 1){
            echo $html;
        }


    }

    function chargerImage($image_nom){

        $target_dir = "images/livres/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $n_target_file = $target_dir . $image_nom . "." . $imageFileType;

        // Check if image file is a actual image or fake image
        if(isset($_POST["bouton_ajouter"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 1;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $n_target_file)) {
                //echo "The file ". $n_target_file. " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

    }





   