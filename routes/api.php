<?php declare(strict_types=1);

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ClienteController;
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
Route::middleware('auth:api')->prefix('auth')->group( function () {
    Route::post('/refresh',[AuthController::class, 'refresh'])->name('refresh');
    Route::get('/me',[AuthController::class, 'me'])->name('me');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
});


//rotas da api
Route::middleware('auth:api')->group( function () {
    Route::post('/agendamentos',[AgendamentoController::class, 'criarAgendamento'])->name('criar_agendamentos');
    Route::get('/agendamentos',[AgendamentoController::class, 'listarAgendamentos'])->name('listar_agendamentos');
    Route::get('/agendamentos/{id}',[AgendamentoController::class, 'buscarAgenda'])->name('buscar_agendamentos');
    Route::patch('/agendamentos/{id}/reagendar',[AgendamentoController::class, 'reagendarAgendamento'])->name('reagendar_agendamentos');
    Route::patch('/agendamentos/{id}/cancelar',[AgendamentoController::class, 'cancelarAgendamentos'])->name('cancelar_agendamentos');
    Route::patch('/agendamentos/{id}/concluir',[AgendamentoController::class, 'concluirAgendamentos'])->name('concluir_agendamentos');

    Route::post('/barbeiros',[BarbeiroController::class, 'criarBarbeiros'])->name('criar_barbeiros');
});



