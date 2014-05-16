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

$booktbl = array(
    'genesis' => 1 ,
    'exodus' => 2 ,
    'leviticus' => 3 ,
    'numbers' => 4 ,
    'deuteronomy' => 5 ,
    'joshua' => 6 ,
    'judges' => 7 ,
    'ruth' => 8 ,
    '1samuel' => 9 ,
    '2samuel' => 10 ,
    '1kings' => 11 ,
    '2kings' => 12 ,
    '1 chronicles' => 13 ,
    '2 chronicles' => 14 ,
    'ezra' => 15 ,
    'nehemiah' => 16 ,
    'esther' => 17 ,
    'job' => 18 ,
    'psalms' => 19 ,
    'proverbs' => 20 ,
    'ecclesiastes' => 21 ,
    'song of songs' => 22 ,
    'isaiah' => 23 ,
    'jeremiah' => 24 ,
    'lamentations' => 25 ,
    'ezekiel' => 26 ,
    'daniel' => 27 ,
    'hosea' => 28 ,
    'joel' => 29 ,
    'amos' => 30 ,
    'obadiah' => 31 ,
    'jonah' => 32 ,
    'micah' => 33 ,
    'nahum' => 34 ,
    'habakkuk' => 35 ,
    'zephaniah' => 36 ,
    'haggai' => 37 ,
    'zechariah' => 38 ,
    'malachi' => 39 ,
    'matthew' => 40 ,
    'mark' => 41 ,
    'luke' => 42 ,
    'john' => 43 ,
    'acts' => 44 ,
    'romans' => 45 ,
    '1corinthians' => 46 ,
    '2corinthians' => 47 ,
    'galatians' => 48 ,
    'ephesians' => 49 ,
    'philippians' => 50 ,
    'colossians' => 51 ,
    '1thessalonians' => 52 ,
    '2thessalonians' => 53 ,
    '1timothy' => 54 ,
    '2timothy' => 55 ,
    'titus' => 56 ,
    'philemon' => 57 ,
    'hebrews' => 58 ,
    'james' => 59 ,
    '1peter' => 60 ,
    '2peter' => 61 ,
    '1john' => 62 ,
    '2john' => 63 ,
    '3john' => 64 ,
    'jude' => 65 ,
    'revelation' => 66
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