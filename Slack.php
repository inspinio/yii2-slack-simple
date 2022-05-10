<?php

namespace inspinio\slack;

use yii\base\Component;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Exception;

/**
 * Class Slack
 * @package inspinio\slack
 */
class Slack extends Component
{
    /**
     * @var string URL of Slack incoming webhook integration
     */
    public $url;

    /**
     * @var string sender username
     */
    public $username = 'logger';

    /**
     * @var string Default message content. Useful when sending only attachments
     */
    public $defaultText = "Message from Yii application";

    /**
     * @var string Default channel to send messages to
     */
    public $defaultChannel;

    /**
     * @var Client
     */
    public $httpclient;

    /**
     * @return void
     */
    public function init()
    {
        $this->httpclient = new Client();
    }

    /**
     * @param $text
     * @param array $attachments
     * @param $channel
     * @return void
     * @throws Exception
     */
    public function send($text = null, array $attachments = [], $channel = null)
    {
        $this->httpclient->post($this->url, [
            'payload' => Json::encode($this->getPayload($text, $attachments, $channel)),
        ])->send();
    }

    /**
     * @param $text
     * @param $attachments
     * @param $channel
     * @return array
     */
    protected function getPayload($text = null, $attachments = [], $channel = null): array
    {
        if ($text === null) {
            $text = $this->defaultText;
        }

        if ($channel === null) {
            $channel = $this->defaultChannel;
        }

        $payload = [
            'text' => $text,
            'username' => $this->username,
            'attachments' => $attachments,
        ];

        if ($channel !== null) {
            $payload['channel'] = $channel;
        }

        return $payload;
    }
}
