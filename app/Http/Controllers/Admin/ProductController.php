<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return Application|Factory|View|Response
   */
  public function index(Request $request): View
  {
    $name = $request->get('name', null);
    $products = Product::query()->withTrashed();
    if ($name) {
      $products = $products->where('title', 'like', '%' . $name . '%');
    }
    $products = $products->paginate(10);
    $filter = ['name' => $name];
    return view('admin.product.index', compact('products', 'filter'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Response
   */
  public function store(Request $request)
  {
      //
  }

  /**
   * Display the specified resource.
   *
   * @param Product $product
   * @return Response
   */
  public function show(Product $product)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param Product $product
   * @return Application|Factory|View|Response
   */
  public function edit(Product $product)
  {
    return view('admin.product.edit', compact('product'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param Product $product
   * @return RedirectResponse
   */
  public function update(Request $request, Product $product): RedirectResponse
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'price' => 'required|integer|min:0',
      'weight' => 'required|min:0',
      'brand' => 'required|exists:brands,id',
      'category' => 'required|exists:categories,id',
      'meta_title' => 'required|string',
      'meta_description' => 'required|string',
      'description' => 'required',
    ]);
    return redirect()->back()->with('success', ['Товар успешно обновлён']);
  }

  /**
   * Удаление или востановление товара.
   *
   * @param int $id
   * @return RedirectResponse
   */
  public function destroy(int $id): RedirectResponse
  {
    $product = Product::withTrashed()->find($id);
    if ($product->trashed()) {
      $product->restore();
      return redirect()->back()->with('success', ['Товар успешно востановлен']);
    } else {
      try {
        $product->delete();
        return redirect()->back()->with('success', ['Товар успешно удалён']);
      } catch (Exception $exception) {
        return redirect()->back()->withErrors($exception->getMessage());
      }
    }
  }
}
