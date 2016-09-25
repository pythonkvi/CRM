<?

class SimpleImage
{

    var $image;
    var $image_type;

    function load($filename)
    {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG )
        {
            $this->image = imagecreatefromjpeg($filename);
        }
        elseif( $this->image_type == IMAGETYPE_GIF )
        {
            $this->image = imagecreatefromgif($filename);
        }
        elseif( $this->image_type == IMAGETYPE_PNG )
        {
            $this->image = imagecreatefrompng($filename);
        }
    }
    function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null)
    {
        if( $image_type == IMAGETYPE_JPEG )
        {
            imagejpeg($this->image,$filename,$compression);
        }
        elseif( $image_type == IMAGETYPE_GIF )
        {
            imagegif($this->image,$filename);
        }
        elseif( $image_type == IMAGETYPE_PNG )
        {
            imagepng($this->image,$filename);
        }
        if( $permissions != null)
        {
            chmod($filename,$permissions);
        }
    }
    function output($image_type=IMAGETYPE_JPEG)
    {
        if( $image_type == IMAGETYPE_JPEG )
        {
            imagejpeg($this->image);
        }
        elseif( $image_type == IMAGETYPE_GIF )
        {
            imagegif($this->image);
        }
        elseif( $image_type == IMAGETYPE_PNG )
        {
            imagepng($this->image);
        }
    }
    function getWidth()
    {

        return imagesx($this->image);
    }
    function getHeight()
    {

        return imagesy($this->image);
    }
    function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }

    function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }

    function scale($scale)
    {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }

    function resize($width,$height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

}

class MessageProcess
{
    function __construct($db)
    {
        $this->db = $db;
    }

    function createMessage($subject, $body, $date, $owner)
    {
        $body = mb_ereg_replace("<HTML><BODY>", "", $body);
        $body = mb_ereg_replace("</BODY></HTML>", "", $body);
        $body = mb_ereg_replace("'", "''", $body);

        //var_dump($body);
        $this->db->exec("insert into news (header, body, newsdate, owner) values ('" .$subject. "','" .$body. "','".$date->format("Y-m-d"). "', '".mb_ereg_replace("'", "''", $owner)."') ");
        $this->news_id = $this->db->lastInsertId();
    }

    function createAttachment($data)
    {
        $file = tempnam("/tmp/", "image");
        $fp = fopen ($file, "w");
        fputs ($fp, $data);
        fclose ($fp);

        require_once('../yandex.php');
        $upl_imgs = uploadPostImage($file);
        // 1 is big image,  0 is small
        $link_id = md5($upl_imgs[0]);
        $link_text = $upl_imgs[0];
        $link2_id = md5($upl_imgs[1]);
        $link2_text = $upl_imgs[1];

        $this->db->exec("insert into link (id, link_text) values ('" .$link_id. "','" .mb_ereg_replace("'", "''", $link_text)."') ");
        $this->db->exec("insert into link (id, link_text) values ('" .$link2_id. "','" .mb_ereg_replace("'", "''", $link2_text)."') ");
        $this->db->exec("insert into news_attachment (news_id, link_id, link2_id) values ('" .$this->news_id. "','" .$link2_id."','" .$link_id."') ");
        unlink($file);
    }
}



?>
