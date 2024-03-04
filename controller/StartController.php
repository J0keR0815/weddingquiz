<?php
	require_once("controller/MainController.php");
	require_once("controller/ViewController.php");
	
	require_once("view/ViewType.php");
	
	/**
	 * Der StartController steuert den Informationsfluss über die
	 * Startoberfläche "StartView". Er wird durch den
	 * MainController zu Beginn der Webapplikation geladen.
	 */
	class StartController extends ViewController {
		/**
		 * Der StartController prüft, ob $_GET["submit"] belegt
		 * wurde. Ist dies nicht der Fall, wurde die Seite noch
		 * nicht aufgerufen und die Startseite wird angezeigt.
		 * Wurde die Seite bereits aufgerufen und der Start-Button
		 * getätigt, dann ist $_GET["submit"] = "Register". In
		 * diesem Fall wird die Variable
		 * $_SESSION["viewType"] = "Register" gesetzt und der
		 * MainController erneut ausgeführt.
		 *
		 * {@inheritDoc}
		 * @see Controller::run()
		 */
		public function run() {
			$data = NULL;
			if (
				isset($_GET["submit"]) &&
				$_GET["submit"] ==
					ViewType::getName(ViewType::REGISTER)
			) {
				/*
				 * Seite wurde bereits aufgerufen und Start-Button
				 * wurde betätigt.
				 */
				$data = array();
				$data["action"] = "submit";
				$this->session->setEntry(
					"viewType",
					ViewType::getName(ViewType::REGISTER)
				);
				MainController::getInstance()->run();
			} else {
				// Seite wird erstmals aufgerufen
				$this->view->display($data);
			}
		}
	}
?>
