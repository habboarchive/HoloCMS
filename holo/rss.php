<?php
include{'core.php')
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<rss xmlns:taxo=\"http://purl.org/rss/1.0/modules/taxonomy/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" version=\"2.0\">";
echo "
  <channel>
    <title>".$shortname." News</title>
    <link>".$path."</link>
    <description>The latest happenings on ".$shortname." direct to your news reader</description>";

$data = mysql_query("SELECT * FROM cms_news ORDER BY num DESC");
while($row = mysql_fetch_array($data))

echo "
    <item>
      <title>".$row[title]."</title>
      <link>".$path."news.php?id=".$row[num]."</link>
      <description>".$row[story]."</description>
      <pubDate>".$row[date]."</pubDate>
      <guid isPermaLink=\"true\">".$path."news.php?id=".$row[num]."</guid>
      <dc:date>".$row[date]."</dc:date>
    </item>";

echo "
</channel>
</rss>";
?>