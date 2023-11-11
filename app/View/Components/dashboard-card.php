<?php

namespace App\View\Components;

use Illuminate\View\Component;

class dashboard_card extends Component
{
    public $value;
    public $label;
    public $svg;

    public function __construct($value, $label, $svg)
    {
        $this->value = $value;
        $this->value = $label;
        $this->value = $svg;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard-card');
    }
}
