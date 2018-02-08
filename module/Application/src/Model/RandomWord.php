<?php
/**
 * Created by PhpStorm.
 * User: hami
 * Date: 2014/8/18
 * Time: 下午 6:07
 */

namespace Application\Model;


class RandomWord {
    // formatting preferences
    // (decisions are made in the order your see here):
    protected $makeMoreRandom = TRUE; // it takes a little longer
    protected $isLowerCase = TRUE;
    protected $isUCFirst = TRUE; // first letter upper-case, the rest lower
    protected $isUpperCase = FALSE;
    protected $shoutIt = FALSE; // example: chivoy!

    // private:
    protected $vowels = array("a","e","i","o","u","y");
    protected $consonants = array(array()); // see the constructor
    protected $word = "";

    // constructor:
    public function __construct()
    {
        $this->consonants[0] = array(
            "b","c","d","f","g","h","j","k","l","m","n","p","r","s","t","v","w","z");
        $this->consonants[1] = array("ch","qu","th","xy");
    }

    // ------------------------------------------------------- |

    // public:
    public function set($protected,$value)
    {
        $this->$protected = $value;
    }



    // public:
    public function buildWord($length=10)
    {
        $done = FALSE;
        $cons_or_vowel = 1;

        // makes the word:
        while(!$done){
            $this->seed();
            // 1 adds a consonant:
            if(1==$cons_or_vowel){
                $i = rand(0,1);
                $add = $this->consonants[$i][array_rand($this->consonants[$i])];
                $cons_or_vowel = 2;
            }
            // 2 adds a vowell:
            elseif(2==$cons_or_vowel){
                $add = $this->vowels[array_rand($this->vowels)];
                $cons_or_vowel = 1;
            }
            $this->word .= $add;
            if(strlen($this->word)>=$length) $done=TRUE;
        }
        // truncate word to fit desired length:
        // (in case a double-consonant was added for the last char build)
        $this->word = substr($this->word,0,$length);
        // change case according to protected prefs:
        $this->formatCase();
        // shout it:
        if($this->shoutIt) $this->word .= "!";

        return $this->word;
    }

    // public:
    // (you need to call buildWord() first though)
    public function addNumbers($length=4)
    {
        for($i=1; $i<=$length; $i++){
            $this->seed();
            $this->word .= (string) rand(0,9);
        }
        return $this->word;
    }

    // ------------------------------------------------------- |

    // private:
    private  function formatCase()
    {
        if($this->isLowerCase) $this->word = strtolower($this->word);
        if($this->isUCFirst) $this->word = ucfirst(strtolower($this->word));
        if($this->isUpperCase) $this->word = strtoupper($this->word);

        return $this->word;
    }

    // private:
    private function seed()
    {
        if($this->makeMoreRandom) usleep(1);
        srand((double)microtime()*1000000);
    }
} 