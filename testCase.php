<?php

class TestCase {

    public function run() {
        $methods = get_class_methods(get_class($this));
        foreach($methods as $method) {
            if (substr($method, 0, 4) == 'test') {
                 $this->$method(substr($method, 4));
            }
        }
    }

    /**
     * Prints a string to STDOUT.
     *
     * @param string $string the string to print
     * @return integer|boolean Number of bytes printed or false on error
     */
    public static function stdout($string)
    {
        echo  $string . PHP_EOL;
    }

    public function failure($string)
    {
        $this->stdout("\033[41m" . $string . "\033[37m\r");
    }

    public function success($string)
    {
        $this->stdout("\033[42m" . $string . "\033[37m\r");
    }


    protected function assertEquals ($expected , $actual, $testName) {
        if ($expected == $actual) {
            $this->success($testName);
        } else {
            $this->failure($testName);
        }
    }
}
