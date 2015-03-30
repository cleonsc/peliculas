var particular = {
	sqlContainer: new Array(),
	init: function() {
		this.toggleSecciones();
		this.ocultarTablas();
		this.inicializarPopup();
	},
	ocultarTablas: function() {
		$('.contenedor_general').hide();
	},
	toggleSecciones: function() {
		$('.subtitulo').click(function() {
			$(this).parent().find('.contenedor_general').toggle('blind');
		});
	},
	inicializarPopup: function() {
		$('.ver_detalle').click(function() {
			var configKey = $(this).parent().attr('id');
			var objectType = $(this).closest('table').attr('id');

			particular.mostrarPopup(configKey, objectType);
		});
	},
	mostrarPopup: function(configKey, objectType) {
		var titulo = "Detalle de ";
		var hasSource = false;
		var hasXmlSource = false;

		switch (objectType) {
			case 'table':
				titulo += "Tabla";
				break;
			case 'view':
				titulo += "Vista";
				hasSource = true;
				break;
			case 'trigger':
				titulo += "Trigger";
				hasSource = true;
				break;
			case 'package':
				titulo += "Paquete";
				hasSource = true;
				break;
			case 'procedure':
				titulo += "Procedimiento";
				hasSource = true;
				break;
			case 'function':
				titulo += "Funcion";
				hasSource = true;
				break;
			case 'job':
				titulo += "Job";
				hasSource = true;
				break;
			case 'sequence':
				titulo += "Secuencia";
				break;
			case 'web_service':
				titulo += "Web Service";
				hasXmlSource = true;
				break;
		}

		particular.setCursor(true);
		$.ajax({
			url: 'backend/libraries/checksystem/controllers/detallado.php',
			type: 'POST',
			data: {
				key: configKey,
				type: objectType,
				rand: Math.random()
			},
			success: function(data) {
				$('html, body').addClass('stop-scrolling');
				var popupDialog = $('<section>' + data + '</section>', {id: 'popupDialog'}).dialog({
					title: titulo,
					width: '950',
					height: '710',
					modal: true,
					resizable: false,
					position: 'top',
					close: function() {
						$('html, body').removeClass('stop-scrolling');
						popupDialog.remove();
					},
					buttons: [
						{
							title: 'Cerrar',
							text: 'Cerrar',
							click: function() {
								popupDialog.dialog('close');
							}
						}
					]
				});

				particular.toggleContenedorDetalle();
				$('.ui-widget-overlay').click(function() {
					popupDialog.dialog('close');
				});

				if (hasXmlSource) {
					particular.agregarContenedorSource('contenedorXml', 'xml');
				}

				if (hasSource) {
					particular.agregarContenedorSource('contenedorSql');

					if (objectType == 'package') {
						particular.agregarContenedorSource('packageBodySql');
					}
				}

				particular.setCursor(false);
			},
			dataType: 'html'
		});
	},
	agregarContenedorSource: function(idTextArea, tipo) {
		var mimeType = 'text/x-mysql';

		if (tipo != undefined) {
			switch (tipo) {
				case 'xml':
					mimeType = 'application/xml';
					break;

				case 'html':
					mimeType = 'text/html';
					break;
			}
		}

		var editor = CodeMirror.fromTextArea(document.getElementById(idTextArea), {
			mode: mimeType,
			indentWithTabs: true,
			smartIndent: true,
			lineNumbers: true,
			matchBrackets: true,
			autofocus: true,
			readOnly: true,
			height: 1050
		});
		$('.CodeMirror').resizable({
			handles: 's',
			resize: function() {
				editor.setSize($(this).width(), $(this).height());
			}
		});
	},
	setCursor: function(busy) {
		if (busy) {
			$('*').addClass('busy');
		} else {
			$('*').removeClass('busy');
		}
	},
	toggleContenedorDetalle: function() {
		$('.subtitulo_detalle').click(function() {
			var contenidoDetalle = $(this).parent().find('.seccion_detalle');
			$(this).toggleClass('detalle_active');

			contenidoDetalle.toggle('blind');
		});
	}
};

$(document).ready(function() {
	particular.init();
});