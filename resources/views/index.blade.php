<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Finance & Accounting Mini Project</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 70px;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .content-wrapper {
            padding: 20px 0;
        }
        .page-header {
            border-bottom: 2px solid #337ab7;
            margin-bottom: 30px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .btn-toolbar {
            margin-bottom: 20px;
        }
        .modal-header {
            background-color: #337ab7;
            color: white;
        }
        .modal-header .close {
            color: white;
        }
        .balance-summary {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .text-success {
            color: #5cb85c !important;
        }
        .text-danger {
            color: #d9534f !important;
        }
        .text-warning {
            color: #f0ad4e !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><i class="fa fa-calculator"></i> Finance & Accounting</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#" onclick="showPage('coa')">Chart of Accounts</a></li>
                    <li><a href="#" onclick="showPage('journals')">Journals</a></li>
                    <li><a href="#" onclick="showPage('invoices')">Invoices</a></li>
                    <li><a href="#" onclick="showPage('payments')">Payments</a></li>
                    <li><a href="#" onclick="showPage('trial-balance')">Trial Balance</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- Chart of Accounts Page -->
        <div id="coa-page" class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1><i class="fa fa-list-alt"></i> Chart of Accounts</h1>
                    </div>

                    <div class="btn-toolbar">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#coaModal" onclick="openCoaModal()">
                            <i class="fa fa-plus"></i> Add Account
                        </button>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                Filter <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="filterCoa('all')">All Accounts</a></li>
                                <li><a href="#" onclick="filterCoa('active')">Active Only</a></li>
                                <li><a href="#" onclick="filterCoa('inactive')">Inactive Only</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="coa-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Normal Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Journals Page -->
        <div id="journals-page" class="content-wrapper" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1><i class="fa fa-book"></i> Journal Entries</h1>
                    </div>

                    <div class="btn-toolbar">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#journalModal" onclick="openJournalModal()">
                            <i class="fa fa-plus"></i> New Journal Entry
                        </button>
                        <div class="form-group" style="display: inline-block; margin-left: 20px;">
                            <div class="input-group">
                                <input type="date" id="journal-start-date" class="form-control" style="width: 150px;">
                                <span class="input-group-addon">to</span>
                                <input type="date" id="journal-end-date" class="form-control" style="width: 150px;">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" onclick="filterJournals()">Filter</button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="journals-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Ref No</th>
                                    <th>Date</th>
                                    <th>Memo</th>
                                    <th>Debit Total</th>
                                    <th>Credit Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Page -->
        <div id="invoices-page" class="content-wrapper" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1><i class="fa fa-file-text-o"></i> Invoices</h1>
                    </div>

                    <div class="btn-toolbar">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#invoiceModal" onclick="openInvoiceModal()">
                            <i class="fa fa-plus"></i> New Invoice
                        </button>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                Status Filter <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="filterInvoices('all')">All Status</a></li>
                                <li><a href="#" onclick="filterInvoices('open')">Open</a></li>
                                <li><a href="#" onclick="filterInvoices('partial')">Partial</a></li>
                                <li><a href="#" onclick="filterInvoices('paid')">Paid</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="invoices-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Tax</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Page -->
        <div id="payments-page" class="content-wrapper" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1><i class="fa fa-credit-card"></i> Payments</h1>
                    </div>

                    <div class="btn-toolbar">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#paymentModal" onclick="openPaymentModal()">
                            <i class="fa fa-plus"></i> New Payment
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="payments-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Payment Ref</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount Paid</th>
                                    <th>Invoice No</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trial Balance Page -->
        <div id="trial-balance-page" class="content-wrapper" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1><i class="fa fa-balance-scale"></i> Trial Balance</h1>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Range:</label>
                                <div class="input-group">
                                    <input type="date" id="tb-start-date" class="form-control">
                                    <span class="input-group-addon">to</span>
                                    <input type="date" id="tb-end-date" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" onclick="generateTrialBalance()">
                                            <i class="fa fa-search"></i> Generate
                                        </button>
                                        <button class="btn btn-success" onclick="exportTrialBalance()">
                                            <i class="fa fa-download"></i> Export Excel
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="trial-balance-content"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- COA Modal -->
    <div class="modal fade" id="coaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Chart of Account</h4>
                </div>
                <form id="coa-form">
                    <div class="modal-body">
                        <input type="hidden" id="coa-id">
                        <div class="form-group">
                            <label>Code *</label>
                            <input type="text" class="form-control" id="coa-code" required maxlength="10">
                        </div>
                        <div class="form-group">
                            <label>Name *</label>
                            <input type="text" class="form-control" id="coa-name" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label>Normal Balance *</label>
                            <select class="form-control" id="coa-normal-balance" required>
                                <option value="">Select...</option>
                                <option value="DR">Debit (DR)</option>
                                <option value="CR">Credit (CR)</option>
                            </select>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="coa-is-active" checked> Active
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Journal Modal -->
    <div class="modal fade" id="journalModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Journal Entry</h4>
                </div>
                <form id="journal-form">
                    <div class="modal-body">
                        <input type="hidden" id="journal-id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Reference No *</label>
                                    <input type="text" class="form-control" id="journal-ref-no" required maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Posting Date *</label>
                                    <input type="date" class="form-control" id="journal-posting-date" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Memo</label>
                            <textarea class="form-control" id="journal-memo" rows="2" maxlength="255"></textarea>
                        </div>

                        <h5>Journal Lines</h5>
                        <div class="balance-summary">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Total Debit: </strong><span id="total-debit" class="text-info">0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Credit: </strong><span id="total-credit" class="text-info">0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Balance: </strong><span id="balance-check" class="text-success">0.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="journal-lines-table">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="journal-lines-body"></tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-default" onclick="addJournalLine()">
                            <i class="fa fa-plus"></i> Add Line
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Invoice</h4>
                </div>
                <form id="invoice-form">
                    <div class="modal-body">
                        <input type="hidden" id="invoice-id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice No *</label>
                                    <input type="text" class="form-control" id="invoice-no" required maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Invoice Date *</label>
                                    <input type="date" class="form-control" id="invoice-date" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Customer *</label>
                            <input type="text" class="form-control" id="invoice-customer" required maxlength="120">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount *</label>
                                    <input type="number" class="form-control" id="invoice-amount" required min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tax Amount</label>
                                    <input type="number" class="form-control" id="invoice-tax-amount" min="0" step="0.01" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="invoice-status">
                                <option value="open">Open</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Payment</h4>
                </div>
                <form id="payment-form">
                    <div class="modal-body">
                        <input type="hidden" id="payment-id">
                        <div class="form-group">
                            <label>Invoice *</label>
                            <select class="form-control" id="payment-invoice-id" required>
                                <option value="">Select Invoice...</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Reference</label>
                                    <input type="text" class="form-control" id="payment-ref" maxlength="30">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Date *</label>
                                    <input type="date" class="form-control" id="payment-date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount Paid *</label>
                                    <input type="number" class="form-control" id="payment-amount" required min="0.01" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select class="form-control" id="payment-method">
                                        <option value="">Select...</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Check">Check</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Journal Detail Modal -->
    <div class="modal fade" id="journalDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title" id="journalDetailModalLabel">Journal Entry Details</h4>
                </div>
                <div class="modal-body">
                    <div id="journal-detail-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Detail Modal -->
    <div class="modal fade" id="invoiceDetailModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title" id="invoiceDetailModalLabel">Invoice Detail</h4>
            </div>
            <div class="modal-body" id="invoiceDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Payment Detail Modal -->
    <div class="modal fade" id="paymentDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="paymentDetailModalLabel">Payment Detail</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="paymentDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

    <script>
        const API_BASE_URL = '/api';
        let accountsCache = [];
        let invoicesCache = [];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {

            showPage('coa');
            loadAccountsCache();
            loadInvoicesCache();
        });

        function showPage(page) {
            $('.content-wrapper').hide();

            $('.nav li').removeClass('active');

            $('#' + page + '-page').show();
            $('a[onclick="showPage(\'' + page + '\')"]').parent().addClass('active');

            switch(page) {
                case 'coa':
                    initCoaTable();
                    break;
                case 'journals':
                    initJournalsTable();
                    break;
                case 'invoices':
                    initInvoicesTable();
                    break;
                case 'payments':
                    initPaymentsTable();
                    break;
                case 'trial-balance':
                    initTrialBalance();
                    break;
            }
        }

        // Utility
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }

        function formatNumber(amount) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }

        function showAlert(message, type = 'success') {
            const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
            const alert = $('<div class="alert ' + alertClass + ' alert-dismissible" role="alert">' +
                           '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' +
                           message + '</div>');

            $('.page-header').after(alert);
            setTimeout(() => alert.fadeOut(), 5000);
        }

        function loadAccountsCache() {
            $.get(API_BASE_URL + '/chart-of-accounts?per_page=1000', function(response) {
                if (response.success) {
                    accountsCache = response.data;
                }
            });
        }

        function loadInvoicesCache() {
            $.get(API_BASE_URL + '/invoices?per_page=1000', function(response) {
                if (response.success) {
                    invoicesCache = response.data;
                }
            });
        }

        // Chart of Accounts
        function initCoaTable() {
            if ($.fn.DataTable.isDataTable('#coa-table')) {
                $('#coa-table').DataTable().destroy();
            }

            $('#coa-table').DataTable({
                ajax: {
                    url: API_BASE_URL + '/chart-of-accounts',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'code' },
                    { data: 'name' },
                    { data: 'normal_balance' },
                    {
                        data: 'is_active',
                        render: function(data) {
                            return data ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(row) {
                            return '<button class="btn btn-xs btn-primary" onclick="editCoa(' + row.id + ')"><i class="fa fa-edit"></i> Edit</button> ' +
                                   '<button class="btn btn-xs btn-danger" onclick="deleteCoa(' + row.id + ')"><i class="fa fa-trash"></i> Delete</button>';
                        }
                    }
                ],
                order: [[0, 'asc']],
                pageLength: 25
            });
        }

        function openCoaModal(id = null) {
            $('#coa-form')[0].reset();
            $('#coa-id').val('');
            $('.modal-title').text('Add Chart of Account');

            if (id) {
                $('.modal-title').text('Edit Chart of Account');
                $.get(API_BASE_URL + '/chart-of-accounts/' + id, function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#coa-id').val(data.id);
                        $('#coa-code').val(data.code);
                        $('#coa-name').val(data.name);
                        $('#coa-normal-balance').val(data.normal_balance);
                        $('#coa-is-active').prop('checked', data.is_active);
                    }
                });
            }
        }

        function editCoa(id) {
            openCoaModal(id);
            $('#coaModal').modal('show');
        }

        function deleteCoa(id) {
            if (confirm('Are you sure you want to delete this account?')) {
                $.ajax({
                    url: API_BASE_URL + '/chart-of-accounts/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        showAlert('Account deleted successfully');
                        $('#coa-table').DataTable().ajax.reload();
                        loadAccountsCache();
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON;
                        showAlert(error.message || 'Error deleting account', 'error');
                    }
                });
            }
        }

        function filterCoa(filter) {
            let url = API_BASE_URL + '/chart-of-accounts';
            if (filter === 'active') url += '?is_active=1';
            else if (filter === 'inactive') url += '?is_active=0';

            $('#coa-table').DataTable().ajax.url(url).load();
        }

        $('#coa-form').on('submit', function(e) {
            e.preventDefault();

            const id = $('#coa-id').val();
            const data = {
                code: $('#coa-code').val(),
                name: $('#coa-name').val(),
                normal_balance: $('#coa-normal-balance').val(),
                is_active: $('#coa-is-active').prop('checked') ? 1 : 0
            };

            const method = id ? 'PUT' : 'POST';
            const url = id ? API_BASE_URL + '/chart-of-accounts/' + id : API_BASE_URL + '/chart-of-accounts';

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    $('#coaModal').modal('hide');
                    showAlert(response.message || (id ? 'Account updated successfully' : 'Account created successfully'));
                    $('#coa-table').DataTable().ajax.reload();
                    loadAccountsCache();
                },
                error: function(xhr) {
                    const error = xhr.responseJSON;
                    showAlert(error.message || 'Error saving account', 'error');
                }
            });
        });

        // Journals
        function initJournalsTable() {
            if ($.fn.DataTable.isDataTable('#journals-table')) {
                $('#journals-table').DataTable().destroy();
            }

            $('#journals-table').DataTable({
                ajax: {
                    url: API_BASE_URL + '/journals',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'ref_no' },
                    {
                        data: 'posting_date',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID');
                        }
                    },
                    { data: 'memo' },
                    {
                        data: 'total_debit',
                        render: function(data) {
                            return formatNumber(data);
                        },
                        className: 'text-right'
                    },
                    {
                        data: 'total_credit',
                        render: function(data) {
                            return formatNumber(data);
                        },
                        className: 'text-right'
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(row) {
                            return '<button class="btn btn-xs btn-info" onclick="viewJournalDetail(' + row.id + ')"><i class="fa fa-eye"></i> Detail</button> ' +
                                   '<button class="btn btn-xs btn-primary" onclick="editJournal(' + row.id + ')"><i class="fa fa-edit"></i> Edit</button> ' +
                                   '<button class="btn btn-xs btn-danger" onclick="deleteJournal(' + row.id + ')"><i class="fa fa-trash"></i> Delete</button>';
                        }
                    }
                ],
                order: [[1, 'desc']],
                pageLength: 25
            });
        }

        function openJournalModal(id = null) {
            $('#journal-form')[0].reset();
            $('#journal-id').val('');
            $('#journal-lines-body').empty();
            $('.modal-title').text('Add Journal Entry');
            updateJournalBalance();

            // Add default empty lines
            addJournalLine();
            addJournalLine();

            if (id) {
                $('.modal-title').text('Edit Journal Entry');
                $.get(API_BASE_URL + '/journals/' + id, function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#journal-id').val(data.id);
                        $('#journal-ref-no').val(data.ref_no);
                        $('#journal-posting-date').val(data.posting_date);
                        $('#journal-memo').val(data.memo);

                        // Clear existing lines and add data lines
                        $('#journal-lines-body').empty();
                        data.lines.forEach(function(line) {
                            addJournalLine(line);
                        });
                        updateJournalBalance();
                    }
                });
            }
        }

        function addJournalLine(lineData = null) {
            const lineIndex = $('#journal-lines-body tr').length;
            let accountOptions = '<option value="">Select Account...</option>';

            accountsCache.forEach(function(account) {
                if (account.is_active) {
                    const selected = lineData && lineData.account_id == account.id ? 'selected' : '';
                    accountOptions += '<option value="' + account.id + '" ' + selected + '>' + account.code + ' - ' + account.name + '</option>';
                }
            });

            const debitValue = lineData ? lineData.debit : '';
            const creditValue = lineData ? lineData.credit : '';

            const row = $(`
                <tr>
                    <td>
                        <select class="form-control account-select" name="lines[${lineIndex}][account_id]" required>
                            ${accountOptions}
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control debit-input" name="lines[${lineIndex}][debit]"
                               value="${debitValue}" min="0" step="0.01" onchange="updateJournalBalance()">
                    </td>
                    <td>
                        <input type="number" class="form-control credit-input" name="lines[${lineIndex}][credit]"
                               value="${creditValue}" min="0" step="0.01" onchange="updateJournalBalance()">
                    </td>
                    <td>
                        <button type="button" class="btn btn-xs btn-danger" onclick="removeJournalLine(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            $('#journal-lines-body').append(row);
        }

        function removeJournalLine(button) {
            $(button).closest('tr').remove();
            updateJournalBalance();
        }

        function updateJournalBalance() {
            let totalDebit = 0;
            let totalCredit = 0;

            $('.debit-input').each(function() {
                totalDebit += parseFloat($(this).val() || 0);
            });

            $('.credit-input').each(function() {
                totalCredit += parseFloat($(this).val() || 0);
            });

            const balance = totalDebit - totalCredit;

            $('#total-debit').text(formatNumber(totalDebit));
            $('#total-credit').text(formatNumber(totalCredit));
            $('#balance-check').text(formatNumber(Math.abs(balance)));

            if (balance === 0) {
                $('#balance-check').removeClass('text-danger').addClass('text-success');
            } else {
                $('#balance-check').removeClass('text-success').addClass('text-danger');
            }
        }

        function viewJournalDetail(id) {
            $('#journalDetailModalLabel').text('Journal Entry Detail');

            $.get(API_BASE_URL + '/journals/' + id, function(response) {
                if (response.success) {
                    const journal = response.data;
                    let linesHtml = `
                        <div class="row">
                            <div class="col-md-6"><strong>Reference No:</strong> ${journal.ref_no}</div>
                            <div class="col-md-6"><strong>Posting Date:</strong> ${new Date(journal.posting_date).toLocaleDateString('id-ID')}</div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-12"><strong>Memo:</strong> ${journal.memo || '-'}</div>
                        </div>
                        <hr>
                        <h5>Journal Lines</h5>
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th>Account Code</th>
                                    <th>Account Name</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    journal.lines.forEach(function(line) {
                        linesHtml += `
                            <tr>
                                <td>${line.account_code}</td>
                                <td>${line.account_name}</td>
                                <td class="text-right">${line.debit > 0 ? formatNumber(line.debit) : '-'}</td>
                                <td class="text-right">${line.credit > 0 ? formatNumber(line.credit) : '-'}</td>
                            </tr>
                        `;
                    });

                    linesHtml += `
                            </tbody>
                            <tfoot>
                                <tr class="info">
                                    <th colspan="2">TOTAL</th>
                                    <th class="text-right">${formatNumber(journal.total_debit)}</th>
                                    <th class="text-right">${formatNumber(journal.total_credit)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    `;

                    $('#journal-detail-content').html(linesHtml);
                    $('#journalDetailModal').modal('show');
                }
            });
        }

        function editJournal(id) {
            openJournalModal(id);
            $('#journalModal').modal('show');
        }

        function deleteJournal(id) {
            if (confirm('Are you sure you want to delete this journal entry?')) {
                $.ajax({
                    url: API_BASE_URL + '/journals/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        showAlert('Journal entry deleted successfully');
                        $('#journals-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON;
                        showAlert(error.message || 'Error deleting journal entry', 'error');
                    }
                });
            }
        }

        function filterJournals() {
            const startDate = $('#journal-start-date').val();
            const endDate = $('#journal-end-date').val();

            let url = API_BASE_URL + '/journals';
            const params = [];

            if (startDate) params.push('start_date=' + startDate);
            if (endDate) params.push('end_date=' + endDate);

            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            $('#journals-table').DataTable().ajax.url(url).load();
        }

        $('#journal-form').on('submit', function(e) {
            e.preventDefault();

            const id = $('#journal-id').val();
            const lines = [];

            $('#journal-lines-body tr').each(function() {
                const accountId = $(this).find('.account-select').val();
                const debit = parseFloat($(this).find('.debit-input').val() || 0);
                const credit = parseFloat($(this).find('.credit-input').val() || 0);

                if (accountId && (debit > 0 || credit > 0)) {
                    lines.push({
                        account_id: parseInt(accountId),
                        debit: debit,
                        credit: credit
                    });
                }
            });

            if (lines.length < 2) {
                showAlert('Journal entry must have at least 2 lines', 'error');
                return;
            }

            const data = {
                ref_no: $('#journal-ref-no').val(),
                posting_date: $('#journal-posting-date').val(),
                memo: $('#journal-memo').val(),
                status: 'posted',
                lines: lines
            };

            const method = id ? 'PUT' : 'POST';
            const url = id ? API_BASE_URL + '/journals/' + id : API_BASE_URL + '/journals';

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    $('#journalModal').modal('hide');
                    showAlert(response.message || (id ? 'Journal entry updated successfully' : 'Journal entry created successfully'));
                    $('#journals-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    const error = xhr.responseJSON;
                    showAlert(error.message || 'Error saving journal entry', 'error');
                }
            });
        });

        // Invoices
        function initInvoicesTable() {
            if ($.fn.DataTable.isDataTable('#invoices-table')) {
                $('#invoices-table').DataTable().destroy();
            }

            $('#invoices-table').DataTable({
                ajax: {
                    url: API_BASE_URL + '/invoices',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'invoice_no' },
                    {
                        data: 'invoice_date',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID');
                        }
                    },
                    { data: 'customer' },
                    {
                        data: 'amount',
                        render: function(data) {
                            return formatNumber(data);
                        },
                        className: 'text-right'
                    },
                    {
                        data: 'tax_amount',
                        render: function(data) {
                            return formatNumber(data);
                        },
                        className: 'text-right'
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            let labelClass = 'label-default';
                            if (data === 'open') labelClass = 'label-warning';
                            else if (data === 'partial') labelClass = 'label-info';
                            else if (data === 'paid') labelClass = 'label-success';

                            return '<span class="label ' + labelClass + '">' + data.toUpperCase() + '</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(row) {
                            return '<button class="btn btn-xs btn-primary" onclick="editInvoice(' + row.id + ')"><i class="fa fa-money"></i> Edit</button> ' +
                                   '<button class="btn btn-xs btn-info" onclick="viewInvoiceDetail(' + row.id + ')"><i class="fa fa-eye"></i> Detail</button>';
                        }
                    }
                ],
                order: [[1, 'desc']],
                pageLength: 25
            });
        }

        function openInvoiceModal(id = null) {
            $('#invoice-form')[0].reset();
            $('#invoice-id').val('');
            $('.modal-title').text('Add Invoice');

            $('#invoice-amount, #invoice-tax-amount').prop('readonly', false);

            if (id) {
                $('.modal-title').text('Edit Invoice');
                $.get(API_BASE_URL + '/invoices/' + id, function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#invoice-id').val(data.id);
                        $('#invoice-no').val(data.invoice_no);
                        $('#invoice-date').val(data.invoice_date);
                        $('#invoice-customer').val(data.customer);
                        $('#invoice-amount').val(data.amount);
                        $('#invoice-tax-amount').val(data.tax_amount);
                        $('#invoice-status').val(data.status);

                        if (data.payments && data.payments.length > 0) {
                            $('#invoice-amount, #invoice-tax-amount').prop('readonly', true);
                        }
                    }
                });
            }
        }


        function editInvoice(id) {
            openInvoiceModal(id);
            $('#invoiceModal').modal('show');
        }

        function viewInvoiceDetail(id) {
            $('#invoiceDetailModalLabel').text('Invoice Detail');

            $.get(API_BASE_URL + '/invoices/' + id, function(response) {
                if (response.success) {
                    const inv = response.data;

                    let html = `
                        <p><strong>Invoice No:</strong> ${inv.invoice_no}</p>
                        <p><strong>Date:</strong> ${new Date(inv.invoice_date).toLocaleDateString('id-ID')}</p>
                        <p><strong>Customer:</strong> ${inv.customer}</p>
                        <p><strong>Amount:</strong> ${formatNumber(inv.amount)}</p>
                        <p><strong>Tax:</strong> ${formatNumber(inv.tax_amount)}</p>
                        <p><strong>Total:</strong> ${formatNumber(inv.total_amount)}</p>
                        <p><strong>Status:</strong> ${inv.status.toUpperCase()}</p>
                    `;

                    if (inv.payments && inv.payments.length > 0) {
                        html += `<h5>Payments</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ref</th><th>Date</th><th>Method</th><th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                        inv.payments.forEach(p => {
                            html += `
                                <tr>
                                    <td>${p.payment_ref}</td>
                                    <td>${new Date(p.paid_at).toLocaleDateString('id-ID')}</td>
                                    <td>${p.method ?? '-'}</td>
                                    <td class="text-right">${formatNumber(p.amount_paid)}</td>
                                </tr>`;
                        });
                        html += `</tbody></table>`;
                    }

                    $('#invoiceDetailBody').html(html);
                    $('#invoiceDetailModal').modal('show');
                }
            });
        }


        function filterInvoices(status) {
            let url = API_BASE_URL + '/invoices';
            if (status !== 'all') {
                url += '?status=' + status;
            }

            $('#invoices-table').DataTable().ajax.url(url).load();
        }

        $('#invoice-form').on('submit', function(e) {
            e.preventDefault();

            const id = $('#invoice-id').val();
            const data = {
                invoice_no: $('#invoice-no').val(),
                invoice_date: $('#invoice-date').val(),
                customer: $('#invoice-customer').val(),
                amount: parseFloat($('#invoice-amount').val()),
                tax_amount: parseFloat($('#invoice-tax-amount').val() || 0),
                status: $('#invoice-status').val()
            };

            const method = id ? 'PUT' : 'POST';
            const url = id ? API_BASE_URL + '/invoices/' + id : API_BASE_URL + '/invoices';

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    $('#invoiceModal').modal('hide');
                    showAlert(response.message || (id ? 'Invoice updated successfully' : 'Invoice created successfully'));
                    $('#invoices-table').DataTable().ajax.reload();
                    loadInvoicesCache();
                },
                error: function(xhr) {
                    const error = xhr.responseJSON;
                    showAlert(error.message || 'Error saving invoice', 'error');
                }
            });
        });

        // Payments
        function initPaymentsTable() {
            if ($.fn.DataTable.isDataTable('#payments-table')) {
                $('#payments-table').DataTable().destroy();
            }

            $('#payments-table').DataTable({
                ajax: {
                    url: API_BASE_URL + '/payments',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'payment_ref' },
                    {
                        data: 'paid_at',
                        render: function(data) {
                            return new Date(data).toLocaleDateString('id-ID');
                        }
                    },
                    { data: 'method' },
                    {
                        data: 'amount_paid',
                        render: function(data) {
                            return formatNumber(data);
                        },
                        className: 'text-right'
                    },
                    { data: 'invoice_no' },
                    {
                        data: null,
                        orderable: false,
                        render: function(row) {
                            return '<button class="btn btn-xs btn-danger" onclick="deletePayment(' + row.id + ')"><i class="fa fa-trash"></i> Delete</button> ' +
                                   '<button class="btn btn-xs btn-info" onclick="viewPayment(' + row.id + ')"><i class="fa fa-eye"></i> Detail</button>';
                        }
                    }
                ],
                order: [[3, 'desc']],
                pageLength: 25
            });
        }

        function openPaymentModal() {
            $('#payment-form')[0].reset();
            $('#payment-id').val('');
            $('.modal-title').text('Add Payment');

            let invoiceOptions = '<option value="">Select Invoice...</option>';
            invoicesCache.forEach(function(invoice) {
                if (invoice.status !== 'paid') {
                    const outstandingText = invoice.outstanding_amount > 0 ? ' (Outstanding: ' + formatNumber(invoice.outstanding_amount) + ')' : '';
                    invoiceOptions += '<option value="' + invoice.id + '">' + invoice.invoice_no + ' - ' + invoice.customer + outstandingText + '</option>';
                }
            });
            $('#payment-invoice-id').html(invoiceOptions);
        }

        function viewPayment(id) {
            $('#paymentDetailModalLabel').text('Payment Detail');

            $.get(API_BASE_URL + '/payments/' + id, function(response) {
                if (response.success) {
                    let p = response.data;

                    let html = `
                        <table class="table table-striped">
                            <tr><th>Payment Ref</th><td>${p.payment_ref}</td></tr>
                            <tr><th>Paid At</th><td>${p.paid_at}</td></tr>
                            <tr><th>Method</th><td>${p.method}</td></tr>
                            <tr><th>Amount Paid</th><td>${formatNumber(p.amount_paid)}</td></tr>
                            <tr><th>Invoice No</th><td>${p.invoice_no}</td></tr>
                            <tr><th>Invoice Total</th><td>${formatNumber(p.invoice_total)}</td></tr>
                            <tr><th>Invoice Status</th><td>${p.invoice_status}</td></tr>
                        </table>
                    `;

                    $('#paymentDetailBody').html(html);
                    $('#paymentDetailModal').modal('show');
                }
            });
        }


        function deletePayment(id) {
            if (confirm('Are you sure you want to delete this payment?')) {
                $.ajax({
                    url: API_BASE_URL + '/payments/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        showAlert('Payment deleted successfully');
                        $('#payments-table').DataTable().ajax.reload();
                        $('#invoices-table').DataTable().ajax.reload();
                        loadInvoicesCache();
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON;
                        showAlert(error.message || 'Error deleting payment', 'error');
                    }
                });
            }
        }

        $('#payment-form').on('submit', function(e) {
            e.preventDefault();

            const data = {
                invoice_id: parseInt($('#payment-invoice-id').val()),
                payment_ref: $('#payment-ref').val(),
                paid_at: $('#payment-date').val(),
                amount_paid: parseFloat($('#payment-amount').val()),
                method: $('#payment-method').val()
            };

            $.ajax({
                url: API_BASE_URL + '/payments',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    $('#paymentModal').modal('hide');
                    showAlert(response.message || 'Payment created successfully');
                    $('#payments-table').DataTable().ajax.reload();
                    $('#invoices-table').DataTable().ajax.reload();
                    loadInvoicesCache();
                },
                error: function(xhr) {
                    const error = xhr.responseJSON;
                    showAlert(error.message || 'Error saving payment', 'error');
                }
            });
        });

        // Trial Balance
        function initTrialBalance() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            $('#tb-start-date').val(firstDay.toISOString().split('T')[0]);
            $('#tb-end-date').val(today.toISOString().split('T')[0]);
        }

        function generateTrialBalance() {
            const startDate = $('#tb-start-date').val();
            const endDate = $('#tb-end-date').val();

            if (!startDate || !endDate) {
                showAlert('Please select both start and end dates', 'error');
                return;
            }

            $.get(API_BASE_URL + '/trial-balance', {
                start: startDate,
                end: endDate
            }, function(response) {
                if (response.success) {
                    displayTrialBalance(response.data, response.period);
                }
            }).fail(function(xhr) {
                const error = xhr.responseJSON;
                showAlert(error.message || 'Error generating trial balance', 'error');
            });
        }

        function displayTrialBalance(data, period) {
            let totalOpeningBalance = 0;
            let totalDebit = 0;
            let totalCredit = 0;
            let totalClosingBalance = 0;

            let html = `
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Trial Balance Report</h4>
                        <small>Period: ${new Date(period.start).toLocaleDateString('id-ID')} to ${new Date(period.end).toLocaleDateString('id-ID')}</small>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr class="info">
                                    <th>Account Code</th>
                                    <th>Account Name</th>
                                    <th class="text-right">Opening Balance</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                    <th class="text-right">Closing Balance</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            data.forEach(function(row) {
                html += `
                    <tr>
                        <td>${row.account_code}</td>
                        <td>${row.account_name}</td>
                        <td class="text-right">${formatNumber(row.opening_balance)}</td>
                        <td class="text-right">${formatNumber(row.debit)}</td>
                        <td class="text-right">${formatNumber(row.credit)}</td>
                        <td class="text-right">${formatNumber(row.closing_balance)}</td>
                    </tr>
                `;

                totalOpeningBalance += row.opening_balance;
                totalDebit += row.debit;
                totalCredit += row.credit;
                totalClosingBalance += row.closing_balance;
            });

            html += `
                            </tbody>
                            <tfoot>
                                <tr class="success">
                                    <th colspan="2">TOTAL</th>
                                    <th class="text-right">${formatNumber(totalOpeningBalance)}</th>
                                    <th class="text-right">${formatNumber(totalDebit)}</th>
                                    <th class="text-right">${formatNumber(totalCredit)}</th>
                                    <th class="text-right">${formatNumber(totalClosingBalance)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            `;

            $('#trial-balance-content').html(html);
        }

        function exportTrialBalance() {
            const startDate = $('#tb-start-date').val();
            const endDate = $('#tb-end-date').val();

            if (!startDate || !endDate) {
                showAlert('Please select both start and end dates', 'error');
                return;
            }

            const url = API_BASE_URL + '/trial-balance/export?start=' + startDate + '&end=' + endDate + '&format=xlsx';
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
