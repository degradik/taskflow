<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index($entity_type)
    {
        $fields = CustomField::where('entity_type', $entity_type)->get();

        return response()->json($fields);
    }
}
