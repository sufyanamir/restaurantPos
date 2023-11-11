<?php

namespace App\View\Components;

use Illuminate\View\Component;

class social_input extends Component
{
    public $socialLogo;
    public $name;
    public $qrId;
    public $qrName;
    public $value;

    public function __construct($socialLogo,  $name, $qrId, $qrName,$value)
    {
        $this->socialLogo = $socialLogo;
        $this->name = $name;
        $this->qrId = $qrId;
        $this->qrName = $qrName;
        $this->value = $value;
    
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.social-input');
    }
}
