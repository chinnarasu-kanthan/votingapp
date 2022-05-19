<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

use App\Models\Category;
use App\Models\Answers;
use App\Models\Statements;
use App\Models\Questions;
use App\Models\Candidates;
use App\Models\State;
use App\Models\Candidateanswer;

class UserController extends Controller
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
     * List User 
     * @param Nill
     * @return Array $user
     * @author Shani Singh
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', ['users' => $users]);
    }
    
    /**
     * Create User 
     * @param Nill
     * @return Array $user
     * @author Shani Singh
     */
    public function create()
    {
        $roles = Role::all();
       
        return view('users.add', ['roles' => $roles]);
    }

    /**
     * Store User
     * @param Request $request
     * @return View Users
     * @author Shani Singh
     */
    public function store(Request $request)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email',
            'mobile_number' => 'required|numeric|digits:10',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user = User::create([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
                'password'      => Hash::make($request->first_name.'@'.$request->mobile_number)
            ]);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success','User Created Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Update Status Of User
     * @param Integer $status
     * @return List Page With Success
     * @author Shani Singh
     */
    public function updateStatus($user_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'user_id'   => $user_id,
            'status'    => $status
        ], [
            'user_id'   =>  'required|exists:users,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('users.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            User::whereId($user_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('users.index')->with('success','User Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Edit User
     * @param Integer $user
     * @return Collection $user
     * @author Shani Singh
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit')->with([
            'roles' => $roles,
            'user'  => $user
        ]);
    }

    /**
     * Update User
     * @param Request $request, User $user
     * @return View Users
     * @author Shani Singh
     */
    public function update(Request $request, User $user)
    {
        // Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|unique:users,email,'.$user->id.',id',
            'mobile_number' => 'required|numeric|digits:10',
            'role_id'       =>  'required|exists:roles,id',
            'status'       =>  'required|numeric|in:0,1',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $user_updated = User::whereId($user->id)->update([
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'email'         => $request->email,
                'mobile_number' => $request->mobile_number,
                'role_id'       => $request->role_id,
                'status'        => $request->status,
            ]);

            // Delete Any Existing Role
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
            // Assign Role To User
            $user->assignRole($user->role_id);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('users.index')->with('success','User Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete User
     * @param User $user
     * @return Index Users
     * @author Shani Singh
     */
    public function delete(User $user)
    {
        DB::beginTransaction();
        try {
            // Delete User
            User::whereId($user->id)->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Import Users 
     * @param Null
     * @return View File
     */
    public function importUsers()
    {
        return view('users.import');
    }

    public function uploadUsers(Request $request)
    {
    
    $candidate_ids = [];
	$file = storage_path('app/Voting Genie - Database sample final (1).xlsx');
    $results = Excel::toArray("", $file);
    

    $parent = "";
    $child = "";
    $new_arr = [];
    
echo "<pre>";
   
    foreach($results[0] as $key =>$result){
	
 //$result = array_filter($result, fn($value) => !is_null($value) && $value !== '');
 
 if(sizeof($result) > 0){
//  echo $key; 
//  print_r($result);
                
                    if($key ===1){
               
                        foreach($result as $k => $v){
                          
                           
                           // exit;
                            if($k > 1 ){
                                if($results[0][3][$k] !=""){
                                $candidate = Candidates::where([

                                        ['firstName', '=',  $results[0][0][$k]],['lastName', '=', $results[0][1][$k]],
                                        ['party', '=',  $results[0][2][$k]],['state', '=', $this->getState($results[0][3][$k])]
                                
                                    ])->first();
                                  
                                    if(!($candidate)){
                                            $candidate = new Candidates;
                                            $candidate->firstName = $results[0][0][$k];
                                            $candidate->lastName = $results[0][1][$k];
                                            $candidate->party = $results[0][2][$k];
                                            $candidate->type = ($results[0][5][$k] == 'U.S. Senate') ? 1 : 2;
                                            $candidate->state = $this->getState($results[0][3][$k]);
                                            $candidate->district = $results[0][4][$k] ? $results[0][4][$k] : 0 ; 
                                            $candidate->status = "Y";
                                            $candidate->save();
                                            $c_id =$candidate->id;
                                            }

                                    else{
                                        $c_id= $candidate->id;
                                    }
                                    array_push($candidate_ids,$c_id);
                                        }
                                    }
                                }
                                
                            }
					
                
                            if($result[0] != ""){
                
                                $parent = $result[0];
                            }
                            if($result[1] != ""  ){
                                
                                $child = $result[1];
                            }
                            // use App\Models\Answers;
                            // use App\Models\Statements;
                            // use App\Models\Questions;
                            // use App\Models\Candidates;
                            // use App\Models\State;
                            
                            if($child != "" && $key > 5  ){
                
                                $cat = Category::where('category_name','=',$parent)->first();
                                //dd( $cat );
                                if(!($cat)){
                                    $main = "";
                                    $category = new Category;
                                    $category->category_name = $parent ;
                                    $category->description = $parent ;
                                    $category->status ="Y";
                                    $category->save();
                                    $main = $category->id;
                                }
                                else{
                                  
                                    $main = $cat->id;
                                }
                                
                                $questionsId = 0;
                                $quiz = Questions::where('question','=',$child)->first();
                                if(!(str_contains(strtolower($child), 'statement'))){
                                    
                                    //dd($result[1]);
                                
                                    if ($quiz) { 
                                        $questionsId =  $quiz->id;
                                    }else{
                                    
                                        
                                            $questions = new Questions;
                                            $questions->cat_id =  $main;
                                            $questions->question =$child;
                                            $questions->description = null;
                                            $questions->point = 1;
                                            $questions->type = 1;
                                            $questions->save();
                                            $questionsId = $questions->id;
                                     }
                                    
                                    
                                }else{
                                         if(!$quiz)  {
                                    $questions = new Questions;
                                            $questions->cat_id =  $main;
                                            $questions->question =$child;
                                            $questions->description = null;
                                            $questions->point = 1;
                                            $questions->type = 2;
                                            $questions->save();
                                         }
                                }
                                $u = 0;
                              
                                if (!(str_contains(strtolower($result[1]), 'statement'))) {
                                unset($result[0]);
                                unset($result[1]);
                                $res = array_filter($result, fn($value) => !is_null($value) && $value !== '');
                                $i = 0;
                                foreach($res as $keys => $values){
                                    $anwerId = $this->getAnsweId($questionsId,$values) ;
                                    $candidate_id = $candidate_ids[$i++];
                                    $candidateanswer = Candidateanswer::where([['candidate_id', '=',  $candidate_id],['answer_id', '=', $anwerId]])->first();
                                  
                                    if(!($candidateanswer)){
                                    $candidateanswer = new Candidateanswer();
                                    $candidateanswer->candidate_id = $candidate_id;
                                    $candidateanswer->answer_id = $anwerId;
                                    //print_r($candidateanswer);
                                    $candidateanswer->save();
                                    }
                                }
                               
                                
                                }else{
                                    echo $main;
                                    unset($result[0]);
                                    unset($result[1]);
                                    $resp = array_filter($result, fn($value) => !is_null($value) && $value !== '');
                                    $m = 0;
                                    foreach($resp as $key_s => $value_s){
                                        $candidate_id = $candidate_ids[$m++];
                                        $statements_first = Statements::where([['candidate_id', '=',  $candidate_id],['statement', '=', $value_s]])->first();
                                      
                                        if(!($statements_first)){
                                        $statements = new Statements;
                                        $statements->candidate_id = $candidate_id;
                                        $statements->question_id = $main;
                                        $statements->statement = $value_s;
                                        $statements->description = "";
                                        $statements->point = "0";
                                        $statements->type =$this->candidateId($candidate_id);
                                        $statements->save() ;
                                        }
                                    
                                $i++;
                               
                             }
                                }
                    
                            } 
        
        }
           

    }
        return redirect()->route('users.import-users')->with('success', 'Data Imported Successfully');
}

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    private function getState($str){
        $stateId = State::where('state_name','=',$str)->first();
        if($stateId){
          $id =  $stateId->id;
        }else{
            $state = new State;
            $state->state_name =$str;
            $state->status ='Y';
            $state->save();
            $id = $state->id;

        }
        return $id;
    }
	
	private function getAnsweId($questionId, $str){
		
        $AnwerId = Answers::where('answer','=',$str)->first();
        if($AnwerId){
          $id =  $AnwerId->id;
        }else{
			$answers = new Answers();
			$answers->question_id = $questionId;
			$answers->answer = $str;
			$answers->status ='Y';
			$answers->save();
			$id = $answers->id;
        }
        return $id;
    }
	
	private function candidateId($id){
        $cid = Candidates::select('type')->where('id','=',$id)->first();
        return $cid->type ? $cid->type :0;
    }

}


