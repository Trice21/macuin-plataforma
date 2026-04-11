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
