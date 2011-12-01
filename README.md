Underscore-Walla-Walla.php
==========================

This is a PHP class inspired by underscore.php at 
https://github.com/brianhaveri/Underscore.php.

Some codes are copied from the Underscore.php but mostly 
re-written. So, I hope MIT license will be OK. 

USAGE
-----

A simple example using _ww as static method. 

    // use as static method. 
    $ww = _ww::each( 
                array( 1,2,3 ), 
                function($n) { echo $n . "\n"; } 
            );

Using _ww as a chained method. 

    // use as a chain of methods. 
    _ww::chain(  array( 1,2,3 ) )
        ->each(  function($n) { echo "in chain $n" . "\n"; } )
        ->get(   $mid )
        ->map(   function($n) { return $n * 2; } )
        ->value( $result );
    var_dump( $mid );
    var_dump( $result );

will result in 

    1
    2
    3
    in chain 1
    in chain 2
    in chain 3
    array(3) {
      [0]=>  int(1)
      [1]=>  int(2)
      [2]=>  int(3)
    }
    array(3) {
      [0]=>  int(2)
      [1]=>  int(4)
      [2]=>  int(6)
    }

