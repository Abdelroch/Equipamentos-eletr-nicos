<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'clientes' => Customer::count(),
            'produtos'      => Product::count(),
            'fornecedores'  => Supplier::count(),
            'vendas'        => Sale::count(),
            'funcionarios'  => Employee::count(),
            'projetos'      => Project::count(),
            'financeiros'   => Financial::count(),
            'contratos'     => Contract::count(),
            'ordens_producao' => ProductionOrder::count(),
            'impostos'      => Tax::count(),
            'beneficios'    => Benefit::count(),
            'idh_metricas'  => IdhMetric::count(),
            'atividades'    => Log::count(),

            // Antes: Sale::join('product', ...)->sum('sale.quantidade * product.preco')
            // Isso recalculava a receita com o PREÇO ATUAL do produto, ignorando
            // preço negociado e mudando retroativamente sempre que o produto era
            // editado. Além disso, um INNER JOIN com product fazia desaparecer do
            // total qualquer venda cujo produto tenha sido apagado depois.
            // sale.total já é o valor real gravado no momento da venda — é a
            // fonte de verdade, sem depender do estado atual do produto.
            'total_vendas' => (float) (Sale::sum('total') ?? 0),

            // Antes: Budget::sum('balance') — 'balance' é um snapshot do saldo
            // ACUMULADO em cada transação, não um valor a somar entre registos.
            // Somar 'balance' multiplica o saldo real por quantos registos existem.
            // 'amount' é o valor assinado de cada movimento (+receita / -despesa),
            // que é exatamente como o BudgetService já calcula o saldo internamente.
            'saldo_orcamento' => (float) (Budget::sum('amount') ?? 0),

            'media_idh'     => IdhMetric::avg('value') ?? 0,
        ];

        // Antes: Client::latest()->take(5)->get() — Client é o model legado que
        // já foi substituído por Customer em todo o resto do sistema.
        $ultimosClientes = Customer::with('user')->latest()->take(5)->get();

        $ultimosProdutos = Product::latest()->take(5)->get();
        $ultimosFornecedores = Supplier::latest()->take(5)->get();
        $ultimasVendas = Sale::with('customer.user', 'product')->latest()->take(5)->get();
        $ultimosFuncionarios = Employee::latest()->take(5)->get();
        $ultimosProjetos = Project::latest()->take(5)->get();
        $ultimosFinanceiros = Financial::latest()->take(5)->get();
        $ultimosContratos = Contract::latest()->take(5)->get();
        $ultimasOrdensProducao = ProductionOrder::with('product')->latest()->take(5)->get();
        $ultimosImpostos = Tax::latest()->take(5)->get();
        $ultimosBeneficios = Benefit::latest()->take(5)->get();
        $ultimasMetricasIdh = IdhMetric::latest()->take(5)->get();
        $ultimasAtividades = Log::with('user')->latest()->take(5)->get();

        $inicio = Carbon::now()->subMonths(5)->startOfMonth();

        // Antes: precisava de join com product para multiplicar pelo preço atual.
        // Agora soma direto sale.total — mais simples, mais rápido (sem join) e
        // já não perde vendas de produtos entretanto apagados.
        $vendasPorMesRaw = Sale::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('SUM(total) as valor_vendas')
            )
            ->where('created_at', '>=', $inicio)
            ->groupBy('mes')
            ->pluck('valor_vendas', 'mes');

        $meses = [];
        $valoresVendas = [];
        for ($i = 0; $i < 6; $i++) {
            $chave = $inicio->copy()->addMonths($i)->format('Y-m');
            $meses[] = $chave;
            $valoresVendas[] = (float) ($vendasPorMesRaw[$chave] ?? 0);
        }

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
        $data['logs'] = Log::with('user')
            ->orderBy('id', 'desc')
            ->paginate(50);
        return view('admin.logs.table', ['data' => $data]);
    }
}
