<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use Stripe\Customer;

class ProfileController extends Controller
{
    public function view(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        if ($customer) {
            $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
            $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        } else {
            $shippingAddress = new CustomerAddress(['type' => AddressType::Shipping]);
            $billingAddress = new CustomerAddress(['type' => AddressType::Billing]);
        }

        // dd($customer, $shippingAddress->attributesToArray(), $billingAddress, $billingAddress->customer);

        $countries = Country::query()->orderBy('name')->get();
        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function store(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;

        $customer->update($customerData);
        // if ($customer) {
        //     $customer->update($customerData);
        // } else {
        //     $customer->create([
        //         'user_id' => $user->id,
        //         'first_name' => $request->first_name,
        //         'last_name' => $request->last_name,
        //         'phone' => $request->phone
        //     ]);
        // }

        if ($customer->shippingAddress) {
            $customer->shippingAddress->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            CustomerAddress::create($shippingData);
        }
        if ($customer->billingAddress) {
            $customer->billingAddress->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            CustomerAddress::create($billingData);
        }

        $request->session()->flash('flash_message', 'Profile was updated successfully.');
        return redirect()->route('profile');
    }

    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $passwordData = $request->validated();

        $user->password = Hash::make($passwordData['new_password']);
        $user->save();

        $request->session()->flash('flash_message', 'Your password was updated successfully.');
        return redirect()->route('profile');
    }
}
