create table if not exists user_warning
(
    id                     varchar(64)  not null comment 'A unique ID index hash'
        primary key,
    chat_id                varchar(126) null comment 'The Chat ID associated with the user that is issued warnings',
    user_id                int          null comment 'The User ID that is issued the warning',
    warnings               blob         null comment 'ZiProto encoded blob of warnings issued to the user',
    last_updated_timestamp int          null comment 'The Unix Timestamp for when this record was last updated',
    created_timestamp      int          null comment 'Unix timestamp for when this record was first created',
    constraint user_warning_chat_id_user_id_uindex
        unique (chat_id, user_id),
    constraint user_warning_id_uindex
        unique (id)
)
    comment 'A table used for storing user warnings';

create index user_warning_chat_id_index
    on user_warning (chat_id);

create index user_warning_user_id_index
    on user_warning (user_id);

