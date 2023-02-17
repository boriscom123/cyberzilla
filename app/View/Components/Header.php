<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Header extends Component
{
    /**
     * @var array $params - Данные компонента
     */
    public array $params;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(Auth::check()){
            $this->params['auth'] = true;
        } else {
            $this->params['auth'] = false;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header');
    }
}
