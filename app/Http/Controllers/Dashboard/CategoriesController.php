<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        if (!Gate::allows('categories.view')) {
            abort(403);
        }
        $request = request();

        // "" BY USING LEFT JOIN "" //

        // SELECT a.* , b.* as parent_name
        // FROM categories as a
        // LEFT JOIN categories as b ON b.id = aparent_id

        // $categories = Category::leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
        // ->select([
        //     'categories.*',
        //     'parents.name as parent_name',
        // ])
        // ->filter($request->query()) // Apply the filter scope
        // ->paginate(); // Paginate the result

        // ----------------------------------------------------------------//

        // "" AFTER USING RELATIONS "" //
        // $categories = Category::with('parent')
        // ->select('categories.*')
        // ->selectRaw('(SELECT COUNT(*) FROM products WHERE status = 'active' AND category_id = categories.id) as products_count')
        // ->filter($request->query()) // Apply the filter scope
        // ->paginate(); // Paginate the result


        // ----------------------------------------------------------------//


        // "" BY USING RELATIONS "" //
        $categories = Category::with('parent')
        ->withCount([
            'products' => function($query){
                $query->where('status', '=' , 'active');
            }
        ])
        ->filter($request->query()) // Apply the filter scope
        ->paginate(); // Paginate the result


        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('categories.create')) {
            abort(403);
        }

        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('categories.create');

        $clean_data = $request->validate(Category::rules(), [
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
    public function show(Category $category)
    {
        if (Gate::denies('categories.view')) {
            abort(403);
        }

        return view('dashboard.categories.show', [

            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Gate::authorize('categories.update');

        $category = Category::findOrFail($id);
        // if(!$category){
        //     abort(404);
        // }

        // select from categories where id <> $id and (parent_ IS NULL or parent_id <> $id)
        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
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
        if ($new_image) {
            $data['imge'] = $new_image;
        }


        $category->update($data);

        if ($old_image && $new_image) {
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
        Gate::authorize('categories.delete');

        $category = Category::findOrFail($id);
        // $category->delete();

        // or
        // Category::where('id', '=' ,$id)->delete();

        // or
        Category::destroy($id);

        // if ($category->imge) {
        //     Storage::disk('public')->delete($category->imge);
        // }

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category Delete!');
    }
    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('imge')) {
            return;
        }
        $file = $request->file('imge');
        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }

    public function trash(){
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));

    }
    public function restore(Request $request ,$id){
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
        ->with('success','Category restored!');
    }
    public function forceDelete($id){
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('dashboard.categories.trash')
        ->with('success','Category deleted forever!');
    }
}
