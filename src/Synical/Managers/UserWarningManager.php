<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace Synical\Managers;

    use msqg\QueryBuilder;
    use Synical\Exceptions\DatabaseException;
    use Synical\Exceptions\TooManyWarningsException;
    use Synical\Exceptions\UserWarningRecordNotFoundException;
    use Synical\Objects\UserWarning;
    use Synical\Objects\UserWarning\Warning;
    use Synical\Synical;
    use ZiProto\ZiProto;

    class UserWarningManager
    {
        private $synical;

        /**
         * @param Synical $synical
         */
        public function __construct(Synical $synical)
        {
            $this->synical = $synical;
        }

        /**
         * Issues a warning to the user
         *
         * @param string $chat_id
         * @param int $user_id
         * @param int $issuer_user_id
         * @param string $reason
         * @return Warning
         * @throws DatabaseException
         * @throws TooManyWarningsException
         * @throws UserWarningRecordNotFoundException
         */
        public function issueWarning(string $chat_id, int $user_id, int $issuer_user_id, string $reason): Warning
        {
            $UserWarning = $this->getRecord($chat_id, $user_id);
            if(count($UserWarning->Warnings) >= 25)
                throw new TooManyWarningsException();

            $Warning = new Warning();
            $Warning->ID = hash('crc32', $chat_id . $user_id . $issuer_user_id . $reason . time());
            $Warning->UserIssuerID = $issuer_user_id;;
            $Warning->Timestamp = time();

            $UserWarning->Warnings[$Warning->ID] = $Warning;

            $this->updateRecord($UserWarning);

            return $Warning;
        }

        /**
         * Removes an existing warning from the user
         *
         * @param string $chat_id
         * @param int $user_id
         * @param string $warning_id
         * @return bool
         * @throws DatabaseException
         * @throws UserWarningRecordNotFoundException
         */
        public function removeWarning(string $chat_id, int $user_id, string $warning_id): bool
        {
            $UserWarning = $this->getRecord($chat_id, $user_id);

            if(isset($UserWarning[$warning_id]))
            {
                unset($UserWarning->Warnings[$warning_id]);
                $this->updateRecord($UserWarning);

                return true;
            }

            return false;
        }

        /**
         * Purges all existing warnings from a user
         *
         * @param string $chat_id
         * @param int $user_id
         * @return bool
         * @throws DatabaseException
         * @throws UserWarningRecordNotFoundException
         */
        public function clearWarnings(string $chat_id, int $user_id): bool
        {
            $UserWarning = $this->getRecord($chat_id, $user_id);
            $UserWarning->Warnings = [];
            $this->updateRecord($UserWarning);

            return true;
        }

        /**
         * Creates a record if it doesn't exist
         *
         * @param string $chat_id
         * @param int $user_id
         * @return UserWarning
         * @throws DatabaseException
         * @noinspection PhpCastIsUnnecessaryInspection
         */
        public function createRecord(string $chat_id, int $user_id): UserWarning
        {
            $UserWarning = new UserWarning();
            $UserWarning->ChatID = $chat_id;
            $UserWarning->UserID = $user_id;
            $UserWarning->Warnings = [];
            $UserWarning->CreatedTimestamp = time();
            $UserWarning->LastUpdatedTimestamp = time();
            $ID = hash('crc32', $chat_id . $user_id);

            $Query = QueryBuilder::insert_into('user_warnings', [
                'id' => $this->synical->getDatabase()->real_escape_string($ID),
                'chat_id' => $this->synical->getDatabase()->real_escape_string($chat_id),
                'user_id' => (int)$user_id,
                'created_timestamp' => (int)$UserWarning->CreatedTimestamp,
                'last_updated_timestamp' => (int)$UserWarning->LastUpdatedTimestamp
            ]);

            $QueryResults = $this->synical->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->synical->getDatabase()->error, $Query, $this->synical->getDatabase()->errno);

            return $UserWarning;
        }

        /**
         * Gets an existing record from the database, optionally creates if it doesn't exist
         *
         * @param string $chat_id
         * @param int $user_id
         * @param bool $create
         * @return UserWarning
         * @throws DatabaseException
         * @throws UserWarningRecordNotFoundException
         */
        public function getRecord(string $chat_id, int $user_id, bool $create=true): UserWarning
        {
            $ID = hash('sha1', $chat_id . $user_id);
            $Query = QueryBuilder::select('user_warnings', [
                'chat_id',
                'user_id',
                'warnings',
                'last_updated_timestamp',
                'created_timestamp'
            ], 'id', $ID);

            $QueryResults = $this->synical->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->synical->getDatabase()->error, $Query, $this->synical->getDatabase()->errno);

            if($QueryResults->num_rows == 0)
            {
                if($create)
                    return $this->createRecord($chat_id, $user_id);

                throw new UserWarningRecordNotFoundException();
            }

            $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
            $Row['warnings'] = ZiProto::decode($Row['warnings']);

            return UserWarning::fromArray($Row);
        }

        /**
         * Updates an existing user warning record
         *
         * @param UserWarning $userWarning
         * @return UserWarning
         * @throws DatabaseException
         * @noinspection PhpCastIsUnnecessaryInspection
         */
        public function updateRecord(UserWarning $userWarning): UserWarning
        {
            $ID = hash('sha1', $userWarning->ChatID . $userWarning->UserID);
            $Warnings = $userWarning->toArray()['warnings'];
            $userWarning->LastUpdatedTimestamp = (int)time();
            $Query = QueryBuilder::update('user_warnings', [
                'warnings' => $this->synical->getDatabase()->real_escape_string(ZiProto::encode($Warnings)),
                'last_updated_timestamp' => (int)$userWarning->LastUpdatedTimestamp
            ], 'id', $this->synical->getDatabase()->real_escape_string($ID));

            $QueryResults = $this->synical->getDatabase()->query($Query);
            if($QueryResults == false)
                throw new DatabaseException($this->synical->getDatabase()->error, $Query, $this->synical->getDatabase()->errno);

            return $userWarning;
        }
    }