<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AddressController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Account/Addresses', [
            'addresses' => auth()->user()->addresses()->latest()->get(),
        ]);
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($request->boolean('is_default')) {
            $user->addresses()->update([
                'is_default' => false,
            ]);
        }

        $user->addresses()->create([
            ...$request->validated(),
            'is_default' => $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Address added successfully.');
    }
}
