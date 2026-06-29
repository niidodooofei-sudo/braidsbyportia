<?php
require_once 'includes/auth.php';
cms_check_auth();
$active_page = 'settings';
$page_title  = 'Settings';

$s = cms_settings();

ob_start(); ?>

<div id="st-alert" style="display:none" class="alert alert-ok"></div>
<div id="st-err"   style="display:none" class="alert alert-err"></div>

<div class="cms-grid-2" style="gap:1.25rem">

  <!-- General -->
  <div class="cms-card">
    <div class="cms-card-head"><h2>General</h2></div>
    <div class="cms-card-body">
      <div class="cms-field" style="margin-bottom:1rem">
        <label class="cms-label">Salon Notification Email</label>
        <input type="email" id="salon-email" class="cms-input" value="<?= htmlspecialchars($s['salon_email'] ?? 'portia@braidsbyportia.com') ?>" placeholder="portia@braidsbyportia.com">
        <span class="text-faint text-sm" style="margin-top:.3rem">Booking confirmations are sent here.</span>
      </div>
      <div class="cms-field">
        <label class="cms-label">Deposit Amount ($)</label>
        <input type="number" id="deposit-amount" class="cms-input cms-input-sm" min="1" max="500" value="<?= (int)($s['deposit_amount'] ?? 50) ?>">
        <span class="text-faint text-sm" style="margin-top:.3rem">Amount charged at booking to hold the appointment.</span>
      </div>
    </div>
  </div>

  <!-- Change Password -->
  <div class="cms-card">
    <div class="cms-card-head"><h2>Change Password</h2></div>
    <div class="cms-card-body">
      <div class="cms-field" style="margin-bottom:1rem">
        <label class="cms-label">New Password</label>
        <input type="password" id="new-password" class="cms-input" placeholder="Leave blank to keep current" autocomplete="new-password">
      </div>
      <div class="cms-field">
        <label class="cms-label">Confirm New Password</label>
        <input type="password" id="confirm-password" class="cms-input" placeholder="Re-enter new password" autocomplete="new-password">
      </div>
      <p class="text-faint" style="margin-top:.6rem;font-size:.72rem">
        Current default: <strong>braids2024</strong> — change this before going live.
      </p>
    </div>
  </div>

</div>

<!-- Stripe Keys -->
<div class="cms-card" style="margin-top:1.25rem">
  <div class="cms-card-head">
    <h2>Stripe Payment Keys</h2>
    <span class="text-faint text-sm">Used to charge deposits. Get these from your Stripe dashboard.</span>
  </div>
  <div class="cms-card-body">
    <div class="alert alert-info" style="margin-bottom:1.25rem">
      Use <strong>test keys</strong> (pk_test_… / sk_test_…) while testing. Switch to <strong>live keys</strong> (pk_live_… / sk_live_…) before going live. Never share your secret key.
    </div>
    <div class="cms-form-row cols-2">
      <div class="cms-field">
        <label class="cms-label">Publishable Key</label>
        <input type="text" id="stripe-pk" class="cms-input" value="<?= htmlspecialchars($s['stripe_publishable_key'] ?? '') ?>" placeholder="pk_live_…">
      </div>
      <div class="cms-field">
        <label class="cms-label">Secret Key</label>
        <input type="password" id="stripe-sk" class="cms-input" value="<?= htmlspecialchars($s['stripe_secret_key'] ?? '') ?>" placeholder="sk_live_…" autocomplete="off">
      </div>
    </div>
    <div class="cms-field" style="margin-top:1rem">
      <label class="cms-label">Webhook Secret</label>
      <input type="password" id="stripe-wh" class="cms-input cms-input-md" value="<?= htmlspecialchars($s['stripe_webhook_secret'] ?? '') ?>" placeholder="whsec_…" autocomplete="off">
      <span class="text-faint text-sm" style="margin-top:.3rem">
        Set up a webhook in Stripe Dashboard → Developers → Webhooks pointing to
        <code style="background:var(--surface2);padding:.1rem .35rem;border-radius:3px;font-size:.72rem">https://yourdomain.com/test/api/webhook.php</code>
        and paste the signing secret here.
      </span>
    </div>
  </div>
</div>

<div style="margin-top:1.25rem;text-align:right">
  <button class="btn btn-gold" id="save-settings-btn">Save Settings</button>
</div>

<?php
$content = ob_get_clean();
$extra_js = <<<'JS'
<script>
document.getElementById('save-settings-btn').addEventListener('click', async () => {
  const btn = document.getElementById('save-settings-btn');
  btn.disabled = true; btn.textContent = 'Saving…';
  document.getElementById('st-alert').style.display = 'none';
  document.getElementById('st-err').style.display   = 'none';

  const newPass = document.getElementById('new-password').value;
  const confPass = document.getElementById('confirm-password').value;

  if (newPass && newPass !== confPass) {
    document.getElementById('st-err').textContent = 'Passwords do not match.';
    document.getElementById('st-err').style.display = 'block';
    btn.disabled = false; btn.textContent = 'Save Settings';
    return;
  }
  if (newPass && newPass.length < 8) {
    document.getElementById('st-err').textContent = 'Password must be at least 8 characters.';
    document.getElementById('st-err').style.display = 'block';
    btn.disabled = false; btn.textContent = 'Save Settings';
    return;
  }

  const payload = {
    salon_email:            document.getElementById('salon-email').value.trim(),
    deposit_amount:         parseInt(document.getElementById('deposit-amount').value) || 50,
    stripe_publishable_key: document.getElementById('stripe-pk').value.trim(),
    stripe_secret_key:      document.getElementById('stripe-sk').value.trim(),
    stripe_webhook_secret:  document.getElementById('stripe-wh').value.trim(),
  };
  if (newPass) payload.new_password = newPass;

  const res = await fetch('api/save-settings.php', {
    method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)
  });
  const data = await res.json();
  btn.disabled = false; btn.textContent = 'Save Settings';
  if (data.success) {
    document.getElementById('st-alert').textContent = data.message || 'Settings saved.';
    document.getElementById('st-alert').style.display = 'block';
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-password').value = '';
    setTimeout(() => document.getElementById('st-alert').style.display='none', 4000);
  } else {
    document.getElementById('st-err').textContent = data.error || 'Save failed.';
    document.getElementById('st-err').style.display = 'block';
  }
});
</script>
JS;
require 'includes/layout.php';
