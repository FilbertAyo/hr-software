<x-app-layout>

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Group Benefits</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <a href="{{ route('group-benefits.create') }}" class="btn mb-2 btn-primary btn-sm">
                            New Group Benefit<span class="fe fe-plus fe-16 ml-2"></span>
                        </a>
                    </div>
                </div>

                <div class="row my-2">
                    @include('elements.spinner')
                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <!-- table -->
                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Earning Group</th>
                                            <th>Benefit Name</th>
                                            <th>Benefit Details</th>
                                            <th>Status</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($groupBenefits->count() > 0)
                                            @foreach ($groupBenefits as $index => $groupBenefit)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $groupBenefit->earngroup->earngroup_name }}</td>
                                                    <td>{{ $groupBenefit->allowance->allowance_name }}</td>
                                                    <td>
                                                        @if($groupBenefit->allowance->allowanceDetails->count() > 0)
                                                            @foreach($groupBenefit->allowance->allowanceDetails as $detail)
                                                                <span class="badge badge-secondary">
                                                                    {{ $detail->calculation_type == 'amount' ? 'Amount: ' . number_format($detail->amount, 2) : 'Percentage: ' . $detail->percentage . '%' }}
                                                                    @if($detail->taxable) (Taxable) @endif
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No details</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $groupBenefit->status == 'active' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($groupBenefit->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="{{ route('group-benefits.show', $groupBenefit) }}"
                                                               class="btn btn-sm btn-secondary" title="View">
                                                                <i class="fe fe-eye fe-16"></i>
                                                            </a>
                                                            <a href="{{ route('group-benefits.edit', $groupBenefit) }}"
                                                               class="btn btn-sm btn-primary" title="Edit">
                                                                <i class="fe fe-edit fe-16"></i>
                                                            </a>
                                                            <form action="{{ route('group-benefits.destroy', $groupBenefit) }}"
                                                                  method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                        title="Delete"
                                                                        onclick="return confirm('Are you sure you want to delete this group benefit?')">
                                                                    <i class="fe fe-trash-2 fe-16"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No group benefits found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reloadPage() {
            window.location.reload();
        }
    </script>
</x-app-layout>
