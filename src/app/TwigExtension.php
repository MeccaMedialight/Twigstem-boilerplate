<?php
/**
 * Ex
 */

namespace App;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('inRequest', [$this, 'inRequest']),
            new TwigFunction('listAllComponents', [$this, 'listAllComponents']),
            new TwigFunction('listAllComponentTypes', [$this, 'listAllComponentTypes']),
            new TwigFunction('listComponentsOfType', [$this, 'listComponentsOfType']),
            new TwigFunction('directoryMenu', [$this, 'directoryMenu']),
            new TwigFunction('makeCopyWidget', [$this, 'makeCopyWidget']),
            new TwigFunction('lorem', [$this, 'lorem']),
            new TwigFunction('breadcrumb', [$this, 'breadcrumb']),
        ];
    }

    public function lorem($minParas = 4, $random = 0)
    {
        if ($random) {
            $paras = $minParas . random_int(1, $random);
        } else {
            $paras = $minParas;
        }
        $ul = false;
        if ($paras > 5) {
            $ul = true;
        }
        $content = file_get_contents('http://loripsum.net/api/' . $paras . '/decorate/headers/' . $ul);
        return $content;
    }

    public function inRequest($k, $default = null)
    {

        if (isset($_REQUEST[$k])) {
            return $_REQUEST[$k];
        }
        return $default;

    }

    /**
     * Sample function to list all the twigs in a directory (default is the '_components' directory)
     *
     * @param string $twigDir name of directory in the views directory
     * @return array
     */
    public function listAllComponents($twigDir = '_components'): array
    {
        $srcDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $twigDir;
        $files = glob($srcDir . '/*.twig');
        $out = [];
        foreach ($files as $f) {
            $out[] = basename($f, '.twig');
        }
        return $out;
    }

    /**
     * This assumes that the components are named {type}-something.twig - for example
     *    hero-1.twig
     *    hero-withleftsideimage.twig
     *
     * @param string $twigDir
     * @return array
     */
    public function listAllComponentTypes($twigDir = '_components'): array
    {
        $srcDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $twigDir;
        $files = glob($srcDir . '/*.twig');

        $out = [];
        foreach ($files as $f) {
            $n = basename($f, '.twig');
            $type = explode('-', $n)[0];
            if (!isset($out[$type])) {
                $out[$type] = [];
            }
            $out[$type][] = $n;
        }

        return $out;
    }

    public function listComponentsOfType($twigDir = '_components', $type = '', $extendedInfo = true): array
    {
        if (empty($type)) {
            $type = $this->inRequest('t', 'hero');
        }
        $srcDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $twigDir;
        if ($type == '*') {
            // get everything
            $files = glob($srcDir . '/*.twig');
        } else {
            $files = glob($srcDir . '/' . $type . '-*.twig');
        }
        $out = [];
        if ($extendedInfo) {
            foreach ($files as $f) {
                $raw = file_get_contents($f);
                $node = array(
                    'name' => basename($f, '.twig'),
                    'info' => $this->parseTpl($raw)
                );


                $out[] = $node;
            }

        } else {
            foreach ($files as $f) {
                $out[] = basename($f, '.twig');
            }
        }

        return $out;
    }

    /**
     * Create an array of twigs that can be used to create a sitemap or menu tree.
     * @return array
     */
    public function directoryMenu(): array
    {
        $srcDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views';
        return $this->children($srcDir, '/');
    }

    public function breadcrumb()
    {
        $here = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $bits = explode('/', $here);
        $out = [];
        $last = '';
        if (count($bits) > 1) {

            $out[] = [
                'name' => 'Home',
                'url' => '/'
            ];

            foreach ($bits as $f) {
                $last .= '/' . $f;
                $out[] = [
                    'name' => self::nicefy($f),
                    'url' => $last //. '.twig',
                ];
            }
        }
        return $out;
    }

    private function children($parent, $urlRoot): array
    {
        $files = glob($parent . '/*.twig');
        $out = [];
        foreach ($files as $f) {
            $root = basename($f, '.twig');
            $node = [
                'name' => self::nicefy($root),
                'url' => $urlRoot . $root, //. '.twig',
                'children' => []

            ];
            $p = $parent . DIRECTORY_SEPARATOR . $root;
            if (is_dir($p)) {
                $node['children'] = $this->children($p, $urlRoot . $root . '/');
            }
            $out[] = $node;
        }

        return $out;
    }


    /**
     * Try and make a camel case string into something presentable
     *
     * @param string $str
     * @param boolean $ucwrds whether to uppercase words
     * @param boolean $keepHyphens whether to replace hyphens
     * @return string
     */
    public static function nicefy($str = '', $ucwrds = true, $keepHyphens = false): string
    {

        if (!$keepHyphens) {
            $txt = preg_replace('/[-_]/', ' ', $str);
        } else {
            $txt = preg_replace('/[_]/', ' ', $str);
        }
        $txt = preg_replace('/\//', ': ', $txt);
        $re = '/# Match position between camelCase "words".
    (?<=[a-z])  # Position is after a lowercase,
    (?=[A-Z])   # and before an uppercase letter.
    /x';
        $a = preg_split($re, $txt);
        $txt = implode(' ', $a);
        if ($ucwrds) {
            return ucwords($txt);
        } else {
            return strtolower($txt);
        }
    }


    public function makeCopyWidget($t)
    {
        $path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $t;
        $src = file_get_contents($path);
        $id = "cmp" . uniqid();
        $out = '
        <input type="text" value="' . htmlspecialchars($src) . '" id="' . $id . '" class="" style="position: absolute;
    left: -999em;">
<div class="tooltip">
<button onclick="copyComponent(\'' . $id . '\')" onmouseout="outFunc(\'' . $id . '\')">
  <span class="tooltiptext" id="tt' . $id . '">Copy to clipboard</span>
 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
  </button>
</div>
        ';
        return $out;
    }

    private function parseTpl($str)
    {
        //$data = array();
        if (preg_match_all("/{#\s*([^}]*)#}/", $str, $matches)) {
            foreach ($matches[1] as $matched) {
                return $matched;
            }
        }
        return '';
        //return $data;
    }


}