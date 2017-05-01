<?php

namespace app\controllers;



class Page extends AppController
{
    public function viewAction(){
        $name = 'Angel';
        $this->set(compact('name'));
    }
}