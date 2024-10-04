<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        $category = new Category();
        return view('dashboard.categories.create', compact('parents','category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clean_data = $request->validate(Category::rules(),[
            'required' => 'this field is (:attribute) required',
            'unique' => 'this name is already exists',
        ]);
        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);

        $data = $request->except('imge');

        $data['imge'] = $this->uploadImage($request);


        $category = Category::create($data);

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
    public function update(CategoryRequest $request, $id)
    {

        $category = Category::findOrFail($id);

        $old_image = $category->image;

        $data = $request->except('imge');


        $new_image = $this->uploadImage($request);
        if($new_image){
            $data['imge'] = $new_image;
        }


        $category->update($data);

        if( $old_image && $new_image){
            Storage::disk('public')->delete($old_image);
        }

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
         $category = Category::findOrFail($id);
        // $category->delete();

        // or
        // Category::where('id', '=' ,$id)->delete();

        // or
        Category::destroy($id);

        if($category->imge){
            Storage::disk('public')->delete($category->imge);
        }

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category Delete!');
    }
    protected function uploadImage(Request $request){
        if(!$request->hasFile('imge')){
            return;
        }
        $file = $request->file('imge');
        $path = $file->store('uploads',[
            'disk' =>'public'
        ]);
         return $path;

    }
}