<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace Synical\Objects;

    use Longman\TelegramBot\Entities\User;
    use Synical\Objects\AdminCacheRecord\AdministratorPermissions;

    class ChatMemberCache
    {
        /**
         * Unique identifier for the target chat or username of the
         * target supergroup or channel (in the format @channelusername)
         *
         * @var int|string
         */
        public $ChatID;

        /**
         * An array of chat administrators with their allocated permissions
         *
         * @var AdministratorPermissions[]
         */
        public $AdministratorPermissions;

        /**
         * The number of members in the chat
         *
         * @var int|null
         */
        public $ChatMemberCount;

        /**
         * The Unix Timestamp for when this record was last updated
         *
         * @var int
         */
        public $LastUpdatedTimestamp;

        /**
         * The Unix Timestamp for when this record was first created
         *
         * @var int
         */
        public $CreatedTimestamp;

        public function __construct()
        {
            $this->AdministratorPermissions = [];
        }

        /**
         * Returns an existing administrator permission if available
         *
         * @param int $id
         * @return AdministratorPermissions|null
         */
        public function getAdministratorUser(int $id): ?AdministratorPermissions
        {
            foreach($this->AdministratorPermissions as $permission)
            {
                if($id == $permission->ID)
                    return $permission;
            }

            return null;
        }

        /**
         * Returns an array representation of the object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            $AdministratorPermissions = [];
            foreach($this->AdministratorPermissions as $permission)
                $AdministratorPermissions[] = $permission->toArray();

            return [
                'chat_id' => $this->ChatID,
                'administrator_permissions' => $AdministratorPermissions,
                'chat_member_count' => $this->ChatMemberCount,
                'last_updated_timestamp' => $this->LastUpdatedTimestamp,
                'created_timestamp' => $this->CreatedTimestamp
            ];
        }

        /**
         * Returns an array representation of the object
         *
         * @param array $data
         * @return ChatMemberCache
         */
        public static function fromArray(array $data): ChatMemberCache
        {
            $ChatMemberCache = new ChatMemberCache();

            if(isset($data['chat_id']))
                $ChatMemberCache->ChatID = $data['chat_id'];

            if(isset($data['administrator_permissions']))
            {
                foreach($data['administrator_permissions'] as $administrator_permission)
                    $ChatMemberCache->AdministratorPermissions[] = AdministratorPermissions::fromArray($administrator_permission);
            }

            if(isset($data['chat_member_count']))
                $ChatMemberCache->ChatMemberCount = (int)$data['chat_member_count'];

            if(isset($data['last_updated_timestamp']))
                $ChatMemberCache->LastUpdatedTimestamp = (int)$data['last_updated_timestamp'];

            if(isset($data['created_timestamp']))
                $ChatMemberCache->CreatedTimestamp = (int)$data['created_timestamp'];

            return $ChatMemberCache;
        }
    }