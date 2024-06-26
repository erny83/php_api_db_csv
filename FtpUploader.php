<?php
class FtpUploader {
    private $ftpHost;
    private $ftpUsername;
    private $ftpPassword;
    private $ftpConn;

    public function __construct(string $ftpHost, string $ftpUsername, string $ftpPassword) {
        $this->ftpHost = $ftpHost;
        $this->ftpUsername = $ftpUsername;
        $this->ftpPassword = $ftpPassword;
        $this->ftpConn = ftp_connect($this->ftpHost);

        if (!$this->ftpConn || !ftp_login($this->ftpConn, $this->ftpUsername, $this->ftpPassword)) {
            throw new \Exception("Could not connect to FTP server.");
        }
    }

    public function uploadFile(string $localFile, string $remoteFile): bool {
        return ftp_put($this->ftpConn, $remoteFile, $localFile, FTP_ASCII);
    }

    public function __destruct() {
        if ($this->ftpConn) {
            ftp_close($this->ftpConn);
        }
    }
}
?>
