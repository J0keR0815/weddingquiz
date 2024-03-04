<?php
	require_once("view/View.php");
	require_once("view/ViewType.php");
	
	/**
	 * Die Klasse RegisterView implementiert das Interface View.
	 * Die Klasse ist verantwortlich für die Darstellung der
	 * Anmeldeoberfläche.
	 */
	class RegisterView implements View {
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
				isset($data["error"]) &&
				is_string($data["error"]) &&
				trim($data["error"]) != ""
			) {
				/*
				 * Es wurde ein Fehlerstring übertragen.
				 */
				echo $data["error"];
			}

			echo "
				<html>
					<head>
						<meta charset=\"UTF-8\"/>
						<title>" . REGISTER_TITLE . "</title>
						<link 
								href=\"css/registerView.css\" 
								rel=\"stylesheet\" 
								type=\"text/css\"
						/>
					</head>
					
					<body>
						<form method=\"post\">
							<label for=\"username\">" .
								REGISTER_LABEL_USERNAME .
							"</label>
							<br/>
							<input 
								type=\"text\" 
								name=\"username\"
							/>
							<br/>
							<button 
								name=\"submit\" 
								value=\"" .
									ViewType::getName(
										ViewType::QUIZ
									) .
								"\" 
							>" .
								REGISTER_BUTTON_SUBMIT .
							"</button>
						</form>
					</body>
				</html>";
		}
	}
?>
