<?php
	require_once("model/quiz/Question.php");
	require_once("model/utils/errorhandling.php");
	require_once("model/utils/Session.php");
	
	/**
	 * Diese Klasse ist verantwortlich für die Datenverwaltung
	 * des Quiz-Teilnehmers.
	 */
	class Player {
		/**
		 * Dieses Attribut speichert den eingelesenen Spielernamen.
		 */
		private $name;

		/**
		 * Dieses Attribut speichert die MAC-Adresse des Spielers.
		 */
		private $macAddr;

		/**
		 * Dieses Attribut speichert das Sprachkürzel der durch den
		 * Spieler präferierten Sprache.
		 */
		private $lang;

		private $score;
		
		/**
		 * Dieses Attribut beschreibt, ob der Spieler neu ist oder
		 * bereits am Quiz teilgenommen hat.
		 */	
		private $isNew;

		/**
		 * Dieses Attribut speichert die bereits absolvierten
		 * Fragen.
		 */
		private $questions;

		/**
		 * Der Konstruktor belegt die Attribute des Player-Objekts.
		 */
		public function __construct(
			string $macAddr = NULL,
			string $name = NULL,
			string $lang = NULL,
			int $score = -1
		) {
			if (
				!is_null($macAddr) &&
				!is_null($name) &&
				!is_null($lang) &&
				$score > 0
			) {
				$this->setArgs(
					$macAddr, $name, $lang, $score
				);
			} else {
				try {
					$this->macAddr = self::readMacAddr();
				} catch (BadFunctionCallException $e) {
					throw $e;
				}

				$session = Session::getInstance();

				self::readUserfile(
					$this->macAddr,
					$this->name,
					$this->lang,
					$this->score
				);

				$this->isNew = true;
				if ($this->name) {
					$this->isNew = false;
				} else if ($session->__isset("username")) {
					$this->name = $session->getEntry("username");
				}

				if (!$this->lang) {
					$this->lang = self::readLang();
				}

				$this->score = $score;

				try {
					$this->questions =
					   Question::loadQuestions($this->lang);
				} catch (InvalidArgumentQuestion $e) {
					throw $e;
				}
			}
		}
		
		private function setArgs(
			$macAddr, $name, $lang, $score
		) {
			$this->macAddr = $macAddr;
			$this->name = $name;
			$this->lang = $lang;
			$this->score = $score;
			$this->isNew = false;
			$this->questions = NULL;
		}

		/**
		 * Diese Methode gibt den Namen des Spielers zurück.
		 */
		public function getName() {
			return $this->name;
		}
		
		public function setName(string $name) {
			$this->name = $name;
		}

		/**
		 * Diese Methode gibt die MAC-Adresse des Spielers zurück.
		 */
		public function getMacAddr() {
			return $this->macAddr;
		}
		
		/**
		 * Diese Methode gibt das Sprachkürzel der durch den
		 * Spieler präferierten Sprache zurück.
		 */
		public function getLang() {
			return $this->lang;
		}

		public function getScore() {
			return $this->score;
		}
		
		public function getNextQuestion() {
			if (!$this->questions) {
				return NULL;
			} else {
				foreach ($this->questions as $key => $val) {
					if (
						$val->getStat() ==
							QuestionStatType::getName(
								QuestionStatType::NONE
							)
					) {
						return $val;
					}
				}
				return -1;
			}
		}
		
		public function getQuestions() {
			return $this->questions;
		}

		public function setQuestions(array $questions) {
			if (!$this->questions) {
				$this->questions = $questions;
			} else {
				$errMsg = createErrStr(
					"Questions can only be initialized once",
					__FILE__,
					__LINE__
				);
				throw new BadFunctionCallException();
			}
		}

		public function save() {
			$this->score = Question::getScore($this->questions);

			$filename = explode(":", $this->macAddr);
			$filename = implode("_", $filename);
			$filename = "data/results/" . $filename;
			$fd = fopen($filename, "w");
			if (!$fd) {
				$errMsg = createErrStr(
					"Cannot create file \"" . $filename . "\"",
					__FILE__,
					__LINE__
				);
				throw new RuntimeException($errMsg);
			}

			$buf = 
				"name=" . $this->name . "\n" .
				"lang=" . $this->lang . "\n" .
				"score=" . $this->score
			;
			if (!fwrite($fd, $buf)) {
				$errMsg = createErrStr(
					"Cannot write Player information into file 
					\"" . $this->macAddr . "\"",
					__FILE__,
					__LINE__
				);
				throw new RuntimeException("$errMsg");
			}
		}

		private static function readUserfile(
			$macAddr, &$name, &$lang, &$score
		) {
			$filename = explode(":", $macAddr);
			$filename = implode("_", $filename);
			$filename = "data/results/" . $filename;
			
			$fd = false;
			if (file_exists($filename)) {
				$fd = fopen($filename, "r");
			}

			if (!$fd) {
				$name = NULL;
				$lang = NULL;
				$score = NULL;
			} else {
				while ($entry = fgets($fd)) {
					$keyval = explode("=", $entry);
					${$keyval[0]} = trim($keyval[1]);
				}
			}
		}
		
		/**
		 * Diese Funktion liest die MAC-Adresse des anfragenden
		 * Clients aus und prüft diese auf Gültigkeit.
		 */
		private static function readMacAddr() {
			$ipAddr = $_SERVER["REMOTE_ADDR"];
			$macAddr = exec(
				"/bin/bash scripts/readmac.sh " . $ipAddr
			);

			$regexpMacAddr = "/^([0-9a-f]{2}:){5}[0-9a-f]{2}$/";
			if (preg_match($regexpMacAddr, $macAddr) == 1) {
				return $macAddr;
			} else {
				$errMsg = createErrStr(
					"Invalid MAC address \"" . $macAddr . "\"",
					__FILE__,
					__LINE__
				);
				throw new BadFunctionCallException($errMsg);
			}
		}

		/**
		 * Diese Funktion zerlegt den vom Browser gelieferten
		 * Language-String $_SERVER["HTTP_ACCEPT_LANGUAGE"].
		 * Dieser besteht aus eine kommaseparierten Sequenz von
		 * verwendbaren Sprachen mit zugehörigen Wertigkeiten:
		 * <lang_1>[;q=<value_1>], ... , <lang_n>[;q=<value_n>
		 * Die Funktion liefert als zweistelliges Sprachkürzel nur
		 * die englische Sprache ("en") oder die deutsche Sprache
		 * ("de");
		 */
		public static function readLang() {
			$accLangs = explode(
				",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]
			);

			// Finden der Sprache mit der höchsten Werigkeit
			$maxWeight = 0.0;
			$lang = NULL;
			foreach ((array)$accLangs as $accLang) {
				$accLangEntries = explode(";", $accLang);
				if (count($accLangEntries) < 2) {
					/*
					 * Wenn keine Werigkeit gesetzt ist, ist dies
					 * die Standardsprache mit höchster Wertigkeit.
					 */
					$weight = 1.0;
				} else {
					/*
					 * Ist eine Wertigkeit gesetzt, wird diese
					 * ausgelesen.
					 */
					$weight = explode("=", $accLangEntries[1])[1];
				}
				
				// Setzen der Sprache mit höchster Wertigkeit
				if ($weight > $maxWeight) {
					$lang = substr($accLangEntries[0], 0, 2);
					$maxWeight = $weight;
				}
			}

			return $lang == "de" || $lang == "en" ? $lang : "en";
		}

		public function evalScores() {
			$path = "data/results";

			$dir = dir($path);
			if (!$dir) {
				$errMsg = createErrStr(
					"Cannot read directory \"" . $path . "\"",
					__FILE__,
					__LINE__
				);
				throw RuntimeException($errMsg);
			}
			
			$players = array();
			while (($entry = $dir->read()) !== false) {
				$filename = $path . "/" . $entry;
				$regex = 
					"/^([0-9a-f]{2}_){5}[0-9a-f]{2}$/";
				if (
					file_exists($filename) &&
					is_file($filename) &&
					preg_match($regex, $entry)
				) {
					$fd = fopen($filename, "r");
					if (!$fd) {
						$errMsg = createErrStr(
							"Cannot open file \"" . 
							$filename .	"\"",
							__FILE__,
							__LINE__
						);
						throw RuntimeException($errMsg);
					}

					$content = fread($fd, filesize($filename));
					if (!$content) {
						$errMsg = createErrStr(
							"Cannot read file \"" . 
							$filename .	"\"",
							__FILE__,
							__LINE__
						);
						throw RuntimeException($errMsg);
					}

					$args = explode("\n", $content);

					$arg_name = explode("=", $args[0]);
					$arg_lang = explode("=", $args[1]);
					$arg_score = explode("=", $args[2]);

					$name = $arg_name[1];
					$lang = $arg_lang[1];
					$score = $arg_score[1];
					
					$player = new Player(
						$entry, $name, $lang, $score
					);
					array_push($players, $player);
				}
			}
			
			usort($players, [__CLASS__, "cmpScore"]);
			$players = array_reverse($players);
			return $players;
		}
		
		private static function cmpScore(Player $p1, Player $p2) {
			return $p1->score - $p2->score;
		}
	}
?>
