<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace Synical;

    use acm2\acm2;
    use acm2\Exceptions\ConfigurationNotDefinedException;
    use acm2\Objects\Schema;
    use mysqli;
    use Synical\Managers\ChatMemberCacheManager;
    use Synical\Managers\UserWarningManager;

    class Synical
    {
        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var null|mysqli
         */
        private $DatabaseConnection;

        /**
         * @var acm2
         */
        private $acm;

        /**
         * @var ChatMemberCacheManager
         */
        private $ChatMemberCacheManager;

        /**
         * @var UserWarningManager
         */
        private $UserWarningManager;

        /**
         * @throws ConfigurationNotDefinedException
         */
        public function __construct()
        {
            $this->acm = new acm2('Synical');

            // Database Schema Configuration
            $DatabaseSchema = new Schema();
            $DatabaseSchema->setName('Database');
            $DatabaseSchema->setDefinition('Host', '127.0.0.1');
            $DatabaseSchema->setDefinition('Port', '3306');
            $DatabaseSchema->setDefinition('Username', 'root');
            $DatabaseSchema->setDefinition('Password', 'root');
            $DatabaseSchema->setDefinition('Name', 'synical');
            $this->acm->defineSchema($DatabaseSchema);

            // Update the configuration
            $this->acm->updateConfiguration();

            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            $this->DatabaseConnection = null;

            $this->ChatMemberCacheManager = new ChatMemberCacheManager($this);
            $this->UserWarningManager = new UserWarningManager($this);
        }

        /**
         * @return mysqli
         */
        public function getDatabase(): mysqli
        {
            if($this->DatabaseConnection == null)
            {
                $this->connectDatabase();
            }

            return $this->DatabaseConnection;
        }

        /**
         * Closes the current database connection
         */
        public function disconnectDatabase()
        {
            $this->DatabaseConnection->close();
            $this->DatabaseConnection = null;
        }

        /**
         * Creates a new database connection
         */
        public function connectDatabase()
        {
            if($this->DatabaseConnection !== null)
            {
                $this->disconnectDatabase();
            }

            $this->DatabaseConnection = new mysqli(
                $this->DatabaseConfiguration['Host'],
                $this->DatabaseConfiguration['Username'],
                $this->DatabaseConfiguration['Password'],
                $this->DatabaseConfiguration['Name'],
                $this->DatabaseConfiguration['Port']
            );
        }

        /**
         * @return ChatMemberCacheManager
         */
        public function getChatMemberCacheManager(): ChatMemberCacheManager
        {
            return $this->ChatMemberCacheManager;
        }

        /**
         * @return UserWarningManager
         */
        public function getUserWarningManager(): UserWarningManager
        {
            return $this->UserWarningManager;
        }
    }