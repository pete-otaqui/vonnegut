<?
/**
 * Grandchild class which inherits from Child.
 * 
 * @package fixtures
 */
class Fixtures_Extends_Grandchild 
{
    /**
     * These docs are defined in the grandparent, skipped in the
     * child and redefined again in the grandchild.
     * 
     * @param string $arg1 
     * @return void
     * @author pete otaqui
     */
    public function overwriteMeWithDocs($arg1) {
        
    }
}