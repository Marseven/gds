<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Gabon Declaration Soft</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,700,900" rel="stylesheet">
    <?= $this->Html->css('../fonts/icomoon/style.css') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('jquery-ui.css') ?>
    <?= $this->Html->css('owl.carousel.min.css') ?>
    <?= $this->Html->css('owl.theme.default.min.css') ?>
    <?= $this->Html->css('owl.theme.default.min.css') ?>
    <?= $this->Html->css('jquery.fancybox.min.css') ?>
    <?= $this->Html->css('bootstrap-datepicker.css') ?>
    <?= $this->Html->css('../fonts/flaticon/font/flaticon.css') ?>
    <?= $this->Html->css('aos.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css') ?>


    <!-- inject css end -->
    <?= $this->fetch('css') ?>
  </head>
  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>


    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <div class="site-logo mr-auto w-25"><a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'index']) ?>">Gabon Declaration Soft</a></div>

            <div class="mx-auto text-center">
                <nav class="site-navigation position-relative text-right" role="navigation">
                    <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block  m-0 p-0">
                    <li><a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'index']) ?>" class="nav-link">Accueil</a></li>
                    <li><a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'about']) ?>" class="nav-link">À Propos</a></li>
                    <?php if(isset($user)):?><!--Vérifie si l'utilisateur est conneecté et afficher son profiel'-->
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle nav-link" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $user->nom ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header"> DTS </li>
                            <li class="dropdown-item"> <a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'import1']) ?>"><i class="fa fa-file-upload"></i> Impoter des Livres</a> </li>
                            <li class="dropdown-item"> <a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'index']) ?>?clean=true"><i class="fa fa-trash"></i> Vider la BD</a> </li>
                            <li class="dropdown-item"> <a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'listeDts']) ?>"><i class="fa fa-list"></i> Liste de la DTS</a> </li>
                            <div class="dropdown-divider"></div>
                            <li class="dropdown-header"> Gérant </li>
                            <li class="dropdown-item"> <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index']) ?>"><i class="fa fa-user"></i> Profil</a> </li>
                            <li class="dropdown-item"> <a href="<?= $this->Url->build(['controller' => 'Configs', 'action' => 'index']) ?>"><i class="fa fa-cog"></i> Réglages</a> </li>
                            <li class="dropdown-item"> <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>"><i class="fa fa-sign-out-alt"></i> Déconnexion</a> </li>
                        </ul>
                        </li>
                    <?php else:?>
                        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signup']) ?>" class="nav-link">Inscription</a></li>
                    <?php endif;?>
                    </ul>
                </nav>
            </div>
          <div class="ml-auto w-25">
            <nav class="site-navigation position-relative text-right" role="navigation">
              <ul class="site-menu main-menu site-menu-dark js-clone-nav mr-auto d-none d-lg-block m-0 p-0">
                <li class="cta"><a href="<?= $this->Url->build(['controller' => 'Contact', 'action' => 'index']) ?>" class="nav-link"><span><i class="fa fa-address-card"></i> Contactez-nous</span></a></li>
              </ul>
            </nav>
            <a href="#" class="d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black float-right"><span class="icon-menu h3"></span></a>
          </div>
        </div>
      </div>

    </header>

    <div class="intro-section" id="home-section">
      <div class="slide-1" style="background-image: url('/img/hero.JPG');" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row align-items-center">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>
      </div>
    </div>


<footer class="footer-section bg-white">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h3>GDS</h3>
            <p>Logiciels de traitement des cotisations sociales</p>
          </div>

          <div class="col-md-3 ml-auto">
            <h3>Lien Utiles</h3>
            <ul class="list-unstyled footer-links">
              <li><a href="#">FAQ</a></li>
              <li><a href="#">Conditions d'utilisations</a></li>
            </ul>
          </div>

          <div class="col-md-4">
            <h3>Abonnez-vous</h3>
            <p>Restez sur les mises à jour et les nouveautés.</p>
            <?= $this->Form->create($newsletter, ['url' => ['controller' => 'Newsletters', 'action' => 'add'], 'class' => 'footer-subscribe']) ?>
                <?= $this->Form->input('email', ['class' => 'form-control', 'label' => '', 'placeholder' => 'Email', 'required', 'id'=>'mc-email', 'type'  => 'email']); ?>
                <br>
                <input class="btn btn-primary" type="submit" name="subscribe" value="Envoyer">
            <?= $this->Form->end(); ?>
          </div>

        </div>

        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
            <p>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        Copyright &copy;<script>document.write(new Date().getFullYear());</script> Tous Droits Réservés | Ce logiciel a été developé avec <i class="icon-heart" aria-hidden="true"></i> par <a href="#" >AGAPPLI</a>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
      </p>
            </div>
          </div>

        </div>
      </div>
    </footer>



  </div> <!-- .site-wrap -->

  <?= $this->Html->script('jquery-3.3.1.min.js') ?>
  <?= $this->Html->script('jquery-migrate-3.0.1.min.js') ?>
  <?= $this->Html->script('jquery-ui.js') ?>
  <?= $this->Html->script('popper.min.js') ?>
  <?= $this->Html->script('bootstrap.min.js') ?>
  <?= $this->Html->script('owl.carousel.min.js') ?>
  <?= $this->Html->script('jquery.countdown.min.js') ?>
  <?= $this->Html->script('bootstrap-datepicker.min.js') ?>
  <?= $this->Html->script('jquery.easing.1.3.js') ?>
  <?= $this->Html->script('aos.js') ?>
  <?= $this->Html->script('jquery.fancybox.min.js') ?>
  <?= $this->Html->script('jquery.sticky.js') ?>

  <?= $this->Html->script('main.js') ?>

  <!-- inject js end -->
  <?= $this->fetch('script') ?>

  </body>
</html>
