<?php
	require_once("controller/Controller.php");
	
	require_once("test/TestType.php");
	
	/**
	 * Die Klasse TestController erbt von der Klasse Controller und
	 * dient der Durchführung von verschiedenen Tests. Hierzu
	 * werden im Enumeration TestType die verschiedenen
	 * Testbezeichner im Array TestType::NAMES gespeichert.
	 */
	class TestController extends Controller {
		/**
		 * Wurde der URL der Parameter "test" übergeben, überprüft
		 * die run()-Methode, ob $_GET["test"] einen gültigen
		 * Testbezeichner enthält. Ist dies der Fall, wird der
		 * Test ausgeführt.
		 * 
		 * {@inheritDoc}
		 * @see Controller::run()
		 */
		public function run() {
			if (TestType::contains($_GET["test"])) {
				require("test/test" . $_GET["test"] . ".php");
			}
		}
	}
?>
