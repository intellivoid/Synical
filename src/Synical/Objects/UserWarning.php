<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace Synical\Objects;

    use Synical\Objects\UserWarning\Warning;

    class UserWarning
    {
        /**
         * The chat ID associated with the warnings
         *
         * @var string
         */
        public $ChatID;

        /**
         * The User ID associated with the warnings
         *
         * @var int
         */
        public $UserID;

        /**
         * An array of warning objects that has been issued so far
         *
         * @var Warning[]
         */
        public $Warnings;

        /**
         * @var int
         */
        public $LastUpdatedTimestamp;

        /**
         * The Unix Timestamp for when
         *
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            $warnings = [];
            foreach($this->Warnings as $warning)
                $warnings[] = $warning->toArray();

            return [
                'chat_id' => $this->ChatID,
                'user_id' => $this->UserID,
                'warnings' => $warnings,
                'last_updated_timestamp' => $this->LastUpdatedTimestamp,
                'created_timestamp' => $this->CreatedTimestamp,
            ];
        }

        /**
         * Constructs object from an array representation of the object
         *
         * @param array $data
         * @return UserWarning
         */
        public static function fromArray(array $data): UserWarning
        {
            $UserWarningsObject = new UserWarning();

            if(isset($data['chat_id']))
                $UserWarningsObject->ChatID = $data['chat_id'];

            if(isset($data['user_id']))
                $UserWarningsObject->UserID = $data['user_id'];

            if(isset($data['warnings']))
            {
                $UserWarningsObject->Warnings = [];

                foreach($data['warnings'] as $warning)
                    $UserWarningsObject->Warnings[] = Warning::fromArray($warning);
            }

            if(isset($data['last_updated_timestamp']))
                $UserWarningsObject->LastUpdatedTimestamp = $data['last_updated_timestamp'];

            if(isset($data['created_timestamp']))
                $UserWarningsObject->CreatedTimestamp = $data['created_timestamp'];

            return $UserWarningsObject;
        }
    }