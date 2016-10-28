<?php

class Application
{
    private $result = [];
    public $text1;
    public $text2;

    public function __construct($text1, $text2) {
        $this->text1 = explode('.', $text1);
        $this->text2 = explode('.', $text2);
    }

    public function run() {
        $text1 = $this->text1;
        $text2 = $this->text2;
        if (count($text1) < 2 || count($text2) < 2) {
            $this->step2($text1, $text2);
            return;
        }

        $l = count($text1);
        $index1 = 0;
        $index2 = 1;
        $flag = false;
        $first = true;

        while (true) {
            $indexArr = $this->indexes($index1, $index2, $text1, $text2);
            if ($indexArr) {
                if ($first) {
                    $this->step2(
                        array_slice($text1, 0, $indexArr[0]),
                        array_slice($text2, 0, $indexArr[2])
                    );
                    $first = false;
                }
                $this->check(
                    array_slice($text1, $indexArr[0], $indexArr[1] + 1),
                    array_slice($text2, $indexArr[2], $indexArr[3] + 1)
                );
                $flag = true;
                break;
            }

            $index2++;
            if ($index2 == $l) {
                $index1++;
                $index2 = $index1 + 1;
                if ($index2 == $l) {
                    break;
                }
            }
        }


        if ($flag) {
            $this->text1 = array_slice($text1, $indexArr[1]);
            $this->text2 = array_slice($text2, $indexArr[3]);

            if (count($this->text1) != 1 || count($this->text2) != 1) {
                $this->run();
            } else {
                $this->addToResult($this->text1, 'not changed');
            }
        } else {
            $this->step2($text1, $text2);
            return;
        }

    }




    public function addToResult($array, $status) {
        foreach ($array as $element) {
            array_push($this->result, [$element, $status]);
        }
    }

    public function addToResultChanged($array1, $array2) {
        foreach ($array1 as $i => $element) {
            array_push($this->result, [$array2[$i], 'changed', $element]);
        }
    }

    function different($a, $b)
    {
        if (count($a) == count($b)) {
            if (count($a) != 0) {
                $this->addToResultChanged($a, $b);
            }
        } else if (count($a) > count($b)) {
            if (count($b) != 0) {
                $this->different(array_slice($a, 0, count($b)), $b);
            }
            $this->addToResult(
                array_slice($a, 0 - (count($a) - count($b))),
                'deleted'
            );
        } else {
            if (count($a) != 0) {
                $this->different($a, array_slice($b, 0, count($a)));
            }
            $this->addToResult(array_slice($b, 0 - (count($b)- count($a))), 'added');
        }
    }

    function step3($arr1, $arr2)
    {
        $flag = false;
        for ($index1 = 0; $index1 < count($arr1); $index1++) {
            $element = $arr1[$index1];
            $index2 = array_search($element, $arr2);
            if ($index2 !== false) {
                $flag = true;
                $this->different(
                    array_slice($arr1, 0, $index1),
                    array_slice($arr2, 0, $index2)
                );
                $this->addToResult([$element], 'not changed');
                $this->different(
                    array_slice($arr1, $index1 + 1),
                    array_slice($arr2, $index2 + 1)
                );
                break;
            }
        }
        if (!$flag) {
            $this->different($arr1, $arr2);
        }
    }

    function step2($array1, $array2) {
        $overlap = [];
        $pos1 = 0;
        $pos2 = 0;
        for ($i = 1; $i < count($array1); $i++) {
            $searched = [$array1[$i - 1], $array1[$i]];
            $intersect = array_intersect($array2, $searched);
            if (count($intersect) > 1) {
                if (implode($intersect) == implode($searched)) {
                    $overlap = $searched;
                    $pos1 = $i - 1;
                    array_pop($intersect);
                    $pos2 = array_keys($intersect)[0];
                    break;
                }
            }
        }

        if ($overlap) {
            $this->step2(array_slice($array1, 0, $pos1), array_slice($array2, 0, $pos2));
            $this->addToResult($overlap, 'not changed');
            $this->step2(
                array_slice($array1, $pos1 + count($overlap)),
                array_slice($array2, $pos2 + count($overlap))
            );
        } else {
            $this->step3($array1, $array2);
        }
    }

    function check($array1, $array2) {
        $this->addToResult([$array1[0]], 'not changed');

        if (count($array1) == 2) {
            $this->addToResult(array_slice($array2, 1, count($array2) - 2), 'added');
            return;
        }

        if (count($array2) == 2) {
            $this->addToResult(
                array_slice($array1, 1, count($array1) - 2),
                'deleted'
            );
            return;
        }

        $this->step2(array_slice($array1, 1, -1), array_slice($array2, 1, -1));
        return;
    }


    function indexes($i1, $i2, $arr1, $arr2) {
        if ($i1 < 0 || $i2 < 0 || count($arr1) < 2 || count($arr2) < 2) {
            return false;
        }

        $i3 = array_search($arr1[$i1], $arr2);
        if ($i3 !== false) {
            $search_array =  array_slice($arr2, $i3 + 1);
            $i4 = array_search($arr1[$i2], $search_array);
            if ($i4 !== false) {
                $i4 = $i4 + $i3 + 1;
                return [$i1, $i2, $i3, $i4];
            }
        }
    }


    public function res() {
        foreach ($this->result as $sentence) {
            $changed = '';
            $color = 'black';
            switch ($sentence[1]) {
                case 'added':
                    $color = 'green';
                    break;
                case 'deleted':
                    $color = 'red';
                    break;
                case 'changed':
                    $color = 'DarkOrange';
                    $changed = ' (' . $sentence[2] . ')';
                    break;
            }
            echo '<p style="color: ' . $color . '">' . $sentence[0] . $changed . '</p>';
        }
    }

    public function getResult() {
        return $this->result;
    }

}
