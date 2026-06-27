/* Braids by Portia — Home page: service card modals */

const SERVICES = window.HOME_SERVICES || {};

const modal       = document.getElementById('svc-modal');
const modalHero   = document.getElementById('modal-hero');
const modalTitle  = document.getElementById('modal-title');
const modalDesc   = document.getElementById('modal-desc');
const modalPricing= document.getElementById('modal-pricing');
const modalBtn    = document.getElementById('modal-book-btn');
const modalClose  = document.getElementById('modal-close');
const modalBack   = document.getElementById('modal-backdrop');

document.querySelectorAll('.sh-card[data-cat]').forEach(card => {
  card.addEventListener('click', () => openModal(card.dataset.cat));
  card.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(card.dataset.cat); }
  });
});

function openModal(catKey) {
  const cat = SERVICES[catKey];
  if (!cat) return;

  modalHero.className = `svc-modal-hero sh-img-${catKey}`;
  modalTitle.textContent = cat.name;
  modalDesc.textContent  = cat.desc;
  modalPricing.innerHTML = buildPricing(cat);
  modalBtn.href = `/booking.php?cat=${encodeURIComponent(catKey)}`;

  modal.classList.add('open');
  document.body.style.overflow = 'hidden';
  modalClose.focus();
}

function buildPricing(cat) {
  if (cat.type === 'list') {
    const rows = cat.services.map(s => {
      const h = Math.floor(s.duration / 60), m = s.duration % 60;
      const dur = h > 0 ? `${h}h${m > 0 ? ' ' + m + 'm' : ''}` : `${m}m`;
      return `<div class="st-row"><span>${s.name}</span><span>${dur}</span><span class="st-price">$${s.price}</span></div>`;
    }).join('');
    return `<p class="modal-pricing-label">Pricing</p>
      <div class="simple-table">
        <div class="st-row st-head"><span>Style</span><span>Duration</span><span>Price</span></div>
        ${rows}
      </div>`;
  }

  if (cat.type === 'matrix') {
    const headers = cat.lengths.map(l => `<th>${l}</th>`).join('');
    const rows    = cat.sizes.map(sz => {
      const cells = cat.lengths.map(l => `<td>$${cat.prices[sz][l]}</td>`).join('');
      return `<tr><td class="size-label">${sz}</td>${cells}</tr>`;
    }).join('');
    let extra = '';
    if (cat.extras && cat.extras.length) {
      const erows = cat.extras.map(ex => {
        const h = Math.floor(ex.duration / 60), m = ex.duration % 60;
        const dur = `${h}h${m > 0 ? ' ' + m + 'm' : ''}`;
        return `<div class="st-row"><span>${ex.name}</span><span>${dur}</span><span class="st-price">$${ex.price}</span></div>`;
      }).join('');
      extra = `<div class="simple-table" style="margin-top:0.75rem">${erows}</div>`;
    }
    return `<p class="modal-pricing-label">Pricing (Size × Length)</p>
      <div class="matrix-wrap">
        <table class="matrix-table">
          <thead><tr><th>Size</th>${headers}</tr></thead>
          <tbody>${rows}</tbody>
        </table>
      </div>${extra}`;
  }

  return '';
}

function closeModal() {
  modal.classList.remove('open');
  document.body.style.overflow = '';
}

modalClose.addEventListener('click', closeModal);
modalBack.addEventListener('click', closeModal);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
