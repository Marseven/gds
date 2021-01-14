

<div class="intro-section single-cover" id="home-section">
    <div class="slide-1 " style="background-image: url('http://localhost/GDS/img/hero.JPG');" data-stellar-background-ratio="0.5">
        <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
            <div class="row justify-content-center align-items-center text-center">
                <div class="col-lg-6">
                <h1 data-aos="fade-up" data-aos-delay="0">Inscription</h1>
                </div>


            </div>
            </div>

        </div>
        </div>
    </div>
</div>

<?= $this->Flash->render() ?>

<div class="site-section bg-light" id="contact-section">
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-12 ml-auto" data-aos="fade-up" data-aos-delay="500">
                <?= $this->Form->create($new_user, ['type' => 'file', 'class' => 'form-box', 'id' => 'form-register']); ?>

                    <h3>Inscription</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Prénom</label>
                                <?= $this->Form->control('prenom', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Prenom',
                                    'label' => '',
                                )); ?>
                            </div>
                            <div class="form-group form-group-default">
                                <label>Téléphone</label>
                                <?= $this->Form->control('telephone', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Téléphone*',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Nom</label>
                                <?= $this->Form->control('nom', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Nom*',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                            <div class="form-group form-group-default">
                                <label>Email</label>
                                <?= $this->Form->control('email', array(
                                    'class' => 'form-control',
                                    'type' => 'email',
                                    'placeholder' => 'Email*',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Photo de profil</label>
                                <div class="row">
                                    <?= $this->Form->control('picture', array(
                                        'type' => 'file',
                                        'label' => '',
                                        'id' => 'picture',
                                        'accept' => 'image/*'
                                    )); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <div id="image_preview" class="row">
                                    <div class="col-sm-6">
                                        <?= $this->Html->image('person_1.jpg', ['alt' => 'img-thumbnail', 'width' => '80%', 'height' => 'auto']); ?>
                                        <h5></h5>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input file">
                                            <button style="margin-top: 30px; padding: 5px; float: left;" class="btn btn-danger" type="button">Annuler</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Mot de passe</label>
                                <?= $this->Form->control('password', array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Mot de Passe*',
                                    'type' => 'password',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Confirmer Mot de passe</label>
                                <?= $this->Form->control('password_verify', array(
                                    'class' => 'form-control',
                                    'type' => 'password',
                                    'placeholder' => 'Confirmer Mot de Passe*',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-lg-6 text-left">
                            <?= $this->Form->control('S\'incrire', array(
                                'class' => 'btn btn-primary',
                                'type' => 'submit',
                            )); ?>
                        </div>
                    </div>
                    <div class="panel-footer" id="Panel_register_center">Vous avez un compte ? <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>"><strong >Connexion</strong ></a></div>
                <?= $this->Form->end(); ?>
            </div>

        </div>
    </div>
</div>

<?=$this->Html->scriptStart(['block' => true]) ?>
    jQuery(function($) {

        //preview picture
        $('#picture').on('change', function (e) {
        var files = $(this)[0].files;
        if (files.length > 0) {
        // On part du principe qu'il n'y qu'un seul fichier
        // Ã©tant donnÃ© que l'on a pas renseignÃ© l'attribut "multiple"
        var file = files[0], $image_preview = $('#image_preview');

        // Ici on injecte les informations recoltÃ©es sur le fichier pour l'utilisateur
        $image_preview.find('.img-thumbnail').removeClass('hidden');
        $image_preview.find('img').attr('src', window.URL.createObjectURL(file));
        $image_preview.find('h4').html(file.name);
        $image_preview.find('h5').html(file.size +' bytes');
        }

        // Bouton "Annuler" pour vider le champ d'upload
        $image_preview.find('button[type="button"]').on('click', function (e) {
        e.preventDefault();
        $('#picture').val('');
        $image_preview.find('img').attr('src', 'http://localhost/GDS-1.0/img/person_1.jpg');
        $image_preview.find('h4').html(' ');
        $image_preview.find('h5').html(' ');
        });
        });
    });
<?= $this->Html->scriptEnd()?>
