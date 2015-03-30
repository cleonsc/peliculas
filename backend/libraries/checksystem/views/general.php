<html>
	<head>
		<title>Checksystem <?= CheckSystemService::getSistema() ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link href="/backend/libraries/checksystem/style/checksystem.css" rel="stylesheet" type="text/css" />
		<link href="/backend/libraries/checksystem/style/jquery-ui.css" rel="stylesheet" type="text/css" />
		<link href="/backend/libraries/checksystem/style/jquery.modal.css" rel="stylesheet" type="text/css" />
		<link href="/backend/libraries/checksystem/style/codemirror.css" rel="stylesheet" type="text/css" />
		<script src="/backend/libraries/checksystem/js/libraries/jquery-1.8.2.min.js"></script>
		<script src="/backend/libraries/checksystem/js/libraries/jquery-ui.min.js"></script>
		<script src="/backend/libraries/checksystem/js/libraries/codemirror.js"></script>
		<script src="/backend/libraries/checksystem/js/libraries/sql.js"></script>
		<script src="/backend/libraries/checksystem/js/libraries/xml.js"></script>
		<script src="/backend/libraries/checksystem/js/checksystem.js"></script>
	</head>
	<body>

		<section id="vista_general">
			<div class="header">
				<div class="titulo_sistema">Checksystem (v<?= CHECKSYSTEM_VERSION ?>) - <?= CheckSystemService::getSistema() ?></div>
				<div class="checksystem_tile"></div>
			</div>
			<section class="contenedor">
				<div class="bullet float_l <?= (!empty($aErrores['connections']) ? 'red' : 'green') ?>"></div>
				<p class="subtitulo">Estado de Conexiones de DB</p>
				<div class="contenedor_general">
					<table>
						<thead>
							<tr class="combinada">
								<th width="150px">Esquema</th>
								<th>Estado</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($aEstado['connections'] as $connection) { ?>
								<tr class="<?= ($connection->getEstado() ? 'successMsg' : 'errorMsg') ?>">
									<td><?= $connection->getObjectName() ?></td>
									<td class="align_left"><?= ($connection->getEstado() ? 'OK' : $connection->getMensaje()) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</section>

			<?php if (!empty($aEstado['tables'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['tables']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Tablas</p>
					<div class="contenedor_general">
						<table id="table">
							<thead>
								<tr class="combinada">
									<th width="150px">Esquema</th>
									<th width="150px" class="align_left">Tabla</th>
									<th class="align_left">Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['tables'] as $tabla) { ?>
									<tr class="<?= ($tabla->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td><?= $tabla->getOwner() ?></td>
										<td class="align_left"><?= $tabla->getObjectName() ?></td>
										<td class="align_left"><?= ($tabla->getEstado() ? 'OK' : $tabla->getMensaje()) ?></td>
										<?php if ($mostrarDetallado) { ?>
											<?php if ($tabla->getEstado()) { ?>
												<td id="<?= $tabla->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['views'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['views']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Vistas</p>
					<div class="contenedor_general">
						<table id="view">
							<thead>
								<tr class="combinada">
									<th width="150px">Esquema</th>
									<th width="150px">Vista</th>
									<th class="align_left">Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['views'] as $view) { ?>
									<tr class="<?= ($view->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td><?= $view->getOwner() ?></td>
										<td class="align_left"><?= $view->getObjectName() ?></td>
										<td class="align_left"><?= ($view->getEstado() ? 'OK' : $view->getMensaje()) ?></td>
										<?php if ($mostrarDetallado) { ?>
											<?php if ($view->getEstado()) { ?>
												<td id="<?= $view->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['triggers'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['triggers']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Triggers</p>
					<div class="contenedor_general">
						<table id="trigger">
							<thead>
								<tr class="combinada">
									<th width="150px">Esquema</th>
									<th width="150px">Trigger</th>
									<th class="align_left">Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['triggers'] as $trigger) { ?>
									<tr class="<?= ($trigger->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td><?= $trigger->getOwner() ?></td>
										<td class="align_left"><?= $trigger->getObjectName() ?></td>
										<td class="align_left"><?= ($trigger->getEstado() ? 'OK' : $trigger->getMensaje()) ?></td>
										<?php if ($mostrarDetallado) { ?>
											<?php if ($trigger->getEstado()) { ?>
												<td id="<?= $trigger->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['dblinks'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['dblinks']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de DB Links</p>
					<div class="contenedor_general">
						<table>
							<thead>
								<tr class="combinada">
									<th width="150px">Nombre DB Link</th>
									<th width="150px">Nombre de Usuario</th>
									<th width="150px">Host</th>
									<th class="align_left">Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['dblinks'] as $dbLink) { ?>
									<tr class="<?= ($dbLink->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td><?= $dbLink->getObjectName() ?></td>
										<td><?= $dbLink->getUserName() ?></td>
										<td><?= $dbLink->getHost() ?></td>
										<td class="align_left"><?= ($dbLink->getEstado() ? 'OK' : $dbLink->getMensaje()) ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['packages'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['packages']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Paquetes</p>
					<div class="contenedor_general">
						<table id="package">
							<thead>
								<tr class="combinada">
									<th width="150px">Esquema</th>
									<th width="150px">Paquete</th>
									<th>Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['packages'] as $package) { ?>
									<tr class="<?= ($package->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td class="align_left"><?= $package->getOwner() ?></td>
										<td class="align_left"><?= $package->getObjectName() ?></td>
										<td class="align_left"><?= ($package->getEstado() ? 'OK' : $package->getMensaje()) ?></td>
										<?php if ($mostrarDetallado) { ?>
											<?php if ($package->getEstado()) { ?>
												<td id="<?= $package->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['procedures'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['procedures']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Procedimientos</p>
					<div class="contenedor_general">
						<table id="procedure">
							<thead>
								<tr class="combinada">
									<th width="150px">Esquema</th>
									<th width="150px">Procedimiento</th>
									<th>Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['procedures'] as $procedure) { ?>
									<tr class="<?= ($procedure->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td><?= $procedure->getOwner() ?></td>
										<td class="align_left"><?= $procedure->getObjectName() ?></td>
										<td class="align_left"><?= ($procedure->getEstado() ? 'OK' : $procedure->getMensaje()) ?></td>
										<?php if ($mostrarDetallado) { ?>
											<?php if ($procedure->getEstado()) { ?>
												<td id="<?= $procedure->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['sequences'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['sequences']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Secuencias</p>
					<div class="contenedor_general">
						<table id="sequence">
							<thead>
								<tr class="combinada">
									<th width="150px">Esquema</th>
									<th width="150px">Secuencia</th>
									<th>Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['sequences'] as $sequence) { ?>
									<tr class="<?= ($sequence->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td><?= $sequence->getOwner() ?></td>
										<td class="align_left"><?= $sequence->getObjectName() ?></td>
										<td class="align_left"><?= ($sequence->getEstado() ? 'OK' : $sequence->getMensaje()) ?></td>
										<?php if ($mostrarDetallado) { ?>
											<?php if ($sequence->getEstado()) { ?>
												<td id="<?= $sequence->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['functions'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['functions']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Funciones</p>
					<div class="contenedor_general">
						<table id="function">
							<thead>
								<tr class="combinada">
									<th width="150px">Esquema</th>
									<th width="150px">Funci&oacute;n</th>
									<th>Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['functions'] as $function) { ?>
									<tr class="<?= ($function->getEstado() ? 'successMsg' : 'errorMsg') ?>">
										<td><?= $function->getOwner() ?></td>
										<td class="align_left"><?= $function->getObjectName() ?></td>
										<td class="align_left"><?= ($function->getEstado() ? 'OK' : $function->getMensaje()) ?></td>
										<?php if ($mostrarDetallado) { ?>
											<?php if ($function->getEstado()) { ?>
												<td id="<?= $function->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										<?php } ?>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['synonyms'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['synonyms']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Sin&oacute;nimos de Tablas/Vistas</p>
					<div class="contenedor_general">
						<table>
							<thead>
								<tr class="combinada">
									<th width="150px">Sin&oacute;nimo</th>
									<th width="150px">Tabla</th>
									<th width="150px">Esquema</th>
									<th width="150px">DB Link</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['synonyms'] as $key => $objeto) { ?>
									<?php if (!$objeto) { ?>
										<tr class="errorMsg">
											<td colspan="5" class="align_left">[<?= strtoupper($key) ?>]: <?= $aErrores['synonyms'][$key] ?></td>
										<tr/>
									<?php } else { ?>
										<tr class="<?= ($objeto->getEstado() ? 'successMsg' : 'errorMsg') ?>">
											<td class="align_left"><?= $objeto->getObjectName() ?></td>
											<td class="align_left"><?= $objeto->getTableName() ?></td>
											<td><?= $objeto->getTableOwner() ?></td>
											<td><?= $objeto->getDbLinkName() ?></td>
											<td class="align_left"><?= ($objeto->getEstado() ? 'OK' : $objeto->getMensaje()) ?></td>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['source_synonyms'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['source_synonyms']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Sin&oacute;nimos de Packages/SP</p>
					<div class="contenedor_general">
						<table>
							<thead>
								<tr class="combinada">
									<th width="150px">Tipo</th>
									<th width="150px">Sin&oacute;nimo</th>
									<th width="150px">Tabla</th>
									<th width="150px">Esquema</th>
									<th width="150px">DB Link</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['source_synonyms'] as $tipoObject => $aObjetos) { ?>
									<?php foreach ($aObjetos as $key => $objeto) { ?>
										<?php if (!$objeto) { ?>
											<tr class="errorMsg">
												<td colspan="5" class="align_left">[<?= strtoupper($key) ?>]: <?= $aErrores['synonyms'][$key] ?></td>
											<tr/>
										<?php } else { ?>
											<tr class="<?= ($objeto->getEstado() ? 'successMsg' : 'errorMsg') ?>">
												<td><?= $tipoObject ?></td>
												<td><?= $objeto->getObjectName() ?></td>
												<td><?= $objeto->getTableName() ?></td>
												<td><?= $objeto->getTableOwner() ?></td>
												<td><?= $objeto->getDbLinkName() ?></td>
												<td class="align_left"><?= ($objeto->getEstado() ? 'OK' : $objeto->getMensaje()) ?></td>
											</tr>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['jobs'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['jobs']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Estado de Jobs</p>
					<div class="contenedor_general">
						<table id="job">
							<thead>
								<tr class="combinada">
									<th width="150px">C&oacute;digo Ejecutado</th>
									<th width="150px">Esquema</th>
									<th width="150px">&Uacute;ltima Ejecuci&oacute;n</th>
									<th>Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['jobs'] as $key => $job) { ?>
									<?php if (!$job) { ?>
										<tr class="errorMsg">
											<td colspan="5" class="align_left">[<?= strtoupper($key) ?>]: <?= $aErrores['jobs'][$key] ?></td>
										<tr/>
									<?php } else { ?>
										<tr class="<?= ($job->getEstado() ? 'successMsg' : 'errorMsg') ?>">
											<td><?= $job->getObjectName() ?></td>
											<td><?= $job->getOwner() ?></td>
											<td><?= $job->getLastDate() ?></td>
											<td class="align_left"><?= ($job->getEstado() ? 'OK' : $job->getMensaje()) ?></td>
											<?php if ($mostrarDetallado) { ?>
												<?php if ($job->getEstado()) { ?>
													<td id="<?= $job->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
												<?php } else { ?>
													<td></td>
												<?php } ?>
											<?php } ?>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aEstado['web_services'])) { ?>
				<section class="contenedor">
					<div class="bullet float_l <?= (!empty($aErrores['web_services']) ? 'red' : 'green') ?>"></div>
					<p class="subtitulo">Conectividad de Web Services</p>
					<div class="contenedor_general">
						<table id="web_service">
							<thead>
								<tr class="combinada">
									<th width="150px">Web Service</th>
									<th>Estado</th>
									<?php if ($mostrarDetallado) { ?>
										<th></th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($aEstado['web_services'] as $key => $webService) { ?>
									<?php if (!$webService) { ?>
										<tr class="errorMsg">
											<td colspan="5" class="align_left">[<?= strtoupper($key) ?>]: <?= $aErrores['jobs'][$key] ?></td>
										<tr/>
									<?php } else { ?>
										<tr class="<?= ($webService->getEstado() ? 'successMsg' : 'errorMsg') ?>">
											<td><?= $webService->getObjectName() ?></td>
											<td class="align_left"><?= ($webService->getEstado() ? 'OK' : $webService->getMensaje()) ?></td>
											<?php if ($mostrarDetallado) { ?>
												<?php if ($webService->getEstado()) { ?>
													<td id="<?= $webService->getConfigKey() ?>"><div class="btnDetalle ver_detalle"></div></td>
												<?php } else { ?>
													<td></td>
												<?php } ?>
											<?php } ?>
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</section>
			<?php } ?>

			<?php if (!empty($aModulosExternos)) { ?>
				<section class="contenedor">
					<div class="bullet float_l"></div>
					<p class="subtitulo">M&oacute;dulos Externos</p>
					<div class="contenedor_externals">
						<?php foreach ($aModulosExternos as $urlModulo) { ?>
							<iframe src="<?= $urlModulo ?>" style="width: 100%; height: 150px; border: none;"></iframe>
						<?php } ?>
					</div>
				</section>
			<?php } ?>
		</section>

		<div class="hidden"><?php var_dump($aErrores); ?></div>
	</body>
</html>