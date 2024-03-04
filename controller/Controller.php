<?php
	require_once("model/utils/Session.php");
	
	/**
	 * Die Klasse Controller dient als Basisklasse für alle
	 * weiteren Controller-Subklassen. Diese ist abstrakt, da von
	 * ihr keine Instanzen erzeugt werden sollen.
	 * Beim Erzeugen der Instanzen der verschiedenen
	 * Controller-Subklassen wird durch den Aufruf der
	 * Konstruktoren immer auch der Konstruktor dieser Klasse
	 * aufgerufen. Hierbei wird jeweils die Singleton-Instanz der
	 * Klasse Session übergeben, sodass jeder Controller direkten
	 * Zugriff auf das Session-Objekt hat.
	 */
	abstract class Controller {
		/**
		 * Das Attribut $session speichert die Instanz der Klasse
		 * Session. Für Objekte der Controller-Subklassen wird der
		 * Zugriff gewährt.
		 * 
		 * @var unknown $session
		 */
		protected $session;
		
		/**
		 * Der Konstruktor belegt das Attribut $session, indem es
		 * auf die Singleton-Instanzen der Klasse Session zugreift.
		 * 
		 * @param View $view
		 */
		public function __construct() {
			$this->session = Session::getInstance();
		}
		
		/**
		 * Jede Controller-Subklasse muss eine run()-Methode
		 * implementieren, worüber die Kommunikation zwischen den
		 * einzelnen Programmmodulen gesteuert wird, für die der
		 * Subklassen-Controller verantwortlich ist.
		 * 
		 * Beispiel:
		 * Durch Aufrufen der Methode run() des LoginControllers
		 * wird ein Objekt der Klasse LoginView erzeugt, worüber
		 * dem Nutzer ein Anmeldefenster angezeigt wird.
		 * Der Nutzer gibt die Anmeldedaten in die
		 * Anmeldeoberfläche ein und bestätigt seine Eingabe.
		 * Die Daten werden durch die entsprechenden Modelklassen
		 * überprüft und das Ergebnis wird dem LoginController
		 * mitgteilt. Dieser leitet die Daten angepasst an die
		 * LoginView, welcher eine Ausgabe für den Nutzer erzeugt.
		 */
		abstract public function run();
	}
?>
