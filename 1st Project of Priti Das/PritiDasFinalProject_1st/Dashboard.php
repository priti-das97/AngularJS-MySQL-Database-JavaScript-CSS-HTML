<?php
session_start();
if (!isset($_SESSION['users'])) { header("Location: login.php"); exit(); }
$users = $_SESSION['users'];
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp"> <!-- ✅ AngularJS app added here -->
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="DashboardStyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- ✅ AngularJS Core + Route Module -->
  
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular-route.min.js"></script>
  
 

</head>

<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo-container">
      <img src="logo.png" alt="Logo" class="logo">
    </div>

    <div class="menu-section">
     <a href="#!/dashboard"
   class="dashboard-link"
   ng-class="{'active-tab': isActive('/dashboard')}">
  <img src="Dashboard.PNG" alt="Dashboard Icon"> Dashboard
</a>

      <h4>Applications</h4>

      <!-- ✅ Angular route link instead of loadPage() -->
      <a href="#!/doctor" ng-class="{'active-tab': isActive('/doctor')}">
        <img src="Doctor.PNG" alt="Doctor Icon"> Doctor
      </a>

      <a href="#!/patient" ng-class="{'active-tab': isActive('/patient')}">
        <img src="Patient.PNG" alt="Patient Icon"> Patient
      </a>

      <a href="#!/room" ng-class="{'active-tab': isActive('/room')}">
        <img src="Room.PNG" alt="Room Icon"> Room
      </a>
    </div>

    <div class="menu-section">
      <h4>Others</h4>
      <a href="#!/bill" ng-class="{'active-tab': isActive('/bill')}">
        <img src="Bill.PNG" alt="Bill Icon"> Bill
      </a>
      <a href="#!/payment" ng-class="{'active-tab': isActive('/payment')}">
        <img src="Payment.PNG" alt="Payment Icon"> Payment
      </a>
    </div>

    <div class="menu-section">
      <h4>Support</h4>
      <a href="#!/security" ng-class="{'active-tab': isActive('/security')}">
        <img src="security.PNG" alt="Security Icon"> Security
      </a>
      <a href="#!/help" ng-class="{'active-tab': isActive('/help')}">
        <img src="help.PNG" alt="Help Icon"> Help
      </a>
    </div>
  </aside>

  <!-- Main -->
  <div class="main">
    <header>
      <div class="header-right">
        <div class="search-bar">
          <input type="text" placeholder="Search..." aria-label="Search" />
          <button type="button" aria-label="Search button"><i class="fa fa-search"></i></button>
        </div>

        <div class="profile-menu" id="profileMenu" aria-haspopup="true" aria-expanded="false" tabindex="0">
          <img src="profileImage.PNG" alt="Profile" />
          <div class="popup" role="menu" aria-hidden="true">
            <div class="user-info">
              <img src="profileImage.PNG" alt="Profile" class="user-popup-img" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
              <p class="user-name"><?= htmlspecialchars($users['username']) ?></p>
              <p class="user-email"><?= htmlspecialchars($users['email']) ?></p>
            </div>
            <a href="#profile"><i class="fa fa-user"></i> Profile</a>
            <a href="#settings"><i class="fa fa-cog"></i> Settings</a>
            <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
      </div>
    </header>

    <!-- ✅ Angular view loads here -->
    <main id="main-content">
      <div ng-view></div>
    </main>
  </div>

  <!-- ✅ Profile Dropdown Script (your original) -->
  <script>
    const profileMenu = document.getElementById('profileMenu');
    const popup = profileMenu.querySelector('.popup');

    profileMenu.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = popup.style.display === 'flex';
      popup.style.display = isOpen ? 'none' : 'flex';
      profileMenu.setAttribute('aria-expanded', !isOpen);
    });

    document.addEventListener('click', () => {
      popup.style.display = 'none';
      profileMenu.setAttribute('aria-expanded', 'false');
    });
  </script>

  <!-- ✅ AngularJS App + Routes + Controllers -->
  <script>
    var app = angular.module('myApp', ['ngRoute']);

