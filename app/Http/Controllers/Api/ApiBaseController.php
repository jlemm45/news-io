<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ApiBaseController extends Controller
{

    protected $type;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd( Input::get() );
        $relations = [];
        $instance = new $this->type;
        if (is_callable([$instance, 'getRelations'])) {
            $relations = $instance->getRelations();
        }
        $model = $this->type;

        $query = $model::with($relations);

        $this->addConstraints($query, Input::get());

        return $query->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $item)
    {
        if (is_callable([$item, 'loadAllRelations'])) {
            $item->loadAllRelations();
        }
        return $item;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $item)
    {
        $data = $request->all();
        foreach ($data as $property => $value) {
            $item[$property] = $value;
        }
        $item->save();
        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function addConstraints(&$query, $constraints)
    {
        if (count($constraints) > 0) {
            foreach ($constraints as $constraint => $id) {
                $query->where($constraint, $id);
            }
        }
    }
}