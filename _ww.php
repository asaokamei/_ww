<?php

/**
 * underscore-walla-walla
 * based on underscore.php
 * to understand how it works. 
 */

// use as static method. 
$ww = _ww::each( 
            array( 1,2,3 ), 
            function($n) { echo $n . "\n"; } 
        );

// use as a chain of methods. 
_ww::chain(  array( 1,2,3 ) )
    ->each(  function($n) { echo "in chain $n" . "\n"; } )
    ->get(   $mid )
    ->map(   function($n) { return $n * 2; } )
    ->value( $result );
var_dump( $mid );
var_dump( $result );


class _ww
{
    private $_wrapped = NULL;  // holds the data
    
	// +--------------------------------------------------------------- +
    /**
     * constructor. feel like making it a private method... 
     * @param array $collection 
     */
    public function __construct( $collection ) {
        $this->_wrapped = (array) $collection;
    }
	// +--------------------------------------------------------------- +
    /**
     * starts a chain. 
     * @param array $collection
     * @return object(_ww)
     */
    public static function chain( $collection ) {
        return new _ww( $collection );
    }
	// +--------------------------------------------------------------- +
    /** 
     * End the chain, and returns the result.
     * @param mix $result
     * @return mix  the result of chain, or null if not in chain.
     */
    public function value( &$result=NULL ) {
        if( isset( $this ) ) {
            $result = $this->_wrapped;
            return $this->_wrapped;
        }
        return NULL;
    }
	// +--------------------------------------------------------------- +
    /**
     * gets inermediate result in the middle of a chain. 
     * @param mix $_ww   result at the middle of a chain 
     * @return $this     continue chain.
     */
    public function get( &$_ww ) {
        $_ww = $this->_wrapped;
        return $this;
    }
	// +--------------------------------------------------------------- +
    /**
     * magic method to call _wwm methods in chain. 
     * @param type $name  name of method (map, each, etc.)
     * @param type $input  arguments (iterator, etc. ).
     * @return $this 
     */
    public function __call( $method, $input ) {
        $arg  = array_merge( array( $this->_wrapped ), $input );
        if( is_callable( "_wwm::$method") ) {
            $return = call_user_func_array( "_wwm::$method", $arg ) ;
        }
        if( !is_null( $return ) ) {
            $this->_wrapped = $return;
        }
        return $this;
    }
    
	// +--------------------------------------------------------------- +
    /**
     * magic method to call _wwm methods statically.
     * @param type $name  name of method (map, each, etc.)
     * @param type $args  arguments (collection, iterator, etc. ).
     * @return mix        result.  
     */
    public static function __callStatic( $method, $arguments ) {
        if( is_callable( "_wwm::$method") ) {
            return call_user_func_array( "_wwm::$method", $arguments );
        }
    }
	// +--------------------------------------------------------------- +
}

class _wwm
{
	// +--------------------------------------------------------------- +
    /** 
     * Invoke the iterator on each item in the collection
     * @param array $collection
     * @param closure $iterator 
     */
    static public function each( $collection, $iterator ) {
        if( !empty( $collection ) )
        foreach($collection as $k=>$v) {
            call_user_func($iterator, $v, $k, $collection);
        }
    }
    
	// +--------------------------------------------------------------- +
    /**
     * Return an array of values by mapping each item through the iterator
     * @param array $collection
     * @param closure $iterator
     * @return array 
     */
    static public function map( $collection, $iterator ) {
        $return = array();
        if( !empty( $collection ) )
        foreach( $collection as $k => $v ) {
            $return[] = call_user_func( $iterator, $v, $k, $collection );
        }
        return $return;
    }
    
	// +--------------------------------------------------------------- +
}

?>