app.config(function($routeProvider) {
  $routeProvider
    .when("/dashboard", {
      templateUrl: "dashboardview.html",
      controller: "DashboardController"
    })
    .when("/doctor", {
      templateUrl: "doctorview.html",
      controller: "DoctorController"
    })
    .when("/patient", {
      templateUrl: "patientview.html",
      controller: "PatientController"
    })
    .when("/room", {
      templateUrl: "roomview.html",
      controller: "RoomController"
    })
    .when("/bill", {
      templateUrl: "billview.html",
      controller: "BillController"
    })
    .when("/payment", {
      templateUrl: "paymentview.html",
      controller: "PaymentController"
    })
	
	  /* ⭐ ADD THIS HERE ⭐ */
    .when("/security", {
      templateUrl: "securityview.html",
      controller: "SecurityController"
    })
    /* END OF SECURITY ROUTE */
	
	

    .when("/help", {
      templateUrl: "help-demo.html",
      controller: "HelpController"
    })
 


    .otherwise({
      redirectTo: "/dashboard"   // 👈 default page is Dashboard
    });
});
    // ✅ startFrom filter for pagination
    app.filter('startFrom', function() {
      return function(input, start) {
        if (!input || !input.length) return [];
        start = +start; // convert to number
        return input.slice(start);
      };
    });

