<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AddButton extends Component
{
    public $value;
    public $dataTarget;

    public function __construct($value, $dataTarget)
    {
        $this->value = $value;
        $this->dataTarget = $dataTarget;
    }

    public function render()
    {
        return view('components.add-button');
    }
}
