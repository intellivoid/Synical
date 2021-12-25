<?php

    namespace Synical\Exceptions;

    use Throwable;

    class ChatMemberCacheRecordNotFoundException extends \Exception
    {
        /**
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct(string $message = "The requested chat member cache was not found in the database", int $code = 0, ?Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
            $this->message = $message;
            $this->code = $code;
        }
    }