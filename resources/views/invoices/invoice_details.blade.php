@extends('layouts.master')
@section('css')
    <!---Internal  Prism css-->
    <link href="{{URL::asset('assets/plugins/prism/prism.css')}}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{URL::asset('assets/plugins/inputtags/inputtags.css')}}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{session()->get('Add')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session()->has('delete'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <!-- div -->
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-2">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات الفاتورة</a></li>
                                            <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الدفع</a></li>
                                            <li><a href="#tab6" class="nav-link" data-toggle="tab">المرفقات</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab4">
                                            <div class="table-responsive mt-15">
                                                <table class="table table-striped mg-b-0 text-md-nowrap">
                                                    <tr>
                                                        <th scope="row">رقم الفاتورة</th>
                                                        <td>{{$invoices->invoice_number}}</td>
                                                        <th scope="row">تاريخ الإصدار</th>
                                                        <td>{{$invoices->invoice_Date}}</td>
                                                        <th scope="row">تاريخ الاستحقاق</th>
                                                        <td>{{$invoices->Due_date}}</td>
                                                        <th scope="row">القسم</th>
                                                        <td>{{$invoices->Section->section_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">المنتج</th>
                                                        <td>{{$invoices->product}}</td>
                                                        <th scope="row">مبلغ التحصيل</th>
                                                        <td>{{$invoices->Amount_collection}}</td>
                                                        <th scope="row">مبلغ العمولة</th>
                                                        <td>{{$invoices->Amount_Commission}}</td>
                                                        <th scope="row">الخصم</th>
                                                        <td>{{$invoices->Discount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">نسبة الضريبة</th>
                                                        <td>{{$invoices->Rate_VAT}}</td>
                                                        <th scope="row">قيمة الضريبة</th>
                                                        <td>{{$invoices->Value_VAT}}</td>
                                                        <th scope="row">الإجمالي مع الضريبة</th>
                                                        <td>{{$invoices->Total}}</td>
                                                        <th scope="row">الحالة الحالية</th>
                                                        @if($invoices->Value_status == 1)
                                                            <td>
                                                                <span class="badge badge-pill badge-success">{{$invoices->Status}}</span>
                                                            </td>
                                                        @elseif($invoices->Value_status == 2)
                                                            <td>
                                                                <span class="badge badge-pill badge-danger">{{$invoices->Status}}</span>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <span class="badge badge-pill badge-warning">{{$invoices->Status}}</span>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">المستخدم</th>
                                                        <td>{{$invoices->user}}</td>
                                                        <th scope="row">الملاحظات</th>
                                                        <td>{{$invoices->note}}</td>
                                                    </tr>


                                                </table>
                                            </div><!-- bd -->
                                        </div>
                                        <div class="tab-pane" id="tab5">
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table-hover" style="text-align: center">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th>#</th>
                                                            <th>رقم الفاتورة</th>
                                                            <th>نوع المنتج</th>
                                                            <th>القسم</th>
                                                            <th>حالة الدفع</th>
                                                            <th>تاريخ الدفع</th>
                                                            <th>ملاحظات</th>
                                                            <th>تاريخ الإضافة</th>
                                                            <th>المستخدم</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i = 0; ?>
                                                    @foreach($invoice_details as $x)
                                                    <?php $i++;?>
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>{{$x->Invoices_number}}</td>
                                                            <td>{{$x->product}}</td>
                                                            <td>{{$invoices->Section->section_name}}</td>
                                                            @if($x->Value_Status == 1)
                                                                <td>
                                                                    <span class="badge badge-pill badge-success">{{$x->Status}}</span>
                                                                </td>
                                                            @elseif($x->Value_Status == 2)
                                                                <td>
                                                                    <span class="badge badge-pill badge-danger">{{$x->Status}}</span>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <span class="badge badge-pill badge-warning">{{$x->Status}}</span>
                                                                </td>
                                                            @endif
                                                            <td>{{$x->Payment_Date}}</td>
                                                            <td>{{$x->note}}</td>
                                                            <td>{{$x->created_at}}</td>
                                                            <td>{{$x->user}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>

                                                </table>
                                            </div><!-- bd -->
                                        </div>

                                        <div class="tab-pane" id="tab6">
                                            <div class="card-body">
                                                <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                                <h5 class="card-title">اضافة مرفقات</h5>
                                                <form method="post" action="{{ url('/InvoiceAttachments') }}" enctype="multipart/form-data">
                                                    @csrf

                                                        <div class="mb-3">
                                                            <input class="form-control" type="file" id="customFile" name="file_name" required>
                                                            <input type="hidden" id="invoice_number" name="invoice_number" value="{{ $invoices->invoice_number }}">
                                                            <input type="hidden" id="invoice_id" name="invoice_id" value="{{ $invoices->id }}">
                                                        </div><br>
                                                    <button type="submit" class="btn btn-primary btn-sm" name="uploadedFile">تاكيد</button>
                                                </form>
                                            </div>


                                            <br>
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table-hover"
                                                       style="text-align: center">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th>م</th>
                                                            <th>اسم الملف</th>
                                                            <th>قام بالإضافة</th>
                                                            <th>تاريخ الإضافة</th>
                                                            <th>العمليات</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 0; ?>
                                                        @foreach($invoice_attachments as $x)
                                                                <?php $i++;?>
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>{{$x->file_name}}</td>
                                                                <td>{{$x->Created_by}}</td>
                                                                <td>{{$invoices->created_at}}</td>
                                                                <td colspan="2">
                                                                    <a class="btn btn-outline-success btn-sm"
                                                                       href="{{ url('View_file') }}/{{ $invoices->invoice_number }}/{{ $x->file_name }}"
                                                                       role="button" target="_blank"> <!-- Add target="_blank" here -->
                                                                        <i class="fas fa-eye">&nbsp; عرض </i>
                                                                    </a>


                                                                    <a class="btn btn-outline-info btn-sm"
                                                                       href="{{url('download')}}/{{$invoices->invoice_number}}/{{$x->file_name }}"
                                                                       role="button">
                                                                        <i class="fas fa-download">&nbsp; تحميل </i>
                                                                    </a>

                                                                    <button class="btn btn-outline-danger btn-sm"
                                                                       data-toggle="modal"
                                                                       data-file_name="{{$x->file_name}}"
                                                                       data-invoice_number="{{$x->invoice_number}}"
                                                                       data-id_file="{{$x->id}}"
                                                                       data-target="#delete_file">
                                                                       حذف
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><!-- bd -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /div -->
    </div>
    <!-- /row -->

    <!-- Delete File Modal -->
    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="deleteFileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFileModalLabel">حذف المرفق</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteForm" action="{{ route('delete_file') }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color: red">هل أنت متأكد من عملية حذف المرفق؟</h6>
                        </p>

                        <!-- Hidden inputs for file details -->
                        <input type="hidden" name="id_file" id="id_file" value="">
                        <input type="hidden" name="file_name" id="file_name" value="">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <!-- Internal Select2 js-->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <!-- Internal Input tags js-->
    <script src="{{URL::asset('assets/plugins/inputtags/inputtags.js')}}"></script>
    <!--- Tabs JS-->
    <script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
    <script src="{{URL::asset('assets/js/tabs.js')}}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{URL::asset('assets/plugins/clipboard/clipboard.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/clipboard/clipboard.js')}}"></script>
    <!-- Internal Prism js-->
    <script src="{{URL::asset('assets/plugins/prism/prism.js')}}"></script>



    <script>
        $(document).ready(function() {
            // When the delete button is clicked, populate the form with necessary data
            $('[data-toggle="modal"]').on('click', function() {
                var idFile = $(this).data('id_file');
                var fileName = $(this).data('file_name');
                var invoiceNumber = $(this).data('invoice_number');

                // Set the form fields with the data
                $('#id_file').val(idFile);
                $('#file_name').val(fileName);
                $('#invoice_number').val(invoiceNumber);
            });
        });

    </script>
    <script>
        // Update the custom file input label to show the selected file name
        $(document).ready(function() {
            $('#customFile').on('change', function() {
                // Get the file name
                var fileName = $(this).val().split('\\').pop();
                // Update the label with the file name
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>

@endsection
