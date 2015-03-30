<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Secuencia:</div>
			<input type="text" disabled="true" class="width_223" value="<?= $sequence->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Esquema:</div>
			<input type="text" disabled="true" class="width_230" value="<?= $sequence->getOwner() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Valor M&iacute;nimo:</div>
			<input type="text" disabled="true" class="width_209" value="<?= $sequence->getMinValue() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Valor M&aacute;ximo:</div>
			<input type="text" disabled="true" class="width_205" value="<?= $sequence->getMaxValue() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Incremento:</div>
			<input type="text" disabled="true" class="width_218" value="<?= $sequence->getIncrementBy() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">&Uacute;ltimo Valor:</div>
			<input type="text" disabled="true" class="width_214" value="<?= $sequence->getLastNumber() ?>"/>
		</div>
	</div>
</section>