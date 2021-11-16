<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BreadCrumbs extends Component
{
    public $breadcrumbs;

    /**
     * Create a new component instance.
     *
     * @param $breadcrumbs
     */
    public function __construct($breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.bread-crumbs', ['breadcrumbs' => $this->breadcrumbs]);
    }
}
