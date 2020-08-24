<?php

return [

    'pdf_thumbnails_count' => 20,
    'storage_path' => [
        'pdf' => "storage/uploads/pdf/",
        'image' => "storage/uploads/pdf/",
    ],
    'theme' => env('THEME', 'default'),
    // Ghostscript settings
    'gs_path' => env('GS_PATH', 'C:\Program Files\gs\gs9.52\bin\gswin64c.exe'),
    'gs_format' => env('GS_FORMAT', 'jpeg'),

];
