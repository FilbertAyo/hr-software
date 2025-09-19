<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Companies</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <a href="{{ route('company.create') }}" class="btn mb-2 btn-primary btn-sm">
                            New Company<span class="fe fe-plus fe-16 ml-2"></span>
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
                                            <th>Company Name</th>
                                            <th>Short Name</th>
                                            <th>Contact Person</th>
                                            <th>City</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($companies->count() > 0)
                                            @foreach ($companies as $index => $company)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $company->company_name }}</td>
                                                    <td>{{ $company->company_short_name ?? '-' }}</td>
                                                    <td>{{ $company->contact_person ?? '-' }}</td>
                                                    <td>{{ $company->city ?? '-' }}</td>
                                                    <td>{{ $company->phone_no ?? '-' }}</td>
                                                    <td>{{ $company->email ?? '-' }}</td>
                                                    <td class="text-right">
                                                        <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="{{ route('company.edit', $company->id) }}"
                                                               class="btn btn-sm btn-primary">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>
                                                            <a href="{{ route('company.show', $company->id) }}"
                                                                class="btn btn-sm btn-secondary">
                                                                 <span class="fe fe-eye fe-16"></span>
                                                             </a>
                                                            <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <span class="fe fe-trash-2 fe-16"></span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center">No companies found</td>
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
</x-app-layout>
