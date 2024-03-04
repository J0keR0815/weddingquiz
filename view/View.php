<?php
	/**
	 * Das Interface View muss durch die einzelnen Viewklassen
	 * implementiert werden.
	 */
	interface View {
		/**
		 * Eine Viewklasse muss die Methode display()
		 * implementieren, welche den Inhalt der Benutzeroberfläche
		 * erzeugt. Über das Argument $data können der Methode
		 * Informationen zur Ausgabe in einem Array übergeben
		 * werden.
		 * 
		 * @param array $data
		 */
		public function display(array $data = NULL);
	}
?>
