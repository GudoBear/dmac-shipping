
/* ===== public/js/booking-wizard.js ===== */
﻿// Booking wizard behavior\n



/* ===== public/js/dmac-ui.js ===== */
(function(){
  function ready(fn){document.readyState!=='loading'?fn():document.addEventListener('DOMContentLoaded',fn);}
  ready(function(){
    if(!document.querySelector('.dmac-mobile-toggle')){
      var btn=document.createElement('button');
      btn.className='dmac-mobile-toggle';
      btn.type='button';
      btn.innerHTML='<i class="fa-solid fa-bars"></i>';
      btn.addEventListener('click',function(){document.body.classList.toggle('dmac-sidebar-open');});
      document.body.appendChild(btn);
    }
    document.addEventListener('click',function(e){
      if(window.innerWidth>1000) return;
      var side=document.querySelector('.sidebar');
      var toggle=document.querySelector('.dmac-mobile-toggle');
      if(document.body.classList.contains('dmac-sidebar-open') && side && !side.contains(e.target) && toggle && !toggle.contains(e.target)){
        document.body.classList.remove('dmac-sidebar-open');
      }
    });
    document.querySelectorAll('table').forEach(function(tbl){
      if(!tbl.closest('.table-responsive') && !tbl.closest('.employees-table-wrap')){
        var wrap=document.createElement('div');
        wrap.className='table-responsive';
        tbl.parentNode.insertBefore(wrap,tbl);wrap.appendChild(tbl);
      }
    });
  });
})();



/* ===== public/js/status-tracker.js ===== */
﻿// Shipment status tracker\n



