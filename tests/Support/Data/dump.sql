PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS _mysql_data_types_cache;
CREATE TABLE _mysql_data_types_cache (
		`table` TEXT NOT NULL,
		`column_or_index` TEXT NOT NULL,
		`mysql_type` TEXT NOT NULL,
		PRIMARY KEY(`table`, `column_or_index`)
	);

INSERT INTO _mysql_data_types_cache ('table','column_or_index','mysql_type') VALUES
('wp_users','ID','bigint(20) unsigned'),
('wp_users','user_login','varchar(60)'),
('wp_users','user_pass','varchar(255)'),
('wp_users','user_nicename','varchar(50)'),
('wp_users','user_email','varchar(100)'),
('wp_users','user_url','varchar(100)'),
('wp_users','user_registered','datetime'),
('wp_users','user_activation_key','varchar(255)'),
('wp_users','user_status','int(11)'),
('wp_users','display_name','varchar(250)'),
('wp_users','wp_users__user_login_key','KEY'),
('wp_users','wp_users__user_nicename','KEY'),
('wp_users','wp_users__user_email','KEY'),
('wp_usermeta','umeta_id','bigint(20) unsigned'),
('wp_usermeta','user_id','bigint(20) unsigned'),
('wp_usermeta','meta_key','varchar(255)'),
('wp_usermeta','meta_value','longtext'),
('wp_usermeta','wp_usermeta__user_id','KEY'),
('wp_usermeta','wp_usermeta__meta_key','KEY'),
('wp_termmeta','meta_id','bigint(20) unsigned'),
('wp_termmeta','term_id','bigint(20) unsigned'),
('wp_termmeta','meta_key','varchar(255)'),
('wp_termmeta','meta_value','longtext'),
('wp_termmeta','wp_termmeta__term_id','KEY'),
('wp_termmeta','wp_termmeta__meta_key','KEY'),
('wp_terms','term_id','bigint(20) unsigned'),
('wp_terms','name','varchar(200)'),
('wp_terms','slug','varchar(200)'),
('wp_terms','term_group','bigint(10)'),
('wp_terms','wp_terms__slug','KEY'),
('wp_terms','wp_terms__name','KEY'),
('wp_term_taxonomy','term_taxonomy_id','bigint(20) unsigned'),
('wp_term_taxonomy','term_id','bigint(20) unsigned'),
('wp_term_taxonomy','taxonomy','varchar(32)'),
('wp_term_taxonomy','description','longtext'),
('wp_term_taxonomy','parent','bigint(20) unsigned'),
('wp_term_taxonomy','count','bigint(20)'),
('wp_term_taxonomy','wp_term_taxonomy__term_id_taxonomy','UNIQUE'),
('wp_term_taxonomy','wp_term_taxonomy__taxonomy','KEY'),
('wp_term_relationships','object_id','bigint(20) unsigned'),
('wp_term_relationships','term_taxonomy_id','bigint(20) unsigned'),
('wp_term_relationships','term_order','int(11)'),
('wp_term_relationships','wp_term_relationships__term_taxonomy_id','KEY'),
('wp_commentmeta','meta_id','bigint(20) unsigned'),
('wp_commentmeta','comment_id','bigint(20) unsigned'),
('wp_commentmeta','meta_key','varchar(255)'),
('wp_commentmeta','meta_value','longtext'),
('wp_commentmeta','wp_commentmeta__comment_id','KEY'),
('wp_commentmeta','wp_commentmeta__meta_key','KEY'),
('wp_comments','comment_ID','bigint(20) unsigned'),
('wp_comments','comment_post_ID','bigint(20) unsigned'),
('wp_comments','comment_author','tinytext'),
('wp_comments','comment_author_email','varchar(100)'),
('wp_comments','comment_author_url','varchar(200)'),
('wp_comments','comment_author_IP','varchar(100)'),
('wp_comments','comment_date','datetime'),
('wp_comments','comment_date_gmt','datetime'),
('wp_comments','comment_content','text'),
('wp_comments','comment_karma','int(11)'),
('wp_comments','comment_approved','varchar(20)'),
('wp_comments','comment_agent','varchar(255)'),
('wp_comments','comment_type','varchar(20)'),
('wp_comments','comment_parent','bigint(20) unsigned'),
('wp_comments','user_id','bigint(20) unsigned'),
('wp_comments','wp_comments__comment_post_ID','KEY'),
('wp_comments','wp_comments__comment_approved_date_gmt','KEY'),
('wp_comments','wp_comments__comment_date_gmt','KEY'),
('wp_comments','wp_comments__comment_parent','KEY'),
('wp_comments','wp_comments__comment_author_email','KEY'),
('wp_links','link_id','bigint(20) unsigned'),
('wp_links','link_url','varchar(255)'),
('wp_links','link_name','varchar(255)'),
('wp_links','link_image','varchar(255)'),
('wp_links','link_target','varchar(25)'),
('wp_links','link_description','varchar(255)'),
('wp_links','link_visible','varchar(20)'),
('wp_links','link_owner','bigint(20) unsigned'),
('wp_links','link_rating','int(11)'),
('wp_links','link_updated','datetime'),
('wp_links','link_rel','varchar(255)'),
('wp_links','link_notes','mediumtext'),
('wp_links','link_rss','varchar(255)'),
('wp_links','wp_links__link_visible','KEY'),
('wp_options','option_id','bigint(20) unsigned'),
('wp_options','option_name','varchar(191)'),
('wp_options','option_value','longtext'),
('wp_options','autoload','varchar(20)'),
('wp_options','wp_options__option_name','UNIQUE'),
('wp_options','wp_options__autoload','KEY'),
('wp_postmeta','meta_id','bigint(20) unsigned'),
('wp_postmeta','post_id','bigint(20) unsigned'),
('wp_postmeta','meta_key','varchar(255)'),
('wp_postmeta','meta_value','longtext'),
('wp_postmeta','wp_postmeta__post_id','KEY'),
('wp_postmeta','wp_postmeta__meta_key','KEY'),
('wp_posts','ID','bigint(20) unsigned'),
('wp_posts','post_author','bigint(20) unsigned'),
('wp_posts','post_date','datetime'),
('wp_posts','post_date_gmt','datetime'),
('wp_posts','post_content','longtext'),
('wp_posts','post_title','text'),
('wp_posts','post_excerpt','text'),
('wp_posts','post_status','varchar(20)'),
('wp_posts','comment_status','varchar(20)'),
('wp_posts','ping_status','varchar(20)'),
('wp_posts','post_password','varchar(255)'),
('wp_posts','post_name','varchar(200)'),
('wp_posts','to_ping','text'),
('wp_posts','pinged','text'),
('wp_posts','post_modified','datetime'),
('wp_posts','post_modified_gmt','datetime'),
('wp_posts','post_content_filtered','longtext'),
('wp_posts','post_parent','bigint(20) unsigned'),
('wp_posts','guid','varchar(255)'),
('wp_posts','menu_order','int(11)'),
('wp_posts','post_type','varchar(20)'),
('wp_posts','post_mime_type','varchar(100)'),
('wp_posts','comment_count','bigint(20)'),
('wp_posts','wp_posts__post_name','KEY'),
('wp_posts','wp_posts__type_status_date','KEY'),
('wp_posts','wp_posts__post_parent','KEY'),
('wp_posts','wp_posts__post_author','KEY');

DROP TABLE IF EXISTS wp_users;
CREATE TABLE "wp_users" (
"ID" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"user_login" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"user_pass" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"user_nicename" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"user_email" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"user_url" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"user_registered" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"user_activation_key" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"user_status" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"display_name" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE);

INSERT INTO wp_users ('ID','user_login','user_pass','user_nicename','user_email','user_url','user_registered','user_activation_key','user_status','display_name') VALUES
('1','admin','$P$B7biJvcGqlR8EpSS68aIhW3EKh/Cxj.','admin','admin@exmaple.com','http://localhost:24412','2024-10-05 15:28:25','','0','admin');

DROP TABLE IF EXISTS wp_usermeta;
CREATE TABLE "wp_usermeta" (
"umeta_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"user_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"meta_key" text DEFAULT NULL COLLATE NOCASE,
"meta_value" text COLLATE NOCASE);

INSERT INTO wp_usermeta ('umeta_id','user_id','meta_key','meta_value') VALUES
('1','1','nickname','admin'),
('2','1','first_name',''),
('3','1','last_name',''),
('4','1','description',''),
('5','1','rich_editing','true'),
('6','1','syntax_highlighting','true'),
('7','1','comment_shortcuts','false'),
('8','1','admin_color','fresh'),
('9','1','use_ssl','0'),
('10','1','show_admin_bar_front','true'),
('11','1','locale',''),
('12','1','wp_capabilities','a:1:{s:13:"administrator";b:1;}'),
('13','1','wp_user_level','10'),
('14','1','dismissed_wp_pointers',''),
('15','1','show_welcome_panel','1');

DROP TABLE IF EXISTS wp_termmeta;
CREATE TABLE "wp_termmeta" (
"meta_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"term_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"meta_key" text DEFAULT NULL COLLATE NOCASE,
"meta_value" text COLLATE NOCASE);

