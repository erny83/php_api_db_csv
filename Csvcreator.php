<?php
class CsvCreator {
    public function createCsv(string $filename, array $header, array $data): void {
        $file = fopen($filename, 'w');

        // Scrive l'intestazione
        fputcsv($file, $header);

        // Scrive i dati
        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }
}
?>
