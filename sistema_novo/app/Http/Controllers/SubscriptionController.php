<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Exibe a landing page de Planos (Pricing).
     * Pode ser visualizada por usuários logados ou não.
     */
    public function pricing()
    {
        // Neste futuro, buscaremos os planos criados no Stripe/Banco de dados.
        // Por hora, montamos a estrutura "Mock" para testar o painel visual
        
        $planos = [
            [
                'nome' => 'Gratuito',
                'id' => 'price_free',
                'preco' => '0,00',
                'periodo' => 'para sempre',
                'destaque' => false,
                'recursos' => [
                    ['nome' => '50 questões por mês', 'ativo' => true],
                    ['nome' => 'Simulados básicos', 'ativo' => true],
                    ['nome' => 'Estatísticas simplificadas', 'ativo' => true],
                    ['nome' => 'Mentor de IA', 'ativo' => false],
                    ['nome' => 'Download de PDF', 'ativo' => false],
                    ['nome' => 'Legislação e Videoaulas', 'ativo' => false],
                ],
                'cta' => 'Criar Conta Grátis'
            ],
            [
                'nome' => 'Pro',
                'id' => 'price_pro',
                'preco' => '39,90',
                'periodo' => '/ mês',
                'destaque' => true, // Para focar neste plano
                'recursos' => [
                    ['nome' => 'Questões Ilimitadas', 'ativo' => true],
                    ['nome' => 'Simulados avançados (Baseados no edital)', 'ativo' => true],
                    ['nome' => 'Métricas de Evolução Premium', 'ativo' => true],
                    ['nome' => 'Mentor de IA', 'ativo' => true],
                    ['nome' => 'Download de PDF', 'ativo' => false],
                    ['nome' => 'Legislação e Videoaulas', 'ativo' => false],
                ],
                'cta' => 'Assinar o Pro'
            ],
            [
                'nome' => 'VIP',
                'id' => 'price_vip',
                'preco' => '89,90',
                'periodo' => '/ mês',
                'destaque' => false,
                'recursos' => [
                    ['nome' => 'Tudo do plano Pro', 'ativo' => true],
                    ['nome' => 'Acesso completo a Legislações', 'ativo' => true],
                    ['nome' => 'Todas as Videoaulas Exclusivas', 'ativo' => true],
                    ['nome' => 'Download de PDFs para offline', 'ativo' => true],
                    ['nome' => 'Suporte Prioritário', 'ativo' => true],
                    ['nome' => 'Rodadas de simulados Inéditos', 'ativo' => true],
                ],
                'cta' => 'Ser VIP Completo'
            ]
        ];

        return view('saas.pricing', compact('planos'));
    }

    /**
     * Área restrita do Aluno para gerenciar a assinatura atual.
     */
    public function meuPlano()
    {
        $user = Auth::user();

        // Mock: Simula o uso/status atual de um usuário
        $planoAtual = [
            'nome' => 'Gratuito',
            'status' => 'ativo', // ativo, pendente, cancelado
            'questoes_usadas' => 32,
            'questoes_limite' => 50,
            'simulados_usados' => 1,
            'simulados_limite' => 3,
            'renovacao' => null // "05/04/2026" se fosse pagante
        ];

        return view('saas.meu-plano', compact('user', 'planoAtual'));
    }
}
