<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

class UsersTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('users');
        
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('nom')
            ->notEmpty('nom', 'Ce champ doit être rempli.')
            ->requirePresence('email')
            ->add('email', [
                'length' => [
                    'rule' => 'email',
                    'message' => 'Ex : abc@xyz.cfr',
                ]
            ])
            ->requirePresence('telephone')
            ->notEmpty('telephone', 'Ce champ doit être rempli.')
            ->requirePresence('password')
            ->notEmpty('password', 'Ce champ doit être rempli.')
            ->requirePresence('password_verify')
            ->notEmpty('password_verify', 'Ce champ doit être rempli.');

        return $validator;
    }

}
