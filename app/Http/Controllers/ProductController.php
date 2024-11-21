<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Fault;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $day = $request->input('day', date('Y-m-d'));

        $items = Product::all();
        $faults = Fault::selectRaw('product_id, SUM(quantity) as total_faults')
            ->whereRaw('DATE_FORMAT(faults.date, "%Y-%m-%d") = ?', [$day])
            ->groupBy('product_id')
            ->get();

        $salesByDate = Sale::selectRaw('DATE(sales.date) as date, total_sale, quantity, product_id')
            ->whereRaw('DATE_FORMAT(sales.date, "%Y-%m-%d") = ?', [$day])
            ->groupBy('date', 'quantity', 'product_id', 'total_sale')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($sale) {
                $sale->product_name = Product::find($sale->product_id)->name;
                return $sale;
            });

        $salesData = Sale::selectRaw('date, SUM(total_sale) as total_sales, SUM(profit) as total_profit')
            ->whereRaw('DATE_FORMAT(sales.date, "%Y-%m-%d") = ?', [$day])
            ->groupBy('date')
            ->get();

        if ($salesData->isEmpty()) {
            $salesData = collect([ (object) ['total_sales' => 0, 'total_profit' => 0] ]);
        }

        return view('products.index', compact('items', 'salesByDate', 'day', 'salesData', 'faults'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $request->input('name');
        $product->quantity = $request->input('quantity');
        $product->cost = $request->input('cost');
        $product->price = $request->input('price');
        $product->save();

        return redirect()->back()->with('success', 'Producto actualizado correctamente.');
    }

    public function addFault(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::find($request->input('product_id'));
        $faultQuantity = $request->input('quantity');

        if ($product->quantity < $faultQuantity) {
            return redirect()->back()->withErrors(['quantity' => 'La cantidad de averías no puede ser mayor que la cantidad disponible.']);
        }

        $fault = Fault::create([
            'product_id' => $product->id,
            'quantity' => $faultQuantity,
            'date' => Carbon::now(),
        ]);

        // $product->faults += $faultQuantity;
        $product->quantity -= $faultQuantity;
        $product->save();
        $fault->save();

        return redirect()->route('products.index')->with('success', 'Avería registrada exitosamente.');
    }

    public function addEntry(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::find($request->input('product_id'));
        $entryQuantity = $request->input('quantity');

        $product->quantity += $entryQuantity;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Entrada registrada exitosamente.');
    }

    public function registerSale(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::find($request->input('product_id'));
        $final = $request->input('quantity');

        if ($product->quantity < $final) {
            return redirect()->back()->withErrors(['quantity' => 'No hay suficiente cantidad disponible para vender.']);
        }

        $saleQuantity = $product->quantity - $final;

        // Actualizar inventario
        $product->quantity = $final;
        $product->save();

        // Calcular la ganancia

        $profit = ($product->price - $product->cost) * $saleQuantity;

        // Registrar la venta
        Sale::create([
            'product_id' => $product->id,
            'total_sale' => $product->price * $saleQuantity,
            'date' => Carbon::now(),
            'quantity' => $saleQuantity,
            'profit' => $profit,
        ]);

        return redirect()->route('products.index')->with('success', 'Venta registrada exitosamente.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Producto eliminado correctamente.');
    }
}