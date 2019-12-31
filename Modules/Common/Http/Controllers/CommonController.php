<?php

namespace Modules\Common\Http\Controllers;

class CommonController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('common::index');
    }

    public function create()
    {
        return view('common::create');
    }

    public function store()
    {
        return '';
    }

    public function show($id)
    {
        return view('common::show', ['id' => $id]);
    }

    public function edit($id)
    {
        return view('common::edit', ['id' => $id]);
    }

    public function update($id)
    {
        return $id;
    }

    public function destroy($id)
    {
        return $id;
    }
}
