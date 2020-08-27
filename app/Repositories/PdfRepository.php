<?php

namespace App\Repositories;

use App\PdfDoc as Model;

/**
 * Class PdfRepository
 * @package App\Repositories
 */
class PdfRepository extends CoreRepository
{
    /**
     * @var string[]
     */
    private $columns = ['id','filename','description','hash','size'];

    /**
     * @return mixed|string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $items = $this->startConditions()->select($this->columns)->get();
        $this->change($items);

        return $items;
    }

    /**
     * @param null $perPage
     * @return mixed
     */
    public function getWithPagination($perPage = null)
    {
        $items = $this->startConditions()->select($this->columns)->paginate($perPage);
        $this->change($items);

        return $items;
    }

    /**
     * @param $items
     */
    private function change($items){
        if (!$items->isEmpty() ) {
            $items->transform(function ($item, $key) {
                $pdf_path = config('settings.storage_path.pdf') . $item->hash;
                $item->hash = $pdf_path;
                $nameWithoutExtension = pathinfo($pdf_path, PATHINFO_FILENAME);
                $item->image = config('settings.storage_path.image') . $nameWithoutExtension . '.' . config('settings.gs_format');
                $item->size = round($item->size / 1024 / 1024, 2);
                return $item;
            });
        }
    }
}
