use Google\Cloud\Firestore\FirestoreClient;

class VacanteController extends Controller
{
    private function getFirestore()
    {
        $credentials = json_decode(env('FIREBASE_CREDENTIALS_JSON'), true);

        return new FirestoreClient([
            'keyFile' => $credentials,
            'transport' => 'rest',
        ]);
    }

    public function index()
    {
        $vacantes = [];

        try {
            $firestore = $this->getFirestore();

            $documents = $firestore->collection('Vacantes')->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $data = $document->data();
                    $aprobada = $data['is_approved'] ?? false;

                    if ($aprobada == true || $aprobada == 'true') {
                        $vacantes[] = array_merge(['id' => $document->id()], $data);
                    }
                }
            }

            return view('auth.vacantes', compact('vacantes'));

        } catch (\Exception $e) {
            Log::error("Firestore error: " . $e->getMessage());
            return view('auth.vacantes', ['vacantes' => []]);
        }
    }

    public function store(Request $request)
    {
        try {
            $firestore = $this->getFirestore();

            $firestore->collection('Vacantes')->add([
                'nombre_empresa' => $request->nombre_empresa,
                'titulo'         => $request->titulo,
                'sueldo'         => (int)$request->sueldo,
                'lugar'          => $request->lugar,
                'descripcion'    => $request->descripcion,
                'contacto'       => $request->contacto,
                'is_approved'    => false,
                'created_at'     => now()->toIso8601String()
            ]);

            return redirect()->route('vacantes.index')
                ->with('success', '¡Vacante publicada con éxito!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
