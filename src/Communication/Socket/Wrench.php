<?php

/*
 * This file is part of Chrome PHP.
 *
 * (c) Soufiane Ghzal <sghzal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HeadlessChromium\Communication\Socket;

use HeadlessChromium\Communication\SocketCreateInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wrench\Client as WrenchClient;
use Wrench\Payload\Payload;
use Wrench\Client as WrenchBaseClient;

class Wrench implements SocketInterface, SocketCreateInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * An auto incremented counter to uniquely identify each socket instance
     * @var int
     */
    private static $socketIdCounter = 0;

    /**
     * @var WrenchClient
     */
    protected $client;

    /**
     * Id of this socket generated from self::$socketIdCounter
     * @var int
     */
    protected $socketId = 0;

    public static function create(string $url, LoggerInterface $logger = null): SocketInterface
    {
        return new Wrench(new WrenchBaseClient($url, 'http://127.0.0.1'), $logger);
    }

    /**
     * @param WrenchClient $client
     */
    public function __construct(WrenchClient $client, LoggerInterface $logger = null)
    {
        $this->client = $client;

        $this->setLogger($logger ?? new NullLogger());

        $this->socketId = ++self::$socketIdCounter;
    }

    /**
     * @inheritdoc
     */
    public function sendData($data)
    {
        // log
        $this->logger->debug('socket(' . $this->socketId . '): → sending data:' . $data);

        // send data
        return $this->client->sendData($data);
    }

    /**
     * @inheritdoc
     */
    public function receiveData(): array
    {
        $playloads = $this->client->receive();

        $data = [];

        if ($playloads) {
            foreach ($playloads as $playload) {
                /** @var Payload */
                $dataString = $playload->getPayload();
                $data[] = $dataString;

                // log
                $this->logger->debug('socket(' . $this->socketId . '): ← receiving data:' . $dataString);
            }
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function connect()
    {
        // log
        $this->logger->debug('socket(' . $this->socketId . '): connecting');

        $connected = $this->client->connect();

        if ($connected) {
            // log
            $this->logger->debug('socket(' . $this->socketId . '): ✓ connected');
        } else {
            // log
            $this->logger->debug('socket(' . $this->socketId . '): ✗ could not connect');
        }

        return $connected;
    }

    /**
     * @inheritdoc
     */
    public function isConnected()
    {
        return $this->client->isConnected();
    }

    /**
     * @inheritdoc
     */
    public function disconnect($reason = 1000)
    {
        // log
        $this->logger->debug('socket(' . $this->socketId . '): disconnecting');

        $disconnected = $this->client->disconnect($reason);

        if ($disconnected) {
            // log
            $this->logger->debug('socket(' . $this->socketId . '): ✓ disconnected');
        } else {
            // log
            $this->logger->debug('socket(' . $this->socketId . '): ✗ could not disconnect');
        }

        return $disconnected;
    }
}
