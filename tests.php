<?php

require 'testCase.php';
require 'application.php';

class Test extends TestCase
{
    public function getResult($text1, $text2) {
        $app = new Application($text1, $text2);
        $app->run();
        return $app->getResult();
    }

    public function test1 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3', '1.3'),
            [["1","unchanged"],["2","deleted"],["3","unchanged"]],
            $testName
        );
    }

    public function test2 ($testName) {
        $this->assertEquals(
            $this->getResult('1.4.3', '1.2.3'),
            [["1","unchanged"],["2","changed","4"],["3","unchanged"]],
            $testName
        );
    }

    public function test3 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2', '1.2.3'),
            [["1","unchanged"],["2","unchanged"],["3","added"]],
            $testName
        );
    }

    public function test4 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3.4', '1.4'),
            [["1","unchanged"],["2","deleted"],["3","deleted"],["4","unchanged"]],
            $testName
        );
    }

    public function test5 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3.4', '1.3'),
            [["1","unchanged"],["2","deleted"],["3","unchanged"],["4","deleted"]],
            $testName
        );
    }

    public function test6 ($testName) {
        $this->assertEquals(
            $this->getResult('1.3', '1.2.3.4'),
            [["1","unchanged"],["2","added"],["3","unchanged"],["4","added"]],
            $testName
        );
    }

    public function test7 ($testName) {
        $this->assertEquals(
            $this->getResult('1.4', '1.2.3.4'),
            [["1","unchanged"],["2","added"],["3","added"],["4","unchanged"]],
            $testName
        );
    }

    public function test8 ($testName) {
        $this->assertEquals(
            $this->getResult('1.4.5', '1.2.3.4.5'),
            [["1","unchanged"],["2","added"],["3","added"],["4","unchanged"],["5","unchanged"]],
            $testName
        );
    }

    public function test9 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3', '1.3.2'),
            [["1","unchanged"],["3","added"],["2","unchanged"],["3","deleted"]],
            $testName
        );
    }

    public function test10 ($testName) {
        $this->assertEquals(
            $this->getResult('1.6.7.4', '1.2.3.4'),
            [["1","unchanged"],["2","changed","6"],["3","changed","7"],["4","unchanged"]],
            $testName
        );
    }

    public function test11 ($testName) {
        $this->assertEquals(
            $this->getResult('1.5.4', '1.2.3.4'),
            [["1","unchanged"],["2","changed","5"],["3","added"],["4","unchanged"]],
            $testName
        );
    }

    public function test12 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3.4.5.6', '1.3.6.4.5.2'),
            [["1","unchanged"],["3","added"],["6","added"],["4","added"],["5","added"],["2","unchanged"],["3","deleted"],["4","deleted"],["5","deleted"],["6","deleted"]],
            $testName
        );
    }

    public function test13 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3.4', '1.3.6.4'),
            [["1","unchanged"],["2","deleted"],["3","unchanged"],["6","added"],["4","unchanged"]],
            $testName
        );
    }

    public function test14 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3.4.7', '1.4.5.6.7'),
            [["1","unchanged"],["2","deleted"],["3","deleted"],["4","unchanged"],["5","added"],["6","added"],["7","unchanged"]],
            $testName
        );
    }

    public function test15 ($testName) {
        $this->assertEquals(
            $this->getResult('1.2.3.4', '1.5.4'),
            [["1","unchanged"],["5","changed","2"],["3","deleted"],["4","unchanged"]],
            $testName
        );
    }

    public function test16 ($testName) {
        $this->assertEquals(
            $this->getResult('2.5', '5'),
            [["2","deleted"],["5","unchanged"]],
            $testName
        );
    }

    public function test17 ($testName) {
        $this->assertEquals(
            $this->getResult('5.2', '5'),
            [["5","unchanged"],["2","deleted"]],
            $testName
        );
    }

    public function test18 ($testName) {
        $this->assertEquals(
            $this->getResult('5', '5.2'),
            [["5","unchanged"],["2","added"]],
            $testName
        );
    }

//    public function test19 ($testName) {
//        $this->assertEquals(
//            $this->getResult('2.5.9.6.7', '6.7.2'),
//            [["2","deleted"],["5","deleted"],["9","deleted"],["6","unchanged"],["7","unchanged"],["2","added"]],
//            $testName
//        );
//    }

    public function test20 ($testName) {
        $this->assertEquals(
            $this->getResult('6.7.2', '2.5.9.6.7'),
            [["2","added"],["5","added"],["9","added"],["6","unchanged"],["7","unchanged"],["2","deleted"]],
            $testName
        );
    }

    public function test21 ($testName) {
        $this->assertEquals(
            $this->getResult('6.2', '2.5.9.6'),
            [["2","added"],["5","added"],["9","added"],["6","unchanged"],["2","deleted"]],
            $testName
        );
    }

    public function test22 ($testName) {
        $this->assertEquals(
            $this->getResult('6.7.2', '4.2.5.9.6.7.3'),
            [["4","added"],["2","added"],["5","added"],["9","added"],["6","unchanged"], ["7","unchanged"],["3","changed","2"]],
            $testName
        );
    }
}

$test = new Test();
$test->run();
