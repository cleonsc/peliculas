<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Web Service:</div>
			<input type="text" disabled="true" class="width_185" value="<?= $webService->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">URL:</div>
			<input type="text" disabled="true" class="width_500" value="<?= $webService->getUrl() ?>"/>
		</div>
	</div>
</section>
<section class="contenedor" style="font-size: 1em;">
	<p class="subtitulo_detalle">WSDL</p>
	<div class="seccion_detalle">
		<textarea id="contenedorXml"><?= $webService->getWsdl() ?></textarea>
	</div>
</section>
<section class="contenedor">
	<p class="subtitulo_detalle">M&eacute;todos Publicados</p>
	<div class="seccion_detalle">
		<table>
			<tbody>
				<?php foreach ($webService->getMethods() as $method) { ?>
				<tr class="align_left">
					<td><?=$method?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</section>