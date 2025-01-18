<?php

namespace App\Http\Controllers;

use App\Model\User;
use Kernel\Application\Http\Request;
use Kernel\Application\View\View;

class HomeController extends Controller
{

    public function index(): View
    {

        return view('home');

    }

    public function testValidation()
    {

        return view('validation');

    }

    public function storeValidation(Request $request)
    {

        $data = $request->validate([
            'name' => ['required', 'min:5', 'max:10'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'have_numbers',
                'uppercase',
                'min:8',
                'have_numbers',
                'special',
                'confirm:' . $request->input('password_confirmation'),
            ],
        ]);

        if (isset($data['error_message'])) {
            dd($data['error_message']);
        }


    }
}
