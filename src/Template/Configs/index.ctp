
<div class="intro-section single-cover" id="home-section">
    <div class="slide-1 " style="background-image: url('./GDS/img/hero.JPG');" data-stellar-background-ratio="0.5">
        <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
            <div class="row justify-content-center align-items-center text-center">
                <div class="col-lg-6">
                <h1 data-aos="fade-up" data-aos-delay="0">Réglages</h1>
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
                <?= $this->Form->create($config, ['class' => 'form-box', 'id' => 'form-register']); ?>

                    <h3>Réglages</h3>

                    <?php
                        if(isset($config->id)){
                            $this->Form->control('id', array(
                                'type' => 'hidden',
                                'value' => $config->id
                            ));
                        }
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Matricule Employeur</label>
                                <?= $this->Form->control('matricule_employeur', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Matricule Employeur*',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                            <div class="form-group form-group-default">
                                <label>Raison Sociale</label>
                                <?= $this->Form->control('raison_social', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Raison Sociale*',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                            <div class="form-group form-group-default">
                                <label>Ville</label>
                                <?= $this->Form->control('ville', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Ville*',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Fax</label>
                                <?= $this->Form->control('fax', array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Fax',
                                    'type' => 'text',
                                    'label' => '',
                                )); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>B.P</label>
                                <?= $this->Form->control('bp', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'B.P',
                                    'label' => '',
                                    'required',
                                )); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Période</label>
                                <?= $this->Form->control('periode', array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Période',
                                    'type' => 'text',
                                    'label' => '',
                                )); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Année</label>
                                <?= $this->Form->control('annee', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Année',
                                    'label' => '',
                                )); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Régime</label>
                                <?= $this->Form->control('regime', array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Régime*',
                                    'type' => 'text',
                                    'label' => '',
                                    'required'
                                )); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Sigle</label>
                                <?= $this->Form->control('sigle', array(
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'placeholder' => 'Sigle',
                                    'label' => '',
                                )); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Montant Déduction Allocations Familiales</label>
                                <?= $this->Form->control('allocation', array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Allocations Familiales*',
                                    'type' => 'number',
                                    'label' => '',
                                )); ?>
                            </div>
                        </div>
                    </div>

                    <?= $this->Form->control('id_user', array(
                        'type' => 'hidden',
                        'value' => $user->id,
                    )); ?>

                    <div class="row m-t-10">
                    <div class="col-lg-6 text-left">
                        <?= $this->Form->control('Enregistrer', array(
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        )); ?>
                    </div>
                    </div>
                <?= $this->Form->end(); ?>
            </div>

        </div>

    </div>
</div>
