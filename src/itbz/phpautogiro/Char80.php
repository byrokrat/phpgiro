<?php
/**
 * This file is part of the STB package
 *
 * Copyright (c) 2011-12 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 *
 * @package STB\Giro
 */


/**
 * The 80 character layout lets each line in a txt file be max 80 characters long
 * and specify one piece of information. Usually the first two characters define
 * the type of information (transaction codes).
 *
 * If your file format does not use two character transaction codes,
 * set the correct number of characters to $this->$tcLength.
 *
 * To automatically validate file structure write a regexp describing
 * allowed combinations of transaction codes to $this->structure.
 *
 * To parse specific lines map transaction codes to parsing regular
 * expressions and functions in $this->map.
 *
 * For a live example se the BgMax file format available in this package.
 *
 * @package STB\Giro
 */
abstract class PhpGiro_Char80
{

    /**
     * Name of file being parsed.
     * @var string
     */
    private $fname = "";

    /**
     * Raw contents of the file to be parsed
     * @var array
     */
    private $raw;

    /**
     * Array of strings describing parsing errors
     * @var array
     */
    private $errors = array();

    /**
     * Regexp describing a valid file structure
     * @var string
     */
    protected $struct = "/.*/";

    /**
     * Transaction codes mapped to parsing regexp and function
     * @var array $map
     */
    protected $map = array();

    /**
     * Transaction code caracter length. Set this if your file type
     * does not use standard caracter length (2).
     * @var int $tcLength
     */
    protected $tcLength = 2;

    /**
     * Stack for storing and traversing parsed content
     * @var array $stack
     */
    protected $stack = array();

    /**
     * Parsed and stored sections
     * @var array $sections
     */
    protected $sections = array();

    /**
     * Array for storing custom values associated with this object
     * @var array $values
     */
    protected $values = array();

    /**
     * Array for storing standard values to us as reset values
     * @var array $stdValues
     */
    protected $stdValues = array();    

    /**
     * Layout name, to identfy different sections
     * @var string $layout
     */
    protected $layout = "undef.";

    /**
     * Layout names
     * @var array $layoutNames
     */
    protected $layoutNames = array();



    /* CONSTRUCT */


    /**
     * Set raw content from file or param
     * @param string $raw File name to read from or string content
     */
    public function __construct($raw=false){
        if ( $raw ) {
            if ( !$this->readFile($raw) ) {
                $this->setRawContent($raw);
            }
        }
    }


    /**
     * Read file to parse
     * @param string $fname
     * @return bool TRUE on success, FALSE on failure
     */
    public function readFile($fname){
        if ( is_readable($fname) ) {
            $this->fname = $fname;
            return $this->setRawContent(file_get_contents($fname));
        } else {
            return false;
        }
    }


    /**
     * Set raw content to parse
     * @param string|array $raw
     * @return bool TRUE on success, FALSE on failure
     */
    public function setRawContent($raw){
        if ( is_string($raw) ) {
            $raw = preg_replace("/\r\n/", "\n", $raw);
            $raw = preg_replace("/\n\r/", "\n", $raw);
            $raw = preg_replace("/\r/", "\n", $raw);
            $raw = explode("\n", $raw);
        }
        return is_array($raw) && $this->raw=$raw;
    }


    /**
     * Get raw content
     * @return array
     */
    public function getRawContent(){
        return $this->raw;
    }



    /* CREATE NEW FILES */


    /**
     * Get file representation. Creates a file from current internal content.
     * This might or might not be the same content as readFile returned, depending
     * on if new lines have been added using addLine().
     *
     * Pads all lines to 80 characters. Add \r\n line ending. Validates file
     * structure. Content is returned as ASCII ISO8859-1.
     *
     * @return string|bool FALSE is file does not have a valid structure
     */
    public function getFile(){
        $str = "";
        $this->valid();
        if ( $this->hasError() ) return false;
        foreach ( $this->raw as $line ) {
            $line = iconv("UTF-8", "ISO-8859-1", $line);
            $line = str_pad($line, 80);
            if ( !preg_match("/^\s*$/", $line) ) {
                $str .= $line."\r\n" ;
            }
        }
        $str .= "\r\n";
        return $str;
    }


