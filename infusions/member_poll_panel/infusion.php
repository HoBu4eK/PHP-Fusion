<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: J.Falk (Falk)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

$locale = fusion_get_locale("", LOCALE.LOCALESET."setup.php");

// Infusion general information
$inf_title = $locale['polls']['title'];
$inf_description = $locale['polls']['description'];
$inf_version = "1.00";
$inf_developer = "PHP Fusion Development Team";
$inf_email = "";
$inf_weburl = "https://www.php-fusion.co.uk";
$inf_folder = "member_poll_panel";

// Multilanguage table for Administration
$inf_mlt[] = array(
"title" => $locale['setup_3207'], 
"rights" => "PO",
);

// Create tables
$inf_newtable[] = DB_POLL_VOTES." (
	vote_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	vote_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
	vote_opt SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
	poll_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (vote_id)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci";

$inf_newtable[] = DB_POLLS." (
	poll_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	poll_title VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_0 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_1 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_2 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_3 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_4 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_5 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_6 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_7 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_8 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_9 VARCHAR(200) NOT NULL DEFAULT '',
	poll_started INT(10) UNSIGNED NOT NULL DEFAULT '0',
	poll_ended INT(10) UNSIGNED NOT NULL DEFAULT '0',
	poll_language VARCHAR(50) NOT NULL DEFAULT '".LANGUAGE."',
	PRIMARY KEY (poll_id)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci";

// Automatic enable of the latest articles panel
$inf_insertdbrow[] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list, panel_restriction) VALUES('".$locale['setup_3407']."', 'member_poll_panel', '', '1', '5', 'file', '0', '0', '1', '', '0')";

// Position these links under Content Administration
$inf_insertdbrow[] = DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PO', 'polls.gif', '".$locale['setup_3022']."', '".INFUSIONS."member_poll_panel/member_poll_panel_admin.php', '1')";

$enabled_languages = makefilelist(LOCALE, ".|..", TRUE, "folders");
// Create a link for all installed languages
if (!empty($enabled_languages)) {
    foreach($enabled_languages as $language) {
        $locale = fusion_get_locale("", LOCALE.$language."/setup.php");
        $mlt_insertdbrow[$language][] = DB_SITE_LINKS." (link_name, link_url, link_visibility, link_position, link_window, link_order, link_language) VALUES ('".$locale['setup_3022']."', '".INFUSIONS."members_poll_panel/polls_archive.php', '0', '1', '0', '2', '".$language."')";
        $mlt_deldbrow[$language][] = DB_SITE_LINKS." WHERE link_url='".INFUSIONS."members_poll_panel/polls_archive.php' AND link_language='".$language."'";
    }
} else {
    $inf_insertdbrow[] = DB_SITE_LINKS." (link_name, link_url, link_visibility, link_position, link_window, link_order, link_language) VALUES ('".$locale['setup_3022']."', '".INFUSIONS."members_poll_panel/polls_archive.php', '0', '1', '0', '2', '".LANGUAGE."')";
    $inf_deldbrow[] = DB_SITE_LINKS." WHERE link_url='".INFUSIONS."members_poll_panel/polls_archive.php' AND link_language='".LANGUAGE."'";
}

// Defuse cleaning	
$inf_droptable[] = DB_POLLS;
$inf_droptable[] = DB_POLL_VOTES;

$inf_deldbrow[] = DB_PANELS." WHERE panel_name='".$locale['setup_3407']."'";
$inf_deldbrow[] = DB_ADMIN." WHERE admin_rights='PO'";
