<?php
	require_once("controller/ViewController.php");

	require_once("view/ViewType.php");
	
	require_once("model/quiz/Player.php");
	
	/**
	 * Der ScoreController steuert den Informationsfluss über die
	 * Oberfläche zur Ausgabe der Spielstatistik "ScoreView".
	 */
	class ScoreController extends ViewController {
		/**		
		 * Der Konstruktor ruft den Konstruktor der Basisklasse
		 * Controller.
		 */
		public function __construct(View $view) {
			parent::__construct($view);
		}
		
		/**
		 * 
		 * {@inheritDoc}
		 * @see Controller::run()
		 */
		public function run() {
			try {
				$lang = Player::readLang();
				require("lang/" . $lang . ".php");
				$players = Player::evalScores();
				$data = array(
					"players" => $players
				);
				$this->view->display($data);
			} catch (RuntimeException $e) {
				exit($e->getMessage());
			}
		}
	}
?>
