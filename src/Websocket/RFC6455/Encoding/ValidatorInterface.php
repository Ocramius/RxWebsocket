<?php
namespace Rx\Websocket\RFC6455\Encoding;

/**
 * @todo Probably move this into Messaging\Validation
 */
interface ValidatorInterface {
    /**
     * Verify a string matches the encoding type
     * @param  string $str      The string to check
     * @param  string $encoding The encoding type to check against
     * @return bool
     */
    function checkEncoding($str, $encoding);
}
