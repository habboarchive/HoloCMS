<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================+
|| # Friends Management by Yifan Lu
|| # www.obbahhotel.com
|+===================================================*/

include('../core.php');

if(isset($_GET['pageNumber'])){

$page = $_GET['pageNumber'];
$pagesize = $_GET['pageSize'];

?>
                        <div id="friend-list" class="clearfix">
<div id="friend-list-header-container" class="clearfix">
    <div id="friend-list-header">
        <div class="page-limit">
            <div class="big-icons friend-header-icon">Friends
                <br />Show

           		<?php if($pagesize == 30){ ?>
                30 |
                <a class="category-limit" id="pagelimit-50">50</a> |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 50){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				50 |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 100){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				<a class="category-limit" id="pagelimit-50">50</a> |
                100
                <?php } ?>
            </div>
        </div>
    </div>
	<div id="friend-list-paging">
	<?php
		if($page <> 1){
		$pageminus = $page - 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageminus."\">&lt;&lt;</a> |";
		}
		$afriendscount = mysql_query("SELECT COUNT(*) FROM messenger_friendships WHERE userid = '".$my_id."' OR friendid = '".$my_id."'") or die(mysql_error());
		$friendscount = mysql_result($afriendscount, 0);

		$pages = ceil($friendscount / $pagesize);

		if($pages == 1){

		echo "1";

		}else{

		$n = 0;

		while ($n < $pages) {
			$n++;
			if($n == $page){
			echo $n." |";
			} else {
			echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$n."\">".$n."</a> |";
			}
		}

		if($page <> $pages){
		$pageplus = $page + 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageplus."\">&gt;&gt;</a>";
		}
		}
	?>

        </div>
    </div>


<form id="friend-list-form">
    <table id="friend-list-table" border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr class="friend-list-header">
                <td class="friend-select" />
                <td class="friend-name table-heading">Name</td>
                <td class="friend-login table-heading">Last login</td>
                <td class="friend-remove table-heading">Remove</td>
            </tr>
           <?php
		   $i = 0;
		   $offset = $pagesize * $page;
		   $offset = $offset - $pagesize;
		   $getem = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$my_id."' OR friendid = '".$my_id."' LIMIT ".$pagesize." OFFSET ".$offset."") or die(mysql_error());

		   while ($row = mysql_fetch_assoc($getem)) {
		           $i++;

		           if(IsEven($i)){
		               $even = "odd";
		           } else {
		               $even = "even";
		           }

		           if($row['friendid'] == $my_id){
		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['userid']."'");
		           } else {
		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['friendid']."'");
		           }

		           $friendrow = mysql_fetch_assoc($friendsql);



printf("   <tr class=\"%s\">
               <td><input type=\"checkbox\" name=\"friendList[]\" value=\"%s\" /></td>
               <td class=\"friend-name\">
                %s
               </td>
               <td class=\"friend-login\" title=\"%s\">%s</td>
               <td class=\"friend-remove\"><div id=\"remove-friend-button-%s\" class=\"friendmanagement-small-icons friendmanagement-remove remove-friend\"></div></td>
           </tr>", $even, $friendrow['id'], $friendrow['name'], $friendrow['lastvisit'], $friendrow['lastvisit'], $friendrow['id']);
		   }
		?>
        </tbody>
    </table>
    <a class="select-all" id="friends-select-all" href="#">Select all</a> |
    <a class="deselect-all" href=#" id="friends-deselect-all">Deselect all</a>
</form>                        </div>
    <script type="text/javascript">
        new FriendManagement({ currentCategoryId: 0, pageListLimit: <?php echo $pagesize; ?>, pageNumber: <?php echo $page; ?>});
    </script>



