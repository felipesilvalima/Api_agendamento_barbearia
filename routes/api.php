<?php declare(strict_types=1);

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AgendamentoServicoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServicoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//rota de autenticação e criação de usuário de clientes
Route::post('/login',[AuthController::class, 'login'])->name('logar_usuario');
Route::post('/clientes',[ClienteController::class, 'criarClientes'])->name('criar_cliente');


//rotas de auth
Route::middleware('auth:api','permissao:Cliente|Barbeiro')->prefix('auth')->group( function () {
    Route::post('/refresh',[AuthController::class, 'refresh'])->name('refresh');
    Route::get('/me',[AuthController::class, 'me'])->name('me');
    Route::put('/update/me',[AuthController::class, 'uptdateMe'])->name('atualizar_password');
    Route::delete('/desativar/me',[AuthController::class, 'desativarMe'])->name('desativar_conta');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
});


//rotas acessada por Clientes
Route::middleware('auth:api','permissao:Cliente')->group( function () {
    Route::get('/clientes',[ClienteController::class, 'listarClientes'])->name('listar_clientes');
    Route::post('/agendamentos',[AgendamentoController::class, 'criarAgendamento'])->name('criar_agendamentos');
    Route::patch('/agendamentos/{id}/reagendar',[AgendamentoController::class, 'reagendarAgendamentos'])->name('reagendar_agendamentos');
    Route::delete('/agendamentos/{id_agendamento}/servicos/{id_servico}',[AgendamentoController::class, 'removerServicos'])->name('remover_servicos');
    Route::post('/agendamentos/{id_agendamento}/servicos/{id_servico}',[AgendamentoServicoController::class, 'adicionarServicosAgendamento'])->name('adiconar_servicos_no_agendamento');
});

//rotas Acessada por Barbeiros
Route::middleware('auth:api','permissao:Barbeiro')->group( function () {
    Route::patch('/agendamentos/{id}/concluir',[AgendamentoController::class, 'finalizarAgendamentos'])->name('finalizar_agendamentos');
    Route::post('/barbeiros',[BarbeiroController::class, 'criarBarbeiros'])->name('criar_barbeiros');
     Route::get('/clientes/{id_cliente}',[ClienteController::class, 'detalhesClientes'])->name('detalhes_clientes');
});

//rotas Acessadas por Cliente e Barbeiro
Route::middleware('auth:api','permissao:Cliente|Barbeiro')->group( function () {
    Route::get('/agendamentos',[AgendamentoController::class, 'listarAgendamentos'])->name('listar_agendamentos');
    Route::get('/agendamentos/{id}',[AgendamentoController::class, 'verAgenda'])->name('detalhes_agendamentos');
    Route::get('/agendamentos/{id_agendamento}/servicos',[AgendamentoServicoController::class, 'listaServicosAgendamento'])->name('listar_servicos_por_agendamento');
    Route::patch('/agendamentos/{id}/cancelar',[AgendamentoController::class, 'cancelarAgendamentos'])->name('cancelar_agendamentos');
});



