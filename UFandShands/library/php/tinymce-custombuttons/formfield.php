<?php
/**
 * Description of FormField
 *
 * @author sjeng
 */
class FormField {
    
    public $label;
    public $fieldType;
    public $attributeName;
    public $defaultValue;
    public $description;
    public $options = null;
    
    // enums
    const Text = 1;
    const Checkbox = 2;
    const DropDown = 3;
    const Hidden = 4;
    const Categories = 5;
    
    function __construct($label = null, $fieldType = null, $attributeName = null, $defaultValue = null, $description = null, $options = null) {
	$this->label = $label;
	$this->fieldType = $fieldType;
	$this->attributeName = $attributeName;
	$this->defaultValue = $defaultValue;
	$this->description = $description;
	$this->options = $options;
    }

}


class FieldOption {
    public $name;
    public $value;
    
    function __construct($name = null, $value = null) {
	$this->name = $name;
	$this->value = $value;
    }
}
?>
