private function getFirestore()
{
    $jsonRaw = env('FIREBASE_CREDENTIALS_JSON');

    if (!$jsonRaw) {
        throw new \Exception('FIREBASE_CREDENTIALS_JSON no existe en Railway');
    }

    // 💡 LIMPIEZA MAESTRA: 
    // 1. Quitamos espacios o comillas accidentales en los extremos
    // 2. Corregimos los escapes de Railway (\n -> salto real, \" -> ")
    $jsonClean = trim($jsonRaw, '" '); 
    $jsonClean = str_replace(['\\n', '\\"'], ["\n", '"'], $jsonClean);
    
    $credentials = json_decode($jsonClean, true);

    if (!$credentials || !isset($credentials['private_key'])) {
        Log::error("Contenido fallido (primeros 50 caracteres): " . substr($jsonClean, 0, 50));
        throw new \Exception('FIREBASE_CREDENTIALS_JSON inválido después de limpieza');
    }

    // Aseguramos saltos de línea reales en la llave privada
    $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);

    return new FirestoreClient([
        'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
        'keyFile'   => $credentials,
        'transport' => 'rest', // ✅ Mantiene la conexión estable en Railway
    ]);
}
