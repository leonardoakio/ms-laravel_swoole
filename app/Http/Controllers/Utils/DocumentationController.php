<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DocumentationController extends Controller
{
    public function show(): View
    {
        return view('documentation');
    }

    public function yaml()
    {
        return file_get_contents(storage_path('api-docs/api-docs-v1.yaml'));
    }

    public function yamlV2()
    {
        return file_get_contents(storage_path('api-docs/api-docs-v2.yaml'));
    }
}
