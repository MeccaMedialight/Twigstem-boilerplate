<?php
/**
 * Ex
 */

namespace App;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ApiExtension //extends AbstractExtension
{
    private $filters;

    public function __construct()  {
        $this->filters = [
            'randomise' =>  [$this, 'randomise'],
            'limit' =>  [$this, 'limit'],
        ];
    }

    public function applyFilter($filter, &$data){

        if (is_array($filter)){
            $name = array_shift($filter);

        } else {
            $name = $filter;
            $filter = array();
        }

        if (isset($this->filters[$name])){
            //echo "calling $name ...";
            $data = call_user_func($this->filters[$name], $data, $filter);

        } else {
            //echo "Filter $name not found";
        }

    }


    public function randomise(array $data, $args = array())
    {
        shuffle($data['movies']);
        return $data;
    }

    public function limit(array $data, $args = array())
    {
        return array_slice($data, 0, 8);
    }
}