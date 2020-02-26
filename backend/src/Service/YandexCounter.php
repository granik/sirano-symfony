<?php


namespace App\Service;


use App\Domain\Frontend\Interactor\CounterInterface;
use DateTime;
use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

final class YandexCounter implements CounterInterface, LoggerAwareInterface
{
    const YANDEX_URL         = 'https://api-metrika.yandex.ru/stat/v1/data';
    const CONNECTION_TIMEOUT = 10; // curl connection timeout in seconds
    
    /**
     * @var string
     */
    private $storagePath;
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $token;
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * YandexCounter constructor.
     *
     * @param string $storagePath
     * @param string $id
     * @param string $token
     */
    public function __construct(string $storagePath, string $id, string $token)
    {
        $this->storagePath = $storagePath;
        $this->id          = $id;
        $this->token       = $token;
    }
    
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function getTodayViews()
    {
        return $this->getViews('today');
    }
    
    public function getAllViews()
    {
        return $this->getViews('all');
    }
    
    /**
     * @param string $key
     *
     * @return bool|string
     * @throws Exception
     */
    private function getViews(string $key)
    {
        if (!$this->isInStorage($key)) {
            $this->updateInStorage($key);
        }
        
        $viewsTime = $this->getUpdatedTimeFromStorage($key);
        
        if ($this->isOverdue($viewsTime)) {
            $this->updateInStorage($key);
        }
        
        return $this->loadFromStorage($key);
    }
    
    private function isOverdue(DateTime $time)
    {
        $overdueTime = (new DateTime())->modify('-30 minute');
        $interval    = $overdueTime->diff($time);
        
        return $interval->invert === 1;
    }
    
    /**
     * @param string $key
     *
     * @return bool
     * @throws Exception
     */
    private function isInStorage(string $key)
    {
        $filename = $this->getFilename($key);
        
        if (!is_readable($filename)) {
            return false;
        }
        
        return !empty($this->loadFromStorage($key));
    }
    
    /**
     * @param string $key
     *
     * @return bool|string
     * @throws Exception
     */
    private function loadFromStorage(string $key)
    {
        $filename = $this->getFilename($key);
        
        $handle = fopen($filename, 'rb');
        
        if ($handle === false) {
            throw new Exception("Can't open file '$filename'");
        }
        
        $views = fgets($handle);
        
        fclose($handle);
        
        return $views;
    }
    
    private function getUpdatedTimeFromStorage(string $key)
    {
        return DateTime::createFromFormat('U', fileatime($this->getFilename($key)));
    }
    
    /**
     * @param string $key
     *
     * @throws Exception
     */
    private function updateInStorage(string $key)
    {
        try {
            $views = $this->loadFromYandex($key);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            
            $views = 0;
        }
    
        $filename = $this->getFilename($key);
        
        if (!file_exists($this->storagePath)) {
            mkdir($this->storagePath, 0777, true);
        }
        
        $handle = fopen($filename, 'wb');
        
        if ($handle === false) {
            throw new Exception("Can't open file '$filename'");
        }
        
        $result = fwrite($handle, $views);
        
        if ($result === false) {
            throw new Exception("Can't write to file '$filename'");
        }
        
        fclose($handle);
    }
    
    private function getFilename(string $key)
    {
        return "{$this->storagePath}/{$key}";
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     * @throws Exception
     */
    private function loadFromYandex(string $key)
    {
        $curl = curl_init();
        if ($curl === false) {
            throw new Exception('cURL init error.');
        }
        
        $params = [
            'ids'     => $this->id,
            'metrics' => 'ym:pv:pageviews',
            'date1'   => $key === 'today' ? 'today' : '2017-07-31',
        ];
        curl_setopt_array($curl, [
            CURLOPT_URL            => self::YANDEX_URL . '?' . http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => [
                'Accept-Language: en',
                "Authorization: OAuth {$this->token}",
                'Content-Type: application/x-yametrika+json'
            ],
            CURLOPT_CONNECTTIMEOUT => self::CONNECTION_TIMEOUT,
        ]);
        
        $encodedData = curl_exec($curl);
        if ($encodedData === false) {
            throw new Exception('cURL error: ' . curl_error($curl));
        }
        
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    
        if ($httpCode === 400 || $httpCode === 403) {
            $errorData = json_decode($encodedData, true);
            error_log(var_export($errorData, true));
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("HTTP response code: {$httpCode}\n{$encodedData}");
            }
    
            if (isset($errorData['message'])) {
                throw new Exception($errorData['message']);
            }
            
            throw new Exception("HTTP response code: {$httpCode}\n{$encodedData}");
        }
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP response code: {$httpCode}");
        }
    
        $data = json_decode($encodedData, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decode error: ' . json_last_error_msg());
        }
        
        if (!isset($data['totals'][0]) || !is_numeric($data['totals'][0])) {
            throw new Exception("Strange answer given:\n" . var_export($encodedData, true));
        }
        
        return $data['totals'][0];
    }
}