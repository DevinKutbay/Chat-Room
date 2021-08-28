<?php
$chat_dir = "chat";
if(!file_exists($chat_dir)) mkdir($chat_dir);

$default = "default-room.log";
if(!file_exists("$chat_dir/$default")) file_put_contents("$chat_dir/$default", "");

$test = "test-room.log";
if(!file_exists("$chat_dir/$test")) file_put_contents("$chat_dir/$test", "");

switch($_POST["action"]) {
    case "send":
        $chatfile = "$chat_dir/" . $_POST["room"] . ".log";
        writeChat($chatfile);
        echo chatLog($chatfile);
        break;
    
    case "poll":
        $chatfile = "$chat_dir/" . $_POST["room"] . ".log";
        echo chatLog($chatfile);
        break;
        
    case "room":
        echo getRoom($chat_dir);
        break;     
}

function writeChat($f) {
    $format = "<p>%s&nbsp;<span style=font-weight:bold>| %s:
	<br></span>&nbsp;%s
	<br></p>";
	$_POST["name"] = htmlspecialchars($_POST["name"]);
	$_POST["msg"] = htmlspecialchars($_POST["msg"]);
    $str = sprintf($format, date("D,M,o, H:i T"), $_POST["name"], $_POST["msg"]);
    file_put_contents($f, "$str\n", FILE_APPEND | LOCK_EX);
}

function chatLog($f) {
    if(file_exists($f)) $log = file_get_contents($f); else $log = "";
    return $log;
}

function getRoom($dir) {    
    $ffs = preg_grep('/^([^.])/', scandir($dir));
    $ffs = array_values($ffs);
    foreach($ffs as $key=>$ff) {
        $ffs[$key] = explode(".", $ff)[0];
    }
	
    if(isset($_POST['new'])) {
        $new = $_POST['new'];
        file_put_contents("$dir/$new" . ".log", "");
        $ffs = array_merge([ $new ], $ffs);
    }
    return json_encode($ffs);    
}
?>