<?php
/**
 * AppShell file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		http://cakephp.org CakePHP(tm) Project
 * @since		CakePHP(tm) v 2.0
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Shell', 'Console');
App::uses('MeasurementsController', 'Controller');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package	   app.Console.Command
 */
class MeasurementShell extends AppShell {
	public $uses = array("Measurement");
	public function main() {
		$this->out('Hello world.');
	}

	public function saveData()
	{
		if(!isset($this->args[0]))
			return $this->out("No File parameter passed along, aborting");
		$measurement = new MeasurementsController();
		$measurement->saveData($this->args[0]);
	}

	public function storeMMFromDBToFile()
	{
		echo "Starting conversion of measurement from database to files\n";
		$this->startTime = microtime(true);
		$measurement = new MeasurementsController();
		for($i=0;$i<10000;$i++){
			echo $measurement->_storeMMFromDBToFile($i)."\n";
		}
		$now = microtime(true)-$this->startTime;
		echo "Full import took ".sprintf("%1.2f seconds",$now)."\n";
	}

	public function cacheMeasurement()
	{
		if(!isset($this->args[0]))
			return $this->out("No File parameter passed along, aborting");
		$measurement = new MeasurementsController();
		$measurement->cacheMeasurement($this->args[0]);
	}
}
