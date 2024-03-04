<?php
	require_once("model/utils/Enumeration.php");
	
	/**
	 * Die abstrakte Klasse TestType bildet ein Enumeration nach.
	 * Hierin werden die verfügbaren Testtypen gespeichert.
	 * Dazu wird ein Array mit den verfügbaren Namen angeboten und
	 * dazugehörige Konstanten, deren Werte die korrespondieren
	 * Array-Indizes bilden, um auf die Namen zuzugreifen.
	 */
	abstract class TestType extends Enumeration {
		/**
		 * Das konstante Array NAMES enthält alle verfügbaren
		 * Testnamen. Diese Namen sind äquivalent zu den zu
		 * verwendenen Werten von $_SESSION["testType"].
		 * Um den Testnamen zu erhalten muss der Array-Wert nur
		 * mit dem String "Test" konkateniert werden.
		 * 
		 * @var unknown
		 */
		const NAMES = array(
			
		);
	}
?>
