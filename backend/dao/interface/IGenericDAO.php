<?php

/**
 *
 * @author u61851
 */
interface IGenericDAO {
	public function startTransaction();
	public function endTransaction();
	public function rollbackTransaction();
	public function autoIncrement($sec);
}
?>
