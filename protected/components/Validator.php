<?php
class Validator {

    public $msg;

    public function required($field=array(), $data='') {
        if (is_array($field) && count($field) >= 1) {
            foreach ($field as $key => $value) {
                if (empty($data[$key])) {
                    $this->msg[] = $value;
                }
            }
        } else
            $this->msg[] = "fields must be an array";
    }

    public function numeric($field=array(), $data='') {
        if (is_array($field) && count($field) >= 1) {
            foreach ($field as $key => $value) {
                if (!empty($data[$key])) {
                    if (!is_numeric($data[$key])) {
                        $this->msg[] = $value;
                    }
                }
            }
        } else
            $this->msg[] = "fields must be an array";
    }

    public function email($field=array(), $data='') {
        if (is_array($field) && count($field) >= 1) {
            foreach ($field as $key => $value) {
                if (!empty($data[$key])) {
                    if (!$this->checkEmail($data[$key])) {
                        $this->msg[] = $value;
                    }
                }
            }
        } else
            $this->msg[] = "fields must be an array";
    }

    public function checkMobile($field=array(), $data='') {
        if (is_array($field) && count($field) >= 1) {
            foreach ($field as $key => $value) {
                if (!empty($data[$key])) {
                    if (strlen($data[$key]) <= 9) {
                        $this->msg[] = "$data[$key] is not a valid Mobile Number";
                    }
                }
            }
        } else
            $this->msg[] = "fields must be an array";
    }

    private function checkEmail($email) {
    	$version = phpversion();
        if($version>=7){
            if(!filter_var($email,FILTER_VALIDATE_EMAIL) === false){
                return true;
            } else
                return false;
        } else {
            if (@eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
                return true;
            } else
                return false;            
        }        
    }

    public function validate() {
        if (!is_array($this->msg) && count((array)$this->msg) <= 1) {
            return true;
        } else
            return false;
    }

    public function getError() {
        return $this->msg;
    }

    public function getErrorAsHTML() {
        $content = '';

        $content.="<ul id=\"cvalidator\">";
        if (is_array($this->msg) && count($this->msg) >= 1) {
            foreach ($this->msg as $value) {
                $content.="<li>$value</li>";
            }
        }
        $content.="</ul>";
        return $content;
    }

}