DROP TABLE IF EXISTS wp_terms;
CREATE TABLE "wp_terms" (
"term_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"name" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"slug" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"term_group" integer NOT NULL ON CONFLICT REPLACE DEFAULT 0);

INSERT INTO wp_terms ('term_id','name','slug','term_group') VALUES
('1','Uncategorized','uncategorized','0');

DROP TABLE IF EXISTS wp_term_taxonomy;
CREATE TABLE "wp_term_taxonomy" (
"term_taxonomy_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"term_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"taxonomy" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"description" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"parent" integer NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"count" integer NOT NULL ON CONFLICT REPLACE DEFAULT 0);

INSERT INTO wp_term_taxonomy ('term_taxonomy_id','term_id','taxonomy','description','parent','count') VALUES
('1','1','category','','0','1');

DROP TABLE IF EXISTS wp_term_relationships;
CREATE TABLE "wp_term_relationships" (
"object_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"term_taxonomy_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"term_order" integer NOT NULL ON CONFLICT REPLACE DEFAULT 0,
PRIMARY KEY ("object_id", "term_taxonomy_id"));

INSERT INTO wp_term_relationships ('object_id','term_taxonomy_id','term_order') VALUES
('1','1','0');

DROP TABLE IF EXISTS wp_commentmeta;
CREATE TABLE "wp_commentmeta" (
"meta_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"comment_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"meta_key" text DEFAULT NULL COLLATE NOCASE,
"meta_value" text COLLATE NOCASE);

DROP TABLE IF EXISTS wp_comments;
CREATE TABLE "wp_comments" (
"comment_ID" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"comment_post_ID" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"comment_author" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"comment_author_email" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"comment_author_url" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"comment_author_IP" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"comment_date" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"comment_date_gmt" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"comment_content" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"comment_karma" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"comment_approved" text NOT NULL ON CONFLICT REPLACE DEFAULT '1' COLLATE NOCASE,
"comment_agent" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"comment_type" text NOT NULL ON CONFLICT REPLACE DEFAULT 'comment' COLLATE NOCASE,
"comment_parent" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"user_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0');

INSERT INTO wp_comments ('comment_ID','comment_post_ID','comment_author','comment_author_email','comment_author_url','comment_author_IP','comment_date','comment_date_gmt','comment_content','comment_karma','comment_approved','comment_agent','comment_type','comment_parent','user_id') VALUES
('1','1','A WordPress Commenter','wapuu@wordpress.example','https://wordpress.org/','','2024-10-05 15:28:25','2024-10-05 15:28:25','Hi, this is a comment.' || char(10) || 'To get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard.' || char(10) || 'Commenter avatars come from <a href="https://en.gravatar.com/">Gravatar</a>.','0','1','','comment','0','0');

DROP TABLE IF EXISTS wp_links;
CREATE TABLE "wp_links" (
"link_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"link_url" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"link_name" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"link_image" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"link_target" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"link_description" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"link_visible" text NOT NULL ON CONFLICT REPLACE DEFAULT 'Y' COLLATE NOCASE,
"link_owner" integer NOT NULL ON CONFLICT REPLACE DEFAULT '1',
"link_rating" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"link_updated" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"link_rel" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"link_notes" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"link_rss" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE);

DROP TABLE IF EXISTS wp_options;
CREATE TABLE "wp_options" (
"option_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"option_name" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"option_value" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"autoload" text NOT NULL ON CONFLICT REPLACE DEFAULT 'yes' COLLATE NOCASE);

INSERT INTO wp_options ('option_id','option_name','option_value','autoload') VALUES
('1','siteurl','http://localhost:24412','on'),
('2','home','http://localhost:24412','on'),
('3','blogname','Html Test','on'),
('4','blogdescription','','on'),
('5','users_can_register','0','on'),
('6','admin_email','admin@exmaple.com','on'),
('7','start_of_week','1','on'),
('8','use_balanceTags','0','on'),
('9','use_smilies','1','on'),
('10','require_name_email','1','on'),
('11','comments_notify','1','on'),
('12','posts_per_rss','10','on'),
('13','rss_use_excerpt','0','on'),
('14','mailserver_url','mail.example.com','on'),
('15','mailserver_login','login@example.com','on'),
('16','mailserver_pass','password','on'),
('17','mailserver_port','110','on'),
('18','default_category','1','on'),
('19','default_comment_status','open','on'),
('20','default_ping_status','open','on'),
('21','default_pingback_flag','1','on'),
('22','posts_per_page','10','on'),
('23','date_format','F j, Y','on'),
('24','time_format','g:i a','on'),
('25','links_updated_date_format','F j, Y g:i a','on'),
('26','comment_moderation','0','on'),
('27','moderation_notify','1','on'),
('28','permalink_structure','/%year%/%monthnum%/%day%/%postname%/','on'),
('29','rewrite_rules','','on'),
('30','hack_file','0','on'),
('31','blog_charset','UTF-8','on'),
('32','moderation_keys','','off'),
('33','active_plugins','a:0:{}','on'),
('34','category_base','','on'),
('35','ping_sites','http://rpc.pingomatic.com/','on'),
('36','comment_max_links','2','on'),
('37','gmt_offset','0','on'),
('38','default_email_category','1','on'),
('39','recently_edited','','off'),
('40','template','twentytwentyfour','on'),
('41','stylesheet','twentytwentyfour','on'),
('42','comment_registration','0','on'),
('43','html_type','text/html','on'),
('44','use_trackback','0','on'),
('45','default_role','subscriber','on'),
('46','db_version','57155','on'),
('47','uploads_use_yearmonth_folders','1','on'),
('48','upload_path','','on'),
('49','blog_public','1','on'),
('50','default_link_category','2','on'),
('51','show_on_front','posts','on'),
('52','tag_base','','on'),
('53','show_avatars','1','on'),
('54','avatar_rating','G','on'),
('55','upload_url_path','','on'),
('56','thumbnail_size_w','150','on'),
('57','thumbnail_size_h','150','on'),
('58','thumbnail_crop','1','on'),
('59','medium_size_w','300','on'),
('60','medium_size_h','300','on'),
('61','avatar_default','mystery','on'),
('62','large_size_w','1024','on'),
('63','large_size_h','1024','on'),
('64','image_default_link_type','none','on'),
('65','image_default_size','','on'),
('66','image_default_align','','on'),
('67','close_comments_for_old_posts','0','on'),
('68','close_comments_days_old','14','on'),
('69','thread_comments','1','on'),
('70','thread_comments_depth','5','on'),
('71','page_comments','0','on'),
('72','comments_per_page','50','on'),
('73','default_comments_page','newest','on'),
('74','comment_order','asc','on'),
('75','sticky_posts','a:0:{}','on'),
('76','widget_categories','a:0:{}','on'),
('77','widget_text','a:0:{}','on'),
('78','widget_rss','a:0:{}','on'),
('79','uninstall_plugins','a:0:{}','off'),
('80','timezone_string','','on'),
('81','page_for_posts','0','on'),
('82','page_on_front','0','on'),
('83','default_post_format','0','on'),
('84','link_manager_enabled','0','on'),
('85','finished_splitting_shared_terms','1','on'),
('86','site_icon','0','on'),
('87','medium_large_size_w','768','on'),
('88','medium_large_size_h','0','on'),
('89','wp_page_for_privacy_policy','3','on'),
('90','show_comments_cookies_opt_in','1','on'),
('91','admin_email_lifespan','1743694105','on'),
('92','disallowed_keys','','off'),
('93','comment_previously_approved','1','on'),
('94','auto_plugin_theme_update_emails','a:0:{}','off'),
('95','auto_update_core_dev','enabled','on'),
('96','auto_update_core_minor','enabled','on'),
('97','auto_update_core_major','enabled','on'),
('98','wp_force_deactivated_plugins','a:0:{}','on'),
('99','wp_attachment_pages_enabled','0','on'),
('100','initial_db_version','57155','on'),
('101','wp_user_roles','a:5:{s:13:"administrator";a:2:{s:4:"name";s:13:"Administrator";s:12:"capabilities";a:61:{s:13:"switch_themes";b:1;s:11:"edit_themes";b:1;s:16:"activate_plugins";b:1;s:12:"edit_plugins";b:1;s:10:"edit_users";b:1;s:10:"edit_files";b:1;s:14:"manage_options";b:1;s:17:"moderate_comments";b:1;s:17:"manage_categories";b:1;s:12:"manage_links";b:1;s:12:"upload_files";b:1;s:6:"import";b:1;s:15:"unfiltered_html";b:1;s:10:"edit_posts";b:1;s:17:"edit_others_posts";b:1;s:20:"edit_published_posts";b:1;s:13:"publish_posts";b:1;s:10:"edit_pages";b:1;s:4:"read";b:1;s:8:"level_10";b:1;s:7:"level_9";b:1;s:7:"level_8";b:1;s:7:"level_7";b:1;s:7:"level_6";b:1;s:7:"level_5";b:1;s:7:"level_4";b:1;s:7:"level_3";b:1;s:7:"level_2";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:17:"edit_others_pages";b:1;s:20:"edit_published_pages";b:1;s:13:"publish_pages";b:1;s:12:"delete_pages";b:1;s:19:"delete_others_pages";b:1;s:22:"delete_published_pages";b:1;s:12:"delete_posts";b:1;s:19:"delete_others_posts";b:1;s:22:"delete_published_posts";b:1;s:20:"delete_private_posts";b:1;s:18:"edit_private_posts";b:1;s:18:"read_private_posts";b:1;s:20:"delete_private_pages";b:1;s:18:"edit_private_pages";b:1;s:18:"read_private_pages";b:1;s:12:"delete_users";b:1;s:12:"create_users";b:1;s:17:"unfiltered_upload";b:1;s:14:"edit_dashboard";b:1;s:14:"update_plugins";b:1;s:14:"delete_plugins";b:1;s:15:"install_plugins";b:1;s:13:"update_themes";b:1;s:14:"install_themes";b:1;s:11:"update_core";b:1;s:10:"list_users";b:1;s:12:"remove_users";b:1;s:13:"promote_users";b:1;s:18:"edit_theme_options";b:1;s:13:"delete_themes";b:1;s:6:"export";b:1;}}s:6:"editor";a:2:{s:4:"name";s:6:"Editor";s:12:"capabilities";a:34:{s:17:"moderate_comments";b:1;s:17:"manage_categories";b:1;s:12:"manage_links";b:1;s:12:"upload_files";b:1;s:15:"unfiltered_html";b:1;s:10:"edit_posts";b:1;s:17:"edit_others_posts";b:1;s:20:"edit_published_posts";b:1;s:13:"publish_posts";b:1;s:10:"edit_pages";b:1;s:4:"read";b:1;s:7:"level_7";b:1;s:7:"level_6";b:1;s:7:"level_5";b:1;s:7:"level_4";b:1;s:7:"level_3";b:1;s:7:"level_2";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:17:"edit_others_pages";b:1;s:20:"edit_published_pages";b:1;s:13:"publish_pages";b:1;s:12:"delete_pages";b:1;s:19:"delete_others_pages";b:1;s:22:"delete_published_pages";b:1;s:12:"delete_posts";b:1;s:19:"delete_others_posts";b:1;s:22:"delete_published_posts";b:1;s:20:"delete_private_posts";b:1;s:18:"edit_private_posts";b:1;s:18:"read_private_posts";b:1;s:20:"delete_private_pages";b:1;s:18:"edit_private_pages";b:1;s:18:"read_private_pages";b:1;}}s:6:"author";a:2:{s:4:"name";s:6:"Author";s:12:"capabilities";a:10:{s:12:"upload_files";b:1;s:10:"edit_posts";b:1;s:20:"edit_published_posts";b:1;s:13:"publish_posts";b:1;s:4:"read";b:1;s:7:"level_2";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:12:"delete_posts";b:1;s:22:"delete_published_posts";b:1;}}s:11:"contributor";a:2:{s:4:"name";s:11:"Contributor";s:12:"capabilities";a:5:{s:10:"edit_posts";b:1;s:4:"read";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:12:"delete_posts";b:1;}}s:10:"subscriber";a:2:{s:4:"name";s:10:"Subscriber";s:12:"capabilities";a:2:{s:4:"read";b:1;s:7:"level_0";b:1;}}}','auto'),
('102','fresh_site','1','auto'),
('103','user_count','1','off'),
('104','widget_block','a:6:{i:2;a:1:{s:7:"content";s:19:"<!-- wp:search /-->";}i:3;a:1:{s:7:"content";s:154:"<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Recent Posts</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->";}i:4;a:1:{s:7:"content";s:227:"<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Recent Comments</h2><!-- /wp:heading --><!-- wp:latest-comments {"displayAvatar":false,"displayDate":false,"displayExcerpt":false} /--></div><!-- /wp:group -->";}i:5;a:1:{s:7:"content";s:146:"<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Archives</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->";}i:6;a:1:{s:7:"content";s:150:"<!-- wp:group --><div class="wp-block-group"><!-- wp:heading --><h2>Categories</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->";}s:12:"_multiwidget";i:1;}','auto'),
('105','sidebars_widgets','a:4:{s:19:"wp_inactive_widgets";a:0:{}s:9:"sidebar-1";a:3:{i:0;s:7:"block-2";i:1;s:7:"block-3";i:2;s:7:"block-4";}s:9:"sidebar-2";a:2:{i:0;s:7:"block-5";i:1;s:7:"block-6";}s:13:"array_version";i:3;}','auto'),
('106','cron','a:3:{i:1728142105;a:4:{s:34:"wp_privacy_delete_old_export_files";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:6:"hourly";s:4:"args";a:0:{}s:8:"interval";i:3600;}}s:16:"wp_version_check";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:10:"twicedaily";s:4:"args";a:0:{}s:8:"interval";i:43200;}}s:17:"wp_update_plugins";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:10:"twicedaily";s:4:"args";a:0:{}s:8:"interval";i:43200;}}s:16:"wp_update_themes";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:10:"twicedaily";s:4:"args";a:0:{}s:8:"interval";i:43200;}}}i:1728228505;a:1:{s:30:"wp_site_health_scheduled_check";a:1:{s:32:"40cd750bba9870f18aada2478b24840a";a:3:{s:8:"schedule";s:6:"weekly";s:4:"args";a:0:{}s:8:"interval";i:604800;}}}s:7:"version";i:2;}','auto'),
('107','widget_pages','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('108','widget_calendar','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('109','widget_archives','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('110','widget_media_audio','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('111','widget_media_image','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('112','widget_media_gallery','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('113','widget_media_video','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('114','widget_meta','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('115','widget_search','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('116','widget_recent-posts','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('117','widget_recent-comments','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('118','widget_tag_cloud','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('119','widget_nav_menu','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('120','widget_custom_html','a:1:{s:12:"_multiwidget";i:1;}','auto'),
('121','_transient_wp_core_block_css_files','a:2:{s:7:"version";s:5:"6.6.2";s:5:"files";a:496:{i:0;s:23:"archives/editor-rtl.css";i:1;s:27:"archives/editor-rtl.min.css";i:2;s:19:"archives/editor.css";i:3;s:23:"archives/editor.min.css";i:4;s:22:"archives/style-rtl.css";i:5;s:26:"archives/style-rtl.min.css";i:6;s:18:"archives/style.css";i:7;s:22:"archives/style.min.css";i:8;s:20:"audio/editor-rtl.css";i:9;s:24:"audio/editor-rtl.min.css";i:10;s:16:"audio/editor.css";i:11;s:20:"audio/editor.min.css";i:12;s:19:"audio/style-rtl.css";i:13;s:23:"audio/style-rtl.min.css";i:14;s:15:"audio/style.css";i:15;s:19:"audio/style.min.css";i:16;s:19:"audio/theme-rtl.css";i:17;s:23:"audio/theme-rtl.min.css";i:18;s:15:"audio/theme.css";i:19;s:19:"audio/theme.min.css";i:20;s:21:"avatar/editor-rtl.css";i:21;s:25:"avatar/editor-rtl.min.css";i:22;s:17:"avatar/editor.css";i:23;s:21:"avatar/editor.min.css";i:24;s:20:"avatar/style-rtl.css";i:25;s:24:"avatar/style-rtl.min.css";i:26;s:16:"avatar/style.css";i:27;s:20:"avatar/style.min.css";i:28;s:21:"button/editor-rtl.css";i:29;s:25:"button/editor-rtl.min.css";i:30;s:17:"button/editor.css";i:31;s:21:"button/editor.min.css";i:32;s:20:"button/style-rtl.css";i:33;s:24:"button/style-rtl.min.css";i:34;s:16:"button/style.css";i:35;s:20:"button/style.min.css";i:36;s:22:"buttons/editor-rtl.css";i:37;s:26:"buttons/editor-rtl.min.css";i:38;s:18:"buttons/editor.css";i:39;s:22:"buttons/editor.min.css";i:40;s:21:"buttons/style-rtl.css";i:41;s:25:"buttons/style-rtl.min.css";i:42;s:17:"buttons/style.css";i:43;s:21:"buttons/style.min.css";i:44;s:22:"calendar/style-rtl.css";i:45;s:26:"calendar/style-rtl.min.css";i:46;s:18:"calendar/style.css";i:47;s:22:"calendar/style.min.css";i:48;s:25:"categories/editor-rtl.css";i:49;s:29:"categories/editor-rtl.min.css";i:50;s:21:"categories/editor.css";i:51;s:25:"categories/editor.min.css";i:52;s:24:"categories/style-rtl.css";i:53;s:28:"categories/style-rtl.min.css";i:54;s:20:"categories/style.css";i:55;s:24:"categories/style.min.css";i:56;s:19:"code/editor-rtl.css";i:57;s:23:"code/editor-rtl.min.css";i:58;s:15:"code/editor.css";i:59;s:19:"code/editor.min.css";i:60;s:18:"code/style-rtl.css";i:61;s:22:"code/style-rtl.min.css";i:62;s:14:"code/style.css";i:63;s:18:"code/style.min.css";i:64;s:18:"code/theme-rtl.css";i:65;s:22:"code/theme-rtl.min.css";i:66;s:14:"code/theme.css";i:67;s:18:"code/theme.min.css";i:68;s:22:"columns/editor-rtl.css";i:69;s:26:"columns/editor-rtl.min.css";i:70;s:18:"columns/editor.css";i:71;s:22:"columns/editor.min.css";i:72;s:21:"columns/style-rtl.css";i:73;s:25:"columns/style-rtl.min.css";i:74;s:17:"columns/style.css";i:75;s:21:"columns/style.min.css";i:76;s:29:"comment-content/style-rtl.css";i:77;s:33:"comment-content/style-rtl.min.css";i:78;s:25:"comment-content/style.css";i:79;s:29:"comment-content/style.min.css";i:80;s:30:"comment-template/style-rtl.css";i:81;s:34:"comment-template/style-rtl.min.css";i:82;s:26:"comment-template/style.css";i:83;s:30:"comment-template/style.min.css";i:84;s:42:"comments-pagination-numbers/editor-rtl.css";i:85;s:46:"comments-pagination-numbers/editor-rtl.min.css";i:86;s:38:"comments-pagination-numbers/editor.css";i:87;s:42:"comments-pagination-numbers/editor.min.css";i:88;s:34:"comments-pagination/editor-rtl.css";i:89;s:38:"comments-pagination/editor-rtl.min.css";i:90;s:30:"comments-pagination/editor.css";i:91;s:34:"comments-pagination/editor.min.css";i:92;s:33:"comments-pagination/style-rtl.css";i:93;s:37:"comments-pagination/style-rtl.min.css";i:94;s:29:"comments-pagination/style.css";i:95;s:33:"comments-pagination/style.min.css";i:96;s:29:"comments-title/editor-rtl.css";i:97;s:33:"comments-title/editor-rtl.min.css";i:98;s:25:"comments-title/editor.css";i:99;s:29:"comments-title/editor.min.css";i:100;s:23:"comments/editor-rtl.css";i:101;s:27:"comments/editor-rtl.min.css";i:102;s:19:"comments/editor.css";i:103;s:23:"comments/editor.min.css";i:104;s:22:"comments/style-rtl.css";i:105;s:26:"comments/style-rtl.min.css";i:106;s:18:"comments/style.css";i:107;s:22:"comments/style.min.css";i:108;s:20:"cover/editor-rtl.css";i:109;s:24:"cover/editor-rtl.min.css";i:110;s:16:"cover/editor.css";i:111;s:20:"cover/editor.min.css";i:112;s:19:"cover/style-rtl.css";i:113;s:23:"cover/style-rtl.min.css";i:114;s:15:"cover/style.css";i:115;s:19:"cover/style.min.css";i:116;s:22:"details/editor-rtl.css";i:117;s:26:"details/editor-rtl.min.css";i:118;s:18:"details/editor.css";i:119;s:22:"details/editor.min.css";i:120;s:21:"details/style-rtl.css";i:121;s:25:"details/style-rtl.min.css";i:122;s:17:"details/style.css";i:123;s:21:"details/style.min.css";i:124;s:20:"embed/editor-rtl.css";i:125;s:24:"embed/editor-rtl.min.css";i:126;s:16:"embed/editor.css";i:127;s:20:"embed/editor.min.css";i:128;s:19:"embed/style-rtl.css";i:129;s:23:"embed/style-rtl.min.css";i:130;s:15:"embed/style.css";i:131;s:19:"embed/style.min.css";i:132;s:19:"embed/theme-rtl.css";i:133;s:23:"embed/theme-rtl.min.css";i:134;s:15:"embed/theme.css";i:135;s:19:"embed/theme.min.css";i:136;s:19:"file/editor-rtl.css";i:137;s:23:"file/editor-rtl.min.css";i:138;s:15:"file/editor.css";i:139;s:19:"file/editor.min.css";i:140;s:18:"file/style-rtl.css";i:141;s:22:"file/style-rtl.min.css";i:142;s:14:"file/style.css";i:143;s:18:"file/style.min.css";i:144;s:23:"footnotes/style-rtl.css";i:145;s:27:"footnotes/style-rtl.min.css";i:146;s:19:"footnotes/style.css";i:147;s:23:"footnotes/style.min.css";i:148;s:23:"freeform/editor-rtl.css";i:149;s:27:"freeform/editor-rtl.min.css";i:150;s:19:"freeform/editor.css";i:151;s:23:"freeform/editor.min.css";i:152;s:22:"gallery/editor-rtl.css";i:153;s:26:"gallery/editor-rtl.min.css";i:154;s:18:"gallery/editor.css";i:155;s:22:"gallery/editor.min.css";i:156;s:21:"gallery/style-rtl.css";i:157;s:25:"gallery/style-rtl.min.css";i:158;s:17:"gallery/style.css";i:159;s:21:"gallery/style.min.css";i:160;s:21:"gallery/theme-rtl.css";i:161;s:25:"gallery/theme-rtl.min.css";i:162;s:17:"gallery/theme.css";i:163;s:21:"gallery/theme.min.css";i:164;s:20:"group/editor-rtl.css";i:165;s:24:"group/editor-rtl.min.css";i:166;s:16:"group/editor.css";i:167;s:20:"group/editor.min.css";i:168;s:19:"group/style-rtl.css";i:169;s:23:"group/style-rtl.min.css";i:170;s:15:"group/style.css";i:171;s:19:"group/style.min.css";i:172;s:19:"group/theme-rtl.css";i:173;s:23:"group/theme-rtl.min.css";i:174;s:15:"group/theme.css";i:175;s:19:"group/theme.min.css";i:176;s:21:"heading/style-rtl.css";i:177;s:25:"heading/style-rtl.min.css";i:178;s:17:"heading/style.css";i:179;s:21:"heading/style.min.css";i:180;s:19:"html/editor-rtl.css";i:181;s:23:"html/editor-rtl.min.css";i:182;s:15:"html/editor.css";i:183;s:19:"html/editor.min.css";i:184;s:20:"image/editor-rtl.css";i:185;s:24:"image/editor-rtl.min.css";i:186;s:16:"image/editor.css";i:187;s:20:"image/editor.min.css";i:188;s:19:"image/style-rtl.css";i:189;s:23:"image/style-rtl.min.css";i:190;s:15:"image/style.css";i:191;s:19:"image/style.min.css";i:192;s:19:"image/theme-rtl.css";i:193;s:23:"image/theme-rtl.min.css";i:194;s:15:"image/theme.css";i:195;s:19:"image/theme.min.css";i:196;s:29:"latest-comments/style-rtl.css";i:197;s:33:"latest-comments/style-rtl.min.css";i:198;s:25:"latest-comments/style.css";i:199;s:29:"latest-comments/style.min.css";i:200;s:27:"latest-posts/editor-rtl.css";i:201;s:31:"latest-posts/editor-rtl.min.css";i:202;s:23:"latest-posts/editor.css";i:203;s:27:"latest-posts/editor.min.css";i:204;s:26:"latest-posts/style-rtl.css";i:205;s:30:"latest-posts/style-rtl.min.css";i:206;s:22:"latest-posts/style.css";i:207;s:26:"latest-posts/style.min.css";i:208;s:18:"list/style-rtl.css";i:209;s:22:"list/style-rtl.min.css";i:210;s:14:"list/style.css";i:211;s:18:"list/style.min.css";i:212;s:25:"media-text/editor-rtl.css";i:213;s:29:"media-text/editor-rtl.min.css";i:214;s:21:"media-text/editor.css";i:215;s:25:"media-text/editor.min.css";i:216;s:24:"media-text/style-rtl.css";i:217;s:28:"media-text/style-rtl.min.css";i:218;s:20:"media-text/style.css";i:219;s:24:"media-text/style.min.css";i:220;s:19:"more/editor-rtl.css";i:221;s:23:"more/editor-rtl.min.css";i:222;s:15:"more/editor.css";i:223;s:19:"more/editor.min.css";i:224;s:30:"navigation-link/editor-rtl.css";i:225;s:34:"navigation-link/editor-rtl.min.css";i:226;s:26:"navigation-link/editor.css";i:227;s:30:"navigation-link/editor.min.css";i:228;s:29:"navigation-link/style-rtl.css";i:229;s:33:"navigation-link/style-rtl.min.css";i:230;s:25:"navigation-link/style.css";i:231;s:29:"navigation-link/style.min.css";i:232;s:33:"navigation-submenu/editor-rtl.css";i:233;s:37:"navigation-submenu/editor-rtl.min.css";i:234;s:29:"navigation-submenu/editor.css";i:235;s:33:"navigation-submenu/editor.min.css";i:236;s:25:"navigation/editor-rtl.css";i:237;s:29:"navigation/editor-rtl.min.css";i:238;s:21:"navigation/editor.css";i:239;s:25:"navigation/editor.min.css";i:240;s:24:"navigation/style-rtl.css";i:241;s:28:"navigation/style-rtl.min.css";i:242;s:20:"navigation/style.css";i:243;s:24:"navigation/style.min.css";i:244;s:23:"nextpage/editor-rtl.css";i:245;s:27:"nextpage/editor-rtl.min.css";i:246;s:19:"nextpage/editor.css";i:247;s:23:"nextpage/editor.min.css";i:248;s:24:"page-list/editor-rtl.css";i:249;s:28:"page-list/editor-rtl.min.css";i:250;s:20:"page-list/editor.css";i:251;s:24:"page-list/editor.min.css";i:252;s:23:"page-list/style-rtl.css";i:253;s:27:"page-list/style-rtl.min.css";i:254;s:19:"page-list/style.css";i:255;s:23:"page-list/style.min.css";i:256;s:24:"paragraph/editor-rtl.css";i:257;s:28:"paragraph/editor-rtl.min.css";i:258;s:20:"paragraph/editor.css";i:259;s:24:"paragraph/editor.min.css";i:260;s:23:"paragraph/style-rtl.css";i:261;s:27:"paragraph/style-rtl.min.css";i:262;s:19:"paragraph/style.css";i:263;s:23:"paragraph/style.min.css";i:264;s:25:"post-author/style-rtl.css";i:265;s:29:"post-author/style-rtl.min.css";i:266;s:21:"post-author/style.css";i:267;s:25:"post-author/style.min.css";i:268;s:33:"post-comments-form/editor-rtl.css";i:269;s:37:"post-comments-form/editor-rtl.min.css";i:270;s:29:"post-comments-form/editor.css";i:271;s:33:"post-comments-form/editor.min.css";i:272;s:32:"post-comments-form/style-rtl.css";i:273;s:36:"post-comments-form/style-rtl.min.css";i:274;s:28:"post-comments-form/style.css";i:275;s:32:"post-comments-form/style.min.css";i:276;s:27:"post-content/editor-rtl.css";i:277;s:31:"post-content/editor-rtl.min.css";i:278;s:23:"post-content/editor.css";i:279;s:27:"post-content/editor.min.css";i:280;s:23:"post-date/style-rtl.css";i:281;s:27:"post-date/style-rtl.min.css";i:282;s:19:"post-date/style.css";i:283;s:23:"post-date/style.min.css";i:284;s:27:"post-excerpt/editor-rtl.css";i:285;s:31:"post-excerpt/editor-rtl.min.css";i:286;s:23:"post-excerpt/editor.css";i:287;s:27:"post-excerpt/editor.min.css";i:288;s:26:"post-excerpt/style-rtl.css";i:289;s:30:"post-excerpt/style-rtl.min.css";i:290;s:22:"post-excerpt/style.css";i:291;s:26:"post-excerpt/style.min.css";i:292;s:34:"post-featured-image/editor-rtl.css";i:293;s:38:"post-featured-image/editor-rtl.min.css";i:294;s:30:"post-featured-image/editor.css";i:295;s:34:"post-featured-image/editor.min.css";i:296;s:33:"post-featured-image/style-rtl.css";i:297;s:37:"post-featured-image/style-rtl.min.css";i:298;s:29:"post-featured-image/style.css";i:299;s:33:"post-featured-image/style.min.css";i:300;s:34:"post-navigation-link/style-rtl.css";i:301;s:38:"post-navigation-link/style-rtl.min.css";i:302;s:30:"post-navigation-link/style.css";i:303;s:34:"post-navigation-link/style.min.css";i:304;s:28:"post-template/editor-rtl.css";i:305;s:32:"post-template/editor-rtl.min.css";i:306;s:24:"post-template/editor.css";i:307;s:28:"post-template/editor.min.css";i:308;s:27:"post-template/style-rtl.css";i:309;s:31:"post-template/style-rtl.min.css";i:310;s:23:"post-template/style.css";i:311;s:27:"post-template/style.min.css";i:312;s:24:"post-terms/style-rtl.css";i:313;s:28:"post-terms/style-rtl.min.css";i:314;s:20:"post-terms/style.css";i:315;s:24:"post-terms/style.min.css";i:316;s:24:"post-title/style-rtl.css";i:317;s:28:"post-title/style-rtl.min.css";i:318;s:20:"post-title/style.css";i:319;s:24:"post-title/style.min.css";i:320;s:26:"preformatted/style-rtl.css";i:321;s:30:"preformatted/style-rtl.min.css";i:322;s:22:"preformatted/style.css";i:323;s:26:"preformatted/style.min.css";i:324;s:24:"pullquote/editor-rtl.css";i:325;s:28:"pullquote/editor-rtl.min.css";i:326;s:20:"pullquote/editor.css";i:327;s:24:"pullquote/editor.min.css";i:328;s:23:"pullquote/style-rtl.css";i:329;s:27:"pullquote/style-rtl.min.css";i:330;s:19:"pullquote/style.css";i:331;s:23:"pullquote/style.min.css";i:332;s:23:"pullquote/theme-rtl.css";i:333;s:27:"pullquote/theme-rtl.min.css";i:334;s:19:"pullquote/theme.css";i:335;s:23:"pullquote/theme.min.css";i:336;s:39:"query-pagination-numbers/editor-rtl.css";i:337;s:43:"query-pagination-numbers/editor-rtl.min.css";i:338;s:35:"query-pagination-numbers/editor.css";i:339;s:39:"query-pagination-numbers/editor.min.css";i:340;s:31:"query-pagination/editor-rtl.css";i:341;s:35:"query-pagination/editor-rtl.min.css";i:342;s:27:"query-pagination/editor.css";i:343;s:31:"query-pagination/editor.min.css";i:344;s:30:"query-pagination/style-rtl.css";i:345;s:34:"query-pagination/style-rtl.min.css";i:346;s:26:"query-pagination/style.css";i:347;s:30:"query-pagination/style.min.css";i:348;s:25:"query-title/style-rtl.css";i:349;s:29:"query-title/style-rtl.min.css";i:350;s:21:"query-title/style.css";i:351;s:25:"query-title/style.min.css";i:352;s:20:"query/editor-rtl.css";i:353;s:24:"query/editor-rtl.min.css";i:354;s:16:"query/editor.css";i:355;s:20:"query/editor.min.css";i:356;s:19:"quote/style-rtl.css";i:357;s:23:"quote/style-rtl.min.css";i:358;s:15:"quote/style.css";i:359;s:19:"quote/style.min.css";i:360;s:19:"quote/theme-rtl.css";i:361;s:23:"quote/theme-rtl.min.css";i:362;s:15:"quote/theme.css";i:363;s:19:"quote/theme.min.css";i:364;s:23:"read-more/style-rtl.css";i:365;s:27:"read-more/style-rtl.min.css";i:366;s:19:"read-more/style.css";i:367;s:23:"read-more/style.min.css";i:368;s:18:"rss/editor-rtl.css";i:369;s:22:"rss/editor-rtl.min.css";i:370;s:14:"rss/editor.css";i:371;s:18:"rss/editor.min.css";i:372;s:17:"rss/style-rtl.css";i:373;s:21:"rss/style-rtl.min.css";i:374;s:13:"rss/style.css";i:375;s:17:"rss/style.min.css";i:376;s:21:"search/editor-rtl.css";i:377;s:25:"search/editor-rtl.min.css";i:378;s:17:"search/editor.css";i:379;s:21:"search/editor.min.css";i:380;s:20:"search/style-rtl.css";i:381;s:24:"search/style-rtl.min.css";i:382;s:16:"search/style.css";i:383;s:20:"search/style.min.css";i:384;s:20:"search/theme-rtl.css";i:385;s:24:"search/theme-rtl.min.css";i:386;s:16:"search/theme.css";i:387;s:20:"search/theme.min.css";i:388;s:24:"separator/editor-rtl.css";i:389;s:28:"separator/editor-rtl.min.css";i:390;s:20:"separator/editor.css";i:391;s:24:"separator/editor.min.css";i:392;s:23:"separator/style-rtl.css";i:393;s:27:"separator/style-rtl.min.css";i:394;s:19:"separator/style.css";i:395;s:23:"separator/style.min.css";i:396;s:23:"separator/theme-rtl.css";i:397;s:27:"separator/theme-rtl.min.css";i:398;s:19:"separator/theme.css";i:399;s:23:"separator/theme.min.css";i:400;s:24:"shortcode/editor-rtl.css";i:401;s:28:"shortcode/editor-rtl.min.css";i:402;s:20:"shortcode/editor.css";i:403;s:24:"shortcode/editor.min.css";i:404;s:24:"site-logo/editor-rtl.css";i:405;s:28:"site-logo/editor-rtl.min.css";i:406;s:20:"site-logo/editor.css";i:407;s:24:"site-logo/editor.min.css";i:408;s:23:"site-logo/style-rtl.css";i:409;s:27:"site-logo/style-rtl.min.css";i:410;s:19:"site-logo/style.css";i:411;s:23:"site-logo/style.min.css";i:412;s:27:"site-tagline/editor-rtl.css";i:413;s:31:"site-tagline/editor-rtl.min.css";i:414;s:23:"site-tagline/editor.css";i:415;s:27:"site-tagline/editor.min.css";i:416;s:25:"site-title/editor-rtl.css";i:417;s:29:"site-title/editor-rtl.min.css";i:418;s:21:"site-title/editor.css";i:419;s:25:"site-title/editor.min.css";i:420;s:24:"site-title/style-rtl.css";i:421;s:28:"site-title/style-rtl.min.css";i:422;s:20:"site-title/style.css";i:423;s:24:"site-title/style.min.css";i:424;s:26:"social-link/editor-rtl.css";i:425;s:30:"social-link/editor-rtl.min.css";i:426;s:22:"social-link/editor.css";i:427;s:26:"social-link/editor.min.css";i:428;s:27:"social-links/editor-rtl.css";i:429;s:31:"social-links/editor-rtl.min.css";i:430;s:23:"social-links/editor.css";i:431;s:27:"social-links/editor.min.css";i:432;s:26:"social-links/style-rtl.css";i:433;s:30:"social-links/style-rtl.min.css";i:434;s:22:"social-links/style.css";i:435;s:26:"social-links/style.min.css";i:436;s:21:"spacer/editor-rtl.css";i:437;s:25:"spacer/editor-rtl.min.css";i:438;s:17:"spacer/editor.css";i:439;s:21:"spacer/editor.min.css";i:440;s:20:"spacer/style-rtl.css";i:441;s:24:"spacer/style-rtl.min.css";i:442;s:16:"spacer/style.css";i:443;s:20:"spacer/style.min.css";i:444;s:20:"table/editor-rtl.css";i:445;s:24:"table/editor-rtl.min.css";i:446;s:16:"table/editor.css";i:447;s:20:"table/editor.min.css";i:448;s:19:"table/style-rtl.css";i:449;s:23:"table/style-rtl.min.css";i:450;s:15:"table/style.css";i:451;s:19:"table/style.min.css";i:452;s:19:"table/theme-rtl.css";i:453;s:23:"table/theme-rtl.min.css";i:454;s:15:"table/theme.css";i:455;s:19:"table/theme.min.css";i:456;s:23:"tag-cloud/style-rtl.css";i:457;s:27:"tag-cloud/style-rtl.min.css";i:458;s:19:"tag-cloud/style.css";i:459;s:23:"tag-cloud/style.min.css";i:460;s:28:"template-part/editor-rtl.css";i:461;s:32:"template-part/editor-rtl.min.css";i:462;s:24:"template-part/editor.css";i:463;s:28:"template-part/editor.min.css";i:464;s:27:"template-part/theme-rtl.css";i:465;s:31:"template-part/theme-rtl.min.css";i:466;s:23:"template-part/theme.css";i:467;s:27:"template-part/theme.min.css";i:468;s:30:"term-description/style-rtl.css";i:469;s:34:"term-description/style-rtl.min.css";i:470;s:26:"term-description/style.css";i:471;s:30:"term-description/style.min.css";i:472;s:27:"text-columns/editor-rtl.css";i:473;s:31:"text-columns/editor-rtl.min.css";i:474;s:23:"text-columns/editor.css";i:475;s:27:"text-columns/editor.min.css";i:476;s:26:"text-columns/style-rtl.css";i:477;s:30:"text-columns/style-rtl.min.css";i:478;s:22:"text-columns/style.css";i:479;s:26:"text-columns/style.min.css";i:480;s:19:"verse/style-rtl.css";i:481;s:23:"verse/style-rtl.min.css";i:482;s:15:"verse/style.css";i:483;s:19:"verse/style.min.css";i:484;s:20:"video/editor-rtl.css";i:485;s:24:"video/editor-rtl.min.css";i:486;s:16:"video/editor.css";i:487;s:20:"video/editor.min.css";i:488;s:19:"video/style-rtl.css";i:489;s:23:"video/style-rtl.min.css";i:490;s:15:"video/style.css";i:491;s:19:"video/style.min.css";i:492;s:19:"video/theme-rtl.css";i:493;s:23:"video/theme-rtl.min.css";i:494;s:15:"video/theme.css";i:495;s:19:"video/theme.min.css";}}','on'),
('122','_site_transient_timeout_wp_theme_files_patterns-9aa72ae238f21c81052870c49ba7516e','1728143905','off'),
('123','_site_transient_wp_theme_files_patterns-9aa72ae238f21c81052870c49ba7516e','a:2:{s:7:"version";s:3:"1.2";s:8:"patterns";a:56:{s:15:"banner-hero.php";a:5:{s:5:"title";s:4:"Hero";s:4:"slug";s:28:"twentytwentyfour/banner-hero";s:11:"description";s:69:"A hero section with a title, a paragraph, a CTA button, and an image.";s:13:"viewportWidth";i:1400;s:10:"categories";a:3:{i:0;s:6:"banner";i:1;s:14:"call-to-action";i:2;s:8:"featured";}}s:30:"banner-project-description.php";a:5:{s:5:"title";s:19:"Project description";s:4:"slug";s:43:"twentytwentyfour/banner-project-description";s:11:"description";s:64:"Project description section with title, paragraph, and an image.";s:13:"viewportWidth";i:1400;s:10:"categories";a:4:{i:0;s:8:"featured";i:1;s:6:"banner";i:2;s:5:"about";i:3;s:9:"portfolio";}}s:30:"cta-content-image-on-right.php";a:5:{s:5:"title";s:34:"Call to action with image on right";s:4:"slug";s:43:"twentytwentyfour/cta-content-image-on-right";s:11:"description";s:76:"A title, paragraph, two CTA buttons, and an image for a general CTA section.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:14:"call-to-action";i:1;s:6:"banner";}}s:15:"cta-pricing.php";a:5:{s:5:"title";s:7:"Pricing";s:4:"slug";s:28:"twentytwentyfour/cta-pricing";s:11:"description";s:69:"A pricing section with a title, a paragraph and three pricing levels.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:14:"call-to-action";i:1;s:8:"services";}}s:12:"cta-rsvp.php";a:5:{s:5:"title";s:4:"RSVP";s:4:"slug";s:25:"twentytwentyfour/cta-rsvp";s:11:"description";s:63:"A large RSVP heading sideways, a description, and a CTA button.";s:13:"viewportWidth";i:1100;s:10:"categories";a:2:{i:0;s:14:"call-to-action";i:1;s:8:"featured";}}s:27:"cta-services-image-left.php";a:5:{s:5:"title";s:42:"Services call to action with image on left";s:4:"slug";s:40:"twentytwentyfour/cta-services-image-left";s:11:"description";s:65:"An image, title, paragraph and a CTA button to describe services.";s:13:"viewportWidth";i:1400;s:10:"categories";a:4:{i:0;s:14:"call-to-action";i:1;s:6:"banner";i:2;s:8:"featured";i:3;s:8:"services";}}s:26:"cta-subscribe-centered.php";a:5:{s:5:"title";s:23:"Centered call to action";s:4:"slug";s:39:"twentytwentyfour/cta-subscribe-centered";s:11:"description";s:67:"Subscribers CTA section with a title, a paragraph and a CTA button.";s:10:"categories";a:1:{i:0;s:14:"call-to-action";}s:8:"keywords";a:3:{i:0;s:10:"newsletter";i:1;s:9:"subscribe";i:2;s:6:"button";}}s:28:"footer-centered-logo-nav.php";a:5:{s:5:"title";s:40:"Footer with centered logo and navigation";s:4:"slug";s:41:"twentytwentyfour/footer-centered-logo-nav";s:11:"description";s:73:"A footer section with a centered logo, navigation, and WordPress credits.";s:10:"categories";a:1:{i:0;s:6:"footer";}s:10:"blockTypes";a:1:{i:0;s:25:"core/template-part/footer";}}s:25:"footer-colophon-3-col.php";a:5:{s:5:"title";s:31:"Footer with colophon, 3 columns";s:4:"slug";s:38:"twentytwentyfour/footer-colophon-3-col";s:11:"description";s:47:"A footer section with a colophon and 3 columns.";s:10:"categories";a:1:{i:0;s:6:"footer";}s:10:"blockTypes";a:1:{i:0;s:25:"core/template-part/footer";}}s:10:"footer.php";a:5:{s:5:"title";s:31:"Footer with colophon, 4 columns";s:4:"slug";s:23:"twentytwentyfour/footer";s:11:"description";s:47:"A footer section with a colophon and 4 columns.";s:10:"categories";a:1:{i:0;s:6:"footer";}s:10:"blockTypes";a:1:{i:0;s:25:"core/template-part/footer";}}s:29:"gallery-full-screen-image.php";a:4:{s:5:"title";s:17:"Full screen image";s:4:"slug";s:42:"twentytwentyfour/gallery-full-screen-image";s:11:"description";s:51:"A cover image section that covers the entire width.";s:10:"categories";a:2:{i:0;s:7:"gallery";i:1;s:9:"portfolio";}}s:36:"gallery-offset-images-grid-2-col.php";a:6:{s:5:"title";s:25:"Offset gallery, 2 columns";s:4:"slug";s:49:"twentytwentyfour/gallery-offset-images-grid-2-col";s:11:"description";s:51:"A gallery section with 2 columns and offset images.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:7:"gallery";i:1;s:9:"portfolio";}s:8:"keywords";a:5:{i:0;s:7:"project";i:1;s:6:"images";i:2;s:5:"media";i:3;s:7:"masonry";i:4;s:7:"columns";}}s:36:"gallery-offset-images-grid-3-col.php";a:6:{s:5:"title";s:25:"Offset gallery, 3 columns";s:4:"slug";s:49:"twentytwentyfour/gallery-offset-images-grid-3-col";s:11:"description";s:51:"A gallery section with 3 columns and offset images.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:7:"gallery";i:1;s:9:"portfolio";}s:8:"keywords";a:5:{i:0;s:7:"project";i:1;s:6:"images";i:2;s:5:"media";i:3;s:7:"masonry";i:4;s:7:"columns";}}s:36:"gallery-offset-images-grid-4-col.php";a:6:{s:5:"title";s:25:"Offset gallery, 4 columns";s:4:"slug";s:49:"twentytwentyfour/gallery-offset-images-grid-4-col";s:11:"description";s:51:"A gallery section with 4 columns and offset images.";s:13:"viewportWidth";i:1400;s:10:"categories";a:3:{i:0;s:7:"gallery";i:1;s:8:"featured";i:2;s:9:"portfolio";}s:8:"keywords";a:5:{i:0;s:7:"project";i:1;s:6:"images";i:2;s:5:"media";i:3;s:7:"masonry";i:4;s:7:"columns";}}s:26:"gallery-project-layout.php";a:5:{s:5:"title";s:14:"Project layout";s:4:"slug";s:39:"twentytwentyfour/gallery-project-layout";s:11:"description";s:54:"A gallery section with a project layout with 2 images.";s:13:"viewportWidth";i:1600;s:10:"categories";a:3:{i:0;s:7:"gallery";i:1;s:8:"featured";i:2;s:9:"portfolio";}}s:14:"hidden-404.php";a:4:{s:5:"title";s:3:"404";s:4:"slug";s:27:"twentytwentyfour/hidden-404";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:19:"hidden-comments.php";a:4:{s:5:"title";s:8:"Comments";s:4:"slug";s:32:"twentytwentyfour/hidden-comments";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:21:"hidden-no-results.php";a:4:{s:5:"title";s:10:"No results";s:4:"slug";s:34:"twentytwentyfour/hidden-no-results";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:25:"hidden-portfolio-hero.php";a:4:{s:5:"title";s:14:"Portfolio hero";s:4:"slug";s:38:"twentytwentyfour/hidden-portfolio-hero";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:20:"hidden-post-meta.php";a:4:{s:5:"title";s:9:"Post meta";s:4:"slug";s:33:"twentytwentyfour/hidden-post-meta";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:26:"hidden-post-navigation.php";a:4:{s:5:"title";s:15:"Post navigation";s:4:"slug";s:39:"twentytwentyfour/hidden-post-navigation";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:17:"hidden-search.php";a:4:{s:5:"title";s:6:"Search";s:4:"slug";s:30:"twentytwentyfour/hidden-search";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:18:"hidden-sidebar.php";a:4:{s:5:"title";s:7:"Sidebar";s:4:"slug";s:31:"twentytwentyfour/hidden-sidebar";s:11:"description";s:0:"";s:8:"inserter";b:0;}s:23:"page-about-business.php";a:8:{s:5:"title";s:5:"About";s:4:"slug";s:36:"twentytwentyfour/page-about-business";s:11:"description";s:147:"A business about page with a hero section, a text section, a services section, a team section, a clients section, a FAQ section, and a CTA section.";s:13:"viewportWidth";i:1400;s:10:"categories";a:1:{i:0;s:21:"twentytwentyfour_page";}s:8:"keywords";a:1:{i:0;s:7:"starter";}s:10:"blockTypes";a:1:{i:0;s:17:"core/post-content";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:22:"page-home-blogging.php";a:7:{s:5:"title";s:13:"Blogging home";s:4:"slug";s:35:"twentytwentyfour/page-home-blogging";s:11:"description";s:92:"A blogging home page with a hero section, a text section, a blog section, and a CTA section.";s:13:"viewportWidth";i:1400;s:10:"categories";a:1:{i:0;s:21:"twentytwentyfour_page";}s:8:"keywords";a:2:{i:0;s:4:"page";i:1;s:7:"starter";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:22:"page-home-business.php";a:8:{s:5:"title";s:13:"Business home";s:4:"slug";s:35:"twentytwentyfour/page-home-business";s:11:"description";s:146:"A business home page with a hero section, a text section, a services section, a team section, a clients section, a FAQ section, and a CTA section.";s:13:"viewportWidth";i:1400;s:10:"categories";a:1:{i:0;s:21:"twentytwentyfour_page";}s:8:"keywords";a:1:{i:0;s:7:"starter";}s:10:"blockTypes";a:1:{i:0;s:17:"core/post-content";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:31:"page-home-portfolio-gallery.php";a:8:{s:5:"title";s:28:"Portfolio home image gallery";s:4:"slug";s:34:"twentytwentyfour/page-home-gallery";s:11:"description";s:45:"A porfolio home page that features a gallery.";s:13:"viewportWidth";i:1400;s:10:"categories";a:1:{i:0;s:21:"twentytwentyfour_page";}s:8:"keywords";a:1:{i:0;s:7:"starter";}s:10:"blockTypes";a:1:{i:0;s:17:"core/post-content";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:23:"page-home-portfolio.php";a:8:{s:5:"title";s:40:"Portfolio home with post featured images";s:4:"slug";s:36:"twentytwentyfour/page-home-portfolio";s:11:"description";s:94:"A portfolio home page with a description and a 4-column post section with only feature images.";s:13:"viewportWidth";i:1400;s:10:"categories";a:1:{i:0;s:21:"twentytwentyfour_page";}s:8:"keywords";a:1:{i:0;s:7:"starter";}s:10:"blockTypes";a:1:{i:0;s:17:"core/post-content";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:27:"page-newsletter-landing.php";a:8:{s:5:"title";s:18:"Newsletter landing";s:4:"slug";s:40:"twentytwentyfour/page-newsletter-landing";s:11:"description";s:62:"A block with a newsletter subscription CTA for a landing page.";s:13:"viewportWidth";i:1100;s:10:"categories";a:3:{i:0;s:14:"call-to-action";i:1;s:21:"twentytwentyfour_page";i:2;s:8:"featured";}s:8:"keywords";a:1:{i:0;s:7:"starter";}s:10:"blockTypes";a:1:{i:0;s:17:"core/post-content";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:27:"page-portfolio-overview.php";a:8:{s:5:"title";s:26:"Portfolio project overview";s:4:"slug";s:40:"twentytwentyfour/page-portfolio-overview";s:11:"description";s:138:"A full portfolio page with a section for project description, project details, a full screen image, and a gallery section with two images.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:21:"twentytwentyfour_page";i:1;s:8:"featured";}s:8:"keywords";a:1:{i:0;s:7:"starter";}s:10:"blockTypes";a:1:{i:0;s:17:"core/post-content";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:21:"page-rsvp-landing.php";a:8:{s:5:"title";s:12:"RSVP landing";s:4:"slug";s:34:"twentytwentyfour/page-rsvp-landing";s:11:"description";s:63:"A large RSVP heading sideways, a description, and a CTA button.";s:13:"viewportWidth";i:1100;s:10:"categories";a:1:{i:0;s:21:"twentytwentyfour_page";}s:8:"keywords";a:1:{i:0;s:7:"starter";}s:10:"blockTypes";a:1:{i:0;s:17:"core/post-content";}s:9:"postTypes";a:2:{i:0;s:4:"page";i:1;s:11:"wp_template";}}s:15:"posts-1-col.php";a:5:{s:5:"title";s:23:"List of posts, 1 column";s:4:"slug";s:28:"twentytwentyfour/posts-1-col";s:11:"description";s:26:"A list of posts, 1 column.";s:10:"categories";a:1:{i:0;s:5:"query";}s:10:"blockTypes";a:1:{i:0;s:10:"core/query";}}s:15:"posts-3-col.php";a:5:{s:5:"title";s:24:"List of posts, 3 columns";s:4:"slug";s:28:"twentytwentyfour/posts-3-col";s:11:"description";s:27:"A list of posts, 3 columns.";s:10:"categories";a:1:{i:0;s:5:"query";}s:10:"blockTypes";a:1:{i:0;s:10:"core/query";}}s:20:"posts-grid-2-col.php";a:5:{s:5:"title";s:49:"Grid of posts featuring the first post, 2 columns";s:4:"slug";s:33:"twentytwentyfour/posts-grid-2-col";s:11:"description";s:52:"A grid of posts featuring the first post, 2 columns.";s:10:"categories";a:1:{i:0;s:5:"query";}s:10:"blockTypes";a:1:{i:0;s:10:"core/query";}}s:27:"posts-images-only-3-col.php";a:5:{s:5:"title";s:42:"Posts with featured images only, 3 columns";s:4:"slug";s:40:"twentytwentyfour/posts-images-only-3-col";s:11:"description";s:53:"A list of posts with featured images only, 3 columns.";s:10:"categories";a:1:{i:0;s:5:"query";}s:10:"blockTypes";a:1:{i:0;s:10:"core/query";}}s:34:"posts-images-only-offset-4-col.php";a:4:{s:5:"title";s:49:"Offset posts with featured images only, 4 columns";s:4:"slug";s:47:"twentytwentyfour/posts-images-only-offset-4-col";s:11:"description";s:53:"A list of posts with featured images only, 4 columns.";s:10:"categories";a:1:{i:0;s:5:"posts";}}s:14:"posts-list.php";a:5:{s:5:"title";s:38:"List of posts without images, 1 column";s:4:"slug";s:27:"twentytwentyfour/posts-list";s:11:"description";s:41:"A list of posts without images, 1 column.";s:10:"categories";a:2:{i:0;s:5:"query";i:1;s:5:"posts";}s:10:"blockTypes";a:1:{i:0;s:10:"core/query";}}s:14:"team-4-col.php";a:5:{s:5:"title";s:23:"Team members, 4 columns";s:4:"slug";s:27:"twentytwentyfour/team-4-col";s:11:"description";s:76:"A team section, with a heading, a paragraph, and 4 columns for team members.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:4:"team";i:1;s:5:"about";}}s:29:"template-archive-blogging.php";a:6:{s:5:"title";s:25:"Blogging archive template";s:4:"slug";s:42:"twentytwentyfour/template-archive-blogging";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:5:{i:0;s:7:"archive";i:1;s:8:"category";i:2;s:3:"tag";i:3;s:6:"author";i:4;s:4:"date";}}s:30:"template-archive-portfolio.php";a:6:{s:5:"title";s:26:"Portfolio archive template";s:4:"slug";s:43:"twentytwentyfour/template-archive-portfolio";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:1:{i:0;s:7:"archive";}}s:26:"template-home-blogging.php";a:6:{s:5:"title";s:22:"Blogging home template";s:4:"slug";s:39:"twentytwentyfour/template-home-blogging";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:3:{i:0;s:10:"front-page";i:1;s:5:"index";i:2;s:4:"home";}}s:26:"template-home-business.php";a:6:{s:5:"title";s:22:"Business home template";s:4:"slug";s:39:"twentytwentyfour/template-home-business";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:2:{i:0;s:10:"front-page";i:1;s:4:"home";}}s:27:"template-home-portfolio.php";a:6:{s:5:"title";s:49:"Portfolio home template with post featured images";s:4:"slug";s:40:"twentytwentyfour/template-home-portfolio";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:2:{i:0;s:10:"front-page";i:1;s:4:"home";}}s:27:"template-index-blogging.php";a:6:{s:5:"title";s:23:"Blogging index template";s:4:"slug";s:40:"twentytwentyfour/template-index-blogging";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:2:{i:0;s:5:"index";i:1;s:4:"home";}}s:28:"template-index-portfolio.php";a:6:{s:5:"title";s:24:"Portfolio index template";s:4:"slug";s:41:"twentytwentyfour/template-index-portfolio";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:1:{i:0;s:5:"index";}}s:28:"template-search-blogging.php";a:6:{s:5:"title";s:24:"Blogging search template";s:4:"slug";s:41:"twentytwentyfour/template-search-blogging";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:1:{i:0;s:6:"search";}}s:29:"template-search-portfolio.php";a:6:{s:5:"title";s:25:"Portfolio search template";s:4:"slug";s:42:"twentytwentyfour/template-search-portfolio";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:1:{i:0;s:6:"search";}}s:29:"template-single-portfolio.php";a:6:{s:5:"title";s:30:"Portfolio single post template";s:4:"slug";s:42:"twentytwentyfour/template-single-portfolio";s:11:"description";s:0:"";s:13:"viewportWidth";i:1400;s:8:"inserter";b:0;s:13:"templateTypes";a:2:{i:0;s:5:"posts";i:1;s:6:"single";}}s:24:"testimonial-centered.php";a:6:{s:5:"title";s:20:"Centered testimonial";s:4:"slug";s:37:"twentytwentyfour/testimonial-centered";s:11:"description";s:66:"A centered testimonial section with a avatar, name, and job title.";s:13:"viewportWidth";i:1300;s:10:"categories";a:2:{i:0;s:12:"testimonials";i:1;s:4:"text";}s:8:"keywords";a:3:{i:0;s:5:"quote";i:1;s:6:"review";i:2;s:5:"about";}}s:27:"text-alternating-images.php";a:5:{s:5:"title";s:28:"Text with alternating images";s:4:"slug";s:40:"twentytwentyfour/text-alternating-images";s:11:"description";s:80:"A text section, then a two column section with text in one and image in another.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:4:"text";i:1;s:5:"about";}}s:33:"text-centered-statement-small.php";a:6:{s:5:"title";s:25:"Centered statement, small";s:4:"slug";s:46:"twentytwentyfour/text-centered-statement-small";s:11:"description";s:55:"A centered itallic text statement with compact padding.";s:13:"viewportWidth";i:1200;s:10:"categories";a:2:{i:0;s:4:"text";i:1;s:5:"about";}s:8:"keywords";a:2:{i:0;s:7:"mission";i:1;s:12:"introduction";}}s:27:"text-centered-statement.php";a:6:{s:5:"title";s:18:"Centered statement";s:4:"slug";s:40:"twentytwentyfour/text-centered-statement";s:11:"description";s:48:"A centered text statement with a large paddings.";s:13:"viewportWidth";i:1400;s:10:"categories";a:3:{i:0;s:4:"text";i:1;s:5:"about";i:2;s:8:"featured";}s:8:"keywords";a:2:{i:0;s:7:"mission";i:1;s:12:"introduction";}}s:12:"text-faq.php";a:6:{s:5:"title";s:3:"FAQ";s:4:"slug";s:25:"twentytwentyfour/text-faq";s:11:"description";s:80:"A FAQ section with a large FAQ heading and list of toggle questions and answers.";s:13:"viewportWidth";i:1400;s:10:"categories";a:3:{i:0;s:4:"text";i:1;s:5:"about";i:2;s:8:"featured";}s:8:"keywords";a:4:{i:0;s:3:"faq";i:1;s:5:"about";i:2;s:10:"frequently";i:3;s:5:"asked";}}s:27:"text-feature-grid-3-col.php";a:5:{s:5:"title";s:23:"Feature grid, 3 columns";s:4:"slug";s:40:"twentytwentyfour/text-feature-grid-3-col";s:11:"description";s:62:"A feature grid of 2 rows and 3 columns with headings and text.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:4:"text";i:1;s:5:"about";}}s:24:"text-project-details.php";a:5:{s:5:"title";s:15:"Project details";s:4:"slug";s:37:"twentytwentyfour/text-project-details";s:11:"description";s:40:"A text only section for project details.";s:13:"viewportWidth";i:1400;s:10:"categories";a:2:{i:0;s:4:"text";i:1;s:9:"portfolio";}}s:31:"text-title-left-image-right.php";a:5:{s:5:"title";s:49:"Title text and button on left with image on right";s:4:"slug";s:44:"twentytwentyfour/text-title-left-image-right";s:11:"description";s:77:"A title, a paragraph and a CTA button on the left with an image on the right.";s:13:"viewportWidth";i:1400;s:10:"categories";a:3:{i:0;s:6:"banner";i:1;s:5:"about";i:2;s:8:"featured";}}}}','off');

DROP TABLE IF EXISTS wp_postmeta;
CREATE TABLE "wp_postmeta" (
"meta_id" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"post_id" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"meta_key" text DEFAULT NULL COLLATE NOCASE,
"meta_value" text COLLATE NOCASE);

INSERT INTO wp_postmeta ('meta_id','post_id','meta_key','meta_value') VALUES
('1','2','_wp_page_template','default'),
('2','3','_wp_page_template','default');

DROP TABLE IF EXISTS wp_posts;
CREATE TABLE "wp_posts" (
"ID" integer PRIMARY KEY AUTOINCREMENT NOT NULL ON CONFLICT REPLACE DEFAULT 0,
"post_author" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"post_date" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"post_date_gmt" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"post_content" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"post_title" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"post_excerpt" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"post_status" text NOT NULL ON CONFLICT REPLACE DEFAULT 'publish' COLLATE NOCASE,
"comment_status" text NOT NULL ON CONFLICT REPLACE DEFAULT 'open' COLLATE NOCASE,
"ping_status" text NOT NULL ON CONFLICT REPLACE DEFAULT 'open' COLLATE NOCASE,
"post_password" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"post_name" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"to_ping" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"pinged" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"post_modified" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"post_modified_gmt" text NOT NULL ON CONFLICT REPLACE DEFAULT '0000-00-00 00:00:00' COLLATE NOCASE,
"post_content_filtered" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"post_parent" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"guid" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"menu_order" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0',
"post_type" text NOT NULL ON CONFLICT REPLACE DEFAULT 'post' COLLATE NOCASE,
"post_mime_type" text NOT NULL ON CONFLICT REPLACE DEFAULT '' COLLATE NOCASE,
"comment_count" integer NOT NULL ON CONFLICT REPLACE DEFAULT '0');

INSERT INTO wp_posts ('ID','post_author','post_date','post_date_gmt','post_content','post_title','post_excerpt','post_status','comment_status','ping_status','post_password','post_name','to_ping','pinged','post_modified','post_modified_gmt','post_content_filtered','post_parent','guid','menu_order','post_type','post_mime_type','comment_count') VALUES
('1','1','2024-10-05 15:28:25','2024-10-05 15:28:25','<!-- wp:paragraph -->' || char(10) || '<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>' || char(10) || '<!-- /wp:paragraph -->','Hello world!','','publish','open','open','','hello-world','','','2024-10-05 15:28:25','2024-10-05 15:28:25','','0','http://localhost:24412/?p=1','0','post','','1'),
('2','1','2024-10-05 15:28:25','2024-10-05 15:28:25','<!-- wp:paragraph -->' || char(10) || '<p>This is an example page. It''s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '' || char(10) || '<!-- wp:quote -->' || char(10) || '<blockquote class="wp-block-quote"><p>Hi there! I''m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin'' caught in the rain.)</p></blockquote>' || char(10) || '<!-- /wp:quote -->' || char(10) || '' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>...or something like this:</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '' || char(10) || '<!-- wp:quote -->' || char(10) || '<blockquote class="wp-block-quote"><p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p></blockquote>' || char(10) || '<!-- /wp:quote -->' || char(10) || '' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>As a new WordPress user, you should go to <a href="http://localhost:24412/wp-admin/">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>' || char(10) || '<!-- /wp:paragraph -->','Sample Page','','publish','closed','open','','sample-page','','','2024-10-05 15:28:25','2024-10-05 15:28:25','','0','http://localhost:24412/?page_id=2','0','page','','0'),
('3','1','2024-10-05 15:28:25','2024-10-05 15:28:25','<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">Who we are</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>Our website address is: http://localhost:24412.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">Comments</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">Media</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">Cookies</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">Embedded content from other websites</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">Who we share your data with</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>If you request a password reset, your IP address will be included in the reset email.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">How long we retain your data</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">What rights you have over your data</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '<!-- wp:heading -->' || char(10) || '<h2 class="wp-block-heading">Where your data is sent</h2>' || char(10) || '<!-- /wp:heading -->' || char(10) || '<!-- wp:paragraph -->' || char(10) || '<p><strong class="privacy-policy-tutorial">Suggested text: </strong>Visitor comments may be checked through an automated spam detection service.</p>' || char(10) || '<!-- /wp:paragraph -->' || char(10) || '','Privacy Policy','','draft','closed','open','','privacy-policy','','','2024-10-05 15:28:25','2024-10-05 15:28:25','','0','http://localhost:24412/?page_id=3','0','page','','0');

PRAGMA foreign_keys = ON