    /**
     * Write contents to filesystem
     * @param string $fname
     * @param string $dirname
     * @return int Bytes written, FALSE on failure.
     */
    public function writeFile($fname, $dirname=false){
        if ( $dirname && is_dir($dirname) ) {
            $fname = $dirname.DIRECTORY_SEPARATOR.$fname;
        }
        return file_put_contents($fname, $this->getFile());
    }


    /**
     * Add a line to raw content. Must contain a $tc and valid structure
     * @param string $line
     * @return bool TRUE on succes, FALSE on failure
     */
    public function addLine($line){
        $line = preg_replace("/\n/", "", $line);
        $line = preg_replace("/\r/", "", $line);

        $tc = $this->getTc($line);
        if ( !$tc ) {
            $this->error(_("Wrong post format"), 'addLine()');
            return false;
        }

        list($regexp, $func) = $this->map[$tc];

        if ( !preg_match($regexp, $line) ) {
            $this->error(_("Wrong post format"), 'addLine()');
            return false;
        }

        $this->raw[] = $line;
        return true;
    }



    /* PARSE RAW CONTENT */
    

    /**
     * Parse raw content to stack
     * @return bool TRUE on success, FALSE on failure
     */
    public function parse(){
        $this->errors = array();
        
        if ( !$this->valid() ) return false;
        
        foreach ( $this->raw as $line ) {
            $tc = $this->getTc($line);
            if ( $tc === false ) continue;

            list($regexp, $func) = $this->map[$tc];
            
            if ( preg_match($regexp, $line, $params) ) {
                array_shift($params);
                if ( !call_user_func_array(array($this, $func), $params) ) {
                    break;
                }
            } else {
                $this->error(_("Wrong post format"));
            }
        }

        $this->parsingComplete();

        return empty($this->errors);
    }


    /**
     * Parsing complete. Owerride to perform post parsing actions.
     * @return void
     */
    protected function parsingComplete(){}


    /**
     * Validate structure of parsed file
     * @return bool TRUE if file is valid, FALSE otherwise
     */
    public function valid(){
        $fstruct = "";

        if ( !is_array($this->raw) ) {
            $this->error("No file contents");
            return false;
        }

        foreach ( $this->raw as $line ) {
            $tc = $this->getTc($line);
            if ( !$tc ) continue;
            $fstruct .= $tc;
        }
        
        if ( preg_match($this->struct, $fstruct) ) {
            return true;
        } else {
            $this->error("Structural error, not a vaild file structure");
            return false;
        }
    }



    /* HELPERS */


    /**
     * Get transaction code from $line. Returns false if $line contains
     * no transaction code, or if code is not known.
     * @param string $line
     * @return mixed
     */
    private function getTc($line){
        $tc = substr($line, 0, $this->tcLength);
        if ( preg_match('/^\s*$/', $tc) ) return false;
        if ( !array_key_exists($tc, $this->map) ) return false;
        return $tc;
    }



    /* STACK */


    /**
     * Push an item to the internal stack
     * @param mixed $item
     * @return int The number of elements in the stack
     */
    protected function push($item){
        return array_push($this->stack, $item);
    }


    /**
     * Pop an item from the internal stack
     * @return mixed
     */
    protected function pop(){
        return array_pop($this->stack);
    }


    /**
     * Push item to 'substack' pop()[$key]
     * @param string|int $key
     * @param mixed item
     * @return TRUE on success, FALSE if pop() does not return an array
     */
    protected function pushTo($key, $item){
        $parent = $this->pop();
        if ( !is_array($parent) ) {
            $this->push($parent);
            return false;
        }
        if ( array_key_exists($key, $parent) ) {
            array_push($parent[$key], $item);
        } else {
            $parent[$key] = array($item);
        }
        $this->push($parent);
        return true;
    }


    /**
     * pop item from 'substack' pop()[$key]
     * @param scalar $key
     */
    protected function popFrom($key){
        $parent = $this->pop();
        if ( !is_array($parent) ) {
            $this->push($parent);
            return false;
        }
        $item = array_pop($parent[$key]);
        $this->push($parent);
        return $item;
    }


    /**
     * Get the entire stack
     * @return array
     */
    public function getStack(){
        return $this->stack;
    }


    /**
     * Clear the stack
     * @return void
     */
    public function clearStack(){
        $this->stack = array();
    }


