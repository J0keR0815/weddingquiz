<?php
	/**
	 * Die abstrakte Klasse Enumeration bildet ein Enumeration
	 * nach. Hierin werden verschiedene Mengenelemente gespeichert.
	 * Dazu wird ein Array mit den verfügbaren Namen angeboten und
	 * dazugehörige Konstanten, deren Werte die korrespondieren
	 * Array-Indizes bilden, um auf die Namen zuzugreifen.
	 */
	abstract class Enumeration {
		/**
		 * Das konstante Array NAMES enthält alle verfügbaren
		 * Bezeichnungen der Mengenelemente.
		 *
		 * @var unknown
		 */
		const NAMES = array(
				
		);
		
		/**
		 * Das konstante Array GERMANTYPELABELS enthält alle
		 * Bezeichnungen der Mengenelemente in deutsch für die
		 * Ausgabe.
		 *
		 * @var unknown
		 */
		const GERMANLABELS = array(
			
		);
		
		/**
		 * getName() gibt zu einem Mengenelement den
		 * korrespondierenden Namen zurück. Wird ein unzulässiger
		 * Wert an den Parameter übergeben, gibt die Funktion NULL
		 * zurück.
		 *
		 * @param unknown $enumType
		 * @return string|NULL
		 */
		public static function getName(int $enumType) {
			$calledClass = get_called_class();
			if (
				$enumType >= 0 &&
				$enumType < count($calledClass::NAMES)
			) {
				return $calledClass::NAMES[$enumType];
			} else {
				return NULL;
			}
		}
		
		/**
		 * getGermanLabel() gibt zu einem Mengenelement die
		 * korrespondierende deutsche Bezeichnung zurück. Wird ein
		 * unzulässiger Wert an den Parameter übergeben, gibt die
		 * Funktion NULL zurück.
		 *
		 * @param unknown $enumType
		 * @return string|NULL
		 */
		public static function getGermanLabel(int $enumType) {
			$calledClass = get_called_class();
			if (
				$enumType >= 0 &&
				$enumType < count($calledClass::GERMANLABELS)
			) {
				return $calledClass::GERMANLABELS[$enumType];
			} else {
				return NULL;
			}
		}
		
		/**
		 * contains() überprüft, ob zu einem übergebenen string
		 * ein Mengenelement verfügbar ist.
		 *
		 * @param unknown $enumTypeStr
		 * @return 	true, wenn das Mengenelement existiert |
		 * 			false, wenn das Mengenelement nicht existiert
		 */
		public static function contains(string $enumTypeStr) {
			$calledClass = get_called_class();
			return in_array($enumTypeStr, $calledClass::NAMES);
		}
		
		/**
		 * getArrayKey() gibt den Array-Index des Arrays NAMES für
		 * die Bezeichnung eines Mengenelements zurück.
		 * 
		 * @param string $enumTypeStr
		 */
		public static function getArrayKey(string $enumTypeStr) {
			$calledClass = get_called_class();
			return array_search($enumTypeStr, $calledClass::NAMES);
		}
		
		/**
		 * translateFromGerman() gibt das zu $enumTypeStrGerman
		 * gehörige Mengenelement aus NAMES zurück. Gibt es keinen
		 * Eintrag zu $enumTypeStrGerman, dann liefert die Funktion
		 * NULL. 
		 * 
		 * @param string $enumTypeStrGerman
		 * @return NULL
		 */
		public static function translateFromGerman(
			string $enumTypeStrGerman
		) {
			$calledClass = get_called_class();
			$key = array_search(
				$enumTypeStrGerman,
				$calledClass::GERMANLABELS
			);
			if ($key === false) {
				return NULL;
			} else {
				return $calledClass::NAMES[$key];
			}
		}
	}
?>
