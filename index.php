<?php
require_once 'Database.php';
require_once 'ApiClient.php';
require_once 'CsvCreator.php';
require_once 'FtpUploader.php

// Configurazione
$baseUrl = 'https://api.example.com';
$bearerToken = 'bearer_token';
$codiceFiscale = 'codice_fiscale';
$ftpHost = 'ftp.example.com';
$ftpUsername = 'ftp_username';
$ftpPassword = 'ftp_password';

// Creazione istanze
$db = new Database();
$apiClient = new ApiClient($baseUrl, $bearerToken);
$csvCreator = new CsvCreator();
$ftpUploader = new FtpUploader($ftpHost, $ftpUsername, $ftpPassword);

// Ottieni i dati tramite API
$response = $apiClient->getProperties($codiceFiscale);

// Controlla la risposta e salva i dati nel database
if ($response && isset($response['immobili'])) {
    $immobili = $response['immobili'];
    foreach ($immobili as $immobile) {
        $data = [
            'id' => $immobile['id'],
            'indirizzo' => $immobile['indirizzo'],
            'valore' => $immobile['valore'],
            'codice_fiscale_proprietario' => $codiceFiscale
        ];
        $db->save('immobili', $data);
    }

    // Crea file CSV con i dati
    $header = ['ID', 'Indirizzo', 'Valore', 'Codice Fiscale Proprietario'];
    $csvData = [];
    foreach ($immobili as $immobile) {
        $csvData[] = [
            $immobile['id'],
            $immobile['indirizzo'],
            $immobile['valore'],
            $codiceFiscale
        ];
    }
    $csvCreator->createCsv('immobili.csv', $header, $csvData);
    
    echo "Dati salvati nel database e file CSV creato.";

    // Carica il file CSV via FTP
    if ($ftpUploader->uploadFile('immobili.csv', 'remote/immobili.csv')) {
        echo "File CSV degli immobili caricato via FTP.";
    } else {
        echo "Errore nel caricamento via FTP del file CSV degli immobili.";
    }
} else {
    echo "Errore nella risposta API.";
}
?>
