<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateWasherRequest;
use App\Models\Washer;

class WasherController extends Controller
{
    public function index()
    {
        return view('staff.washers.index', ['washers' => Washer::orderBy('code')->get()]);
    }

    public function update(UpdateWasherRequest $request, Washer $washer)
    {
        $washer->update($request->validated());

        return back()->with('status', 'Lavadora actualizada.');
    }
}
