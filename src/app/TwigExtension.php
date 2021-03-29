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
            new TwigFunction('listAllSections', [$this, 'listAllSections']),
        ];
    }

    /**
     * Sample function to list all the twigs in a directory
     *
     * @param string $twigDir name of directory in the views directory
     * @return array
     */
    public function listAllSections( $twigDir = '_sections')
    {
        $srcDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $twigDir;
        $files = glob($srcDir . '/*.twig');

        $out = [];
        foreach($files as $f){
            $out[] = basename($f, '.twig');
        }

        return $out;
    }
}