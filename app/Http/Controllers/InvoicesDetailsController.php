<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Fetch the invoice using the provided ID
        $invoices = invoices::query()->where('id', $id)->firstOrFail();

        // Fetch the invoice details for the given invoice ID
        $invoice_details = invoices_details::query()->where('id_Invoice', $id)->get();

        // Fetch the invoice attachments for the given invoice ID
        $invoice_attachments = invoice_attachments::query()->where('invoice_id', $id)->get();
        // Pass the data to the view
       return view('invoices.invoice_details', compact('invoices', 'invoice_details', 'invoice_attachments'));
    }


    public function gitFile($invoice_number, $file_name)
{
    // Construct the file path relative to the public directory
    $filePath = public_path('Attachments/' . $invoice_number . '/' . $file_name);

    // Log the constructed full path
    Log::info("Constructed full path: " . $filePath);

    // Check if the file exists directly
    if (file_exists($filePath)) {
        Log::info("File found: " . $filePath);
        return response()->download($filePath);
    }

    // Log an error if the file does not exist
    Log::error("File not found: " . $filePath);
    return response()->json(['error' => 'File not found'], 404);
}


    public function openFile($invoice_number, $file_name)
    {
        // Construct the file path relative to the public directory
        $filePath = 'Attachments/' . $invoice_number . '/' . $file_name;

        // Log the file path being checked
        Log::info("Checking for file: " . $filePath);

        // Get the full path to the file
        $fullPath = public_path($filePath);

        // Log the full path for debugging
        Log::info("Full path to check: " . $fullPath);

        // Check if the file exists directly
        if (file_exists($fullPath)) {
            return response()->file($fullPath, [
                'Content-Type' => 'image/jpeg', // Change this according to your image type
                'Content-Disposition' => 'inline', // Inline to display in the browser
            ]);
        }

        // Log an error if the file does not exist
        Log::error("File not found: " . $fullPath);
        return response()->json(['error' => 'File not found'], 404);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Find the invoice attachment record and delete it
        $invoice = invoice_attachments::findOrFail($request->id_file);
        $invoice->delete();

        // Flash the success message
        session()->flash('delete', 'تم حذف المرفق بنجاح');

        return back();
    }



}
