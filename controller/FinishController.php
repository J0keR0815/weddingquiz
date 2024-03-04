<?php
	require_once("controller/MainController.php");
	require_once("controller/ViewController.php");

	require_once("view/ViewType.php");
	require_once("view/View.php");
	
	/**
	 * Der FinishController steuert den Informationsfluss über
	 * die Abschlussoberfläche "FinishView". Er wird aufgerufen,
	 * nachdem alle Fragen beantwortet wurden.
	 */
	class FinishController extends ViewController {
		/**
		 *
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
			if (
				isset($_GET["exit"]) &&
				$_GET["exit"] == "exit"
			) {
				$this->view->display();
				$this->session->destroy();
				exit(0);
			} else {
				$player =
				   MainController::getInstance()->getPlayer();
				$questions = $player->getQuestions();
				$data["questions"] = $questions;
				try {
					$player->save();
				} catch (RuntimeException $e) {
					$this->session->destroy();
					exit($e->getMessage());
				}
				$this->view->display($data);
			}
		}
	}
?>
