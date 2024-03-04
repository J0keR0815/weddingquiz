<?php
	require_once("model/quiz/QuestionStatType.php");
	require_once("model/utils/errorhandling.php");
	
	class Question {
		private $nr;
		private $filename;
		private $lang;
		private $qustr;
		private $opts;
		private $stat;
		
		public function __construct(
			string $filename,
			string $lang,
			string $qustr,
			array $opts,
			string $solution,
			string $stat = "None"
		) {
			$this->lang = $lang;
			$this->filename = $filename;

			try {
				$this->nr = $this->extractNumber();
			} catch (InvalidArgumentException $e) {
				throw $e;
			}

			$this->qustr = $qustr;
			$this->opts = $opts;
			$this->solution = $solution;
			try {
				$this->setStat($stat);
			} catch (InvalidArgumentException $e) {
				throw $e;
			}
		}
		
		private function extractNumber() {
			$regexp =
				"/^data\/" . $this->lang . "\/question(.*)\.php$/";

			if (preg_match($regexp, $this->filename) == 1) {
				$n = preg_filter($regexp, "$1", $this->filename);
				return $n;
			} else {
				$errMsg = createErrStr(
					"Invalid question filename \"" .
					$this->filename .
				   	"\"",
					__FILE__,
					__LINE__
				);
				throw new InvalidArgumentException($errMsg);
			}
		}

		public function getNr() {
			return $this->nr;
		}
		
		public function getFilename() {
			return $this->filename;
		}

		public function getQustr() {
			return $this->qustr;
		}

		public function getOpts() {
			return $this->opts;
		}
		
		public function getSolution() {
			return $this->solution;
		}

		public function getStat() {
			return $this->stat;
		}

		public function setStat(string $stat) {
			if (!QuestionStatType::contains($stat)) {
				$errMsg = createErrStr(
					"Invalid type for question's status \"" .
					$stat .
				   	"\"",
					__FILE__,
					__LINE__
				);
				throw new InvalidArgumentException($errMsg);
			} else {
				$this->stat = $stat;
			}
		}
		
		public static function compare(Question $q1, Question $q2) {
			if ($q1->lang != $q2->lang) {
				$errMsg = createErrStr(
					"Cannot compare questions,
				   	languages are different",
					__FILE__,
					__LINE__
				);
				throw new InvalidArgumentException($errMsg);
			}
			
			try {
				$n1 = $q1->extractNumber();
				$n2 = $q2->extractNumber();

				if ($q1->filename == $q2->filename) {
					return 0;
				} else {
					return $n1 < $n2 ? -1 : 1;
				}
			} catch (InvalidArgumentException $e) {
				throw $e;
			}
		}

		public static function loadQuestions($lang) {
			$questions = array();
			foreach ($_SESSION as $key => $val) {
				$regexp =
					"/^data\/" . $lang . "\/question.*\.php$/";
				if (preg_match($regexp, $key) == 1) {
					$filename = $key;
					if (
						file_exists($filename) &&
						is_file($filename)
					) {
						require($filename);
						try {
							$q = new Question(
								$filename,
								$lang,
								$qustr,
								$opts,
								$solution,
							   	$val
							);
							$questions[$filename] = $q;
						} catch (InvalidArgumentException $e) {
							throw $e;
						}
					}
				}
			}

			try {
				usort($questions, [__CLASS__, "compare"]);
			} catch (InvalidArgumentException $e) {
				throw $e;
			}
			return $questions;
		}

		public static function initQuestions(
			int $count, string $lang
		) {
			$path = "data/" . $lang;

			$dir = dir($path);
			if (!$dir) {
				$errMsg = createErrStr(
					"Cannot read directory \"" . $path . "\"",
					__FILE__,
					__LINE__
				);
				throw RuntimeException($errMsg);
			}
			
			$questions = array();
			while (($entry = $dir->read()) !== false) {
				$filename = $path . "/" . $entry;
				if (file_exists($filename) && is_file($filename)) {
					require($filename);
					try {
						$q = new Question(
							$filename,
							$lang,
							$qustr,
							$opts,
						   	$solution
						);
						$questions[$filename] = $q;
					} catch (InvalidArgumentException $e) {
						throw $e;
					}
				}
			}

			$resultQuests = array();
			if (count($questions) < $count) {
				$errMsg = createErrStr(
					"Number of questions does not reach the 
					minimal number of questions",
					__FILE__,
					__LINE__
				);
				throw new RuntimeException($errMsg);
			} else if (count($questions) > $count) {
				for ($i = 0; $i < $count; ++$i) {
					$key = array_rand($questions);
					$resultQuests[$key] = $questions[$key];
					unset($questions[$key]);
					Session::getInstance()->setEntry($key, "None");
				}
			}

			try {
				usort($resultQuests, [__CLASS__, "compare"]);
			} catch (InvalidArgumentException $e) {
				throw $e;
			}
			return $resultQuests;
		}

		public static function getScore(array $questions) {
			$score = 0;
			foreach ($questions as $q) {
				if ($q->stat == $q->solution) {
					++$score;
				}
			}
			return $score;
		}
	}
?>
