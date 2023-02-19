<?php

namespace App\View\Components;

use App\Models\UserOptions;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\Http\Request;

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
    public function __construct(Request $request)
    {
        if (Auth::check()) {
            $this->params['auth'] = true;
            $this->params['user'] = Auth::user();
            $this->getUserData();
            $this->params['active-navigation'] = $this->getActiveNavigation($request);
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

    public function getUserData()
    {
        /** @var UserOptions $userOptions */
        $userOptions = UserOptions::query()->where('user_id', $this->params['user']->id)->first();
        /** @var UserRoles $userRoles */
        $userRoles = UserRoles::query()->where('id', $userOptions->user_role_id)->first();
        $this->params['role'] = $userRoles;
    }

    public function getActiveNavigation(Request $request): string
    {
        $nav = explode('/', $request->getPathInfo());
        return $nav[1];
    }
}
