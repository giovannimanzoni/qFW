<?php
/**
 * qFW - quick Framework, an PHP 7.2 Framework for speedup website development
 *
 * @mantainer Giovanni Manzoni (https://giovannimanzoni.com)
 * @license   GNU GENERAL PUBLIC LICENSE Version 3
 *
 */
declare(strict_types=1);

namespace qFW\mvc\model\crypto\sodium;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use qFW\mvc\model\crypto\uniqid\Uniqid;

/**
 * Class SodiumRabbitClient
 *
 * -> https://www.rabbitmq.com/tutorials/tutorial-six-php.html
 *
 * @package qFW\mvc\model\crypto\sodium
 */
class SodiumRabbitClient
{

    private const RABBIT_CORRELATION_ID_LENGHT = 255;

    /** @var string user selected queue */
    private $queue = '';

    private $connection = null;
    private $channel = null;
    private $callbackQueue = null;
    private $response = '';
    private $corrId = '';

    /**
     * SodiumRabbit constructor.
     *
     * @param string $serverIp
     * @param string $serverPort
     * @param string $serverUser
     * @param string $serverPwd
     * @param string $queue Max lenght 255 characters
     */
    public function __construct(
        string $serverIp,
        string $serverPort,
        string $serverUser,
        string $serverPwd,
        string $queue
    ) {
        $this->queue = $queue;

        $this->connection = new AMQPStreamConnection($serverIp, $serverPort, $serverUser, $serverPwd);
        $this->channel = $this->connection->channel();


        // Reply queue -> '' = random generated by RabbitMQ and set to exclusive in order to be read only from this
        //   script
        list($this->callbackQueue, ,) = $this->channel->queue_declare('', false, false, true, false);
        $this->channel->basic_consume(
            $this->callbackQueue,
            '',
            false,
            false,
            false,
            false,
            array($this, 'onResponse')
        );
    }

    /**
     * @param $rep
     */
    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corrId) {
            $this->response = $rep->body;
        } else {
            /*Ok, wait next id*/
        }
    }

    /**
     * @param string $pwd
     *
     * @return string
     */
    public function getHashByRMQ(string $pwd): string
    {
        /************************
         * Create queue and add to it
         */

        $this->response = null;
        $this->corrId = (new Uniqid)->get(self::RABBIT_CORRELATION_ID_LENGHT);

        $msg = new AMQPMessage(
            $pwd,
            array(
                'correlation_id' => $this->corrId,
                'reply_to'       => $this->callbackQueue
            )
        );

        // Write to the queue to send all requests of hash, with default excange (direct)
        $this->channel->basic_publish($msg, '', $this->queue);
        while (!$this->response) {
            $this->channel->wait();
        }
        return $this->response;
    }

    /**
     * Send hash and user password concatenated
     *
     * @param string $hash
     * @param string $pwd
     *
     * @return bool
     */
    public function pwdVerifyByRMQ(string $hash, string $pwd): bool
    {
        /************************
         * Create queue and add to it
         */

        $this->response = null;
        $this->corrId = (new Uniqid)->get(self::RABBIT_CORRELATION_ID_LENGHT);

        $json = array('hash' => $hash, 'pwd' => $pwd);
        $msgBody = json_encode($json);
        $msg = new AMQPMessage(
            $msgBody,
            array(
                'correlation_id' => $this->corrId,
                'reply_to'       => $this->callbackQueue
            )
        );

        // writes to the queue in which to send all requests of hash, with excange default (direct)
        $this->channel->basic_publish($msg, '', $this->queue);
        while (!$this->response) {
            $this->channel->wait();
        }
        if ($this->response == '1') {
            $ret = true;
        } else {
            $ret = false;
        }

        return $ret;
    }
}
