<?php

/**
 * GetResponse API Exception
 */
class Unauthorized_Request_Exception extends Exception {

    /**
     * @param string $statusCode
     * @param string $description
     *
     * @return Unauthorized_Request_Exception
     */
    public static function createForInvalidResponseStatus($statusCode, $description) {
        return new self("Invalid response status: " . $statusCode . ", " . $description);
    }
}
