<?php
namespace App\Components\Logging;

use InvalidArgumentException;

/**
 * Application filesystem logger. Writes log messages to files
 *
 * @author Basile Trujillo
 * @link https://bitbucket.org/L0gIn/slim-twig-laravel-html5-boilerplate
 */
class FileSystemLogWriter
{
    /**
     * @var string
     */
    private $logfile;

    /**
     * @param string $logfile the logfile path
     */
    public function __construct($logfile)
    {
        if(!is_writable(dirname($logfile))) {
            throw new InvalidArgumentException("Can not write log file '$logfile'.");
        }

        $this->logfile = $logfile;
    }

    /**
     * @param mixed $message
     */
    public function write($message)
    {
        file_put_contents($this->logfile, print_r($message, true), FILE_APPEND);
    }
}