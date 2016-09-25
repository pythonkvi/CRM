<?php
require_once('authorized.php');

if (isset($_REQUEST['link_id']) && isset($_REQUEST['operation']))
{
    if (isset($_REQUEST['image_mark']))
    {
        $mark = json_decode($_REQUEST['image_mark']);
        if ($_REQUEST['operation'] === "add") 
        {
            $db->exec("insert into link_mark (link_id, x1, x2, y1, y2, width, height, mark) values ('" .$_REQUEST['link_id']. "','".$mark->x1."','".$mark->x2."','".$mark->y1."','".$mark->y2."','".$mark->width."','".$mark->height."','" .mb_ereg_replace("'", "''", $mark->text)."')") || die(json_encode($db->errorInfo(), true));
        }
        if ($_REQUEST['operation'] === "delete") 
        {
            $db->exec("delete from link_mark where link_id = '".$_REQUEST['link_id']."' and id='".$mark->id."'") || die(json_encode($db->errorInfo(), true));
        }
    }
    if ($_REQUEST['operation'] === "list")
    {
        ($result = $db->query("select id, x1, x2, y1, y2, mark from link_mark where link_id = '".$_REQUEST['link_id']."'")) || die(json_encode($db->errorInfo(), true));

        if ($result)
        {
            print json_encode($result->fetchAll(PDO::FETCH_ASSOC));
        }
        else
        {
            print json_encode(array());
        }
    }
}

?>
