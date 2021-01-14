
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
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" role="button" aria-expanded="false" >
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

        <div class="intro-section single-cover" id="home-section">

        <div class="slide-1 " style="background-image: url('http://localhost/GDS/img/hero.JPG');" data-stellar-background-ratio="0.5">
            <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                <div class="row justify-content-center align-items-center text-center">
                    <div class="col-lg-6">
                    <h1 data-aos="fade-up" data-aos-delay="0">AGAPPLI</h1>
                    </div>


                </div>
                </div>

            </div>
            </div>
        </div>
        </div>

        <?= $this->Flash->render() ?>

        <div class="site-section" id="programs-section">
            <div class="container">
                <div class="row mb-5 justify-content-center">
                    <div class="col-lg-7 text-center"  data-aos="fade-up" data-aos-delay="">
                        <h2 class="section-title">Une Vision</h2>
                        <p>Notre Vision est rendre le travail des ressources humaines simple et facile. En effet le temps et la possibilité de faire des erreurs lors du traitemenent des cotisations sociales sont élevés, mais avec GDS ce problème est résolu et ça prend pas plus de 10 minutes ;).</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="site-section" id="teachers-section">
            <div class="container">

                <div class="row mb-5 justify-content-center">
                <div class="col-lg-7 mb-5 text-center"  data-aos="fade-up" data-aos-delay="">
                    <h2 class="section-title">L'équipe</h2>
                    <p class="mb-5">La Jeunesse et L'expérience</p>
                </div>
                </div>

                <div class="row">

                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="teacher text-center">
                    <img src="/gds/img/person_1.png" alt="Image" class="img-fluid w-50 rounded-circle mx-auto mb-4">
                    <div class="py-2">
                        <h3 class="text-black">Ghislain Mhindou</h3>
                        <p class="position">Spécialiste RH & Entrepreneur</p>
                    </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="teacher text-center">
                    <img src="/gds/img/person_2.png" alt="Image" class="img-fluid w-50 rounded-circle mx-auto mb-4">
                    <div class="py-2">
                        <h3 class="text-black">Arouna Alaho</h3>
                        <p class="position">Social Manager et Eentrepreneur</p>
                    </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="teacher text-center">
                    <img src="/gds/img/person_3.png" alt="Image" class="img-fluid w-50 rounded-circle mx-auto mb-4">
                    <div class="py-2">
                        <h3 class="text-black">Richard  Mebodo</h3>
                        <p class="position">Développeur & Entrepreneur</p>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>



        <div class="site-section pb-0">

            <div class="container">
                <div class="row mb-5 justify-content-center" data-aos="fade-up" data-aos-delay="">
                    <div class="col-lg-7 text-center">
                        <h2 class="section-title">Pourquoi choisir GDS ?</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 ml-auto align-self-start"  data-aos="fade-up" data-aos-delay="100">

                        <div class="p-4 rounded bg-white why-choose-us-box">

                            <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-check"></span></span></div>
                                <div><h3 class="m-0">Rapidité</h3></div>
                            </div>

                            <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-check"></span></span></div>
                                <div><h3 class="m-0">Simplicité</h3></div>
                            </div>

                            <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-check"></span></span></div>
                                <div><h3 class="m-0">Gain de temps</h3></div>
                            </div>

                            <div class="d-flex align-items-center custom-icon-wrap custom-icon-light mb-3">
                                <div class="mr-3"><span class="custom-icon-inner"><span class="icon icon-check"></span></span></div>
                                <div><h3 class="m-0">Adapté</h3></div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-7 align-self-end"  data-aos="fade-left" data-aos-delay="200">
                        <img src="/gds/img/why.jpg" alt="Image" class="img-fluid">
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
        <?= $this->fetch('js') ?>

    </body>
</html>

