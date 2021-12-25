<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace Synical\Managers;

    use Longman\TelegramBot\Entities\Chat;
    use Longman\TelegramBot\Entities\ChatMember\ChatMember;
    use Longman\TelegramBot\Entities\ChatMember\ChatMemberAdministrator;
    use Longman\TelegramBot\Entities\ChatMember\ChatMemberOwner;
    use Longman\TelegramBot\Request;
    use msqg\QueryBuilder;
    use Synical\Exceptions\CannotUpdateChatMembersCacheException;
    use Synical\Exceptions\ChatMemberCacheRecordNotFoundException;
    use Synical\Exceptions\DatabaseException;
    use Synical\Objects\AdminCacheRecord\AdministratorPermissions;
    use Synical\Objects\ChatMemberCache;
    use Synical\Synical;
    use ZiProto\ZiProto;

    class ChatMemberCacheManager
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
         * Registers the record into the database
         *
         * @param ChatMemberCache $chatMemberCache
         * @return ChatMemberCache
         * @throws DatabaseException
         * @noinspection PhpCastIsUnnecessaryInspection
         */
        public function registerRecord(ChatMemberCache $chatMemberCache): ChatMemberCache
        {
            $chatMemberCache->LastUpdatedTimestamp = time();
            $chatMemberCache->CreatedTimestamp = time();

            $admin_permissions = $chatMemberCache->toArray()['administrator_permissions'];
            $Query = QueryBuilder::insert_into('chat_member_cache', [
                'chat_id' => $this->synical->getDatabase()->real_escape_string($chatMemberCache->ChatID),
                'administrator_permissions' => $this->synical->getDatabase()->real_escape_string(ZiProto::encode($admin_permissions)),
                'chat_member_count' => (int)$chatMemberCache->ChatMemberCount,
                'last_updated_timestamp' => (int)$chatMemberCache->LastUpdatedTimestamp,
                'created_timestamp' => (int)$chatMemberCache->CreatedTimestamp
            ]);

            $QueryResults = $this->synical->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->synical->getDatabase()->error, $Query, $this->synical->getDatabase()->errno);

            return $chatMemberCache;
        }

        /**
         * Returns an existing record from the database
         *
         * @param string $chat_id
         * @return ChatMemberCache
         * @throws ChatMemberCacheRecordNotFoundException
         * @throws DatabaseException
         */
        public function getRecord(string $chat_id): ChatMemberCache
        {
            $Query = QueryBuilder::select('chat_member_cache', [
                'chat_id',
                'administrator_permissions',
                'chat_member_count',
                'last_updated_timestamp',
                'created_timestamp'
            ], 'chat_id', $this->synical->getDatabase()->real_escape_string($chat_id));

            $QueryResults = $this->synical->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->synical->getDatabase()->error, $Query, $this->synical->getDatabase()->errno);

            if($QueryResults->num_rows == 0)
                throw new ChatMemberCacheRecordNotFoundException();

            $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);
            $Row['administrator_permissions'] = ZiProto::decode($Row['administrator_permissions']);

            return ChatMemberCache::fromArray($Row);
        }

        /**
         * Updates an existing record in the database
         *
         * @param ChatMemberCache $chatMemberCache
         * @return ChatMemberCache
         * @throws DatabaseException
         */
        public function updateRecord(ChatMemberCache $chatMemberCache): ChatMemberCache
        {
            $chatMemberCache->LastUpdatedTimestamp = time();
            $admin_permissions = $chatMemberCache->toArray()['administrator_permissions'];
            $Query = QueryBuilder::update('chat_member_cache', [
                'administrator_permissions' => $this->synical->getDatabase()->real_escape_string(ZiProto::encode($admin_permissions)),
                'chat_member_count' => (int)$chatMemberCache->ChatMemberCount,
                'last_updated_timestamp' => (int)$chatMemberCache->LastUpdatedTimestamp,
            ], 'chat_id', $this->synical->getDatabase()->real_escape_string($chatMemberCache->ChatID));

            $QueryResults = $this->synical->getDatabase()->query($Query);

            if($QueryResults == false)
                throw new DatabaseException($this->synical->getDatabase()->error, $Query, $this->synical->getDatabase()->errno);

            return $chatMemberCache;
        }

        /**
         * A smart function to get the chat member cache
         *
         * @param Chat $chat
         * @return ChatMemberCache
         * @throws CannotUpdateChatMembersCacheException
         * @throws DatabaseException
         */
        public function getChatMemberCache(Chat $chat, bool $force_update=True): ChatMemberCache
        {
            try
            {
                $returnResults = $this->getRecord($chat->getId());
                if((time() - $returnResults->LastUpdatedTimestamp) < 300 && $force_update == false)
                    return $returnResults;
                $needsUpdate = true;
            }
            catch(ChatMemberCacheRecordNotFoundException $e)
            {
                $needsUpdate = false;
                $returnResults = null;
                unset($e);
            }

            $Results = Request::getChatAdministrators(['chat_id' => $chat->getId()]);

            if($Results->isOk() == false)
            {
                if($returnResults !== null)
                {
                    return $returnResults;
                }

                throw new CannotUpdateChatMembersCacheException($Results->getDescription(), $Results->getErrorCode());
            }

            if($returnResults == null)
                $returnResults = new ChatMemberCache();

            $chatMembersResponse = $Results->getRawData()["result"];

            /** @var ChatMemberOwner $chatMemberOwner */
            foreach($chatMembersResponse as $chatMemberOwner)
            {
                if($chatMemberOwner->getStatus() == 'creator')
                {
                    $returnResults->AdministratorPermissions[] = AdministratorPermissions::fromChatMemberOwner($chatMemberOwner);
                }
            }

            /** @var ChatMemberAdministrator $chatMemberAdministrator */
            foreach($chatMembersResponse as $chatMemberAdministrator)
            {
                if($chatMemberAdministrator->getStatus() == 'administrator')
                {
                    $returnResults->AdministratorPermissions[] = AdministratorPermissions::fromChatMemberAdministrator($chatMemberAdministrator);
                }
            }

            $returnResults->ChatMemberCount = null;
            $chatMemberCountResponse = Request::getChatMemberCount(['chat_id' => $chat->getId()]);
            if($chatMemberCountResponse->isOk())
                $returnResults->ChatMemberCount = (int)$chatMemberCountResponse->getResult();

            if($needsUpdate)
                return $this->updateRecord($returnResults);

            return $this->registerRecord($returnResults);
        }
    }