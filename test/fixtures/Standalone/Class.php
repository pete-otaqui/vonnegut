<?
/**
 * This file contains a standalone class in it's own package.
 * 
 * @author pete otaqui
 * @version $Id$
 * @copyright bbc.co.uk, 30 June, 2010
 * @package standalone
 **/

/**
 * Standalone class in it's own package.
 * 
 * @property mixed $readwrite a readable and writable magic property
 * @property-read string $read a readble magic property
 * @property-write mixed $write a writable magic property
 * 
 * @package standalone
 * @author pete otaqui
 */
class Standalone_Class
{
    /**
     * Static property.
     * 
     * Static properties are called on the class.
     * 
     * @example $foo = Standalone_Class::static;
     */
    static $static = "static";
    
    /**
     * Static method.
     * 
     * Static methods are called on the class.
     * 
     * @example if ( Standalone_Class::staticMethod() ) echo "yay!";
     * @param Standalone_Class $arg1 an instance of this class.
     * @return boolean
     */
    public static function staticMethod(Standalone_Class $arg1) {
        return true;
    }
    
    /**
     * Undocumented type-hinting.
     * 
     * This static method uses type hinting for the param, but does
     * not have any documentation for type.
     * 
     * @return boolean
     */
    public static function undocumentedTypeHint(Standalone_Class $arg1) {
        return true;
    }
    
    /**
     * Public property.
     *
     * @var array
     */
    public $public = array();
    
    /**
     * Public property which is not defined with a type.
     */
    public $publicMixed;
    
    /**
     * Public method.
     * 
     * A public method can be thought of by user's of the class as it's API.
     * <code>
     * // example 1
     * $instance = new FixtureSingleClass();
     * $result = $instance->publicMethod('arg1');
     * </code>
     * <code>
     * // example 2
     * $instance = new FixtureSingleClass();
     * $result = $instance->publicMethod('arg1', true);
     * </code>
     * 
     * @example $result = $fixtureSingleClassInstance->publicMethod('foo');
     * @param mixed $arg1 A required parameter, which can be different types.
     * @param bool $arg2 optional An optional boolean parameter, default is false.
     * @return array|string|object
     * @author pete otaqui
     **/
    public function publicMethod($arg1, $arg2 = false) {
        
    }
    
    /**
     * It's a good thing this is deprecated, because it
     * doesn't do anything - although it's short description
     * does span a few lines.
     * 
     * But it's long description is quite short!
     * 
     * @deprecated
     * @return void
     * @author pete otaqui
     **/
    public function deprecatedMethod () {}
    
    
    
    
    
    protected $_readwrite;
    protected $_write;
    
    public function __get($key) {
        switch ($key) {
            case 'read' :
                return "you read me like a book!";
            case 'readwrite' :
                return $this->_readwrite;
            break;
        }
    }
    public function __set($key, $val) {
        switch ($key) {
            case 'write' :
                $this->_write = $val;
            case 'readwrite' :
                $this->_readwrite = $val;
            break;
        }
    }
    
}



