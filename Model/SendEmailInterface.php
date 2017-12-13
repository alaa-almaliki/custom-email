<?php

namespace Alaa\CustomEmail\Model;

/**
 * Interface SendEmailInterface
 * @package Alaa\CustomEmail\Model
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface SendEmailInterface
{
    /**
     * @param array $config
     * @return \Alaa\CustomEmail\Model\SendEmailInterface
     */
    public function setConfig(array $config);

    /**
     * @param array $to
     * @return \Alaa\CustomEmail\Model\SendEmailInterface
     */
    public function send(array $to);
}
