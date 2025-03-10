<?php

class Logger {
    private $logFile;
    private $logLevel;

    public function __construct($logFile = 'logs/app_log.log', $logLevel = 'debug') {
        $this->logFile = $logFile;
        $this->logLevel = $logLevel;
    }

    private function log($level, $message) {
        $levels = [
            'emergency' => 1,
            'alert' => 2,
            'critical' => 3,
            'error' => 4,
            'warning' => 5,
            'notice' => 6,
            'info' => 7,
            'debug' => 8
        ];

        if ($levels[$level] <= $levels[$this->logLevel]) {
            $date = date('Y-m-d H:i:s');
            $logMessage = "[$date] [$level] - $message" . PHP_EOL;
            file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        }
    }

    public function emergency($message) {
        $this->log('emergency', $message);
    }

    public function alert($message) {
        $this->log('alert', $message);
    }

    public function critical($message) {
        $this->log('critical', $message);
    }

    public function error($message) {
        $this->log('error', $message);
    }

    public function warning($message) {
        $this->log('warning', $message);
    }

    public function notice($message) {
        $this->log('notice', $message);
    }

    public function info($message) {
        $this->log('info', $message);
    }

    public function debug($message) {
        $this->log('debug', $message);
    }
}
?>
