<?php

namespace Alaa\CustomEmail\Model;

use Magento\Framework\Mail\Template\TransportBuilder;

/**
 * Class SendEmail
 * @package Alaa\CustomEmail\Model
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SendEmail implements SendEmailInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * SendEmail constructor.
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(TransportBuilder $transportBuilder)
    {
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * @param array $config
     * @return \Alaa\CustomEmail\Model\SendEmailInterface
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param array $to
     * @return \Alaa\CustomEmail\Model\SendEmailInterface
     */
    public function send(array $to)
    {
        $this->prepareEmail();
        $this->transportBuilder->addTo($to['email'], $to['name']);
        $this->transportBuilder->getTransport()->sendMessage();
        return $this;
    }

    /**
     * @return $this
     */
    protected function prepareEmail()
    {
        foreach ($this->config as $method => $value) {
            call_user_func_array(
                [$this->transportBuilder, $this->getMethod($method)],
                [$value]
            );
        }

        return $this;
    }

    /**
     * @param  string $name
     * @return string
     */
    protected function getMethod($name)
    {
        $toCamelCase = '';
        if (strpos($name, '_') !== false) {
            $parts = explode('_', $name);
            foreach ($parts as &$part) {
                $part = ucfirst($part);
                $toCamelCase = implode('', $parts);
            }
        } else {
            $toCamelCase = ucfirst($name);
        }

        $method =  'set' . $toCamelCase;
        return $method;
    }
}