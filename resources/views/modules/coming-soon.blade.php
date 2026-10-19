<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $module }} - PP Kunuuzul Imam Kauman</title>
    <style>
        body { margin: 0; font-family: system-ui, sans-serif; background: #0f172a; color: #e2e8f0; display: flex; min-height: 100vh; align-items: center; justify-content: center; }
        .card { text-align: center; padding: 2rem; }
        h1 { font-size: 2rem; margin: 0 0 .5rem; }
        p { color: #94a3b8; }
        code { background: #1e293b; padding: .15rem .4rem; border-radius: .25rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Modul {{ $module }}</h1>
        <p>Aktif di <code>{{ request()->getHost() }}</code></p>
        <p>Halaman ini masih kerangka. Silakan isi modulnya.</p>
    </div>
</body>
</html>
