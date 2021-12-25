<?php

    namespace Synical\Exceptions;

    use Exception;
    use Throwable;

    class DatabaseException extends Exception
    {
        private string $query;

        /**
         * @param string $message
         * @param string $query
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct(string $message = "", string $query="", int $code = 0, ?Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
            $this->message = $message;
            $this->query = $query;
            $this->code = $code;
        }

        /**
         * @return string
         */
        public function getQuery(): string
        {
            return $this->query;
        }
    }