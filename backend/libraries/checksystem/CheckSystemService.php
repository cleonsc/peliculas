<?php

require_once('entities/DBConnection.php');
require_once('entities/Constraint.php');
require_once('entities/DatabaseLink.php');
require_once('entities/DBFunction.php');
require_once('entities/ForeignKey.php');
require_once('entities/Job.php');
require_once('entities/Package.php');
require_once('entities/Procedure.php');
require_once('entities/Sequence.php');
require_once('entities/SourceSynonym.php');
require_once('entities/Synonym.php');
require_once('entities/Table.php');
require_once('entities/Trigger.php');
require_once('entities/View.php');
require_once('entities/WebService.php');

class CheckSystemService {

	const ND_ESTADO_GLOBAL = 0;
	const ND_ESTADO_GENERAL = 1;
	const ND_ESTADO_DEALLADO = 2;

	private static $aConexiones = array(); // Array que contiene todas las conexiones
	private static $aFunctions = array();
	private static $aDatabaseLinks = array();
	private static $aJobs = array();
	private static $aPackages = array();
	private static $aProcedures = array();
	private static $aRemoteFunctions = array();
	private static $aSequences = array();
	private static $aSynonyms = array();
	private static $aTables = array();
	private static $aTriggers = array();
	private static $aViews = array();
	private static $aWebServices = array();
	public static $aMensajes = array();
	private static $oSelf = null;
	private static $config = array();

	private function __construct() {
		self::parseConfiguration();
	}

	/**
	 * Este es el pseudo constructor singleton
	 * Comprueba si la variable privada $_oSelf tiene un objeto
	 * de esta misma clase, si no lo tiene lo crea y lo guarda
	 * @static
	 * @param string Nombre del usuario a conectarse (Por defecto la constante NOMBRE del archivo conf.inc.php)
	 * @param string Clave de conexión. (Por defecto la constante PASSWORD del archivo conf.inc.php)
	 * @param string Nombre de la base de datos (Por defecto la constante BASE del archivo conf.inc.php)
	 * @param string Nombre del servidor (Por defecto la constante SERVIDOR del archivo conf.inc.php)
	 * @return resource
	 */
	public static function getInstance() {

		if (!self::$oSelf instanceof self) {
			self::$oSelf = new self(); //new self ejecuta __construct()
		}

		return self::$oSelf;
	}

	private static function parseConfiguration() {
		$checksystemConfigPath = DIR_CHECKSYSTEM_CONFIG . 'configCheckSystem.ini';

		if (file_exists($checksystemConfigPath)) {
			self::$config = parse_ini_file($checksystemConfigPath, true);
		} else {
			self::$aMensajes = "No se encontr&oacute; el archivo de configuraci&oacute;n del checksystem";
		}
	}

	/**
	 * Obtiene el nombre de sistema de la configuración y lo retorna
	 * @return string
	 */
	public static function getSistema() {
		return self::$config['Global']['sistema'];
	}

	/**
	 * Obtiene la lista de módulos externos para integrar al checksystem
	 * @return Array
	 */
	public static function getModulosExternos() {
		return self::$config['ModulosExternos'];
	}

	/**
	 * Chequea todas las conexiones especificadas en la configuración
	 * @return type
	 */
	public function checkAllConnections() {
		$aEstados = array();

		$aConnectionKeys = array_keys(self::$config['Connections']);
		foreach ($aConnectionKeys as $connectionKey) {
			$aEstados[$connectionKey] = $this->checkConnection($connectionKey);
		}

		return $aEstados;
	}

	/**
	 * Chequea la conexión especificada
	 * @param string $connectionKey
	 * @return boolean
	 */
	public function checkConnection($connectionKey) {
		if (!key_exists($connectionKey, self::$config['Connections'])) {
			self::$aMensajes = 'No existe la conexi&oacute;n solicitada';
		} else {
			// Si la conexión no está generada pero sí definida, la genera
			if (!key_exists($connectionKey, self::$aConexiones)) {
				$aConnectionConfig = $this->getConectionConfigValues($connectionKey);

				if (!is_array($aConnectionConfig)) {
					$dbConnection = new \Checksystem\DBConnection('', $connectionKey, '', $connectionKey);
					$dbConnection->setMensaje(self::$aMensajes['connections'][$connectionKey]);
					self::$aConexiones[$connectionKey] = $dbConnection;
					return $dbConnection;
				} else {
					$dbConnection = new \Checksystem\DBConnection($aConnectionConfig['dbString'], $aConnectionConfig['dbUser'], $aConnectionConfig['dbPassword'], $connectionKey);
					self::$aConexiones[$connectionKey] = $dbConnection;
				}
			}

			$estado = self::$aConexiones[$connectionKey]->chequear();

			if (!$estado) {
				self::$aMensajes['connections'][$connectionKey] = "Error en la conexi&oacute;n '{$connectionKey}' - " . self::$aConexiones[$connectionKey]->getMensaje();
			}

			return self::$aConexiones[$connectionKey];
		}
	}

