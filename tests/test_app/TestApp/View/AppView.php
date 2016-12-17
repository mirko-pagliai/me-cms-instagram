<?php
namespace TestApp\View;

use Cake\View\View;

class AppView extends View
{
    public function initialize()
    {
        parent::initialize();

        //Loads helpers
        $this->loadHelper('Html', ['className' => 'MeTools.Html']);
        $this->loadHelper('Thumber.Thumb');
    }
}
