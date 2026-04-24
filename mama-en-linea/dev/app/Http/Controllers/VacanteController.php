public function index()
{
    $vacantes = [];

    try {
        // Forzar REST (recomendado en Oracle Cloud / entornos sin gRPC fácil)
        $database = Firebase::firestore()->database(['transport' => 'rest']);

        $query = $database->collection('Vacantes');

        // Filtramos solo las aprobadas (mejor forma)
        $query = $query->where('is_approved', '=', true);

        // Si en Firestore algunos documentos tienen el valor como string 'true', agrega un OR con array-contains o haz dos consultas
        // Por ahora usamos solo booleano true (recomendado)

        $documents = $query
            ->orderBy('created_at', 'DESC')   // opcional pero útil
            ->limit(50)
            ->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();        // importante: el id no viene en data()
                $vacantes[] = $data;
            }
        }

        return view('auth.vacantes', compact('vacantes'));

    } catch (\Exception $e) {
        Log::error("Error Firestore Vacantes index: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
        
        // Nunca devuelvas error 500 al usuario
        return view('auth.vacantes', ['vacantes' => []])
            ->with('error', 'No se pudieron cargar las vacantes en este momento.');
    }
}