/* ===== unified page scripts from inline PHP blocks ===== */
(function(){
  function ready(fn){document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn);}

  window.togglePermissions = function(value) {
    document.querySelectorAll('input[name="permissions[]"]').forEach(function(cb){ cb.checked = value; });
  };
  window.togglePermissionGroup = function(button, value) {
    var group = button && button.closest ? button.closest('.permission-group-block') : null;
    if (!group) return;
    group.querySelectorAll('input[name="permissions[]"]').forEach(function(cb){ cb.checked = value; });
  };
  window.selectOperationalAccess = function() {
    var allowed = ['dashboard_view','bookings_view','bookings_approve','bookings_assign','shipments_view','shipments_update'];
    document.querySelectorAll('input[name="permissions[]"]').forEach(function(cb){ cb.checked = allowed.indexOf(cb.dataset.key) !== -1; });
  };

  // Booking wizard
  var dropOffLocations = {
    "Mindoro": [
      "Calapan",
      "Naujan",
      "Victoria",
      "Socorro",
      "Pinamalayan",
      "Gloria",
      "Bansud",
      "Bongabong",
      "Roxas"
    ],
    "Aklan": [
      "Caticlan",
      "Nabas",
      "Ibajay",
      "Tangalan",
      "Makato",
      "Kalibo",
      "Banga",
      "Balete",
      "Altavas"
    ],
    "Capiz": [
      "Sapian",
      "Mambusao",
      "Ivisan",
      "Roxas City",
      "Panitan",
      "Sigma",
      "Dao",
      "Cuartero",
      "Dumarao"
    ],
    "Iloilo": [
      "Passi",
      "Duenas",
      "Dingle",
      "Pototan",
      "Zarraga",
      "Leganes",
      "Iloilo City",
      "Sta Barbara",
      "Pavia",
      "Jaro",
      "Oton"
    ],
    "Negros Occidental": [
      "CERES South and North Terminal",
      "NGC Bacolod City",
      "Bago City",
      "Pulupandan",
      "Hinigaran",
      "Binalbagan",
      "Himamaylan",
      "Kabankalan",
      "Mabinay",
      "Bais City",
      "Amlan",
      "San Jose",
      "Dumaguete City"
    ],
    "Camsur": [
      "Camsur",
      "Caluag",
      "Tagkawayan",
      "Liboro",
      "Ragay",
      "Sipocot",
      "Libmanan",
      "Bagacay",
      "Pamplona",
      "Milaor",
      "Naga",
      "Pili",
      "Baao",
      "Nabua Crossing",
      "Bato"
    ],
    "Albay": [
      "Matacon",
      "Polangui",
      "Ligao",
      "Guinobatan",
      "Daraga",
      "Pilar",
      "Putiao",
      "Sorsogon",
      "Bual",
      "Castilla",
      "Sorsogon City",
      "Casiuran",
      "Juban",
      "Irosin",
      "Matnog"
    ],
    "Samar": [
      "Allen",
      "Victoria",
      "Calbayog",
      "Sta Margarita",
      "Gandara",
      "Tarangan",
      "Catbalogan",
      "Paranas",
      "San Sebastian",
      "Santa Rita"
    ],
    "Leyte": [
      "Tacloban City",
      "Palo",
      "Tanauan",
      "Tolosa",
      "Mayorga",
      "Mahaplag Crossing",
      "Baybay City",
      "Inopacan",
      "Hindang",
      "Hilongos",
      "Bato",
      "Bontoc",
      "Sogod",
      "Tigbao",
      "Libagon",
      "Himayangan Crossing",
      "Liloan"
    ]
  };

  var animalNames = {"1": "Gamefowl", "2": "Chicks", "3": "Dog"};

  var provinceSelect = document.getElementById("receiver_province");
  var municipalitySelect = document.getElementById("receiver_municipality");

  if (provinceSelect && municipalitySelect) {
    Object.keys(dropOffLocations).forEach(function(province) {
      var option = document.createElement("option");
      option.value = province;
      option.textContent = province;
      provinceSelect.appendChild(option);
    });

    provinceSelect.addEventListener("change", function() {
      var selectedProvince = this.value;
      municipalitySelect.innerHTML = '<option value="">Select Municipality</option>';
      if (dropOffLocations[selectedProvince]) {
        dropOffLocations[selectedProvince].forEach(function(city) {
          var option = document.createElement("option");
          option.value = city;
          option.textContent = city;
          municipalitySelect.appendChild(option);
        });
      }
    });
  }

  window.loadMunicipalities = function() {
    if (provinceSelect) {
      provinceSelect.dispatchEvent(new Event('change'));
    }
  };

  window.changeStep = function(current, next) {
    if (next > current && !window.validateStep(current)) {
      alert('Please complete all required fields in this section before continuing.');
      return;
    }
    var currentEl = document.getElementById('wizard-step-' + current);
    var nextEl = document.getElementById('wizard-step-' + next);
    var currentInd = document.getElementById('step-indicator-' + current);
    var nextInd = document.getElementById('step-indicator-' + next);
    if (currentEl) currentEl.classList.remove('step-visible');
    if (nextEl) nextEl.classList.add('step-visible');
    if (currentInd) currentInd.classList.remove('active');
    if (nextInd) nextInd.classList.add('active');
  };

  window.validateStep = function(stepNumber) {
    var currentStepEl = document.getElementById('wizard-step-' + stepNumber);
    if (!currentStepEl) return true;
    var fields = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
    var valid = true;
    fields.forEach(function(field){
      var fieldValid = field.type === 'checkbox' ? field.checked : String(field.value || '').trim() !== '';
      if (!fieldValid || (field.pattern && field.value && !(new RegExp(field.pattern).test(field.value)))) {
        valid = false;
        field.classList.add('input-error');
      } else {
        field.classList.remove('input-error');
      }
    });
    return valid;
  };

  window.addAnimalRow = function() {
    var container = document.getElementById('animalBatchContainer');
    if (!container) return;
    var newRow = document.createElement('div');
    newRow.className = 'animal-batch-row row-animated';
    newRow.innerHTML = '\n      <div class="form-group max-width-select">\n        <label>Animal Type *</label>\n        <select name="animal_types[]" class="form-control animal-type" required>\n          <option value="">Select Animal</option>\n          <option value="1">Gamefowl</option>\n          <option value="2">Chicks</option>\n          <option value="3">Dog</option>\n        </select>\n      </div>\n      <div class="form-group max-width-input">\n        <label>Quantity *</label>\n        <input type="number" name="animal_quantities[]" min="1" value="1" class="form-control animal-qty" required>\n      </div>\n      <button type="button" class="btn-remove-row" onclick="this.parentElement.remove(); togglePreviewButton();"><i class="fa-solid fa-trash-can"></i></button>';
    container.appendChild(newRow);
  };

  window.togglePreviewButton = function() {
    var previewBtn = document.getElementById('previewBtn');
    var terms = document.getElementById('terms_accepted');
    var ins = document.getElementById('insurance_accepted');
    if (!previewBtn || !terms || !ins) return;
    var enabled = terms.checked && ins.checked;
    previewBtn.disabled = !enabled;
    previewBtn.classList.toggle('btn-disabled', !enabled);
  };

  function valueOf(name) {
    var field = document.querySelector('[name="' + name + '"]');
    return field ? String(field.value || '').trim() : '';
  }
  function makeLine(label, value) { return '<div class="preview-line"><span>' + label + '</span><span>' + (value || '-') + '</span></div>'; }
  function getAnimalPreview() {
    var types = document.querySelectorAll('.animal-type');
    var qtys = document.querySelectorAll('.animal-qty');
    var lines = '';
    types.forEach(function(type, index){
      if (type.value) lines += makeLine(animalNames[type.value] || type.value, (qtys[index] && qtys[index].value) || '1');
    });
    return lines || makeLine('Animals', '-');
  }
  window.showPreview = function() {
    if (!window.validateStep(1) || !window.validateStep(2) || !window.validateStep(3) || !window.validateStep(4)) {
      alert('Please complete all required fields and agreements before previewing.');
      return;
    }
    var previewContent = document.getElementById('previewContent');
    if (!previewContent) return;
    previewContent.innerHTML = '<div class="preview-section"><h4>Pickup Details</h4>'+
      makeLine('Contact Name', valueOf('pickup_firstname') + ' ' + valueOf('pickup_lastname'))+
      makeLine('Contact Number', valueOf('pickup_number'))+
      makeLine('Pickup Address', valueOf('pickup_street'))+
      makeLine('Pickup Location', valueOf('pickup_municipality') + ', ' + valueOf('pickup_province'))+'</div>'+
      '<div class="preview-section"><h4>Receiver Details</h4>'+
      makeLine('Receiver Name', valueOf('receiver_firstname') + ' ' + valueOf('receiver_lastname'))+
      makeLine('Receiver Contact', valueOf('receiver_contact'))+
      makeLine('Receiver Address', valueOf('receiver_street'))+
      makeLine('Drop-off Location', valueOf('receiver_municipality') + ', ' + valueOf('receiver_province'))+'</div>'+
      '<div class="preview-section"><h4>Animal Details</h4>'+
      getAnimalPreview()+makeLine('Request Ship Date', valueOf('booking_requestdate'))+'</div>'+
      '<div class="preview-section"><h4>Agreement</h4>'+makeLine('Terms and Agreement','Accepted')+makeLine('Insurance Policy','Accepted')+makeLine('Initial Status','PENDING REVIEW')+'</div>';
    var modal = document.getElementById('previewModal');
    if (modal) { modal.classList.add('show'); modal.setAttribute('aria-hidden','false'); }
  };
  window.closePreview = function() {
    var modal = document.getElementById('previewModal');
    if (modal) { modal.classList.remove('show'); modal.setAttribute('aria-hidden','true'); }
  };
  window.confirmBooking = function() {
    var confirmed = document.getElementById('booking_confirmed');
    var form = document.getElementById('shippingWizardForm');
    if (confirmed) confirmed.value = '1';
    if (form) form.submit();
  };

  // Active shipment transport mode sections
  function initTransportSections(){
    document.querySelectorAll('.transport-mode-select').forEach(function(select) {
      function toggleSections() {
        var booking = select.dataset.booking;
        var selected = select.value === '1' ? 'air' : 'land';
        document.querySelectorAll('.travel-section[data-booking="' + booking + '"]').forEach(function(section) {
          section.style.display = section.dataset.type === selected ? 'block' : 'none';
          section.querySelectorAll('input, select').forEach(function(field) {
            if (section.dataset.type === selected) field.removeAttribute('disabled');
            else field.setAttribute('disabled', 'disabled');
          });
        });
      }
      select.addEventListener('change', toggleSections);
      toggleSections();
    });
  }

  // Billing modal
  window.peso = function(value) {
    var amount = Number(value) || 0;
    return '₱' + amount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
  };
  window.calcBillingTotal = function(form) {
    if (!form) return;
    var box = parseFloat(form.box_fee && form.box_fee.value) || 0;
    var pickup = parseFloat(form.pickup_fee && form.pickup_fee.value) || 0;
    var shipping = parseFloat(form.shipping_fee && form.shipping_fee.value) || 0;
    var headPrice = parseFloat(form.head_price && form.head_price.value) || 0;
    var heads = parseInt(form.number_of_heads && form.number_of_heads.value) || 0;
    var preview = document.getElementById('totalPreview');
    if (preview) preview.textContent = window.peso(box + pickup + shipping + (heads * headPrice));
  };
  window.hideBillingModal = function() { var modal = document.getElementById('billingModal'); if (modal) modal.classList.remove('show'); };
  window.closeBillingModal = function(event) { if (event.target && event.target.id === 'billingModal') window.hideBillingModal(); };
  window.setMethodRules = function(shipmentType, selectedMethodId) {
    var select = document.getElementById('paymethod_id');
    var airNote = document.getElementById('airNote');
    if (!select) return;
    shipmentType = (shipmentType || '').toUpperCase();
    var firstOnline = null;
    Array.from(select.options).forEach(function(option){
      if (!option.value) return;
      var method = (option.dataset.method || '').toUpperCase();
      var isOnline = method.indexOf('ONLINE') !== -1;
      if (isOnline && !firstOnline) firstOnline = option.value;
      option.disabled = shipmentType === 'AIR' && !isOnline;
    });
    if (shipmentType === 'AIR') {
      if (airNote) { airNote.style.display = 'block'; airNote.innerHTML = '<i class="fa-solid fa-plane"></i> Air travel requires Online Payment only.'; }
      var current = select.options[select.selectedIndex];
      var currentIsOnline = current && (current.dataset.method || '').toUpperCase().indexOf('ONLINE') !== -1;
      if (!selectedMethodId || !currentIsOnline) select.value = firstOnline || '';
    } else if (shipmentType === 'LAND') {
      if (airNote) { airNote.style.display = 'block'; airNote.innerHTML = '<i class="fa-solid fa-truck"></i> Land transport allows Cash on Delivery or Online Payment.'; }
      if (selectedMethodId) select.value = String(selectedMethodId);
    } else {
      if (airNote) { airNote.style.display = 'block'; airNote.innerHTML = '<i class="fa-solid fa-circle-info"></i> Select Land or Air before saving billing.'; }
      select.value = selectedMethodId || '';
    }
  };
  window.openBillingModal = function(data) {
    var modal = document.getElementById('billingModal');
    if (!modal) return;
    document.getElementById('modalTitle').textContent = 'Billing Setup for Booking #' + data.bookingId;
    document.getElementById('modalSubtitle').textContent = (data.clientName || 'Client') + ' • Transportation mode set by admin/staff';
    document.getElementById('booking_id').value = data.bookingId || '';
    document.getElementById('shipment_type').value = (data.shipmentType && data.shipmentType !== 'NOT SET') ? data.shipmentType : '';
    document.getElementById('box_fee').value = Number(data.boxFee || 0).toFixed(2);
    document.getElementById('pickup_fee').value = Number(data.pickupFee || 0).toFixed(2);
    document.getElementById('shipping_fee').value = Number(data.shippingFee || 0).toFixed(2);
    document.getElementById('head_price').value = Number(data.headPrice || 0).toFixed(2);
    document.getElementById('number_of_heads').value = parseInt(data.numberOfHeads || 0);
    document.getElementById('payment_status').value = data.paymentStatus || 'PENDING';
    document.getElementById('payment_reference').value = data.paymentReference || '';
    var methodSelect = document.getElementById('paymethod_id');
    if (methodSelect) methodSelect.value = data.paymethodId ? String(data.paymethodId) : '';
    window.setMethodRules((data.shipmentType && data.shipmentType !== 'NOT SET') ? data.shipmentType : '', data.paymethodId ? String(data.paymethodId) : '');
    window.calcBillingTotal(document.querySelector('.billing-form'));
    modal.classList.add('show');
  };

  ready(function(){
    window.togglePreviewButton();
    initTransportSections();
    document.addEventListener('keydown', function(event){ if (event.key === 'Escape') window.hideBillingModal(); });
  });
})();

