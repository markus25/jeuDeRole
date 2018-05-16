<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Guerrier
 *
 * @author Markus-Strike
 */
require_once '../personnage/Personnage.class.php';

class Guerrier extends Personnage 
  {
    protected $_frappeEpee;
    
    public function frapperAvecEpee ($perso)
    {
     $perso->recevoirDegats($this->_frappeEpee); // force du guerrier 
    }     
    
     public function gagnerExperience() {
        // On appelle la méthode gagnerExperience de la classe parente
        parent::gagnerExperience();
        
        if ($this->_frappeEpee < 100)
        {
         $this->_frappeEpee += 10;   
        }   
    }
    
     public function parler() {
        $test = parent::parler();
        echo $test;
    }
    
    public function recevoirDegats($force) {
        if ($this->_degats >=0 && $this->_degats <= 25)
        {
         $this->_atout = 4;
        }  
        elseif($this->_degats > 25 && $this->_degats <=50)
        {
         $this->_atout = 3;   
        }  
        elseif($this->_degats > 50 && $this->_degats <=75)
        {
         $this->_atout = 2;   
        } 
         elseif($this->_degats > 50 && $this->_degats <=90)
        {
         $this->_atout = 1;   
        }
        else
        {
          $this->_atout = 0;
        }   
        
        $this->_degats += 5 - $this->_atout;
        
        // Si on a 100 de dégâts ou plus, on supprime le personnage de la BDD
        if ($this->_degats >= 100)
        {
         return self::PERSONNAGE_TUE;   
        }  
        // Sinon on se contente de mettre à jour les dégâts  du personnage
        return self::PERSONNAGE_FRAPPE;
    }
 
  }
