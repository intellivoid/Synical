<?php

    /** @noinspection PhpMissingFieldTypeInspection */

    namespace Synical\Objects\AdminCacheRecord;

    use Longman\TelegramBot\Entities\ChatMember\ChatMemberAdministrator;
    use Longman\TelegramBot\Entities\ChatMember\ChatMemberOwner;

    class AdministratorPermissions
    {
        /**
         * Unique identifier for this user or bot. This number may have more than 32 significant bits and some
         * programming languages may have difficulty/silent defects in interpreting it. But it has at most 52
         * significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
         *
         * @var int
         */
        public $ID;

        /**
         * Indicates if the chat member is the owner of the chat (Full permissions)
         *
         * @var bool
         */
        public $IsOwner;

        /**
         * Indicates if the chat member is an administrator of the chat
         *
         * @var bool
         */
        public $IsAdmin;

        /**
         * Indicates if the chat member is a bot
         *
         * @var bool
         */
        public $IsBot;

        /**
         * if the bot is allowed to edit administrator privileges of that user
         *
         * @var bool
         */
        public $CanBeEdited;

        /**
         * True, If the user's presence in the chat is hidden (Anonymous Admin)
         *
         * @var bool
         */
        public $IsAnonymous;

        /**
         * True, if the administrator can access the chat event log, chat statistics, message statistics in channels,
         * see channel members, see anonymous administrators in supergroups and ignore slow mode. Implied by
         * any other administrator privilege
         *
         * @var bool
         */
        public $CanManageChat;

        /**
         * True, if the administrator can delete messages of other users
         *
         * @var bool
         */
        public $CanDeleteMessages;

        /**
         * True, if the administrator can manage voice chats
         *
         * @var bool
         */
        public $CanManageVoiceChats;

        /**
         * True, if the administrator can restrict, ban or unban chat members
         *
         * @var bool
         */
        public $CanRestrictMembers;

        /**
         * True, if the administrator can add new administrators with a subset of their own privileges or demote
         * administrators that he has promoted, directly or indirectly (promoted by administrators that were
         * appointed by the user)
         *
         * @var bool
         */
        public $CanPromoteMembers;

        /**
         * True, if the user is allowed to change the chat title, photo and other settings
         *
         * @var bool
         */
        public $CanChangeInfo;

        /**
         * True, if the user is allowed to invite new users to the chat
         *
         * @var bool
         */
        public $CanInviteUsers;

        /**
         * Optional. True, if the administrator can post in the channel; channels only
         *
         * @var bool
         */
        public $CanPostMessages;

        /**
         * Optional. True, if the administrator can edit messages of other users and can pin messages; channels only
         *
         * @var bool
         */
        public $CanEditMessages;

        /**
         * Optional. True, if the user is allowed to pin messages; groups and supergroups only
         *
         * @var bool
         */
        public $CanPinMessages;

        /**
         * Optional. Custom title for this user
         *
         * @var null|bool
         */
        public $CustomTitle;

        public function __construct()
        {
            $this->ID = null;
            $this->IsOwner = false;
            $this->IsAdmin = false;
            $this->IsBot = false;
            $this->CanBeEdited = false;
            $this->IsAnonymous = false;
            $this->CanManageChat = false;
            $this->CanDeleteMessages = false;
            $this->CanManageVoiceChats = false;
            $this->CanRestrictMembers = false;
            $this->CanPromoteMembers = false;
            $this->CanChangeInfo = false;
            $this->CanInviteUsers = false;
            $this->CanPostMessages = false;
            $this->CanEditMessages = false;
            $this->CanPinMessages = false;
            $this->CustomTitle = null;
        }

        /**
         * Returns an array representation of the object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                'id' => $this->ID,
                'is_owner' => $this->IsOwner,
                'is_admin' => $this->IsAdmin,
                'is_bot' => $this->IsBot,
                'can_be_edited' => $this->CanBeEdited,
                'is_anonymous' => $this->IsAnonymous,
                'can_manage_chat' => $this->CanManageChat,
                'can_delete_messages' => $this->CanDeleteMessages,
                'can_manage_voice_chats' => $this->CanManageVoiceChats,
                'can_restrict_members' => $this->CanRestrictMembers,
                'can_promote_members' => $this->CanPromoteMembers,
                'can_change_info' => $this->CanChangeInfo,
                'can_invite_users' => $this->CanInviteUsers,
                'can_post_messages' => $this->CanPostMessages,
                'can_edit_messages' => $this->CanEditMessages,
                'can_pin_messages' => $this->CanPinMessages,
                'custom_title' => $this->CustomTitle
            ];
        }

        /**
         * Constructs object from an array representation of the object
         *
         * @param array $data
         * @return AdministratorPermissions
         */
        public static function fromArray(array $data): AdministratorPermissions
        {
            $Permissions = new AdministratorPermissions();

            if(isset($data['id']))
                $Permissions->ID = (int)$data['id'];

            if(isset($data['is_owner']))
                $Permissions->IsOwner = (bool)$data['is_owner'];

            if(isset($data['is_admin']))
                $Permissions->IsAdmin = (bool)$data['is_admin'];

            if(isset($data['is_bot']))
                $Permissions->IsBot = (bool)$data['is_bot'];

            if(isset($data['can_be_edited']))
                $Permissions->CanBeEdited = (bool)$data['can_be_edited'];

            if(isset($data['is_anonymous']))
                $Permissions->IsAnonymous = (bool)$data['is_anonymous'];

            if(isset($data['can_manage_chat']))
                $Permissions->CanManageChat = (bool)$data['can_manage_chat'];

            if(isset($data['can_delete_messages']))
                $Permissions->CanDeleteMessages = (bool)$data['can_delete_messages'];

            if(isset($data['can_manage_voice_chats']))
                $Permissions->CanManageVoiceChats = (bool)$data['can_manage_voice_chats'];

            if(isset($data['can_restrict_members']))
                $Permissions->CanRestrictMembers = (bool)$data['can_restrict_members'];

            if(isset($data['can_promote_members']))
                $Permissions->CanPromoteMembers = (bool)$data['can_promote_members'];

            if(isset($data['can_change_info']))
                $Permissions->CanChangeInfo = (bool)$data['can_change_info'];

            if(isset($data['can_invite_users']))
                $Permissions->CanInviteUsers = (bool)$data['can_invite_users'];

            if(isset($data['can_post_messages']))
                $Permissions->CanPostMessages = (bool)$data['can_post_messages'];

            if(isset($data['can_edit_messages']))
                $Permissions->CanEditMessages = (bool)$data['can_edit_messages'];

            if(isset($data['can_pin_messages']))
                $Permissions->CanPinMessages = (bool)$data['can_pin_messages'];

            return $Permissions;
        }

        /**
         * Constructs object from ChatMemberOwner object
         *
         * @param ChatMemberOwner $chatMemberOwner
         * @return AdministratorPermissions
         */
        public static function fromChatMemberOwner(ChatMemberOwner $chatMemberOwner): AdministratorPermissions
        {
            $Permissions = new AdministratorPermissions();
            $Permissions->IsOwner = true;
            $Permissions->ID = $chatMemberOwner->getUser()->getId();
            $Permissions->IsBot = $chatMemberOwner->getUser()->getIsBot();
            $Permissions->IsAnonymous = $chatMemberOwner->getIsAnonymous();
            $Permissions->CustomTitle = $chatMemberOwner->getCustomTitle();
            $Permissions->CanBeEdited = false;
            /** @noinspection DuplicatedCode */
            $Permissions->CanManageChat = true;
            $Permissions->CanDeleteMessages = true;
            $Permissions->CanManageVoiceChats = true;
            $Permissions->CanRestrictMembers = true;
            $Permissions->CanPromoteMembers = true;
            $Permissions->CanChangeInfo = true;
            $Permissions->CanInviteUsers = true;
            $Permissions->CanPostMessages = true;
            $Permissions->CanEditMessages = true;
            $Permissions->CanPinMessages = true;

            return $Permissions;
        }

        /**
         * Constructs object form a ChatMemberAdministrator object
         *
         * @param ChatMemberAdministrator $chatMemberAdministrator
         * @return AdministratorPermissions
         */
        public static function fromChatMemberAdministrator(ChatMemberAdministrator $chatMemberAdministrator): AdministratorPermissions
        {
            $Permissions = new AdministratorPermissions();
            $Permissions->IsAdmin = true;
            $Permissions->ID = $chatMemberAdministrator->getUser()->getId();
            $Permissions->IsBot = $chatMemberAdministrator->getUser()->getIsBot();
            $Permissions->IsAnonymous = $chatMemberAdministrator->getIsAnonymous();
            $Permissions->CustomTitle = $chatMemberAdministrator->getCustomTitle();
            $Permissions->CanBeEdited = $chatMemberAdministrator->getCanBeEdited();
            /** @noinspection DuplicatedCode */
            $Permissions->CanManageChat = $chatMemberAdministrator->getCanManageChat();
            $Permissions->CanDeleteMessages = $chatMemberAdministrator->getCanDeleteMessages();
            $Permissions->CanManageVoiceChats = $chatMemberAdministrator->getCanManageVoiceChats();
            $Permissions->CanRestrictMembers = $chatMemberAdministrator->getCanRestrictMembers();
            $Permissions->CanPromoteMembers = $chatMemberAdministrator->getCanPromoteMembers();
            $Permissions->CanChangeInfo = $chatMemberAdministrator->getCanChangeInfo();
            $Permissions->CanInviteUsers = $chatMemberAdministrator->getCanInviteUsers();
            $Permissions->CanPostMessages = $chatMemberAdministrator->getCanPostMessages();
            $Permissions->CanEditMessages = $chatMemberAdministrator->getCanEditMessages();
            $Permissions->CanPinMessages = $chatMemberAdministrator->getCanPinMessages();

            return $Permissions;
        }
    }