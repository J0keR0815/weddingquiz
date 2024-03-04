<?php
	/**
	 * Dieses Modul enthält Hilfsfunktionen zur Fehlerbehandlung.
	 */
	
	/**
	 * Diese Funktion erzeugt eine Fehlermeldung der Form:
	 *
	 *	Error in "<filename>" in line "<linenr>":
	 *	<error message>
	 */
	function createErrStr(
		string $errMsg,
		string $file,
		int $line
	) {
		return "
			<p style=\"color: red; fontweight: bold;\">
				Error in \"" .
				$file .
				"\" in line \"" .
				$line .
				"\":<br/>" .
				$errMsg .
			"</p>
		";
	}
	
	/**
	 * Diese Funktion erzeugt eine Nutzerwarnung.
	 */
	function createUsrWarning(string $errMsg) {
		return "
			<p style=\"color: red; font-weight: bold\">" .
				$errMsg .
			"</p>
		";
	}
?>
