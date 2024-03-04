<?php
	/**
	 * Interface für Objekte, die vergleichbar sein sollen.
	 */
	interface Comparable {
		/**
		 * Das Objekt, das Comparable implementiert, muss die
		 * Methode compareTo() implementieren. Hierüber wird das
		 * Objekt selbst mit dem übergebenen Objekt verglichen.
		 * 
		 * @param unknown $obj
		 */
		public function compareTo($obj);
	}
?>
