/* ============================================================
   Braids by Portia — Booking Flow
   Steps: 1 Style → 2 Date/Time → 3 Your Info → 4 Payment
   ============================================================ */

const SERVICES = window.SERVICES_DATA || {};
const DEPOSIT  = 50;

const state = {
  categoryKey: null,
  service:     null,   // { id, name, price, duration }
  date:        null,   // 'YYYY-MM-DD'
  time:        null,   // 'HH:MM'
  customer:    {},
  paymentIntentClientSecret: null,
};

// ── Utilities ─────────────────────────────────────────────
const $ = id => document.getElementById(id);

const fmt_duration = m => {
  const h = Math.floor(m / 60), r = m % 60;
  return h > 0 ? `${h}h${r > 0 ? ' ' + r + 'm' : ''}` : `${r}m`;
};
const fmt_date = d =>
  new Date(d + 'T12:00:00').toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
const fmt_time = t => {
  const [h, m] = t.split(':');
  const d = new Date(); d.setHours(+h, +m);
  return d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
};

// ── Sidebar live summary ───────────────────────────────────
function updateSidebar() {
  const sv = $('sum-service-val');
  const dv = $('sum-datetime-val');
  const cv = $('sum-customer-val');

  if (state.service) {
    sv.textContent = `${state.service.name} — $${state.service.price}`;
    sv.classList.remove('bk-sum-empty');
  } else {
    sv.textContent = 'Not yet selected';
    sv.classList.add('bk-sum-empty');
  }

  if (state.date && state.time) {
    dv.textContent = `${fmt_date(state.date)} at ${fmt_time(state.time)}`;
    dv.classList.remove('bk-sum-empty');
  } else {
    dv.textContent = 'Not yet selected';
    dv.classList.add('bk-sum-empty');
  }

  if (state.customer?.name) {
    cv.textContent = state.customer.name;
    cv.classList.remove('bk-sum-empty');
  } else {
    cv.textContent = 'Not yet entered';
    cv.classList.add('bk-sum-empty');
  }
}

// ── Step management ───────────────────────────────────────
let currentStep = 1;

