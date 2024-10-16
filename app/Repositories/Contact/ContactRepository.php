<?php

namespace App\Repositories\Contact;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ContactRepository implements ContactRepositoryInterface
{
    protected function limit(Request $request)
    {
        $limit = (int) $request->input('limit', Config::get('paginate.default_limit'));

        return $limit;
    }

    public function index($request)
    {
        // return Contact::paginate($this->limit($request));
        return Contact::get();
    }
    public function store($request)
    {

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
    }
}