	/**
	 * Obtiene los parámetros para la conexión de la configuración especificada
	 * @param string $connectionKey
	 * @return array o false si falló
	 */
	private function getConectionConfigValues($connectionKey) {
		$config_file = DIR_CHECKSYSTEM_CONFIG . 'configDB.ini';
		$buscarConexionVieja = false;

		$configKey = self::$config['Connections'][$connectionKey];

		// Si no existe configDB.ini, busca conexión vieja
		if (!file_exists($config_file)) {
			$buscarConexionVieja = true;
		} else {
			$configDB = parse_ini_file($config_file, true);

			// Si no existe la clave en el configDB, busca conexión vieja
			if (!key_exists($configKey, $configDB)) {
				$buscarConexionVieja = true;
			} else {
				$dbUser = $configDB[$configKey]['db_usr'];
				$dbPassword = $configDB[$configKey]['db_pass'];
				$dbString = $configDB[$configKey]['db_string'];
			}
		}

		if ($buscarConexionVieja) {
			if (!key_exists($configKey, self::$config['ConnPath'])) {
				self::$aMensajes['connections'][$connectionKey] = "No se pudo encontrar la ruta del archivo de la conexi&oacute;n '{$connectionKey}'";
				return false;
			}

			if (!key_exists($configKey, self::$config['ConnUser'])) {
				self::$aMensajes['connections'][$connectionKey] = "No se pudo encontrar el nombre de la variable del usuario de la conexi&oacute;n '{$connectionKey}'";
				return false;
			}

			if (!key_exists($configKey, self::$config['ConnPass'])) {
				self::$aMensajes['connections'][$connectionKey] = "No se pudo encontrar el nombre de la variable de la clave de la conexi&oacute;n '{$connectionKey}'";
				return false;
			}

			if (!key_exists($configKey, self::$config['ConnString'])) {
				self::$aMensajes['connections'][$connectionKey] = "No se pudo encontrar el nombre de la variable del db_string de la conexi&oacute;n '{$connectionKey}'";
				return false;
			}

			$dbPath = self::$config['ConnPath'][$configKey];
			$dbUserVar = self::$config['ConnUser'][$configKey];
			$dbPasswordVar = self::$config['ConnPass'][$configKey];
			$dbStringVar = self::$config['ConnString'][$configKey];

			if (!file_exists(ROOT_PATH . $dbPath)) {
				self::$aMensajes['connections'][$connectionKey] = "No se pudo acceder al archivo que contiene las variables de conexi&oacute;n '{$connectionKey}'";
				return false;
			} else {
				$data = file_get_contents(ROOT_PATH . $dbPath);

				$regExpComment = "/(\/\*(.|\n)*?\*\/)|(\/\/.*)/";
				$dataNoComments = preg_replace($regExpComment, '', $data);

				// Expresión regular para obtener el usuario y password
				$regExp = "/%s\s*\={1}\s*(\'|\")(.*)(\'|\")\s*\;/";
				// Expresión regular para obtener DB String
				$regexpDbStr = "/{$dbStringVar}\s*\={1}\s*(\'|\")(.[^\;]*)(\'|\")\s*\;/s";

				// Obtiene el usuario
				$matches = array();
				preg_match(sprintf($regExp, $dbUserVar), $dataNoComments, $matches);

				if (!empty($matches)) {
					$dbUser = $matches[2];
				} else {
					self::$aMensajes['connections'][$connectionKey] = "No se pudo acceder a la variable que contiene el usuario de la conexi&oacute;n '{$connectionKey}'";
					return false;
				}

				// Obtiene el password
				preg_match(sprintf($regExp, $dbPasswordVar), $dataNoComments, $matches);

				if (!empty($matches)) {
					$dbPassword = $matches[2];
				} else {
					self::$aMensajes['connections'][$connectionKey] = "No se pudo acceder a la variable que contiene el password de la conexi&oacute;n '{$connectionKey}'";
					return false;
				}

				// Obtiene el db_string
				preg_match($regexpDbStr, $dataNoComments, $matches);

				if (!empty($matches)) {
					$dbString = $matches[2];
				} else {
					self::$aMensajes['connections'][$connectionKey] = "No se pudo acceder a la variable que contiene el db_string de la conexi&oacute;n '{$connectionKey}'";
					return false;
				}
			}
		}

		return array('dbUser' => $dbUser, 'dbPassword' => $dbPassword, 'dbString' => $dbString);
	}

	/**
	 * Devuelve la conexion para el elemento de la configuracion solicitado
	 * @param type $objectKey
	 * @return \Checksystem\DBConnection
	 */
	private function getObjectConection($objectKey) {
		if (key_exists($objectKey, self::$config['Externals'])) {
			$connectionKey = self::$config['Externals'][$objectKey];

			if (!key_exists($connectionKey, self::$aConexiones)) {
				self::$aMensajes['general'] = "Error al obtener la conexi&oacute;n para el external '{$objectKey}', no existe ninguna conexi&oacute;n '{$connectionKey}'.";

				$dbConnection = new \Checksystem\DBConnection('', $connectionKey, '', $connectionKey);
				$dbConnection->setEstado(false);
				self::$aConexiones[$connectionKey] = $dbConnection;

				return $dbConnection;
			}
		} else {
			$connectionKey = self::$config['Global']['default_connection'];
		}

		return self::$aConexiones[$connectionKey];
	}

