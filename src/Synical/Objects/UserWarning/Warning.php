<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace Synical\Objects\UserWarning;

    class Warning
    {
        /**
         * The Unique ID of the warning issued to the user
         *
         * @var string
         */
        public $ID;

        /**
         * The user ID of the admin who issued the warning
         *
         * @var int
         */
        public $UserIssuerID;

        /**
         * The Unix Timestamp for when this warning was issued
         *
         * @var int
         */
        public $Timestamp;

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                'id' => $this->ID,
                'user_issuer_id' => $this->UserIssuerID,
                'timestamp' => $this->Timestamp
            ];
        }

        /**
         * Constructs object form an array representation
         *
         * @param array $data
         * @return Warning
         */
        public static function fromArray(array $data): Warning
        {
            $WarningObject = new Warning();

            if(isset($data['id']))
                $WarningObject->ID = $data['id'];

            if(isset($data['user_issuer_id']))
                $WarningObject->UserIssuerID = $data['user_issuer_id'];

            if(isset($data['timestamp']))
                $WarningObject = (int)$data['timestamp'];

            return $WarningObject;
        }
    }