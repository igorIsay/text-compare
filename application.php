<?php

require 'text.php';

class Application
{
    const STATUS_ADDED     = 'added';
    const STATUS_DELETED   = 'deleted';
    const STATUS_CHANGED   = 'changed';
    const STATUS_UNCHANGED = 'unchanged';


    private $text1;
    private $text2;
    private $result = [];

    public function __construct($text1, $text2) {
        $this->text1 = new Text($text1);
        $this->text2 = new Text($text2);
    }

    public function getResult() {
        return $this->result;
    }

    public function run()
    {
        $this->step1();
    }

    private function step1() {
        if ($this->text1->isEmpty() || $this->text2->isEmpty()) {
            $this->step4($this->text1, $this->text2);
        } else {
            $this->step2();
        }
    }

    private function step2() {
        if ($this->text1->getFirstSentence() == $this->text2->getFirstSentence()) {
            $this->unchangedSentences(
                $this->text1->cutCurrentSentence()
            );
            $this->text2->cutCurrentSentence();
            $this->step1();
        } else {
            $this->step();
        }
    }

    private function step() {
        $overlap = $this->text2->findSentence(
            $this->text1->getPointerSenctence()
        );

        if ($overlap !== false) {
            $this->text2->setPointer($overlap);
            $this->step4(
                $this->text1->cutBeforePointer(),
                $this->text2->cutBeforePointer()
            );
            $this->step1();
        } else {
            if ($this->text1->pointerOnLastSentence()) {
                $this->step4($this->text1, $this->text2);
                return $this->result;
            } else {
                $this->text1->incPointer();
                $this->step();
            }
        }
    }

    private function step4(Text $text1, Text $text2)
    {
        if ($text1->size() == $text2->size()) {
            if (!$text1->isEmpty()) {
                $this->changedSentences($text1->getText(), $text2->getText());
            }
        } else if ($text1->size() > $text2->size()) {
            if (!$text2->isEmpty()) {
                $text1->setPointer($text2->size());
                $this->step4($text1->cutBeforePointer(), $text2);
            }
            $this->deletedSentences(
                $text1->cutAfterPointer(true)->getText()
            );
        } else {
            if (!$text1->isEmpty()) {
                $text2->setPointer($text1->size());
                $this->step4($text1, $text2->cutBeforePointer());
            }
            $this->addedSentences($text2->cutAfterPointer(true)->getText());
        }
    }

    private function deletedSentences(array $sentences) {
        $this->appendToResult($sentences, self::STATUS_DELETED);
    }

    private function addedSentences(array $sentences) {
        $this->appendToResult($sentences, self::STATUS_ADDED);
    }

    private function unchangedSentences(array $sentences) {
        $this->appendToResult($sentences, self::STATUS_UNCHANGED);
    }

    private function changedSentences($sentences1, $sentences2) {
        foreach ($sentences1 as $index => $sentence) {
            array_push(
                $this->result,
                [$sentences2[$index], self::STATUS_CHANGED, $sentence]
            );
        }
    }

    private function appendToResult($sentences, $status) {
        foreach ($sentences as $sentence) {
            array_push($this->result, [$sentence, $status]);
        }
    }
}
