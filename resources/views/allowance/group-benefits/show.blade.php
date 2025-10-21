<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
             
                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Group Benefit Details</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('group-benefits.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>Back
                        </a>
                        <a href="{{ route('group-benefits.edit', $groupBenefit) }}" class="btn btn-sm btn-primary">
                            <i class="fe fe-edit fe-16 mr-2"></i>Edit
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Earning Group:</label>
                                            <p class="form-control-plaintext">{{ $groupBenefit->earngroup->earngroup_name }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Benefit Name:</label>
                                            <p class="form-control-plaintext">{{ $groupBenefit->allowance->allowance_name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Status:</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge badge-{{ $groupBenefit->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($groupBenefit->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Created At:</label>
                                            <p class="form-control-plaintext">{{ $groupBenefit->created_at->format('M d, Y H:i:s') }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($groupBenefit->allowance->allowanceDetails->count() > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Benefit Details:</label>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Calculation Type</th>
                                                                <th>Amount/Percentage</th>
                                                                <th>Taxable</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($groupBenefit->allowance->allowanceDetails as $detail)
                                                                <tr>
                                                                    <td>{{ ucfirst($detail->calculation_type) }}</td>
                                                                    <td>
                                                                        @if($detail->calculation_type == 'amount')
                                                                            {{ number_format($detail->amount, 2) }}
                                                                        @else
                                                                            {{ $detail->percentage }}%
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-{{ $detail->taxable ? 'success' : 'secondary' }}">
                                                                            {{ $detail->taxable ? 'Yes' : 'No' }}
                                                                        </span>
                                                                    </td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
