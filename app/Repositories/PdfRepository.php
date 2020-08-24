<?php

namespace App\Repositories;

use App\PdfDoc as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * [Description PdfRepository]
 */
class PdfRepository extends CoreRepository
{
    /**
     * @return [type]
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param mixed $id
     *
     * @return [type]
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    public function getAll()
    {
        $data = $this->startConditions()->all();
        //$pdfDocs = $this->pd_rep->get(['id','filename','description','hash','size'], config('settings.pdf_thumbnails_count'));
        if (!$data->isEmpty()) {
            $data->transform(
                function ($item, $key) {
                    $pdf_path = config('settings.storage_path.pdf').$item->hash;
                    $item->hash = $pdf_path;
                    $nameWithoutExtension = pathinfo($pdf_path, PATHINFO_FILENAME);
                    $item->image = config('settings.storage_path.image').$nameWithoutExtension.'.'.config('settings.gs_format');
                    $item->size = round($item->size/1024/1024, 2);
                    return $item;
                }
            );
        }

        return $data;
    }

    public function getWithLimit($limit = null)
    {
        $fields = ['id','filename','description','hash','size'];

        $result = $this
            ->startConditions()
            ->select($fields);
        //->take($limit);

        return $result;
    }
}
