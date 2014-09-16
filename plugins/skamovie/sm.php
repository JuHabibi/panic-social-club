<?php
function sm($var,$toplevel=true){echo serializeMore($var,$toplevel);}


	function serializeMore($var, $toplevel = true) {
		global $__SERIALIZEMORE__;

		if (!$__SERIALIZEMORE__) { // If this is the first time we load the script then output css & javascript
			$__SERIALIZEMORE__ = true;

			SM_cssAndJavascript();
		}

		if ($toplevel) {
			$returnval = "<div class='serializeMore'>\n";
		}
		else {
			$returnval = "";
		}

		$myType = gettype($var);
		switch($myType) {
			case "array":		/* Arrays and objects share the same code*/
				$vars = &$var;
				$className = "";
			case "object":
				if (!isset($vars)) { // If this is not set
					$vars = get_object_vars($var);
					$className = "&nbsp;&nbsp;<span class='classname'>(" . get_class($var) .")</span>";
				}

				// Random DOM ID for javascript
				$id = md5((string)mt_rand());

				// 's' ... or not
				$c = count($vars);
				$s = ($c > 1) ? "s" : "";

				$returnval .= "<table id='" . $id ."' class='object'><tr><th colspan='2'>";
				$returnval .= "<a href=\"javascript:switchPanel('" . $id ."')\">" . ucfirst($myType) . "</a>" . $className;
				$returnval .= "&nbsp;&nbsp;(" . $c . " element". $s . ")</th></tr>\n";

				foreach($vars as $key => $value) {
					$returnval .= "<tr><td>" . serializeMore($key, false) ."</td>";
					$returnval .= "<td>" . serializeMore($value, false) ."</td></tr>\n";
				}

				// If it's an object
				if (($vars = get_class_methods($var))) {
					// If the object have methods
					if (($countVars = count($vars))) {
						$returnval .= "<tr><td colspan='2'><div class='hr' style='width:auto'/></td></tr>\n";
						for ($i = 0; $i < $countVars; $i++) {
							$returnval .= "<tr><td class='method' colspan='2'>-&gt;" . $vars[$i] . "()</td>\n";
						}
					}
				}
				$returnval .= "</table>\n";
				break;
			case "string":
				$returnval .= "<span class='string'>&quot;" . htmlentities($var) ."&quot;</span>\n";
				break;
			case "boolean":
				$returnval .= "<span class='boolean'>" . ( $var === TRUE ? "TRUE" : "FALSE" ) . "</span>\n";
				break;
			case "resource":
				$returnval .= "<span class='resource'>" . $var ."</span>\n";
				break;
			case "NULL":
				$returnval .= "<span class='null'>NULL</span>\n";
				break;
			default:
				$returnval .= "<span class='" . $myType . "'>" . htmlentities($var) ."</span>\n";
				break;
		}

		if ($toplevel) {
			$returnval .= "</div>\n";
		}
		return $returnval;
	}
		/*
	<function>
		<author>Romuald Brunet</author>
		<date>2002-12-02</date>
		<description>
			Outputs javascript code and CSS for serializemore
		</description>
	</function>
	*/
	function SM_cssAndJavascript() {
	?>
	<style type="text/css">
	.serializeMore * { font-family: sans-serif; text-align: left; background-color: white; font-size: 12px }
	.serializeMore .integer, .serializeMore .boolean, .serializeMore .null { color: blue }
	.serializeMore .method { color: #6969EE }
	.serializeMore .classname { color: black }
	.serializeMore .double, .serializeMore .float { color: teal }
	.serializeMore .string { color: #993311 }
	.serializeMore .resource { font-style: oblique; font-weight: bold}

	.serializeMore TABLE.array, .serializeMore TABLE.object {
		padding-top: 0px;
		border: 1px solid black;
		border-right: 2px solid #444444;
		border-bottom: 2px solid #444444;
	}

	.serializeMore TABLE.array A, .serializeMore TABLE.object A {
		font-size: 14px;
		font-weight: bold;
		color: #AAAAAA;
		font-style: oblique;
	}

	.serializeMore TABLE.array TH, .serializeMore TABLE.object TH {
		font-weight: normal;
		color: #555555;
		text-align: left;
	}

	.serializeMore TABLE.array TD:first-child, .serializeMore TABLE.object TD:first-child {
		padding-right: 8px;
	}

	.serializeMore TABLE.array TD, .serializeMore TABLE.object TD {
		vertical-align: top;
	}
	</style>
	
	
	<script type="text/javascript">
	var panels = new Array();

	function switchPanel(id) {
		// On vérifie d'abord si le DOM est géré
		if (! document.getElementById)
			return;

		// Si l'element n'existe pas dans la page
		if (! (table = document.getElementById(id)))
			return;

		// On cherche le tbody parmi les fils de la table
		for (i = 0; i < table.childNodes.length ; i++) {
			if (table.childNodes[i].nodeName == "TBODY") {
				tbody = table.childNodes[i];
			}
		}

		// On ne sait jamais
		if (!tbody)
			return;

		// On regarde si le panel existe dans ceux qu'on a déjà utilisé
		panelFound = false;
		for (i = 0 ; i < panels.length; i++) {
			if (panels[i].id == id) {
				panelFound = true;
				panelIndex = i;
				break;
			}
		}

		// Si on ne l'a pas trouvé alors on le crée
		if (!panelFound) {
			panelIndex = panels.length;
			panels[panelIndex] = new Object();
			panels[panelIndex].id = id;
			panels[panelIndex].html = "";
		}

		// Ensuite si on a pas de code html associé au panel celui-ci est déployé
		if (panels[panelIndex].html == "") {
			// NS6 ne gère pas le outerHTML et IE6 déconne avec le innerHTML lors du remplacement :/
			if (table.outerHTML)
				panels[panelIndex].html = table.outerHTML;
			else
				panels[panelIndex].html = table.innerHTML;

			// Pour tous les fils du TBODY (sauf le premier)
			for (i = 1; i < tbody.childNodes.length; i++) {
				child = tbody.childNodes[i];

				// S'il s'agit d'un TR alors on le masque
				if (child.nodeName == "TR") {
					child.style.display = "none";
				}
			}

			// Enfin on met l'image appropriée si elle existe
			if (document.images[id])
				document.images[id].src="/images/+.gif";
		}
		else { // Sinon si le tableau est replié
			// Idem que plus haut :/ Cette fois ci on rétabli le code html sauvegardé
			if (table.outerHTML)
				table.outerHTML = panels[panelIndex].html;
			else
				table.innerHTML = panels[panelIndex].html;

			// On vide le code html sauvegardé
			panels[panelIndex].html = "";

			// Et finalement on met l'image appropriée si elle existe
			if (document.images[id])
				document.images[id].src="/images/-.gif";
		}
	}
	</script>
<?php
	}
?>