/* Final DMAC sidebar mobile behavior */
(function(){
  function ready(fn){document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn);}
  ready(function(){
    if(!document.querySelector('.dmac-mobile-toggle')){
      var btn=document.createElement('button');
      btn.className='dmac-mobile-toggle';
      btn.type='button';
      btn.setAttribute('aria-label','Open sidebar');
      btn.innerHTML='<i class="fa-solid fa-bars"></i>';
      document.body.appendChild(btn);
    }
    var toggle=document.querySelector('.dmac-mobile-toggle');
    if(toggle && !toggle.dataset.bound){
      toggle.dataset.bound='1';
      toggle.addEventListener('click',function(){document.body.classList.toggle('dmac-sidebar-open');});
    }
    document.querySelectorAll('.sidebar a').forEach(function(link){
      link.addEventListener('click',function(){
        if(window.innerWidth <= 1000){document.body.classList.remove('dmac-sidebar-open');}
      });
    });
  });
})();

/* =========================================================
   DMAC Sidebar Profile Dropdown
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
    var profileCards = document.querySelectorAll('.sidebar-profile');

    profileCards.forEach(function (card) {
        var toggle = card.querySelector('.sidebar-profile-toggle');
        if (!toggle) return;

        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            profileCards.forEach(function (otherCard) {
                if (otherCard !== card) {
                    otherCard.classList.remove('open');
                    var otherToggle = otherCard.querySelector('.sidebar-profile-toggle');
                    if (otherToggle) otherToggle.setAttribute('aria-expanded', 'false');
                }
            });

            var isOpen = card.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    document.addEventListener('click', function () {
        profileCards.forEach(function (card) {
            card.classList.remove('open');
            var toggle = card.querySelector('.sidebar-profile-toggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        });
    });
});

/* =========================================================
   DMAC SweetAlert Global Success + Logout Confirmation
   ========================================================= */
