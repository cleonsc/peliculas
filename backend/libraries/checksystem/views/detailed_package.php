<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Paquete:</div>
			<input type="text" disabled="true" class="width_185" value="<?= $package->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Esquema:</div>
			<input type="text" disabled="true" class="width_179" value="<?= $package->getOwner() ?>"/>
		</div>
	</div>
</section>
<section class="contenedor" style="font-size: 1em;">
	<p class="subtitulo_detalle">Header SQL</p>
	<div class="seccion_detalle">
		<textarea id="contenedorSql"><?= $package->getSql() ?></textarea>
	</div>
</section>
<section class="contenedor" style="font-size: 1em;">
	<p class="subtitulo_detalle">Body SQL</p>
	<div class="seccion_detalle">
		<textarea id="packageBodySql"><?= $package->getPackageBody()->getSql(); ?></textarea>
	</div>
</section>