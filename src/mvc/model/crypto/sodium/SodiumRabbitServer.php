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

/**
 * Class SodiumRabbitServer
 *
 * @package qFW\mvc\model\crypto\sodium
 */
class SodiumRabbitServer
{

    private $connection = null;
    private $channel = null;
    private $queue = '';

    private $sod = null;

    /**
     * SodiumRabbitServer constructor.
     *
     * @param string $serverIp
     * @param string $serverPort
     * @param string $serverUser
     * @param string $serverPwd
     * @param string $queue
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

        // Declares a queue from which to read hash requests
        $this->channel->queue_declare($this->queue, false, false, false, false);

        $this->sod = new Sodium();
    }

    /**
     *
     */
    public function processRPCGetHash()
    {
        echo " [x] Awaiting RPC Get Hash requests\n";

        $callback = function ($req) {
            $pwd = $req->body;

            echo " [.] calculate hash of $pwd \n";

            $msg = new AMQPMessage(
                $this->sod->getHash($pwd),
                array('correlation_id' => $req->get('correlation_id'))
            );
            // '' = default excange (direct)
            $req->delivery_info['channel']->basic_publish($msg, '', $req->get('reply_to'));
            $req->delivery_info['channel']->basic_ack($req->delivery_info['delivery_tag']);

            echo " [x] Awaiting RPC Get Hash requests\n";
        };

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($this->queue, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }

    /**
     *
     */
    public function processRPCVerifyHash()
    {
        echo " [x] Awaiting RPC Verify Hash requests\n";

        $callback = function ($req) {
            $json = json_decode($req->body, true, 2);

            echo " [.] Verify hash of {$json['hash']} \n";

            $res = $this->sod->pwdVerify($json['hash'], $json['pwd']);
            if ($res) {
                $body = '1';
            } else {
                $body = '0';
            }

            $msg = new AMQPMessage(
                $body,
                array('correlation_id' => $req->get('correlation_id'))
            );
            // '' = default excange (direct)
            $req->delivery_info['channel']->basic_publish($msg, '', $req->get('reply_to'));
            $req->delivery_info['channel']->basic_ack($req->delivery_info['delivery_tag']);

            echo " [x] Awaiting RPC Verify Hash requests\n";
        };

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($this->queue, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}
