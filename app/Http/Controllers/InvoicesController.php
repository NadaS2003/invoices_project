<?php

namespace App\Http\Controllers;

use App\Exports\invoicesExport;
use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\sections;
use App\Models\User;
use App\Notifications\Add_invoice;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use voku\helper\ASCII;
use function Symfony\Component\String\b;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices =invoices::all();
        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = sections::all();
        return view('invoices\add_invoices', compact('sections'));
    }


    public function getProducts($id){
        $products = DB::table("products")->where('section_id',$id)->pluck('product_name','id');
        return json_encode($products);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'Invoices_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => Auth::user()->name,
        ]);

        if ($request->hasFile('pic')) {
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            $image->move(public_path('Attachments/' . $invoice_number), $file_name);
        }

        //Mail
        //        Notification::send($user,new AddInvoice($invoice_id));
//اشعار فقط للي انشا الفاتورة
        //  $user = User::query()->find(Auth::user()->id);
     //   $user->notify(new Add_invoice($invoices));
        $user = User::all(); // اشعار للجميع
        $invoices= invoices::latest()->first();
        Notification::send($user, new Add_invoice($invoices));

        // Flash message and redirect
        session()->flash('Add', 'تم إضافة الفاتورة بنجاح');
        return redirect()->back(); // Redirect to invoices page
    }

    public function MarkAsRead_all(Request $request){
        $userUnreadNotification = \auth()->user()->unreadNotifications;
        if ($userUnreadNotification){
            $userUnreadNotification->markAsRead();
            return back();
        }
    }

    public function invoice_paid(){
        $invoices = invoices::query()->where('Value_status',1)->get();
        return view('invoices.invoice_paid',compact('invoices'));
    }

    public function invoice_unpaid(){
        $invoices = invoices::query()->where('Value_status','=',2)->get();
        return view('invoices.invoice_unpaid',compact('invoices'));
    }

    public function invoice_partial(){
        $invoices = invoices::query()->where('Value_status','=',3)->get();
        return view('invoices.invoice_partial',compact('invoices'));
    }

    public function show($id)
    {
        $invoices = invoices::query()->where('id',$id)->first();
        return view('invoices.status_update',compact('invoices'));
    }

    public function print($id){

        $invoices = invoices::query()->where('id','=',$id)->first();
        return view('invoices.Print_invoice',compact('invoices'));
    }

    public function Status_Update($id,Request $request){
        $invoices = invoices::query()->findOrFail($id);

        if ($request->Status === 'مدفوعة'){
            $invoices->update([
                'Value_Status'=>1,
                'Status'=>$request->Status,
                'Payment_Date'=>$request->Payment_Date
            ]);

            invoices_details::create([
                'id_Invoice'=>$request->invoice_id,
                'Invoices_number'=>$request->invoice_number,
                'product'=>$request->product,
                'Section'=>$request->Section,
                'Status'=>$request->Status,
                'Value_Status'=>1,
                'note'=>$request->note,
                'Payment_Date'=>$request->Payment_Date,
                'user'=>(Auth::user()->name),
            ]);
        }else{
            $invoices->update([
                'Value_Status'=>3,
                'Status'=>$request->Status,
                'Payment_Date'=>$request->Payment_Date
            ]);

            invoices_details::create([
                'id_Invoice'=>$request->invoice_id,
                'Invoices_number'=>$request->invoice_number,
                'product'=>$request->product,
                'Section'=>$request->Section,
                'Status'=>$request->Status,
                'Value_Status'=>3,
                'note'=>$request->note,
                'Payment_Date'=>$request->Payment_Date,
                'user'=>(Auth::user()->name),
            ]);
        }

        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = invoices::query()->where('id',$id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoice',compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices $invoices)
    {
        $invoices = invoices::query()->findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();

        $id_page =$request->id_page;


        if (!$id_page == 2) {
            if (!$invoices) {
                return redirect()->back()->with('error', 'Invoice not found.');
            }

            if ($Details && !empty($Details->invoice_number)) {
                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }

            $invoices->forceDelete();

            session()->flash('delete_invoice', 'Invoice deleted successfully.');
            return redirect('/invoices');
        }


        else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/invoices/Archive');
        }



    }

    public function export()
    {
        return Excel::download(new invoicesExport, 'invoices.xlsx');
    }




}
