CREATE TABLE `commands` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `user_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `update` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `snippets` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `description` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `user_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `snippet_commands` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `snippet_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `command_id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` char(36) character set utf8 collate utf8_unicode_ci NOT NULL,
  `email` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `pass` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;