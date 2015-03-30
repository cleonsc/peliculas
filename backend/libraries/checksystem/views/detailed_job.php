<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Job:</div>
			<input type="text" disabled="true" class="width_213" value="<?= $job->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Esquema:</div>
			<input type="text" disabled="true" class="width_181" value="<?= $job->getOwner() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Job ID:</div>
			<input type="text" disabled="true" class="width_199" value="<?= $job->getJobId() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Intervalo:</div>
			<input type="text" disabled="true" class="width_185" value="<?= $job->getInterval() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">&Uacute;ltima Ejecuci&oacute;n:</div>
			<input type="text" disabled="true" class="width_140" value="<?= $job->getLastDate() ?> <?= $job->getLastSec()?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Pr&oacute;xima Ejecuci&oacute;n:</div>
			<input type="text" disabled="true" class="width_128" value="<?= $job->getNextDate() ?> <?= $job->getNextSec()?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Cantidad de Fallos:</div>
			<input type="text" disabled="true" class="width_131" value="<?= $job->getFailures() ?>"/>
		</div>
	</div>
</section>
<section class="contenedor" style="font-size: 1em;">
	<p class="subtitulo_detalle">SQL</p>
	<div class="seccion_detalle">
		<textarea id="contenedorSql"><?= $job->getJobSql() ?></textarea>
	</div>
</section>