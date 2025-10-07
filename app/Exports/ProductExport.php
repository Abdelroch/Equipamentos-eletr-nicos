<?php
namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Product::query()
            ->join('supplier', 'product.id_fornecedor', '=', 'supplier.id')
            ->select('product.*', 'supplier.nome as nome_fornecedor');

        // Aplicar filtros
        if ($this->filters['search']) {
            $query->where(function ($q) {
                $q->where('product.nome', 'like', "%{$this->filters['search']}%")
                  ->orWhere('product.descricao', 'like', "%{$this->filters['search']}%")
                  ->orWhere('product.status', 'like', "%{$this->filters['search']}%");
            });
        }

        if ($this->filters['status']) {
            $query->where('product.status', $this->filters['status']);
        }

        if ($this->filters['category']) {
            $query->where('product.categoria', $this->filters['category']);
        }

        if ($this->filters['date_start']) {
            $query->whereDate('product.created_at', '>=', $this->filters['date_start']);
        }

        if ($this->filters['date_end']) {
            $query->whereDate('product.created_at', '<=', $this->filters['date_end']);
        }

        return $query->get()->map(function ($produto) {
            return [
                'ID' => $produto->id,
                'Nome' => $produto->nome,
                'Descrição' => $produto->descricao,
                'Status' => $produto->status ?? 'Sem Status',
                'Quantidade' => $produto->quantidade_disponivel,
                'Categoria' => $produto->categoria,
                'Fornecedor' => $produto->nome_fornecedor,
                'Data de Criação' => date('d/m/Y', strtotime($produto->created_at)),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome',
            'Descrição',
            'Status',
            'Quantidade',
            'Categoria',
            'Fornecedor',
            'Data de Criação',
        ];
    }
}