// Doctor Controller
app.controller("DoctorController", function ($scope, $http, $httpParamSerializerJQLike) {

  $scope.doctors = [];
  $scope.error = "";

  $scope.currentPage = 1;
  $scope.itemsPerPage = 8;

  // search box
  $scope.searchText = "";

  // add popup
  $scope.showAddPopup = false;
  $scope.newDoctor = {};

  // edit popup
  $scope.showEditDoctorPopup = false;
  $scope.editDoctor = {};

  // ---------- LOAD DOCTORS ----------
  $http.get('doctortest.php')
    .then(function (response) {
      $scope.doctors = response.data || [];
      console.log("Loaded doctors:", $scope.doctors);
    })
    .catch(function (err) {
      console.log("doctortest.php error:", err);
      $scope.error = "Failed to fetch doctor data.";
    });

  // ---------- PAGINATION ----------
  $scope.totalPages = function () {
    return Math.ceil($scope.doctors.length / $scope.itemsPerPage) || 1;
  };

  $scope.pagedDoctors = function () {
    var start = ($scope.currentPage - 1) * $scope.itemsPerPage;
    return $scope.doctors.slice(start, start + $scope.itemsPerPage);
  };

  $scope.nextPage = function () {
    if ($scope.currentPage < $scope.totalPages()) $scope.currentPage++;
  };

  $scope.prevPage = function () {
    if ($scope.currentPage > 1) $scope.currentPage--;
  };

  // optional: search icon click (filter already works with searchText)
  $scope.searchFunction = function () {
    console.log("Search:", $scope.searchText);
  };

  // ---------- ADD DOCTOR ----------
  $scope.openAddPopup = function () {
    $scope.showAddPopup = true;
    $scope.newDoctor = {};
  };

  $scope.closeAddPopup = function () {
    $scope.showAddPopup = false;
  };

  $scope.addDoctor = function () {
    console.log("Adding doctor:", angular.toJson($scope.newDoctor));

    $http.post('adddoctor.php', $scope.newDoctor)
      .then(function (response) {
        console.log("adddoctor.php response:", response.data);
        $scope.doctors.push(response.data); // assuming PHP returns new row
        $scope.closeAddPopup();
      })
      .catch(function (err) {
        console.log("adddoctor.php error:", err);
        alert("Failed to add doctor.");
      });
  };

  // ---------- EDIT DOCTOR ----------
  $scope.openEditDoctorPopup = function (doctor) {
    $scope.editDoctor = angular.copy(doctor);
    console.log("openEditDoctorPopup:", $scope.editDoctor);
    $scope.showEditDoctorPopup = true;
  };

  $scope.closeEditDoctorPopup = function () {
    $scope.showEditDoctorPopup = false;
  };

  $scope.updateDoctor = function () {
    console.log("updateDoctor sending:", angular.toJson($scope.editDoctor));

    $http({
      method: 'POST',
      url: 'updatedoctor.php',
      data: $scope.editDoctor, // { id, name, specialization, phone, email }
      headers: { 'Content-Type': 'application/json' }
    })
      .then(function (response) {
        console.log("updatedoctor.php response:", response.data);

        if (response.data && response.data.success) {
          // server may send updated doctor in response.data.doctor
          var updated = response.data.doctor || $scope.editDoctor;

          for (var i = 0; i < $scope.doctors.length; i++) {
            if ($scope.doctors[i].id == updated.id) {
              $scope.doctors[i] = angular.copy(updated);
              break;
            }
          }
          $scope.closeEditDoctorPopup();
        } else {
          alert("Update error: " + (response.data && response.data.error || "Unknown error"));
        }
      })
      .catch(function (err) {
        console.log("updatedoctor.php error:", err);
        alert("Failed to update doctor.");
      });
  };

  // ---------- DELETE DOCTOR ----------
  $scope.deleteDoctor = function (id) {
    if (!confirm("Are you sure you want to delete this doctor?")) return;

    console.log("Deleting doctor id:", id);

    $http({
      method: 'POST',
      url: 'deletedoctor.php',
      data: $httpParamSerializerJQLike({ id: id }),
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(function (response) {
        console.log("deletedoctor.php response:", response.data);

        if (response.data && response.data.success) {
          $scope.doctors = $scope.doctors.filter(function (d) {
            return d.id != id;
          });

          if ($scope.currentPage > $scope.totalPages()) {
            $scope.currentPage = $scope.totalPages();
          }
        } else {
          alert("Delete error: " + (response.data.error || "Unknown error"));
        }
      })
      .catch(function (err) {
        console.log("deletedoctor.php error:", err);
        alert("Failed to delete doctor.");
      });
  };

});


    // Patient Controller
// Patient Controller
app.controller("PatientController", function ($scope, $http, $httpParamSerializerJQLike) {

  $scope.patients = [];
  $scope.error = "";

  $scope.currentPage = 1;
  $scope.itemsPerPage = 8;

  // Search text (used by | filter:searchText in view)
  $scope.searchText = "";

  // Add popup
  $scope.showAddPopup = false;
  $scope.newPatient = {};

  // Edit popup
  $scope.showEditPatientPopup = false;
  $scope.editPatient = {};

  // ---------------------------------------------------
  // Load patient data
  // ---------------------------------------------------
  $http.get('patienttest.php')
    .then(function (response) {
      // patienttest.php should return an array of patients
      // with fields: patient_id, p_name, age, gender,
      // contact_info, address, doctor_id, room_id
      $scope.patients = response.data;
      console.log("Loaded patients:", $scope.patients);
    })
    .catch(function (err) {
      console.log("patienttest.php error:", err);
      $scope.error = "Failed to fetch patient data.";
    });

  // ---------------------------------------------------
  // Pagination helpers
  // ---------------------------------------------------
  $scope.totalPages = function () {
    return Math.ceil($scope.patients.length / $scope.itemsPerPage) || 1;
  };

  $scope.pagedPatients = function () {
    var start = ($scope.currentPage - 1) * $scope.itemsPerPage;
    return $scope.patients.slice(start, start + $scope.itemsPerPage);
  };

  $scope.nextPage = function () {
    if ($scope.currentPage < $scope.totalPages()) {
      $scope.currentPage++;
    }
  };

  $scope.prevPage = function () {
    if ($scope.currentPage > 1) {
      $scope.currentPage--;
    }
  };

  // Dummy search click handler (filter works automatically)
  $scope.searchFunction = function () {
    // nothing needed — the table already filters with | filter:searchText
    console.log("Search text:", $scope.searchText);
  };

  // ---------------------------------------------------
  // ADD Patient popup
  // ---------------------------------------------------
  $scope.openAddPopup = function () {
    $scope.showAddPopup = true;
    $scope.newPatient = {};
  };

  $scope.closeAddPopup = function () {
    $scope.showAddPopup = false;
  };

  // Add Patient
  $scope.addPatient = function () {
    console.log("Adding patient:", angular.toJson($scope.newPatient));

    $http.post('addpatient.php', $scope.newPatient)
      .then(function (response) {
        console.log("addpatient.php response:", response.data);

        // if addpatient.php returns the new patient row, push it;
        // otherwise you may need to reload the list from server
        $scope.patients.push(response.data);
        $scope.closeAddPopup();
      })
      .catch(function (err) {
        console.log("addpatient.php error:", err);
        alert("Failed to add patient.");
      });
  };

  // ---------------------------------------------------
  // EDIT Patient popup
  // ---------------------------------------------------
  $scope.openEditPatientPopup = function (patient) {
    $scope.editPatient = angular.copy(patient); // avoid changing table directly
    console.log("openEditPatientPopup:", $scope.editPatient);
    $scope.showEditPatientPopup = true;
  };

  $scope.closeEditPatientPopup = function () {
    $scope.showEditPatientPopup = false;
  };

  // Update Patient
  $scope.updatePatient = function () {
    console.log("updatePatient sending:", angular.toJson($scope.editPatient));

    $http({
      method: 'POST',
      url: 'updatepatient.php',
      data: $scope.editPatient,                 // contains patient_id, p_name, age, etc.
      headers: { 'Content-Type': 'application/json' }
    })
      .then(function (response) {
        console.log("updatepatient.php response:", response.data);

        if (response.data && response.data.success) {
          // Prefer server-returned patient if provided
          var updated = response.data.patient || $scope.editPatient;

          for (var i = 0; i < $scope.patients.length; i++) {
            if ($scope.patients[i].patient_id == updated.patient_id) {
              $scope.patients[i] = angular.copy(updated);
              break;
            }
          }

          $scope.closeEditPatientPopup();
        } else {
          alert("Update error: " + (response.data && response.data.error || "Unknown error"));
        }
      })
      .catch(function (err) {
        console.log("updatepatient.php error:", err);
        alert("Failed to update patient.");
      });
  };

  // ---------------------------------------------------
  // DELETE Patient
  // ---------------------------------------------------
  $scope.deletePatient = function (patient_id) {
    if (!confirm("Are you sure you want to delete this patient?")) return;

    console.log("Deleting patient_id:", patient_id);

    $http({
      method: 'POST',
      url: 'deletepatient.php',
      data: $httpParamSerializerJQLike({ patient_id: patient_id }),
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(function (response) {
        console.log("deletepatient.php response:", response.data);

        if (response.data && response.data.success) {
          // Remove from local list
          $scope.patients = $scope.patients.filter(function (p) {
            return p.patient_id != patient_id;
          });

          // Fix current page if needed
          if ($scope.currentPage > $scope.totalPages()) {
            $scope.currentPage = $scope.totalPages();
          }
        } else {
          alert("Delete error: " + (response.data.error || "Unknown error"));
        }
      })
      .catch(function (err) {
        console.log("deletepatient.php error:", err);
        alert("Failed to delete patient.");
      });
  };

});

 


    // Room Controller
// Room Controller
app.controller("RoomController", function ($scope, $http, $httpParamSerializerJQLike) {
  $scope.rooms = [];
  $scope.error = "";

  $scope.currentPage = 1;
  $scope.pageSize = 8;

  $scope.showAddPopup = false;
  $scope.newRoom = {};
  $scope.showEditRoomPopup = false;
  $scope.editRoom = {};

  // ===== INITIAL LOAD =====
  $http.get('roomtest.php')
    .then(function (response) {
      console.log('roomtest.php response:', response.data);
      $scope.rooms = response.data || [];
    })
    .catch(function (err) {
      console.log('roomtest.php error:', err);
      $scope.error = "Failed to fetch room data.";
    });

  // ===== PAGINATION =====
  $scope.totalPages = function () {
    return Math.ceil($scope.rooms.length / $scope.pageSize) || 1;
  };

  $scope.pagedRooms = function () {
    var start = ($scope.currentPage - 1) * $scope.pageSize;
    return $scope.rooms.slice(start, start + $scope.pageSize);
  };

  $scope.prevPage = function () {
    if ($scope.currentPage > 1) $scope.currentPage--;
  };

  $scope.nextPage = function () {
    if ($scope.currentPage < $scope.totalPages()) $scope.currentPage++;
  };

  // ===== ADD ROOM =====
  $scope.openAddPopup = function () {
    $scope.showAddPopup = true;
    $scope.newRoom = {
      room_number: "",
      room_type: "",
      is_available: 0,
      room_price: 0
    };
  };

  $scope.closeAddPopup = function () {
    $scope.showAddPopup = false;
  };

  $scope.addRoom = function () {
    console.log('addRoom sending:', angular.toJson($scope.newRoom));

    $http({
      method: 'POST',
      url: 'addroom.php',
      data: $scope.newRoom,
      headers: { 'Content-Type': 'application/json' }
    })
      .then(function (response) {
        console.log('addroom.php response:', response.data);
        if (response.data && !response.data.error) {
          // if PHP returns the new row as JSON:
          $scope.rooms.push(response.data);
          $scope.closeAddPopup();
        } else {
          alert("Add error: " + (response.data.error || "Unknown error"));
        }
      })
      .catch(function (err) {
        console.log('addroom.php error:', err);
        alert("Failed to add room.");
      });
  };

  // ===== EDIT ROOM =====
  $scope.openEditRoomPopup = function (room) {
    $scope.editRoom = angular.copy(room);
    console.log('openEditRoomPopup:', $scope.editRoom);
    $scope.showEditRoomPopup = true;
  };

  $scope.closeEditRoomPopup = function () {
    $scope.showEditRoomPopup = false;
  };

  $scope.updateRoom = function () {
    console.log('updateRoom sending:', angular.toJson($scope.editRoom));

    $http({
      method: 'POST',
      url: 'updateroom.php',
      data: $scope.editRoom,
      headers: { 'Content-Type': 'application/json' }
    })
      .then(function (response) {
        console.log('updateroom.php response:', response.data);

        if (response.data && response.data.success) {
          // if PHP returns updated room as response.data.room, use that,
          // otherwise keep what we just edited
          var updated = response.data.room || $scope.editRoom;

          for (var i = 0; i < $scope.rooms.length; i++) {
            // IMPORTANT: use room_id (change to id if your DB uses that)
            if ($scope.rooms[i].room_id == updated.room_id) {
              $scope.rooms[i] = angular.copy(updated);
              break;
            }
          }

          $scope.closeEditRoomPopup();
        } else {
          alert("Update error: " + (response.data && response.data.error || "Unknown error"));
        }
      })
      .catch(function (err) {
        console.log('updateroom.php error:', err);
        alert("Failed to update room.");
      });
  };

  // ===== DELETE ROOM controller =====
  $scope.deleteRoom = function (room_id) {
    if (!confirm("Are you sure you want to delete this room?")) return;

    $http({
      method: 'POST',
      url: 'deleteroom.php',
      data: $httpParamSerializerJQLike({ room_id: room_id }), // no jQuery
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(function (response) {
        console.log('deleteroom.php response:', response.data);
        if (response.data && response.data.success) {
          $scope.rooms = $scope.rooms.filter(function (r) {
            return r.room_id != room_id; // compare with room_id
          });
          if ($scope.currentPage > $scope.totalPages()) {
            $scope.currentPage = $scope.totalPages();
          }
        } else {
          alert("Delete error: " + (response.data.error || "Unknown error"));
        }
      })
      .catch(function (err) {
        console.log('deleteroom.php error:', err);
        alert("Failed to delete room.");
      });
  };
});








    // Bill Controller
   app.controller("BillController", function($scope, $http) {
  $scope.bills = [];
  $scope.error = "";

  $scope.currentPage = 1;
  $scope.pageSize = 8;

  // ADD popup
  $scope.showAddPopup = false;
  $scope.newBill = {};

  // EDIT popup
  $scope.showEditBillPopup = false;   // <== NEW
  $scope.editBill = {};               // <== NEW

  // ----- LOAD BILLS -----
  $http.get('billtest.php')
    .then(function(response) {
      $scope.bills = response.data;
    })
    .catch(function(err) {
      console.error(err);
      $scope.error = "Failed to fetch bill data.";
    });

  // ----- PAGINATION -----
  $scope.totalPages = function() {
    return Math.ceil($scope.bills.length / $scope.pageSize) || 1;
  };

  $scope.pagedBills = function() {
    var start = ($scope.currentPage - 1) * $scope.pageSize;
    return $scope.bills.slice(start, start + $scope.pageSize);
  };

  $scope.prevPage = function() {
    if ($scope.currentPage > 1) $scope.currentPage--;
  };

  $scope.nextPage = function() {
    if ($scope.currentPage < $scope.totalPages()) $scope.currentPage++;
  };

  // ----- ADD BILL -----
  $scope.openAddPopup = function() {
    $scope.showAddPopup = true;
    $scope.newBill = {
      patient_id: null,
      services_tax: '',
      total_amount: 0,
      paid_bill: 0,
      remaining_bill: 0
    };
  };

  $scope.closeAddPopup = function() {
    $scope.showAddPopup = false;
  };

  $scope.addBill = function() {
    $http({
      method: 'POST',
      url: 'addbill.php',
      data: $scope.newBill,
      headers: { 'Content-Type': 'application/json' }
    })
    .then(function(response) {
      if (response.data && !response.data.error) {
        $scope.bills.push(response.data);
        $scope.currentPage = $scope.totalPages();
        $scope.closeAddPopup();
      } else {
        alert("Error: " + (response.data.error || "Unknown error"));
      }
    })
    .catch(function(err) {
      console.error(err);
      alert("Failed to add bill.");
    });
  };

  // ----- EDIT BILL -----
  $scope.openEditBillPopup = function(bill) {
    // copy so editing does not immediately change the table row
    $scope.editBill = angular.copy(bill);
    $scope.showEditBillPopup = true;
  };

  $scope.closeEditBillPopup = function() {
    $scope.showEditBillPopup = false;
  };

  $scope.updateBill = function() {
    $http({
      method: 'POST',
      url: 'updatebill.php',
      data: $scope.editBill,
      headers: { 'Content-Type': 'application/json' }
    })
    .then(function(response) {
      if (response.data && response.data.success) {
        // update row in $scope.bills
        for (var i = 0; i < $scope.bills.length; i++) {
          if ($scope.bills[i].bill_id == $scope.editBill.bill_id) {
            $scope.bills[i] = angular.copy($scope.editBill);
            break;
          }
        }
        $scope.closeEditBillPopup();
      } else {
        alert("Error: " + (response.data.error || "Unknown error"));
      }
    })
    .catch(function(err) {
      console.error(err);
      alert("Failed to update bill.");
    });
  };

  // ----- DELETE BILL controller -----
  $scope.deleteBill = function(bill_id) {
    if (!confirm("Do you really want to delete this bill?")) return;

    $http({
      method: 'POST',
      url: 'deletebill.php',
      data: { bill_id: bill_id },
      headers: { 'Content-Type': 'application/json' }
    })
    .then(function(response) {
      if (response.data && response.data.success) {
        // remove from array
        $scope.bills = $scope.bills.filter(function(b) {
          return b.bill_id != bill_id;
        });
        // fix page if needed
        if ($scope.currentPage > $scope.totalPages()) {
          $scope.currentPage = $scope.totalPages();
        }
      } else {
        alert("Error: " + (response.data.error || "Unknown error"));
      }
    })
    .catch(function(err) {
      console.error(err);
      alert("Failed to delete bill.");
    });
  };
});

	

 

    // ----------------------------
    // Payment Controller (updated, single impl)
    // ----------------------------
    app.controller("PaymentController", function($scope, $http) {
      $scope.payments = [];
      $scope.error = "";

      // Pagination
      $scope.currentPage = 1;
      $scope.itemsPerPage = 8;

      // Popups and models
      $scope.showAddPopup = false;
      $scope.showEditPopup = false;
      $scope.newPayment = {};
      $scope.editPayment = {};

      // Load payments
      $scope.loadPayments = function() {
        $http.get('paymenttest.php')
          .then(function(response) {
            $scope.payments = response.data || [];
          })
          .catch(function(err) {
            console.error('Failed to fetch payments:', err);
            $scope.error = "Failed to fetch payment data.";
          });
      };
      $scope.loadPayments();

      // Pagination functions
      $scope.totalPages = function() {
        return Math.max(1, Math.ceil($scope.payments.length / $scope.itemsPerPage));
      };
      $scope.pagedPayments = function() {
        var start = ($scope.currentPage - 1) * $scope.itemsPerPage;
        return $scope.payments.slice(start, start + $scope.itemsPerPage);
      };
      $scope.prevPage = function() { if ($scope.currentPage > 1) $scope.currentPage--; };
      $scope.nextPage = function() { if ($scope.currentPage < $scope.totalPages()) $scope.currentPage++; };

      // Open Add popup
      $scope.openAddPopup = function() {
        $scope.newPayment = {
          bill_id: "",
          amount_paid: "",
          payment_method: "",
          payment_date: new Date().toISOString().slice(0,10),
          bank_name: ""
        };
        $scope.showAddPopup = true;
      };
      $scope.closeAddPopup = function() { $scope.showAddPopup = false; };

      // Date normalization helper
      function normalizeDateToISO(dateStr) {
        if (!dateStr) return null;
        if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return dateStr;
        var m = String(dateStr).match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/);
        if (m) {
          var d = m[1].padStart(2,'0'), mo = m[2].padStart(2,'0'), y = m[3];
          if (+mo>=1 && +mo<=12 && +d>=1 && +d<=31) return y + '-' + mo + '-' + d;
        }
        var dt = new Date(dateStr);
        if (!isNaN(dt.getTime())) {
          var y = dt.getFullYear(), mo = String(dt.getMonth()+1).padStart(2,'0'), d = String(dt.getDate()).padStart(2,'0');
          return y + '-' + mo + '-' + d;
        }
        return null;
      }

      // Add new payment (single reliable implementation)
      $scope.addPayment = function() {
        try {
          if (!$scope.newPayment) { alert('No payment data'); return; }
          if (!$scope.newPayment.bill_id) { alert('Enter Bill ID'); return; }
          if (!$scope.newPayment.amount_paid || $scope.newPayment.amount_paid <= 0) { alert('Invalid amount'); return; }
          if (!$scope.newPayment.payment_method) { alert('Enter payment method'); return; }

          var isoDate = normalizeDateToISO($scope.newPayment.payment_date);
          if (!isoDate) { alert('Invalid date format. Use YYYY-MM-DD or DD-MM-YYYY.'); return; }
          $scope.newPayment.payment_date = isoDate;

          var payload = {
            bill_id: $scope.newPayment.bill_id,
            amount_paid: $scope.newPayment.amount_paid,
            payment_method: $scope.newPayment.payment_method,
            payment_date: $scope.newPayment.payment_date,
            bank_name: $scope.newPayment.bank_name || ''
          };

          console.log('POST payload -> addpayment.php:', payload);

          // IMPORTANT: use absolute path — adjust if save_payment.php is somewhere else.
          $http({
            method: 'POST',
            url: '/PritiProjectFinalFinalPaymentAddNeedToFix/addpayment.php',
            data: angular.toJson(payload),
            headers: { 'Content-Type': 'application/json' }
          })
          .then(function(resp) {
            console.log('addPayment server response:', resp);
            var data = resp.data;
            if (typeof data === 'string') {
              try { data = JSON.parse(data); } catch(e) {
                console.error('Could not parse server JSON:', resp.data);
                alert('Server returned invalid JSON. Check console Network response.');
                return;
              }
            }

            if (data.error) {
              alert('Error: ' + data.error);
              return;
            }
            if (data.success && data.payment) {
              $scope.payments.push(data.payment);
              $scope.closeAddPopup();
              alert('Payment added.');
              return;
            }
            if (data.payment_id) {
              $scope.payments.push(data);
              $scope.closeAddPopup();
              alert('Payment added (fallback).');
              return;
            }

            alert('Unexpected server response. See console for details.');
          })
          .catch(function(err) {
            console.error('HTTP POST failed:', err);
            var msg = (err.data && typeof err.data === 'string') ? err.data : (err.statusText || 'network error');
            alert('Request failed: ' + msg + ' — check console Network tab.');
          });

        } catch (ex) {
          console.error('addPayment exception:', ex);
          alert('Unexpected client error. See console.');
        }
      };

      // Edit popup
      $scope.openEditPopup = function(payment) {
        $scope.editPayment = angular.copy(payment) || {};
        if ($scope.editPayment.payment_date) {
          $scope.editPayment.payment_date = String($scope.editPayment.payment_date).slice(0,10);
        }
        $scope.showEditPopup = true;
      };
      $scope.closeEditPopup = function() { $scope.showEditPopup = false; };

      // Update Payment (normalizes date and posts JSON)
      $scope.updatePayment = function() {
        if (!$scope.editPayment || !$scope.editPayment.bill_id) { alert('Missing bill id'); return; }
        if (!$scope.editPayment.amount_paid || $scope.editPayment.amount_paid <= 0) { alert('Invalid amount'); return; }
        if (!$scope.editPayment.payment_method) { alert('Enter payment method'); return; }

        var isoDate = normalizeDateToISO($scope.editPayment.payment_date);
        if (!isoDate) { alert('Invalid date format. Please use YYYY-MM-DD or DD-MM-YYYY.'); return; }
        $scope.editPayment.payment_date = isoDate;

        var payload = {
          payment_id: $scope.editPayment.payment_id,
          bill_id: $scope.editPayment.bill_id,
          amount_paid: $scope.editPayment.amount_paid,
          payment_method: $scope.editPayment.payment_method,
          payment_date: $scope.editPayment.payment_date,
          bank_name: $scope.editPayment.bank_name || ''
        };

        $http({
          method: 'POST',
          url: '/PritiProjectFinalFinalPaymentAddNeedToFix/updatepayment.php',
          data: angular.toJson(payload),
          headers: { 'Content-Type': 'application/json' }
        })
        .then(function(response) {
          var data = response.data;
          if (typeof data === 'string') {
            try { data = JSON.parse(data); } catch(e) { console.error('Invalid JSON', response.data); alert('Server returned invalid JSON.'); return; }
          }
          if (data.error) { alert('Error updating payment: ' + data.error); return; }

          for (var i = 0; i < $scope.payments.length; i++) {
            if ($scope.payments[i].payment_id === $scope.editPayment.payment_id) {
              $scope.payments[i] = data.updatedPayment ? data.updatedPayment : angular.copy($scope.editPayment);
              break;
            }
          }
          $scope.closeEditPopup();
        })
        .catch(function(err) {
          console.error('Update failed:', err);
          alert('Failed to update payment. See console for details.');
        });
      };

      // Delete Payment
      $scope.deletePayment = function(payment_id) {
        if (!confirm("Are you sure you want to delete this payment?")) return;
        $http({
          method: 'POST',
          url: '/PritiProjectFinalFinalPaymentAddNeedToFix/deletepayment.php',
          data: angular.toJson({ payment_id: payment_id }),
          headers: { 'Content-Type': 'application/json' }
        })
        .then(function(resp) {
          var data = resp.data;
          if (typeof data === 'string') {
            try { data = JSON.parse(data); } catch(e) { console.error('Invalid JSON', resp.data); alert('Server returned invalid JSON.'); return; }
          }
          if (data.success) {
            $scope.payments = $scope.payments.filter(function(p){ return p.payment_id !== payment_id; });
          } else {
            alert('Error deleting payment: ' + (data.error || 'unknown'));
          }
        })
        .catch(function(err) {
          console.error('Delete failed:', err);
          alert('Failed to delete payment.');
        });
      };

    });

    // sidebar active tab helper
    app.run(function($rootScope, $location) {
      $rootScope.isActive = function(viewLocation) {
        return viewLocation === $location.path();
      };
    });
	
	
	//DashboardController
app.controller("DashboardController", function($scope, $http, $location, $timeout) {

    $scope.go = function(path) {
    $location.path(path);
  };
  
  
  $scope.genderLoaded = false;
  $scope.billingLoaded = false;

  let genderChart = null;
  let billingChart = null;

  $http.get('dashboard_stats.php').then(function(res) {

    $scope.stats = res.data;

    $scope.gender  = res.data.gender || { Male: 0, Female: 0 };
    $scope.billing = res.data.billing || [];

    // IMPORTANT
    $scope.genderLoaded  = true;
    $scope.billingLoaded = true;

    $timeout(function () {
      drawGenderChart();
      drawBillingChart();
    }, 0);

  });

function drawGenderChart() {
  if (genderChart) genderChart.destroy();

  const ctx = document.getElementById("genderChart");
  if (!ctx) return;

  genderChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Female', 'Male'],   // Female first
      datasets: [{
        data: [
          $scope.gender.Female || 0,
          $scope.gender.Male || 0
        ],
        backgroundColor: [
          '#1abc9c', // Female (GREEN)
          '#2980b9'  // Male (BLUE)
        ],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,

      // ⭐ THIS IS THE KEY ⭐
      rotation: -190,        // start from top
      circumference: 360,   // full circle

      plugins: {
        legend: {
          position: 'top'
        }
      }
    }
  });
}


  function drawBillingChart() {
    const ctx = document.getElementById("billingChart");
    if (!ctx) return;

    if (billingChart) billingChart.destroy();

    billingChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: $scope.billing.map(b => 'Bill ' + b.bill_id),
        datasets: [
          {
            label: 'Paid',
            data: $scope.billing.map(b => Number(b.paid_bill)),
            borderColor: '#16a34a',
            borderWidth: 2,
            fill: false
          },
          {
            label: 'Remaining',
            data: $scope.billing.map(b => Number(b.remaining_bill)),
            borderColor: '#dc2626',
            borderWidth: 2,
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
  }
});








app.controller("SecurityController", function($scope) {

    $scope.security = {

        users: [
            { avatar:"Jennifer.png", name:"Jennifer Doe", email:"jennifer@example.com", role:"Admin", lastLogin:"2 hours ago", active:true },
            { avatar:"Jason.png", name:"Jason Smith", email:"jason@example.com", role:"Admin", lastLogin:"1 day ago", active:false },
            { avatar:"Emma.png", name:"Emma Johnson", email:"emma@example.com", role:"Editor", lastLogin:"1 day ago", active:true }
        ],

        policy: {
            strongPasswords: true,
            minLength: 8,
            lockoutAttempts: 5,
            sessionTimeout: 30,
            enable2FA: false
        },

        sessions: [
            { id:1, user:"jane.doe@example.com", ip:"192.168.0.10", time:"5 min ago" },
            { id:2, user:"john.smith@example.com", ip:"10.0.0.5", time:"15 min ago" }
        ],

        audit: [
            "Login success — John",
            "Login failed — unknown IP",
            "Password changed — Emma",
            "Admin updated — Jason"
        ]
    };

    $scope.editAdmin = function(u){ alert("Edit admin: " + u.name); };
    $scope.resetPassword = function(u){ alert("Reset password for: " + u.name); };
    $scope.removeAdmin = function(u){ alert("Remove user: " + u.name); };

    $scope.savePolicies = function(){ alert("Policies saved!"); };

    $scope.logoutSession = function(id){ alert("Session logged out: " + id); };

});

  

 app.controller("HelpController", function ($scope) {

    // FAQ list
    $scope.faqs = [
        {
            title: "How to solve troubleshooting?",
            text: "Check for errors, restart the device/app, and update software; if it continues, follow the help guide or contact support."
        },
        {
            title: "How to solve billing & payments issue?",
            text: "Verify payment method, update card details, retry; if it still fails, reach billing support."
        },
        {
            title: "How to solve the account issue?",
            text: "Reset your password or verify identity; if account stays locked or inaccessible, contact support."
        }
    ];

    // Documentation Links
    $scope.docs = [
        { text: "User and Admin Guide", url: "#" },
        { text: "Policies & Security", url: "#" },
        { text: "Downloadable PDFs", url: "#" }
    ];

    // Support Contact
    $scope.support = {
        email: "support@medicarehub.com",
        phone: "+1-800-555-1234"
    };

});

  
  
  </script>

</body>
</html>
