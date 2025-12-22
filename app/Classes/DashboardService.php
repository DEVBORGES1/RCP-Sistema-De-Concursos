<?php

require_once __DIR__ . '/Database.php';

class DashboardService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getEstatisticas($usuario_id) {
        // Estatísticas de Questões
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM respostas_usuario WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $total_questoes = $stmt->fetchColumn();

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM respostas_usuario WHERE usuario_id = ? AND correta = 1");
        $stmt->execute([$usuario_id]);
        $questoes_corretas = $stmt->fetchColumn();

        $percentual_acerto = $total_questoes > 0 ? round(($questoes_corretas / $total_questoes) * 100, 1) : 0;

        // Editais enviados
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM editais WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $total_editais = $stmt->fetchColumn();

        // Simulados realizados
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM simulados WHERE usuario_id = ? AND questoes_corretas IS NOT NULL");
        $stmt->execute([$usuario_id]);
        $total_simulados = $stmt->fetchColumn();

        return [
            'total_questoes' => $total_questoes,
            'questoes_corretas' => $questoes_corretas,
            'percentual_acerto' => $percentual_acerto,
            'total_editais' => $total_editais,
            'total_simulados' => $total_simulados
        ];
    }
}
