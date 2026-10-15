<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

/**
 * Route publik ringan tanpa closure (agar route:cache bisa dipakai).
 */
class SiteController extends Controller
{
    public function healthz(): JsonResponse
    {
        return response()->json(['ok' => true, 'time' => now()->toIso8601String()]);
    }

    public function sitemap(): Response
    {
        // Normalisasi host ke domain yang sedang diakses agar sitemap tidak
        // bocor ke domain staging (mis. APP_URL masih xxx.up.railway.app).
        $root = request()->root();
        $canon = function (string $url) use ($root) {
            try {
                $want = parse_url($root);
                $parts = parse_url($url);
                if (! isset($parts['host']) || ! isset($want['host'])) return $url;
                $parts['host'] = $want['host'];
                $parts['scheme'] = $want['scheme'] ?? $parts['scheme'] ?? 'https';
                unset($parts['port']);
                $out = ($parts['scheme'] ?? 'https') . '://' . $parts['host'] . ($parts['path'] ?? '/');
                if (! empty($parts['query'])) $out .= '?' . $parts['query'];
                return $out;
            } catch (\Throwable $e) { return $url; }
        };
        $urls = [];
        foreach (['home','profil','profil.sejarah','profil.struktur','profil.visi','lembaga','organisasi','program.index','berita.index','artikel.index','galeri.index','santri.index','ppdb.form','ppdb.cek','kontak','konsultasi','privasi'] as $name) {
            try { $urls[] = ['loc' => $canon(route($name)), 'lastmod' => now()]; } catch (\Throwable $e) {}
        }
        try {
            $beritas = \App\Models\Berita::where('is_published', true)->latest('published_at')->get();
            foreach ($beritas as $b) {
                $urls[] = ['loc' => $canon(route('berita.show', $b->slug)), 'lastmod' => $b->updated_at ?? $b->published_at ?? now()];
            }
            $programs = \App\Models\Program::where('is_aktif', true)->orderBy('urutan')->get();
            foreach ($programs as $p) {
                $urls[] = ['loc' => $canon(route('program.show', $p->slug)), 'lastmod' => $p->updated_at ?? now()];
            }
            $orgs = \App\Models\Organisasi::where('is_aktif', true)->orderBy('urutan')->get();
            foreach ($orgs as $o) {
                $urls[] = ['loc' => $canon(route('organisasi.show', $o->slug)), 'lastmod' => $o->updated_at ?? now()];
            }
        } catch (\Throwable $e) {
            // DB bermasalah: tetap tampilkan URL statis
        }
        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
    }

    /**
     * Webhook deploy: migrate --force, sinkron public/ -> public_html
     * (copy lokal, tanpa FTP), dan optimize:clear. Tanpa akses shell.
     * Diproteksi token (DEPLOY_TOKEN) + throttle.
     */
    public function deployHook(Request $request): JsonResponse
    {
        $token = (string) config('services.deploy.token', '');
        abort_unless($token !== '' && hash_equals($token, (string) $request->input('token')), 403);
        Artisan::call('migrate', ['--force' => true]);
        $migrate = Artisan::output();
        // public_html adalah copy terpisah dari laravel/public (bukan symlink),
        // jadi hasil build Vite + favicon + robots + images disalin di sini.
        // index.php/.htaccess milik server tidak disentuh.
        $sync = ['target' => null, 'copied' => 0, 'skipped' => false];
        try {
            $src = realpath(public_path()) ?: public_path();
            $dst = realpath(base_path('../public_html')) ?: null;
            $sync['target'] = $dst;
            if ($dst && is_dir($dst) && is_file($dst . '/index.php')) {
                $it = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($src, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($it as $file) {
                    $rel = ltrim(str_replace('\\', '/', substr($file->getPathname(), strlen($src))), '/');
                    if (in_array($rel, ['index.php', '.htaccess'], true)) continue;
                    $dest = $dst . '/' . $rel;
                    if ($file->isDir()) {
                        if (! is_dir($dest)) mkdir($dest, 0755, true);
                        continue;
                    }
                    if (! is_file($dest) || filesize($dest) !== $file->getSize()
                        || filemtime($dest) !== $file->getMTime()) {
                        @mkdir(dirname($dest), 0755, true);
                        if (@copy($file->getPathname(), $dest)) {
                            @touch($dest, $file->getMTime());
                            $sync['copied']++;
                        }
                    }
                }
            } else {
                $sync['skipped'] = true;
            }
        } catch (\Throwable $e) {
            $sync['error'] = substr($e->getMessage(), 0, 200);
        }
        Artisan::call('optimize:clear');
        return response()->json(['ok' => true, 'migrate' => $migrate, 'public_sync' => $sync]);
    }
}
