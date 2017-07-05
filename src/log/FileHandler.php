<?php

namespace swoft\log;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 *
 *
 * @uses      FileHandler
 * @version   2017年07月05日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class FileHandler extends AbstractProcessingHandler
{

    protected $filePath = "";

    public function __construct($filePath, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->filePath = $filePath;
    }

    public function handleBatch(array $records)
    {
        $records = $this->recordFilter($records);
        $lines = array_column($records, 'formatted');
        $this->write($lines);
    }

    protected function write(array $records)
    {
        $messageText = implode("\n", $records) . "\n";
        $this->createDir();
        while (true) {
            $result = \Swoole\Async::writeFile($this->filePath, $messageText, null, FILE_APPEND);
            if ($result == true) {
                break;
            }
        }
    }

    private function recordFilter(array $records)
    {
        $messages = [];
        foreach ($records as $record) {
            if (!isset($record['level'])) {
                continue;
            }
            if (!$this->isHandling($record)) {
                continue;
            }

            $record = $this->processRecord($record);
            $record['formatted'] = $this->getFormatter()->format($record);

            $messages[] = $record;
        }
        return $messages;
    }

    private function createDir()
    {
        $dir = dirname($this->filePath);
        if ($dir !== null && !is_dir($dir)) {
            $status = mkdir($dir, 0777, true);
            if ($status === false) {
                throw new \UnexpectedValueException(sprintf('There is no existing directory at "%s" and its not buildable: ', $dir));
            }
        }
    }
}