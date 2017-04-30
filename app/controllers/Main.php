<?php
namespace app\controllers;


class Main
{
    public function __construct()
    {
        echo "Main controller created";
    }

    public function indexAction(){
        echo 'Main-index';
    }
}