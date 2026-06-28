<?php
$page_title = 'Book an Appointment | Braids by Portia';
$page_desc  = 'Book your braiding appointment online — choose your style, pick a time, and secure your spot with a $50 deposit.';
require 'config/stripe.php';
$services    = require 'config/services.php';
$preset_cat  = htmlspecialchars($_GET['cat'] ?? '');
$extra_head  = '<link rel="stylesheet" href="' . APP_BASE . '/styles/booking.css">';
$extra_js    = '<script src="https://js.stripe.com/v3/"></script>'
             . '<script>window.STRIPE_PK = ' . json_encode(STRIPE_PUBLISHABLE_KEY) . ';</script>'
             . '<script>window.SERVICES_DATA = ' . json_encode($services) . ';</script>'
             . '<script>window.PRESET_CAT = ' . json_encode($preset_cat) . ';</script>'
             . '<script src="' . APP_BASE . '/js/booking.js" defer></script>';
require 'includes/header.php';
?>

<main class="booking-main">
  <div class="page-banner page-banner-sm">
    <div class="container">
      <span class="eyebrow">Secure Online Booking</span>
      <h1>Book Your Appointment</h1>
    </div>
  </div>

  <div class="container booking-layout">

    <!-- Main column -->
    <div class="booking-col-main">

      <!-- Step indicator -->
      <div class="bk-stepper" id="booking-progress">
        <div class="bk-step-pill active" data-step="1">
          <span class="bsp-num">1</span>
          <span class="bsp-label">Style</span>
        </div>
        <div class="bsp-line"></div>
        <div class="bk-step-pill" data-step="2">
          <span class="bsp-num">2</span>
          <span class="bsp-label">Date &amp; Time</span>
        </div>
        <div class="bsp-line"></div>
        <div class="bk-step-pill" data-step="3">
          <span class="bsp-num">3</span>
          <span class="bsp-label">Your Info</span>
        </div>
        <div class="bsp-line"></div>
        <div class="bk-step-pill" data-step="4">
          <span class="bsp-num">4</span>
          <span class="bsp-label">Payment</span>
        </div>
      </div>

      <!-- ── STEP 1: Service ── -->
      <div class="bk-panel" id="bk-step-1">
        <h2 class="bk-panel-title">Choose Your Style</h2>
        <p class="bk-panel-sub">Tap a style to see options and pricing.</p>

        <div class="bk-svc-grid" id="bk-cat-grid">
          <?php foreach ($services as $key => $cat):
            if ($cat['type'] === 'matrix') {
              $ps = []; foreach ($cat['prices'] as $r) foreach ($r as $p) $ps[] = $p;
              $from = min($ps);
            } else {
              $from = min(array_column($cat['services'], 'price'));
            }
          ?>
          <button class="bk-svc-card" data-cat="<?= htmlspecialchars($key) ?>">
            <div class="bk-svc-img sh-img-<?= htmlspecialchars($key) ?>"></div>
            <div class="bk-svc-info">
              <span class="bk-svc-name"><?= htmlspecialchars($cat['name']) ?></span>
              <span class="bk-svc-from">from $<?= $from ?></span>
            </div>
            <div class="bk-svc-check" aria-hidden="true">✓</div>
          </button>
          <?php endforeach; ?>
        </div>

        <div class="bk-options-panel hidden" id="bk-sub-options"></div>

        <div class="bk-panel-nav">
          <div></div>
          <button class="btn-gold" id="step1-next" disabled>Continue to Date &amp; Time →</button>
        </div>
      </div>

      <!-- ── STEP 2: Date & Time ── -->
      <div class="bk-panel hidden" id="bk-step-2">
        <h2 class="bk-panel-title">Pick a Date &amp; Time</h2>
        <p class="bk-panel-sub">We're available Tuesday through Saturday.</p>

        <div class="bk-datetime-grid">
          <div class="bk-cal-col">
            <div class="cal-header">
              <button class="cal-nav-btn" id="cal-prev">&#8249;</button>
              <span class="cal-month-label" id="cal-month-label"></span>
              <button class="cal-nav-btn" id="cal-next">&#8250;</button>
            </div>
            <div class="cal-weekdays">
              <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
            </div>
            <div class="cal-grid" id="cal-grid"></div>
          </div>

          <div class="bk-slots-col">
            <div class="bk-slots-placeholder" id="slots-placeholder">
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <p>Select a date to see available times</p>
            </div>
            <div class="bk-slots-content hidden" id="bk-slots-content">
              <p class="slots-heading" id="ts-heading"></p>
              <div class="ts-loading hidden" id="ts-loading">Loading times…</div>
              <div class="time-slots-grid" id="time-slots"></div>
              <p class="ts-none hidden" id="ts-none">No times available for this day — try another date.</p>
            </div>
          </div>
        </div>

        <div class="bk-panel-nav">
          <button class="btn-outline" id="step2-back">← Back</button>
          <button class="btn-gold" id="step2-next" disabled>Continue to Your Info →</button>
        </div>
      </div>

      <!-- ── STEP 3: Your Info ── -->
      <div class="bk-panel hidden" id="bk-step-3">
        <h2 class="bk-panel-title">Your Details</h2>
        <p class="bk-panel-sub">We'll use these to send your confirmation.</p>

        <form class="bk-form" id="bk-form" novalidate>
          <div class="bk-2col">
            <div class="bk-field">
              <label for="f-name">Full Name <span class="req">*</span></label>
              <input type="text" id="f-name" name="name" required autocomplete="name" placeholder="Jane Doe">
            </div>
            <div class="bk-field">
              <label for="f-email">Email <span class="req">*</span></label>
              <input type="email" id="f-email" name="email" required autocomplete="email" placeholder="jane@example.com">
            </div>
          </div>
          <div class="bk-field">
            <label for="f-phone">Phone Number <span class="req">*</span></label>
            <input type="tel" id="f-phone" name="phone" required autocomplete="tel" placeholder="(555) 000-0000">
          </div>
          <div class="bk-field">
            <label for="f-notes">Notes <span class="bk-opt">(optional)</span></label>
            <textarea id="f-notes" name="notes" rows="3" placeholder="Style references, hair length, anything we should know…"></textarea>
          </div>
        </form>

        <div class="bk-panel-nav">
          <button class="btn-outline" id="step3-back">← Back</button>
          <button class="btn-gold" id="step3-next">Continue to Payment →</button>
        </div>
      </div>

      <!-- ── STEP 4: Payment ── -->
      <div class="bk-panel hidden" id="bk-step-4">
        <h2 class="bk-panel-title">Secure Your Spot</h2>
        <p class="bk-panel-sub">A $50 deposit is charged now to hold your appointment. The remaining balance is due on the day.</p>

        <div class="stripe-wrap">
          <div id="payment-element"></div>
          <div class="stripe-error hidden" id="stripe-error"></div>
        </div>

        <div class="bk-panel-nav">
          <button class="btn-outline" id="step4-back">← Back</button>
          <button class="btn-gold" id="pay-btn">Pay $50 &amp; Confirm Booking</button>
        </div>
        <div class="pay-processing hidden" id="pay-processing">
          <span class="spinner"></span> Processing your payment…
        </div>
      </div>

    </div><!-- /booking-col-main -->

    <!-- Sidebar -->
    <div class="booking-col-sidebar">
      <div class="bk-summary">
        <h4 class="bk-summary-title">Your Booking</h4>

        <div class="bk-sum-item">
          <span class="bk-sum-label">Style</span>
          <span class="bk-sum-val bk-sum-empty" id="sum-service-val">Not yet selected</span>
        </div>
        <div class="bk-sum-item">
          <span class="bk-sum-label">Date &amp; Time</span>
          <span class="bk-sum-val bk-sum-empty" id="sum-datetime-val">Not yet selected</span>
        </div>
        <div class="bk-sum-item">
          <span class="bk-sum-label">Name</span>
          <span class="bk-sum-val bk-sum-empty" id="sum-customer-val">Not yet entered</span>
        </div>

        <div class="bk-sum-divider"></div>

        <div class="bk-sum-total">
          <span>Deposit due today</span>
          <strong>$50</strong>
        </div>
        <p class="bk-sum-note">Remaining balance paid at your appointment</p>

        <div class="bk-sum-info">
          <div class="bk-sum-info-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Tue – Sat appointments
          </div>
          <div class="bk-sum-info-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Cancel 24 hrs in advance, no charge
          </div>
          <div class="bk-sum-info-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Payments secured by Stripe
          </div>
        </div>
      </div>
    </div>

  </div><!-- /booking-layout -->
</main>

<?php require 'includes/footer.php'; ?>
