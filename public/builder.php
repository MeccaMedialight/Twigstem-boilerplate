<?php

class Builder
{

    static $LOREM;
    static $template;
    static $COUNTERS;

    static function render($root = 'root', $max = 12, $tpl = 'id | Category | Title:Sentence | Image:Image,900,600 | Snippet | Published:Date | Tags | Views:number,1000,30000 | Comments:number,1,10')
    {
        $tpl = explode('|', $tpl);
        self::$template = [];
        foreach ($tpl as $col) {
            $col = trim($col);
            $bits = explode(':', $col);
            if (count($bits) == 1) {
                $k = strtolower($col);
                self::$template[$k] = $col;
            } elseif (count($bits) == 2) {
                $k = strtolower(trim($bits[0]));
                $v = explode(',', $bits[1]);
                if (count($v) == 1) {
                    self::$template[$k] = trim($bits[1]);
                } else {
                    self::$template[$k] = ($v);
                }

            }
        }

        $out = self::createArr($max, function () {
            $node = [];
            foreach (self::$template as $key => $mthod) {
                if (is_array($mthod)) {
                    $m = array_shift($mthod);
                    $node[$key] = self::getMockery($m, $mthod);
                } else {
                    $node[$key] = self::getMockery($mthod);
                }

            }
            return $node;

        });

        return json_encode([$root => $out], JSON_PRETTY_PRINT);
    }

    static function listAllBuildFunctions($encode = true)
    {
        $all = get_class_methods(self::class);
        $out = array();
        foreach ($all as $m) {
            if (substr($m, 0, 5) == 'build') {
                $out[] = strtolower(substr($m, 5));
            }
        }
        sort($out);
        if ($encode) {
            return json_encode($out);
        } else {
            return $out;
        }

    }

    static function render2($root = 'root')
    {
        $out = array();

        for ($y = 21; $y > 18; $y--) {
            $out[] = [
                'year' => '20' . $y,
                'approvals' => self::createArr(12, function () {
                    $node = [
                        'Applicant' => self::buildSentence(3, 6),
                        'Project' => self::buildSentence(3, 8),
                        'Amount' => '$' . rand(10, 30) . ',000'
                    ];
                    return $node;

                })
            ];
        }
        echo json_encode([$root => $out]);
    }

    static private function getMockery($key, $params = array())
    {
        $mthod = 'build' . ucfirst($key);
        if (method_exists('Builder', $mthod)) {
            return call_user_func(array('Builder', $mthod), $params);
        }
        if (function_exists($key)) {
            return call_user_func($key, $params);
        }
        return $key;
    }

    static private function buildTitle()
    {
        return ucwords(self::buildWords([3, 8]));
    }

    static private function buildWord()
    {
        return self::buildWords([1]);
    }

    static private function buildSnippet($params)
    {
        $mx = count($params);
        if ($mx) {
            $max = (int)$params[0];
        } else {
            $max = 50;
        }
        $text = file_get_contents('http://loripsum.net/api/1/plaintext');
        if (strlen($text) > $max) {
            return substr($text, 0, $max);
        }
        return $text;
    }

    static private function buildSynopsis()
    {
        return file_get_contents('http://loripsum.net/api/1/plaintext');
    }

    static private function buildProse($params)
    {
        $api = 'http://loripsum.net/api';

        $mx = count($params);
        if ($mx) {
            foreach ($params as $p) {
                $api .= '/' . trim($p);
            }
        } else {
            $api .= '/5/medium/headers/ul/link';
        }

        return file_get_contents($api);
    }

    static private function buildTags()
    {
        if (!self::$LOREM) {
            self::$LOREM = file_get_contents('http://loripsum.net/api/3/plaintext');
        }
        $words = preg_split('/\s+/', self::$LOREM);
        explode(' ', self::$LOREM);

        shuffle($words);
        $wordArr = [];
        $len = rand(3, 8);
        for ($i = 0; $i < $len; $i++) {
            $wordArr[] = ucfirst(trim($words[$i]));
        }
        return $wordArr;
    }

    static private function buildThumb()
    {
        return 'https://placeimg.com/500/300/animals/grayscale';
    }

