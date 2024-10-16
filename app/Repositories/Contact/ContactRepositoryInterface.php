<?php

namespace App\Repositories\Contact;

interface ContactRepositoryInterface
{

    public function index($request);

    public function store($request);
}
