<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class MacuinApi
{
    public static function url(): string
    {
        return rtrim(config('api.url'), '/');
    }

    public static function internalUrl(): string
    {
        return rtrim(config('api.internal_url'), '/');
    }

    public static function imageUrl(?string $path): ?string
    {
        // Si no hay ruta, usar la imagen por defecto
        if (! $path || $path === 'None' || $path === '') {
            $path = '/static/images/default-autopart.svg';
        }

        // Si ya es una URL absoluta, devolverla tal cual
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Normalizar la ruta: asegurar que empiece con /static/images/autoparts/
        // si parece ser solo un nombre de archivo
        $cleanPath = ltrim($path, '/');
        if (! str_starts_with($cleanPath, 'static/')) {
            $cleanPath = 'static/images/autoparts/'.$cleanPath;
        }

        // Intentar obtener el host actual para construir la URL del navegador
        try {
            $host = request()->getHost();
            // Si estamos en el navegador, usamos el mismo host pero puerto 5000 (Flask)
            if ($host) {
                return "http://{$host}:5000/{$cleanPath}";
            }
        } catch (\Exception $e) {
            // Si falla (ej. consola), usar el valor de configuración
        }

        // Fallback al URL interno de la configuración o localhost
        $internalBase = rtrim(config('api.internal_url', 'http://localhost:5000'), '/');
        return "{$internalBase}/{$cleanPath}";
    }

    /**
     * Normaliza un pedido JSON de la API para las vistas (Carbon, colección de ítems).
     */
    public static function orderFromApi(array $data): object
    {
        $o = json_decode(json_encode($data));
        $o->created_at = Carbon::parse($data['created_at']);
        $o->items = collect($data['items'] ?? [])->map(function ($row) {
            $it = json_decode(json_encode($row));
            if (isset($row['autopart'])) {
                $it->autopart = json_decode(json_encode($row['autopart']));
            }

            return $it;
        });

        return $o;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    public static function cartItemsFromApi(array $rows): Collection
    {
        return collect($rows)->map(function ($row) {
            return json_decode(json_encode($row));
        });
    }
}
