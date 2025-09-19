<x-app-layout>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-center mb-3 border-bottom no-gutters">
                    <div class="col">
                        <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">Employee Registration</a>
                            </li>

                        </ul>
                    </div>

                </div>

            <div class="row my-2">
                <!-- Small table -->

                @include('elements.spinner')
                <div class="col-md-12">
                    <div class="card shadow-none border">

                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Personal Details</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Earning and Deduction</a>
                              </li>

                            </ul>
                            <div class="tab-content" id="myTabContent">
                              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="accordion w-100" id="accordion1">
                                    <div class="card shadow-none border">
                                      <div class="card-header" id="heading1">
                                        <a role="button" href="#collapse1" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                          <strong> Personal Details</strong>
                                        </a>
                                      </div>
                                      <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordion1">



                                                <div class="card-body">
                                                  <form class="needs-validation" novalidate>
                                                    <!-- First Name -->
                                                    <div class="form-row">
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationFirstName">First Name *</label>
                                                        <input type="text" class="form-control" id="validationFirstName" required>
                                                        <div class="invalid-feedback"> First Name is required. </div>
                                                      </div>

                                                      <!-- Middle Name -->
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationMiddleName">Middle Name *</label>
                                                        <input type="text" class="form-control" id="validationMiddleName" required>
                                                        <div class="invalid-feedback"> Middle Name is required. </div>
                                                      </div>
                                                    </div>

                                                    <!-- Last Name -->
                                                    <div class="form-row">
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationLastName">Last Name *</label>
                                                        <input type="text" class="form-control" id="validationLastName" required>
                                                        <div class="invalid-feedback"> Last Name is required. </div>
                                                      </div>

                                                      <!-- Employee ID -->
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationEmployeeID">Employee ID</label>
                                                        <input type="text" class="form-control" id="validationEmployeeID">
                                                        <div class="valid-feedback"> Looks good! </div>
                                                      </div>
                                                    </div>

                                                    <!-- Department -->
                                                    <div class="form-row">
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationDepartment">Department</label>
                                                        <input type="text" class="form-control" id="validationDepartment">
                                                        <div class="valid-feedback"> Looks good! </div>
                                                      </div>

                                                      <!-- Sub Station -->
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationSubStation">Sub Station *</label>
                                                        <select class="form-control" id="validationSubStation" required>
                                                          <option value="">Select Sub Station</option>
                                                          <option value="Station1">Station 1</option>
                                                          <option value="Station2">Station 2</option>
                                                        </select>
                                                        <div class="invalid-feedback"> Please select a valid Sub Station. </div>
                                                      </div>
                                                    </div>

                                                    <!-- Country -->
                                                    <div class="form-row">
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationCountry">Country *</label>
                                                        <select class="form-control" id="validationCountry" required>
                                                          <option value="">Select Country</option>
                                                          <option value="USA">USA</option>
                                                          <option value="Canada">Canada</option>
                                                        </select>
                                                        <div class="invalid-feedback"> Please select a valid Country. </div>
                                                      </div>

                                                      <!-- Marital Status -->
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationMaritalStatus">Marital Status *</label>
                                                        <select class="form-control" id="validationMaritalStatus" required>
                                                          <option value="">Select Marital Status</option>
                                                          <option value="Single">Single</option>
                                                          <option value="Married">Married</option>
                                                        </select>
                                                        <div class="invalid-feedback"> Please select a valid Marital Status. </div>
                                                      </div>
                                                    </div>

                                                    <!-- Gender -->
                                                    <div class="form-row">
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationGender">Gender *</label>
                                                        <select class="form-control" id="validationGender" required>
                                                          <option value="">Select Gender</option>
                                                          <option value="Male">Male</option>
                                                          <option value="Female">Female</option>
                                                        </select>
                                                        <div class="invalid-feedback"> Please select a valid Gender. </div>
                                                      </div>

                                                      <!-- Shift Type -->
                                                      <div class="col-md-6 mb-3">
                                                        <label for="validationShiftType">Shift Type</label>
                                                        <select class="form-control" id="validationShiftType" required>
                                                          <option value="">Select Shift Type</option>
                                                          <option value="Day">Day</option>
                                                          <option value="Night">Night</option>
                                                        </select>
                                                        <div class="invalid-feedback"> Please select a valid Shift Type. </div>
                                                      </div>
                                                    </div>

                                                    <!-- Emergency Contact -->
                                                    <div class="form-group mb-3">
                                                      <label for="validationEmergencyContact">Emergency Contact</label>
                                                      <input type="text" class="form-control" id="validationEmergencyContact">
                                                      <div class="valid-feedback"> Looks good! </div>
                                                    </div>


                                                  </form>
                                                </div>

                                        </div>
                                      </div>
                                    </div>
                                    <div class="card shadow-none border">
                                      <div class="card-header" id="heading1">
                                        <a role="button" href="#collapse2" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                          <strong> Birth Details</strong>
                                        </a>
                                      </div>
                                      <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordion1">
                                        <div class="card-body">



                                                <div class="card-body">
                                                  <form class="needs-validation" novalidate>
                                                    <!-- Date of Birth -->
                                                    <div class="form-group mb-3">
                                                      <label for="validationDOB">Date of Birth *</label>
                                                      <input type="date" class="form-control" id="validationDOB" required>
                                                      <div class="invalid-feedback"> Date of Birth is required. </div>
                                                    </div>

                                                    <!-- Village Born -->
                                                    <div class="form-group mb-3">
                                                      <label for="validationVillageBorn">Village Born *</label>
                                                      <input type="text" class="form-control" id="validationVillageBorn" required>
                                                      <div class="invalid-feedback"> Village Born is required. </div>
                                                    </div>

                                                    <!-- Ward Born -->
                                                    <div class="form-group mb-3">
                                                      <label for="validationWardBorn">Ward Born *</label>
                                                      <input type="text" class="form-control" id="validationWardBorn" required>
                                                      <div class="invalid-feedback"> Ward Born is required. </div>
                                                    </div>

                                                    <!-- Birth Certificate Number -->
                                                    <div class="form-group mb-3">
                                                      <label for="validationBirthCertificate">Birth Certificate Number *</label>
                                                      <input type="text" class="form-control" id="validationBirthCertificate" required>
                                                      <div class="invalid-feedback"> Birth Certificate Number is required. </div>
                                                    </div>


                                                  </form>
                                                </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="card shadow-none border">
                                      <div class="card-header" id="heading1">
                                        <a role="button" href="#collapse3" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                          <strong>Collapse three</strong>
                                        </a>
                                      </div>
                                      <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion1">
                                        <div class="card-body">


                                         


                                        </div>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> Anm eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. </div>
                           </div>
                          </div>

                    </div>
                </div> <!-- simple table -->


        </div> <!-- .row -->
    </div> <!-- .container-fluid -->






<script>
document.querySelectorAll('.edit-substation-btn').forEach(button => {
button.addEventListener('click', function () {
const substationId = this.getAttribute('data-substation-id');
const substationName = this.getAttribute('data-substation-name');

// Set the form's action attribute to the route for updating the substation
document.getElementById('editsubstationForm').setAttribute('action', `/substation/${substationId}`);

// Populate the substation name in the modal
document.getElementById('editsubstationName').value = substationName;

// Show the modal
$('#editsubstationModal').modal('show');
});
});

</script>


</x-app-layout>
