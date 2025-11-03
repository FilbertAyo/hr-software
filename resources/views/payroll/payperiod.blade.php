<x-app-layout>


    <div class="row align-items-center mb-3 border-bottom no-gutters">
        <div class="col">
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">pay periods</a>
                </li>
            </ul>
        </div>
        <div class="col-auto">
            <x-modal-button>
                {{ __('New payperiod') }}
            </x-modal-button>
        </div>
    </div>

    <div class="row my-2">


        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <!-- table -->
                    <table class="table table-bordered datatables" id="dataTable-1">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>payperiod</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($payperiods->count() > 0)
                                @foreach ($payperiods as $index => $payperiod)
                                    <tr class="{{ $payperiod->status === 'closed' ? 'table-secondary' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $payperiod->period_name }}
                                            @if ($payperiod->status === 'draft')
                                                <span class="badge badge-warning ml-2">Current</span>
                                            @elseif($payperiod->status === 'closed')
                                                <span class="badge badge-secondary ml-2">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $payperiod->status === 'draft' ? 'success' : ($payperiod->status === 'closed' ? 'secondary' : 'info') }}">
                                                {{ ucfirst($payperiod->status) }}
                                            </span>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No payroll periods found for this company
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- simple table -->


    </div> <!-- .row -->



    <div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="varyModalLabel">New payperiod</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('payperiod.store') }}">
                        @csrf

                        <div class="form-row">
                            @php
                                $selMonth = old('month', $nextMonthYear['month'] ?? now()->month);
                                $selYear = old('year', $nextMonthYear['year'] ?? now()->year);
                            @endphp

                            <div class="col-md-6">
                                <label for="month">Month</label>
                                <select name="month" class="form-control" readonly>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $selMonth == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="year">Year</label>
                                <select name="year" class="form-control" readonly>
                                    @for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                                        <option value="{{ $i }}" {{ $selYear == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        @if (isset($nextMonthYear))
                            <div class="alert alert-{{ $nextMonthYear['is_first_period'] ? 'success' : 'info' }} mt-2">
                                <small>
                                    <i class="fe fe-{{ $nextMonthYear['is_first_period'] ? 'calendar' : 'info' }}"></i>
                                    @if ($nextMonthYear['is_first_period'])
                                        <strong>First Payroll Period:</strong> {{ $nextMonthYear['month_name'] }}
                                        {{ $nextMonthYear['year'] }}
                                        @if ($company->start_month && $company->start_year)
                                            <br><small>Based on company start date: {{ $company->start_month }}
                                                {{ $company->start_year }}</small>
                                        @endif
                                    @else
                                        <strong>Next available period:</strong> {{ $nextMonthYear['month_name'] }}
                                        {{ $nextMonthYear['year'] }}
                                        <br><small>Previous periods will be automatically closed when this one is
                                            created</small>
                                    @endif
                                </small>
                            </div>
                        @endif

                        <div class="modal-footer">
                            <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>


                </div>
            </div>
        </div>




        <style>
            select[readonly] {
                background-color: #f8f9fa;
                cursor: not-allowed;
                opacity: 0.8;
            }
        </style>



</x-app-layout>
