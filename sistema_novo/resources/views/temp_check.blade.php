@extends('layouts.app')

@section('title', 'RCP - Sistema de Concursos - Plataforma de Estudos')

@section('content')
<!-- NOTA: A landing page tem estrutura diferente do layout padrão (sem sidebar).
     Idealmente teria um layout específico, mas vou sobrescrever o comportamento aqui ou não usar layout.
     Vou criar um arquivo isolado para garantir que não herde a sidebar se o layout app forcar.
     Mas espere, layouts/app.blade.php inclui sidebar.
     A landing page NÃO DEVE ter sidebar.
     Vou fazer welcome.blade.php NÃO estender layouts.app, mas sim ser autossuficiente como o index.php original.
-->
@endsection