	/**
	 * Chequea todas las tablas especificadas en la configuracion sin detalle
	 * @return array
	 */
	public function checkAllTables($detallado = false) {
		$aEstados = array();

		$aTableKeys = array_keys(self::$config['Tables']);

		foreach ($aTableKeys as $tableKey) {
			$aEstados[$tableKey] = $this->checkTable($tableKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * Chequea la tabla especificada
	 * @param string $tableKey
	 * @return boolean
	 */
	public function checkTable($tableKey, $detallado = false) {
		if (!key_exists($tableKey, self::$config['Tables'])) {
			self::$aMensajes['tables'][$tableKey] = "No existe la tabla solicitada en la configuraci&oacute;n";
		} else {
			$tableName = self::$config['Tables'][$tableKey];

			// Chequea si el objeto de la tabla está generado, sino lo genera
			if (!key_exists($tableKey, self::$aTables)) {
				$dbConnection = $this->getObjectConection($tableKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['tables'][$tableKey] = "No se pudo encontrar la conexi&oacute;n externa para la tabla {$tableKey}";

					$table = new \Checksystem\Table($dbConnection, $tableName, $tableKey);
					$table->setMensaje(self::$aMensajes['tables'][$tableKey]);
					self::$aTables[$tableKey] = $table;

					return $table;
				}

				$table = new Checksystem\Table($dbConnection, $tableName, $tableKey);
				self::$aTables[$tableKey] = $table;
			}

			$table = self::$aTables[$tableKey];
			$table->chequear($detallado);

			if (!$table->getEstado()) {
				$mensaje = "Error en la tabla '{$tableName}' - " . $table->getMensaje();
				self::$aMensajes['tables'][$tableKey] = $mensaje;
			}

			return $table;
		}
	}

	/**
	 * Chequea todas las tablas especificadas en la configuracion sin detalle
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllViews($detallado = false) {
		$aEstados = array();

		$aViewKeys = array_keys(self::$config['Views']);

		foreach ($aViewKeys as $viewKey) {
			$aEstados[$viewKey] = $this->checkView($viewKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * 
	 * @param string $viewKey
	 * @param boolean $detallado
	 * @return boolean|Checksystem\View
	 */
	public function checkView($viewKey, $detallado = false) {
		if (!key_exists($viewKey, self::$config['Views'])) {
			self::$aMensajes['views'][$viewKey] = "No existe la vista solicitada en la configuraci&oacute;n";
		} else {
			$viewName = self::$config['Views'][$viewKey];

			// Chequea si el objeto de la tabla está generado, sino lo genera
			if (!key_exists($viewKey, self::$aViews)) {
				$dbConnection = $this->getObjectConection($viewKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['views'][$viewKey] = "No se pudo encontrar la conexi&oacute;n externa para la vista {$viewKey}";

					$view = new \Checksystem\View($dbConnection, $viewName, $viewKey);
					$view->setMensaje(self::$aMensajes['views'][$viewKey]);
					self::$aViews[$viewKey] = $view;

					return $view;
				}
				$view = new Checksystem\View($dbConnection, $viewName, $viewKey);
				self::$aViews[$viewKey] = $view;
			}
			$view = self::$aViews[$viewKey];
			$view->chequear($detallado);

			if (!$view->getEstado()) {
				self::$aMensajes['views'][$viewKey] = "Error en la vista '{$viewName}' - " . $view->getMensaje();
			}

			return $view;
		}
	}

	/**
	 * Chequea todos los triggers especificados en la configuración del checksystem
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllTriggers($detallado = false) {
		$aEstados = array();

		$aTriggerKeys = array_keys(self::$config['Triggers']);

		foreach ($aTriggerKeys as $triggerKey) {
			$aEstados[$triggerKey] = $this->checkTrigger($triggerKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * Chequea el trigger especificado
	 * @param string $triggerKey
	 * @return boolean|\Checksystem\Trigger
	 */
	public function checkTrigger($triggerKey, $detallado = false) {
		if (!key_exists($triggerKey, self::$config['Triggers'])) {
			self::$aMensajes['triggers'][$triggerKey] = "No existe el trigger solicitado en la configuraci&oacute;n";
		} else {
			$triggerName = self::$config['Triggers'][$triggerKey];

			// Chequea si el objeto de la tabla está generado, sino lo genera
			if (!key_exists($triggerKey, self::$aTriggers)) {
				$dbConnection = $this->getObjectConection($triggerKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['triggers'][$triggerKey] = "No se pudo encontrar la conexi&oacute;n externa para el trigger {$triggerKey}";

					$trigger = new \Checksystem\Trigger($dbConnection, $triggerName, $triggerKey);
					$trigger->setMensaje(self::$aMensajes['triggers'][$triggerKey]);
					self::$aTriggers[$triggerKey] = $trigger;

					return $trigger;
				}
				$trigger = new Checksystem\Trigger($dbConnection, $triggerName, $triggerKey);
				self::$aTriggers[$triggerKey] = $trigger;
			}
			$trigger = self::$aTriggers[$triggerKey];
			$trigger->chequear($detallado);

			if (!$trigger->getEstado()) {
				self::$aMensajes['triggers'][$triggerKey] = "Error en el trigger '{$triggerName}' - " . $trigger->getMensaje();
			}

			return $trigger;
		}
	}

	/**
	 * Chequea todos los procedimientos especificados en la configuración del checksystem
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllProcedures($detallado = false) {
		$aEstados = array();

		$aProcedureKeys = array_keys(self::$config['Procedures']);

		foreach ($aProcedureKeys as $functionKey) {
			$aEstados[$functionKey] = $this->checkProcedure($functionKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * Chequea el procedimiento especificado
	 * @param string $procedureKey
	 * @param boolean $detallado
	 * @return boolean|\Checksystem\Procedure
	 */
	public function checkProcedure($procedureKey, $detallado = false) {
		if (!key_exists($procedureKey, self::$config['Procedures'])) {
			self::$aMensajes['procedures'][$procedureKey] = "No existe la funci&oacute;n solicitada en la configuraci&oacute;n";
		} else {
			$procedureName = self::$config['Procedures'][$procedureKey];

			// Chequea si el objeto de la tabla está generado, sino lo genera
			if (!key_exists($procedureKey, self::$aProcedures)) {
				$dbConnection = $this->getObjectConection($procedureKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['procedures'][$procedureKey] = "No se pudo encontrar la conexi&oacute;n externa para el procedimiento {$procedureKey}";

					$function = new \Checksystem\DBFunction($dbConnection, $procedureName, $procedureKey);
					$function->setMensaje(self::$aMensajes['procedures'][$procedureKey]);
					self::$aProcedures[$procedureKey] = $function;

					return $function;
				}
				$procedure = new Checksystem\Procedure($dbConnection, $procedureName, $procedureKey);
				self::$aProcedures[$procedureKey] = $procedure;
			}

			$procedure = self::$aProcedures[$procedureKey];
			$procedure->chequear($detallado);

			if (!$procedure->getEstado()) {
				self::$aMensajes['procedures'][$procedureKey] = "Error en el procedure '{$procedureName}' - " . $procedure->getMensaje();
			}

			return $procedure;
		}
	}

	/**
	 * Chequea todas las funciones especificadas en la configuración del checksystem
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllFunctions($detallado = false) {
		$aEstados = array();

		$aFunctionKeys = array_keys(self::$config['Functions']);

		foreach ($aFunctionKeys as $functionKey) {
			$aEstados[$functionKey] = $this->checkFunction($functionKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * Chequea la función especificada
	 * @param string $functionKey
	 * @param boolean $detallado
	 * @return boolean|Checksystem\DBFunction
	 */
	public function checkFunction($functionKey, $detallado = false) {
		if (!key_exists($functionKey, self::$config['Functions'])) {
			self::$aMensajes['functions'][$functionKey] = "No existe la funci&oacute;n solicitada en la configuraci&oacute;n";
		} else {
			$functionName = self::$config['Functions'][$functionKey];

			// Chequea si el objeto de la tabla está generado, sino lo genera
			if (!key_exists($functionKey, self::$aFunctions)) {
				$dbConnection = $this->getObjectConection($functionKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['functions'][$functionKey] = "No se pudo encontrar la conexi&oacute;n externa para la funci&oacute;n {$functionKey}";

					$function = new \Checksystem\DBFunction($dbConnection, $functionName, $functionKey);
					$function->setMensaje(self::$aMensajes['functions'][$functionKey]);
					self::$aFunctions[$functionKey] = $function;

					return $function;
				}
				$function = new Checksystem\DBFunction($dbConnection, $functionName, $functionKey);
				self::$aFunctions[$functionKey] = $function;
			}

			$function = self::$aFunctions[$functionKey];
			$function->chequear($detallado);

			if (!$function->getEstado()) {
				self::$aMensajes['functions'][$functionKey] = "Error en la funci&oacute;n '{$functionName}' - " . $function->getMensaje();
			}

			return $function;
		}
	}

	/**
	 * Chequea todos los packages especificados en la configuración del checksystem
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllPackages($detallado = false) {
		$aEstados = array();

		$aPackageKeys = array_keys(self::$config['Packages']);

		foreach ($aPackageKeys as $packageKey) {
			$aEstados[$packageKey] = $this->checkPackage($packageKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * Chquea el package especificado
	 * @param string $packageKey
	 * @param boolean $detallado
	 * @return boolean|Checksystem\Package
	 */
	public function checkPackage($packageKey, $detallado = false) {
		if (!key_exists($packageKey, self::$config['Packages'])) {
			self::$aMensajes['packages'][$packageKey] = "No existe el package solicitado en la configuraci&oacute;n";
		} else {
			$packageName = self::$config['Packages'][$packageKey];

			// Chequea si el objeto de la tabla está generado, sino lo genera
			if (!key_exists($packageKey, self::$aPackages)) {
				$dbConnection = $this->getObjectConection($packageKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['packages'][$packageKey] = "No se pudo encontrar la conexi&oacute;n externa para el package {$packageKey}";

					$package = new \Checksystem\Package($dbConnection, $packageName, $packageKey);
					$package->setMensaje(self::$aMensajes['packages'][$packageKey]);
					self::$aPackages[$packageKey] = $package;

					return $package;
				}
				$package = new Checksystem\Package($dbConnection, $packageName, $packageKey);
				self::$aPackages[$packageKey] = $package;
			}

			$package = self::$aPackages[$packageKey];
			$package->chequear($detallado);

			if (!$package->getEstado()) {
				self::$aMensajes['packages'][$packageKey] = "Error en el package '{$packageName}' - " . $package->getMensaje();
			}

			return $package;
		}
	}

	/**
	 * Chequea todos los DB Links especificados en la configuración del checksystem
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllDatabaseLinks($detallado = false) {
		$aEstados = array();

		$aDatabaseLinkKeys = array_keys(self::$config['DatabaseLinks']);

		foreach ($aDatabaseLinkKeys as $databaseLinkKey) {
			$aEstados[$databaseLinkKey] = $this->checkDatabaseLink($databaseLinkKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * Chequea el DB Link especificado
	 * @param string $dbLinkKey
	 * @param boolean $detallado
	 * @return boolean||\Checksystem\DatabaseLink
	 */
	public function checkDatabaseLink($dbLinkKey, $detallado = false) {
		if (!key_exists($dbLinkKey, self::$config['DatabaseLinks'])) {
			self::$aMensajes['dblinks'][$dbLinkKey] = "No existe el DB Link solicitado en la configuraci&oacute;n";
		} else {
			$dbLinkName = self::$config['DatabaseLinks'][$dbLinkKey];

			// Chequea si el objeto del sinónimo está generado, sino lo genera
			if (!key_exists($dbLinkKey, self::$aDatabaseLinks)) {
				$dbConnection = $this->getObjectConection($dbLinkKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['dblinks'][$dbLinkKey] = "No se pudo encontrar la conexi&oacute;n externa para el DB Link {$dbLinkKey}";

					$dbLink = new \Checksystem\DatabaseLink($dbConnection, $dbLinkName, $dbLinkKey);
					$dbLink->setMensaje(self::$aMensajes['dblinks'][$dbLinkKey]);
					self::$aDatabaseLinks[$dbLinkKey] = $dbLink;

					return $dbLink;
				}
				$dbLink = new Checksystem\DatabaseLink($dbConnection, $dbLinkName, $dbLinkKey);
				self::$aDatabaseLinks[$dbLinkKey] = $dbLink;
			}

			$dbLink = self::$aDatabaseLinks[$dbLinkKey];
			$dbLink->chequear($detallado);

			if (!$dbLink->getEstado()) {
				self::$aMensajes['dblinks'][$dbLinkKey] = "Error en el DB Link '{$dbLinkName}' - " . $dbLink->getMensaje();
			}

			return $dbLink;
		}
	}

	/**
	 * Chequea todos los sinónimos especificados en la configuración del checksystem
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllSynonyms($detallado = false) {
		$aEstados = array();

		$aSynonymKeys = array_keys(self::$config['Synonyms']);

		foreach ($aSynonymKeys as $synonymKey) {
			$aEstados[$synonymKey] = $this->checkSynonym($synonymKey, $detallado);
		}

		return $aEstados;
	}

	/**
	 * Chequea el sinónimo especificado
	 * @param string $synonymKey
	 * @param boolean $detallado
	 * @return boolean|Checksystem\Synonym
	 */
	public function checkSynonym($synonymKey, $detallado = false) {
		if (!key_exists($synonymKey, self::$config['Synonyms'])) {
			self::$aMensajes['synonyms'][$synonymKey] = "No existe el sin&oacute;nimo solicitado en la configuraci&oacute;n";
		} else {
			$synonymName = self::$config['Synonyms'][$synonymKey];

			// Chequea si el objeto del sinónimo está generado, sino lo genera
			if (!key_exists($synonymKey, self::$aSynonyms)) {
				$dbConnection = $this->getObjectConection($synonymKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['synonyms'][$synonymKey] = "No se pudo obtener la conexi&oacute;n para el sin&oacute;nimo solicitado.";

					$synonym = new \Checksystem\Synonym($dbConnection, $synonymName, $synonymKey);
					$synonym->setMensaje(self::$aMensajes['synonyms'][$synonymKey]);
					self::$aSynonyms[$synonymKey] = $synonym;

					return $synonym;
				}
				$synonym = new Checksystem\Synonym($dbConnection, $synonymName, $synonymKey);
				self::$aSynonyms[$synonymKey] = $synonym;
			}

			$synonym = self::$aSynonyms[$synonymKey];
			$synonym->chequear($detallado);

			if (!$synonym->getEstado()) {
				self::$aMensajes['synonyms'][$synonymKey] = "Error en el sin&oacute;nimo '{$synonymName}' - " . $synonym->getMensaje();
			}

			return $synonym;
		}
	}

	public function checkAllSourceSynonyms($detallado = false) {
		$aEstados = array();

		$aSynonymKeys = array('PACKAGE' => array_keys(self::$config['PackageSynonyms']),
			'PROCEDURE' => array_keys(self::$config['ProcedureSynonyms']),
			'FUNCTION' => array_keys(self::$config['FunctionSynonyms']));

		foreach ($aSynonymKeys as $sourceType => $aObjects) {
			foreach ($aObjects as $synonymKey) {
				$aEstados[$sourceType][$synonymKey] = $this->checkSourceSynonym($synonymKey, $sourceType, $detallado);
			}
		}


		return $aEstados;
	}

	public function checkSourceSynonym($synonymKey, $sourceType = '', $detallado = false) {
		switch ($sourceType) {
			case 'PACKAGE':
				$configKey = 'PackageSynonyms';
				$msgKey = 'packages';
				break;
			case 'PROCEDURE':
				$configKey = 'ProcedureSynonyms';
				$msgKey = 'procedures';
				break;
			case 'FUNCTION':
				$configKey = 'FunctionSynonyms';
				$msgKey = 'functions';
				break;
		}

		if (!key_exists($synonymKey, self::$config[$configKey])) {
			self::$aMensajes['source_synonyms'][$msgKey][$synonymKey] = "No existe el sin&oacute;nimo solicitado en la configuraci&oacute;n";
		} else {
			$synonymName = self::$config[$configKey][$synonymKey];

			// Chequea si el objeto del sinónimo está generado, sino lo genera
			if (!key_exists($synonymKey, self::$aSynonyms)) {
				$dbConnection = $this->getObjectConection($synonymKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['source_synonyms'][$msgKey][$synonymKey] = "No se pudo obtener la conexi&oacute;n para el sin&oacute;nimo solicitado.";

					$synonym = new \Checksystem\SourceSynonym($dbConnection, $synonymName, $synonymKey, $sourceType);
					$synonym->setMensaje(self::$aMensajes['synonyms'][$synonymKey]);
					self::$aSynonyms[$synonymKey] = $synonym;

					return $synonym;
				}
				$synonym = new Checksystem\SourceSynonym($dbConnection, $synonymName, $synonymKey, $sourceType);
				self::$aSynonyms[$synonymKey] = $synonym;
			}

			$synonym = self::$aSynonyms[$synonymKey];
			$synonym->chequear($detallado);

			if (!$synonym->getEstado()) {
				self::$aMensajes['source_synonyms'][$msgKey][$synonymKey] = "Error en el sin&oacute;nimo '{$synonymName}' - " . $synonym->getMensaje();
			}

			return $synonym;
		}
	}

	/**
	 * 
	 * @param boolean $detallado
	 * @return Checksystem\Job
	 */
	public function checkAllJobs($detallado = false) {
		$aEstados = array();

		$aJobKeys = array_keys(self::$config['Jobs']);

		foreach ($aJobKeys as $jobKey) {
			$aEstados[$jobKey] = $this->checkJob($jobKey, $detallado);
		}

		return $aEstados;
	}

	public function checkJob($jobKey, $detallado = false) {
		if (!key_exists($jobKey, self::$config['Jobs'])) {
			self::$aMensajes['jobs'][$jobKey] = "No existe el job solicitado en la configuraci&oacute;n";
		} else {
			$jobName = self::$config['Jobs'][$jobKey];

			// Chequea si el objeto del sinónimo está generado, sino lo genera
			if (!key_exists($jobKey, self::$aJobs)) {
				$dbConnection = $this->getObjectConection($jobKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['jobs'][$jobKey] = "No se pudo obtener la conexi&oacute;n para el job solicitado.";

					$job = new \Checksystem\Job($dbConnection, $jobName, $jobKey);
					$job->setMensaje(self::$aMensajes['jobs'][$jobKey]);
					self::$aJobs[$jobKey] = $job;

					return $job;
				}
				$job = new Checksystem\Job($dbConnection, $jobName, $jobKey);
				self::$aJobs[$jobKey] = $job;
			}

			$job = self::$aJobs[$jobKey];
			$job->chequear($detallado);

			if (!$job->getEstado()) {
				self::$aMensajes['jobs'][$jobKey] = "Error en el job '{$jobName}' - " . $job->getMensaje();
			}

			return $job;
		}
	}

	/**
	 * Chequea todas las secuencias presentes en la configuración
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllSequences($detallado = false) {
		$aEstados = array();

		$aSequenceKeys = array_keys(self::$config['Sequences']);

		foreach ($aSequenceKeys as $sequenceKey) {
			$aEstados[$sequenceKey] = $this->checkSequence($sequenceKey, $detallado);
		}

		return $aEstados;
	}

	public function checkSequence($sequenceKey, $detallado = false) {
		if (!key_exists($sequenceKey, self::$config['Sequences'])) {
			self::$aMensajes['sequences'][$sequenceKey] = "No existe la secuencia solicitada en la configuraci&oacute;n";
		} else {
			$sequenceName = self::$config['Sequences'][$sequenceKey];

			// Chequea si el objeto del sinónimo está generado, sino lo genera
			if (!key_exists($sequenceKey, self::$aSequences)) {
				$dbConnection = $this->getObjectConection($sequenceKey);

				if (empty($dbConnection) || !$dbConnection->getEstado()) {
					self::$aMensajes['sequences'][$sequenceKey] = "No se pudo obtener la conexi&oacute;n para la secuencia solicitada.";

					$sequence = new \Checksystem\Sequence($dbConnection, $sequenceName, $sequenceKey);
					$sequence->setMensaje(self::$aMensajes['sequences'][$sequenceKey]);
					self::$aSequences[$sequenceKey] = $sequence;

					return $sequence;
				}
				$sequence = new \Checksystem\Sequence($dbConnection, $sequenceName, $sequenceKey);
				self::$aSequences[$sequenceKey] = $sequence;
			}

			$sequence = self::$aSequences[$sequenceKey];
			$sequence->chequear($detallado);

			if (!$sequence->getEstado()) {
				self::$aMensajes['sequences'][$sequenceKey] = "Error en la secuencia '{$sequenceName}' - " . $sequence->getMensaje();
			}

			return $sequence;
		}
	}

	/**
	 * Obtiene la URL de un web service de la configuración
	 * @param string $webServiceKey
	 * @return string|false
	 */
	public function getWebServiceUrl($webServiceKey) {
		$webServiceName = self::$config['WebServices'][$webServiceKey];
		$wsUrl = '';

		if (strtolower(substr($webServiceName, 0, 4)) == 'http') {
			$wsUrl = $webServiceName;
		} else {
			$config_file = DIR_CHECKSYSTEM_CONFIG . 'configWS.ini';

			if (!file_exists($config_file)) {
				self::$aMensajes['web_services'][$webServiceKey] = "No existe el archivo configWS.ini";
				return false;
			}

			$configWS = parse_ini_file($config_file, true);

			if (key_exists('server_url', $configWS)) {
				if (key_exists($webServiceName, $configWS['server_url'])) {
					$wsUrl = $configWS['server_url'][$webServiceName];
				} else {
					self::$aMensajes['web_services'][$webServiceKey] = "No existe el web service solicitado en la secci&oacute;n [server_url] del archivo configWS.ini";
					return false;
				}
			} else if (key_exists(strtolower($webServiceName), $configWS)) {
				$wsUrl = $configWS[$webServiceName]['wsdl'];
			} else {
				self::$aMensajes['web_services'][$webServiceKey] = "No existe el web service [{$webServiceKey}] en el archivo configWS.ini";
				return false;
			}
		}
		return $wsUrl;
	}

	/**
	 * Chequea todas las secuencias presentes en la configuración
	 * @param boolean $detallado
	 * @return array
	 */
	public function checkAllWebServices($detallado = false) {
		$aEstados = array();

		$aSequenceKeys = array_keys(self::$config['WebServices']);

		foreach ($aSequenceKeys as $webServiceKey) {
			$aEstados[$webServiceKey] = $this->checkWebService($webServiceKey, $detallado);
		}

		return $aEstados;
	}

	public function checkWebService($webServiceKey, $detallado = false) {
		if (!key_exists($webServiceKey, self::$config['WebServices'])) {
			self::$aMensajes['web_services'][$webServiceKey] = "No existe el web service solicitado en la configuraci&oacute;n";
		} else {
			$webServiceName = self::$config['WebServices'][$webServiceKey];

			// Chequea si el objeto del sinónimo está generado, sino lo genera
			if (!key_exists($webServiceKey, self::$aWebServices)) {
				$webServiceUrl = $this->getWebServiceUrl($webServiceKey);

				$webService = new \Checksystem\WebService($webServiceName, $webServiceKey, $webServiceUrl);
				self::$aWebServices[$webServiceKey] = $webService;
			}

			$webService = self::$aWebServices[$webServiceKey];
			$webService->chequear($detallado);

			if (!$webService->getEstado()) {
				self::$aMensajes['web_services'][$webServiceKey] = "Error en el web service '{$webServiceName}' - " . $webService->getMensaje();
			}

			return $webService;
		}
	}

	/**
	 * Obtiene todas las constraints para el esquema especificado
	 * @param string $connectionKey
	 * @return boolean|\Checksystem\Constraint
	 */
	public function getConstraints($connectionKey) {
		/* @var $dbConnection Checksystem\DBConnection */
		$dbConnection = self::$aConexiones[$connectionKey];
		$aResultado = array();

		$sql = "SELECT uc.constraint_name, uc.constraint_type, uc.table_name, ucc.column_name, uc.search_condition, uc.status
				FROM user_constraints uc
					INNER JOIN user_cons_columns ucc ON uc.constraint_name = ucc.constraint_name
				WHERE constraint_type IN ('P', 'U')
					AND uc.owner = UPPER(:v_owner)";

		$stmt = oci_parse($dbConnection->getConnection(), $sql);
		oci_bind_by_name($stmt, "v_owner", $dbConnection->getUser());

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			self::$aMensajes['general'] = "Error al obtener la lista de Constraints - {$e['message']}";
			return false;
		}

		while ($row = oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS)) {
			$constraint = new \Checksystem\Constraint();
			$constraint->setConstraintName($row['CONSTRAINT_NAME']);
			$constraint->setTableName($row['TABLE_NAME']);
			$constraint->setColumnName($row['COLUMN_NAME']);
			$constraint->setConstraintType($row['CONSTRAINT_TYPE']);
			$constraint->setStatus($row['STATUS']);

			$aResultado[] = $constraint;
		}

		return $aResultado;
	}

	/**
	 * Obtiene todas las FK del esquema especificado
	 * @param type $connectionKey
	 * @return boolean|\Checksystem\ForeignKey
	 */
	public function getForeignKeys($connectionKey) {
		/* @var $dbConnection Checksystem\DBConnection */
		$dbConnection = self::$aConexiones[$connectionKey];
		$aResultado = array();

		$sql = "SELECT uc.constraint_name, uc.table_name, ucc.column_name, uc.r_constraint_name, destino.table_name as r_table_name, destino.column_name as r_column_name, uc.status
					FROM user_constraints  uc
						INNER JOIN user_cons_columns destino on uc.r_constraint_name = destino.constraint_name 
						INNER JOIN user_cons_columns ucc on uc.constraint_name = ucc.constraint_name 
					WHERE uc.constraint_type = 'R' and uc.owner = UPPER(:v_owner)
					ORDER BY uc.table_name, uc.r_constraint_name, destino.table_name, destino.column_name";

		$stmt = oci_parse($dbConnection->getConnection(), $sql);
		oci_bind_by_name($stmt, "v_owner", $dbConnection->getUser());

		if (!@oci_execute($stmt)) {
			$e = oci_error($stmt);
			self::$aMensajes['general'] = "Error al obtener la lista de Foreign Keys - {$e['message']}";
			return false;
		}

		while ($row = oci_fetch_array($stmt, OCI_ASSOC | OCI_RETURN_NULLS)) {
			$foreignKey = new \Checksystem\ForeignKey();
			$foreignKey->setObjectName($row['CONSTRAINT_NAME']);
			$foreignKey->setTableName($row['TABLE_NAME']);
			$foreignKey->setColumnName($row['COLUMN_NAME']);
			$foreignKey->setReferenceTableName($row['R_TABLE_NAME']);
			$foreignKey->setReferenceColumnName($row['R_COLUMN_NAME']);
			$foreignKey->setReferenceColumnName($row['R_COLUMN_NAME']);
			$foreignKey->setStatus($row['STATUS']);

			$aResultado[] = $foreignKey;
		}

		return $aResultado;
	}

	public function __destruct() {
		if (self::$oSelf instanceof self) {
			self::$oSelf = null; //destruyo el objeto
		}
	}

	/**
	 * Realiza chequeos sobre todos los objetos disponibles
	 * @param type $nivelDetalle
	 * @return boolean|array
	 */
	public function checkAll($nivelDetalle = self::ND_ESTADO_GLOBAL) {
		$detallado = false;

		$aEstado['connections'] = $this->checkAllConnections();
		$aEstado['tables'] = $this->checkAllTables($detallado);
		$aEstado['views'] = $this->checkAllViews($detallado);
		$aEstado['triggers'] = $this->checkAllTriggers($detallado);
		$aEstado['procedures'] = $this->checkAllProcedures($detallado);
		$aEstado['functions'] = $this->checkAllFunctions($detallado);
		$aEstado['packages'] = $this->checkAllPackages($detallado);
		$aEstado['dblinks'] = $this->checkAllDatabaseLinks($detallado);
		$aEstado['synonyms'] = $this->checkAllSynonyms($detallado);
		$aEstado['source_synonyms'] = $this->checkAllSourceSynonyms($detallado);
		$aEstado['sequences'] = $this->checkAllSequences($detallado);
		$aEstado['jobs'] = $this->checkAllJobs($detallado);
		$aEstado['web_services'] = $this->checkAllWebServices($detallado);

		if ($nivelDetalle == self::ND_ESTADO_GLOBAL) {
			foreach ($aEstado as $key => $aCheckables) {
				if ($key != 'source_synonyms') {
					foreach ($aCheckables as $checkable) {
						if (!$checkable->getEstado()) {
							return false;
						}
					}
				} else {
					foreach ($aCheckables as $aCategoria) {
						foreach ($aCategoria as $checkable) {
							if (!$checkable->getEstado()) {
								return false;
							}
						}
					}
				}
			}
			return true;
		} else if ($nivelDetalle == self::ND_ESTADO_GENERAL) {
			return $aEstado;
		} else if ($nivelDetalle == self::ND_ESTADO_SOLO_ERRORES) {
			return self::$aMensajes;
		}
	}

}

?>