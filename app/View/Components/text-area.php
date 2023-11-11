<?php

namespace App\View\Components;

use Illuminate\View\Component;

class text_area extends Component
{
    public $name;
    public $value;
    public $label;

    public function __construct($name, $value, $label)
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.text-area');
    }
}
