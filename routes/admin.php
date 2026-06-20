<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\Client\MainController as ClientController;
use App\Http\Controllers\Admin\Employee\MainController as EmployeeController;
use App\Http\Controllers\Admin\Financial\MainController as FinancialController;
use App\Http\Controllers\Admin\Contract\MainController as ContractController;
use App\Http\Controllers\Admin\ProductionOrder\MainController as ProductionOrderController;
use App\Http\Controllers\Admin\Tax\MainController as TaxController;
use App\Http\Controllers\Admin\Benefit\MainController as BenefitController;
use App\Http\Controllers\Admin\Project\MainController as ProjectController;
use App\Http\Controllers\Admin\Supplier\MainController as SupplierController;
use App\Http\Controllers\Admin\Sale\MainController as SaleController;
use App\Http\Controllers\Admin\Product\MainController as ProductController;
use App\Http\Controllers\Admin\IdhMetric\MainController as IdhMetricController;
use App\Http\Controllers\Admin\Saques\MainController as WithdrawalLoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth', 'role:admin|gestor|contabilista|marketing|juridico|rh|producao']
], function () {
    Route::post('notificacoes/marcar-lidas', ['uses' => 'Admin\NotificationController@mark_all_read', 'as' => 'admin.notifications.read_all']);

    Route::get('/dashboard', [MainController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.dashboard');

    Route::prefix('/gestao')->group(function () {

        Route::get('/admin/reports/financial', [App\Http\Controllers\Admin\ReportController::class, 'financialReport'])->name('admin.reports.financial');
        // Clientes
        Route::prefix('/clientes')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('admin.gestao.clientes');
            Route::post('cadastrar', [ClientController::class, 'store'])->name('admin.gestao.cliente.cadastrar');
            Route::put('editar/{id}', [ClientController::class, 'update'])->name('admin.gestao.cliente.editar');
            Route::delete('apagar/{id}', [ClientController::class, 'destroy'])->name('admin.gestao.cliente.apagar');
        });

        // Produtos
        Route::prefix('/produtos')->group(function () {
            Route::get('/', [ProductController::class, 'list_products'])->name('admin.gestao.produtos');
            Route::post('cadastrar', [ProductController::class, 'store'])->name('admin.gestao.produto.cadastrar');
            Route::put('editar/{id}', [ProductController::class, 'update'])->name('admin.gestao.produto.editar');
            Route::delete('apagar/{id}', [ProductController::class, 'destroy'])->name('admin.gestao.produto.apagar');
            Route::get('/admin/reports/products/bom', [ReportController::class, 'reportBom'])->name('admin.reports.products.bom');
            Route::get('/produtos/deletados', [ProductController::class, 'list_trashed'])->name('admin.gestao.produtos.deletados');
            Route::post('restaurar/{id}', [ProductController::class, 'restore'])->name('admin.gestao.produto.restaurar');
            Route::get('/admin/reports/products/avariado', [ReportController::class, 'reportAvariado'])->name('admin.reports.products.avariado');
            Route::get('/admin/reports/products/em-analise', [ReportController::class, 'reportEmAnalise'])->name('admin.reports.products.em-analise');
            Route::get('/admin/reports/products/reparado', [ReportController::class, 'reportReparado'])->name('admin.reports.products.reparado');
            Route::get('/admin/reports/products/irreparavel', [ReportController::class, 'reportIrreparavel'])->name('admin.reports.products.irreparavel');
            Route::get('/admin/reports/products', [ReportController::class, 'productReport'])->name('admin.reports.products');
            Route::get('/admin/reports/products', [ReportController::class, 'productReport'])->name('admin.reports.products');
            Route::get('/admin/reports/sales', [ReportController::class, 'salesReport'])->name('admin.reports.sales');
            Route::get('/admin/reports/labels', [ReportController::class, 'labelsReport'])->name('admin.reports.labels');
            Route::get('/admin/reports/prices', [ReportController::class, 'pricesReport'])->name('admin.reports.prices');
            Route::get('/admin/reports/financial', [ReportController::class, 'financialReport'])->name('admin.reports.financial');
        });

        // Fornecedores
        Route::resource('admin/gestao/fornecedores', SupplierController::class)
            ->names([
                'index' => 'admin.gestao.fornecedores',
                'create' => 'admin.gestao.fornecedores.create',
                'store' => 'admin.gestao.fornecedor.cadastrar',
                'edit' => 'admin.gestao.fornecedor.editar',
                'update' => 'admin.gestao.fornecedor.atualizar',
                'destroy' => 'admin.gestao.fornecedor.apagar',
            ]);

        // Vendas
        Route::prefix('/vendas')->group(function () {
            Route::get('/', [SaleController::class, 'index'])->name('admin.gestao.vendas');
            Route::get('/criar', [SaleController::class, 'create'])->name('admin.gestao.venda.criar');
            Route::post('cadastrar', [SaleController::class, 'store'])->name('admin.gestao.venda.cadastrar');
            Route::get('/editar/{id}', [SaleController::class, 'edit'])->name('admin.gestao.venda.editar');
            Route::put('/atualizar/{id}', [SaleController::class, 'update'])->name('admin.gestao.venda.atualizar');
            Route::delete('apagar/{id}', [SaleController::class, 'destroy'])->name('admin.gestao.venda.apagar');
        });

        // Funcionários
        Route::prefix('/funcionarios')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('admin.gestao.funcionarios');
            Route::post('cadastrar', [EmployeeController::class, 'store'])->name('admin.gestao.funcionario.cadastrar');
            Route::put('editar/{id}', [EmployeeController::class, 'update'])->name('admin.gestao.funcionario.editar');
            Route::delete('apagar/{id}', [EmployeeController::class, 'destroy'])->name('admin.gestao.funcionario.apagar');
        });

        // Projetos
        Route::prefix('/projetos')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('admin.gestao.projetos');
            Route::post('cadastrar', [ProjectController::class, 'store'])->name('admin.gestao.projeto.cadastrar');
            Route::put('editar/{id}', [ProjectController::class, 'update'])->name('admin.gestao.projeto.editar');
            Route::delete('apagar/{id}', [ProjectController::class, 'destroy'])->name('admin.gestao.projeto.apagar');
        });

        // Financeiro
        Route::prefix('/financeiro')->group(function () {
            Route::get('/', [FinancialController::class, 'index'])->name('admin.gestao.financeiro');
            Route::post('cadastrar', [FinancialController::class, 'store'])->name('admin.gestao.financeiro.cadastrar');
            Route::put('editar/{id}', [FinancialController::class, 'update'])->name('admin.gestao.financeiro.editar');
            Route::delete('apagar/{id}', [FinancialController::class, 'destroy'])->name('admin.gestao.financeiro.apagar');
        });

        // Contratos
        Route::prefix('/contratos')->group(function () {
            Route::get('/', [ContractController::class, 'index'])->name('admin.gestao.contratos');
            Route::post('cadastrar', [ContractController::class, 'store'])->name('admin.gestao.contrato.cadastrar');
            Route::put('editar/{id}', [ContractController::class, 'update'])->name('admin.gestao.contrato.editar');
            Route::delete('apagar/{id}', [ContractController::class, 'destroy'])->name('admin.gestao.contrato.apagar');
        });

        Route::get('/admin/reports/financial', [ReportController::class, 'financialReport'])->name('admin.reports.financial');
        Route::get('/admin/reports/sales', [ReportController::class, 'salesReport'])->name('admin.reports.sales');

        // Ordens de Produção
        Route::prefix('/ordens-producao')->group(function () {
            Route::get('/', [ProductionOrderController::class, 'index'])->name('admin.gestao.ordens-producao');
            Route::post('/{id}/confirm', [App\Http\Controllers\Admin\OrderNegotiation\MainController::class, 'confirm'])
                ->name('admin.orders.confirm');
            Route::post('cadastrar', [ProductionOrderController::class, 'store'])->name('admin.gestao.ordem-producao.cadastrar');
            Route::put('editar/{id}', [ProductionOrderController::class, 'update'])->name('admin.gestao.ordem-producao.editar');
            Route::delete('apagar/{id}', [ProductionOrderController::class, 'destroy'])->name('admin.gestao.ordem-producao.apagar');
        });

        // Impostos
        Route::prefix('/impostos')->group(function () {
            Route::get('/', [TaxController::class, 'index'])->name('admin.gestao.impostos');
            Route::post('cadastrar', [TaxController::class, 'store'])->name('admin.gestao.imposto.cadastrar');
            Route::put('editar/{id}', [TaxController::class, 'update'])->name('admin.gestao.imposto.editar');
            Route::delete('apagar/{id}', [TaxController::class, 'destroy'])->name('admin.gestao.imposto.apagar');
        });

        // Benefícios
        Route::prefix('/beneficios')->group(function () {
            Route::get('/', [BenefitController::class, 'index'])->name('admin.gestao.beneficios');
            Route::post('cadastrar', [BenefitController::class, 'store'])->name('admin.gestao.beneficio.cadastrar');
            Route::put('editar/{id}', [BenefitController::class, 'update'])->name('admin.gestao.beneficio.editar');
            Route::delete('apagar/{id}', [BenefitController::class, 'destroy'])->name('admin.gestao.beneficio.apagar');
        });

        // Saques e Empréstimos
        Route::prefix('/saques-emprestimos')->group(function () {
            Route::get('/', [WithdrawalLoanController::class, 'index'])->name('admin.gestao.withdrawals-loans');
            Route::post('saque/cadastrar', [WithdrawalLoanController::class, 'storeWithdrawal'])->name('admin.gestao.withdrawal.cadastrar');
            Route::post('emprestimo/cadastrar', [WithdrawalLoanController::class, 'storeLoan'])->name('admin.gestao.loan.cadastrar');
            Route::get('editar/{id}', [WithdrawalLoanController::class, 'edit'])->name('admin.gestao.withdrawal-loan.edit');
            Route::put('atualizar/{id}', [WithdrawalLoanController::class, 'update'])->name('admin.gestao.transaction.update');
            Route::delete('apagar/{id}', [WithdrawalLoanController::class, 'destroy'])->name('admin.gestao.withdrawal.delete');
            Route::post('restaurar/{id}', [WithdrawalLoanController::class, 'restore'])->name('admin.gestao.withdrawal.restore');
        });

        // Métricas IDH
        Route::prefix('/metricas-idh')->group(function () {
            Route::get('/', [IdhMetricController::class, 'index'])->name('admin.gestao.idh-metricas');
            Route::post('cadastrar', [IdhMetricController::class, 'store'])->name('admin.gestao.idh-metrica.cadastrar');
            Route::put('atualizar/{id}', [IdhMetricController::class, 'update'])->name('admin.gestao.idh-metrica.editar');
            Route::delete('apagar/{id}', [IdhMetricController::class, 'destroy'])->name('admin.gestao.idh-metrica.apagar');
        });

        // Atividades (Logs)
        Route::prefix('/atividades')->group(function () {
            Route::get('/', [MainController::class, 'list_logs'])->name('admin.gestao.atividades');
        });
        Route::resource('/usuarios', UserController::class)
            ->names([
                'index' => 'admin.gestao.usuarios',
                'create' => 'admin.gestao.usuarios.create',
                'store' => 'admin.gestao.usuario.cadastrar',
                'edit' => 'admin.gestao.usuario.editar',
                'update' => 'admin.gestao.usuario.atualizar',
                'destroy' => 'admin.gestao.usuario.apagar',
            ]);
    });
    // Gestão de Encomendas
    Route::prefix('encomendas')->group(function () {
        /*         Route::get('/admin/reports/orders', [ReportController::class, 'ordersReport'])->name('admin.reports.orders');
                Route::get('/admin/reports/orders/negociacoes', [ReportController::class, 'orderNegotiationsReport'])->name('admin.reports.orders.negociacoes');
                Route::get('/admin/reports/orders/comprovativos', [ReportController::class, 'paymentProofsReport'])->name('admin.reports.orders.comprovativos');
                Route::get('/admin/reports/orders/pendentes', [ReportController::class, 'pendingOrdersReport'])->name('admin.reports.orders.pendentes');
                Route::get('/admin/reports/orders/aceites', [ReportController::class, 'acceptedOrdersReport'])->name('admin.reports.orders.aceites');
                Route::get('/admin/reports/orders/rejeitados', [ReportController::class, 'rejectedOrdersReport'])->name('admin.reports.orders.rejeitados');
                Route::get('/admin/reports/orders/aguardando-confirmacao', [ReportController::class, 'awaitingConfirmationOrdersReport'])->name('admin.reports.orders.aguardando-confirmacao');
                Route::get('/admin/reports/orders/expirados', [ReportController::class, 'expiredOrdersReport'])->name('admin.reports.orders.expirados');
        */

        Route::get('/admin/encomendas/confirmar/produto', [App\Http\Controllers\Admin\OrderNegotiation\MainController::class, 'confirmProduct'])
            ->name('admin.encomendas.confirmar-produto');
        Route::get('/admin/encomendas/update', [App\Http\Controllers\Admin\OrderNegotiation\MainController::class, 'update'])->name('admin.encomendas.update');

        Route::get(
            '',
            [
                'uses' => 'Admin\OrderNegotiation\MainController@index',
                'as'   => 'admin.orders.index'
            ]
        );
        Route::get(
            '{id}/comprovativo',
            [
                'uses' => 'Admin\OrderNegotiation\MainController@show_proof',
                'as'   => 'admin.orders.proof'
            ]
        );
        Route::post(
            '{id}/aprovar',
            [
                'uses' => 'Admin\OrderNegotiation\MainController@approve',
                'as'   => 'admin.orders.approve'
            ]
        );
        Route::post(
            '{id}/rejeitar',
            [
                'uses' => 'Admin\OrderNegotiation\MainController@reject',
                'as'   => 'admin.orders.reject'
            ]
        );
    });

    // Gestão de Categorias
    Route::prefix('categorias')->group(function () {
        Route::get(
            '',
            [
                'uses' => 'Admin\Categoria\MainController@index',
                'as'   => 'admin.categorias.index'
            ]
        );
        Route::post(
            '',
            [
                'uses' => 'Admin\Categoria\MainController@store',
                'as'   => 'admin.categorias.store'
            ]
        );
        Route::put(
            '{id}',
            [
                'uses' => 'Admin\Categoria\MainController@update',
                'as'   => 'admin.categorias.update'
            ]
        );
        Route::delete(
            '{id}',
            [
                'uses' => 'Admin\Categoria\MainController@destroy',
                'as'   => 'admin.categorias.destroy'
            ]
        );
    });

    // Perfil
    Route::prefix('/profile')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
