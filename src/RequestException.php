<?php

declare(strict_types=1);

namespace Hypervel\HttpClient;

use GuzzleHttp\Psr7\Message;

class RequestException extends HttpClientException
{
    /**
     * The truncation length for the exception message.
     */
    public static false|int $truncateAt = 120;

    /**
     * Create a new exception instance.
     */
    public function __construct(public Response $response)
    {
        parent::__construct($this->prepareMessage($response), $response->status());
    }

    /**
     * Enable truncation of request exception messages.
     */
    public static function truncate(): void
    {
        static::$truncateAt = 120;
    }

    /**
     * Set the truncation length for request exception messages.
     */
    public static function truncateAt(int $length): void
    {
        static::$truncateAt = $length;
    }

    /**
     * Disable truncation of request exception messages.
     */
    public static function dontTruncate(): void
    {
        static::$truncateAt = false;
    }

    /**
     * Prepare the exception message.
     */
    protected function prepareMessage(Response $response): string
    {
        $message = "HTTP request returned status code {$response->status()}";

        $summary = static::$truncateAt
            ? Message::bodySummary($response->toPsrResponse(), static::$truncateAt)
            : Message::toString($response->toPsrResponse());

        return is_null($summary) ? $message : $message .= ":\n{$summary}\n";
    }
}
