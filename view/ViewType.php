<?php
	require_once("model/utils/Enumeration.php");
	
	/**
	 * Die abstrakte Klasse ViewType bildet ein Enumeration nach.
	 * Hierin werden die verfügbaren Oberflächentypen gespeichert.
	 * Dazu wird ein Array mit den verfügbaren Namen angeboten und
	 * dazugehörige Konstanten, deren Werte die korrespondieren
	 * Array-Indizes bilden, um auf die Namen zuzugreifen.
	 */
	abstract class ViewType extends Enumeration {
		/**
		 * Das konstante Array NAMES enthält alle verfügbaren
		 * Oberflächen-Namen. Diese Namen sind äquivalent zu den
		 * zu verwendenen Werten von $_SESSION["viewType"].
		 * Um den Oberflächen-Namen zu erhalten muss der
		 * Array-Wert nur mit dem String "View" konkateniert
		 * werden. Auch die Oberflächen-Controller-Namen stimmen
		 * hiermit überein. Anschließend muss das Resultat dann
		 * mit dem String "Controller" konkateniert werden.
		 * 
		 * @var unknown
		 */
		const NAMES = array(
			"Start",
			"Register",
			"Quiz",
			"Finish",
			"Score",
		);
		
		const START = 0;
		const REGISTER = 1;
		const QUIZ = 2;
		const FINISH = 3;
		const SCORE = 4;
	}
?>
