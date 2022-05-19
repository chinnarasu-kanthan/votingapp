<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\Category;
use App\Models\Answers;
use App\Models\Statements;
use App\Models\Questions;
use App\Models\Candidates;
use App\Models\State;


class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $questions = Questions::select('id','question')->get();
        $display = [];
        foreach($questions as $key => $val){
            
            $display[$key]['id'] = $val->id;
            $display[$key]['question'] = $val->question;
            $display[$key]['items'] = $this->mapQuestionToanswer($val->id);
            
        }
       
        $statements = Statements::select('id','statement')->get();
        // echo "<pre>";
        // print_r($display);
        // exit;
        return view('home',["data" => $display,"statements" => $statements]);
    }

    private function mapQuestionToanswer($id, $cat_id, $type){
        if($type ==1){
            $questions = Answers::leftjoin('candidateanswers','candidateanswers.answer_id', '=', 'answers.id')
            ->leftjoin('candidates','candidateanswers.candidate_id', '=', 'candidates.id')
            ->distinct('answers.id')
            ->select('answers.id','answers.answer')->where('answers.question_id','=',$id)->get();
            if( $questions){
                return  $questions;
            }
        }else{
            $statements = Statements::leftjoin('candidates','statements.candidate_id', '=', 'candidates.id')
            ->distinct('statements.id')
            ->where('candidates.state','=',1)
            ->select('statements.id','statements.question_id','statements.statement')->where('statements.question_id','=',$cat_id)->get();
            if( $statements){
                return  $statements;
            }
        }
       
       
        return [];
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        return view('profile');
    }
	
	public function showquestion()
    {
		$questions = Questions::select('cat_id','id','question','type')->get();
       
        $display = [];
        foreach($questions as $key => $val){
            
            $display[$key]['id'] = $val->id;
            $display[$key]['question'] = $val->question;
            $display[$key]['items'] = $this->mapQuestionToanswer($val->id, $val->cat_id, $val->type);
            $display[$key]['type'] = $val->type;
            
        }
       
        $statements = Statements::select('id','statement')->get();
        // echo "<pre>";
        // print_r($display);
        // exit;
        return view('layouts.frontapp',["data" => $display,"statements" => $statements]);
   
    }
    public function mapQuestionTostatements()
    {
		
        
        $statements = Statements::join('candidates','candidates.id', '=', 'statements.candidate_id')
        ->select('statements.id','statements.statement','candidates.type')->get();
        echo "<pre>";
        print_r($statements);
        exit;
        //return view('layouts.frontappstatment',["statements" => $statements]);
   
    }
	
	

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        try {
            DB::beginTransaction();
            
            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            
            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Password Changed Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
	
	public function getCandidate(Request $request)
    {
		
        try {
            

        $new = [];
        $res = Candidates::join('candidateanswers','candidateanswers.candidate_id', '=', 'candidates.id')
        ->join('answers','candidateanswers.answer_id', '=', 'answers.id')
		->join('questions','questions.id', '=', 'answers.question_id')
        ->sum('questions.point')
        ->select('candidates.id','answers.question_id','questions.point')
		->where('candidates.state','=',1)
        ->whereIn('answers.dd',$request->input('answers'))
        ->groupBy('candidates.id')
		->get();

          
        foreach($res as $val){
        }
            
            dd($new);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()->with('error', $th->getMessage());
        }
    }

    // $questions = Answers::leftjoin('candidateanswers','candidateanswers.answer_id', '=', 'answers.id')
    //     ->leftjoin('candidates','candidateanswers.candidate_id', '=', 'candidates.id')
    //     ->where('candidates.state','=',1)
    //     ->distinct('answers.id')
    //     ->select('answers.id','answers.answer')->where('answers.question_id','=',$id)->get();
    //     if( $questions){
    //         return  $questions;
    //     }
    //     return [];
    // }

}
