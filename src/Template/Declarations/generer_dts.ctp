<div class="col-12">
	<div class="row align-items-center">
		<div class="col-lg-12 ml-auto form-box" data-aos="fade-up" data-aos-delay="500">
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-body">
							<h3>Générer le DTS</h3>
							<div class="carre">
								<?= $this->Form->create('final_data', ['url' => ['action' => 'genererDts']]); ?>
									<div class="form-group">
										<label for="exampleInputFile">Charger le fichier</label>
										<input type="file" name="file" id="file" size="150" disabled>
										<p class="help-block">Seul les fichiers Excel/CSV sont importés.</p>
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
                                                    'required',
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
                                                    'required',
                                                )); ?>
                                            </div>
                                        </div>
                                    </div>
									<button type="submit" class="btn btn-primary" name="Generer" >Générer</button>
								<?= $this->Form->end(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
