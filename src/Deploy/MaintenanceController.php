<?php

namespace LaravelToolkit\Deploy;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaintenanceController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response|RedirectResponse
    {
        if (! app()->isDownForMaintenance()) {
            return redirect(request()->query('redirect') ?? config('laraveltoolkit.deploy.redirect_callback'));
        }

        return Inertia::render(config('laraveltoolkit.deploy.inertia_component'));
    }
}
