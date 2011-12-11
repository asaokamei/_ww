<?php

/**
 * test _ww
 */
require_once './_ww.php';

class ArryTest extends PHPUnit_Framework_TestCase 
{
	// +--------------------------------------------------------------- +
    function test_static() {
        
        // use as static method. 
        
        // test each.
        global $static_test1;
        $static_test1 = array();
        $ww = _ww::each( 
                array( 1,2,3 ), 
                function($n) { global $static_test1; $static_test1[] = $n; } 
            );
        $this->assertEquals( array( 1, 2, 3 ), $static_test1 );
        
        // test sortBy
        $ww = _ww::sortBy(
                array( 'a' => 1, 'b' => 2, 'c' => 3 ),
                function( $a ) {
                    return - $a;
                }
            );
        $sorted = array( 'c' =>3, 'b' => 2, 'a' => 1 );
        $this->assertEquals( $sorted, $ww );
        
        // test sort (usort)
        $ww = _ww::sort(
                array( 1, 2, 3 ),
                function( $a ) { return -$a; }
            );
        $sorted = array( 3, 2, 1 );
        $this->assertEquals( $sorted, $ww );
        var_dump( $ww );
    }
    
	// +--------------------------------------------------------------- +
    function test_chain() {
        
        // use as a chain of methods. 
        global $chain_test1;$chain_test1 = array();
        _ww::chain(  array( 1,2,3 ) )
            ->each(  function($n) { global $chain_test1; $chain_test1[] = "chain $n"; } )
            ->get(   $mid )
            ->map(   function($n) { return $n * 2; } )
            ->value( $result );
        $correct = array( 'chain 1', 'chain 2', 'chain 3' );
        $this->assertEquals( $correct, $chain_test1 );
        $correct = array( 2, 4, 6 );
        $this->assertEquals( $correct, $result );
        
    }
    
	// +--------------------------------------------------------------- +
}





?>
