<?php

namespace App\Http\Controllers;

use App\Gig;
use Illuminate\Http\Request;
use App\Http\Resources\GigResource;
use Validator;
use Illuminate\Validation\Rule;

class GigsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Gig::with('company');
        if ($request->has('name')) {
            $query->whereRaw(
                "MATCH(name) AGAINST(?)",
                $request->input('name')
            );
            $query->orWhere('name', 'like', "%" . $request->input('name') . "%");
        }
        if ($request->has('description')) {
            $query->whereRaw(
                "MATCH(description) AGAINST(?)",
                $request->input('description')
            );
            $query->orWhere('description', 'like', "%" . $request->input('description') . "%");
        }
        if ($request->has('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('posted')) {
            $query->where('posted', $request->input('posted'));
        }

        return GigResource::collection($query->paginate(25));
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
            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
                //prevent user from adding company_id of another user
                // Rule::exists('companies')->where(function ($query) use ($request) {
                //     $query->where('id', $request->id)->where('user_id', $request->user()->id);
                // }),
            ],
            'name' => 'required|string',
            'description' => 'required|string',
            'timestamp_start' => 'required|date_format:Y-m-d H:i:s',
            'timestamp_end' => 'required|date_format:Y-m-d H:i:s|after:timestamp_start',
            'number_of_positions' => 'required|integer',
            'pay_per_hour' => 'required|numeric',
            'posted' => 'required|boolean',
            'status' => 'required|in:Not started,Started,Finished'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $gig = new Gig;
        $gig->company_id = $request->company_id;
        $gig->name = $request->name;
        $gig->description = $request->description;
        $gig->timestamp_start = $request->timestamp_start;
        $gig->timestamp_end = $request->timestamp_end;
        $gig->number_of_positions = $request->number_of_positions;
        $gig->pay_per_hour = $request->pay_per_hour;
        $gig->posted = $request->posted;
        $gig->status = $request->status;
        $gig->save();

        return new GigResource($gig);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gig  $gig
     * @return \Illuminate\Http\Response
     */
    public function show(Gig $gig)
    {
        return new GigResource($gig);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gig  $gig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gig $gig)
    {
        // check if currently authenticated user is the owner of the book
        if ($request->user()->id !== $gig->company->user_id) {
            return response()->json(['error' => 'You can only edit your own company gig.'], 403);
        }
        $gig->update($request->only(['name', 'name', 'description', 'timestamp_start', 'timestamp_end', 'number_of_positions', 'pay_per_hour', 'posted', 'status']));
        return new GigResource($gig);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gig  $gig
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Gig $gig)
    {
        if ($request->user()->id != $gig->company->user_id) {
            return response()->json(['error' => 'You can only delete your own company gig.'], 403);
        }
        $gig->delete();
        return response()->json(null, 204);
    }
}
