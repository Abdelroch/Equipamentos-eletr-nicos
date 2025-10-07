<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MainController extends Controller
{
    public function index(){
        $data['products_featured'] = Product::orderByDesc('created_at')->take(10)->get();
        $data['products_good'] = Product::orderByDesc('created_at')->where('status', 'Bom')->get();
        $data['products_very_good'] = Product::orderByDesc('created_at')->where('status', 'Extremamente Bom')->where('categoria','laptops')->get();
        $data['products_monitors'] = Product::orderByDesc('created_at')->where('status', 'Extremamente Bom')->where('categoria', 'monitors')->get();
        $data['products_sold'] = Product::orderByDesc('created_at')->where('estado_venda', 'vendido')->get();
        $data['products_carcass'] = Product::orderByDesc('created_at')->where('status','Irreparável')->get();
        return view("index", $data);
    }

    public function product_details($product_slug){
        $data['product'] = Product::where("slug", $product_slug)->firstOrFail();
        return view('visitor.product-details', $data);

    }

    public function customer_create_account(Request $request)
    {
        // Validação dos dados do formulário
        $validated = $request->validate([
            'nif' => 'required|string|max:50|unique:customer,nif',
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Iniciar transação para garantir consistência
            DB::beginTransaction();

            // Criar usuário na tabela users
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'access_level' => 'customer',
            ]);

            // Criar cliente na tabela customers
            Customer::create([
                'user_id' => $user->id,
                'nif' => $validated['nif'],
                'birth_date' => $validated['birth_date'],
                'phone_number' => $validated['phone_number'],
            ]);

            // Confirmar transação
            DB::commit();

            // Redirecionar com mensagem de sucesso
            return redirect()->route('login')->with('success', 'Conta criada com sucesso! Faça login.');

        } catch (\Exception $e) {
            // Reverter transação em caso de erro
            DB::rollBack();

           dd($e->getMessage());
            // Retornar erro
            throw ValidationException::withMessages([
                'email' => 'Ocorreu um erro ao criar a conta. Tente novamente.',
            ]);
        }
    }

    public function store(){
        $data['products_featured'] = Product::orderByDesc('created_at')->take(10)->get();
        $data['products_good'] = Product::orderByDesc('created_at')->where('status', 'Bom')->get();
        $data['products_very_good'] = Product::orderByDesc('created_at')->where('status', 'Extremamente Bom')->paginate(12);
        $data['products_monitors'] = Product::orderByDesc('created_at')->where('status', 'Extremamente Bom')->where('categoria', 'monitors')->get();
        $data['products_sold'] = Product::orderByDesc('created_at')->where('estado_venda', 'vendido')->get();
        $data['products_carcass'] = Product::orderByDesc('created_at')->where('status', 'Irreparável')->get();
        return view('visitor.store', $data);
    }

}
