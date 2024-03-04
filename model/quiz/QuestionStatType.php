<?php
	require_once("model/utils/Enumeration.php");
	
	/**
	 * Die abstrakte Klasse QuestionStatType bildet ein Enumeration
	 * nach. Hierin werden die verfügbaren Stati gespeichert, die
	 * ein Question-Objekt haben darf. Dazu wird ein Array mit den
	 * verfügbaren Namen angeboten und dazugehörige Konstanten, 
	 * deren Werte die korrespondieren Array-Indizes bilden, um 
	 * auf die Namen zuzugreifen.
	 */
	abstract class QuestionStatType extends Enumeration {
		/**
		 * Das konstante Array NAMES enthält alle verfügbaren
		 * Stati-Namen. Diese Namen sind äquivalent zu den
		 * zu verwendenen Werten von 
		 * 
		 * @var unknown
		 */
		const NAMES = array("None", "a", "b", "c");
		
		const NONE = 0;
		const A = 1;
		const B = 2;
		const C = 3;
	}
?>
