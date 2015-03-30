<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Funci&oacute;n:</div>
			<input type="text" disabled="true" class="width_240" value="<?= $function->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Esquema:</div>
			<input type="text" disabled="true" class="width_231" value="<?= $function->getOwner() ?>"/>
		</div>
	</div>
</section>
<section class="contenedor" style="font-size: 1em;">
	<p class="subtitulo_detalle">SQL</p>
	<div class="seccion_detalle">
		<textarea id="contenedorSql"><?= $function->getSql() ?></textarea>
	</div>
</section>