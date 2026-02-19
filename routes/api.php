<?php declare(strict_types=1);

use App\Http\Controllers\BarbeariaController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AgendamentoServicoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\NotificacaoController;
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


Route::prefix('v1')->group(function (){

    //rota de autenticação e criação de usuário de clientes
    Route::post('/login',[AuthController::class, 'login'])->name('logar_usuario');
    Route::post('/clientes/{barbearia_id}',[ClienteController::class, 'criarClientes'])->name('criar_cliente');
    Route::patch('/users/ativar',[AuthController::class, 'ativarMe'])->name('ativar_conta');
    
    Route::middleware('Ativo','AtivoUser')->group(function(){

        //rotas de auth
        Route::middleware('auth:api','permissao:Cliente|Barbeiro')->prefix('auth')->group( function () {
            Route::post('/refresh',[AuthController::class, 'refresh'])->name('refresh');
            Route::get('/me',[AuthController::class, 'me'])->name('me');
            Route::patch('/users/password',[AuthController::class, 'uptdatePassword'])->name('atualizar_password');
            Route::patch('/users',[AuthController::class, 'uptdateMe'])->name('atualizar_user');
            Route::patch('/users/desativar',[AuthController::class, 'desativarMe'])->name('desativar_conta');
            Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
        });
        
        
        //rotas acessada por Clientes
        Route::middleware('auth:api','permissao:Cliente')->group( function () {
            Route::get('/clientes/agendamentos',[ClienteController::class, 'listarAgendamentosClientes'])->name('listar_agendamentos_clientes');
            Route::post('/agendamentos',[AgendamentoController::class, 'criarAgendamento'])->name('criar_agendamentos');
            Route::patch('/agendamentos/{id}/reagendar',[AgendamentoController::class, 'reagendarAgendamentos'])->name('reagendar_agendamentos');
            Route::delete('/agendamentos/{id_agendamento}/servicos/{id_servico}',[AgendamentoServicoController::class, 'removerServicos'])->name('remover_servicos');
            Route::post('/agendamentos/{id_agendamento}/servicos/{id_servico}',[AgendamentoServicoController::class, 'adicionarServicosAgendamento'])->name('adiconar_servicos_no_agendamento');
            Route::patch('/clientes',[ClienteController::class, 'atualizarClientes'])->name('atualizar_clientes');
        });
        
        //rotas Acessada por Barbeiros
        Route::middleware('auth:api','permissao:Barbeiro')->group( function () {
            Route::get('/barbeiros/agendamentos',[BarbeiroController::class, 'listarAgendamentosBarbeiros'])->name('listar_agendamentos_barbeiros');
            Route::patch('/barbeiros',[BarbeiroController::class, 'atualizarBarbeiros'])->name('atualizar_barbeiros');
            Route::post('/servicos',[ServicoController::class, 'criarServicos'])->name('cadastrar_servicos');
            Route::get('/servicos/desativados',[ServicoController::class, 'listarDesativado'])->name('listar_desativar_servicos');
            Route::patch('/agendamentos/{id}/concluir',[AgendamentoController::class, 'finalizarAgendamentos'])->name('finalizar_agendamentos');
            Route::post('/barbeiros/{barbearia_id}',[BarbeiroController::class, 'criarBarbeiros'])->name('criar_barbeiros');
            Route::patch('/servicos/{id_servico}',[ServicoController::class, 'atualizarServicos'])->name('atualizar_servicos');
            Route::delete('/servicos/{id_servico}/desativar',[ServicoController::class, 'DesativarServicos'])->name('desativar_servicos');
            Route::patch('/servicos/{id_servico}/ativar',[ServicoController::class, 'ativarServico'])->name('ativar_servicos');
        });
        
        //rotas Acessadas por Cliente e Barbeiro
        Route::middleware('auth:api','permissao:Cliente|Barbeiro')->group( function () {
            Route::get('/agendamentos',[AgendamentoController::class, 'listarAgendamentos'])->name('listar_agendamentos');
            Route::get('/agendamentos/{id}',[AgendamentoController::class, 'verAgenda'])->name('detalhes_agendamentos');
            Route::get('/agendamentos/{id_agendamento}/servicos',[AgendamentoServicoController::class, 'listaServicosAgendamento'])->name('listar_servicos_por_agendamento');
            Route::patch('/agendamentos/{id}/cancelar',[AgendamentoController::class, 'cancelarAgendamentos'])->name('cancelar_agendamentos');
            Route::get('/barbeiros/{id_barbeiro}',[BarbeiroController::class, 'detalhesBarbeiros'])->name('detalhes_barbeiros');
            Route::get('/clientes/{id_cliente}',[ClienteController::class, 'detalhesClientes'])->name('detalhes_clientes');
            Route::get('/servicos',[ServicoController::class, 'listarServicos'])->name('listar_servicos');
            Route::get('/agendamentos/{id_agendamento}/total',[ServicoController::class, 'precoTotalAgendamento'])->name('precoTotal_por_agendamento');
            Route::get('/servicos/{id_servico}',[ServicoController::class, 'detalhesServicos'])->name('detalhes_servico');
            Route::get('/notificacoes',[NotificacaoController::class, 'listarNotificacoes'])->name('lista_notificacoes');
            Route::delete('/notificacoes/{id_notificao}',[NotificacaoController::class, 'deletarNotificaos'])->name('deleta_notificacoes');
        });
    });

    Route::middleware('auth:api','permissao:Admin')->prefix('admin')->group(function () {
        Route::get('/barbearias', [BarbeariaController::class,'listarBarbearias'])->name('listar_barbearias');
        Route::get('/barbearias/{id_barbearia}', [BarbeariaController::class,'detalhesBarbearia'])->name('detalhes_barbearias');
        Route::patch('/barbearias/{id_barbearia}/ativar', [BarbeariaController::class,'ativarBarbearia'])->name('ativar_barbearias');
        Route::patch('/barbearias/{id_barbearia}/desativar', [BarbeariaController::class,'desativarBarbearia'])->name('desativar_barbearias');
    });

});

Route::fallback(
    function () {
        return response()->json([
            'message' => 'Endpoint não encontrado. Verifique a URL e tente novamente.'
        ], 404);
    }
);





