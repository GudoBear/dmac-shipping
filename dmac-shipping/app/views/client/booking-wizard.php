<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['client_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$errorMessages = [
    'missing' => 'Please complete all required fields before previewing or confirming your booking.',
    'date' => 'Requested ship date cannot be in the past.',
    'animal' => 'Please add at least one animal type with a valid quantity.',
    'agreement' => 'You must accept the Terms and Agreement and Insurance Policy before confirming.',
    'phone' => 'Please enter valid contact numbers.',
    'pickup_phone' => 'Please enter a valid pickup contact number.',
    'receiver_phone' => 'Please enter a valid receiver contact number.',
    'server' => 'Something went wrong while saving your booking. Please try again.'
];
$successMessages = [
    'saved' => 'Booking request submitted successfully.'
];
?>
<?php require_once __DIR__ . '/../../helpers/client_nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Shipment - DMAC Shipping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../public/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">

    <?php showClientSidebar(); ?>

<div class="main-content">
        <header>
            <div class="header-title">
                <h1>Create Shipment Booking</h1>
                <small>Complete the details, review the preview, then confirm your booking.</small>
            </div>
        </header>

        <main>
            <?php if (isset($_GET['error']) && isset($errorMessages[$_GET['error']])): ?>
                <div class="alert-error" style="padding:12px 14px;border-radius:13px;margin-bottom:16px;font-weight:700;border:1px solid #fecaca;">
                    <?php echo htmlspecialchars($errorMessages[$_GET['error']]); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['success']) && isset($successMessages[$_GET['success']])): ?>
                <div class="alert-success"><?php echo htmlspecialchars($successMessages[$_GET['success']]); ?></div>
            <?php endif; ?>

            <div class="wizard-progress">
                <div class="step active" id="step-indicator-1">
                    <span class="step-number">1</span>
                    <span class="step-label">Pickup Details</span>
                </div>
                <div class="step" id="step-indicator-2">
                    <span class="step-number">2</span>
                    <span class="step-label">Receiver Details</span>
                </div>
                <div class="step" id="step-indicator-3">
                    <span class="step-number">3</span>
                    <span class="step-label">Shipment Details</span>
                </div>
                <div class="step" id="step-indicator-4">
                    <span class="step-number">4</span>
                    <span class="step-label">Agreement & Preview</span>
                </div>
            </div>

            <div class="wizard-card">
                <form id="shippingWizardForm" action="process-booking.php" method="POST" novalidate>
                    <input type="hidden" name="booking_confirmed" id="booking_confirmed" value="0">

                    <div class="wizard-step step-visible" id="wizard-step-1">
                        <h3><i class="fa-solid fa-location-dot"></i> Pickup Details</h3>
                        <p class="step-subtitle">Input the contact person and pickup address.</p>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Contact First Name *</label>
                                <input type="text" name="pickup_firstname" data-preview="Pickup First Name" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Last Name *</label>
                                <input type="text" name="pickup_lastname" data-preview="Pickup Last Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contact Number *</label>
                            <input type="tel" name="pickup_number" data-preview="Pickup Contact" required placeholder="09XXXXXXXXX" maxlength="11" minlength="11" inputmode="numeric" pattern="09[0-9]{9}" title="Contact number must be exactly 11 digits and start with 09.">
                            <div class="help-text">Example: 09123456789 or +639123456789</div>
                        </div>
                        <div class="form-group">
                            <label>Pickup Address *</label>
                            <input type="text" name="pickup_street" data-preview="Pickup Address" required placeholder="House no., street, barangay, landmark">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Pickup Municipality / City *</label>
                                <input type="text" name="pickup_municipality" data-preview="Pickup Municipality" required>
                            </div>
                            <div class="form-group">
                                <label>Pickup Province *</label>
                                <input type="text" name="pickup_province" data-preview="Pickup Province" required>
                            </div>
                        </div>

                        <div class="wizard-buttons">
                            <button type="button" class="btn-primary" onclick="changeStep(1, 2)">Next Step <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <div class="wizard-step" id="wizard-step-2">
                        <h3><i class="fa-solid fa-user-location"></i> Receiver Details</h3>
                        <p class="step-subtitle">Input receiver information and choose the fixed drop-off location.</p>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Receiver First Name *</label>
                                <input type="text" name="receiver_firstname" data-preview="Receiver First Name" required>
                            </div>
                            <div class="form-group">
                                <label>Receiver Last Name *</label>
                                <input type="text" name="receiver_lastname" data-preview="Receiver Last Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Receiver Contact Number *</label>
                            <input type="tel" name="receiver_contact" data-preview="Receiver Contact" required placeholder="09XXXXXXXXX" maxlength="11" minlength="11" inputmode="numeric" pattern="09[0-9]{9}" title="Contact number must be exactly 11 digits and start with 09.">
                        </div>
                        <div class="form-group">
                            <label>Receiver Address *</label>
                            <input type="text" name="receiver_street" data-preview="Receiver Address" required placeholder="House no., street, barangay, landmark">
                        </div>

                        <div class="location-note">
                            <i class="fa-solid fa-circle-info"></i> Choose the drop-off location from the approved municipality/province list.
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Drop-off Province *</label>
                                <select name="receiver_province" id="receiver_province" data-preview="Drop-off Province" required onchange="loadMunicipalities()">
                                    <option value="">Select Province</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Drop-off Municipality / City *</label>
                                <select name="receiver_municipality" id="receiver_municipality" data-preview="Drop-off Municipality" required>
                                    <option value="">Select Municipality</option>
                                </select>
                            </div>
                        </div>

                        <div class="wizard-buttons">
                            <button type="button" class="btn-secondary-outline" onclick="changeStep(2, 1)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="button" class="btn-primary" onclick="changeStep(2, 3)">Next Step <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <div class="wizard-step" id="wizard-step-3">
                        <h3><i class="fa-solid fa-egg"></i> Other Shipment Details</h3>
                        <p class="step-subtitle">Input animal type, quantity, and requested ship date. Transportation mode will be set by admin or authorized staff after review.</p>

                        <div id="animalBatchContainer">
                            <div class="animal-batch-row">
                                <div class="form-group max-width-select">
                                    <label>Animal Type *</label>
                                    <select name="animal_types[]" class="form-control animal-type" required>
                                        <option value="">Select Animal</option>
                                        <option value="1">Gamefowl</option>
                                        <option value="2">Chicks</option>
                                        <option value="3">Dog</option>
                                    </select>
                                </div>
                                <div class="form-group max-width-input">
                                    <label>Quantity *</label>
                                    <input type="number" name="animal_quantities[]" min="1" value="1" class="form-control animal-qty" required>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn-add-row" onclick="addAnimalRow()">
                            <i class="fa-solid fa-plus-circle"></i> Add Another Animal Type
                        </button>


                        <div class="form-group margin-top-lg">
                            <label>Request Ship Date *</label>
                            <input type="date" name="booking_requestdate" data-preview="Request Ship Date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="wizard-buttons">
                            <button type="button" class="btn-secondary-outline" onclick="changeStep(3, 2)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="button" class="btn-primary" onclick="changeStep(3, 4)">Next Step <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <div class="wizard-step" id="wizard-step-4">
                        <h3><i class="fa-solid fa-file-signature"></i> Terms, Insurance & Preview</h3>
                        <p class="step-subtitle">You must review and check both agreements before previewing your booking.</p>

                        <div class="terms-box terms-layout-box">
                            <div class="terms-sidebar">
                                <button type="button" class="terms-nav active" onclick="showAgreement('termsPage', this)">
                                    <span>1</span>
                                    Terms of Agreement
                                </button>
                                <button type="button" class="terms-nav" onclick="showAgreement('insurancePage', this)">
                                    <span>2</span>
                                    Insurance Policy
                                </button>
                            </div>

                            <div class="terms-content">
                                <div id="termsPage" class="agreement-page active">
                                    <h4><i class="fa-solid fa-file-contract"></i> Terms and Agreement</h4>
                                    <p class="help-text">Please review and agree before continuing your booking.</p>

                                    <div class="agreement-text">
                                        <h1>Terms of Agreement</h1>
                                        <h2>DMAC SHIPPING – TERMS AND CONDITIONS</h2>

                                        <h3>SECTION I: PROCESSING TIME (Tagal ng Processing)</h3>
                                        <ul class="terms-list">
                                            <li>Ang lahat ng shipment requests ay pinoproseso sa loob ng <strong>4–7 business days</strong>, basta kumpleto at tama ang lahat ng required information at documents.</li>
                                            <li><strong>Maaaring magbago ang processing time depende sa:</strong>
                                                <ul>
                                                    <li>availability ng flight bookings</li>
                                                    <li>ventilation ng aircraft</li>
                                                    <li>permit approvals</li>
                                                    <li>dami ng shipments</li>
                                                    <li>weather at operational conditions</li>
                                                </ul>
                                            </li>
                                        </ul>

                                        <h3>SECTION II: HOW TO BOOK (Paano Magpa-Book)</h3>
                                        <ul class="terms-list">
                                            <li>Para ma-process ang shipment, kailangang ibigay ng client ang origin, contact person, number of heads, destination, receiver name, and receiver contact number.</li>
                                            <li><strong>Paalaala:</strong> Kapag kulang ang details, maaaring ma-delay o ma-reject ang booking.</li>
                                        </ul>

                                        <h3>SECTION III: LEAD TIME & FLIGHT AVAILABILITY</h3>
                                        <ul class="terms-list">
                                            <li>Hindi makakapagbigay ang DMAC Shipping ng exact shipment date dahil ang schedule ay nakadepende sa airline availability, aircraft assignment, flight cancellation, permit issuance, pickup location, and logistics.</li>
                                            <li>Karaniwan, nagpi-pick up lang kami kapag may confirmed o highly probable flight booking.</li>
                                            <li>Kapag may offload o flight cancellation, agad naming ina-update ang shipper at receiver, inaalagaan ang gamefowls sa DMAC holding facility, at inaayos ang rescheduling kapag may available na flight.</li>
                                            <li><strong>Hindi liable ang DMAC Shipping sa delays na dulot ng airline discretion.</strong></li>
                                        </ul>

                                        <h3>SECTION IV: RATES & PICKUP</h3>
                                        <ul class="terms-list">
                                            <li>Standard rates: ₱3,000 double box and ₱2,000 single box.</li>
                                            <li>May pickup charge depende sa location.</li>
                                            <li>Client options include airport meet-up with DMAC staff or delivery via Lalamove, Grab, or equivalent services.</li>
                                        </ul>

                                        <h3>SECTION IV.1: HOLDING FACILITY ADDRESSES</h3>
                                        <ul class="terms-list">
                                            <li><strong>3PLJ Services:</strong> Wawa 3, Rosario, Cavite — Russell Copon, 0997 501 2668</li>
                                            <li><strong>DMAC Farm:</strong> Pansol, Padre Garcia, Batangas — Gladys Dimaculangan, 0917 574 2934</li>
                                        </ul>

                                        <h3>SECTION V: CASUALTY & INSURANCE POLICY</h3>
                                        <ul class="terms-list">
                                            <li>May kaakibat na risk ang pagbiyahe ng live animals, lalo na kung may factors na wala sa control ng DMAC.</li>
                                            <li><strong>Free Basic Insurance</strong> is subject to strict eligibility and investigation.</li>
                                            <li>Claims require proof that casualty was caused by direct negligence of DMAC staff.</li>
                                            <li>Paid DMAC Shipping Insurance may be available for wider protection. Insurance cost is 2% of declared value.</li>
                                            <li>Coverage may be 75% of declared value for eligible shipments.</li>
                                            <li>Eligible birds must be mature, healthy, and fit to ship.</li>
                                            <li>Month-old chicks, juveniles, and immature fowls may not be eligible.</li>
                                        </ul>

                                        <h3>LAND TRAVEL COVERAGE</h3>
                                        <ul class="terms-list">
                                            <li>Land travel is limited to approved areas only and subject to route availability, weather, road conditions, RORO schedules, and operational assessment.</li>
                                            <li>DMAC Shipping may adjust or decline land travel if the shipment is high-risk.</li>
                                        </ul>

                                        <h3>LAND TRAVEL – AREA 1</h3>
                                        <h4>MINDORO</h4>
                                        <ul class="area-list"><li>Calapan</li><li>Naujan</li><li>Victoria</li><li>Socorro</li><li>Pinamalayan</li><li>Gloria</li><li>Bansud</li><li>Bongabong</li><li>Roxas</li></ul>
                                        <h4>AKLAN</h4>
                                        <ul class="area-list"><li>Caticlan</li><li>Nabas</li><li>Ibajay</li><li>Tangalan</li><li>Makato</li><li>Kalibo</li><li>Banga</li><li>Balete</li><li>Altavas</li></ul>
                                        <h4>CAPIZ</h4>
                                        <ul class="area-list"><li>Sapian or Mambusao, subject to route availability</li><li>Ivisan</li><li>Roxas City</li><li>Panitan</li><li>Sigma</li><li>Dao</li><li>Cuartero</li><li>Dumarao</li></ul>
                                        <h4>ILOILO</h4>
                                        <ul class="area-list"><li>Passi</li><li>Dueñas</li><li>Dingle</li><li>Pototan</li><li>Zarraga</li><li>Leganes</li><li>Iloilo City</li><li>Sta. Barbara</li><li>Pavia</li><li>Jaro</li><li>Oton</li></ul>
                                        <h4>NEGROS OCCIDENTAL / NEGROS ORIENTAL</h4>
                                        <ul class="area-list"><li>Ceres South & North Terminal</li><li>NGC, Bacolod City</li><li>Bago City</li><li>Pulupandan</li><li>Hinigaran</li><li>Binalbagan</li><li>Himamaylan</li><li>Kabankalan</li><li>Mabinay</li><li>Bais City</li><li>Amlan</li><li>San Jose</li><li>Dumaguete City</li></ul>

                                        <h3>LAND TRAVEL – AREA 2</h3>
                                        <h4>BICOL REGION – CAMARINES SUR</h4>
                                        <ul class="area-list"><li>Calauag</li><li>Tagkawayan</li><li>Liboro</li><li>Ragay</li><li>Sipocot</li><li>Libmanan</li><li>Bagacay</li><li>Pamplona</li><li>Milaor</li><li>Naga</li><li>Pili</li><li>Baao</li><li>Nabua Crossing</li><li>Bato</li></ul>
                                        <h4>BICOL REGION – ALBAY</h4>
                                        <ul class="area-list"><li>Matacon</li><li>Polangui</li><li>Ligao</li><li>Guinobatan</li><li>Daraga</li><li>Pilar</li><li>Putiao</li></ul>
                                        <h4>SORSOGON</h4>
                                        <ul class="area-list"><li>Bulan</li><li>Castilla</li><li>Sorsogon City</li><li>Casiguran</li><li>Juban</li><li>Irosin</li><li>Matnog</li></ul>
                                        <h4>SAMAR</h4>
                                        <ul class="area-list"><li>Allen</li><li>Victoria</li><li>Calbayog</li><li>Sta. Margarita</li><li>Gandara</li><li>Tarangan</li><li>Catbalogan</li><li>Paranas</li><li>San Sebastian</li><li>Sta. Rita</li></ul>
                                        <h4>LEYTE</h4>
                                        <ul class="area-list"><li>Tacloban City</li><li>Palo</li><li>Tanauan</li><li>Tolosa</li><li>Mayorga</li><li>Mahaplag Crossing</li><li>Baybay City</li><li>Inopacan</li><li>Hindang</li><li>Hilongos</li></ul>

                                        <h3>IMPORTANT NOTES</h3>
                                        <ul class="terms-list">
                                            <li>Land travel coverage is subject to confirmation by DMAC Travel Team.</li>
                                            <li>Routes may change due to weather, road conditions, RORO schedules, and operational constraints.</li>
                                            <li>DMAC Shipping reserves the right to limit, adjust, or decline land travel requests when conditions are high-risk.</li>
                                        </ul>
                                    </div>

                                    <div class="check-row">
                                        <input type="checkbox" name="terms_accepted" id="terms_accepted" value="1" onchange="togglePreviewButton()" required>
                                        <label for="terms_accepted">I have read and agree to the Terms and Agreement.</label>
                                    </div>
                                </div>

                                <div id="insurancePage" class="agreement-page">
                                    <h4><i class="fa-solid fa-shield-heart"></i> Insurance Policy</h4>
                                    <p class="help-text">Please review the insurance coverage and policies carefully.</p>

                                    <div class="agreement-text">
                                        <h1>Insurance Fee Regulation</h1>
                                        <h2>DMAC SHIPPING – INSURANCE TERMS & CONDITIONS</h2>
                                        <p>DMAC Shipping strengthens its commitment to safe, reliable, and accountable live animal transport. Starting May 1, 2026, all qualified shipments are automatically covered under DMAC Shipping Insurance with simplified guidelines and standardized benefits.</p>

                                        <h3>DEFINITION OF TERMS</h3>
                                        <ul class="terms-list">
                                            <li><strong>Shipping Insurance</strong> – protection included in the shipping fee that provides compensation in case of casualty of eligible birds during shipment.</li>
                                            <li><strong>Casualty</strong> – any instance where a bird is found dead upon arrival.</li>
                                            <li><strong>Eligible Shipment</strong> – complies with DMAC requirements, proper compartment setup, qualified age, and healthy condition upon turnover.</li>
                                            <li><strong>Non-Eligible Shipment</strong> – does not meet eligibility criteria, including two heads per compartment, day-old chicks, and month-old chicks in shared compartments.</li>
                                            <li><strong>Proof of Loss</strong> – photos or videos taken immediately upon arrival showing the condition of the bird.</li>
                                        </ul>

                                        <h3>ARTICLE I: COVERAGE INCLUSION</h3>
                                        <ul class="terms-list"><li>Starting May 1, 2026, all qualified shipments are automatically covered by DMAC Shipping Insurance.</li><li>The insurance cost is already included in the shipping fee.</li></ul>

                                        <h3>ARTICLE II: ELIGIBILITY</h3>
                                        <ul class="terms-list"><li>One head per compartment.</li><li>Birds must be healthy upon turnover.</li><li>Birds must be aged 2 months and above.</li><li>Day-old chicks and non-compliant shipments are not eligible.</li></ul>

                                        <h3>ARTICLE III: COVERAGE BENEFIT</h3>
                                        <ul class="terms-list"><li>Eligible big clients may be entitled to ₱7,000 claim per head in case of casualty.</li></ul>

                                        <h3>ARTICLE IV: SCOPE OF COVERAGE</h3>
                                        <ul class="terms-list"><li>Covers casualty during shipping process provided eligibility requirements are met.</li></ul>

                                        <h3>ARTICLE V: CLAIMS PROCESS</h3>
                                        <ul class="terms-list"><li>Report incident immediately upon receipt.</li><li>Provide photo/video proof upon arrival.</li><li>Submit claim details to DMAC team for verification.</li></ul>

                                        <h3>ARTICLE VI: IMPORTANT CONDITIONS</h3>
                                        <ul class="terms-list"><li>Birds must be checked immediately upon arrival.</li><li>Failure to report timely may affect claim validity.</li><li>Insurance applies only to shipments handled under DMAC protocols.</li></ul>

                                        <h3>DMAC SHIPPING PREMIUM INSURANCE (Optional Upgrade)</h3>
                                        <ul class="terms-list">
                                            <li>Optional Premium Insurance is available for clients declaring higher value birds.</li>
                                            <li>Premium cost is 2% of declared value per head.</li>
                                            <li>In the event of casualty, compensation may be 75% of declared value per head.</li>
                                            <li>Premium Insurance must be declared before shipment and cannot be applied retroactively.</li>
                                        </ul>
                                    </div>

                                    <div class="check-row">
                                        <input type="checkbox" name="insurance_accepted" id="insurance_accepted" value="1" onchange="togglePreviewButton()" required>
                                        <label for="insurance_accepted">I have read and agree to the Insurance Policy.</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-buttons">
                            <button type="button" class="btn-secondary-outline" onclick="changeStep(4, 3)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="button" id="previewBtn" class="btn-success-submit btn-disabled" onclick="showPreview()" disabled>
                                <i class="fa-solid fa-eye"></i> Preview Booking
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <div class="preview-modal" id="previewModal" aria-hidden="true">
        <div class="preview-box">
            <div class="section-header">
                <div>
                    <h2>Preview Booking</h2>
                    <small>Please review all details before confirming.</small>
                </div>
                <button type="button" class="btn-secondary-outline mini" onclick="closePreview()"><i class="fa-solid fa-xmark"></i> Close</button>
            </div>
            <div class="preview-grid" id="previewContent"></div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary-outline" onclick="closePreview()">Edit Details</button>
                <button type="button" class="btn-success-submit" onclick="confirmBooking()"><i class="fa-solid fa-circle-check"></i> Confirm Booking</button>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/all.js"></script>
</body>
</html>
