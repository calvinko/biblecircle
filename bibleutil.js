/* 
 * The MIT License
 *
 * Copyright 2014 Calvin Ko.
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

// booknum start with 1
var biblebooklist = [
    ['Bible', 0],
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
    ['Titus' , 3],
    ['Philemon', 1],
    ['Hebrews' , 13],
    ['James' , 5],
    ['1 Peter', 5],
    ['2 Peter', 3],
    ['1 John' , 5],
    ['2 John' , 1],
    ['3 John' , 1], 
    ['Jude' , 1],
    ['Revelation', 22],
    ['end', 10]
];
            
function createSubMenu(c1, c2) {
    var ul = $("<ul style='height: 600px' class='submenu'>");
    var i;
    for (i=c1;i<=c2;i++) {
        var li = $("<li><a href='#'></a></li>");
        li.attr("bookid", i);
        li.attr("chapter", "1");
        li.find("a").text(biblebooklist[i][0]);

        ul.append(li);
    }
    return ul;
}

function createBibleIndexMenu(mdiv) {
    mdiv.append(createSubMenu(1,22));
    mdiv.append(createSubMenu(23,44));
    mdiv.append(createSubMenu(45,66));
}
             