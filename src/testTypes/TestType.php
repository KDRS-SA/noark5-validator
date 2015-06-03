<?php


/*
 * We could also develop a few straight forward tests
 * that we carry out for here. For example we could
 * create a check well formedness of all files in 
 * a given directory. This would be added to for example
 * SingelFileTest.php. The point is we can do a lot of 
 * fun things
 */


class TestType {
	
	function __construct($testName) {
		print "In TestType constructor " . PHP_EOL;

	}

	public function __toString()
    {
        return "toString in Test Class";
    }
}

?>