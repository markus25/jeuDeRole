<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Magicien
 *
 * @author Markus-Strike
 */
require_once '../personnage/Personnage.class.php';

class Magicien extends Personnage 
  {
    protected $_magie;
    
    public function lancerUnSort (Personnage $perso)
    {
      if ($this->_degats >=0 && $this->_degats <=25)
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
      if($perso->_id == $this->_id)
      {
          return self::CEST_MOI;
      } 
      if($this->_atout == 0)
      {
       return self::PAS_DE_MAGIE;   
      }   
      if ($this->estEndormi())
      {
          return self::PPERSO_ENDORMI;
      } 
      
      $perso->_timeEndormi = time() + ($this->_atout * 6) * 3600;
      return self::PERSONNAGE_ENSORCELE;
    } 
    
    public function gagnerExperience() {
        // On appelle la méthode gagnerExperience de la classe parente
        parent::gagnerExperience();
        
        if ($this->_magie < 100)
        {
         $this->_magie += 10;   
        }   
    }
    
    public function parler() {
        $test = parent::parler();
        echo $test;
    }
 
    
    
    }
