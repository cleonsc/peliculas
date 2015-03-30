<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Procedimiento:</div>
			<input type="text" disabled="true" class="width_240" value="<?= $procedure->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Esquema:</div>
			<input type="text" disabled="true" class="width_270" value="<?= $procedure->getOwner() ?>"/>
		</div>
	</div>
</section>
<section class=contenedor" style="font-size: 1em;">
	<p class="subtitulo_detalle">SQL</p>
	<div class="seccion_detalle">
		<textarea id="contenedorSql"><?= $procedure->getSql() ?></textarea>
	</div>
</section>