    /**
     * Get a part of the stack by applying a filter
     * @param mixed $key Stack item must be arrays and contain $key to match filter
     * @param mixed $val Item[$key] must equal $val to match filter
     * @return array
     */
    public function filterStack($key, $val=null){
        $stack = $this->getStack();
        $subStack = array();    
        foreach ( $stack as $item ) {
            if ( !is_array($item) ) continue;
            if ( !array_key_exists($key, $item) ) continue;
            if ( $val!==null && $item[$key] != $val ) continue;
            $subStack[] = $item;
        }
        return $subStack;
    }


    /**
     * Count items in stack. If $key is specified only items containing $key
     * are counted. If $val is specified only intems containing $key == $val
     * are coutned.
     * @param scalar $key
     * @param mixed $val
     * @return int
     */
    public function count($key=false, $val=null){
        if ( $key ) {
            $s = $this->filterStack($key, $val);
        } else {
            $s = $this->getStack();
        }
        return count($s);
    }


    /**
     * Sum items in stack. Usage: sum('amount', array('tc', '32'))
     * @param string $field Name of field in stack item (must be array)
     * that should be summed.
     * @param array $where A key-value-pair, only those items who have key
     * and where item[key] == value will be summed.
     * @return int
     */
    public function sum($field, $where=false){
        if ( $where ) {
            $stack = $this->filterStack($where[0], $where[1]);
        } else {
            $stack = $this->getStack();
        }
        $sum = 0;
        foreach ( $stack as $item ) {
            if ( !array_key_exists($field, $item) ) continue;
            if ( is_numeric($item[$field]) ) {
                $sum += $item[$field];
            }
        }
        return $sum;
    }



    /* VALUES */


    /**
     * Set key-value pair. Will only set value if key is not definied.
     * Returns TRUE is current value for key is $val. FALSE otherwise.
     * @param string $key
     * @param string $val
     * @param bool $std If set to TRUE $val will be set as standard value for $key
     * @return bool
     */
    protected function setValue($key, $val, $std=false){
        if ( !array_key_exists($key, $this->values) ) {
            $this->values[$key] = $val;
        }
        if ( $this->values[$key] == $val ) {
            if ( $std ) $this->stdValues[$key] = $val;
            return true;
        } else {
            return false;
        }
    }


    /**
     * Get value from $key
     * @param string $key
     * @return mixed
     */
    protected function getValue($key){
        if ( array_key_exists($key, $this->values) ) {
            return $this->values[$key];
        } else {
            return false;
        }
    }


    /**
     * Reset values to stdValues
     * @return void
     */
    protected function clearValues(){
        $this->values = $this->stdValues;
    }



    /* SECTIONS */


    /**
     * Create a section object and store it. Clear internal representation
     * @param string $layout
     * @return void
     */
    protected function writeSection($layout=false){
        if ( !$layout ) $layout = $this->layout;
        
        $s = array(
            'layout' => $layout,
        );

        if ( array_key_exists($layout, $this->layoutNames) ) {
            $s['layoutName'] = $this->layoutNames[$layout];
        }

        $s = array_merge($s, $this->values);
        
        $s['errors'] = $this->getErrors();
        $s['posts'] = $this->getStack();

        //save section
        array_push($this->sections, $s);

        //clear for next section
        $this->sectionClear();
    }


    /**
     * Get next parsed section. If there is no parsed section null is returned.
     * @return array
     */
    public function getSection(){
        $section = array_shift($this->sections);
        if ( $section === null ) {
            $stack = $this->getStack();
            if ( !empty($stack) ) {
                $this->clearStack();
                return $stack;
            }
        }
        return $section;
    }


    /**
     * Clear internal representation for new section
     * @return void
     */
    public function sectionClear(){
        $this->clearStack();
        $this->clearValues();
    }



    /* ERRORS */


    /**
     * Do something when a parsing function triggers an error.
     * @param string $msg The error message
     * @param string $source
     * @return void
     */
    protected function error($msg, $source=false){
        if ( !$source ) $source = $this->fname;
        $line = "";
        if ( is_array($this->raw) ) $line = key($this->raw);
        $msg = sprintf("%s <%s:%d>", $msg, $source, $line);
        if ( !in_array($msg, $this->errors) ) {
            $this->errors[] = $msg;
        }
    }


