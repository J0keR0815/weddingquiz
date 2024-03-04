<?php
	require_once("controller/QuizController.php");
	
	require_once("view/View.php");
	require_once("view/ViewType.php");
	
	/**
	 * Die Klasse QuizView implementiert das Interface View.
	 * Die Klasse ist verantwortlich für die Darstellung der
	 * Quizoberfläche.
	 */
	class QuizView implements View {
		/**
		 * display() implementiert die Methode display() des
		 * Interfaces View und dient der Ausgabe des
		 * Oberflächeninhalts.
		 * 
		 * {@inheritDoc}
		 * @see View::display()
		 */
		public function display(array $data = NULL) {
			if (
				is_array($data) &&
				isset($data["question"]) &&
				$data["question"] instanceof Question &&
				isset($data["qnr"]) &&
				$data["qnr"] > 0 &&
				$data["qnr"] <= QuizController::$count
			) {
				$title =
					QUIZ_TITLE_QUESTION .
					" " .
					$data["qnr"] .
					" / " .
					QuizController::$count;
			}

			echo "
				<html>
					<head>
						<meta charset=\"UTF-8\"/>
						<title>" . $title . "</title>
						<link 
							href=\"css/quizView.css\" 
							rel=\"stylesheet\" 
							type=\"text/css\"
						/>
					</head>
					
					<body>";

			$this->displayQuestion(
				$data["question"],
				$data["qnr"],
				isset($data["error"]) ? $data["error"] : NULL
			);
			
			echo "
					</body>
				</html>
			";
		}

		private function displayQuestion(
			Question $question,
			int $nr,
			string $error = NULL
		) {
			if ($error) {
				echo $error;
			}

			echo "
				<h3>" . $question->getQustr() . "</h3>
				<form method=\"post\">
					<table class=\"tablequestion\">
			";
			
			foreach ($question->getOpts() as $key => $val) {
				echo "
						<tr>
							<td>
								<input 
									type=\"radio\" 
									name=\"option\" 
									value=\"" . $key . "\"
								/>
							</td>
							<td>" .
								$val . 
							"</td>
						</tr>";
			}

			echo "
					</table>
					<button 
						type=\"submit\" 
						name=\"submit\"
						value=\"submit\"
					>" .
						($nr < QuizController::$count ?
							QUIZ_BUTTON_NEXT :
						   	QUIZ_BUTTON_REVIEW) . 
					"</button>
				</form>
			";
		}
	}
?>
