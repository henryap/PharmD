<?php

/**
 * Description of CustomButton
 *
 * @author sjeng
 */
class CustomButton {
    
   public $title;
   public $icon;
   public $shortCodeTag;
   public $description;
   public $enclosing = false;
   public $fields;
   public $buttonSeparator = '';
   
   function addField($field) {
       if (!isset($this->fields))
	       $this->fields = array();
       
       array_push($this->fields, $field);
   }
   
}

?>
