<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\State;

class StateController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::all();

        return view('state.index', [
            'states' => $states
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = [];

        return view('state.add', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'state_name' => 'required',
                'status' => 'required'
            ]);
    
            State::create($request->all());

            DB::commit();
            return redirect()->route('state.index')->with('success','state created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            
            return redirect()->route('state.create')->with('error',$th->getMessage());
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state = State::whereId($id)->first();
        

        return view('state.edit', ['state' => $state]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            // Validate Request
            $request->validate([
                'state_name' => 'required'
            ]);
            
            $state = State::whereId($id)->first();

            $state->state_name = $request->state_name;
            $state->status = $request->status;
            $state->save();

            
            DB::commit();
            return redirect()->route('state.index')->with('success','state updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('state.edit',['state' => $state])->with('error',$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
    
            State::whereId($id)->delete();
            
            DB::commit();
            return redirect()->route('state.index')->with('success','state deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('state.index')->with('error',$th->getMessage());
        }
    }
}
