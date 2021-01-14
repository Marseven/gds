<div class="col-12">
	<div class="row align-items-center">
		<div class="col-lg-6 mb-4">
			<h1  data-aos="fade-up" data-aos-delay="100">Gabon Declaration Soft</h1>
			<p class="mb-4"  data-aos="fade-up" data-aos-delay="200">Logiciel de Traitement des cotisations sociales CNSS et CNAMGS.</p>
			<p data-aos="fade-up" data-aos-delay="300"><a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'import1']) ?>" class="btn btn-primary py-3 px-5 btn-pill"><i class="fa fa-file-export"></i> Générer une déclaration</a></p>

		</div>

		<div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">

			<?php if(isset($user)):?><!--Vérifie si l'utilisateur est conneecté et afficher son profiel'-->
				<div class="form-box">
					<h3 class="h4 text-black mb-4">Traitement du Fichier DTS</h3>
					<ul>
						<li> <a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'import1']) ?>"><i class="fa fa-file-upload"></i> Importer les livres de paie</a> </li>
						<li> <a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'index']) ?>?clean=true"><i class="fa fa-trash"></i> Vider la base de données</a> </li>
						<li> <a href="<?= $this->Url->build(['controller' => 'Declarations', 'action' => 'listeDts']) ?>"><i class="fa fa-list"></i> Liste de la DTS</a> </li>
					</ul>
				</div>
			<?php else:?>
                <?= $this->Form->create('User', ['url' => ['controller' => 'Users','action' => 'login'], 'class' => 'form-box']); ?>
					<h3 class="h4 text-black mb-4">Connexion</h3>
					<!--identifiant field-->
					<div class="form-group">
                        <?= $this->Form->input('email', array(
                            'class' => 'form-control white_bg',
                            'placeholder' => 'Email',
                            'type' => 'text',
                            'label' => 'Email',
                        )); ?>
					</div>

					<!--Password field-->
					<div class="form-group">
                        <?= $this->Form->input('password', array(
                            'class' => 'form-control white_bg',
                            'placeholder' => 'Mot de passe',
                            'type' => 'password',
                            'label' => 'Mot de Passe',
                        )); ?>
					</div>

					<?= $this->Form->input('Connexion', array(
                        'class' => 'btn btn-primary',
                        'id'    => 'connexion',
                        'type'  => 'submit',
                        'label' => ''
                    )); ?>
                    <br/><br/>

					<div class="panel-footer" id="Panel_register_center">Mot de passe oublié ? <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'remember']) ?>"><strong >Cliquez-ici</strong ></a></div>
                <?= $this->Form->end(); ?>
			<?php endif;?>

		</div>

	</div>
</div>
