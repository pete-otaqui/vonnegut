<?php

require_once('VonnegutTestCase.php');
/**
 * Test the basic Vonnegut class
 *
 * @package VonnegutTests
 * @author pete otaqui
 */
class VonnegutTest extends VonnegutTestCase
{
    
    public function testReflectFile() {
        $vonnegut = new Vonnegut();
        $serial = $vonnegut->reflectFile(dirname(__FILE__) . "/fixtures/Standalone/Class.php");
        $vType = "Vonnegut String Serialization";
        $this->assertObjectHasAttribute('classes', $serial, "$vType does not have a 'classes' attribute");
    }
    
    public function testReflectClass() {
        $vonnegut = new Vonnegut();
        require_once(dirname(__FILE__) . "/fixtures/Standalone/Class.php");
        $serial = $vonnegut->reflectClass(new Zend_Reflection_Class('Standalone_Class'));
        $this->markTestIncomplete('This test is yet to be implemented');
    }
    
    public function testReflectMethod() {
        $vonnegut = new Vonnegut();
        require_once(dirname(__FILE__) . "/fixtures/Standalone/Class.php");
        $serial = $vonnegut->reflectClass(new Zend_Reflection_Class('Standalone_Class'));
        $this->markTestIncomplete('This test is yet to be implemented');
    }
    
    
    public function testReflectString() {
        $phpString = <<<PHPSTRING
<?php
/**
 * phpString.php file description.
 * 
 * @package VonnegutTests
 * @author Joe Bloggs
 * @see SomethingElse
 */
/**
 * Lorem ipsum dolor sit amet.
 * 
 * Long description would go here and be a bit longer.
 * 
 * @package VonnegutTests
 * @author Joe Bloggs
 * @see SomethingElse
 */
class OneThing
{
    /**
     * Reference to the hoozit.
     * 
     * This is the long description of the hoozit which spans multiple
     * lines.
     * 
     * @var HoozitObject \$hoozit
     */
    protected \$hoozit;
    
    /**
     * Gets a whatsit.
     *
     * @param string \$thingy 
     * @param object \$mabob 
     * @return HoozitObject \$hoozit
     */
    public function whatsit(\$thingy, \$mabob) {
        return \$this->hoozit;
    }
    protected function eck() {
        
    }
}
/**
 * Short description of AndAnother
 * 
 * @package VonnegutTests
 * @author Joe Bloggs
 * @see SomethingElse
 */
class AndAnother
{
    
}
class UndocumentedClass
{
    private function undocumentedMethod(\$param1, \$param2) {
        
    }
}
PHPSTRING;
        $vonnegut = new Vonnegut();
        $serial = $vonnegut->reflectString($phpString);
        $vType = "Vonnegut String Serialization";
        $this->assertObjectHasAttribute('path', $serial,                "$vType does not contain a 'path' attribute");
        $this->assertObjectHasAttribute('classes', $serial,             "$vType does not contain a 'classes' attribute");
        $this->assertEquals(3, count($serial->classes),                 "$vType does not contain 3 classes");
        $this->assertObjectHasAttribute('methods', $serial->classes[0], "$vType Class does not contain a 'methods' attribute");
        $this->assertEquals(2, count($serial->classes[0]->methods),     "$vType Class does not contain 2 methods");
        $this->assertEquals('Gets a whatsit.', $serial->classes[0]->methods[0]->shortDescription, "OneThing::whatsit method has the wrong short description");
        
    }
}