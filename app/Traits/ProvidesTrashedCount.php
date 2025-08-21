<?php

namespace App\Traits;

use Illuminate\Support\Facades\View;

trait ProvidesTrashedCount
{
    protected function shareTrashedCount(): void
    {
        // Check if the controller has defined its model property
        if (property_exists($this, 'model') && $this->model) {
            // Share the variable with all views rendered by this controller instance
            View::share('trashedCount', $this->model::onlyTrashed()->count());
        }
    }
}
