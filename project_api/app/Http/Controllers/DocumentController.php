<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Document::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'pdf_file' => 'required' 
        ]);

        $data = $request->all();

        if($request->hasFile('pdf_file')){
            /* Validerar pdf-filen som skickats */
            $request->validate([
                'pdf_file'=>'required|file|mimes:pdf',
            ]);

            $pdf_file = $request->file('pdf_file');
            $filesize = $request->file('pdf_file')->getSize();

            $pdf_fileName = time() . '.' . $pdf_file->getClientOriginalExtension();

            $pdf_file->move(public_path('uploads'), $pdf_fileName);

            $pdf_fileUrl = asset('uploads/' . $pdf_fileName);

            $data['pdf_file'] = $pdf_fileUrl;
        }

        return Document::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doc = Document::find($id);

        if($doc != null){
            return $doc;
        } else {
            return response()->json([
                'Document not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doc = Document::find($id);

        if($doc != null){
            $doc->update($request->all());
            return $doc;
        } else {
            return response()->json([
                'Document not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doc = Document::find($id);

        if($doc != null){
            $doc->delete();
            return response()->json([
                'Document deleted'
            ]);
        } else {
            return response()->json([
                'Document not found'
            ], 404);
        }
    }
}
