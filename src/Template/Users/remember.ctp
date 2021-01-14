<div class="col-12">
	<div class="row align-items-center">
		<div class="col-lg-6 mb-4">
			<h1  data-aos="fade-up" data-aos-delay="100">Gabon Declaration Soft</h1>
			<p class="mb-4"  data-aos="fade-up" data-aos-delay="200">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime ipsa nulla sed quis rerum amet natus quas necessitatibus.</p>
			<p data-aos="fade-up" data-aos-delay="300"><a href="<?= $this->Url->build(['controller' => 'Declaration', 'action' => 'import1']) ?>" class="btn btn-primary py-3 px-5 btn-pill">Générer une déclaration</a></p>

		</div>

		<div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">
    <?= $this->Form->create('User', ['class' => 'form-box', 'id' => 'form-login', 'url' => ['Controller' => 'Users','action' => 'remember']]); ?>
       <h3 class="h4 text-black mb-4">Mot de passe oublié !</h3>
      <div class="form-group form-group-default">
      <label>Email</label>
      <div class="controls">
      <?= $this->Form->control('email', array(
          'class' => 'form-control',
          'placeholder' => 'abc@xyz.com',
          'type' => 'text',
          'label' => '',
          'required'
      )); ?>
      </div>
      </div>

      <div class="row">
      <div class="col-md-6 no-padding sm-p-l-10">

      </div>
      <div class="col-md-6 d-flex align-items-center justify-content-end">
      <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>" >Connexion</a>
      </div>
      </div>

      <?= $this->Form->control('Envoyer', array(
          'class' => 'btn btn-primary btn-cons m-t-10',
          'id'    => 'connexion',
          'type'  => 'submit',
          'label' => ''
      )); ?>
      <?= $this->Form->end(); ?>
		</div>

	</div>
</div>
