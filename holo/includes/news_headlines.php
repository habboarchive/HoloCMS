<?php
/*---------------------------------------------------+
| HoloCMS - Website and Content Management System
+----------------------------------------------------+
| Copyright © 2008 Meth0d
+----------------------------------------------------+
| HoloCMS is provided "as is" and comes without
| warrenty of any kind. 
+---------------------------------------------------*/

if (!defined("IN_HOLOCMS")) { header("Location: ../index.php"); exit; }

// So many queries just to make it look pretty..

$news_1_query = mysql_query("SELECT num, title, date, topstory, short_story FROM cms_news ORDER BY num DESC LIMIT 1");
$news_2_query = mysql_query("SELECT num, title, date, topstory, short_story FROM cms_news ORDER BY num DESC LIMIT 1,2");
$news_3_query = mysql_query("SELECT num, title, date, topstory, short_story FROM cms_news ORDER BY num DESC LIMIT 2,3");
$news_4_query = mysql_query("SELECT num, title, date, topstory, short_story FROM cms_news ORDER BY num DESC LIMIT 3,4");

$news_1_row = mysql_fetch_assoc($news_1_query);
$news_2_row = mysql_fetch_assoc($news_2_query);
$news_3_row = mysql_fetch_assoc($news_3_query);
$news_4_row = mysql_fetch_assoc($news_4_query);

$news_1_title = htmlspecialchars(stripslashes($news_1_row['title']));
$news_1_snippet = htmlspecialchars(stripslashes($news_1_row['short_story']));
$news_1_date = stripslashes($news_1_row['date']);
$news_1_topstory = stripslashes($news_1_row['topstory']);
$news_1_id = stripslashes($news_1_row['num']);

if(empty($news_1_title)){ $news_1_title = "Article not found"; }
if(empty($news_1_snippet)){ $news_1_snippet = "This article does not exist yet."; }
if(empty($news_1_topstory)){ $news_1_topstory = "http://images.habbohotel.co.uk/c_images/Top_Story_Images/shabbolin_300x187_A.gif"; }

$news_2_title = htmlspecialchars(stripslashes($news_2_row['title']));
$news_2_snippet = htmlspecialchars(stripslashes($news_2_row['short_story']));
$news_2_date = stripslashes($news_2_row['date']);
$news_2_topstory = stripslashes($news_2_row['topstory']);
$news_2_id = stripslashes($news_2_row['num']);

if(empty($news_2_title)){ $news_2_title = "Article not found"; }
if(empty($news_2_snippet)){ $news_2_snippet = "This article does not exist yet."; }
if(empty($news_2_topstory)){ $news_2_topstory = "http://images.habbohotel.co.uk/c_images/Top_Story_Images/shabbolin_300x187_A.gif"; }

$news_3_title = htmlspecialchars(stripslashes($news_3_row['title']));
$news_3_snippet = htmlspecialchars(stripslashes($news_3_row['short_story']));
$news_3_date = stripslashes($news_3_row['date']);
$news_3_id = stripslashes($news_3_row['num']);

if(empty($news_3_title)){ $news_3_title = "Article not found"; }
if(empty($news_3_date)){ $news_3_date = "This article does not exist yet."; }

$news_4_title = htmlspecialchars(stripslashes($news_4_row['title']));
$news_4_snippet = htmlspecialchars(stripslashes($news_4_row['short_story']));
$news_4_date = stripslashes($news_4_row['date']);
$news_4_id = stripslashes($news_4_row['num']);

if(empty($news_4_title)){ $news_4_title = "Article not found"; }
if(empty($news_4_date)){ $news_4_date = "This article does not exist yet."; }

?>