<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;


class ConfigsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $user = $this->Auth->user();
        if($user){
            $user['confirmed_at'] = new FrozenTime($user['confirmed_at']);
            $user['reset_at'] = new FrozenTime($user['reset_at']);
            $usersTable = TableRegistry::get('Users');
            $user = $usersTable->newEntity($user);
            $this->set('user', $user);
        }
    }

    public function index(){

        $user = $this->Auth->user();
        $configsTable = TableRegistry::get('Configs');
        $config = $configsTable->find()->where(['id_user =' => $user['id']])->first();

        if($this->request->is('post'))
        {
            $_config = $configsTable->newEntity($this->request->getData());
            if ($configsTable->save($_config)) {
                $this->Flash->set('Les réglages ont été mis à jour avec succès.', ['element' => 'success']);
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->set('Certains champs ont été mal saisis', ['element' => 'error']);
            }
        }

        if($config){
            $this->set('config', $config);
        }else{
            $config = $configsTable->newEntity();
            $this->set('config', $config);
        }

        $this->render('index', 'standard');
    }

}