(function () {
    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }

    function loadSweetAlert(callback) {
        if (window.Swal) {
            callback();
            return;
        }

        var existing = document.querySelector('script[data-dmac-swal="1"]');
        if (existing) {
            existing.addEventListener('load', callback);
            return;
        }

        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        script.setAttribute('data-dmac-swal', '1');
        script.onload = callback;
        document.head.appendChild(script);
    }

    function getParam(name) {
        return new URLSearchParams(window.location.search).get(name);
    }

    function cleanUrl() {
        if (!window.history || !window.history.replaceState) return;
        var clean = window.location.pathname;
        window.history.replaceState({}, document.title, clean);
    }

    function getSuccessMessage() {
        var params = new URLSearchParams(window.location.search);

        if (params.get('msg') === 'registered') return 'Registration successful. Please login.';
        if (params.get('msg') === 'staff_registered') return 'Employee account created successfully.';

        if (params.get('booking') === 'success') return 'Booking submitted successfully.';
        if (params.get('updated') === '1') return 'Record updated successfully.';
        if (params.get('payment') === 'success') return 'Billing information updated successfully.';
        if (params.get('role_added') === '1') return 'New role added successfully.';
        if (params.get('position_updated') === '1') return 'Employee position updated successfully.';

        var success = params.get('success');
        if (success !== null) {
            if (success === 'profile') return 'Profile updated successfully.';
            if (success === 'password') return 'Password changed successfully.';
            if (success === 'booking_created') return 'Booking created successfully.';
            if (success === 'deleted') return 'Employee account disabled successfully.';
            if (success === 'updated') return 'Employee details and access updated successfully.';
            if (success === 'saved') return 'Data saved successfully.';
            return 'Action completed successfully.';
        }

        var successBox = document.querySelector('.alert-success:not(.alert-error):not(.alert-warning), .alert.alert-success');
        if (successBox && successBox.textContent.trim() !== '') {
            return successBox.textContent.trim();
        }

        return '';
    }

    function showSuccessAlert() {
        var message = getSuccessMessage();
        if (!message) return;

        loadSweetAlert(function () {
            if (!window.Swal) return;

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#0f6b43',
                background: '#ffffff',
                color: '#063b28',
                customClass: {
                    popup: 'dmac-swal-popup',
                    title: 'dmac-swal-title',
                    confirmButton: 'dmac-swal-confirm'
                }
            }).then(function () {
                cleanUrl();
            });
        });
    }

    function bindLogoutSweetAlert() {
        document.querySelectorAll('a[href$="logout.php"], a.logout-link, a.logout-settings-btn').forEach(function (link) {
            if (link.dataset.swalLogoutBound === '1') return;
            link.dataset.swalLogoutBound = '1';

            link.addEventListener('click', function (event) {
                event.preventDefault();
                var href = link.getAttribute('href') || 'logout.php';

                loadSweetAlert(function () {
                    if (!window.Swal) {
                        window.location.href = href;
                        return;
                    }

                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure you want to log out?',
                        text: 'You will need to login again to access your account.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, log out',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#0f6b43',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        background: '#ffffff',
                        color: '#063b28',
                        customClass: {
                            popup: 'dmac-swal-popup',
                            title: 'dmac-swal-title',
                            confirmButton: 'dmac-swal-confirm',
                            cancelButton: 'dmac-swal-cancel'
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });
        });
    }

    ready(function () {
        showSuccessAlert();
        bindLogoutSweetAlert();
    });
})();


/* DMAC 11-digit contact limiter + email helper */
document.addEventListener('input', function (event) {
    var input = event.target;
    if (!input || !input.name) return;

    var contactNames = [
        'client_contact',
        'emp_contact',
        'pickup_number',
        'receiver_contact'
    ];

    if (contactNames.indexOf(input.name) !== -1) {
        input.value = input.value.replace(/\D/g, '').slice(0, 11);
    }
});

document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!form || !form.querySelectorAll) return;

    var contactInputs = form.querySelectorAll('input[name="client_contact"], input[name="emp_contact"], input[name="pickup_number"], input[name="receiver_contact"]');
    for (var i = 0; i < contactInputs.length; i++) {
        var value = contactInputs[i].value.trim();
        if (!/^09\d{9}$/.test(value)) {
            event.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid contact number',
                    text: 'Contact number must be exactly 11 digits and start with 09.',
                    confirmButtonColor: '#137c49'
                });
            } else {
                alert('Contact number must be exactly 11 digits and start with 09.');
            }
            contactInputs[i].focus();
            return;
        }
    }

    var emailInputs = form.querySelectorAll('input[type="email"]');
    for (var j = 0; j < emailInputs.length; j++) {
        var email = emailInputs[j].value.trim();
        if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
            event.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid email address',
                    text: 'Please enter a valid email address, example: name@gmail.com.',
                    confirmButtonColor: '#137c49'
                });
            } else {
                alert('Please enter a valid email address, example: name@gmail.com.');
            }
            emailInputs[j].focus();
            return;
        }
    }
});

/* Terms / Insurance tabs */
window.showAgreement = function(pageId, clickedEl) {
  document.querySelectorAll('.agreement-page').forEach(function(page){
    page.classList.remove('active');
  });
  document.querySelectorAll('.terms-nav').forEach(function(nav){
    nav.classList.remove('active');
  });
  var target = document.getElementById(pageId);
  if (target) target.classList.add('active');
  if (clickedEl) clickedEl.classList.add('active');
};
