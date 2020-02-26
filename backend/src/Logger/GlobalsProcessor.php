<?php


namespace App\Logger;


use Monolog\Processor\ProcessorInterface;

final class GlobalsProcessor implements ProcessorInterface
{
    
    /**
     * @param array $records
     *
     * @return array The processed records
     */
    public function __invoke(array $records)
    {
        $records['extra']['$_server'] = $_SERVER;
        $records['extra']['$_get']    = $_GET;
        $records['extra']['$_post']   = $_POST;
        $records['extra']['$_files']  = $_FILES;
        
        return $records;
    }
}