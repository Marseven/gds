<div class="col-12">
	<div class="row align-items-center">
		<div class="col-lg-12 ml-auto form-box" data-aos="fade-up" data-aos-delay="500">
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-body">
							<h3>Traitement des données pour le DTS</h3>			
							<div class="carre">
								<?= $this->Form->create('one_import', ['url' => ['action' => 'assainir']]); ?>
									<div class="form-group">
										<label for="exampleInputFile">Charger le fichier</label>
										<input type="file" name="file" id="file" size="150" disabled>
										<p class="help-block">Seul les fichiers Excel/CSV sont importés.</p>
									</div>
									<button type="submit" class="btn btn-primary" name="Assainir" >Assainir</button>
								<?= $this->Form->end(); ?>
							</div>
						</div>	
					</div>				
				</div>			
			</div>
		</div>
	</div>	
</div>