    static private function buildImage($params = [])
    {
        if (!isset(self::$COUNTERS['img'])) {
            self::$COUNTERS['img'] = 0;
        }

        $mx = count($params);
        switch ($mx) {
            case 2:
                $w = (int)$params[0];
                $h = (int)$params[1];
                break;
            case 1:
                $w = (int)$params[0];
                $h = (int)$params[0];
                break;
            case 0:
                $min = 1200;
                $max = 600;
                break;
            default:
                $w = (int)$params[0];
                $h = (int)$params[1];
        }


        $size = "$w/$h";
        $stem = substr($size, 0, -1);
        $size = $stem . (self::$COUNTERS['img'] % 10);

        return "https://placeimg.com/$size/animals/grayscale";
    }

    static private function buildNumber($params = array())
    {
        $mx = count($params);
        switch ($mx) {
            case 1:
                $min = 1;
                $max = max($min, (int)$params[0]);
                break;

            case 0:
                $min = 1;
                $max = 10;
                break;

            default:
                $min = (int)$params[0];
                $max = max($min, (int)$params[1]);
                break;

        }
        return random_int($min, $max);
    }

    static private function buildId($params = array())
    {
        return uniqid();
    }

    static private function buildCounter($params = array())
    {
        if (!isset(self::$COUNTERS['cnt'])) {
            self::$COUNTERS['cnt'] = 0;
        } else {
            self::$COUNTERS['cnt']++;
        }

        $mx = count($params);
        switch ($mx) {
            case 1:
                $min = (int)$params[0];
                break;

            default:
                $min = 1;
                break;

        }
        return ($min + self::$COUNTERS['cnt']);// % $max;
    }


    static private function buildDate($params)
    {
        $mx = count($params);
        if ($mx) {
            $format = $params[0];
        } else {
            $format = 'd/m/Y';
        }

        // Convert to timetamps
        $min = strtotime("-1 year");
        $max = strtotime("-1 day");

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return date($format, $val);
    }


    static private function buildSentence($params = [2, 3])
    {
        $mx = count($params);
        switch ($mx) {
            case 1:
                $min = 1;
                $max = max($min, (int)$params[0]);
                break;

            case 0:
                $min = 1;
                $max = 10;
                break;

            default:
                $min = (int)$params[0];
                $max = max($min, (int)$params[1]);
                break;

        }

        if (!self::$LOREM) {
            self::$LOREM = file_get_contents('http://loripsum.net/api/3/plaintext');
        }
        $words = preg_split('/\s+/', self::$LOREM);
        explode(' ', self::$LOREM);

        shuffle($words);
        $wordArr = [];
        $len = rand($min, $max);
        for ($i = 0; $i < $len; $i++) {
            $wordArr[] = trim($words[$i]);
        }

        return ucfirst(implode(' ', $wordArr)) . '. ';
    }

    static private function buildWords($params = [2, 3])
    {
        $mx = count($params);
        switch ($mx) {
            case 1:
                $min = 1;
                $max = max($min, (int)$params[0]);
                break;

            case 0:
                $min = 1;
                $max = 10;
                break;

            default:
                $min = (int)$params[0];
                $max = max($min, (int)$params[1]);
                break;

        }

        if (!self::$LOREM) {
            self::$LOREM = file_get_contents('http://loripsum.net/api/3/plaintext');
        }
        $words = preg_split('/\s+/', self::$LOREM);
        explode(' ', self::$LOREM);

        shuffle($words);
        $wordArr = [];
        $len = rand($min, $max);
        for ($i = 0; $i < $len; $i++) {
            $wordArr[] = trim($words[$i]);
        }

        return (implode(' ', $wordArr));
    }


    static private function createArr($len, $tpl)
    {
        $data = [];
        for ($i = 0; $i < $len; $i++) {
            $data[] = $tpl();
        }
        return $data;
    }


}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="stylesheet" href="/dist/main.css">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,400i,600,600i,700&display=swap"
          rel="stylesheet">
    <title>ACTF</title>
    <style>

        #tpl-name-edit {
            color: #ccc;
        }

        .func {
            font-weight: bold;
            color: #2e2ea5;
        }

        .param {
            color: #7c3aed;
        }

        .combo {
            color: #307caf;
        }

        .fieldname,
        .literal {
            color: #888;
        }

        .colgrp {
            border: 1px solid rgba(0, 0, 0, .1);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.5);
            padding: 5px
        }
    </style>
