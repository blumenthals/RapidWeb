#!/usr/bin/env php
<?php
/**
 * Copyright © 2012 Online Buddies, Inc. - All Rights Reserved
 *
 * @package Modyllic
 * @author bturner@online-buddies.com
 */

require_once dirname(__FILE__)."/../test_environment.php";

$delim = "DELIMITER ;;\n";
$datatypes = array();
$datatypes[] = "BOOLEAN";
$assert_results[] = array(
    array( null, true ),
    array( 0, true ),
    array( 1, true ),
    array( false, true ),
    array( true, true ),
    array( 2, false ),
    array( -1, false ),
    array( "", false ),
    array( array(), false ),
);
$datatypes[] = "YEAR";
$assert_results[] = array(
    array(   null, true ),
    array(      0, true ),
    array(   "99", true ),
    array( "1999", true ),
    array(  "abc", false ),
    array(      5, false ),
    array(  12345, false ),
);
$datatypes[] = "FLOAT";
$assert_results[] = array(
    array(   null, true ),
    array(    123, true ),
    array(  "456", true ),
    array(  "45a", false ),
    array(  "1.3", true ),
    array(  "5e7", true ),
    array(   12.2, true ),
);
$datatypes[] = "INTEGER";
$assert_results[] = array(
    array(   null, true ),
    array(    123, true ),
    array(  "456", true ),
    array(  "45a", false ),
    array(  "1.3", false ),
    array(  "5e7", true ),
    array(    1.5, false ),
    array(     -3, true ),
);
$datatypes[] = "INTEGER UNSIGNED";
$assert_results[] = array(
    array(   null, true ),
    array(    123, true ),
    array(  "456", true ),
    array(  "45a", false ),
    array(  "1.3", false ),
    array(  "5e7", true ),
    array(    1.5, false ),
    array(     -3, false ),
);

plan('no_plan');

$parser = new Modyllic_Parser();

$sql = $delim;
foreach ($datatypes as $num => $datatype) {
    $sql .= "CREATE PROCEDURE test$num( testvar $datatype ) BEGIN END $delim\n";
}

$schema = $parser->parse($sql);

$gen = new Modyllic_Generator_PHP();

global $assert_result;

function test_assertion($file,$line,$code) {
    global $assert_result;
    $assert_result = false;
}

assert_options( ASSERT_WARNING, 0);
assert_options( ASSERT_QUIET_EVAL, 1 );
assert_options( ASSERT_BAIL, 0 );
assert_options( ASSERT_CALLBACK, 'test_assertion' );

foreach ( $schema->routines as $name=>$routine ) {

    if ( preg_match( "/test(\d+)/", $name, $matches ) ) {
        $num = $matches[1];
    }
    else {
        fail( "Found a routine whose name isn't test##: $name" );
        exit(1);
    }

    $gen->args_validate($routine);

    $php = $gen->get_and_flush_php();
    foreach (explode("\n",trim($php)) as $line) {
        diag($line);
    }

    foreach ( $assert_results[$num] as $assertion ) {
        list( $testvar, $expectedValue ) = $assertion;

        $assert_result = true;
        eval( $php ); // if an assert fails in here, it will set
                      // assert_result to false

        $should = "should" . ($expectedValue ? "" : " not");
        $testedValue = gettype($testvar);

        if ( is_bool($testvar) ) {
            $testedValue .= ": ".($testvar ? "TRUE" : "FALSE");
        }
        else if ( is_string($testvar) ) {
            $str = $testvar;
            $str = preg_replace('/\\\/', '\\\\', $str);
            $str = preg_replace('/\'/', '\\\'', $str);
            $testedValue .= ": '$str'";
        }
        else if ( is_scalar($testvar) ) {
            $testedValue .= ": ".$testvar;
        }
        else if ( is_null($testvar) or is_array($testvar) ) {
        }
        else {
            $testedValue .= ": ".print_r( $testvar, true );
        }

        if ( ! ok($assert_result == $expectedValue, "{$datatypes[$num]}: $should accept $testedValue" ) ) {
            diag( "        ". preg_replace('/\n/',"\n#         ", trim($php)) );
        }

    }

}

