<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonnageManager
 *
 * @author Markus-Strike
 */

require_once 'Personnage.class.php';

class PersonnageManager 
   {
   // private $_db;
    private $db;  // Instance de PDO
    
    public function __construct($db) {
        //$this->setDb($db);
        $this->db = $db;
    }
    
    public function add(Personnage $perso)
    {
        // Préparation de la requête d'insertion .
        $q = $this->db->prepare('INSERT INTO personnage SET nom =: nom, typePersonnage =:typePersonnage');
      
        // Assignation des valeurs pour le nom du personnage . 
       $q->bindValue(':nom', $perso->getNom());
       $q->bindValue(':ypePersonnage', $perso->getTypePersonnage());
     
       // Exécution de la requête.
        $q->execute();
     // Hydratation du personnage passé en paramètre avec assignation de son identifiant et des dégâts initiaux (=0).
        $perso->hydrate(array('id'=> $this->db->lastInsertId(),
            'degats'=> 0,
            'atout' =>0
            ));
    } 
    
    public function count()
    {
      // Exécute une requête COUNT () et retourne le nombre de résultats retourné.
        return $this->db->query('SELECT COUNT(*) FROM personnage')->fetchColumn();   
    }       

    public function delete(Personnage $perso)
    {
         // Exécute une requête de type DELETE .
        $this->db->exec('DELETE FROM personnages WHERE id= '.$perso->getId());
    }   
    
    public function exists($info)
    {
      if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe 
        {
           return (bool) $this->db->query('SELECT COUNT(*) FROM personnage WHERE id = '.$info)->fetchColumn(); 
        }   
        // Sinon on veut vérifier que le nom existe ou pas
        $q = $this->db->prepare('SELECT COUNT(*) FROM personnage WHERE nom = :nom');
        $q->execute(array(':nom'=> $info));
        
        return (bool) $q->fetchColumn();   
    }       
    
     public function get($info)
    {
      if (is_int($info)) 
      {   
     $q = $this->db->query('SELECT id, nom, degats FROM personnage WHERE id= '.$info);
     $perso = $q->fetch(PDO::FETCH_ASSOC);
      }
      else
      {
          $q = $this->_db->prepare('SELECT id, nom, degats FROM personnage WHERE nom = :nom');
          $q->execute(array(':nom'=> $info));
          $perso($q->fetch(PDO::FETCH_ASSOC));
      }  
      switch ($perso['typePersonnage'])
      {
          case 'guerrier': return new Guerrier ($perso);
          case 'magicien': return new Magicien ($perso);    
      }
    } 
    
    public function getList($nom)
    {
       $persos = array();
       $q = $this->db->prepare('SELECT id, nom, degats FROM personnage WHERE  nom <> :nom ORDER BY nom');
       $q->execute(array(':nom'=> $nom));
       
       while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
       {
        switch ($donnees ['typePersonnage'])
        {
         case 'guerrier': $persos[]= new Guerrier($donnees);
             break;
         case 'magicien': $persos[]= new Magicien($donnees);
             break; 
        }
       }       
       
       return $persos;  
    }     
    
    public function update (Personnage $perso)
    {
      $q = $this->db->prepare('UPDATE personnage SET degats = :degats, tiemEndormi =: timeEndormi, atout =: atout  WHERE id = :id');
      $q->bindvalue(':degats', $perso->getDegats(), PDO::PARAM_INT);
      $q->bindValue(':timeEndormi', $perso->getTimeEndormi(), PDO::PARAM_INT);
      $q->bindValue(':atout', $perso->getAtout(), PDO::PARAM_INT);
      $q->bindvalue(':id', $perso->getId(), PDO::PARAM_INT);
      
      $q->execute(); 
    }     
    
    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }      
}