function goToStep(n) {
  document.querySelectorAll('.bk-panel').forEach((el, i) => {
    el.classList.toggle('hidden', i + 1 !== n);
  });
  document.querySelectorAll('.bk-step-pill').forEach(el => {
    const s = +el.dataset.step;
    el.classList.toggle('active', s === n);
    el.classList.toggle('done',   s < n);
  });
  currentStep = n;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── Step 1: Service selection ─────────────────────────────
const catGrid    = $('bk-cat-grid');
const subOptions = $('bk-sub-options');
const step1Next  = $('step1-next');

catGrid.querySelectorAll('.bk-svc-card').forEach(btn => {
  btn.addEventListener('click', () => {
    catGrid.querySelectorAll('.bk-svc-card').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    state.categoryKey = btn.dataset.cat;
    state.service     = null;
    step1Next.disabled = true;
    updateSidebar();
    renderSubOptions(state.categoryKey);
    subOptions.classList.remove('hidden');
    subOptions.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });
});

function renderSubOptions(catKey) {
  const cat = SERVICES[catKey];
  if (!cat) return;
  subOptions.innerHTML = `<p class="bk-options-label">${cat.name} — choose your option</p>`;

  if (cat.type === 'list') {
    const wrap = document.createElement('div');
    wrap.className = 'sub-list';

    const allItems = [...(cat.services || []), ...(cat.extras || [])];
    allItems.forEach(svc => {
      const btn = document.createElement('button');
      btn.className = 'sub-svc-btn';
      btn.innerHTML = `<span class="ssb-name">${svc.name}</span><span class="ssb-price">$${svc.price}</span><span class="ssb-dur">${fmt_duration(svc.duration)}</span>`;
      btn.addEventListener('click', () => {
        wrap.querySelectorAll('.sub-svc-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectService({ id: svc.id, name: svc.name, price: svc.price, duration: svc.duration });
      });
      wrap.appendChild(btn);
    });
    subOptions.appendChild(wrap);

  } else if (cat.type === 'matrix') {
    const div = document.createElement('div');
    div.className = 'matrix-selectors';
    div.innerHTML = `
      <div class="ms-group">
        <label>Braid Size</label>
        <div class="ms-btns" id="ms-sizes">
          ${cat.sizes.map(s => `<button class="ms-btn" data-val="${s}">${s}</button>`).join('')}
        </div>
      </div>
      <div class="ms-group">
        <label>Length</label>
        <div class="ms-btns" id="ms-lengths">
          ${cat.lengths.map(l => `<button class="ms-btn" data-val="${l}">${l}</button>`).join('')}
        </div>
      </div>`;
    subOptions.appendChild(div);

    let selSize = null, selLength = null;

    const updateMatrix = () => {
      if (!selSize || !selLength) return;
      const price    = cat.prices[selSize][selLength];
      const duration = cat.durations[selSize][selLength];
      const name     = `${cat.name} – ${selSize} – ${selLength}`;
      selectService({
        id: `${state.categoryKey}-${selSize}-${selLength}`.toLowerCase().replace(/\s+/g, '-'),
        name, price, duration,
      });
    };

    div.querySelectorAll('#ms-sizes .ms-btn').forEach(b => {
      b.addEventListener('click', () => {
        div.querySelectorAll('#ms-sizes .ms-btn').forEach(x => x.classList.remove('selected'));
        b.classList.add('selected'); selSize = b.dataset.val; updateMatrix();
      });
    });
    div.querySelectorAll('#ms-lengths .ms-btn').forEach(b => {
      b.addEventListener('click', () => {
        div.querySelectorAll('#ms-lengths .ms-btn').forEach(x => x.classList.remove('selected'));
        b.classList.add('selected'); selLength = b.dataset.val; updateMatrix();
      });
    });
  }
}

function selectService(svc) {
  state.service = svc;
  updateSidebar();
  step1Next.disabled = false;
}

step1Next.addEventListener('click', () => goToStep(2));

// Pre-select from URL param
if (window.PRESET_CAT && SERVICES[window.PRESET_CAT]) {
  const btn = catGrid.querySelector(`[data-cat="${window.PRESET_CAT}"]`);
  if (btn) btn.click();
}

// ── Step 2: Calendar ──────────────────────────────────────
let calYear, calMonth;

function initCalendar() {
  const today = new Date();
  calYear  = today.getFullYear();
  calMonth = today.getMonth();
  renderCalendar();
}

function renderCalendar() {
  const avail  = [2, 3, 4, 5, 6]; // Tue–Sat
  const today  = new Date(); today.setHours(0, 0, 0, 0);
  const maxDay = new Date(today); maxDay.setDate(maxDay.getDate() + 56);

  $('cal-month-label').textContent = new Date(calYear, calMonth, 1)
    .toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

  const firstDay = new Date(calYear, calMonth, 1).getDay();
  const daysIn   = new Date(calYear, calMonth + 1, 0).getDate();

  let html = '';
  for (let i = 0; i < firstDay; i++) html += '<div class="cal-cell empty"></div>';
  for (let d = 1; d <= daysIn; d++) {
    const date    = new Date(calYear, calMonth, d);
    const dateStr = `${calYear}-${String(calMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    const disabled = date < today || date > maxDay || !avail.includes(date.getDay());
    const selected = dateStr === state.date;
    html += `<button class="cal-cell${disabled ? ' disabled' : ''}${selected ? ' selected' : ''}"
               data-date="${dateStr}"${disabled ? ' disabled' : ''}>${d}</button>`;
  }

  $('cal-grid').innerHTML = html;
  $('cal-grid').querySelectorAll('.cal-cell:not(.disabled)').forEach(btn => {
    btn.addEventListener('click', () => {
      $('cal-grid').querySelectorAll('.cal-cell').forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
      state.date = btn.dataset.date;
      state.time = null;
      $('step2-next').disabled = true;
      updateSidebar();
      loadTimeSlots(state.date);
    });
  });
}

$('cal-prev').addEventListener('click', () => {
  if (calMonth-- === 0) { calMonth = 11; calYear--; }
  renderCalendar();
});
$('cal-next').addEventListener('click', () => {
  if (++calMonth > 11) { calMonth = 0; calYear++; }
  renderCalendar();
});

async function loadTimeSlots(date) {
  $('slots-placeholder').classList.add('hidden');
  $('bk-slots-content').classList.remove('hidden');

  const loading = $('ts-loading');
  const grid    = $('time-slots');
  const none    = $('ts-none');
  $('ts-heading').textContent = `Available times — ${fmt_date(date)}`;
  loading.classList.remove('hidden');
  grid.innerHTML = '';
  none.classList.add('hidden');

  const duration = state.service?.duration ?? 60;
  const res  = await fetch(`api/availability.php?date=${date}&duration=${duration}`);
  const data = await res.json();
  loading.classList.add('hidden');

  if (!data.slots || data.slots.length === 0) {
    none.classList.remove('hidden');
    return;
  }

  data.slots.forEach(slot => {
    const btn = document.createElement('button');
    btn.className = 'ts-btn';
    btn.textContent = fmt_time(slot);
    btn.dataset.time = slot;
    btn.addEventListener('click', () => {
      grid.querySelectorAll('.ts-btn').forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
      state.time = slot;
      updateSidebar();
      $('step2-next').disabled = false;
    });
    grid.appendChild(btn);
  });
}

$('step2-back').addEventListener('click', () => goToStep(1));
$('step2-next').addEventListener('click', () => goToStep(3));

// ── Step 3: Customer details ──────────────────────────────
$('step3-back').addEventListener('click', () => goToStep(2));
$('step3-next').addEventListener('click', () => {
  const name  = $('f-name').value.trim();
  const email = $('f-email').value.trim();
  const phone = $('f-phone').value.trim();

  let valid = true;
  [['f-name', name], ['f-email', email], ['f-phone', phone]].forEach(([id, val]) => {
    const el = $(id);
    if (!val) { el.classList.add('invalid'); valid = false; }
    else el.classList.remove('invalid');
  });
  if (!valid) return;

  state.customer = { name, email, phone, notes: $('f-notes').value.trim() };
  updateSidebar();
  goToStep(4);
  initPayment();
});

document.querySelectorAll('#bk-form input').forEach(inp => {
  inp.addEventListener('input', () => inp.classList.remove('invalid'));
});

// ── Step 4: Stripe Payment ────────────────────────────────
let stripe, elements, paymentElement;

async function initPayment() {
  if (paymentElement) return;
  $('pay-btn').disabled = true;
  stripe = Stripe(window.STRIPE_PK);

  const res  = await fetch('api/create-payment-intent.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ service_name: state.service?.name }),
  });
  const data = await res.json();

  if (data.error) { showStripeError(data.error); return; }

  state.paymentIntentClientSecret = data.client_secret;
  elements = stripe.elements({ clientSecret: data.client_secret, appearance: stripeAppearance() });
  paymentElement = elements.create('payment');
  paymentElement.mount('#payment-element');
  paymentElement.on('ready', () => { $('pay-btn').disabled = false; });
}

function stripeAppearance() {
  return {
    theme: 'stripe',
    variables: {
      colorPrimary:    '#c49b30',
      colorBackground: '#ffffff',
      colorText:       '#1c1208',
      colorDanger:     '#c0392b',
      fontFamily:      'Poppins, sans-serif',
      borderRadius:    '8px',
    },
    rules: {
      '.Input': { border: '1.5px solid rgba(196,155,48,0.30)', padding: '10px 14px', backgroundColor: '#f5f0e8' },
      '.Input:focus': { border: '1.5px solid #c49b30', boxShadow: '0 0 0 3px rgba(196,155,48,0.10)' },
      '.Label': { color: 'rgba(28,18,8,0.55)', fontSize: '0.72rem', fontWeight: '600', letterSpacing: '0.08em', textTransform: 'uppercase' },
    },
  };
}

$('step4-back').addEventListener('click', () => goToStep(3));

$('pay-btn').addEventListener('click', async () => {
  $('pay-btn').disabled = true;
  $('pay-processing').classList.remove('hidden');
  hideStripeError();

  const { error, paymentIntent } = await stripe.confirmPayment({
    elements,
    redirect: 'if_required',
    confirmParams: {
      payment_method_data: {
        billing_details: {
          name:  state.customer.name,
          email: state.customer.email,
          phone: state.customer.phone,
        },
      },
    },
  });

  if (error) {
    showStripeError(error.message);
    $('pay-btn').disabled = false;
    $('pay-processing').classList.add('hidden');
    return;
  }

  if (paymentIntent.status === 'succeeded') {
    await confirmBooking(paymentIntent.id);
  }
});

async function confirmBooking(piId) {
  const res = await fetch('api/confirm-booking.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      payment_intent_id: piId,
      booking: {
        service_name: state.service.name,
        service_id:   state.service.id,
        price:        state.service.price,
        duration:     state.service.duration,
        date:         state.date,
        time:         state.time,
        customer:     state.customer,
      },
    }),
  });
  const data = await res.json();
  if (data.success) {
    window.location.href = `booking-success.php?ref=${data.ref}`;
  } else {
    showStripeError('Booking could not be saved. Please contact us with your payment reference.');
    $('pay-btn').disabled = false;
    $('pay-processing').classList.add('hidden');
  }
}

function showStripeError(msg) {
  const el = $('stripe-error');
  el.textContent = msg;
  el.classList.remove('hidden');
}
function hideStripeError() {
  $('stripe-error').classList.add('hidden');
}

// ── Boot ──────────────────────────────────────────────────
initCalendar();
