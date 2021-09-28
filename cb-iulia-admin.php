<?php
// Menü initialisieren
function cb_iulia_initialise_menu() {
	add_menu_page(
		'IULIA',								// Seitentitel
		'IULIA',								// Menü-Titel
		'administrator',						// notwendige Berechtigungsstufe
		'cb-iulia',								// Slug
		'cb_iulia_dashboard',					// PHP-Funktion
		'dashicons-editor-code'					// Icon
	);
	add_submenu_page(
		'null',									// Parent-Slug (null = hidden)
		'IULIA',								// Seitentitel
		'IULIA',								// Menütitel
		'administrator',						// notwendige Berechtigungsstufe
		'cb-iulia-download',					// Slug
		'cb_iulia_download'						// PHP-Funktion
	);
	add_submenu_page(
		'null',									// Parent-Slug (null = hidden)
		'IULIA',								// Seitentitel
		'IULIA',								// Menütitel
		'administrator',						// notwendige Berechtigungsstufe
		'cb-iulia-remove',						// Slug
		'cb_iulia_remove'						// PHP-Funktion
	);
	add_submenu_page(
		'null',									// Parent-Slug (null = hidden)
		'IULIA',								// Seitentitel
		'IULIA',								// Menütitel
		'administrator',						// notwendige Berechtigungsstufe
		'cb-iulia-update',						// Slug
		'cb_iulia_update'						// PHP-Funktion
	);
}
add_action('admin_menu', 'cb_iulia_initialise_menu');

// Dashboard
function cb_iulia_dashboard() {
	// Vars
	global $iuliaComponents;
	$url = '/wp-admin/admin.php?page=cb-iulia-';

	// Content
	$output = '<h1>IULIA - Intuitive und leistungsfähige Installations-App</h1>';
	$output .= '<a class="cb-iulia-update-btn" href="' . $url . 'update">Update</a>';
	$output .= '<table class="cb-iulia-table">';

	foreach($iuliaComponents as $comp => $repo) {
		$output .= '<tr class="cb-iulia-table-row">';
		$output .= '<td class="cb-iulia-table-name">' . $comp . '</td>';
		$output .= '<td class="cb-iulia-table-link">';

		// Ist Komponente bereits installiert? - Ausgeblendet, bis Feature fertig
// 		$dirName = get_stylesheet_directory() . '/cb-iulia/' . $repo . '-main';
// 		if(file_exists($dirName)) {
// 			$output .= '<a class="cb-iulia-table-link-btn" href="' . $url . 'remove&compName=' . $repo . '">Entfernen</a>';
// 		}
// 		else {
			$output .= '<a class="cb-iulia-table-link-btn" href="' . $url . 'download&compName=' . $repo . '">' . $comp . '</a>';
// 		}
		$output .= '</td>';
		$output .= '</tr>';
	}
	$output .= '</table>';

	echo $output;
}

// Download
function cb_iulia_download() {
	// Komponente bestimmten
	$compName = $_REQUEST['compName'];
	$url = 'https://github.com/cbradigital/' . $compName . '/archive/refs/heads/main.zip';

	// Speicherpfade und Dateinamen festlegen
	$workDir = get_stylesheet_directory() . '/cb-iulia';
	$compDir = $workDir . '/' . $compName;
	$compFile = $compDir . '.zip';

	// Komponente herunterladen und in Verzeichnis kopieren...
	$tmpfile = download_url($url);
	rename ($tmpfile, $compFile);

	// ... und extrahieren
	$zip = new ZipArchive;
	if($zip->open($compFile)) {
		$zip->extractTo($workDir);
		$zip->close();
	}

	// Erfolgsmeldung
	echo '
		<h1>IULIA - Intuitive und leistungsfähige Installations-App</h1>
		<p>Die Komponente wurde erfolgreich heruntergeladen.</p>
		<a href="/wp-admin/admin.php?page=cb-iulia">Zurück zum Dashboard</a>
	';
}

// Delete
function cb_iulia_remove() {
	echo 'Ich lösche Dinge - aber ich funktioniere aus Sicherheitsgründen noch nicht.';
}

// Update
function cb_iulia_update() {
	?> <h1>IULIA - Intuitive und leistungsfähige Installations-App</h1> <?php

	// Download-Pfad definieren
	$url = 'https://github.com/cbradigital/cbra-iulia-core/archive/refs/heads/main.zip';

	// Speicherpfad und Dateinamen festlegen
	$workDir = get_stylesheet_directory() . '/cb-iulia';
	$tmpDir = $workDir . '/cbra-iulia-core-main';
	$updFile = $workDir . '/cbra-iulia-core.zip';

	// Update herunterladen und in Verzeichnis kopieren...
	$tmpFile = download_url($url);
	rename($tmpFile, $updFile);

	// ... und ins Hauptverzeichnis extrahieren
	$zip = new ZipArchive;
	if($zip->open($updFile)) {
		$zip->extractTo($workDir);
		$zip->close();
		if(is_Dir($tmpDir)) {
			foreach(glob($tmpDir . '/*') as $file) {
				$newName = str_replace('/cbra-iulia-core-main', '', $file);
				rename($file, $newName);
			}
		}
		if(!scandir($tmpDir)) {
			rmdir($tmpDir);
		}
	}
	?> <a href="/wp-admin/admin.php?page=cb-iulia">Zurück zum Dashboard</a> <?php
}

?>
