<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Videoaula;
use App\Models\VideoaulaCategoria;
use Illuminate\Support\Facades\Auth;

class AdminVideoaulasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videoaulas = Videoaula::with('categoria')
            ->orderBy('categoria_id')
            ->orderBy('ordem')
            ->get();
            
        return view('admin.videoaulas.index', compact('videoaulas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = VideoaulaCategoria::where('ativo', 1)->orderBy('ordem')->get();
        return view('admin.videoaulas.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:videoaulas_categorias,id',
            'titulo' => 'required|string|max:255',
            'url_video' => 'required|string',
            'duracao' => 'nullable|integer|min:0',
            'ordem' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        $data['url_video'] = $this->converterUrlYoutube($request->url_video);
        $data['ativo'] = $request->has('ativo') ? 1 : 0;
        $data['ordem'] = $request->ordem ?? 0;

        Videoaula::create($data);

        return redirect()->route('admin.videoaulas.index')
            ->with('success', 'Videoaula criada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $videoaula = Videoaula::findOrFail($id);
        $categorias = VideoaulaCategoria::where('ativo', 1)->orderBy('ordem')->get();
        return view('admin.videoaulas.edit', compact('videoaula', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'categoria_id' => 'required|exists:videoaulas_categorias,id',
            'titulo' => 'required|string|max:255',
            'url_video' => 'required|string',
            'duracao' => 'nullable|integer|min:0',
            'ordem' => 'nullable|integer|min:0',
        ]);

        $videoaula = Videoaula::findOrFail($id);
        
        $data = $request->all();
        $data['url_video'] = $this->converterUrlYoutube($request->url_video);
        $data['ativo'] = $request->has('ativo') ? 1 : 0;
        
        $videoaula->update($data);

        return redirect()->route('admin.videoaulas.index')
            ->with('success', 'Videoaula atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $videoaula = Videoaula::findOrFail($id);
        $videoaula->delete();

        return redirect()->route('admin.videoaulas.index')
            ->with('success', 'Videoaula excluída com sucesso!');
    }

    /**
     * Converte URL do YouTube para formato embed
     */
    private function converterUrlYoutube($url) {
        // Se já está em formato embed, retorna como está
        if (strpos($url, 'youtube.com/embed') !== false) {
            return $url;
        }
        
        // Extrair ID do vídeo de diferentes formatos
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $video_id = $matches[1];
                return "https://www.youtube.com/embed/" . $video_id;
            }
        }
        
        // Se não conseguir extrair, retorna a URL original
        return $url;
    }
}
