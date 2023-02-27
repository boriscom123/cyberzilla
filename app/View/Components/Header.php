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
     * @var array $data - Данные компонента
     */
    public array $data;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        if (Auth::check()) {
            $this->data['auth'] = true;
            $this->data['user'] = Auth::user();
            $this->data['user-role'] = $this->getUserRole();
            $this->data['active-navigation'] = $this->getActiveNavigation($request);
        } else {
            $this->data['auth'] = false;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header', ['data' => $this->data]);
    }

    public function getUserRole(): UserRoles
    {
        /** @var UserOptions $userOptions */
        $userOptions = UserOptions::query()->where('user_id', $this->data['user']->id)->first();
        /** @var UserRoles $userRoles */
        $userRoles = UserRoles::query()->where('id', $userOptions->user_role_id)->first();

        return $userRoles;
    }

    public function getActiveNavigation(Request $request): string
    {
        $nav = explode('/', $request->getPathInfo());
        return $nav[1];
    }
}
