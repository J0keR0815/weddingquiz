<?php
	require_once("controller/Controller.php");
	
	require_once("controller/TestController.php");
	
	require_once("controller/StartController.php");
	require_once("controller/RegisterController.php");
	require_once("controller/QuizController.php");
	require_once("controller/FinishController.php");
	require_once("controller/ScoreController.php");
	
	require_once("view/ViewType.php");
	
	require_once("view/StartView.php");
	require_once("view/RegisterView.php");
	require_once("view/QuizView.php");
	require_once("view/FinishView.php");
	require_once("view/ScoreView.php");

	require_once("model/quiz/Player.php");

	require_once("model/utils/errorhandling.php");
	
	/**
	 * Der MainController wird als Singleton realisiert, da dieser
	 * nur einmalig zur Verfügung stehen soll.
	 * Er hat die Aufgabe den Programmfluss übergreifend zu
	 * steuern. Anhand der Belegung der $_SESSION-Variablen durch
	 * die untergeordneten Oberflächen-Controller, steuert der
	 * MainController den Aufruf der einzelnen
	 * Oberflächen-Controller.
	 */
	class MainController extends Controller {
		/**
		 * Die statische Variable $instance speichert die
		 * Singleton-Instanz der Klasse.
		 * 
		 * @var unknown
		 */
		private static $instance;
		
		/**
		 * Mit der statischen Variablen $isTestMode kann geprüft
		 * werden, ob das Programm unter Testbedingungen läuft
		 * oder unter operationellen Bedingungen. 
		 * 
		 * @return unknown
		 */
		private static $isTestMode = false;

		private $controller;
		
		/**
		 * Diese Variable speichert das Spielerobjekt.
		 */
		private $player;

		/**
		 * Der Konstruktor ruft zunächst den Basisklassenkonstruktor
		 * auf und erzeugt dann ein Objekt vom Typ Player. Diesem
		 * entnimmt er die Spracheinstellungen, um die passende
		 * Sprachdatei zu laden.
		 */
		public function __construct() {
			parent::__construct();
			if (
				!$this->session->__isset("viewType") &&
				self::isTestMode() &&
			   	isset($_GET["test"])) {
				/*
				 * Programm läuft im Testmodus:
				 * Es soll der Test ausgeführt werden, welcher
				 * mittels GET übergeben wurde.
				 */
				$this->controller = new TestController();
			} else if (
				!$this->session->__isset("viewType") &&
				isset($_GET["score"]) &&
				$_GET["score"] == "1"
			) {

				$viewType = ViewType::getName(ViewType::SCORE);
				$controller = $viewType . "Controller";
				$view = $viewType . "View";
				$this->controller = 
					new $controller(new $view(NULL));
			} else {
				try {
					$this->player = new Player();
				} catch (BadFunctionCallException $e) {
					$this->session->destroy();
					exit($e->getMessage());
				} catch (InvalidArgumentException $e) {
					$this->session->destroy();
					exit($e->getMessage());
				}
				
				// Laden der passenden Sprachdatei
				require(
					"lang/" . $this->player->getLang() . ".php"
				);
			}
		}
		
		/**
		 * Diese Methode liefert das Player-Objekt zurück.
		 */
		public function getPlayer() {
			return $this->player;
		}
		
		/**
		 * Über die Methode getInstance() wird die
		 * Singelton-Instanz der Klasse erzeugt, falls diese noch
		 * nicht vorhanden ist. Ansonsten wird die Instanz
		 * ausgegeben.
		 */
		public static function getInstance() {
			if (!isset(self::$instance)) {
				self::$instance = new self;
			}
				
			return self::$instance;
		}
		
		/**
		 * isTestMode() prüft, ob der Testmodus aktiviert ist.
		 */
		public static function isTestMode() {
			return self::$isTestMode;
		}
		
		/**
		 * Die Methode prüft, zunächst, ob das Programm im
		 * Testmodus läuft. Ist dies der Fall und wurde
		 * $_GET["test"] gesetzt, wird der TestController
		 * aufgerufen, um den über $_GET["test"] angegebenen Test
		 * durchzuführen.
		 * Läuft das Programm nicht im Testmodus oder ist kein Wert
		 * für $_GET["test"] gesetzt, wird überprüft, ob bereits
		 * ein Wert für $_SESSION["viewType"] existiert. Ist dies
		 * nicht der Fall wurde das Programm gerade gestartet und
		 * der StartController wird geladen.
		 * Wurde ein Wert für $_SESSION["viewType"] gesetzt, wird
		 * und gibt es einen entsprechenden View Type wird der
		 * dazugehörige Controller ausgeführt. Ist dies nicht der
		 * Fall wird das Programm mit einer Fehlermeldung beendet.
		 *
		 * {@inheritDoc}
		 * @see Controller::run()
		 */
		public function run() {
			if (isset($this->controller) && $this->controller) {
				$this->controller->run();
				exit(0);
			}

			// Pass over data, if Player already exists.
			$data = NULL;
			if ($this->player->getName()) {
				$data = array(
					"username" => $this->player->getName()
				);
			}
			
			if (!$this->session->__isset("viewType")) {
				/*
				 * Es wurde noch kein View Type mittels GET
				 * übergeben:
				 * Das Programm wurde gestartet.
				 */
				$viewType = ViewType::getName(ViewType::START);
				$controller = $viewType . "Controller";
				$view = $viewType . "View";
				$this->session->setEntry("viewType", $viewType);

				(new $controller(new $view($data)))->run();
			} else if (
				$this->session->__isset("viewType") &&
				ViewType::contains(
					$this->session->getEntry("viewType")
				)
			) {
				/*
				 * Es wurde mittels GET ein View Type übergeben und
				 * dieser existiert:
				 * Der ViewController wird ausgeführt.
				 */
				$viewType = $this->session->getEntry("viewType");
				$controller = $viewType . "Controller";
				$view = $viewType . "View";
				(new $controller(new $view($data)))->run();
			} else {
				/*
				 * Dieser Fall darf nicht auftreten:
				 * Das Programm wird beendet.
				 */
				$errMsg = createErrStr(
					"Unknown view type \"" .
					$this->session->getEntry("viewType") .
					"\"",
					__FILE__,
					__LINE__
				);
				$this->session->destroy();
				exit($errMsg);
			}
		}
	}
?>