<?php } elseif(isset($_POST['searchString'])){
$search = $_POST['searchString'];
$pagesize = $_POST['pageSize'];
$page = 1;
?>
                        <div id="friend-list" class="clearfix">
<div id="friend-list-header-container" class="clearfix">
    <div id="friend-list-header">
        <div class="page-limit">
            <div class="big-icons friend-header-icon">Friends
                <br />Show

           		<?php if($pagesize == 30){ ?>
                30 |
                <a class="category-limit" id="pagelimit-50">50</a> |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 50){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				50 |
                <a class="category-limit" id="pagelimit-100">100</a>
                <?php }elseif($pagesize == 100){ ?>
                <a class="category-limit" id="pagelimit-30">30</a> |
				<a class="category-limit" id="pagelimit-50">50</a> |
                100
                <?php } ?>
            </div>
        </div>
    </div>
	<div id="friend-list-paging">
	<?php
		if($page <> 1){
		$pageminus = $page - 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageminus."\">&lt;&lt;</a> |";
		}
		$i = 0;
	    $list = 0;
		$sql = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$my_id."' OR friendid = '".$my_id."'") or die(mysql_error());
		while ($row = mysql_fetch_assoc($sql)) {
			   $i++;

			   if($row['friendid'] == 1){
					$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['userid']."' AND name LIKE '%".$search."%'");
			   } else {
					$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['friendid']."' AND name LIKE '%".$search."%'");
			   }
	   $list = $list + mysql_num_rows($friendsql);
		}

		$pages = ceil($list / $pagesize);

		if($pages == 1){

		echo "1";

		}else{

		$n = 0;

		while ($n < $pages) {
			$n++;
			if($n == $page){
			echo $n." |";
			} else {
			echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$n."\">".$n."</a> |";
			}
		}

		if($page <> $pages){
		$pageplus = $page + 1;
		echo "<a href=\"#\" class=\"friend-list-page\" id=\"page-".$pageplus."\">&gt;&gt;</a>";
		}
		}
	?>

        </div>
    </div>


<form id="friend-list-form">
    <table id="friend-list-table" border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr class="friend-list-header">
                <td class="friend-select" />
                <td class="friend-name table-heading">Name</td>
                <td class="friend-login table-heading">Last login</td>
                <td class="friend-remove table-heading">Remove</td>
            </tr>
           <?php
		   $i = 0;
		   $n = 0;
		   $getem = mysql_query("SELECT * FROM messenger_friendships WHERE userid = '".$my_id."' OR friendid = '".$my_id."'") or die(mysql_error());

		   while ($row = mysql_fetch_assoc($getem)) {
		           $i++;

		           if($row['friendid'] == $my_id){
		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['userid']."' AND name LIKE '%".$search."%'");
		           } else {
		           		$friendsql = mysql_query("SELECT * FROM users WHERE id = '".$row['friendid']."' AND name LIKE '%".$search."%'");
		           }

		           $friendrow = mysql_fetch_assoc($friendsql);

		           if(!empty($friendrow['name'])){

		           $n++;

				   if(IsEven($n)){
					   $even = "odd";
				   } else {
					   $even = "even";
		           }

printf("   <tr class=\"%s\">
               <td><input type=\"checkbox\" name=\"friendList[]\" value=\"%s\" /></td>
               <td class=\"friend-name\">
                %s
               </td>
               <td class=\"friend-login\" title=\"%s\">%s</td>
               <td class=\"friend-remove\"><div id=\"remove-friend-button-%s\" class=\"friendmanagement-small-icons friendmanagement-remove remove-friend\"></div></td>
           </tr>", $even, $friendrow['id'], $friendrow['name'], $friendrow['lastvisit'], $friendrow['lastvisit'], $friendrow['id']);
		   }
		   }
		?>
        </tbody>
    </table>
    <a class="select-all" id="friends-select-all" href="#">Select all</a> |
    <a class="deselect-all" href=#" id="friends-deselect-all">Deselect all</a>
</form>                        </div>
    <script type="text/javascript">
        new FriendManagement({ currentCategoryId: 0, pageListLimit: <?php echo $pagesize; ?>, pageNumber: <?php echo $page; ?>});
    </script>
<?php } ?>