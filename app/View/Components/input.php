<?php

namespace App\View\Components;

use Illuminate\View\Component;

class input extends Component
{
    public $name;
    public $value;
    public $label;
    public $inputType;
    public $id;

    public function __construct($name, $value, $label,$inputType,$id)
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->inputType = $inputType;
        $this->id=$id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input');
    }
}
