<?php
/**
 * 02/22, 2015
 * Rathes Sachchithananthan
 *
 * class Validator
 * fks Validator in the style of Laravel Validation
 * but kept much more simpler than the original
 */

namespace fokuscms\Components\Validation;


use DateTime;

class Validator {

    private $rules;
    private $fields;
    private $errors;

    public function __construct($fields, $rules){
        $this->fields = $fields;
        $this->rules = $rules;
        $this->errors = array();
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * static make()
     * creates an instance of the validator class with the
     * given data
     * @param $fields
     * @param $rules
     * @return Validator
     */
    public static function make($fields, $rules){
        $validator = new Validator($fields, $rules);
        return $validator;
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * passes()
     * returns the result of validation from perspective of
     * passing
     */
    public function passes(){
        $result = $this->validate();
        return $result;
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * fails()
     * returns the result of validation from perspective of
     * failing
     */
    public function fails(){
        $result = $this->validate();
        return !$result;
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private validate()
     * validates the fields against the rules and
     * returns true if validation passes and false
     * if validation fails
     */
    private function validate(){

        // go through every rule and check
        // if the fields fulfill every rule
        foreach ($this->rules as $key => $rule){
            // first check if field with $key
            // as key exists
            if (!isset($this->fields[$key])){
                $this->fields[$key] = '';
            }
            // array of every rule of $field
            $rulesForField = explode('|', $rule);
            foreach($rulesForField as $ruleForField){
                $ruleForField = explode(':', $ruleForField);
                if(isset($ruleForField[1])){
                    $result = $this->checkRule($this->fields[$key], $ruleForField[0], $ruleForField[1]);
                } else {
                    $result = $this->checkRule($this->fields[$key], $ruleForField[0]);
                }
                if(!$result){
                    $this->errors[$key][] = $ruleForField;
                }
            }
        }

        // if $error-array is still empty return true
        // else return false
        if(empty($this->errors)){
            return true;
        } else {
            return false;
        }

    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * getErrors()
     * returns the array containing the errors
     *
     * @return mixed
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private checkRule()
     * checks the field against the rule and
     * adds the violation to $errors
     */
    private function checkRule($field, $rule, $data = null){
        switch ($rule){
            case 'alpha':
                return $this->valAlpha($field);
                break;
            case 'alpha_dash':
                return $this->valAlphaDash($field);
                break;
            case 'alpha_num':
                return $this->valAlphaNum($field);
                break;
            case 'date':
                return $this->valDate($field);
                break;
            case 'integer':
                return $this->valInteger($field);
                break;
            case 'max':
                return $this->valMax($field, $data);
                break;
            case 'min':
                return $this->valMin($field, $data);
                break;
            case 'required':
                return $this->valRequired($field);
            case 'url':
                return $this->valUrl($field);
                break;
            default:
                return true;
                break;
        }
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private valRequired()
     * validates that the field exists
     *
     * @param mixed $field
     * @return bool
     */
    private function valRequired($field){
        if (is_null($field)){
            return false;
        }
        elseif (is_string($field) && trim($field) === ''){
            return false;
        }
        elseif (is_array($field) && count($field) < 1){
            return false;
        }
        return true;
    }


    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private valAlpha()
     * validate that a field contains
     * only alphabetic characters.
     *
     * @param $field
     * @return bool
     */
    private function valAlpha($field){
        return preg_match('/^[\pL\pM]+$/u', $field);
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private valAlphaNum()
     * validate that a field contains
     * only alpha-numeric characters.
     *
     * @param $field
     * @return bool
     */
    private function valAlphaNum($field){
        return preg_match('/^[\pL\pM\pN]+$/u', $field);
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private valAlphaDash()
     * validate that a field contains
     * only alpha-numeric characters, dashes
     * and underscores.
     *
     * @param $field
     * @return bool
     */
    private function valAlphaDash($field){
        return preg_match('/^[\pL\pM\pN\s_-]+$/u', $field);
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private valDate()
     * validate that a field is a date
     *
     * @param $field
     * @return bool
     */
    private function valDate($field){
        if ($field instanceof DateTime) return true;
        if (strtotime($field) === false) return false;
        $date = date_parse($field);
        return checkdate($date['month'], $date['day'], $date['year']);

    }

    /**
     * 02/28, 2015
     * Rathes Sachchithananthan
     *
     * private valMax()
     * validate that a field has $max length
     *
     * @param $field
     * @param $max
     * @return bool
     */
    private function valMax($field, $max){
        if(is_array($field) && sizeof($field) <= $max) return true;
        if(is_string($field) && strlen($field) <= $max) return true;
        if (is_numeric($field) && $field <= $max) return true;
        return false;
    }

    /**
     * 02/28, 2015
     * Rathes Sachchithananthan
     *
     * private valMin()
     * validate that a field has $min length
     *
     * @param $field
     * @param $min
     * @return bool
     */
    private function valMin($field, $min){
        if(is_array($field) && sizeof($field) >= $min) return true;
        if(is_string($field) && strlen($field) >= $min) return true;
        if (is_numeric($field) && $field >= $min) return true;
        return false;
    }

    /**
     * 02/22, 2015
     * Rathes Sachchithananthan
     *
     * private valUrl()
     * validate that a field is a URL
     *
     * @param $field
     * @return bool
     */
    private function valUrl($field){
        return filter_var($field, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * 08/06, 2015
     * Rathes Sachchithananthan
     *
     * private valInteger
     * validate that a field is an interger
     *
     * @param $field
     * @return bool
     */
    private function valInteger($field){
        return filter_var($field, FILTER_VALIDATE_INT) !== false;
    }


} 