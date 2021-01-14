<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

class SearchForm extends Form
{

    public function setErrors(array $errors)
    {
        $this->_errors = $errors;
    }
}
