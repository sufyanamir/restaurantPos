<?php

namespace App\View\Components;

use Illuminate\View\Component;

class plus_button extends Component
{
    public $name;
    public $label;
    public $addRow;
    public $onclick;
    public $class;

    public function __construct($name, $label, $addRow, $onclick, $class)
    {
        $this->name = $name;
        $this->label = $label;
        $this->addRow = $addRow;
        $this->onclick = $onclick;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.plus-button');
    }
}
