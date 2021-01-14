<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\Mailer\Email;

class DemandeForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('nom', 'string')
            ->addField('email', ['type' => 'string'])
            ->addField('telephone', ['type' => 'string'])
            ->addField('contenu', ['type' => 'text'])
            ->addField('objet', ['type' => 'text'])
            ->addField('service', ['type' => 'text']);
    }

    protected function _buildValidator(Validator $validator)
    {

    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->_errors = $errors;
    }

    protected function _execute(array $data)
    {
        // Envoie un email.
        $mail = new Email();
        $mail->setFrom($data['email'])
            ->setTo('richard.mebodo@jobs-conseil.com')
            ->setSubject('Demande de service | Jobs conseil')
            ->setEmailFormat('html')
            ->setTemplate('demande')
            ->setViewVars(['contenu' => $data])
            ->send();
        return true;
    }
}
