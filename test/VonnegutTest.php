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
        $vType = "Vonnegut File Serialization";
        $this->assertObjectHasAttribute('classes', $serial, "$vType has 'classes'");
        $this->assertObjectHasAttribute('interfaces', $serial, "$vType has 'interfaces'");
        $this->assertObjectHasAttribute('functions', $serial, "$vType has 'functions'");
        //$this->assertObjectHasAttribute('constants', $serial, "$vType has 'constants'");
        //$this->assertObjectHasAttribute('variables', $serial, "$vType has 'variables'");
        //$this->assertObjectHasAttribute('namespaces', $serial, "$vType has 'namespaces'");
        //$this->assertObjectHasAttribute('meta', $serial, "$vType has 'meta'");
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
    
    public function testUndocumentedParamTypeHint() {
        $vonnegut = new Vonnegut();
        require_once(dirname(__FILE__) . "/fixtures/Standalone/Class.php");
        $class = $vonnegut->reflectClass(new Zend_Reflection_Class('Standalone_Class'));
        $method = $class->methods['undocumentedTypeHint'];
        $parameters = $method->parameters;
        $this->assertEquals( 1, count($parameters), "Takes 1 parameter");
        $parameter = $parameters[0];
        $this->assertEquals( "arg1", $parameter->name, "Name is 'arg1'");
        $this->assertEquals( "Standalone_Class", $parameter->type, "Type is Standalone_Class");
        $this->assertFalse( $parameter->allowsNull, "Does not allow null");
        $this->assertFalse( $parameter->passedByReference, "Is not passed by reference");
    }
    
    public function testDocumentedParamTypeHint() {
        $vonnegut = new Vonnegut();
        require_once(dirname(__FILE__) . "/fixtures/Standalone/Class.php");
        $class = $vonnegut->reflectClass(new Zend_Reflection_Class('Standalone_Class'));
        $method = $class->methods['documentedTypeHint'];
        $parameters = $method->parameters;
        $this->assertEquals( 1, count($parameters), "Takes 1 parameter");
        $parameter = $parameters[0];
        $this->assertEquals( "\$overrideName", $parameter->name, "Name is 'overrideName'");
        $this->assertEquals( "Override_Class", $parameter->type, "Type is Standalone_Class");
        $this->assertTrue( $parameter->allowsNull, "Allows null");
        $this->assertFalse( $parameter->passedByReference, "Is not passed by reference");
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
        $oneThing = $serial->classes['OneThing'];
        $this->assertObjectHasAttribute('classes', $serial,     "$vType contains a 'classes' attribute");
        $this->assertObjectHasAttribute('constants', $serial,   "$vType has 'constants'");
        $this->assertObjectHasAttribute('variables', $serial,   "$vType has 'variables'");
        $this->assertObjectHasAttribute('namespaces', $serial,  "$vType has 'namespaces'");
        $this->assertObjectHasAttribute('meta', $serial,        "$vType has 'meta'");
        $this->assertEquals(3, count($serial->classes),         "$vType contains 3 classes");
        $this->assertObjectHasAttribute('methods', $oneThing,   "$vType Class contains a 'methods' attribute");
        $this->assertArrayHasKey('whatsit', $oneThing->methods, "$vType Class contains whatsit method");
        $this->assertArrayHasKey('eck', $oneThing->methods,     "$vType Class contains eck method");
        $this->assertEquals("Gets a whatsit.", $oneThing->methods['whatsit']->description, "OneThing::whatsit method has the right description");
        
    }
}