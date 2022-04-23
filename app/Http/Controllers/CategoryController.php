<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryController extends Controller
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

        return view('category.index', [
            'category' => $category
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

        return view('category.add', ['permissions' => $permissions]);
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
                'category_name' => 'required'
            ]);
    
            Category::create($request->all());

            DB::commit();
            return redirect()->route('category.index')->with('success','category created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            
            return redirect()->route('category.create')->with('error',$th->getMessage());
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
        $category = Category::whereId($id)->first();
        

        return view('category.edit', ['category' => $category]);
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
                'category_name' => 'required'
            ]);
            
            $category = Category::whereId($id)->first();

            $category->category_name = $request->category_name;
            $category->description = $request->description;
            $category->save();

            
            DB::commit();
            return redirect()->route('category.index')->with('success','category updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('category.edit',['category' => $category])->with('error',$th->getMessage());
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
    
            Category::whereId($id)->delete();
            
            DB::commit();
            return redirect()->route('category.index')->with('success','category deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('category.index')->with('error',$th->getMessage());
        }
    }
}
