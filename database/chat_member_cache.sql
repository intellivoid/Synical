create table chat_member_cache
(
    chat_id                   varchar(86) not null comment 'Unique identifier for the target chat or username of the
target supergroup or channel (in the format @channelusername)'
        primary key,
    administrator_permissions blob        null comment 'ZiProto encoded blob of the administrator permissions',
    chat_member_count         int         null comment 'The amount of members in the chat/channel',
    last_updated_timestamp    int         null comment 'The Unix Timestamp for when the record was last updated',
    created_timestamp         int         null comment 'The Unix Timestamp for when the record was first registered into the database',
    constraint chat_member_cache_chat_id_uindex
        unique (chat_id)
)
    comment 'A table for housing a cache of the current state of chat members in the group/channel';

