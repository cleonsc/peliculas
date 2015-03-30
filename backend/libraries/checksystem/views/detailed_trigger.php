<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Trigger:</div>
			<input type="text" disabled="true" class="width_284" value="<?= $trigger->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Esquema:</div>
			<input type="text" disabled="true" class="width_272" value="<?= $trigger->getOwner() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Tabla Afectada:</div>
			<input type="text" disabled="true" class="width_240" value="<?= $trigger->getAffectedTable() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Evento:</div>
			<input type="text" disabled="true" class="width_287" value="<?= $trigger->getTriggerType() ?> <?= $trigger->getTriggeringEvent() ?>"/>
		</div>
	</div>
</section>
<section class="contenedor" style="font-size: 1em;">
	<p class="subtitulo_detalle">SQL</p>
	<div class="seccion_detalle">
		<textarea id="contenedorSql"><?= $trigger->getTriggerSql() ?></textarea>
	</div>
</section>