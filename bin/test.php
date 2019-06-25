<?php

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Annotation\Mapping\Primary;
use Swoft\Bean\BeanFactory;

interface SmsInterface
{
    public function send(string $content): bool;
}


/**
 * Class AliyunSms
 *
 * @since 2.0
 *
 * @Bean()
 * @Primary()
 */
class AliyunSms implements SmsInterface
{
    /**
     * @param string $content
     *
     * @return bool
     */
    public function send(string $content): bool
    {
        return true;
    }
}

/**
 * Class QcloudSms
 *
 * @since 2.0
 *
 * @Bean()
 */
class QcloudSms implements SmsInterface
{
    /**
     * @param string $content
     *
     * @return bool
     */
    public function send(string $content): bool
    {
        return true;
    }
}

/**
 * Class Sms
 *
 * @since 2.0
 *
 * @Bean()
 */
class Sms implements SmsInterface
{
    /**
     * @Inject()
     *
     * @var SmsInterface
     */
    private $smsInterface;

    /**
     * @param string $content
     *
     * @return bool
     */
    public function send(string $content): bool
    {
        return $this->smsInterface->send($content);
    }
}

/* @var SmsInterface $sms*/
$sms = BeanFactory::getBean(Sms::class);
$sms->send('sms content');