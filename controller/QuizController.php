<?php
	require_once("controller/MainController.php");
	require_once("controller/ViewController.php");

	require_once("view/ViewType.php");
	require_once("view/View.php");
	
	require_once("model/quiz/Question.php");
	require_once("model/quiz/QuestionStatType.php");
	
	/**
	 * Der QuizController steuert den Informationsfluss über
	 * die Quizoberfläche "QuizView". Er wird aufgerufen,
	 * nachdem in der Anmeldeoberfläche der Button "Zum Quiz"
	 * betätigt wurde.
	 */
	class QuizController extends ViewController {
		/**
		 * This static value describes the number of questions.
		 */
		public static $count = 10;
		
		/**
		 *
		 */
		private $question;

		/**
		 *
		 */
		public function __construct(View $view) {
			parent::__construct($view);
			$player = MainController::getInstance()->getPlayer();
			$this->question = $player->getNextQuestion();
			if (!$this->question) {
				try {
					$questions = Question::initQuestions(
						self::$count, $player->getLang()
					);
					$player->setQuestions($questions);
					$this->question = $player->getNextQuestion();
					$this->session->setEntry("qnr", 1);
				} catch (InvalidArgumentException $e) {
					$this->session->destroy();
					exit($e->getMessage());
				} catch (BadFunctionCallException $e) {
					$this->session->destroy();
					exit($e->getMessage());
				} catch (RuntimeException $e) {
					$this->session->destroy();
					exit($e->getMessage());
				}
			}
		}
		
		/**
		 *
		 * {@inheritDoc}
		 * @see Controller::run()
		 */
		public function run() {
			$data = array();
			if (
				isset($_POST["submit"]) &&
				$_POST["submit"] == "submit" &&
				$this->question instanceof Question
			) {
				$player =
					MainController::getInstance()->getPlayer();
				// Seite wurde bereits geladen
				if (isset($_POST["option"])) {
					try {
						$this->question->setStat($_POST["option"]);
					} catch (InvalidArgumentException $e) {
						$this->session->destroy();
						exit($e->getMessage());
					}

					$this->session->setEntry(
						$this->question->getFilename(),
						$_POST["option"]
					);

					$nr = $this->session->getEntry("qnr");
					$this->session->setEntry("qnr", ++$nr);
					
					$this->question = $player->getNextQuestion();					
				} else {
					$data["error"] = createUsrWarning(
						QUIZ_ERROR_NOOPT
					);
				}
				
				if (
					is_numeric($this->question) &&
					$this->question == -1
				) {
					$this->session->setEntry(
						"viewType",
						ViewType::getName(ViewType::FINISH)
					);
					MainController::getInstance()->run();
				} else {
					// Zeige nächste Frage
					$data["question"] = $this->question;
					$data["qnr"] = $this->session->getEntry("qnr");
					$this->view->display($data);
				}
			} else {
				// Start: Show this question
				try {
					$data["question"] = $this->question;
					$data["qnr"] = $this->session->getEntry("qnr");
					$this->view->display($data);
				} catch (InvalidArgumentException $e) {
					$this->session->destroy();
					exit($e->getMessage());
				}
			}
		}
	}
?>
