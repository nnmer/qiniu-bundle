<?php
namespace Nnmer\QiniuBundle\Events;


use Symfony\Component\EventDispatcher\Event;

class QiniuUploaderEvent extends Event
{
    private $payload = [];

    public function __construct($payload = [])
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}