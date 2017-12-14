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
     * @return \Alaa\CustomEmail\Model\SendEmailInterface
     */
    public function send()
    {
        $this->prepareEmail();
        $this->transportBuilder->getTransport()->sendMessage();
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function prepareEmail()
    {
        foreach ($this->config as $method => $value) {
            if (!method_exists($this->transportBuilder, $this->getMethod($method))
                || $this->getMethod($method) === null
            ) {
                $message = sprintf(
                    'Undefined method %s of class %s',
                    $this->getMethod($method),
                    get_class($this->transportBuilder)
                );
                throw new \Exception(__($message));
            }
            call_user_func_array(
                [$this->transportBuilder, $this->getMethod($method)],
                $value
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

        foreach (['set', 'add'] as $prefix) {
            $searchMethod = $prefix . $toCamelCase;
            if (array_key_exists($searchMethod, array_flip($this->getMethods()))) {
                return $searchMethod;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    protected function getMethods()
    {
        static $methods = [];
        if (empty($methods)) {
            $methods = get_class_methods($this->transportBuilder);
        }

        return $methods;
    }
}