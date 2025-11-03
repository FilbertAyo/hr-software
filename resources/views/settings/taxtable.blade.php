<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Tax tables/ PAYE</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        {{-- <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">New taxtable') }}
            </x-modal-button> --}}
                    </div>
                </div>

                <div class="row my-2">
                    <!-- Small table -->


                    <div class="col-md-12">
                        <div class="card shadow-none border">
                            <div class="card-body">

                                <table class="table table-bordered datatables" id="dataTable-1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                            <th>Tax %</th>
                                            <th>Additional</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($taxtables as $index => $taxtable)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $taxtable->min_income }}</td>
                                                <td>{{ $taxtable->max_income }}</td>
                                                <td>{{ $taxtable->rate_percentage }}</td>
                                                <td>{{ $taxtable->fixed_amount }}</td>
                                                <td class="text-right">
                                                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-sm btn-primary edit-taxtable-btn"
                                                            data-taxtable-id="{{ $taxtable->id }}"
                                                            data-min="{{ $taxtable->min_income }}"
                                                            data-max="{{ $taxtable->max_income }}"
                                                            data-tax-percent="{{ $taxtable->rate_percentage }}"
                                                            data-add-amount="{{ $taxtable->fixed_amount }}">
                                                            <span class="fe fe-edit fe-16"></span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- .row -->


            <div class="modal fade" id="edittaxtableModal" tabindex="-1" role="dialog"
                aria-labelledby="edittaxtableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="edittaxtableModalLabel">Edit Tax Table</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="" id="edittaxtableForm">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Minimum Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="editMin"
                                            name="min_income" placeholder="Min" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Maximum Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="editMax"
                                            name="max_income" placeholder="Max" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Tax Percentage</label>
                                        <input type="number" step="0.01" class="form-control" id="editTaxPercent"
                                            name="rate_percentage" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Additional Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="editAddAmount"
                                            name="fixed_amount" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <x-secondary-button data-dismiss="modal">
                                {{ __('Close') }}
                            </x-secondary-button>
                                    <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.querySelectorAll('.edit-taxtable-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const taxtableId = this.getAttribute('data-taxtable-id');
                        const min = this.getAttribute('data-min');
                        const max = this.getAttribute('data-max');
                        const taxPercent = this.getAttribute('data-tax-percent');
                        const addAmount = this.getAttribute('data-add-amount');

                        document.getElementById('edittaxtableForm').setAttribute('action',
                            `/taxtable/${taxtableId}`);
                        document.getElementById('editMin').value = min;
                        document.getElementById('editMax').value = max;
                        document.getElementById('editTaxPercent').value = taxPercent;
                        document.getElementById('editAddAmount').value = addAmount;

                        $('#edittaxtableModal').modal('show');
                    });
                });
            </script>
</x-app-layout>