    /**
     * Get the array with parsed errors, FALSE if no error occured.
     * @return array
     */
    public function getErrors(){
        return empty($this->errors) ? false : $this->errors;
    }


    /**
     * Returns TRUE if an error has occured, FALSE otherwise
     * @return bool
     */
    public function hasError(){
        return !empty($this->errors);
    }


    /* AMOUNTS */


    /**
     * Takes a numerical string and converse it to a float. The last two
     * positions in string are treated as cents.
     *
     * If $amount is an empty string (whitespaces are considered empty)
     * the empty string is returned.
     *
     * If $allowSignal==true and $amount ends in a letter it will be
     * transformed to a negative amount accourding to this table:
     * 
     * <code>
     * å: letter is transformed to 0
     * J: 1
     * K: 2
     * L: 3
     * M: 4
     * N: 5
     * O: 6
     * P: 7
     * Q: 8
     * R: 9
     * </code>
     *
     * @param string $amount
     * @param bool $allowSignal
     * @return float|string
     */
    protected function str2amount($amount, $allowSignal=true){
        $sign = 1;
        if ( is_string($amount) ) {
            $amount = trim($amount);
            if ( empty($amount) ) return "";
        }
        if ( !is_numeric($amount) ) {

            if ( $allowSignal && preg_match("/^\d+å|[JKLMNOPQR]$/", $amount) ) {
                //Transform to negative amount
                $sign = -1;
                $ptrns = array(
                    "/^(\d+)å$/",
                    "/^(\d+)J$/",
                    "/^(\d+)K$/",
                    "/^(\d+)L$/",
                    "/^(\d+)M$/",
                    "/^(\d+)N$/",
                    "/^(\d+)O$/",
                    "/^(\d+)P$/",
                    "/^(\d+)Q$/",
                    "/^(\d+)R$/",
                );
                $replace = array(
                    '${1}0',
                    '${1}1',
                    '${1}2',
                    '${1}3',
                    '${1}4',
                    '${1}5',
                    '${1}6',
                    '${1}7',
                    '${1}8',
                    '${1}9',
                );
                $amount =  preg_replace($ptrns, $replace, $amount);

            } else {
                $this->error(_('Unvalid amount: not numerical.'));
                return false;
            }
        }
        $amount = ltrim($amount, '0');
        if ( strlen($amount) > strlen((string)PHP_INT_MAX)+1 ) {
            $this->error(_('Unable to parse amount to float, to long.'));
            return false;
        }
        $amount = preg_replace("/(\d*)(\d\d)$/", "$1.$2", $amount, 1);
        return floatval($amount)*$sign;
    }


    
    /**
     * Takes an amount as integer, float or string. Returns a string with
     * the last two positions denoting cents (00 if cents were not specified
     * in integer or string) and no punctuation.
     * @param int|float|string $amount
     * @param bool $signal If TRUE negative values will be represented as
     * siganal values (see str2amount() for description).
     * @return string
     */
    protected function amount2str($amount, $signal=false){
        if ( !is_numeric($amount) ) {
            $this->error(_('Unvalid amount: not numerical.'));
            return false;
        }
        
        if ( $amount < 0 && $signal ) {
            $amount = $amount * -1;
        } else {
            $signal = false;
        }

        $amount = (string)$amount;
        list($unit, $cent) = explode('.', $amount);
        $cent = str_pad($cent, 2, '0');
        if ( strlen($cent) > 2 ) {
            $this->error(_('Unvalid amount: cent section to long.'));
            return false;
        }
        
        if ( $signal ) {
            $ptrns = array(
                "/^(\d)0$/",
                "/^(\d)1$/",
                "/^(\d)2$/",
                "/^(\d)3$/",
                "/^(\d)4$/",
                "/^(\d)5$/",
                "/^(\d)6$/",
                "/^(\d)7$/",
                "/^(\d)8$/",
                "/^(\d)9$/",
            );
            $replace = array(
                '${1}å',
                '${1}J',
                '${1}K',
                '${1}L',
                '${1}M',
                '${1}N',
                '${1}O',
                '${1}P',
                '${1}Q',
                '${1}R',
            );
            $cent = preg_replace($ptrns, $replace, $cent);
        }
        
        $amount = "$unit$cent";        
        return $amount;
    }

}
