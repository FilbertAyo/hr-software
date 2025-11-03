<x-app-layout>


                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">other benefits</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-auto">

                        <button type="button" class="btn btn-sm" onclick="reloadPage()">
                            <i class="fe fe-16 fe-refresh-ccw text-muted"></i>
                        </button>
                        <button type="button" class="btn mb-2 btn-primary btn-sm" data-toggle="modal"
                            data-target="#varyModal" data-whatever="@mdo">New other benefit') }}
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
                                            <th>other benefit</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($other_benefits->count() > 0)
                                            @foreach ($other_benefits as $index => $other_benefit)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $other_benefit->other_benefit_name }}</td>
                                                    <td class="text-right">
                                                        <div
                                                            style="display: flex; gap: 4px; justify-content: flex-end;">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-sm btn-primary edit-other-benefit-btn"
                                                                data-other-benefit-id="{{ $other_benefit->id }}"
                                                                data-other-benefit-name="{{ $other_benefit->other_benefit_name }}">
                                                                <span class="fe fe-edit fe-16"></span>
                                                            </a>

                                                            <form
                                                                action="{{ route('other-benefit.destroy', $other_benefit->id) }}"
                                                                method="POST"
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
                                                <td colspan="3" class="text-center">No other benefit found</td>
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
                            <h5 class="modal-title" id="varyModalLabel">New other-benefit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('other-benefit.store') }}" validate>
                                @csrf

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <input type="text" class="form-control" id="validationCustom3"
                                            name="other_benefit_name" required>
                                        <div class="valid-feedback"> Looks good! </div>
                                    </div>

                                </div>

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
            </div>


            <!-- Edit other-benefit Modal -->
            <div class="modal fade" id="editother-benefitModal" tabindex="-1" role="dialog"
                aria-labelledby="editother-benefitModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editother-benefitModalLabel">Edit other-benefit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="" id="editother-benefitForm">
                                @csrf
                                @method('PUT')

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <input type="text" class="form-control" id="editother-benefitName"
                                            name="other_benefit_name" required>
                                        <div class="valid-feedback"> Looks good! </div>
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
                document.querySelectorAll('.edit-other-benefit-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const otherBenefitId = this.getAttribute('data-other-benefit-id');
                        const otherBenefitName = this.getAttribute('data-other-benefit-name');

                        // Set the form's action attribute to the route for updating the other-benefit
                        document.getElementById('editother-benefitForm').setAttribute('action',
                            `/other-benefit/${otherBenefitId}`);

                        // Populate the other-benefit name in the modal
                        document.getElementById('editother-benefitName').value = otherBenefitName;

                        // Show the modal
                        $('#editother-benefitModal').modal('show');
                    });
                });
            </script>



</x-app-layout>