</head>
<body class="font-custom min-h-screen" x-data="{ 'showModal': false, ajaxloaded: true}">
<header id="mainheader" class="js-header active-header shadow relative bg-white py-4 top-0 left-0 right-0 z-20">
    <div class="container mx-auto px-4">
        <div class="flex items-center">
            <div class="flex-1">
                <a class="" href="/">MOCK DATA BUILDER</a>
            </div>

        </div>
    </div>
</header>


<main id="ajaxContainer">
    <div class="py-6 sm:px-6 lg:px-8 bg-gray-50">

        <div class="container mx-auto relative px-4 py-6 h-screen">


            <?php
            $tpl = (isset($_POST['tpl'])) ? $_POST['tpl'] : 'id | Category | Title | Image:Image,900,600 | Snippet | Published:Date | Tags | Views:number,1000,30000 | Comments:number,1,10';
            $root = (isset($_POST['root'])) ? $_POST['root'] : 'posts';
            $max = (int)(isset($_POST ['max'])) ? $_POST['max'] : '12';
            $action = (isset($_POST ['action'])) ? $_POST['action'] : '';
            if ($action == 'build') {
                $json = Builder::render($root, $max, $tpl);
            } else {
              //  $json = 'Set your template and click <a href="#" onclick="document.getElementById(\'configBuilder\').submit();">build</a>';
                $json = 'Set your template and click build';
            }
            ?>
            <textarea class="bg-black text-white border shadow w-full  overflow-y-scroll p-4" style="height: 50vh"><?php echo $json; ?></textarea>
            <div class="border bg-blue-100 shadow p-4 lg:p-8">
            <form method="post" id="configBuilder">
                <input type="hidden" id="action" name="action" value="build"/>
                <div class="relative flex-grow w-full mb-8">
                    <label for="tpl-name" class="flex leading-7 text-sm text-gray-600">Template for each record
                    <a href="#help" class="text-blue-200 ml-2">
                        Huh?
                    </a>
                    </label>

                    <div contenteditable="true"
                         id="tpl-name-edit"
                         data-target="tpl-name"
                         class="w-full bg-gray-100 bg-opacity-50 rounded-full border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"><?php echo $tpl ?></div>
                    <input type="hidden" id="tpl-name" name="tpl"
                           value="<?php echo $tpl ?>"
                           class="w-full bg-gray-100 bg-opacity-50 rounded-full border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>


                <div class="flex w-full sm:flex-row flex-col px-8 sm:space-x-4 sm:space-y-0 space-y-4 sm:px-0 items-end">


                    <div class="relative flex-grow w-full">
                        <label for="root-name" class="leading-7 text-sm text-gray-600">Root Property</label>
                        <input type="text" id="root-name" name="root"
                               value="<?php echo $root ?>"
                               class="w-full bg-gray-100 bg-opacity-50 rounded-full border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>


                    <div class="relative flex-grow ">
                        <label for="max-name" class="leading-7 text-sm text-gray-600">Rows</label>
                        <input type="number" id="max-name" name="max" value="<?php echo $max ?>"

                               class="w-full bg-gray-100 bg-opacity-50 rounded-full border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        Build
                    </button>
                </div>
            </form>
            </div>

            <div id="help" class=" my-12 py-12 ">
                <h3>Templates</h3>
                <div class="my-1 text-gray-500 ">
                    <p>Template for each record. Separate each column with a pipe character: eg <span
                                class="colgrp">Title</span> | <span class="colgrp">Synopsis</span> | <span class="colgrp">Author</span></p>
                    <p>
                    The content for each column can be specified like
                    </p>
                    <pre class="p-2 bg-gray-50">
Foo                 -- "foo" is both the fieldName and the value
Foo:Bar             -- "foo" is the fieldName and the value is either the literal string "Bar"
Foo:<span class="func">Bar</span>				--  or if "Bar" is matched to a function, then the value returned by that function
Foo:<span class="func">Image</span>,<span class="param">1200</span>,<span class="param">800</span>  -- "foo" is the fieldName and "Image" is a function that takes width and height as parameters
                    </pre>
