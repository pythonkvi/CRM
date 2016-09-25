<?php

require_once('newsmanage.php');

class ImapProcessor
{

    function __construct()
    {
        $this->connection = imap_open("{imap.yandex.ru:993/imap/ssl}INBOX","news@ivalera.ru", "12qwaszx" );
        $this->db = new PDO('sqlite:../site.db');

        $this->messageCount = imap_num_msg($this->connection);
        var_dump("Found ".$this->messageCount." messages");
    }

    function readMessages()
    {
        for( $MID = 1; $MID <= $this->messageCount; $MID++ )
        {
            $m = new ImapMessage($this->connection);
            $m->readMessage($MID);
            $mp = new MessageProcess($this->db);
            $mp->createMessage($m->getSubject(), $m->getMessage(), $m->date, $m->owner);

            foreach($m->attachments as $att)
            {
                $mp->createAttachment($att);
            }

            imap_delete($this->connection, $MID);
        }
        imap_expunge($this->connection );

    }

    function __destruct()
    {
        imap_close ($this->connection );
    }

}

class ImapMessage
{
    function clear()
    {
        $this->htmlmsg = $this->plainmsg = $this->charset = '';
        $this->attachments = array();
    }

    function getSubject()
    {
        return mb_convert_encoding($this->subject, "UTF-8", $this->charset);
    }

    function getMessage()
    {
        return mb_convert_encoding(mb_strlen($this->htmlmsg) > 0 ? $this->htmlmsg : mb_ereg_replace("\n", "<BR/>", $this->plainmsg), "UTF-8", $this->charset);
    }

    function getmsg($mbox,$mid)
    {
        // HEADER
        $h = imap_header($mbox,$mid);
        // add code here to get date, from, to, cc, subject...

        // BODY
        $s = imap_fetchstructure($mbox,$mid);
        if (!$s->parts)  // simple
            $this->getpart($mbox,$mid,$s,0);  // pass 0 as part-number
        else    // multipart: cycle through each part
        {
            foreach ($s->parts as $partno0=>$p)
            $this->getpart($mbox,$mid,$p,$partno0+1);
        }
    }

    function getpart($mbox,$mid,$p,$partno)
    {
        // DECODE DATA
        $data = ($partno)?
                imap_fetchbody($mbox,$mid,$partno):  // multipart
                imap_body($mbox,$mid);  // simple
        // Any part may be encoded, even plain text messages, so check everything.
        if ($p->encoding==4)
            $data = quoted_printable_decode($data);
        elseif ($p->encoding==3)
        $data = base64_decode($data);

        // PARAMETERS
        // get all parameters, like charset, filenames of attachments, etc.
        $params = array();
        if ($p->parameters)
            foreach ($p->parameters as $x)
            $params[strtolower($x->attribute)] = $x->value;
        if ($p->dparameters)
            foreach ($p->dparameters as $x)
            $params[strtolower($x->attribute)] = $x->value;

        // ATTACHMENT
        // Any part with a filename is an attachment,
        // so an attached text file (type 0) is not mistaken as the message.
        if ($params['filename'] || $params['name'])
        {
            // filename may be given as 'Filename' or 'Name' or both
            $filename = ($params['filename'])? $params['filename'] : $params['name'];
            // filename may be encoded, so see imap_mime_header_decode()
            $this->attachments[$filename] = $data;  // this is a problem if two files have same name
        }

        // TEXT
        if ($p->type==0 && $data)
        {
            // Messages may be split in different parts because of inline attachments,
            // so append parts together with blank row.
            if (strtolower($p->subtype)=='plain')
                $this->plainmsg.= trim($data) ."\n\n";
            else
                $this->htmlmsg.= $data ."<br><br>";
            $this->charset = $params['charset'];  // assume all parts are same charset
        }

        // EMBEDDED MESSAGE
        // Many bounce notifications embed the original message as type 2,
        // but AOL uses type 1 (multipart), which is not handled here.
        // There are no PHP functions to parse embedded messages,
        // so this just appends the raw source to the main message.
        elseif ($p->type==2 && $data)
        {
            $this->plainmsg.= $data."\n\n";
        }

        // SUBPART RECURSION
        if ($p->parts)
        {
            foreach ($p->parts as $partno0=>$p2)
            $this->getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
        }
    }

    function __construct($connection)
    {
        $this->connection = $connection;
        $this->clear();
        date_default_timezone_set('Asia/Dubai');
    }

    function readMessage($n)
    {
        $this->getmsg($this->connection,$n);

        $headers = imap_headerinfo($this->connection, $n);
        $this->subject = iconv_mime_decode($headers->subject, 0, $this->charset);
        $this->date = new DateTime($headers->date);
        $this->owner = iconv_mime_decode($headers->fromaddress, 0, $this->charset);

    }
}

$ip = new ImapProcessor();
$ip->readMessages();
?>
