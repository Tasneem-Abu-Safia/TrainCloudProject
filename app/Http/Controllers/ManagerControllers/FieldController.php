<?php

namespace App\Http\Controllers\ManagerControllers;

use App\Http\Controllers\Controller;
use App\Models\Advisor;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class FieldController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Field::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($field) {
                    $btn = '<a   class="btn btn-light-info editField" data-id="' . $field->id . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="mainDelete btn btn-light-danger" data-id="' . $field->id . '"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.field.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:fields',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()->first()]);
        }

        $field = new Field;
        $field->name = $request->name;
        $field->save();

        // Return a response or redirect as needed
        return response()->json(['msg' => 'Field created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:fields,name,' . $id,
        ]);

        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()->first()]);
        }


        $field = Field::findOrFail($id);
        $field->name = $request->name;
        $field->save();

        // Return a response or redirect as needed
        return response()->json(['msg' => 'Field updated successfully']);
    }


    public function destroy($id)
    {
        Field::destroy($id);
        return back()->with('msg', 'Deleted Done');
    }

    public function getAdvisors(Request $request)
    {
        $fieldId = $request->field_id;
        $advisors = Advisor::whereHas('fields', function ($query) use ($fieldId) {
            $query->where('field_id', $fieldId);
        })->with('user')->get();

        return response()->json([
            'advisors' => $advisors
        ]);
    }
}
