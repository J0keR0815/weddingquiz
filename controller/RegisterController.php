<?php
	require_once("controller/ViewController.php");

	require_once("view/ViewType.php");
	
	/**
	 * Der RegisterController steuert den Informationsfluss über
	 * die Anmeldeoberfläche "RegisterView". Er wird aufgerufen,
	 * nachdem in der Startoberfläche der Button "Start" betätigt
	 * wurde.
	 */
	class RegisterController extends ViewController {
		/**
		 * Zunächst prüft der RegisterController, ob er bereits
		 * aufgerufen wurde. Dies ist dann der Fall, wenn der
		 * Nutzer den Button "Zum Quiz" betätigt hat und dadurch
		 * $_POST["submit"] = "Quiz" ist.
		 * In diesem Fall muss der eingegebene Name gültig sein.
		 * Ist dies der Fall wird $_SESSION["viewType"] = "Quiz"
		 * gesetzt und der MainController benachrichtigt.
		 * Andernfalls wird eine Fehlermeldung ausgegeben.
		 * Wird der RegisterController erstmalig aufgerufen, wird
		 * nur die Seite zum Anmelden angezeigt.
		 *
		 * {@inheritDoc}
		 * @see Controller::run()
		 */
		public function run() {
			$data = NULL;
			if (
				isset($_POST["submit"]) &&
				$_POST["submit"] ==
					ViewType::getName(ViewType::QUIZ)
			) {
				/*
				 * Die Seite wurde bereits aufgerufen und der
				 * Button "Zum Quiz" wurde betätigt.
				 */
				if (
					isset($_POST["username"]) &&
					is_string($_POST["username"]) &&
					trim($_POST["username"]) != ""
				) {
					/*
					 * Der eingegebene Name ist gültig:
					 * Der Name wird an das Player-Objekt übergeben.
					 * Es wird $_SESSION["viewType"] = "Quiz"
					 * gesetzt und der MainController wird
					 * benachrichtigt.
					 */
					$this->session->setEntry(
						"viewType",
						ViewType::getName(ViewType::QUIZ)
					);
					$this->session->setEntry(
						"username", $_POST["username"]
					);
					$controller = MainController::getInstance();
					$controller->getPlayer()->setName(
						$_POST["username"]
					);
					$controller->run();
				} else {
					/*
					 * Der eingegebene Name ist ungültigt:
					 * Die Seite wird erneut mit einer Fehlermeldung
					 * angezeigt.
					 */
					$data = array();
					$data["error"] = createUsrWarning(
						REGISTER_ERROR_INVALID_NAME
					);
					$this->view->display($data);
				}
			} else {
				// Die Seite wird erstmalig aufgerufen.
				$this->view->display($data);
			}
		}
	}
?>
