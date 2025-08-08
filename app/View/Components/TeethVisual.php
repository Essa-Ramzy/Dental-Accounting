<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TeethVisual extends Component
{
    public function __construct(
        public $selectedTeeth
    ) {}
    public function render(): View|Closure|string
    {
        return view('components.teeth-visual');
    }
}
