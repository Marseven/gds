<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class DeclarationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        ini_set('memory_limit', '2G');
        $this->Auth->allow(['index', 'faq', 'about']);
        $user = $this->Auth->user();
        if($user){
            $user['confirmed_at'] = new FrozenTime($user['confirmed_at']);
            $user['reset_at'] = new FrozenTime($user['reset_at']);
            $usersTable = TableRegistry::get('Users');
            $user = $usersTable->newEntity($user);
            $this->set('user', $user);
        }

        $this->loadComponent('PhpExcel');
    }

    // Acceuil
    public function index()
    {
        if(!empty($this->request->params['?']['clean']))
        {
            $this->viderBd();
            $this->Flash->success('Base de données nettoyée avec succès !');
            $this->redirect(['action' => 'index']);
        }
    }

    // importation du 1er mois
    public function import1()
    {

        if($this->request->is('post'))
        {
            $data1Table = TableRegistry::get('data1_imports');
            $data1 = $data1Table->find()->all();
            if(!$data1->first())
            {
                if($this->request->getData()["file"]["size"] > 0)
                {
                    if($this->typeLivre($this->request->getData()["file"]["name"])){
                        $filename =  ROOT . DS . 'webroot' . DS . 'files' . DS . 'tmp_livre' . DS .$this->request->getData()["file"]["name"];
                        if (move_uploaded_file($this->request->getData()["file"]["tmp_name"] , $filename)){
                            $objet = $this->PhpExcel->openExcel(ROOT . DS . 'webroot' . DS . 'files' . DS . 'tmp_livre' . DS .$this->request->getData()["file"]["name"]);
                            $sheet = $objet->getActiveSheet();
                            $nbre = 2;
                            $i = 1;

                            while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() !== null)
                            {

                                if($objet->getActiveSheet()->getCell('A1')->getValue() == '="Matricule"'){
                                    $nom = utf8_encode($this->cleanChart($objet->getActiveSheet()->getCell('B'.$nbre)->getValue()));
                                    $prenom = utf8_encode($this->cleanChart($objet->getActiveSheet()->getCell('C'.$nbre)->getValue()));
                                    $matricule = $this->cleanChart($objet->getActiveSheet()->getCell('A'.$nbre)->getValue());
                                    $brut = 0;
                                    $nbrejour = 0;
                                    do {
                                        if($matricule ==  $this->cleanChart($objet->getActiveSheet()->getCell('A'.$nbre)->getValue()))
                                        {
                                            if($this->cleanChart($objet->getActiveSheet()->getCell('D'.$nbre)->getValue()) == "BRUT"){
                                                $brut = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                            if($this->cleanChart($objet->getActiveSheet()->getCell('D'.$nbre)->getValue()) == "TOTALHTRAV"){
                                                $nbrejour = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                        }

                                        $nbre++;
                                        $nbre2 = $nbre+1;

                                    } while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() == $objet->getActiveSheet()->getCell('A'.$nbre2)->getValue());
                                }else{
                                    $nom = utf8_encode($objet->getActiveSheet()->getCell('B'.$nbre)->getValue());
                                    $prenom = utf8_encode($objet->getActiveSheet()->getCell('C'.$nbre)->getValue());
                                    $matricule = $objet->getActiveSheet()->getCell('A'.$nbre)->getValue();
                                    $brut = 0;
                                    $nbrejour = 0;
                                    do {
                                        if($matricule ==  $objet->getActiveSheet()->getCell('A'.$nbre)->getValue())
                                        {
                                            if($objet->getActiveSheet()->getCell('D'.$nbre)->getValue() == "BRUT"){
                                                $brut = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                            if($objet->getActiveSheet()->getCell('D'.$nbre)->getValue() == "TOTALHTRAV"){
                                                $nbrejour = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                        }

                                        $nbre++;
                                        $nbre2 = $nbre+1;

                                    } while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() == $objet->getActiveSheet()->getCell('A'.$nbre2)->getValue());

                                }

                                $data = $data1Table->newEntity();
                                $data->Import_1_1 = $matricule;
                                $data->Import_1_2 = $nom;
                                $data->Import_1_3 = $prenom;
                                $data->Import_1_5 = $nbrejour;
                                $data->Import_1_6 = $brut;
                                $data1Table->save($data);
                                $nbre = $nbre2;
                                $brut = 0;
                                $nbrejour = 0;
                                $nom = '';
                                $prenom = '';
                                $matricule = '';

                            }
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            unlink($filename);
                            $this->redirect(['action' => 'import2']);
                        }else{
                            $this->Flash->error('Un problème est survenu : Veuillez recharger le fichier !');
                        }
                    }else{
                        $filename=$this->request->getData()["file"]["tmp_name"];
                        $file = fopen($filename, "r");
                        $count = 0;
                        $brut = 0;
                        $nbrejour = 0;
                        $nom = '';
                        $prenom = '';
                        $matricule = '';
                        $typeof = $this->typefile($file);
                        if($typeof === true){
                            while (($emapData = fgetcsv($file, 10000, ";")) !== FALSE)
                            {

                                $count++;    // add this line

                                if($count>1)
                                {
                                    if(!isset($emapData[5])){
                                        $this->Flash->error("Le Livre de paie n'est pas valide, veuillez vérifier que toutes colones sont présentes !");
                                        $this->redirect(['action' => 'import1']);
                                        break;
                                    }
                                    // add this line
                                    $data = $data1Table->newEntity();
                                    $data->Import_1_1 = $emapData[0];
                                    $data->Import_1_2 = utf8_encode($emapData[1]);
                                    $data->Import_1_3 = utf8_encode($emapData[2]);
                                    $data->Import_1_4 = $emapData[3];
                                    $data->Import_1_5 = $emapData[4];
                                    $data->Import_1_6 = $emapData[5];
                                    $data->Import_1_7 = $emapData[6];
                                    $data->Import_1_8 = $emapData[7];
                                    $data->Import_1_9 = $emapData[8];
                                    $data->Import_1_10 = $emapData[9];
                                    $data->Import_1_11 = $emapData[10];
                                    $data->Import_1_12 = $emapData[11];
                                    $data->Import_1_13 = $emapData[12];
                                    $data->Import_1_14 = $emapData[13];
                                    $data->Import_1_15 = $emapData[14];
                                    $data->Import_1_16 = $emapData[15];
                                    $data->Import_1_17 = $emapData[16];
                                    $data->Import_1_18 = $emapData[17];
                                    $data->Import_1_19 = $emapData[18];
                                    $data->Import_1_20 = $emapData[19];
                                    $data1Table->save($data);
                                }
                            }
                            fclose($file);
                            // echo 'CSV File has been successfully Imported';
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            $this->redirect(['action' => 'import2']);
                        }else{

                            if($typeof === "Sage"){
                                $sage = true;
                            }else{
                                $sage = false;
                            }
                            while (($emapData = fgetcsv($file, 10000, ";")) !== FALSE)
                            {

                                $count++;    // add this line

                                if($count==2)
                                {
                                   if($sage){$matricule = $this->cleanChart($emapData[0]);}else{$matricule = $emapData[0];}
                                }
                                if($count>1)
                                {

                                    if($matricule ==  $emapData[0] && !$sage)
                                    {
                                        if($emapData[3] == "BRUT"){
                                            $brut = $emapData[5];
                                        }elseif($emapData[3] == "TOTALHTRAV"){
                                            $nbrejour = $emapData[5];
                                            $nom = utf8_encode($emapData[1]);
                                            $prenom = utf8_encode($emapData[2]);
                                        }

                                    }elseif($matricule ==  $this->cleanChart($emapData[0]) && $sage){
                                        if($this->cleanChart($emapData[3]) == "BRUT"){
                                            $brut = $emapData[5];
                                            settype($brut, "integer");
                                        }elseif($this->cleanChart($emapData[3]) == "TOTALHTRAV"){
                                            $nbrejour = $emapData[5];
                                            $nom = utf8_encode($this->cleanChart($emapData[1]));
                                            $prenom = utf8_encode($this->cleanChart($emapData[2]));
                                        }
                                    }else{
                                        // add this line
                                        $data = $data1Table->newEntity();
                                        $data->Import_1_1 = $matricule;
                                        $data->Import_1_2 = $nom;
                                        $data->Import_1_3 = $prenom;
                                        $data->Import_1_5 = $nbrejour;
                                        $data->Import_1_6 = $brut;
                                        $data1Table->save($data);
                                        $brut = 0;
                                        $nbrejour = 0;
                                        $nom = '';
                                        $prenom = '';
                                        if($sage){$matricule = $this->cleanChart($emapData[0]);}else{$matricule = $emapData[0];}
                                    }
                                }
                            }
                            fclose($file);
                            // echo 'CSV File has been successfully Imported';
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            $this->redirect(['action' => 'import2']);
                        }
                    }
                }else{
                    $this->Flash->error('Le fichier est invalide : Veuillez charger un fichier valide !');
                }

            }else{
                $this->Flash->error('Vous avez déjà importé le premier livre de paie !');
                $this->redirect(['action' => 'import2']);
            }
        }
    }

    // importation du 2eme mois
    public function import2()
    {

        $data1Table = TableRegistry::get('data1_imports');
        $data1 = $data1Table->find()->all();
        if(!$data1->first())
        {
            $this->Flash->error('Importez le livre de paie du premier trimestre !');
            $this->redirect(['action' => 'import1']);
        }

        if($this->request->is('post'))
        {
            $data2Table = TableRegistry::get('data2_imports');
            $data2 = $data2Table->find()->all();
            if(!$data2->first())
            {
                if($this->request->getData()["file"]["size"] > 0)
                {
                    if($this->typeLivre($this->request->getData()["file"]["name"])){
                        $filename =  ROOT . DS . 'webroot' . DS . 'files' . DS . 'tmp_livre' . DS .$this->request->getData()["file"]["name"];
                        if (move_uploaded_file($this->request->getData()["file"]["tmp_name"] , $filename)){
                            $objet = $this->PhpExcel->openExcel(ROOT . DS . 'webroot' . DS . 'files' . DS . 'tmp_livre' . DS .$this->request->getData()["file"]["name"]);
                            $sheet = $objet->getActiveSheet();
                            $nbre = 2;
                            $i = 1;

                            while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() !== null)
                            {

                                if($objet->getActiveSheet()->getCell('A1')->getValue() == '="Matricule"'){
                                    $nom = utf8_encode($this->cleanChart($objet->getActiveSheet()->getCell('B'.$nbre)->getValue()));
                                    $prenom = utf8_encode($this->cleanChart($objet->getActiveSheet()->getCell('C'.$nbre)->getValue()));
                                    $matricule = $this->cleanChart($objet->getActiveSheet()->getCell('A'.$nbre)->getValue());
                                    $brut = 0;
                                    $nbrejour = 0;
                                    do {
                                        if($matricule ==  $this->cleanChart($objet->getActiveSheet()->getCell('A'.$nbre)->getValue()))
                                        {
                                            if($this->cleanChart($objet->getActiveSheet()->getCell('D'.$nbre)->getValue()) == "BRUT"){
                                                $brut = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                            if($this->cleanChart($objet->getActiveSheet()->getCell('D'.$nbre)->getValue()) == "TOTALHTRAV"){
                                                $nbrejour = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                        }

                                        $nbre++;
                                        $nbre2 = $nbre+1;

                                    } while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() == $objet->getActiveSheet()->getCell('A'.$nbre2)->getValue());

                                }else{
                                    $nom = utf8_encode($objet->getActiveSheet()->getCell('B'.$nbre)->getValue());
                                    $prenom = utf8_encode($objet->getActiveSheet()->getCell('C'.$nbre)->getValue());
                                    $matricule = $objet->getActiveSheet()->getCell('A'.$nbre)->getValue();
                                    $brut = 0;
                                    $nbrejour = 0;
                                    do {
                                        if($matricule ==  $objet->getActiveSheet()->getCell('A'.$nbre)->getValue())
                                        {
                                            if($objet->getActiveSheet()->getCell('D'.$nbre)->getValue() == "BRUT"){
                                                $brut = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                            if($objet->getActiveSheet()->getCell('D'.$nbre)->getValue() == "TOTALHTRAV"){
                                                $nbrejour = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                        }

                                        $nbre++;
                                        $nbre2 = $nbre+1;

                                    } while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() == $objet->getActiveSheet()->getCell('A'.$nbre2)->getValue());

                                }

                                $data = $data2Table->newEntity();
                                $data->Import_2_1 = $matricule;
                                $data->Import_2_2 = $nom;
                                $data->Import_2_3 = $prenom;
                                $data->Import_2_5 = $nbrejour;
                                $data->Import_2_6 = $brut;
                                $data2Table->save($data);
                                $nbre = $nbre2;
                                $brut = 0;
                                $nbrejour = 0;
                                $nom = '';
                                $prenom = '';
                                $matricule = '';

                            }
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            unlink($filename);
                            $this->redirect(['action' => 'import3']);
                        }else{
                            $this->Flash->error('Un problème est survenu : Veuillez recharger le fichier !');
                        }
                    }else{
                        $filename=$this->request->getData()["file"]["tmp_name"];
                        $file = fopen($filename, "r");
                        $count=0;
                        $brut = 0;
                        $nbrejour = 0;
                        $nom = '';
                        $prenom = '';
                        $matricule = '';
                        $typeof = $this->typefile($file);
                        if((($typeof === true) && ($data1->first()->Import_1_4 == NULL)) || (($typeof === true) && ($data1->first()->Import_1_8 == NULL)))
                        {
                            $this->Flash->error('Impoter le même type de livre de paie que 1er mois !');
                            return $this->redirect(['action' => 'import2']);
                        }
                        if($typeof === true){
                            while (($emapData = fgetcsv($file, 10000, ";")) !== FALSE)
                            {

                                $count++;    // add this line

                                if($count>1)
                                {
                                    if(!isset($emapData[5])){
                                        $this->Flash->error("Le Livre de paie n'est pas valide, veuillez vérifier que toutes colones sont présentes !");
                                        $this->redirect(['action' => 'import2']);
                                        break;
                                    }
                                    // add this line
                                    $data = $data2Table->newEntity();
                                    $data->Import_2_1 = $emapData[0];
                                    $data->Import_2_2 = utf8_encode($emapData[1]);
                                    $data->Import_2_3 = utf8_encode($emapData[2]);
                                    $data->Import_2_4 = $emapData[3];
                                    $data->Import_2_5 = $emapData[4];
                                    $data->Import_2_6 = $emapData[5];
                                    $data->Import_2_7 = $emapData[6];
                                    $data->Import_2_8 = $emapData[7];
                                    $data->Import_2_9 = $emapData[8];
                                    $data->Import_2_10 = $emapData[9];
                                    $data->Import_2_11 = $emapData[10];
                                    $data->Import_2_12 = $emapData[11];
                                    $data->Import_2_13 = $emapData[12];
                                    $data->Import_2_14 = $emapData[13];
                                    $data->Import_2_15 = $emapData[14];
                                    $data->Import_2_16 = $emapData[15];
                                    $data->Import_2_17 = $emapData[16];
                                    $data->Import_2_18 = $emapData[17];
                                    $data->Import_2_19 = $emapData[18];
                                    $data->Import_2_20 = $emapData[19];
                                    $data2Table->save($data);
                                }
                            }
                            fclose($file);
                            // echo 'CSV File has been successfully Imported';
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            $this->redirect(['action' => 'import3']);
                        }else{
                            $sage = false;
                            while (($emapData = fgetcsv($file, 10000, ";")) !== FALSE)
                            {

                                $count++;    // add this line

                                if($typeof === "Sage"){
                                    $sage = true;
                                }

                                if($count==2)
                                {
                                   if($sage){$matricule = $this->cleanChart($emapData[0]);}else{$matricule = $emapData[0];}
                                }
                                if($count>1)
                                {
                                    if($matricule ==  $emapData[0] && !$sage)
                                    {
                                        if($emapData[3] == "BRUT"){
                                            $brut = $emapData[5];
                                        }elseif($emapData[3] == "TOTALHTRAV"){
                                            $nbrejour = $emapData[5];
                                            $nom = utf8_encode($emapData[1]);
                                            $prenom = utf8_encode($emapData[2]);
                                        }

                                    }elseif($matricule ==  $this->cleanChart($emapData[0]) && $sage){
                                        if($this->cleanChart($emapData[3]) == "BRUT"){
                                            $brut = $emapData[5];
                                            settype($brut, "integer");
                                        }elseif($this->cleanChart($emapData[3]) == "TOTALHTRAV"){
                                            $nbrejour = $emapData[5];
                                            $nom = utf8_encode($this->cleanChart($emapData[1]));
                                            $prenom = utf8_encode($this->cleanChart($emapData[2]));
                                        }
                                    }else{
                                        // add this line
                                        $data = $data2Table->newEntity();
                                        $data->Import_2_1 = $matricule;
                                        $data->Import_2_2 = $nom;
                                        $data->Import_2_3 = $prenom;
                                        $data->Import_2_5 = $nbrejour;
                                        $data->Import_2_6 = $brut;
                                        $data2Table->save($data);
                                        $brut = 0;
                                        $nbrejour = 0;
                                        $nom = '';
                                        $prenom = '';
                                        if($sage){$matricule = $this->cleanChart($emapData[0]);}else{$matricule = $emapData[0];}
                                    }
                                }
                            }
                            fclose($file);
                            // echo 'CSV File has been successfully Imported';
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            $this->redirect(['action' => 'import3']);
                        }
                    }
                }else{
                    $this->Flash->error('Le fichier est invalide : Veuillez charger un fichier valide !');
                }
            }else{
                $this->Flash->error('Vous avez déjà importé le deuxième livre de paie !');
                $this->redirect(['action' => 'import3']);
            }
        }
    }

    // importation du 3eme mois
    public function import3()
    {
        $data2Table = TableRegistry::get('data2_imports');
        $data2 = $data2Table->find()->all();
        $data1Table = TableRegistry::get('data1_imports');
        $data1 = $data1Table->find()->all();
        if(!$data1->first())
        {
            $this->Flash->error('Importez le livre de paie du premier trimestre !');
            $this->redirect(['action' => 'import1']);
        }elseif(!$data2->first()){
            $this->Flash->error('Importez le livre de paie du deuxième trimestre !');
            $this->redirect(['action' => 'import2']);
        }

        if($this->request->is('post'))
        {
            $data3Table = TableRegistry::get('data3_imports');
            $data3 = $data3Table->find()->all();
            if(!$data3->first())
            {
                if($this->request->getData()["file"]["size"] > 0)
                {
                    if($this->typeLivre($this->request->getData()["file"]["name"])){
                        $filename =  ROOT . DS . 'webroot' . DS . 'files' . DS . 'tmp_livre' . DS .$this->request->getData()["file"]["name"];
                        if (move_uploaded_file($this->request->getData()["file"]["tmp_name"] , $filename)){
                            $objet = $this->PhpExcel->openExcel(ROOT . DS . 'webroot' . DS . 'files' . DS . 'tmp_livre' . DS .$this->request->getData()["file"]["name"]);
                            $sheet = $objet->getActiveSheet();
                            $nbre = 2;
                            $i = 1;

                            while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() !== null)
                            {

                                if($objet->getActiveSheet()->getCell('A1')->getValue() == '="Matricule"'){
                                    $nom = utf8_encode($this->cleanChart($objet->getActiveSheet()->getCell('B'.$nbre)->getValue()));
                                    $prenom = utf8_encode($this->cleanChart($objet->getActiveSheet()->getCell('C'.$nbre)->getValue()));
                                    $matricule = $this->cleanChart($objet->getActiveSheet()->getCell('A'.$nbre)->getValue());
                                    $brut = 0;
                                    $nbrejour = 0;
                                    do {
                                        if($matricule ==  $this->cleanChart($objet->getActiveSheet()->getCell('A'.$nbre)->getValue()))
                                        {
                                            if($this->cleanChart($objet->getActiveSheet()->getCell('D'.$nbre)->getValue()) == "BRUT"){
                                                $brut = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                            if($this->cleanChart($objet->getActiveSheet()->getCell('D'.$nbre)->getValue()) == "TOTALHTRAV"){
                                                $nbrejour = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                        }

                                        $nbre++;
                                        $nbre2 = $nbre+1;

                                    } while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() == $objet->getActiveSheet()->getCell('A'.$nbre2)->getValue());

                                }else{
                                    $nom = utf8_encode($objet->getActiveSheet()->getCell('B'.$nbre)->getValue());
                                    $prenom = utf8_encode($objet->getActiveSheet()->getCell('C'.$nbre)->getValue());
                                    $matricule = $objet->getActiveSheet()->getCell('A'.$nbre)->getValue();
                                    $brut = 0;
                                    $nbrejour = 0;
                                    do {
                                        if($matricule ==  $objet->getActiveSheet()->getCell('A'.$nbre)->getValue())
                                        {
                                            if($objet->getActiveSheet()->getCell('D'.$nbre)->getValue() == "BRUT"){
                                                $brut = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                            if($objet->getActiveSheet()->getCell('D'.$nbre)->getValue() == "TOTALHTRAV"){
                                                $nbrejour = $objet->getActiveSheet()->getCell('F'.$nbre)->getValue();
                                            }

                                        }

                                        $nbre++;
                                        $nbre2 = $nbre+1;

                                    } while ($objet->getActiveSheet()->getCell('A'.$nbre)->getValue() == $objet->getActiveSheet()->getCell('A'.$nbre2)->getValue());

                                }

                                $data = $data3Table->newEntity();
                                $data->Import_3_1 = $matricule;
                                $data->Import_3_2 = $nom;
                                $data->Import_3_3 = $prenom;
                                $data->Import_3_5 = $nbrejour;
                                $data->Import_3_6 = $brut;
                                $data3Table->save($data);
                                $nbre = $nbre2;
                                $brut = 0;
                                $nbrejour = 0;
                                $nom = '';
                                $prenom = '';
                                $matricule = '';

                            }
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            unlink($filename);
                            $this->redirect(['action' => 'assainir']);
                        }else{
                            $this->Flash->error('Un problème est survenu : Veuillez recharger le fichier !');
                        }
                    }else{
                        $filename=$this->request->getData()["file"]["tmp_name"];
                        $file = fopen($filename, "r");
                        $count=0;
                        $brut = 0;
                        $nbrejour = 0;
                        $nom = '';
                        $prenom = '';
                        $matricule = '';
                        $typeof = $this->typefile($file);
                        if((($typeof === true) && ($data1->first()->Import_1_8 == NULL)) || (($typeof === true) && ($data2->first()->Import_2_8 == NULL)))
                        {
                            $this->Flash->error('Impoter le même type de livre de paie que 1er et 2eme mois !');
                            return $this->redirect(['action' => 'import3']);
                        }
                        if($typeof === true){
                            while (($emapData = fgetcsv($file, 10000, ";")) !== FALSE)
                            {

                                $count++;    // add this line

                                if($count>1)
                                {
                                    if(!isset($emapData[5])){
                                        $this->Flash->error("Le Livre de paie n'est pas valide, veuillez vérifier que toutes colones sont présentes !");
                                        $this->redirect(['action' => 'import3']);
                                        break;
                                    }
                                    // add this line
                                    $data = $data3Table->newEntity();
                                    $data->Import_3_1 = $emapData[0];
                                    $data->Import_3_2 = utf8_encode($emapData[1]);
                                    $data->Import_3_3 = utf8_encode($emapData[2]);
                                    $data->Import_3_4 = $emapData[3];
                                    $data->Import_3_5 = $emapData[4];
                                    $data->Import_3_6 = $emapData[5];
                                    $data->Import_3_7 = $emapData[6];
                                    $data->Import_3_8 = $emapData[7];
                                    $data->Import_3_9 = $emapData[8];
                                    $data->Import_3_10 = $emapData[9];
                                    $data->Import_3_11 = $emapData[10];
                                    $data->Import_3_12 = $emapData[11];
                                    $data->Import_3_13 = $emapData[12];
                                    $data->Import_3_14 = $emapData[13];
                                    $data->Import_3_15 = $emapData[14];
                                    $data->Import_3_16 = $emapData[15];
                                    $data->Import_3_17 = $emapData[16];
                                    $data->Import_3_18 = $emapData[17];
                                    $data->Import_3_19 = $emapData[18];
                                    $data->Import_3_20 = $emapData[19];
                                    $data3Table->save($data);
                                }
                            }
                            fclose($file);
                            // echo 'CSV File has been successfully Imported';
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            $this->redirect(['action' => 'assainir']);
                        }else{
                            $sage = false;
                            while (($emapData = fgetcsv($file, 10000, ";")) !== FALSE)
                            {

                                $count++;    // add this line

                                if($typeof === "Sage"){
                                    $sage = true;
                                }

                                if($count==2)
                                {
                                   if($sage){$matricule = $this->cleanChart($emapData[0]);}else{$matricule = $emapData[0];}
                                }
                                if($count>1)
                                {
                                    if($matricule ==  $emapData[0] && !$sage)
                                    {
                                        if($emapData[3] == "BRUT"){
                                            $brut = $emapData[5];
                                        }elseif($emapData[3] == "TOTALHTRAV"){
                                            $nbrejour = $emapData[5];
                                            $nom = utf8_encode($emapData[1]);
                                            $prenom = utf8_encode($emapData[2]);
                                        }

                                    }elseif($matricule ==  $this->cleanChart($emapData[0]) && $sage){
                                        if($this->cleanChart($emapData[3]) == "BRUT"){
                                            $brut = $emapData[5];
                                            settype($brut, "integer");
                                        }elseif($this->cleanChart($emapData[3]) == "TOTALHTRAV"){
                                            $nbrejour = $emapData[5];
                                            $nom = utf8_encode($this->cleanChart($emapData[1]));
                                            $prenom = utf8_encode($this->cleanChart($emapData[2]));
                                        }
                                    }else{
                                        // add this line
                                        $data = $data3Table->newEntity();
                                        $data->Import_3_1 = $matricule;
                                        $data->Import_3_2 = $nom;
                                        $data->Import_3_3 = $prenom;
                                        $data->Import_3_5 = $nbrejour;
                                        $data->Import_3_6 = $brut;
                                        $data3Table->save($data);
                                        $brut = 0;
                                        $nbrejour = 0;
                                        $nom = '';
                                        $prenom = '';
                                        if($sage){$matricule = $this->cleanChart($emapData[0]);}else{$matricule = $emapData[0];}
                                    }
                                }
                            }
                            fclose($file);
                            // echo 'CSV File has been successfully Imported';
                            $this->Flash->success('Le Livre de paie a été importé avec succès !');
                            $this->redirect(['action' => 'assainir']);
                        }
                    }
                }else{
                    $this->Flash->error('Le fichier est invalide : Veuillez charger un fichier valide !');
                }

            }else{
                $this->Flash->error('Vous avez déjà importé le troisième livre de paie !');
                $this->redirect(['action' => 'assainir']);
            }
        }
    }

    // Traitement des données pour générer le fichier final
    public function assainir()
    {
        if($this->request->is('post'))
        {
            $connection = ConnectionManager::get('default');

            $data2Table = TableRegistry::get('data2_imports');
            $data2 = $data2Table->find()->all();
            $data1Table = TableRegistry::get('data1_imports');
            $data1 = $data1Table->find()->all();
            $data3Table = TableRegistry::get('data3_imports');
            $data3 = $data3Table->find()->all();
            if(!$data1->first())
            {
                $this->Flash->error('Importez le livre de paie du premier trimestre !');
                $this->redirect(['action' => 'import1']);
            }elseif(!$data2->first()){
                $this->Flash->error('Importez le livre de paie du deuxième trimestre !');
                $this->redirect(['action' => 'import2']);
            }elseif(!$data3->first()){
                $this->Flash->error('Importez le livre de paie du troisième trimestre !');
                $this->redirect(['action' => 'import3']);
            }

            $oneTable = TableRegistry::get('One_imports');
            $twoTable = TableRegistry::get('Two_imports');
            $treeTable = TableRegistry::get('Tree_imports');
            $sql ="TRUNCATE one_imports";
            $result = $connection->execute($sql);

            $sql ="TRUNCATE two_imports";
            $result = $connection->execute($sql);

            $sql ="TRUNCATE tree_imports";
            $result = $connection->execute($sql);

            $sql ="TRUNCATE final_datas";
            $result = $connection->execute($sql);


                /////////////////////////////////////////// Assainir fichier 1
                $data1Table = TableRegistry::get('data1_imports');
                $data1 = $data1Table->find()->all();
                foreach($data1 as $data) {
                    $onedata = $oneTable->newEntity();
                    $onedata->Matricule_Employe_1_1 = $data->Import_1_1;
                    $onedata->Nom_1_2 = $data->Import_1_2;
                    $onedata->Prenom_1_3 = $data->Import_1_3;
                    $onedata->Date_de_debut_1_4 = $data->Import_1_4;
                    $onedata->Nombre_de_jours_travailles_1_5 = $data->Import_1_5;
                    $onedata->Salaire_brut_1_6 = $data->Import_1_6;
                    $oneTable->save($onedata);
                }
                ////////////////////////////////////////// Assainir Fichier 2
                $data2Table = TableRegistry::get('data2_imports');
                $data2 = $data2Table->find()->all();
                foreach($data2 as $data) {
                    $twodata = $twoTable->newEntity();
                    $twodata->Matricule_Employe_2_1 = $data->Import_2_1;
                    $twodata->Nom_2_2 = $data->Import_2_2;
                    $twodata->Prenom_2_3 = $data->Import_2_3;
                    $twodata->Date_de_debut_2_4 = $data->Import_2_4;
                    $twodata->Nombre_de_jours_travailles_2_5 = $data->Import_2_5;
                    $twodata->Salaire_brut_2_6 = $data->Import_2_6;
                    $twoTable->save($twodata);
                }

                ////////////////////////////////////////// Assainir Fichier 3
                $data3Table = TableRegistry::get('data3_imports');
                $data3 = $data3Table->find()->all();
                foreach($data3 as $data) {
                    $treedata = $treeTable->newEntity();
                    $treedata->Matricule_Employe_3_1 = $data->Import_3_1;
                    $treedata->Nom_3_2 = $data->Import_3_2;
                    $treedata->Prenom_3_3 = $data->Import_3_3;
                    $treedata->Date_de_debut_3_4 = $data->Import_3_4;
                    $treedata->Nombre_de_jours_travailles_3_5 = $data->Import_3_5;
                    $treedata->Salaire_brut_3_6 = $data->Import_3_6;
                    $treeTable->save($treedata);
                }

                //////////////////////////////////////////////////////////////Assainissement final/////////////////////////////////////////////////////////////////////////////
                $finalTable = TableRegistry::get('final_datas');


                // Charger le premier mois
                $sql = "INSERT INTO final_datas(Matricule_Employe_1_1, Nom_1_2, Prenom_1_3, Date_de_debut_1_4, Nombre_de_jours_travailles_1_5, Salaire_brut_1_6)
                SELECT Matricule_Employe_1_1, Nom_1_2, Prenom_1_3, Date_de_debut_1_4, Nombre_de_jours_travailles_1_5, Salaire_brut_1_6 FROM one_imports";
                $onedatas = $connection->execute($sql);

                //ceux qui ne sont pas présent au premier mois et apparaissent au deuxieme mois
                $twodatas = $connection->execute("INSERT INTO final_datas(Matricule_Employe_1_1, Nom_1_2, Prenom_1_3,Date_de_debut_1_4 ,Nombre_de_jours_travailles_2_5, Salaire_brut_2_6)
                SELECT Matricule_Employe_2_1, Nom_2_2, Prenom_2_3,Date_de_debut_2_4, Nombre_de_jours_travailles_2_5, Salaire_brut_2_6 FROM two_imports WHERE Matricule_Employe_2_1 NOT IN ( SELECT Matricule_Employe_1_1 FROM final_datas)");

                //ceux qui ne sont pas présent au premier mois et apparaissent au troisieme mois
                $treedatas = $connection->execute("INSERT INTO final_datas(Matricule_Employe_1_1, Nom_1_2, Prenom_1_3,Date_de_debut_1_4 ,Nombre_de_jours_travailles_3_5, Salaire_brut_3_6)
                SELECT Matricule_Employe_3_1, Nom_3_2, Prenom_3_3,Date_de_debut_3_4, Nombre_de_jours_travailles_3_5, Salaire_brut_3_6 FROM tree_imports WHERE Matricule_Employe_3_1 NOT IN ( SELECT Matricule_Employe_1_1 FROM final_datas)");

                //+++Mise a jour-ceux qui sont présent le 1er et 2em mois
                $sql = "UPDATE final_datas INNER JOIN two_imports ON final_datas.Matricule_Employe_1_1=two_imports.Matricule_Employe_2_1 SET final_datas.Salaire_brut_2_6=two_imports.Salaire_brut_2_6, final_datas.Nombre_de_jours_travailles_2_5=two_imports.Nombre_de_jours_travailles_2_5";
                $result = $connection->execute($sql);

                //+++Mise a jour- de ceux qui sont présent le 1er, 2em et 3e mois
                $sql = "UPDATE final_datas INNER JOIN tree_imports ON final_datas.Matricule_Employe_1_1=tree_imports.Matricule_Employe_3_1 SET final_datas.Salaire_brut_3_6=tree_imports.Salaire_brut_3_6, final_datas.Nombre_de_jours_travailles_3_5=tree_imports.Nombre_de_jours_travailles_3_5";
                $result = $connection->execute($sql);

                //Supprimer les ligne qui n'ont pas de nom
                $result = $connection->execute("DELETE FROM final_datas WHERE Nom_1_2='' ");

                //Suppression pour une nouvelle importation
                $sql ="TRUNCATE data1_imports";
                $result = $connection->execute($sql);

                $sql ="TRUNCATE data2_imports";
                $result = $connection->execute($sql);

                $sql ="TRUNCATE data3_imports";
                $result = $connection->execute($sql);

                $this->Flash->success('Les livres sont prêts pour l\'export du DTS !');
                $this->redirect(['action' => 'listeDts']);


        }
    }

    // Exportation du fichier final
    public function genererDts()
    {
        $connection = ConnectionManager::get('default');
        $user = $this->Auth->user();
        if($this->request->is('post'))
        {

            $finalTable = TableRegistry::get('final_datas');
            $configsTable = TableRegistry::get('configs');
            $config = $configsTable->find()->where(['id_user =' => $user['id']])->first();
            if($config == null){
                $this->Flash->error('Veuillez configurer les informations de votre entreprise !');
                return $this->redirect(['controller' => 'Configs', 'action' => 'index']);
            }
            $datas = $finalTable->find()->all();
            if(!$datas->first())
            {
                $this->Flash->error('Il faut assainir les données avant de générer le DTS !');
                return $this->redirect(['action' => 'assainir']);
            }

            $objet = $this->PhpExcel->openExcel( ROOT . DS . 'webroot' . DS . 'files' .DS . 'models' . DS . 'DTS_GDS.xls');
            $sheet = $objet->getSheet(0);

            //Enlever les space dans les salaires; pour favoriser l'ecriture dans le fichier excel qui attends un nombre au lieu d'une chaine de caradtere
            $sql = "UPDATE final_datas SET Salaire_brut_1_6=replace(Salaire_brut_1_6,' ','')";
            $result = $connection->execute($sql);


            $sql = "UPDATE final_datas SET Salaire_brut_2_6=replace(Salaire_brut_2_6,' ','')";
            $result = $connection->execute($sql);

            $sql = "UPDATE final_datas SET Salaire_brut_3_6=replace(Salaire_brut_3_6,' ','')";
            $result = $connection->execute($sql);

            $sql = "SELECT * FROM final_datas";
            $result = $connection->execute($sql)->fetchAll('assoc');

            $nbre=26;

            $effectif = 0;

            $total_m1 = 0;
            $total_m2 = 0;
            $total_m3 = 0;

            $renumeration_totale = 0;
            $cotisation_brut = 0;
            $cotisation_net = 0;

            $cotisation_cnamgs = 0;

            $sheet->setCellValue('A11', $config->matricule_employeur);
            $sheet->setCellValue('A13', $config->raison_social);
            $sheet->setCellValue('B15', $config->bp);
            $sheet->setCellValue('B17', $config->telephone);
            $sheet->setCellValue('D15', $config->ville);
            $sheet->setCellValue('D17', $config->fax);

            if($this->request->getData()["periode"] == $config->periode){
                $sheet->setCellValue('E11', $config->periode);
            }else{
                $sheet->setCellValue('E11', $this->request->getData()["periode"]);
            }

            if($this->request->getData()["annee"] == $config->annee){
                $sheet->setCellValue('F11', $config->annee);
            }else{
                $sheet->setCellValue('F11', $this->request->getData()["annee"]);
            }

            $sheet->setCellValue('G11', $config->regime);
            $sheet->setCellValue('I11', $config->sigle);
            $sheet->setCellValue('E21', $config->allocation);

            foreach($result as $row)
            {
                $nbre++;
                $taux_cnss='18,5';
                $taux_cnamgs='4,1';

                if($nbre>0)
                {

                    $nbre2 = $nbre + 1;

                    $sheet->setCellValue('E'.$nbre, $taux_cnss);
                    $sheet->setCellValue('E'.$nbre2, $taux_cnamgs);
                    $sheet->mergeCells('A'.$nbre.':A'.$nbre2);
                    $sheet->setCellValue('A'.$nbre, $row["id"]);
                    $sheet->getStyle('A'.$nbre.':A'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('A'.$nbre)->applyFromArray([
                        'alignment'=>[
                            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                         ]]);

                    $sheet->getStyle('B'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('B'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );

                    $sheet->getStyle('C'.$nbre.':C'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );


                    //si le salaire depasse 1500000 on affecte le salaire plafone 1500000
                    $SalaireDeplafone1 = ($row["Salaire_brut_1_6"] < 1500000) ? $row["Salaire_brut_1_6"] : 1500000;
                    // $Valeur2 = !empty($row["Salaire_brut_1_6"]) ? $row["Salaire_brut_1_6"] : 0;
                    // $Valeur3 = $row["Matricule_Employe_1_1"];
                    // $Valeur4 = !empty($row["Nombre_de_jours_travailles_1_5"]) ? $row["Nombre_de_jours_travailles_1_5"] : 0;


                    $sheet->mergeCells('D'.$nbre.':D'.$nbre2);
                    $sheet->setCellValue('D'.$nbre, trim($row["Nom_1_2"])." ".$row["Prenom_1_3"]);
                    $sheet->getStyle('D'.$nbre.':D'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );

                    $sheet->getStyle('B'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('B'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('E'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('E'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );

                    $sheet->getStyle('F'.$nbre.':F'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('G'.$nbre.':G'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->setCellValue('H'.$nbre, $SalaireDeplafone1);
                    $total_m1 = $total_m1+$SalaireDeplafone1;
                    $sheet->getStyle('H'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('H'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->mergeCells('I'.$nbre.':I'.$nbre2);
                    $sheet->setCellValue('I'.$nbre, $row["Salaire_brut_1_6"]);
                    $sheet->getStyle('I'.$nbre.':I'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );

                    $sheet->getStyle('J'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('J'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );

                    $sheet->getStyle('K'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('K'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );

                    $sheet->setCellValue('F'.$nbre, $row["Date_de_debut_1_4"]);

                    $sheet->setCellValue('B'.$nbre, $row["Matricule_Employe_1_1"]);
                    $sheet->setCellValue('J'.$nbre, $row["Nombre_de_jours_travailles_1_5"]);

                    //si le salaire depasse 1500000 on affecte le salaire plafone 1500000
                    $SalaireDeplafone2 = ($row["Salaire_brut_2_6"] < 1500000) ? $row["Salaire_brut_2_6"] : 1500000;
                    // $Valeur6 = !empty($row["Salaire_brut_2_6"]) ? $row["Salaire_brut_2_6"] : 0;
                    // $Valeur7 = !empty($row["Nombre_de_jours_travailles_2_5"]) ? $row["Nombre_de_jours_travailles_2_5"] : 0;

                    ///Génération 2mois
                    $sheet->setCellValue('K'.$nbre, trim($SalaireDeplafone2));
                    $total_m2 = $total_m2+$SalaireDeplafone2;
                    $sheet->mergeCells('L'.$nbre.':L'.$nbre2);
                    $sheet->setCellValue('L'.$nbre, trim($row["Salaire_brut_2_6"]));
                    $sheet->getStyle('L'.$nbre.':L'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->setCellValue('M'.$nbre, $row["Nombre_de_jours_travailles_2_5"]);

                    $sheet->getStyle('M'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('M'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    //si le salaire depasse 1500000 on affecte le salaire plafone 1500000
                    $SalaireDeplafone3 = ($row["Salaire_brut_3_6"] < 1500000) ? $row["Salaire_brut_3_6"] : 1500000;
                    // $Valeur9 = !empty($row["Salaire_brut_3_6"]) ? $row["Salaire_brut_3_6"] : 0;
                    // $Valeur10 = !empty($row["Nombre_de_jours_travailles_3_5"]) ? $row["Nombre_de_jours_travailles_3_5"] : 0;

                    ///Génération 3mois
                    $sheet->setCellValue('N'.$nbre, trim($SalaireDeplafone3));
                    $total_m3 = $total_m3+$SalaireDeplafone3;

                    $sheet->getStyle('N'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('N'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );

                    $sheet->mergeCells('O'.$nbre.':O'.$nbre2);
                    $sheet->setCellValue('O'.$nbre, trim($row["Salaire_brut_3_6"]));
                    $sheet->getStyle('O'.$nbre.':O'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->setCellValue('P'.$nbre, $row["Nombre_de_jours_travailles_3_5"]);
                    $sheet->getStyle('P'.$nbre)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $sheet->getStyle('P'.$nbre2)->getBorders()->applyFromArray(
                        array(
                            'allborders' => array(
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array(
                                        'rgb' => '000000'
                                    )
                            )
                        )
                    );
                    $effectif++;
                    $nbre++;
                }

            }

            $renumeration_totale = $total_m1+$total_m2+$total_m3;
            $cotisation_brut = ($renumeration_totale*18.5)/100;
            $cotisation_cnamgs = ($renumeration_totale*4.1)/100;
            $cotisation_net = $cotisation_brut - $config->allocation;

            $sheet->setCellValue('I18', $effectif);
            $sheet->setCellValue('B21', $renumeration_totale);
            $sheet->setCellValue('B23', $cotisation_brut);
            $sheet->setCellValue('E23', $cotisation_net);
            $sheet->setCellValue('I21', $renumeration_totale);
            $sheet->setCellValue('I23', $cotisation_cnamgs);

            //pied de tableau
            $nbre+=2;
            $init = $nbre;
            $sheet->mergeCells('A'.$nbre.':P'.$nbre);
            $sheet->setCellValue('A'.$nbre, "SOUS TOTAL A REPORTER PAGE SUIVANTE");
            $sheet->getStyle('A'.$nbre.':P'.$nbre)->getBorders()->applyFromArray(
                    array(
                        'allborders' => array(
                            'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                            'color' => array(
                                    'rgb' => '000000'
                                )
                        )
                    )
                );
            $sheet->getStyle('A'.$nbre)->applyFromArray(array(
                'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                'rgb' => 'd8d8d8'))));
            $sheet->getStyle('A'.$nbre)->applyFromArray(array(
                'font'=>array(
                'bold'=>true,
                'size'=>10,
                'color'=>array(
                'rgb'=>'000000'))
                ));
            $nbre+=2;
            $nbre2 = $nbre + 1;
            $sheet->mergeCells('A'.$nbre.':A'.$nbre2);
            $sheet->setCellValue('A'.$nbre, "RECAP");
            $sheet->getStyle('A'.$nbre.':A'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('A'.$nbre)->applyFromArray([
                'alignment'=>[
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                 ]]);

            $sheet->getStyle('A'.$nbre)->applyFromArray(array(
            'font'=>array(
            'bold'=>true,
            'size'=>10,
            'color'=>array(
            'rgb'=>'000000'))
            ));
            $sheet->setCellValue('B'.$nbre, "CNSS");
            $sheet->getStyle('B'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('B'.$nbre)->applyFromArray(array(
                'font'=>array(
                'bold'=>true,
                'size'=>10,
                'color'=>array(
                'rgb'=>'000000'))
                ));
            $sheet->setCellValue('B'.$nbre2, "CNAMGS");
            $sheet->getStyle('B'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('B'.$nbre2)->applyFromArray(array(
                'font'=>array(
                'bold'=>true,
                'size'=>10,
                'color'=>array(
                'rgb'=>'000000'))
                ));
            $sheet->mergeCells('C'.$nbre.':C'.$nbre2);
            $sheet->setCellValue('C'.$nbre, "TAUX");
            $sheet->getStyle('C'.$nbre.':C'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('C'.$nbre)->applyFromArray([
                'alignment'=>[
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                 ]]);
            $sheet->getStyle('C'.$nbre)->applyFromArray(array(
            'font'=>array(
            'bold'=>true,
            'size'=>10,
            'color'=>array(
            'rgb'=>'000000'))
            ));
            $sheet->setCellValue('D'.$nbre, $taux_cnss."0%");
            $sheet->setCellValue('D'.$nbre2, $taux_cnamgs."0%");
            $sheet->getStyle('D'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('D'.$nbre)->applyFromArray([
                'alignment'=>[
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                 ]]);
            $sheet->getStyle('D'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->mergeCells('E'.$nbre.':G'.$nbre);
            $sheet->setCellValue('E'.$nbre, "MASSE SALARIALE PLAFONNEE CNSS");
            $sheet->getStyle('E'.$nbre.':G'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('E'.$nbre)->applyFromArray(array(
                            'font'=>array(
                            'bold'=>true,
                            'size'=>8,
                            'color'=>array(
                            'rgb'=>'000000'))
                            ));
            $sheet->mergeCells('E'.$nbre2.':G'.$nbre2);
            $sheet->setCellValue('E'.$nbre2, "MASSE SALARIALE PLAFONNEE CNAMGS");
            $sheet->getStyle('E'.$nbre2.':G'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('E'.$nbre2)->applyFromArray(array(
                'font'=>array(
                'bold'=>true,
                'size'=>8,
                'color'=>array(
                'rgb'=>'000000'))
                ));

            $sheet->setCellValue('H'.$nbre, $total_m1);
            $sheet->setCellValue('H'.$nbre2, $total_m1);
            $sheet->getStyle('H'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('H'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->mergeCells('I'.$nbre.':J'.$nbre2);
            $sheet->getStyle('I'.$nbre.':J'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );

            $sheet->setCellValue('K'.$nbre, $total_m2);
            $sheet->setCellValue('K'.$nbre2, $total_m2);
            $sheet->getStyle('K'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('K'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->mergeCells('L'.$nbre.':M'.$nbre2);
            $sheet->getStyle('L'.$nbre.':M'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );

            $sheet->setCellValue('N'.$nbre, $total_m3);
            $sheet->setCellValue('N'.$nbre2, $total_m3);
            $sheet->getStyle('N'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('N'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->mergeCells('O'.$nbre.':P'.$nbre2);
            $sheet->getStyle('O'.$nbre.':P'.$nbre2)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $nbre+=2;
            $sheet->mergeCells('A'.$nbre.':N'.$nbre);
            $sheet->setCellValue('A'.$nbre, "COTISATION GLOBALE DUE (CNSS + CNAMGS)");
            $sheet->getStyle('A'.$nbre.':N'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );
            $sheet->getStyle('A'.$nbre)->applyFromArray(array(
                'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                'rgb' => 'd8d8d8'))));
            $sheet->getStyle('A'.$nbre)->applyFromArray(array(
                'font'=>array(
                'bold'=>true,
                'size'=>10,
                'color'=>array(
                'rgb'=>'000000'))
                ));
            $sheet->mergeCells('O'.$nbre.':P'.$nbre);
            $sheet->getStyle('O'.$nbre.':P'.$nbre)->getBorders()->applyFromArray(
                array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                        'color' => array(
                                'rgb' => '000000'
                            )
                    )
                )
            );

            $cotisation_total = $cotisation_net + $cotisation_cnamgs;
            settype($cotisation_total, "integer");
            $sheet->setCellValue('O'.$nbre, $cotisation_total);
            $sheet->getStyle('O'.$nbre)->applyFromArray(array(
                'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                'rgb' => 'd8d8d8')))) ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_GENERAL);;

            $date = date('dmYHis');
            $filename = basename( ROOT . DS . 'webroot' . DS . 'files' . DS .'GDS-'.$date.'.xls');
            $link = '/files/GDS-'.$date.'.xls';

            $objWriter = $this->PhpExcel->downloadFile($objet, $filename);

            $this->Flash->success("La DTS a été généré avec succès ! Télécharger <a href='".$link."' target='_blank'>ici</a> ");
            return $this->redirect(['controller' => 'Declarations','action' => 'listeDts']);
        }
    }

    // Liste des entrées du fichier final
    public function listeDts($id = NULL)
    {
        $finalTable = TableRegistry::get('final_datas');
        if($id != NULL){
            $final = $finalTable->get($id);
            if (!$final) {
                $this->Flash->error('Cet employé n\'exite pas');
                return $this->redirect(['action' => 'listeDts']);
            }else{
                $finalTable->delete($final);
                $this->Flash->set('L\'employé a été supprimé avec succès.', ['element' => 'success']);
            }
        }
        $finals = $finalTable->find()->all();
        $this->set('finals', $finals);
        return $this->render('liste_dts', 'login');
    }



    // Nettoyage de la BD pour la prochaine génération de DTS
    public function viderBd()
    {
        $connection = ConnectionManager::get('default');

        $sql ="TRUNCATE one_imports";
        $result = $connection->execute($sql);

        $sql ="TRUNCATE two_imports";
        $result = $connection->execute($sql);

        $sql ="TRUNCATE tree_imports";
        $result = $connection->execute($sql);

        $sql ="TRUNCATE data1_imports";
        $result = $connection->execute($sql);

        $sql ="TRUNCATE data2_imports";
        $result = $connection->execute($sql);

        $sql ="TRUNCATE data3_imports";
        $result = $connection->execute($sql);

        $sql ="TRUNCATE final_datas";
        $result = $connection->execute($sql);
    }

    //presentation de la solution
    public function about(){
        return $this->render('about', 'login');
    }
}
