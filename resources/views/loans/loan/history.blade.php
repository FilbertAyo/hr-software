<x-app-layout>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="fe fe-clock"></i> Loan Restructure History</h4>
                    <div>
                        <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-arrow-left fe-16"></i> Back to Loan Details
                        </a>
                        <a href="{{ route('loan.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fe fe-list fe-16"></i> All Loans
                        </a>
                    </div>
                </div>

                <!-- Current Loan Summary -->
                <div class="card mb-4 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fe fe-info"></i> Current Loan Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Employee:</strong><br>{{ $loan->employee->employee_name }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Loan Type:</strong><br>{{ $loan->loanType->loan_type_name }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Original Amount:</strong><br><span class="text-primary">{{ number_format($loan->loan_amount, 2) }}</span></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Remaining Amount:</strong><br><span class="text-danger">{{ number_format($loan->remaining_amount, 2) }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Original Installments:</strong><br>{{ $loan->original_installment_count ?? $loan->installment_count }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Current Installments:</strong><br>{{ $loan->installment_count }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Monthly Payment:</strong><br>{{ number_format($loan->monthly_payment, 2) }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Times Restructured:</strong><br>
                                    <span class="badge badge-info badge-lg">{{ $loan->restructure_count }}x</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restructure History Timeline -->
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fe fe-list"></i> Restructure History ({{ $restructures->count() }} Record{{ $restructures->count() != 1 ? 's' : '' }})</h5>
                    </div>
                    <div class="card-body">
                        @if($restructures->count() > 0)
                            <div class="timeline">
                                @foreach($restructures as $index => $restructure)
                                    <div class="card mb-3 shadow-sm @if($index === 0) border-warning @endif">
                                        <div class="card-header @if($index === 0) bg-warning text-white @else bg-light @endif">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="fe fe-repeat"></i> Restructure #{{ $restructures->count() - $index }}
                                                    @if($index === 0)
                                                        <span class="badge badge-light text-warning ml-2">Most Recent</span>
                                                    @endif
                                                </h6>
                                                <small>{{ $restructure->created_at->format('M d, Y h:i A') }}</small>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p class="mb-2"><strong>Restructured By:</strong>
                                                        {{ $restructure->restructuredBy ? $restructure->restructuredBy->name : 'System' }}
                                                    </p>
                                                    <p class="mb-2"><strong>Reason:</strong></p>
                                                    <div class="alert alert-info">
                                                        {{ $restructure->reason }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card bg-light mb-3">
                                                        <div class="card-header">
                                                            <strong><i class="fe fe-arrow-left text-danger"></i> Before Restructure</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-sm table-borderless mb-0">
                                                                <tr>
                                                                    <td><strong>Installments:</strong></td>
                                                                    <td>{{ $restructure->old_installment_count }} months</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Monthly Payment:</strong></td>
                                                                    <td>{{ number_format($restructure->old_monthly_payment, 2) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Start Date:</strong></td>
                                                                    <td>{{ $restructure->old_start_date ? \Carbon\Carbon::parse($restructure->old_start_date)->format('M d, Y') : 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>End Date:</strong></td>
                                                                    <td>{{ $restructure->old_end_date ? \Carbon\Carbon::parse($restructure->old_end_date)->format('M d, Y') : 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Remaining at Restructure:</strong></td>
                                                                    <td class="text-danger font-weight-bold">{{ number_format($restructure->remaining_amount_at_restructure, 2) }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card bg-light mb-3">
                                                        <div class="card-header">
                                                            <strong><i class="fe fe-arrow-right text-success"></i> After Restructure</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-sm table-borderless mb-0">
                                                                <tr>
                                                                    <td><strong>Installments:</strong></td>
                                                                    <td>{{ $restructure->new_installment_count }} months</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Monthly Payment:</strong></td>
                                                                    <td>{{ number_format($restructure->new_monthly_payment, 2) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Start Date:</strong></td>
                                                                    <td>{{ $restructure->new_start_date ? \Carbon\Carbon::parse($restructure->new_start_date)->format('M d, Y') : 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>End Date:</strong></td>
                                                                    <td>{{ $restructure->new_end_date ? \Carbon\Carbon::parse($restructure->new_end_date)->format('M d, Y') : 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Change:</strong></td>
                                                                    <td>
                                                                        @php
                                                                            $diff = $restructure->new_installment_count - $restructure->old_installment_count;
                                                                        @endphp
                                                                        @if($diff > 0)
                                                                            <span class="badge badge-warning">+{{ $diff }} months</span>
                                                                        @elseif($diff < 0)
                                                                            <span class="badge badge-success">{{ $diff }} months</span>
                                                                        @else
                                                                            <span class="badge badge-secondary">No change</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Old Installments Snapshot (Collapsible) -->
                                            @if($restructure->old_installments_snapshot)
                                                <div class="mt-3">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                                            data-toggle="collapse" data-target="#snapshot{{ $restructure->id }}">
                                                        <i class="fe fe-eye"></i> View Old Installments Snapshot
                                                    </button>
                                                    <div class="collapse mt-2" id="snapshot{{ $restructure->id }}">
                                                        <div class="card card-body bg-light">
                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Amount</th>
                                                                            <th>Due Date</th>
                                                                            <th>Status</th>
                                                                            <th>Paid Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($restructure->old_installments_snapshot as $inst)
                                                                            <tr>
                                                                                <td>{{ $inst['installment_number'] }}</td>
                                                                                <td>{{ number_format($inst['amount'], 2) }}</td>
                                                                                <td>{{ \Carbon\Carbon::parse($inst['due_date'])->format('M Y') }}</td>
                                                                                <td>
                                                                                    @if($inst['status'] == 'paid')
                                                                                        <span class="badge badge-success">Paid</span>
                                                                                    @else
                                                                                        <span class="badge badge-warning">{{ ucfirst($inst['status']) }}</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $inst['paid_date'] ? \Carbon\Carbon::parse($inst['paid_date'])->format('M d, Y') : '-' }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="alert alert-info mt-4">
                                <i class="fe fe-info"></i>
                                <strong>Note:</strong> This history shows all restructuring events for this loan. Each restructure creates a new payment schedule while preserving the history of previous terms.
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fe fe-alert-triangle"></i>
                                <strong>No History Found:</strong> This loan has not been restructured yet.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>


