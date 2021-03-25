<?php
/**
 * Example
 */

namespace App;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ApiExtension //extends AbstractExtension
{
    private $filters;

    public function __construct()
    {
        $this->filters = [
            'randomise' => [$this, 'randomise'],
            'limit' => [$this, 'limit'],
        ];
    }

    public function applyFilter($name, $data)
    {
        if ($name) {
            if (isset($this->filters[$name])) {
                $data = call_user_func($this->filters[$name], $data);
            } else {
                $this->error("Filter $name not found");
            }
        }
        return $data;
    }


    public function randomise(array $data)
    {
        shuffle($data['movies']);
        return $data;
    }

    public function limit(array $data, $args = array())
    {
        $data['movies'] = array_slice($data['movies'], 0, 8);
        return $data;
    }

    private function error($msg)
    {
        echo $msg;
    }

}