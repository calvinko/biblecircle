<?php 

require_once "bcutil.php";
require_once "bibleutil.php";

initmysqli();
$logmgr = new BibleLogMgr();
$logmgr->newReadingPlanForUser(1001, "bible");
echo "<html>Done</html>";

