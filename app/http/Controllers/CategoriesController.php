<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Categories\Models\Category;
use Yajra\Datatables\Datatables;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp;

class CategoriesController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index() {
        return view('categories::layouts.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
            return view('categories::layouts.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request) {
        $validator = $request->validate([
            'en.name' => 'bail|required|max:255',
            'en.tag' => 'required|max:255',
            'ar.name' => 'required|max:255',
            'ar.tag' => 'required|max:255',
        ]);
        $category = new Category();
//        $category->admin_id = auth('admin')->user()->id;
        $category->is_active = 1;
        $category->model = $request->model;
        $category->save();
        $attributes = $request->only(['ar', 'en']);
        $category->translateAttributes($attributes);
        $request->request->add(['id' => $category->id]);
        $request->request->add(['admin_name' => auth('admin')->user()->name]);
        $category = Category::findOrFail($category->id);
        return redirect(route('categories.index'))->with(['success' => 'Category Created']);

    }

    /**
     * Show the specified resource.
     * @param $id
     * @return Response
     */
    public function show($id) {
        $category = Category::withTrashed()->find($id);
        if (!$category) {
            return redirect(route('categories.index'))->with(['error' => 'Category Not Found']);
        }
        return view('categories::layouts.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param $categoryId
     * @return Response
     */
    public function edit($categoryId) {
        $category = Category::whereNull('deleted_at')->find($categoryId);
        if (!$category) {
            return redirect(route('categories.index'))->with(['error' => 'Category Not Found']);
        }
        return view('categories::layouts.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     * @param Category $category
     * @param  Request $request
     * @return Response
     */
    public function update(Category $category, Request $request) {
        $validator = $request->validate([
            'en.name' => 'bail|required|max:255',
            'en.tag' => 'required|max:255',
            'ar.name' => 'required|max:255',
            'ar.tag' => 'required|max:255',
        ]);
        $category->is_active = 1;
        $category->model = $request->model;
        $category->save();
        $category->deleteTranslations();
        $attributes = $request->only(['ar', 'en']);
        $category->translateAttributes($attributes);
        $request->request->add(['id' => $category->id]);
        return redirect(route('categories.index'))->with(['success' => 'Category Updated']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id) {
        if (auth('admin')->user()->role_id == 1) {
            $category = Category::find($id);
            if (!$category) {
                return redirect(route('categories.index'))->with(['error' => 'Category Not Found']);
            }
            $category->delete();
            $record = BootForm::linkOfshow('categories.show', $category->id, $category->name);
            $process = "Record on categories was deleted ( $record ) by " . auth('admin')->user()->name;
            return redirect(route('categories.index'))->with(['success' => 'Category Deleted']);
        }

    }

    public function delete($id) {
            $category = Category::find($id);
            if ($category) {
                return redirect(route('categories.index'))->with(['error' => 'Category Not Found']);
            }
            $category->forceDelete();
            return redirect(route('categories.index'))->with(['success' => 'Category Deleted']);
    }

    public function datatable() {
        $category = Category::get();
        return Datatables::of($category)
            ->editColumn('name', function ($model) {
                return $model->name;
            })
            ->addColumn('action', function ($model) {
                if ($model->is_active) {
                    $status = 'fa-times-circle';
                } else {
                    $status = 'fa-check-circle-o';
                }
                $visible = false;
                if (auth('admin')->user()->role_id == 1) {
                    $visible = true;
                }
                $edit_page = false;
                if (auth('admin')->user()->role_id == 1 || auth('admin')->user()->role_id == 2) {
                    $edit_page = true;
                }
                return '' . BootForm::linkOfPermanentDelete('categories.destroy', $model->id, $model->name, 'link', true, '', $visible) . '
                            ' . BootForm::linkOfedit('categories.edit', $model->id, $model->name, $edit_page) . '
                             ' . BootForm::linkOfshow('categories.show', $model->id, $model->name) . '
                              ' . BootForm::link('categories/' . $model->id . '/status', '', 'primary', $status);
//                               ' . BootForm::linkOfDelete('categories.delete', $model->id, $model->name) . ' ';

            })
            ->make(true);
    }


    public function is_active($id) {
        if (Category::find($id)) {
            $category = Category::find($id);
            if ($category->is_active) {
                $category->is_active = 0;
                $category->save();
            } else {
                $category->is_active = 1;
                $category->save();
            }
            return redirect(route('categories.index'));
        }

    }

    public function excel() {
        $category = Category::all();
        return view('categories::layouts.excel', compact('category'));
    }

    public function export() {
        //  $category = $export->view();
        //dd($export);
        return Excel::download(new CategoryExport, 'categories.xlsx');
    }

}
