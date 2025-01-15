<?php

namespace App\Http\Controllers;

use Kernel\Application\Http\Request;
use Kernel\Application\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $names = [
            'John Doe',
            'Jane Doe',
            'Joe Doe',
            'Jill Doe',
            'John Doe',
        ];

        return view('home', [
            'names' => $names,
            'test' => '123',
            'appDebug' => env('APP_DEBUG'),
        ]);
    }

    public function test(Request $request): View
    {
        return view('test', [
            'name' => $request->input('name'),
        ]);
    }

    public function store(Request $request): false|string
    {
        $arr = [
            'name' => $request->input('name'),
            'test' => '123',
        ];

        return json_encode($arr);
    }
}
