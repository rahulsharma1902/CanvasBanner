<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CanvasTemplate;
class CanvasTemplateController extends Controller
{
    //
    public function index(Request $request){
        $templates = CanvasTemplate::all();
        return view('welcome',compact('templates'));
    }
    public function save(Request $request)
    {
        // Retrieve and save the template data to the database
        $templateData = $request->input('templateData');
        $template = new CanvasTemplate;
        $template->name = $request->input('templateName');
        $template->templateData = $templateData;
        $template->save();
        // Create a new template record or update an existing one
        // $template = Template::firstOrNew(['name' => 'someTemplateName']);
        // $template->data = $templateData;
        // $template->save();

        return response()->json($templateData);
    }
}
