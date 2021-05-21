<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Color;
use App\Http\Resources\Color as ColorResource;
use Illuminate\Support\Facades\Validator;

class ColorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::all();

        return $this->sendResponse(ColorResource::collection($colors), 'Colors retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:colors',
            'hex' => 'required|unique:colors'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $color = Color::create($input);

        return $this->sendResponse(new ColorResource($color), 'Color created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $color = Color::find($id);

        if (is_null($color)) {
            return $this->sendError('Color not found.');
        }

        return $this->sendResponse(new ColorResource($color), 'Color retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:colors',
            'hex' => 'required|unique:colors'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $color->name = $input['name'];
        $color->hex = $input['hex'];
        $color->save();

        return $this->sendResponse(new ColorResource($color), 'Color updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        $color->delete();

        return $this->sendResponse([], 'Color deleted successfully.');
    }
}
