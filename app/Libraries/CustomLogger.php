<?php

namespace App\Libraries;

use CodeIgniter\Log\Logger;
use Psr\Log\LoggerInterface;

class CustomLogger extends Logger implements LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function emergency($message, array $context = []): void
    {
        $this->log('emergency', $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function alert($message, array $context = []): void
    {
        $this->log('alert', $message, $context);
    }

    /**
     * Critical conditions.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function warning($message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = []): void
    {
        $this->log('notice', $message, $context);
    }

    /**
     * Interesting events.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function info($message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string|\Stringable $message
     * @param array $context
     *
     * @return void
     */
    public function debug($message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string|\Stringable $message
     * @param array $context
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        parent::log($level, $message, $context);
    }
} 