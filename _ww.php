<?php

/**
 * underscore-walla-walla
 * based on underscore.php
 * to understand how it works. 
 */

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
        $return = NULL;
        if( is_callable( "_ww::_{$method}") ) {
            $return = call_user_func_array( "_ww::_{$method}", $arg ) ;
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
        if( is_callable( "_ww::_{$method}") ) {
            return call_user_func_array( "_ww::_{$method}", $arguments );
        }
        return NULL;
    }
	// +--------------------------------------------------------------- +
    /** 
     * Invoke the iterator on each item in the collection
     * @param array $collection
     * @param closure $iterator 
     */
    static public function _each( $collection, $iterator ) {
        if( !empty( $collection ) )
        foreach($collection as $k=>$v) {
            call_user_func($iterator, $v, $k, $collection);
        }
        return NULL;
    }
    
	// +--------------------------------------------------------------- +
    /**
     * Return an array of values by mapping each item through the iterator
     * @param array $collection
     * @param closure $iterator
     * @return array 
     */
    static public function _map( $collection, $iterator ) {
        $return = array();
        if( !empty( $collection ) )
        foreach( $collection as $k => $v ) {
            $return[] = call_user_func( $iterator, $v, $k, $collection );
        }
        return $return;
    }
    
	// +--------------------------------------------------------------- +
    /**
     * Sort the collection by return values from the iterator
     * @param type $collection
     * @param type $iterator
     * @return type 
     */
    public function _sortBy( $collection, $iterator ) {
        $results = array();
        foreach( $collection as $k => $item ) {
            $results[$k] = $iterator( $item );
        }
        asort($results);
        foreach( $results as $k => $v ) {
            $results[$k] = $collection[$k];
        }
        return $results;
    }
    
	// +--------------------------------------------------------------- +
    /**
     * Sort the collection by return values from the iterator
     * @param type $collection
     * @param type $iterator
     * @return type 
     */
    public function _sort( $collection, $iterator ) {
        usort( $collection, $iterator );
        return $collection;
    }
	// +--------------------------------------------------------------- +
}

?>