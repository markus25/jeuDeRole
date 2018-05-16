<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Personnage
 *
 * @author Markus-Strike
 */
abstract class  Personnage {
    
    protected $_id,
              $_nom,
              $_force,
              $_degats,
              $_timeEndormi,
              $_typePersonnage,
              $_atout,
              $_niveau,
              $_experience;
    
    // Constantes pour faciliter les tests
    
     const CEST_MOI = 1;  // constante renvoyée par la méthode 'frapper' si on se frappe soi-même
    const PERSONNAGE_TUE = 2; // constante renvoyée si on a tué le personnage ne le frappant
    const PERSONNAGE_FRAPPE = 3; // constante renvoyée si on a bien frappé le personnage
    const PERSONNAGE_ENSORCELE = 4; // constante renvoyé par la méthode 'lancerUnSort' si on a bien ensorcelé le personnage
    const PAS_DE_MAGIE = 5; // cosntante renvoyée si on veut jeter un sort et que la magie = 0
    const PPERSO_ENDORMI = 6; // constante renvoyée si le personnage qui veut frapper est endormi
    
    // Constructeur 
    public function __construct(array $donnees)
     {
         $this->hydrate($donnees);
         $this->type = strtolower(get_class($this));
     }       

    
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set'.ucfirst($key);
            
            if (method_exists($this, $method))
            {
                $this -> $method($value);
            }   
        }  
    }   



      
    // Accesseurs de la classe (Get)
    
     public function getId()
    {
        return $this->_id;
    }
    
     public function getNom()
    {
        return $this->_nom;
    }
    public function getForce()
    {
        return $this->_degats;
    }    
    
    public function getDegats()
    {
        return $this->_degats;
    } 
    
    public function  getAtout()
    {
     return $this->_atout;   
    }       

    public function getTimeEndormi()
    {
      return $this->_timeEndormi;  
    }       
    
    public function getTypePersonnage()
    {
      return $this->_typePersonnage;  
    }       

    public function getNiveau()
    {
        return $this->_niveau;
    } 
    
    public function getExperience()
    {
       return $this->_experience; 
    }       
    
         

     // Mutateurs de la classe (Set)
    public function setId($id)
    {
      $id = (int)$id;  
      
      if ($id > 0)
      {
         $this->_id = $id; 
      }  
    }   

    public function setNom($nom)
    {
      if (is_string($nom) && strlen($nom) <= 30)
      {   
      $this->_nom = $nom;  
      }
    }
    public function setForce($force)
    {
        $force = (int) $force;
        if ($force >=1 && $force <= 100)
        {
             $this->_force = $force;
        }  
    }
    
     public function  setDegats($degats)
    {
       $degats = (int) $degats;
       if ($degats >= 0 && $degats <= 100)
       {   
       $this->_degats = $degats; 
       }
    }  
    
    public function  setAtout()
    {
     $atout = (int) $atout;
     if ($atout >=0 && $atout <= 100)
     {
         $this->_atout = $atout;
     }   
    }   

    public function  setTimeEndormi()
    {
        $this->_timeEndormi = (int) $time;
    }       

    public function setNiveau ( $niveau )
      {
       $niveau = (int) $niveau ;

       if ( $niveau >= 1 && $niveau <= 100)
        {
         $this -> _niveau = $niveau ;
        }
       }     

    
    public function setExperience($experience)
    {
       $experience = (int) $experience;
       
       if ($experience >= 1 && $experience <= 100)
       {  
        $this->_experience = $experience;
       }
    }
   


// Méthodes  de la classe
    
public function parler()
{
    echo 'Je suis un grand personnage!';
}  

 public function frapper(Personnage $perso)
 {
     if ($perso->id() == $this->_id)
     {
      return self::CEST_MOI;   
     }  
     
     if ($this->estEndormi())
     {
      return self::PPERSO_ENDORMI;   
     }   
     return $perso->recevoirDegats();
 }      
 
 public function recevoirDegats()
 {
     $this->_degats += 5;
     if ($this->_degats >= 100)
     {
         return self::PERSONNAGE_TUE;   
     }    
     return self::PERSONNAGE_FRAPPE;
 }       

 public function  reveil()
 {
   $secondes = $this->timeEndormi;
   $secondes -= time();
   
   $heures = floor($secondes / 3600);
   $secondes -= $heures * 3600;
   $minutes = floor($secondes / 60);
   $secondes -= $minutes * 60;
   
   $heures .= $heures <= 1 ? 'heure' : 'heures';
   $minutes .= $minutes <= 1 ? 'minute' : 'minutes';
   $secondes .= $secondes <= 1 ? 'seconde' : 'secondes';
   
   return $heures.','.$minutes.'et'.$secondes;
 }       

 public function estEndormi()
 {
  return $this->timeEndormi > time();   
 }    
 
public function afficherExperience()
{
  echo $this->_experience ;  
}  

public function gagnerExperience()
{
   $this->_experience = $this->_experience + 1; 
} 

public function nomValide()
{
 return !empty($this->_nom);   
}  
}

    
