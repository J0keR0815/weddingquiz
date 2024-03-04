<?php
	require_once("view/View.php");
	
	/**
	 * Die Klasse ScoreView implementiert das Interface View.
	 * Die Klasse ist verantwortlich für die Darstellung der
	 * Spielauswertung.
	 */
	class ScoreView implements View {
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
				isset($data["players"]) &&
				is_array($data["players"]) &&
				count($data["players"]) > 0
			) {
				$players = $data["players"];
			}

			echo "
				<html>
					<head>
						<meta charset=\"UTF-8\"/>
						<title>" . SCORE_TITLE . "</title>
						<link 
								href=\"css/scoreView.css\" 
								rel=\"stylesheet\" 
								type=\"text/css\"
						/>
					</head>
					
					<body>
			";

			if (!isset($players)) {
				echo "
						<h2>" . SCORE_NO_RESULT . "</h2>
				";
			} else {
				echo "
						<h2>" . SCORE_TITLE . "</h2>
						<table>
							<tr>
								<th>" .
									SCORE_TABLE_NR .
								"</th>
								<th>" .
									SCORE_TABLE_NAME .
								"</th>
								<th>" .
									SCORE_TABLE_RESULT .
								"</th>
							</tr>
				";

				$i = 0;
				foreach ($data["players"] as $player) {
					echo "
							<tr>
								<td>" .
									++$i .
								"</td>
								<td>" .
									$player->getName() .
								"</td>
								<td>" .
									$player->getScore() .
								"</td>
							</tr>
					";
				}
			}
			
			echo "
						</table>
					</body>
				</html>";
		}
	}
?>
