<?php
/**
 * Ex
 */

namespace App;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ApiExtension //extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFunction('area', [$this, 'calculateArea']),
        ];
    }

    public function randomise(array &$data)
    {
        shuffle($data);
    }

    public function limit(array &$data, int $maxlength)
    {
        shuffle($data);
    }
}