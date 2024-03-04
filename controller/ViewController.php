<?php
	require_once("controller/Controller.php");
	
	require_once("view/View.php");
	
	/**
	 * Die Klasse ViewController erbt von der Klasse Controller und
	 * dient als Basisklasse für alle ViewController-Subklassen.
	 * Diese ist abstrakt, da von ihr keine Instanzen erzeugt
	 * werden sollen.
	 * Beim Erzeugen der Instanzen der verschiedenen
	 * ViewController-Subklassen wird durch den Aufruf der
	 * Konstruktoren immer auch der Konstruktor dieser Klasse
	 * aufgerufen.
	 * Hierbei werden jeweils die $view-Attribute vorbelegt.
	 */
	abstract class ViewController extends Controller {
		/**
		 * Das Attribut $view speichert die Instanz der Klasse
		 * View. Für Objekte der ViewController-Subklassen wird
		 * der Zugriff gewährt.
		 *
		 * @var unknown $view
		 */
		protected $view;
		
		/**
		 * Der Konstruktor ruft erst den Konstruktor der Klasse
		 * Controller auf. Anschließend, wird das Attribut $view
		 * durch ein Objekt der Klasse View belegt.
		 * 
		 * @param View $view
		 */
		public function __construct(View $view) {
			parent::__construct();
			$this->view = $view;
		}
	}
?>
