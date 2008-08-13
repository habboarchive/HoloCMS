<?php
include('../core.php');


if(isset($_POST['searchString'])) {
$page = $_POST['pageNumber'];
$search = stripslashes($_POST['searchString']);
$sql = mysql_query("SELECT name,figure,id,lastvisit FROM users WHERE name LIKE '%$search%' ORDER BY name ASC");
$count = mysql_num_rows($sql);
$pages = ceil($count / 10);
if($page == null){ $page = 1; }
/*
$realpage = ($count - 10);
$ceil = ceil($realpage);
$ceil = ($ceil - 1);
if($page != 0) {
$limit2 = ($page * 10);
$limit = ($limit2 - 10);
}else{
$limit2 = "10";
$limit = ($limit2 - 10);
}

		$calc = $ceil;
		if($calc < 0) {
		$sql = mysql_query("SELECT id FROM users LIMIT 1");
		}else{
		$sql = mysql_query("SELECT id FROM users LIMIT $calc");
		}
		while($row = mysql_fetch_assoc($sql)) {
$page = $_POST['pageNumber'];		?>
*/
$limit = 10;
$offset = $page - 1;
$offset = $offset * 10;
$sql = mysql_query("SELECT name,figure,id,lastvisit FROM users WHERE name LIKE '%$search%' ORDER BY name ASC LIMIT $limit OFFSET $offset");
if(mysql_num_rows($sql) > 0) {
echo '<ul class="habblet-list">';
while($row = mysql_fetch_assoc($sql)) {
		        $i++;

        if(IsEven($i)){
            $even = "odd";
        } else {
            $even = "even";
        } ?>

              <li class="<?php echo $even; ?> offline" homeurl="user_profile.php?tag=<?php echo stripslashes($row['name']); ?>" style="background-image: url(http://www.habbo.co.uk/habbo-imaging/avatarimage?figure=<?php echo $row['figure']; ?>&direction=2&head_direction=2&gesture=sml&size=s)">
	            	    <div class="item">
	            		    <b><?php echo stripslashes(htmlspecialchars($row['name'])); ?></b><br />

	            	    </div>
	            	    <div class="lastlogin">
	            	    	<b>Last visit</b><br />
	            	    		<span title="<?php echo $row['lastvisit']; ?>"><?php echo $row['lastvisit']; ?></span>
	            	    </div>
	            	    <div class="tools">
	            	    		<a href="#" class="add" avatarid="<?php echo $row['id']; ?>" title="Send friend request"></a>
	            	    </div>
	            	    <div class="clear"></div>
	                </li>

<?php			} ?>
							    <div id="habblet-paging-avatar-habblet-list-container">
        <p id="avatar-habblet-list-container-list-paging" class="paging-navigation">
		            	 <?php if($page > 1) { ?><a href="#" class="avatar-habblet-list-container-list-paging-link" id="avatar-habblet-list-container-list-previous">&laquo;</a><?php } else { ?><span class="disabled">&laquo;</span><?php } ?>
		<?php           
		$i = 0;
		$n = $pages;
		while ($i <> $n){
			$i++;
			if ($i < $page + 8){
				if($i == $page){ echo "<span class=\"current\">".$i."</span>\n";
				} else {
					if ($i + 4 >= $page && $page + 4 >= $i){
						echo "<a href=\"#\" class=\"avatar-habblet-list-container-list-paging-link\" id=\"avatar-habblet-list-container-list-page-".$i."\">".$i."</a>\n";
					}
				}
			}
		}
		?>
		<?php if($page < $pages) { ?><a href="#" class="avatar-habblet-list-container-list-paging-link" id="avatar-habblet-list-container-list-next">&raquo;</a><?php }else{ ?><span class="disabled">&raquo;</span><?php } ?>
			        </p>
        <input type="hidden" id="avatar-habblet-list-container-pageNumber" value="<?php echo $page; ?>"/>
        <input type="hidden" id="avatar-habblet-list-container-totalPages" value="<?php echo $pages; ?>"/>
    </div>
				<?php
			}else{
			echo "<div class=\"box-content\">
                ".$shortname." not found. Please make sure you have typed his or her name correctly and try again. <br>
       </div>";
		}
}

?>