<p>
                    The following helper functions are available:
</p>
                    <?php
                    //                    $helpers = Builder::listAllBuildFunctions(false);
                    //                    foreach ($helpers as $helper) {
                    //                        echo '<li>' . $helper . '</li>';
                    //                    }
                    ?>


                    <table class="table-auto border-collapse border border-gray-300 align-baseline text-left align-top">
                        <tr class="mb-1">
                            <td style="vertical-align: top" class=" text-left p-2 func">counter</td>
                            <td style="vertical-align: top" class="p-2">Produces an incrementing number, Can specify a
                                minimun number (eg counter,100)
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class=" text-left p-2 func">date</td>
                            <td style="vertical-align: top" class="p-2">Produces a recent date. Can specify format as
                                parameter (default is d/m/Y)
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">id</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces unique ID (number)
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">image</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces a url to an image from
                                https://placeimg.com. Can specify with and height (eg image,600,400)
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">number</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces a random number. Can specify
                                max or min and max value
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">prose</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces a long article with html
                                formatting. Can specify features such
                                as number of paragraphs, whether to decorate with lists and headers. The default
                                configuration is <span class="param">5/medium/headers/ul/link</span> which is 5
                                paragraphs
                                of medium size with unordered lists and headers.
                                See <a href="http://loripsum.net/" target="_blank">http://loripsum.net</a> for full
                                range of options
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">sentence</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces random words, starting with
                                capital and ending with fullstop. Can specify max or min and max number of words
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">snippet</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces random words. Can specify max
                                or min and max number of characters
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">synopsis</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces a paragraph of random words
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">tags</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces a list of random words</td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">thumb</td>
                            <td style="vertical-align: top" class="border-t p-2">A shortcut for image with 500x300
                                size
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">title</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces 3-8 words, in title case</td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">word</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces just a single word
                                (lowercase)
                            </td>
                        </tr>
                        <tr class="mb-1">
                            <td style="vertical-align: top" class="border-t align-top p-2 func">words</td>
                            <td style="vertical-align: top" class="border-t p-2">Produces random words (all lowercase).
                                Can specify max or min and max number of words
                            </td>
                        </tr>
                    </table>

                </div>

            </div>
        </div>

    </div>
</main>

<script>

    const tplInput = document.getElementById("tpl-name");
    const editEl = document.getElementById("tpl-name-edit");
    const ALL_BUILD_FUNCTIONS = <?php echo Builder::listAllBuildFunctions();?>

        function isFunctionName(m) {
            return ALL_BUILD_FUNCTIONS.includes(m.toLowerCase());
        }

    function hilightEditor() {
        console.log('hilightEditor');
        let t = editEl.innerText;
        let colsHt = [];
        let cols = t.split('|');
        cols.forEach(function (s) {
            // column def
            let n = s.trim();
            let sp = n.split(':');
            if (sp.length > 1) {
                // check if the right side is a function
                n = '<span class="fieldname" title="this is a fieldname name">' + sp[0].trim() + "</span>:";
                let spp = sp[1].split(',');
                let m = (spp.shift()).trim();
                if (isFunctionName(m)) {
                    n += '<span class="func" title="this is a valid function name">' + m + '</span>';
                } else {
                    n += m;
                }
                if (spp.length > 0) {
                    n += ',<span class="param" title="this is a function parameter">' + spp.join('</span>,<span class="param"  title="this is a function parameter">') + "</span>";
                }

            } else {
                if (isFunctionName(n)) {
                    n = '<span class="combo"  title="this is both a field name and a function will be used to produce a value">' + n + '</span>';
                } else {
                    n = '<span class="literal"  title="this is both a field name and its literal value">' + n + '</span>';
                }

            }
            colsHt.push('<span class="colgrp">' + n + '</span>');
        });

        editEl.innerHTML = colsHt.join(' | ');
    }

    editEl.addEventListener("blur", function () {
        hilightEditor();
        tplInput.value = editEl.innerText;
    }, false);

    hilightEditor();
</script>
</body>
</html>


