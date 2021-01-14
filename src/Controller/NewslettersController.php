<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\Mailer\Email;


class NewslettersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add']);
        $user = $this->Auth->user();
        if($user){
            $user['confirmed_at'] = new FrozenTime($user['confirmed_at']);
            $user['reset_at'] = new FrozenTime($user['reset_at']);
            $usersTable = TableRegistry::get('Users');
            $user = $usersTable->newEntity($user);
            $this->set('user', $user);
        }
    }

    public function add(){
        if ($this->request->is('post')) {
            $newslettersTable = TableRegistry::get('Newsletters');
            $exist_email = $newslettersTable->find()
                ->where(
                    [
                        'email' => $this->request->getData()['email'],
                    ]
                )
                ->limit(1)
                ->all();
            if(!$exist_email->isEmpty()){
                $this->Flash->error('Cette email existe déjà.');
                $this->redirect(['controller' => 'Contact', 'action' => 'index']);
            }else{
              $email = $newslettersTable->newEntity($this->request->getData());

              if($newslettersTable->save($email)){
                $mail = new Email();
                $mail->setFrom('support@agappli.com')
                    ->setTo($email->email)
                    ->setSubject('La Newsletter AGAPPLI')
                    ->setEmailFormat('html')
                    ->setTemplate('Newsletter')
                    ->send();
                  $this->Flash->success('Bienvennue Dans La Newsletter D\'AGAPPLI !');
                  $this->redirect(['controller' => 'Contact','action' => 'index']);
              }else{
                  $this->Flash->error('Désolé vous n\'avez pas pus être enregistrer dans la newsletter !');
                  $this->redirect(['controller' => 'Contact','action' => 'index']);
              }
            }
        }
    }

}
