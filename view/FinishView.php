<?php
	require_once("controller/QuizController.php");
	
	require_once("view/View.php");
	require_once("view/ViewType.php");
	
	/**
	 * Die Klasse FinisView implementiert das Interface View.
	 * Die Klasse ist verantwortlich für die Darstellung der
	 * Auswertung des Quiz.
	 */
	class FinishView implements View {
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
				isset($data["questions"]) &&
				is_array($data["questions"]) &&
				count($data["questions"]) ==
					QuizController::$count
			) {
				$questions = $data["questions"];
				$content = $this->displayResult($questions);
			} else if (!$data) {
				$content = "<h2>" . FINISH_HEADER_EXIT . "</h2>";
			}

			echo "
				<html>
					<head>
						<meta charset=\"UTF-8\"/>
						<title>" . FINISH_TITLE . "</title>
						<link 
								href=\"css/finishView.css\" 
								rel=\"stylesheet\" 
								type=\"text/css\"
						/>
					</head>
					
					<body>" . $content . "</body>
				</html>
			";
		}
		
		private function displayResult(array $questions) {
			echo "
				<h2>" . FINISH_TITLE . "</h2>
				<table>
					<tr>
						<th>" .
							FINISH_TABLE_NR .
						"</th>
						<th>" .
							FINISH_TABLE_QUESTION .
						"</th>
						<th>" .
							FINISH_TABLE_ANSWER .
						"</th>
						<th>" .
							FINISH_TABLE_RESULT .
						"</th>
					</tr>
					
			";

			$i = 1;
			foreach ($questions as $q) {
				echo "
					<tr>
						<td>" . $i++ .	"</td>
						<td>" . $q->getQustr() . "</td>
						<td>" . $q->getStat() .	"</td>
						<td>
							<img 
								src=\"img/" . (
									$q->getStat() == 
										$q->getSolution() ?
											"green_hook" :
											"red_cross"
								) . ".png\" 
								width=\"20px\"
							/>
						</td>
					</tr>
				";
			}
				
			echo "
				</table>
				<form method=\"get\">
					<button 
						type=\"submit\" 
						name=\"exit\" 
						value=\"exit\"
					>" .
						FINISH_BUTTON_EXIT .
					"</button>
				</form>
			";
		}
	}
?>
