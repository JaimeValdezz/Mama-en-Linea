use Google\Cloud\Firestore\FirestoreClient;

public function index()
{
    $vacantes = [];

    try {
        $credentials = json_decode(env('FIREBASE_CREDENTIALS_JSON'), true);

        $firestore = new FirestoreClient([
            'keyFile' => $credentials,
            'transport' => 'rest',
        ]);

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
        Log::error("Error Firestore: " . $e->getMessage());
        return view('auth.vacantes', ['vacantes' => []]);
    }
}
