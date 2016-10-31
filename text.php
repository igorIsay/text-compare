<?php

class Text
{
    private $text;
    private $pointer = 0;

    public function __construct($text) {
        if (is_array($text)) {
            $this->text = $text;
        } else {
            $output = preg_split("/(!|\.|\?) /", $text, -1, PREG_SPLIT_DELIM_CAPTURE);
            $this->text = [];
            for ($i = 0; $i < count($output); $i+=2) {
                $this->text[] = $output[$i] . $output[$i+1];
            }
            $this->text = array_filter($this->text, function ($element) {
                return !empty($element);
            });
        }
    }

    public function isEmpty(){
        return $this->size() == 0;
    }

    public function cutBeforePointer($include = false) {
        $sliced = new Text(
            array_splice($this->text, 0, $this->pointer + (boolean) $include)
        );
        $this->resetPointer();
        return $sliced;
    }

    public function cutAfterPointer($include = false) {
        $sliced = new Text(
            array_splice($this->text, $this->pointer + !(boolean) $include)
        );
        $this->resetPointer();
        return $sliced;
    }

    public function cutCurrentSentence() {
        $sliced = array_splice($this->text, $this->pointer, 1);
        return $sliced;
    }

    public function findSentence($sentence) {
        return array_search($sentence, $this->text);
    }

    public function getFirstSentence() {
        return $this->text[0];
    }


    public function getText() {
        return $this->text;
    }

    public function getPointerSenctence() {
        return $this->text[$this->pointer];
    }

    public function size() {
        return count($this->text);
    }

    public function pointerOnLastSentence() {
        return $this->pointer >= ($this->size() - 1);
    }

    public function setPointer($value) {
        $this->pointer = $value;
    }

    public function incPointer() {
        $this->pointer++;
    }

    public function resetPointer() {
        $this->pointer = 0;
    }
}
