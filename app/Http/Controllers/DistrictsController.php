<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\State;
use App\Models\Districts;

class DistrictsController extends Controller
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

        $states= State::all();
        $districts = Districts::all();

        return view('districts.index', [
            'states' => $states,'districts' => $districts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states    = State::all();

        return view('districts.add', ['states' => $states]);
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
                'district_name' => 'required',
                'state_id' => 'required',
                'status' => 'required'
            ]);
    
            Districts::create($request->all());

            DB::commit();
            return redirect()->route('districts.index')->with('success','districts created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            
            return redirect()->route('districts.create')->with('error',$th->getMessage());
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
        $states    = State::all();
        $districts = Districts::whereId($id)->first();
        

        return view('districts.edit', ['district' => $districts,'states' => $states,]);
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
                'district_name' => 'required',
                'state_id' => 'required',
                'status' => 'required'
            ]);
            
            $district = Districts::whereId($id)->first();

            $district->district_name = $request->district_name;
            $district->status = $request->status;
            $district->state_id = $request->state_id;
            $district->save();

            
            DB::commit();
            return redirect()->route('districts.index')->with('success','districts updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
           // dd($th->getMessage());
            return redirect()->route('districts.edit',['district' => $district])->with('error',$th->getMessage());
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
    
            Districts::whereId($id)->delete();
            
            DB::commit();
            return redirect()->route('districts.index')->with('success','districts deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('districts.index')->with('error',$th->getMessage());
        }
    }
}
