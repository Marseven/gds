<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

class ContactForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('name', 'string')
            ->addField('email', ['type' => 'string'])
            ->addField('phone', ['type' => 'string'])
            ->addField('body', ['type' => 'text']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator->add('name', 'length', [
            'rule' => ['minLength', 2],
            'message' => 'Un nom est requis'
        ])->add('email', 'format', [
            'rule' => 'email',
            'message' => 'Une adresse email valide est requise',
        ])->add('subject', 'format', [
            'rule' => ['minLength', 2],
            'message' => 'Un sujet est requis',
        ])->add('body', 'format', [
            'rule' => ['minLength', 2],
            'message' => 'Un contenu est requis',
        ]);
    }

    public function setErrors(array $errors)
    {
        $this->_errors = $errors;
    }

    protected function _execute(array $data)
    {
        // Envoie un email.
        $mail = new Email();
        $mail->setFrom($data['email'])
            ->setTo('richard.mebodo@jobs-coneil.com')
            ->setSubject('Contact | Jobs Conseil')
            ->setEmailFormat('html')
            ->setTemplate('contact')
            ->setViewVars([
                'auteur' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'message' => $data['body'],
            ])
            ->send();
        return true;
    }
}
