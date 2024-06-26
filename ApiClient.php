<?php
class ApiClient {
    private $baseUrl;
    private $bearerToken;

    public function __construct(string $baseUrl, string $bearerToken) {
        $this->baseUrl = $baseUrl;
        $this->bearerToken = $bearerToken;
    }

    public function getProperties(string $codiceFiscale): ?array {
        $url = $this->baseUrl . "/immobili";
        $data = ['codice_fiscale' => $codiceFiscale];
        $dataJson = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->bearerToken
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            return null;
        }
        curl_close($ch);

        return json_decode($response, true);
    }
}
?>
