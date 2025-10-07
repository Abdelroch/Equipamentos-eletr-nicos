<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Financial;
use App\Models\Contract;
use App\Models\ProductionOrder;
use App\Models\Tax;
use App\Models\Benefit;
use App\Models\IdhMetric;
use App\Models\Log;
use App\Models\Budget;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Request;

class MainController extends Controller
{
    public function index()
    {

        $data = [
            'clientes' => Client::count(),
            'produtos' => Product::count(),
            'fornecedores' => Supplier::count(),
            'vendas' => Product::where('estado_venda', 'vendido')->count(),
            'funcionarios' => Employee::count(),
            'projetos' => Project::count(),
            'financeiros' => Financial::count(),
            'contratos' => Contract::count(),
            'ordens_producao' => ProductionOrder::count(),
            'impostos' => Tax::count(),
            'beneficios' => Benefit::count(),
            'idh_metricas' => IdhMetric::count(),
            'atividades' => Log::count(),
            'total_vendas' => Product::where('estado_venda', 'vendido')
            ->sum(DB::raw('quantidade_disponivel * preco')) ?? 0,
            'total_despesas' => Budget::where('transaction_type', 'Despesa')->sum('amount') ?? 0,
            'total_receitas' => Budget::where('transaction_type', 'Receita')->sum('amount') ?? 0,
            'saldo_orcamento' => Budget::sum('amount') ?? 0,
            'media_idh' => IdhMetric::avg('value') ?? 0,
        ];

        $ultimosClientes = Client::latest()->take(5)->get();
        $ultimosProdutos = Product::latest()->take(5)->get();
        $ultimosFornecedores = Supplier::latest()->take(5)->get();
        $ultimasVendas = Sale::with('client', 'product')->latest()->take(5)->get();
        $ultimosFuncionarios = Employee::latest()->take(5)->get();
        $ultimosProjetos = Project::latest()->take(5)->get();
        $ultimosFinanceiros = Financial::latest()->take(5)->get();
        $ultimosContratos = Contract::latest()->take(5)->get();
        $ultimasOrdensProducao = ProductionOrder::with('product')->latest()->take(5)->get();
        $ultimosImpostos = Tax::latest()->take(5)->get();
        $ultimosBeneficios = Benefit::latest()->take(5)->get();
        $ultimasMetricasIdh = IdhMetric::latest()->take(5)->get();
        $ultimasAtividades = Log::with('user')->latest()->take(5)->get();

        $vendasPorMes = Sale::join('product', 'sale.id_product', 'product.id')
            ->select(
                DB::raw('DATE_FORMAT(sale.created_at, "%Y-%m") as mes'),
                DB::raw('SUM(sale.quantidade * product.preco) as valor_vendas')
            )
            ->where('sale.created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->pluck('valor_vendas', 'mes')
            ->toArray();

        $meses = array_keys($vendasPorMes);
        $valoresVendas = array_values($vendasPorMes);

        return view('admin.dashboard.index', compact(
            'data',
            'ultimosClientes',
            'ultimosProdutos',
            'ultimosFornecedores',
            'ultimasVendas',
            'ultimosFuncionarios',
            'ultimosProjetos',
            'ultimosFinanceiros',
            'ultimosContratos',
            'ultimasOrdensProducao',
            'ultimosImpostos',
            'ultimosBeneficios',
            'ultimasMetricasIdh',
            'ultimasAtividades',
            'meses',
            'valoresVendas',
        ));
    }
        public function list_logs()
    {
        $data['user'] = auth()->user();
        $data['logs'] = Log::join('users', 'log.user_id', 'users.id')
            ->select('log.*', 'users.id as user_id', 'users.name as nome_user')
            ->orderBy('log.id', 'desc')
            ->get();
        return view('admin.logs.table', ['data' => $data]);
    }

    

}
