<?php
namespace Rx\Websocket\RFC6455\Messaging\Streaming;

use Rx\Websocket\RFC6455\Messaging\Protocol\MessageInterface;
use Rx\Websocket\RFC6455\Messaging\Protocol\FrameInterface;

interface ContextInterface {
    /**
     * @param FrameInterface $frame
     * @return FrameInterface
     */
    public function setFrame(FrameInterface $frame = null);

    /**
     * @return \Rx\Websocket\RFC6455\Messaging\Protocol\FrameInterface
     */
    public function getFrame();

    /**
     * @param MessageInterface $message
     * @return MessageInterface
     */
    public function setMessage(MessageInterface $message = null);

    /**
     * @return \Rx\Websocket\RFC6455\Messaging\Protocol\MessageInterface
     */
    public function getMessage();

    public function onMessage(MessageInterface $msg);
    public function onPing(FrameInterface $frame);
    public function onPong(FrameInterface $frame);

    /**
     * @param $code int
     */
    public function onClose($code);
}