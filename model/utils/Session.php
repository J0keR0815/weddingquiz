<?php
	/**
	 * Die Klasse Session kapselt die in PHP verwendeten Zugriffe
	 * auf die $_SESSION-Variable. Die Session ist als Singleton
	 * realisiert, sodass nur eine Instanz zur Verfügung steht.
	 */
	class Session {
		/**
		 * Die Konstante SESSION_STARTED besitzt den Wert true.
		 * Sie dient der Zuweisung des Attributes $state.
		 * 
		 * @var unknown
		 */
		const SESSION_STARTED = true;
		
		/**
		 * Die Konstante SESSION_NOT_STARTED besitzt den Wert
		 * false. Sie dient der Zuweisung des Attributes $state.
		 *
		 * @var unknown
		 */
		const SESSION_NOT_STARTED = false;
		
		/**
		 * Das Attribut $state dient der Überprüfung der Session.
		 * Standardmäßig besitzt diese den Wert SESSION_NOT_STARTED.
		 * 
		 * @var unknown
		 */
		private $state = self::SESSION_NOT_STARTED;
		
		/**
		 * Die statische Variable $instance speichert die
		 * Singleton-Instanz der Klasse.
		 * 
		 * @var unknown
		 */
		private static $instance;
		
		private function __construct() {
			
		}
		
		/**
		 * Über die Methode getInstance() wird die
		 * Singelton-Instanz der Klasse erzeugt, falls diese noch
		 * nicht vorhanden ist.
		 * Ansonsten wird die Instanz ausgegeben.
		 */
		public static function getInstance() {
			if (!isset(self::$instance)) {
				self::$instance = new self;
			}
			
			self::$instance->start();
			
			return self::$instance;
		}
		
		/**
		 * start() startet die Session, wenn der Wert des
		 * Attributes SESSION_NOT_STARTED ist.
		 * 
		 * @return 	true, Session erfolgreich gestartet
		 * 			false, Session nicht erfolgreich gestartet 
		 */
		public function start() {
			if ($this->state == self::SESSION_NOT_STARTED) {
				$this->state = session_start();
			}
			
			return $this->state;
		}
		
		/**
		 * setEntry() setzt die Variable $_SESSION[$key] = $value.
		 * 
		 * @param unknown $key
		 * @param unknown $value
		 */
		public function setEntry($key, $value) {
			$_SESSION[$key] = $value;
		}
		
		/**
		 * getEntry() gibt den Wert für $_SESSION[$key] zurück.
		 * 
		 * @param unknown $key
		 */
		public function getEntry($key) {
			return $_SESSION[$key];
		}
		
		/**
		 * __isset() überprüft, ob das assoziative Array $_SESSION
		 * einen Index $key besitzt.
		 * @param unknown $key
		 */
		public function __isset($key) {
			return isset($_SESSION[$key]);
		}
		
		/**
		 * __unset() löscht aus dem assoziativen Array $_SESSION
		 * den Eintrag mit dem Index $key.
		 * @param unknown $key
		 */
		public function __unset($key) {
			unset($_SESSION[$key]);
		}
		
		/**
		 * destroy() beendet die Session, wenn der Wert des
		 * Attributes $state = SESSION_STARTED ist.
		 * $state wird auf false gesetzt, wenn die Session
		 * erfolgreich beendet wurde. Andernfalls wird $state auf
		 * true gesetzt.
		 * 
		 * @return 	true, Session wurde erfolgreich beendet
		 * 			false,	Session wurde nicht erfolgreich beendet
		 *					oder ist bereits aus
		 */
		public function destroy() {
			if ($this->state == self::SESSION_STARTED) {
				$this->state = !session_destroy();
				unset($_SESSION);
				
				return !$this->state;
			}
			
			return false;
		}
	}
?>
