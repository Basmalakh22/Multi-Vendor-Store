<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all(); //return all categories
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::all();
        return view('dashboard.categories.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->input('name');
        // $request->post('name');
        // $request->query('name');
        // $request->get('name');
        // $request->name;
        // $request['name'];

        // $request->all();
        // $request->only(['name','parent_id']);
        // $request->expect(['image','status']);

        // $category = new Category($request->all());
        // $category->save();

        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);

        $category = Category::create($request->all());

        // PRG  => Post Redirect Get
        // return redirect()->route('categories.index');
        return Redirect::route('dashboard.categories.index')->with('success', 'Category Created!');
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
        $category = Category::findOrFail($id);
        // if(!$category){
        //     abort(404);
        // }

        // select from categories where id <> $id and (parent_ IS NULL or parent_id <> $id)
        $parents = Category::where('id', '<>', $id)
            ->where(function($query) use($id){
                $query->whereNull('parent_id')
                      ->orWhere('parent_id', '<>', $id);

            })
            
            ->get();
        return view('dashboard.categories.edit', compact('category', 'parents'));
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
        $category = Category::findOrFail($id);

        $category->update($request->all());

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $category = Category::findOrFail($id);
        // $category->delete();

        // or
        // Category::where('id', '=' ,$id)->delete();

        // or
        Category::destroy($id);

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category Delete!');
    }
}
