<section class="contenedor">
	<p class="subtitulo_detalle">General</p>
	<div class="seccion_detalle">
		<div class="form-row">
			<div class="placeholder">Tabla:</div>
			<input type="text" disabled="true" class="width_240" value="<?= $table->getObjectName() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Esquema:</div>
			<input type="text" disabled="true" class="width_218" value="<?= $table->getOwner() ?>"/>
		</div>
		<div class="form-row">
			<div class="placeholder">Cantidad de Registros:</div>
			<input type="text" disabled="true" class="width_146" value="<?= $table->getRecordCount() ?>"/>
		</div>
	</div>
</section>
<section class="contenedor">
	<p class="subtitulo_detalle">Detalle de Columnas</p>
	<div class="seccion_detalle">
		<table>
			<thead>
				<tr class="combinada">
					<th width="150px">Nombre</th>
					<th>Data Type</th>
					<th>Data Length</th>
					<th>Nullable</th>
					<th>Default Value</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($aColumnas as $columna) { ?>
					<tr>
						<td class="align_left"><?= $columna->getColumnName() ?></td>
						<td class="align_center"><?= $columna->getDataType() ?></td>
						<td class="align_center"><?= $columna->getDataLength() ?></td>
						<td class="align_center"><?= ($columna->getIsNullable() ? 'S' : 'N') ?></td>
						<td class="align_center"><?= $columna->getDefaultValue() ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</section>