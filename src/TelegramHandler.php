<?php
namespace rahimi\TelegramHandler;

use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Telegram Handler For Monolog
 *
 * This class helps you in logging your application events
 * into telegram using it's API.
 *
 * @author Moein Rahimi <m.rahimi2150@gmail.com>
 */

class TelegramHandler extends AbstractProcessingHandler
{
    protected $timeOut;
    protected $token;
    protected $channel;
    protected $dateFormat;

    /**
     * @var array
     */
    protected $curlOptions;

    const host = 'https://api.telegram.org/bot';

    /**
     * getting token a channel name from Telegram Handler Object.
     *
     * @param string $token Telegram Bot Access Token Provided by BotFather
     * @param string $channel Telegram Channel userName
     * @param string $timeZone set default date timezone
     * @param string $dateFormat set default date format
     * @param int $timeOut curl timeout
     * @param array $curlOptions
     */
    public function __construct(
        $token,
        $channel,
        $timeZone = 'UTC',
        $dateFormat = 'Y-m-d H:i:s',
        $timeOut = 100,
        $curlOptions = []
    ) {
        $this->token   = $token;
        $this->channel = $channel;
        $this->dateFormat = $dateFormat;
        $this->timeOut = $timeOut;
        date_default_timezone_set($timeZone);
        $this->curlOptions = $curlOptions;
    }

    /**
     * format the log to send
     * @param $record[] log data
     * @return void
     */
    public function write(array $record)
    {
        $format = new LineFormatter;
        $context = $record['context'] ? $format->stringify($record['context']) : '';
        $date =  date($this->dateFormat);
        $message =  $date . ' ' . $record['channel'].'.'.$record['level_name'] . ' ' . PHP_EOL . $this->getEmoji($record['level']) . ' ' . $record['message'] . ' ' . $context . ' ' . json_encode($record['extra']);
        $this->send($message);
    }

    /**
     *    send log to telegram channel
     *    @param string $message Text Message
     *    @return void
     *
     */
    public function send($message)
    {
      try{
        $ch = curl_init();
        $url = self::host . $this->token . "/SendMessage";
        $timeOut = $this->timeOut;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut); 
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            'text'    => $message,
            'chat_id' => $this->channel,
        )));
        foreach ($this->curlOptions as $option => $value) {
            curl_setopt($ch, $option, $value);
        }
         $result = curl_exec($ch);
         $result = json_decode($result,1);
         if($result['ok'] === false){
            echo  'telegram api response : ' .$result['description'];
         }
       }catch(Exception $error){
         echo $error;
       }
    }

    /**
     * make emoji for log events
     * @return array
     *
     */
    protected function emojiMap()
    {
        return [
            Logger::DEBUG     => '🚧',
            Logger::INFO      => '‍🗨',
            Logger::NOTICE    => '🕵',
            Logger::WARNING   => '⚡️',
            Logger::ERROR     => '🚨',
            Logger::CRITICAL  => '🤒',
            Logger::ALERT     => '👀',
            Logger::EMERGENCY => '🤕',
        ];
    }

    /**
     * return emoji for given level
     *
     * @param $level
     * @return string
     */
    protected function getEmoji($level)
    {
        $levelEmojiMap = $this->emojiMap();
        return $levelEmojiMap[$level];
    }

}
