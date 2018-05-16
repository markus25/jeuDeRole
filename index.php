<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//include_once 'view/vue_personnage.php';
function chargerClasse($classname)
{
 require 'model/personnage/'.$classname.'.class.php'; 
}

spl_autoload_register('chargerClasse'); 

session_start();

if (isset($_GET['deconnexion']))
{
    session_destroy();
    header('Location: .');
    exit();
} 

$db = new PDO('mysql:host=localhost;dbname=jeuderole', 'adminUser','Aston');
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
$manager = new PersonnageManager($db);

if (isset($_SESSION['perso']))
{
  $perso = $_SESSION['perso'];  
}  

if (isset($_POST['creer']) && isset($_POST['nom'])) // Si on a voulu créer un personnage
{
 switch ($_POST['typePersonnage'])
 {
  case 'magicien':
  $perso = new Magicien(array('nom'=> $_POST['nom']));
   break; 
  
  case 'guerrier':
  $perso = new Guerrier(array('nom'=> $_POST['nom']));
   break; 

  default:
    $message = 'Le type de personnage est invalide.';
    break;      
 }
 
 if  (isset($perso))  // Si le type de personnage est valide, on a crée un personnage
 {
     if (!$perso->nomValide())
     {
         $message = 'Le nom choisi est invalide.';
         unset($perso);
     }   
     elseif ($manager->exists($perso->getNom()))
     {
      $message = 'Le nom est déjà pris!';
      unset($perso);
     } 
     else 
     {
       $manager->add($perso);  
     }   
 }   
}   
 
 elseif ( isset ( $_POST ['utiliser ']) && isset ( $_POST ['nom '])) // Si on a voulu utiliser un personnage .
 {
if ( $manager -> exists ( $_POST ['nom '])) // Si celui -ci existe .
 {
 $perso = $manager -> get( $_POST ['nom ']);
 }
 else
 {
 $message = 'Ce personnage n\' existe pas!'; // S'il n'existe pas , on affichera ce message .
 }
 }

 elseif ( isset ( $_GET ['frapper '])) // Si on a cliqué sur un personnage pour le frapper .
 {
      if (! isset ( $perso ))
 {
 $message = 'Merci de créer un personnage ou de vous
identifier .';
 }

 else
 {
 if (! $manager -> exists (( int) $_GET ['frapper ']))
 {
 $message = 'Le personnage que vous voulez frapper n\'
existe pas!';
 }

 else
 {
 $persoAFrapper = $manager ->get (( int ) $_GET ['frapper ']);

 $retour = $perso -> frapper ( $persoAFrapper ); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper

   switch ( $retour )
    {
     case Personnage :: CEST_MOI :
          $message = 'Mais... pourquoi voulez - vous vous frapper ??? ';
     break ;

    case Personnage :: PERSONNAGE_FRAPPE :
         $message = 'Le personnage a bien été frappé!';

        $manager -> update ( $perso );
        $manager -> update ( $persoAFrapper );
     break ;

    case Personnage :: PERSONNAGE_TUE :
         $message = 'Vous avez tué ce personnage !';

         $manager -> update ( $perso );
         $manager -> delete ( $persoAFrapper );
    break ;
   }
   }
   }
   }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mini jeu de combat</title>
    </head>
    <body>
        <p> Nombre de personnages créés : <?php echo $manager -> count(); ?></p>
        <?php
        if ( isset ( $message )) // On a un message à afficher ?
        {  
          echo '<p>', $message , ' </p>'; // Si oui , on l'affiche .
        }
        if ( isset ( $perso )) // Si on utilise un personnage ( nouveau ou pas ).
        {
        ?>
        <p><a href="?deconnexion=1">Déconnexion</a> </p>
        
        <fieldset>
            <legend>Mes informations</legend>
            <p>
                Nom: <?php echo htmlspecialchars($perso->getNom()); ?><br/>
                Dégâts: <?php echo $perso->getDegats(); ?> <br/>
       <?php  
     // On affiche l'atout du personnage suivant le type
       switch($perso->getTypePersonnage())
       {
        case 'magicien':
            echo 'Magie';
            break;
        
        case 'guerrier':
            echo 'Protection: ';
            break;
       }
       echo $perso->getAtout();
       ?>
         </p>
        </fieldset>
        
        <fieldset>
            <legend>Qui frapper ?</legend >
             <p>
                <?php
                  $persos = $manager -> getList ($perso ->getNom());
        // On récupère tous les personnages par ordre alphab étique ,dont le nom est diff é rent de celui de notre personnage (on va pas se frapper nous -même :p).
                  $retourPersos = $manager->getList($perso->getNom());
                 if ( empty ( $retourPersos ))
                  {
                   echo 'Personne à frapper !';
                   }

                 else
                  {
                   foreach ( $retourPersos as $unPerso )
                   {
                    echo '<a href ="?frapper =', $unPerso ->getId() , '">',
                    htmlspecialchars ( $unPerso ->getNom()), ' </a> (dégâts : ',
                    $unPerso ->getDegats() , ')<br />';
                    }
                    }
                 ?>
              </p>
        </fieldset>
        <?php
        }
        else
        {
       ?>
             <form action ="" method ="post">
              <p>
                 Nom : <input type ="text" name ="nom" maxlength ="50" />
                 <input type ="submit" value ="Utiliser ce personnage" name ="utiliser" /><br/><br/>
                 Type:
                 <select name="typePersonnage">
                     <option value="magicien">Magicien</option>
                     <option value="guerrier">Guerrier</option>
                 </select>
                 <input type="submit" value="Créer ce personnage" name="creer"/>
               </p>
             </form >
     <?php
     }
     ?>
       <script src=""></script>      
    </body>
</html>
<?php
 if ( isset ( $perso )) // Si on a créé un personnage , on le stocke dans une variable session afin d'économiser une requête SQL.
 {
   $_SESSION ['perso'] = $perso ;
  }
