<?php

/**
 * compare performance of underscore.php, arry.php, and _ww.php.
 */
require_once __DIR__ . '/../Underscore.php/underscore.php';
require_once __DIR__ . '/../arry/Arry.php';
require_once __DIR__ . '/../_ww/_ww.php';

/**
 * this data are copied from 
 * http://www.1x1.jp/blog/2011/12/php_arry.html
 */
$data = array(
  array('name' => 'Tigers',   'win' => 68, 'lose' => 70),
  array('name' => 'Dragons',  'win' => 75, 'lose' => 59),
  array('name' => 'Giants',   'win' => 71, 'lose' => 62),
  array('name' => 'Swallows', 'win' => 70, 'lose' => 59),
  array('name' => 'Carp',     'win' => 60, 'lose' => 76),
  array('name' => 'Baystars', 'win' => 47, 'lose' => 86)
);

$try = 5000;
function pingtime() {
    static $a=0;
    $now = microtime(TRUE);
    $ping = $now - $a;
    $a = $now;
    return $ping;
}

// +--------------------------------------------------------------- +
// measure _ww

pingtime();
for( $i = 0; $i < $try; $i++ ) {
      $result = _ww::chain( $data )
        ->map(function($v) {
        $v['percent'] = $v['win'] / ($v['win'] + $v['lose']);
        return $v;

      })->sortBy(function($v1) {
        return -$v1['percent'];

      })->map(function($v) {
        return sprintf("%-10s %02d %02d %.3f\n", $v['name'], $v['win'], $v['lose'], $v['percent']);

      })->value();
}
echo pingtime() . "\n";

// +--------------------------------------------------------------- +
// measure __

for( $i = 0; $i < $try; $i++ ) {
     $result = __( $data )->chain()
        ->map(function($v) {
        $v['percent'] = $v['win'] / ($v['win'] + $v['lose']);
        return $v;

      })->sortBy(function($v1) {
        return -$v1['percent'];

      })->map(function($v) {
        return sprintf("%-10s %02d %02d %.3f\n", $v['name'], $v['win'], $v['lose'], $v['percent']);

      })->value();
}
echo pingtime() . "\n";

// +--------------------------------------------------------------- +
// measure arry.

for( $i = 0; $i < $try; $i++ ) {
    $result = arr( $data )
    ->map(function($v) {
        $v['percent'] = $v['win'] / ($v['win'] + $v['lose']);
        return $v;

      })->usort(function($v1, $v2) {
        return $v1['percent'] < $v2['percent'] ? 1 : -1;

      })->map(function($v) {
        return sprintf("%-10s %02d %02d %.3f\n", $v['name'], $v['win'], $v['lose'], $v['percent']);

      })->y();
}
echo pingtime() . "\n";

// +--------------------------------------------------------------- +
// pure PHP.

for( $i = 0; $i < $try; $i++ ) {
    measurePhp( $data );
}
echo pingtime() . "\n";

function measurePhp( $data ) {
    foreach( $data as $k => $v ) {
        $data[$k][ 'percent'] = $v['win'] / ($v['win'] + $v['lose'] );
    }
    usort( $data, function($v1, $v2) {
            return $v1['percent'] < $v2['percent'] ? 1 : -1;
    });
    foreach( $data as $k => $v ) {
        $data[$k]= sprintf("%-10s %02d %02d %.3f\n", $v['name'], $v['win'], $v['lose'], $v['percent']);
    }
}

?>