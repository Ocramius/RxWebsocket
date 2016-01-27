<?php
namespace Rx\Websocket\RFC6455\Messaging\Protocol;

class Message implements \IteratorAggregate, MessageInterface {
    /**
     * @var \SplDoublyLinkedList
     */
    private $_frames;

    public function __construct() {
        $this->_frames = new \SplDoublyLinkedList;
    }

    public function getIterator() {
        return $this->_frames;
    }

    /**
     * {@inheritdoc}
     */
    public function count() {
        return count($this->_frames);
    }

    public function offsetExists($index) {
        return $this->_frames->offsetExists($index);
    }

    public function offsetGet($index) {
        return $this->_frames->offsetGet($index);
    }

    public function offsetSet($index, $newval) {
        throw new \DomainException('Frame access in messages is read-only');
    }

    public function offsetUnset($index) {
        throw new \DomainException('Frame access in messages is read-only');
    }

    /**
     * {@inheritdoc}
     */
    public function isCoalesced() {
        if (count($this->_frames) == 0) {
            return false;
        }

        $last = $this->_frames->top();

        return ($last->isCoalesced() && $last->isFinal());
    }

    /**
     * {@inheritdoc}
     */
    public function addFrame(FrameInterface $fragment) {
        $this->_frames->push($fragment);
    }

    /**
     * {@inheritdoc}
     */
    public function getOpcode() {
        if (count($this->_frames) == 0) {
            throw new \UnderflowException('No frames have been added to this message');
        }

        return $this->_frames->bottom()->getOpcode();
    }

    /**
     * {@inheritdoc}
     */
    public function getPayloadLength() {
        $len = 0;

        foreach ($this->_frames as $frame) {
            try {
                $len += $frame->getPayloadLength();
            } catch (\UnderflowException $e) {
                // Not an error, want the current amount buffered
            }
        }

        return $len;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload() {
        if (!$this->isCoalesced()) {
            throw new \UnderflowException('Message has not been put back together yet');
        }

        $buffer = '';

        foreach ($this->_frames as $frame) {
            $buffer .= $frame->getPayload();
        }

        return $buffer;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents() {
        if (!$this->isCoalesced()) {
            throw new \UnderflowException("Message has not been put back together yet");
        }

        $buffer = '';

        foreach ($this->_frames as $frame) {
            $buffer .= $frame->getContents();
        }

        return $buffer;
    }

    /**
     * @return boolean
     */
    public function isBinary() {
        if ($this->_frames->isEmpty()) {
            throw new \UnderflowException('Not enough data has been received to determine if message is binary');
        }

        return Frame::OP_BINARY === $this->_frames->bottom()->getOpcode();
    }
}
