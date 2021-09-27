<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use Validator;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CompanyResource::collection(Company::with('user')->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string',
            'description' => 'string',
            'address' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $company = new Company;
        $company->user_id = $request->user()->id;
        $company->name = $request->name;
        $company->description = $request->description;
        $company->address = $request->address;
        $company->save();

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        // check if currently authenticated user is the owner of the book
        if ($request->user()->id !== $company->user_id) {
            return response()->json(['error' => 'You can only edit your own company.'], 403);
        }
        $company->update($request->only(['name', 'address', 'description']));
        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Company $company)
    {
        if ($request->user()->id != $company->user_id) {
            return response()->json(['error' => 'You can only delete your own company.'], 403);
        }
        $company->delete();
        return response()->json(null, 204);
    }
}
