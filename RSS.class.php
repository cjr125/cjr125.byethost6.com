<?

class RSS
{
    public function RSS()
    {
        require_once ('pathto.../mysql_connect.php');
    }

    public function GetFeed()
    {
        return $this->getDetails() . $this->getItems();
    }

    private function dbConnect()
    {
        DEFINE ('LINK', mysql_connect (DB_HOST, DB_USER, DB_PASSWORD));
    }

    private function getDetails()
    {
        $detailsTable = "webref_rss_details";
        $this->dbConnect($detailsTable);
        $query = "SELECT * FROM ". $detailsTable;
        $result = mysql_db_query (DB_NAME, $query, LINK);

        while($row = mysql_fetch_array($result))
        {
            $details = '<?xml version="1.0" encoding="ISO-8859-1" ?>
            <rss version="2.0">
                <channel>
                    <title>'. $row['title'] .'</title>
                    <link>'. $row['link'] .'</link>
                    <description>'. $row['description'] .'</description>
                    <language>'. $row['language'] .'</language>
                    <image>
                    <title>'. $row['image_title'] .'</title>
                    <url>'. $row['image_url'] .'</url>
                    <link>'. $row['image_link'] .'</link>
                    <width>'. $row['image_width'] .'</width>
                    <height>'. $row['image_height'] .'</height>
                    </image>';
        }
        return $details;
    }

    private function getItems()
    {
        $itemsTable = "webref_rss_items";
        $this->dbConnect($itemsTable);
        $query = "SELECT * FROM ". $itemsTable;
        $result = mysql_db_query (DB_NAME, $query, LINK);
        $items = '';
        while($row = mysql_fetch_array($result))
        {
            $items .= '<item>
                <title>'. $row["title"] .'</title>
                <link>'. $row["link"] .'</link>
                <description><![CDATA['. $row["description"] .']]></description>
            </item>';
        }
        $items .= '</channel>
        </rss>';
        return $items;
    }

}

?>
