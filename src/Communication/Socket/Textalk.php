<?php
declare(strict_types=1);

namespace HeadlessChromium\Communication\Socket;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use WebSocket\Client;

class Textalk implements SocketInterface
{
    use LoggerAwareTrait;

    /**
     * An auto incremented counter to uniquely identify each socket instance
     * @var int
     */
    private static $socketIdCounter = 0;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Id of this socket generated from self::$socketIdCounter
     * @var int
     */
    protected $socketId = 0;

    public static function build(string $url, $origin, ?LoggerInterface $logger)
    {
        $client = new Client($url, [
            'headers' => [
                'origin' => $origin
            ]
        ]);
        return new self($client, $logger);
    }

    /**
     * @param Client               $client
     * @param LoggerInterface|null $logger
     */
    public function __construct(Client $client, LoggerInterface $logger = null)
    {
        $this->client = $client;

        $this->setLogger($logger ?? new NullLogger());

        $this->socketId = ++self::$socketIdCounter;
    }

    public function sendData($data)
    {
        // log
        $this->logger->debug('socket(' . $this->socketId . '): → sending data:' . $data);

        // send data
        $this->client->send($data);
        return true;
    }

    public function receiveData(): array
    {
        $playload = $this->client->receive();

        // log
        $this->logger->debug('socket(' . $this->socketId . '): ← receiving data:' . $playload);

        return [
            $playload,
        ];
    }

    public function connect()
    {
        // log
        $this->logger->debug('socket(' . $this->socketId . '): connecting');

        return true;
    }

    public function isConnected()
    {
        return $this->client->isConnected();
    }

    public function disconnect($reason = 1000)
    {
        // log
        $this->logger->debug('socket(' . $this->socketId . '): disconnecting');

        $this->client = null;

        return true;
    }
}
