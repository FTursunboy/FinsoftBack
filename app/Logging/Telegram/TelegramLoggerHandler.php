<?php

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;

final class TelegramLoggerHandler extends AbstractProcessingHandler
{

    public int $chatId;

    public string $token;

    public int $messageThreadId;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);

        $this->chatId = $config['chat_id'];
        $this->token = $config['token'];
        $this->messageThreadId = $config['messageThreadId'];

        parent::__construct($level);
    }


    protected function write(LogRecord $record): void
    {

        TelegramBotApi::sendMessage(
            $this->token,
            $this->chatId,
            $this->messageThreadId,
            $record->formatted
        );
    }
}
