<?php
namespace App;

class Validator
{
    /**
     * @var array
     */
    public $errors = [];
    /**
     * @var array
     */
    public $data = [];
    /**
     * @var string
     */
    public $message = '';
    /**
     * @var string
     */
    public $type = "";

    public function __construct($data)
    {
        $this->format($data);
        $this->sanitize();
        $this->validate();
    }

    /**
     * @param $key
     * @param $val
     */
    public function appendErrors($key, $val)
    {
        $this->errors[$key] = $val;
    }

    /**
     * @param $data
     */
    public function format($data)
    {
        $this->data = json_decode($data, true);
        $this->type = $this->data['type'];
    }

    /**
     * @return void
     */
    public function sanitize()
    {
        foreach ((array) $this->data as $key => $val) {
            $trim = trim($val);
            $strip = stripslashes($trim);
            $clean = htmlspecialchars($strip);
            $this->data[$key] = $clean;

        }
    }

    /**
     * @param $key
     * @param $val
     */
    public function isEmpty($key, $val)
    {
        if ($val == "") {
            $this->errors[$key] = $key . ' field is required';
        }
    }

    /**
     * @return bool
     */
    public function validate()
    {
        foreach ((array) $this->data as $key => $val) {
            $this->isEmpty($key, $val);
        }
        if (empty($this->errors)) {
            $this->message();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    public function message()
    {
        if ($this->type == 'login') {
            $this->message = 'You are logged in';
        }
        if ($this->type == 'register') {
            $this->message = 'You are registered';
        }
        if ($this->type == 'recovery') {
            $this->message = 'Please check your email';
        }
    }

}
