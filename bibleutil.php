<?php

/* 
 * The MIT License
 *
 * Copyright 2014 ko.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once 'config.php';

$chinesebooknametbl = array(
    '創 世 記',
    '出 埃 及 記 ',
    '利 未 記',
    '民 數 記',
    '申 命 記',
    '約 書 亞 記',
    '士 師 記',
    '路 得 記',
    '撒 母 耳 記 上',
    '撒 母 耳 記 下',
    '列 王 記 上',
    '列 王 記 下',
    '歷 代 志 上',
    '歷 代 志 下',
    '以 斯 拉 記',
    '尼 希 米 記',
    '以 斯 帖 記', 
    '約 伯 記',
    '詩 篇',
    '箴 言', 
    '傳 道 書',
    '雅 歌',
    '以 賽 亞 書',
    '耶 利 米 書',
    '耶 利 米 哀 歌',
    '以 西 結 書',
    '但 以 理 書',
    '何 西 阿 書',
    '約 珥 書',
    '阿 摩 司 書',
    '俄 巴 底 亞 書',
    '約 拿 書',
    '彌 迦 書',
    '那 鴻 書',
    '哈 巴 谷 書',
    '西 番 雅 書',
    '哈 該 書',
    '撒 迦 利 亞',
    '瑪 拉 基 書'	,
    '馬 太 福 音',
    '馬 可 福 音',
    '路 加 福 音',
    '約 翰 福 音',
    '使 徒 行 傳',
    '羅 馬 書',
    '哥 林 多 前 書',
    '哥 林 多 後 書',
    '加 拉 太 書',
    '以 弗 所 書',
    '腓 立 比 書',
    '歌 羅 西 書',
    '帖 撒 羅 尼 迦 前 書',
    '帖 撒 羅 尼 迦 後 書',
    '提 摩 太 前 書',
    '提 摩 太 後 書',
    '提 多 書',
    '腓 利 門 書',
    '希 伯 來 書',
    '雅 各 書',
    '彼 得 前 書',
    '彼 得 後 書',
    '約 翰 一 書',
    '約 翰 二 書',
    '約 翰 三 書',
    '猶 大 書',
    '啟 示 錄'		
);

$biblebookinfo = [
    ["Genesis", 50],
    ["Exodus", 40],
    ["Leviticus", 27],
    ["Numbers", 36],
    ["Deuteronomy", 34],
    ["Joshua", 24],
    ["Judges", 21],
    ["Ruth", 4],
    ["1 Samuel", 31],
    ["2 Samuel", 24],
    ["1 Kings", 22],
    ["2 Kings", 25],
    ["1 Chronicles", 29],
    ["2 Chronicles", 36],
    ["Ezra", 10],
    ["Nehemiah", 13],
    ["Esther", 10],
    ["Jobs", 42],
    ["Psalms", 150],
    ["Proverbs", 31],
    ["Ecclesistes", 12],
    ['Song of Songs', 8],
    ['Isaiah', 66],
    ['Jeremiah' , 52],
    ['Lamentations', 5],
    ['Ezekiel' , 48],
    ['Daniel', 12], 
    ['Hosea' , 14],
    ['Joel', 3],
    ['Amos', 9],
    ['Obadiah', 1],
    ['Jonah', 4],
    ['Micah' , 7],
    ['Nahum' , 3],
    ['Habakkuk', 3],
    ['Zephaniah', 3],
    ['Haggai', 2],
    ['Zechariah', 14],
    ['Malachi', 4],
    ['Matthew', 28],
    ['Mark', 16],
    ['Luke', 24],
    ['John', 21],
    ['Acts', 28],
    ['Romans', 16] ,
    ['1 Corinthians' , 16],
    ['2 Corinthians' , 13],
    ['Galatians' , 6],
    ['Ephesians' , 6],
    ['Philippians' , 4],
    ['Colossians' , 4],
    ['1 Thessalonians', 5],
    ['2 Thessalonians' , 3],
    ['1 Timothy' , 6],
    ['2 Timothy' , 4],
    ['Titus' , 3, 0],
    ['Philemon', 1, 3],
    ['Hebrews' , 13, 4],
    ['James' , 5, 17],
    ['1 Peter', 5, 22],
    ['2 Peter', 3, 27],
    ['1 John' , 5, 30],
    ['2 John' , 1, 35],
    ['3 John' , 1, 36], 
    ['Jude' , 1, 37],
    ['Revelation', 22, 38],
];

$booktbl = array(
    'Genesis' => 1 ,
    'Exodus' => 2 ,
    'Leviticus' => 3 ,
    'Numbers' => 4 ,
    'Deuteronomy' => 5 ,
    'Joshua' => 6 ,
    'Judges' => 7 ,
    'Ruth' => 8 ,
    '1 Samuel' => 9 ,
    '2 Samuel' => 10 ,
    '1 Kings' => 11 ,
    '2 Kings' => 12 ,
    '1 Chronicles' => 13 ,
    '2 Chronicles' => 14 ,
    'Ezra' => 15 ,
    'Nehemiah' => 16 ,
    'Esther' => 17 ,
    'Job' => 18 ,
    'Psalms' => 19 ,
    'Proverbs' => 20 ,
    'Ecclesiastes' => 21 ,
    'Song of Solomon' => 22 ,
    'Isaiah' => 23 ,
    'Jeremiah' => 24 ,
    'Lamentations' => 25 ,
    'Ezekiel' => 26 ,
    'Daniel' => 27 ,
    'Hosea' => 28 ,
    'Joel' => 29 ,
    'Amos' => 30 ,
    'Obadiah' => 31 ,
    'Jonah' => 32 ,
    'Micah' => 33 ,
    'Nahum' => 34 ,
    'Habakkuk' => 35 ,
    'Zephaniah' => 36 ,
    'Haggai' => 37 ,
    'Zechariah' => 38 ,
    'Malachi' => 39 ,
    'Matthew' => 40 ,
    'Mark' => 41 ,
    'Luke' => 42 ,
    'John' => 43 ,
    'Acts' => 44 ,
    'Romans' => 45 ,
    '1 Corinthians' => 46 ,
    '2 Corinthians' => 47 ,
    'Galatians' => 48 ,
    'Ephesians' => 49 ,
    'Philippians' => 50 ,
    'Colossians' => 51 ,
    '1 Thessalonians' => 52 ,
    '2 Thessalonians' => 53 ,
    '1 Timothy' => 54 ,
    '2 Timothy' => 55 ,
    'Titus' => 56 ,
    'Philemon' => 57 ,
    'Hebrews' => 58 ,
    'James' => 59 ,
    '1 Peter' => 60 ,
    '2 Peter' => 61 ,
    '1 John' => 62 ,
    '2 John' => 63 ,
    '3 John' => 64 ,
    'Jude' => 65 ,
    'Revelation' => 66
);

$booknametbl = array_keys($booktbl);

$bktbl = array (
    'gen' => 1 ,
    'exo' => 2 ,
    'lev' => 3 ,
    'num' => 4 ,
    'deu' => 5 ,
    'jos' => 6 ,
    'jud' => 7 ,
    'rut' => 8 ,
    '1sa' => 9 ,
    '2sa' => 10 ,
    '1ki' => 11 ,
    '2ki' => 12 ,
    '1ch' => 13 ,
    '2ch' => 14 ,
    'ezr' => 15 ,
    'neh' => 16 ,
    'est' => 17 ,
    'job' => 18 ,
    'psa' => 19 ,
    'pro' => 20 ,
    'ecc' => 21 ,
    'son' => 22 ,
    'isa' => 23 ,
    'jer' => 24 ,
    'lam' => 25 ,
    'eze' => 26 ,
    'dan' => 27 ,
    'hos' => 28 ,
    'joe' => 29 ,
    'amo' => 30 ,
    'oba' => 31 ,
    'jon' => 32 ,
    'mic' => 33 ,
    'nah' => 34 ,
    'hab' => 35 ,
    'zep' => 36 ,
    'hag' => 37 ,
    'zec' => 38 ,
    'mal' => 39 ,
    'mat' => 40 ,
    'mar' => 41 ,
    'luk' => 42 ,
    'joh' => 43 ,
    'act' => 44 ,
    'rom' => 45 ,
    '1co' => 46 ,
    '2co' => 47 ,
    'gal' => 48 ,
    'eph' => 49 ,
    'phi' => 50 ,
    'col' => 51 ,
    '1th' => 52 ,
    '2th' => 53 ,
    '1ti' => 54 ,
    '2ti' => 55 ,
    'tit' => 56 ,
    'phm' => 57 ,
    'heb' => 58 ,
    'jam' => 59 ,
    '1pe' => 60 ,
    '2pe' => 61 ,
    '1jo' => 62 ,
    '2jo' => 63 ,
    '3jo' => 64 ,
    'jud' => 65 ,
    'rev' => 66 ,
);

$bkbasetbl = array(
    
);

function getTextESV($passage="John+1")
{
    $passage = urlencode($passage);
    $baseurl = "http://www.esvapi.org/v2/rest/passageQuery?key=IP&passage=$passage";
    $options = "&include-footnotes=false&include-audio-link=false";
    $ch = curl_init();
    $url = $baseurl . $options;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// New English Translation
function getTextNET($passage="John+1")
{
    $passage = urlencode($passage);
    $baseurl = "http://labs.bible.org/api/?passage=$passage";
    $options = "";
    $ch = curl_init();
    $url = $baseurl . $options;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


function getBookNumber($bookname) {
    global $bktbl;
    $bkname = substr($bookname, 0, 3);
    return $bktbl[$bkname];
}


function getBookName($booknum, $version)
        
{
    global $booknametbl, $chinesebooknametbl;
    if ($booknum >= 1 && $booknum <=66) {
        if ($version == "HB5" || $version == "CUV") {
            $bname = $chinesebooknametbl[$booknum-1];
        } else {
            $bname = ucfirst($booknametbl[$booknum-1]);
        }
        return $bname;
    } else {
        return "Invalid";    
    }
}

function parseBookName($book, $version) 
{
    if (is_numeric($book)) {
        $booknum = $book + 0;
        if (!is_int($booknum) || $booknum < 1 || $booknum > 66) {
            $booknum = 1; 
        }
        return getBookName($booknum, $version);   
        
    } else {
        $booknum = getBookNumber($book);
        return getBookName($booknum, $version);
    }
}

function parsePassagePhrase($passage)
{
    $passage = strtolower($passage);
    if ($passage[0] == '1' or $passage[0] == '2') {
        $tok = preg_split("/[\s]+/", $passage);
        $tok[0] = $tok[0] . $tok[1];
        $tok[1] = $tok[2];
    } else {
        $tok = preg_split("/[\s]+/", $passage);
    }
    $bname = getBookNumber($tok[0]);
    if ($bname != 0) {
        return array($bname,$tok[1]);
    } else {
        return array(1, 1);
    }
}

function getChapterText($book, $chapter, $version) {
    global $mysqli;
    if (is_numeric($book)) {
        $booknum = $book + 0;
        if (!is_int($booknum) || $booknum < 1 || $booknum > 66) {
            $booknum = 1; 
        }
    } else {
        $booknum = getBookNumber($book);
    }
    if (strtoupper($version) == 'CUV') {
        $texttbl = 'hb5text';
    } else {
        $texttbl = 'kjvtext';
    }
    $result = $mysqli->query("SELECT book,chapter,verse,text FROM $texttbl WHERE book=$booknum and chapter=$chapter ORDER BY chapter, verse");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
            $data = array(); 
    }
    return $data;
}

class BibleLogMgr {
    
    public $mysqli;
    public $errmsg;
    
    public function __construct() {
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $this;
    }
    
    // pack the reading log from array(bookid, array of bool status) into 
    public function packLog($rlogbook) {
        
    }
    
    // expand the reading log 
    public function unpackLog($prlog) {
        
    }
    
    public function convertToLoc($booknum, $chnum) {
        if ($booknum == 19)  {
            if ($chnum <= 60) {
                return [19, $chnum];
            } else if ($chnum <=120 ) {
                return [67, $chnum-60];
            } else {
                return [68, $chnum-120];
            }
        } else if ($booknum == 23 && $chnum > 60) {
            return [69, $chnum-60];
        } else {
            return [$booknum, $chnum];
        }
    }
    
    public function convertFrom($blknum, $section) {
        
    }
    
    public function markChapterDone($instid, $booknum, $chnum) {
        
        $loc = convertToLoc($booknum, $chnum);
        $blk = $loc[0];
        $section = $loc[1];
        $newval = (1 << $section);
        $result = $this->mysqli->query("UPDATE biblerlog set data = data | $newval WHERE instid = $instid and blknum = $blk");
        return $result;
    }
    
    public function unmarkChapterDone($instid, $booknum, $chnum) {
        $loc = convertToLoc($booknum, $chnum);
        $blk = $loc[0];
        $section = $loc[1];
        $newval = ~ (1 << $section);
        $result = $this->mysqli->query("UPDATE biblerlog set data = data & $newval WHERE instid = $instid and blknum = $blk");
        return $result;
    }
    
    public function getChapterStatus($instid, $booknum, $chnum) {
        $loc = convert($booknum, $chnum);
        $blk = $loc[0];
        $section = $loc[1];
        $result = $this->mysqli->query("SELECT data from biblerlog WHERE instid=$instid and blknum=$blk");
        if ($result) {
            $row = $result->fetch_row();
            $mask = (1 << $section);
            return $row & $mask;
        } else {
            return 0;
        }
    }
    
    // 8 byte integer to 64 bit array 
    public function unpackLogData($data, $size) {
        $mask = 1;
        for ($i=0; $i<$size; $i++) {
            $ret[$i] = ($data & $mask) != 0 ? 1 : 0;
            $mask = $mask << 1;
        } 
        return $ret;
    }
    
    public function getAllChapterStatus($instid) {
        global $biblebookinfo;
        $result = $this->mysqli->query("SELECT blknum,data from biblerlog WHERE instid=$instid ORDER BY Blknum ");
        if ($result) {
            while ($row = $result->fetch_row()) {
                $bnum = $row[0];
                if ($bnum < 67) {
                    $nchp = $biblebookinfo[$bnum][1];
                    $statustbl[$bnum] = $this->unpackLogData($row[1], $nchp);
                }
            }
            return $statustbl;
        } else {
            throw new Exception("no result");
        }
    }
    
    public function newReadingPlan() {
  
        if (!($stmt = $this->mysqli->prepare("INSERT INTO biblerlog (instid, blknum) VALUES (?, ?)"))) {
            $this->errmsg = "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
            echo $this->errmsg;
            return 0;
        }
        
        $instid = 7; 
        for ($i=1;$i<=69;$i++) {
            $stmt->bind_param("ii", $instid, $i);
            $stmt->execute();              
        }
    }
}

// function handling the REST /bible api 
// /bible/{BOOK}/{chapter}?version=XX 
// 
function bibleapi($elm, $vars, $ret)  {
    global $mysqli;
    
    $result = $mysqli->query("SELECT chapter,verse,text FROM $texttbl WHERE book=$book and chapter>=$lrange and chapter <=$urange ORDER BY chapter, verse");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
            $data = "Error" . mysql_error();     
    }
} 


$logmgr = new BibleLogMgr();

$logmgr->newReadingPlan();