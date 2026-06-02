<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\Carrito;

class CartController extends Controller
{
    private function mergeSessionCart()
    {
        if (Auth::check() && session()->has('carrito')) {
            $sessionCart = session()->get('carrito');
            $ci = Auth::user()->ci;

            foreach ($sessionCart as $idproducto => $item) {
                $dbItem = Carrito::where('ci_usuario', $ci)->where('idproducto', $idproducto)->first();
                if ($dbItem) {
                    $dbItem->cantidad += $item['cantidad'];
                    $dbItem->save();
                } else {
                    Carrito::create([
                        'ci_usuario' => $ci,
                        'idproducto' => $idproducto,
                        'cantidad' => $item['cantidad']
                    ]);
                }
            }
            session()->forget('carrito');
        }
    }

    public function index()
    {
        $this->mergeSessionCart();

        $cartItems = [];
        $total = 0;

        if (Auth::check()) {
            $items = Carrito::with('producto')->where('ci_usuario', Auth::user()->ci)->get();
            foreach ($items as $item) {
                if ($item->producto) {
                    $subtotal = $item->producto->precio * $item->cantidad;
                    $cartItems[] = [
                        'idproducto' => $item->idproducto,
                        'nombre' => $item->producto->nombre,
                        'precio' => $item->producto->precio,
                        'cantidad' => $item->cantidad,
                        'subtotal' => $subtotal
                    ];
                    $total += $subtotal;
                }
            }
        } else {
            $sessionCart = session()->get('carrito', []);
            foreach ($sessionCart as $idproducto => $item) {
                $producto = Producto::find($idproducto);
                if ($producto) {
                    $subtotal = $producto->precio * $item['cantidad'];
                    $cartItems[] = [
                        'idproducto' => $idproducto,
                        'nombre' => $producto->nombre,
                        'precio' => $producto->precio,
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $subtotal
                    ];
                    $total += $subtotal;
                }
            }
        }

        return view('carrito.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $idproducto = $request->input('idproducto');
        $cantidad = max(1, (int)$request->input('cantidad', 1));
        
        $producto = Producto::findOrFail($idproducto);

        // Check if adding this quantity exceeds available stock
        $currentCartQuantity = 0;
        if (Auth::check()) {
            $ci = Auth::user()->ci;
            $dbItem = Carrito::where('ci_usuario', $ci)->where('idproducto', $idproducto)->first();
            if ($dbItem) $currentCartQuantity = $dbItem->cantidad;
        } else {
            $cart = session()->get('carrito', []);
            if (isset($cart[$idproducto])) $currentCartQuantity = $cart[$idproducto]['cantidad'];
        }

        if ($currentCartQuantity + $cantidad > $producto->cantidad) {
            $availableToAdd = max(0, $producto->cantidad - $currentCartQuantity);
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. Solo puedes añadir ' . $availableToAdd . ' unidad(es) más.'
                ]);
            }
            return redirect()->back()->with('error', 'Stock insuficiente.');
        }

        if (Auth::check()) {
            $ci = Auth::user()->ci;
            $dbItem = Carrito::where('ci_usuario', $ci)->where('idproducto', $idproducto)->first();
            
            if ($dbItem) {
                $dbItem->cantidad += $cantidad;
                $dbItem->save();
            } else {
                Carrito::create([
                    'ci_usuario' => $ci,
                    'idproducto' => $idproducto,
                    'cantidad' => $cantidad
                ]);
            }
        } else {
            $cart = session()->get('carrito', []);
            if (isset($cart[$idproducto])) {
                $cart[$idproducto]['cantidad'] += $cantidad;
            } else {
                $cart[$idproducto] = [
                    'cantidad' => $cantidad
                ];
            }
            session()->put('carrito', $cart);
        }

        if ($request->ajax()) {
            $cartCount = Auth::check() 
                ? Carrito::where('ci_usuario', Auth::user()->ci)->sum('cantidad')
                : collect(session()->get('carrito', []))->sum('cantidad');
                
            return response()->json([
                'success' => true, 
                'cartCount' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request)
    {
        $idproducto = $request->input('idproducto');
        $cantidad = max(1, (int)$request->input('cantidad', 1));

        $producto = Producto::findOrFail($idproducto);

        // Check if requested quantity exceeds available stock
        if ($cantidad > $producto->cantidad) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. Solo hay ' . $producto->cantidad . ' unidad(es) disponibles.',
                    'revertTo' => $producto->cantidad // Opcional, para revertir el input si quisiéramos
                ]);
            }
            return redirect()->back()->with('error', 'Stock insuficiente.');
        }

        if (Auth::check()) {
            $ci = Auth::user()->ci;
            $dbItem = Carrito::where('ci_usuario', $ci)->where('idproducto', $idproducto)->first();
            if ($dbItem) {
                $dbItem->cantidad = $cantidad;
                $dbItem->save();
            }
        } else {
            $cart = session()->get('carrito', []);
            if (isset($cart[$idproducto])) {
                $cart[$idproducto]['cantidad'] = $cantidad;
                session()->put('carrito', $cart);
            }
        }

        if ($request->ajax()) {
            $cartCount = 0;
            $total = 0;
            $subtotal = 0;

            if (Auth::check()) {
                $cartCount = Carrito::where('ci_usuario', Auth::user()->ci)->sum('cantidad');
                $items = Carrito::with('producto')->where('ci_usuario', Auth::user()->ci)->get();
                foreach ($items as $item) {
                    if ($item->producto) {
                        $itemSub = $item->producto->precio * $item->cantidad;
                        $total += $itemSub;
                        if ($item->idproducto == $idproducto) $subtotal = $itemSub;
                    }
                }
            } else {
                $cart = session()->get('carrito', []);
                foreach ($cart as $id => $item) {
                    $cartCount += $item['cantidad'];
                    $prod = Producto::find($id);
                    if ($prod) {
                        $itemSub = $prod->precio * $item['cantidad'];
                        $total += $itemSub;
                        if ($id == $idproducto) $subtotal = $itemSub;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'cartCount' => $cartCount,
                'subtotal' => number_format($subtotal, 2, '.', ''),
                'total' => number_format($total, 2, '.', '')
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Carrito actualizado.');
    }

    public function remove(Request $request)
    {
        $idproducto = $request->input('idproducto');

        if (Auth::check()) {
            Carrito::where('ci_usuario', Auth::user()->ci)->where('idproducto', $idproducto)->delete();
        } else {
            $cart = session()->get('carrito', []);
            if (isset($cart[$idproducto])) {
                unset($cart[$idproducto]);
                session()->put('carrito', $cart);
            }
        }

        if ($request->ajax()) {
            $cartCount = 0;
            $total = 0;

            if (Auth::check()) {
                $cartCount = Carrito::where('ci_usuario', Auth::user()->ci)->sum('cantidad');
                $items = Carrito::with('producto')->where('ci_usuario', Auth::user()->ci)->get();
                foreach ($items as $item) {
                    if ($item->producto) {
                        $total += $item->producto->precio * $item->cantidad;
                    }
                }
            } else {
                $cart = session()->get('carrito', []);
                foreach ($cart as $id => $item) {
                    $cartCount += $item['cantidad'];
                    $prod = Producto::find($id);
                    if ($prod) {
                        $total += $prod->precio * $item['cantidad'];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'cartCount' => $cartCount,
                'total' => number_format($total, 2, '.', '')
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function clear()
    {
        if (Auth::check()) {
            Carrito::where('ci_usuario', Auth::user()->ci)->delete();
        } else {
            session()->forget('carrito');
        }

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }

    public function checkout()
    {
        $this->mergeSessionCart();

        // Verificar que hay items
        $items = Carrito::where('ci_usuario', Auth::user()->ci)->get();
        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        // Descontar la cantidad de stock en cada producto
        foreach ($items as $item) {
            $producto = Producto::find($item->idproducto);
            if ($producto) {
                // Previene que quede en negativo si hay menos stock del pedido
                $producto->cantidad = max(0, $producto->cantidad - $item->cantidad);
                $producto->save();
            }
        }

        // Vaciar el carrito ya que la compra fue "hecha"
        Carrito::where('ci_usuario', Auth::user()->ci)->delete();

        return view('carrito.success');
    }

    public function generarCotizacion()
    {
        $this->mergeSessionCart();

        $cartItems = [];
        $total = 0;

        if (Auth::check()) {
            $items = Carrito::with('producto')->where('ci_usuario', Auth::user()->ci)->get();
            foreach ($items as $item) {
                if ($item->producto) {
                    $subtotal = $item->producto->precio * $item->cantidad;
                    $cartItems[] = [
                        'idproducto' => $item->idproducto,
                        'nombre' => $item->producto->nombre,
                        'precio' => $item->producto->precio,
                        'cantidad' => $item->cantidad,
                        'subtotal' => $subtotal
                    ];
                    $total += $subtotal;
                }
            }
        } else {
            $sessionCart = session()->get('carrito', []);
            foreach ($sessionCart as $idproducto => $item) {
                $producto = Producto::find($idproducto);
                if ($producto) {
                    $subtotal = $producto->precio * $item['cantidad'];
                    $cartItems[] = [
                        'idproducto' => $idproducto,
                        'nombre' => $producto->nombre,
                        'precio' => $producto->precio,
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $subtotal
                    ];
                    $total += $subtotal;
                }
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío, no se puede generar la cotización.');
        }

        return view('cotizacion.imprimir', compact('cartItems', 'total'));
    }
}
