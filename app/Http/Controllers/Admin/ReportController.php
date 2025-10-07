<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Log;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function financialReport(Request $request)
    {
        $query = Budget::query();

        // Aplicar filtros
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', Carbon::parse($request->start_date));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', Carbon::parse($request->end_date));
        }
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $transactions = $query->with('user')->get();
        $totalRevenue = $query->where('transaction_type', 'Receita')->sum('amount');
        $totalExpense = $query->where('transaction_type', 'Despesa')->sum('amount');

        // Resumo por tipo de transação
        $transactionSummary = $transactions->groupBy('transaction_type')->map->count()->toArray();

        $data = [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalExpense' => $totalExpense,
            'filters' => $request->only(['start_date', 'end_date', 'transaction_type', 'category']),
            'transactionSummary' => $transactionSummary,
            'reportDate' => Carbon::now()->format('d/m/Y H:i:s'),
        ];

        // Exportar para PDF
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.financial.pdf', ['data' => $data])
                     ->setPaper('a4', 'landscape');
            return $pdf->download('relatorio_financeiro_completo.pdf');
        }

        $meses = array_keys($vendasPorMes);
        $valores = array_values($vendasPorMes);

        return view('admin.reports.financial', compact('transactions', 'totalRevenue', 'totalExpense', 'meses', 'valores'));
    }

    public function productReportByStatus(Request $request, $status = null)
    {
        if (!$status) {
            return redirect()->back()->with('error', 'Por favor, especifique um status para gerar o relatório.');
        }

        $filters = [
            'search' => $request->input('search'),
            'status' => $status,
            'category' => $request->input('category'),
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
        ];

        $query = Product::query()
            ->join('supplier', 'product.id_fornecedor', '=', 'supplier.id')
            ->select('product.*', 'supplier.nome as nome_fornecedor')
            ->orderBy('product.id', 'desc');

        // Aplicar filtros
        if ($filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('product.nome', 'like', "%{$filters['search']}%")
                  ->orWhere('product.descricao', 'like', "%{$filters['search']}%")
                  ->orWhere('product.status', 'like', "%{$filters['search']}%");
            });
        }

        $query->where('product.status', $status);

        if ($filters['category']) {
            $query->where('product.categoria', $filters['category']);
        }

        if ($filters['date_start']) {
            $query->whereDate('product.created_at', '>=', $filters['date_start']);
        }

        if ($filters['date_end']) {
            $query->whereDate('product.created_at', '<=', $filters['date_end']);
        }

        $produtos = $query->get();

        // Resumo por status
        $statusSummary = $produtos->groupBy('status')->map->count()->toArray();

        // Dados para o relatório
        $data = [
            'produtos' => $produtos,
            'filters' => $filters,
            'statusSummary' => $statusSummary,
            'reportDate' => Carbon::now()->format('d/m/Y H:i:s'),
        ];

        // Exportar para PDF
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.products.pdf', ['data' => $data])
                     ->setPaper('a4', 'landscape')
                     ->setOption(['enable_remote' => true, 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            return $pdf->download("relatorio_produtos_{$status}_completo.pdf");
        }

        // Exportar para Excel
        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new ProductExport($filters), "relatorio_produtos_{$status}.xlsx");
        }

        // Log da exportação
        if ($request->has('export')) {
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Exportação de Relatório',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} exportou um relatório de produtos com status {$status} em formato " . ($request->export === 'pdf' ? 'PDF' : 'Excel') . ".",
            ]);
        }

        return view('admin.reports.products.index', ['data' => $data, 'filters' => $filters]);
    }

    // Bots para cada status
    public function reportBom(Request $request)
    {
        return $this->productReportByStatus($request, 'Extremamente Bom');
    }

    public function reportAvariado(Request $request)
    {
        return $this->productReportByStatus($request, 'Avariado');
    }

    public function reportEmAnalise(Request $request)
    {
        return $this->productReportByStatus($request, 'Em Análise');
    }

    public function reportReparado(Request $request)
    {
        return $this->productReportByStatus($request, 'Reparado');
    }

    public function reportIrreparavel(Request $request)
    {
        return $this->productReportByStatus($request, 'Irreparável');
    }
     public function productReport(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'category' => $request->input('category'),
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
        ];

        $query = Product::query()
            ->join('supplier', 'product.id_fornecedor', '=', 'supplier.id')
            ->select('product.*', 'supplier.nome as nome_fornecedor')
            ->orderBy('product.id', 'desc');

        // Aplicar filtros
        if ($filters['search']) {
            $query->where(function ($q) use ($filters) {
                $q->where('product.nome', 'like', "%{$filters['search']}%")
                  ->orWhere('product.descricao', 'like', "%{$filters['search']}%")
                  ->orWhere('product.status', 'like', "%{$filters['search']}%");
            });
        }

        // Gerar relatório apenas se um status específico for selecionado (diferente de vazio ou "Todos")
        if ($filters['status'] && $filters['status'] !== 'Todos' && $filters['status'] !== '') {
            $query->where('product.status', $filters['status']);
        } else {
            // Se não houver status específico, não filtra por status e desativa exportação PDF
            $filters['status'] = null;
        }

        if ($filters['category']) {
            $query->where('product.categoria', $filters['category']);
        }

        if ($filters['date_start']) {
            $query->whereDate('product.created_at', '>=', $filters['date_start']);
        }

        if ($filters['date_end']) {
            $query->whereDate('product.created_at', '<=', $filters['date_end']);
        }

        $produtos = $query->get();

        // Resumo por status
        $statusSummary = $produtos->groupBy('status')->map->count()->toArray();

        // Dados para o relatório
        $data = [
            'produtos' => $produtos,
            'filters' => $filters,
            'statusSummary' => $statusSummary,
            'reportDate' => Carbon::now()->format('d/m/Y H:i:s'),
        ];

        // Exportar para PDF apenas se status for definido
        if ($request->has('export') && $request->export === 'pdf' && $filters['status']) {
            $pdf = Pdf::loadView('admin.reports.products.pdf', ['data' => $data])
                     ->setPaper('a4', 'landscape')
                     ->setOption(['enable_remote' => true, 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            return $pdf->download('relatorio_produtos_completo.pdf');
        }

        // Exportar para Excel (pode ser sempre permitido, mas respeitará os filtros)
        if ($request->has('export') && $request->export === 'excel') {
            return Excel::download(new ProductExport($filters), 'relatorio_produtos.xlsx');
        }

        // Log da exportação
        if ($request->has('export')) {
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Exportação de Relatório',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} exportou um relatório de produtos em formato " . ($request->export === 'pdf' ? 'PDF' : 'Excel') . ".",
            ]);
        }

        return view('admin.reports.products.index', ['data' => $data, 'filters' => $filters]);
    }

    public function labelsReport(Request $request)
    {
        $query = Product::query()
            ->select('product.id', 'product.nome', 'product.status')
            ->orderBy('product.id', 'asc');

        if ($request->has('status') && $request->status !== '') {
            $query->where('product.status', $request->status);
        }

        $data['produtos'] = $query->get();

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.labels.pdf', ['data' => $data])
                ->setPaper('a4', 'landscape')
                ->setOption([
                    'dpi' => 96,
                    'defaultFont' => 'DejaVu Sans',
                    'isPhpEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);
            return $pdf->download('relatorio_rotulos.pdf');
        }

        if ($request->has('export')) {
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Exportação de Relatório',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} exportou um relatório de rótulos em formato PDF.",
            ]);
        }

        return view('admin.reports.labels.index', ['data' => $data, 'filters' => ['status' => $request->status ?? '']]);
    }

    // Novo método para Relatório de Preços
    public function pricesReport(Request $request)
    {
        $query = Product::query()
            ->select('product.id', 'product.nome', 'product.status', 'product.preco', 'product.descricao')
            ->where('status','Extremamente Bom')
            ->orderBy('product.id', 'asc');

        // Filtro opcional por status
        if ($request->has('status') && $request->status !== '') {
            $query->where('product.status', $request->status);
        }

        $data['produtos'] = $query->get();

        // Exportar para PDF
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.prices.pdf', ['data' => $data])
                ->setPaper('a4', 'landscape'); // A4 horizontal
            return $pdf->download('relatorio_precos.pdf');
        }

        // Registrar log de exportação
        if ($request->has('export')) {
            $user_id = auth()->id();
            Log::create([
                'user_id' => $user_id,
                'ip' => $request->ip(),
                'accao' => 'Exportação de Relatório',
                'id_user' => $user_id,
                'descricao' => "Usuário {$user_id} exportou um relatório de preços em formato PDF.",
            ]);
        }

        return view('admin.reports.prices.index', ['data' => $data, 'filters' => ['status' => $request->status ?? '']]);
    }
}
