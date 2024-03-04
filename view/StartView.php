<?php
	require_once("view/View.php");
	require_once("view/ViewType.php");
	/**
	 * Die Klasse StartView implementiert das Interface View. Die
	 * Klasse ist verantwortlich für die Darstellung der
	 * Startoberfläche. 
	 */
	class StartView implements View {
		private $userExists = false;
		/**
		 * Der Konstruktor ruft zuerst den Basisklassenkonstruktor
		 * auf und prüft dann, ob ihm über $data Informationen
		 * übermittelt wurden. In diesem Fall kann der StartView
		 * mitgeteilt werden, dass der Spieler bereits existiert.
		 */
		public function __construct(array $data = NULL) {
			if (
				is_array($data) &&
				isset($data["username"]) &&
				is_string($data["username"]) &&
				trim($data["username"]) != ""
			) {
				$this->userExists = true;
			}
		}
		/**
		 * display() implementiert die Methode display() des
		 * Interfaces View und dient der Ausgabe des
		 * Oberflächeninhalts.
		 * 
		 * {@inheritDoc}
		 * @see View::display()
		 */
		public function display(array $data = NULL) {
			echo "
				<html>
					<head>
						<meta charset=\"UTF-8\"/>
						<title>" . START_TITLE . "</title>
						<link 
								href=\"css/startView.css\" 
								rel=\"stylesheet\" 
								type=\"text/css\"
						/>
					</head>
					
					<body>
			";

			if ($this->userExists) {
				echo	"<h2>" . START_USER_EXISTS . "</h2>";			
			} else {
				echo "
						<h1>" . START_HEADER . "</h1>
						<p>" . START_INTRO . "</p>
						<h3>" . START_GL . "</p>
						<form method=\"get\">
							<button 
								type=\"submit\" 
								name=\"submit\"
								value=\"" .
								ViewType::getName(
									ViewType::REGISTER
								) .
								"\"
							>" .
								START_BUTTON_SUBMIT .
							"</button>
						</form>
				";
			}
			
			echo "
					</body>
				</html>";
		}
	}
?>
