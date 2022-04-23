<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\State;
use App\Models\Districts;
use App\Models\Category;
use App\Models\Candidates;
use App\Models\Candidateanswer;

class CandidatesController extends Controller
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

        $category = Category::all();
        $states= State::all();
        $Candidates = Candidates::all();


        return view('candidates.index', [
            'states' => $states,'candidates' => $Candidates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorys = Category::where('status','=','Y')->get();
        $states= State::where('status','=','Y')->get();
        return view('candidates.add', ['categorys' => $categorys,'states' => $states]);
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
                'firstName' => 'required',
                'lastName' => 'required',
                'type' => 'required',
                'category' => 'required',
                'state' => 'required',
                'district' => 'required',
                'status' =>'required'
            ]);
    
            Candidates::create($request->all());

            DB::commit();
            return redirect()->route('candidates.index')->with('success','Candidate created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            
            return redirect()->route('candidates.create')->with('error',$th->getMessage());
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
        $answers = Candidateanswer::leftJoin('statements', function($join) {
            $join->on('statements.id', '=', 'candidate_answers.statement_id');
          })
        ->where('candidate_id','=',$id)
        ->select("candidate_id","statement","candidate_answer")
        ->get();



        return view('candidates.show', ['answers' => $answers]);
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
        $categorys = Category::where('status','=','Y')->get();
        $candidate = Candidates::whereId($id)->first();
        $districts = Districts::where('state_id','=',$candidate->state)->get();
    
        return view('candidates.edit', ['districts' => $districts,'states' => $states,'categorys' => $categorys,'candidate' =>$candidate]);
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
                'firstName' => 'required',
                'lastName' => 'required',
                'type' => 'required',
                'category' => 'required',
                'state' => 'required',
                'district' => 'required',
                'status' =>'required'
            ]);
            
            $Candidates = Candidates::whereId($id)->first();

            $Candidates->firstName = $request->firstName;
            $Candidates->lastName = $request->lastName;
            $Candidates->type = $request->type;
            $Candidates->category = $request->category;
            $Candidates->state = $request->state;
            $Candidates->district = $request->district;
            $Candidates->status = $request->status;
          
            $Candidates->save();

            
            DB::commit();
            return redirect()->route('candidates.index')->with('success','Candidates updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
           // dd($th->getMessage());
            return redirect()->route('candidates.edit',['district' => $district])->with('error',$th->getMessage());
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
    
            Candidates::whereId($id)->delete();
            
            DB::commit();
            return redirect()->route('candidates.index')->with('success','Candidates deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('candidates.index')->with('error',$th->getMessage());
        }
    }
}
