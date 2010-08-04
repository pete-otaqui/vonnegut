<?php
/**
 * Class which uses the Zend_Reflection API for parsing PhpDoc comments.
 * 
 * @package vonnegut
 * @author Pete Otaqui <pete.otaqui@bbc.co.uk>
 * @version $Rev$
 */
class Vonnegut
{
    
    
    /**
     * Reflects on a given file, parsing out classes and methods
     * for serialization.
     * 
     * @param string $path the path of a file to reflect.
     * @return object the serialized documentation object.
     */
    public function reflectFile($path) {
        require_once($path);
        $filename = (strpos($path,"/")!==false) ? preg_replace("|.*/(.+)$|",$path,"$1") : $path;
        $serial = new StdClass();
        $serial->path = $path;
        $serial->classes = array();
        $serial->interfaces = array();
        $serial->functions = array();
        $file_reflector = new Zend_Reflection_File($path);
        $classes = $file_reflector->getClasses();
        foreach ( $classes as $class ) {
            $classSerial = $this->reflectClass($class);
            $isInterface = $classSerial->interface;
            unset($classSerial->interface);
            if ( $isInterface == false ) {
                $serial->classes[$classSerial->name] = $classSerial;
            } else {
                $serial->interfaces[$classSerial->name] = $classSerial;
            }
            unset($classSerial->name);
        }
        $functions  = $file_reflector->getFunctions();
        foreach ( $functions as $function ) {
            $functionSerial = $this->reflectMethod($function);
            $serial->functions[$function->name] = $functionSerial;
        }
        return $serial;
    }
    
    /**
     * Reflects on a php string (useful for reflecting 'files' not on
     * the local filesystem).
     *
     * @param string $phpString 
     * @return object
     * @author pete otaqui
     */
    public function reflectString($phpString) {
        $path = tempnam(sys_get_temp_dir(), uniqid('__vonnegut__').'.php');
        file_put_contents($path, $phpString);
        return $this->reflectFile($path);
    }
    
    /**
     * Serializes a Class docblock.
     *
     * @param ReflectionClass $reflection 
     * @return void
     * @author pete otaqui
     */
    public function reflectClass($reflection) {
        $serial = new StdClass();
        $serial->name = $reflection->name;
        $properties = $reflection->getProperties();
        $serial->properties = count($properties) ? array() : new StdClass();
        foreach ( $properties as $property ) {
            $serialProp = new StdClass();
            $serialProp->name = $property->name;
            if ( $dbProp = $property->getDocComment() ) {
                $serialProp->description = $dbProp->getShortDescription() ."\n\n". $dbProp->getLongDescription();
            }
            $serial->properties[$serialProp->name] = $serialProp;
            unset($serialProp->name);
        }
        $methods = $reflection->getMethods();
        $serial->methods = count($methods) ? array() : new StdClass();
        foreach ( $methods as $method ) {
            if ( $method->getDeclaringClass()->name !== $reflection->name ) continue;
            $serialMethod = $this->reflectMethod($method);
            $serial->methods[$serialMethod->name] = $serialMethod;
            unset($serialMethod->name);
        }
        try {
            $db = $reflection->getDocBlock();
            $serial->description = $db->getShortDescription() . "\n\n" . $db->getLongDescription();
        } catch ( Zend_Reflection_Exception $e ) {
            
        }
        // put parent class name into $serial->extends
        $parentClass = (array) $reflection->getParentClass();
        if ( array_key_exists('name', $parentClass) ) $serial->extends = $parentClass['name'];
        // abstract / final / interface
        $serial->abstract = $reflection->isAbstract();
        $serial->final = $reflection->isFinal();
        $serial->interface = $reflection->isInterface();
        // put interfaces into $serial->implements
        $serial->implements = $reflection->getInterfaceNames();
        // constants
        $serial->constants = $reflection->getConstants();
        return $serial;
    }
    
    /**
     * Serializes a Method docblock.
     *
     * @param ReflectionMethod $reflection 
     * @return object
     * @author pete otaqui
     */
    public function reflectMethod($reflection) {
        $serial = new StdClass();
        $serial->name = $reflection->name;
        if ( $reflection->isPrivate() ) $serial->access = "private";
        if ( $reflection->isProtected() ) $serial->access = "protected";
        if ( $reflection->isPublic() ) $serial->access = "public";
        try {
            $db = $reflection->getDocBlock();
            $serial->description = $db->getShortDescription() . "\n\n" . $db->getLongDescription();
            //$serial->body = $reflection->getContents(true);
        } catch ( Zend_Reflection_Exception $e ) {
            //$serial->body = $reflection->getContents(false);
            $db = false;
        }
        $serial->parameters = array();
        $serial->tags = array();
        if ( $db ) {
            $tags = $db->getTags();
            foreach ( $tags as $tag ) {
                $tagSerial = new StdClass();
                $tagSerial->description = $tag->getDescription();
                if ( is_a($tag, "Zend_Reflection_Docblock_Tag_Return") ) {
                    $tagSerial->type = $tag->getType();
                    $serial->return = $tagSerial;
                } elseif ( is_a($tag, "Zend_Reflection_Docblock_Tag_Param") ) {
                    $tagSerial->type = $tag->getType();
                    $tagSerial->variableName = $tag->getVariableName();
                    $serial->parameters[] = $tagSerial;
                } else {
                    //$tagSerial->name = $tag->getName();
                    $serial->tags[] = $tagSerial;
                }
            }
        }
        return $serial;
    }
    
    
}
