/**
 * SDC Dental Care System
 * Frontend Interactions & Transparent Calculation Audits
 */

// Open Transparent Calculation Modal (Daily Net Income, etc.)
function openCalculationModal(type = 'daily_net', date = '', branchId = '') {
    const modal = document.getElementById('calculationAuditModal');
    if (!modal) return;

    modal.classList.remove('hidden');

    const formulaEl = document.getElementById('calcModalFormula');
    const grossEl = document.getElementById('calcGrossIncome');
    const expEl = document.getElementById('calcTotalExpenses');
    const netEl = document.getElementById('calcComputedNet');
    const incomeListEl = document.getElementById('calcIncomeItems');
    const expListEl = document.getElementById('calcExpenseItems');

    formulaEl.innerText = 'Loading calculation audit...';
    incomeListEl.innerHTML = '<div class="text-slate-400">Loading payments...</div>';
    expListEl.innerHTML = '<div class="text-slate-400">Loading expenses...</div>';

    fetch(`index.php?page=api_calc_audit&type=${type}&date=${date}&branch_id=${branchId}`)
        .then(res => res.json())
        .then(data => {
            formulaEl.innerText = data.formula || 'No formula details';
            grossEl.innerText = '₱' + Number(data.total_income || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
            expEl.innerText = '₱' + Number(data.total_expense || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
            netEl.innerText = '₱' + Number(data.net_income || 0).toLocaleString(undefined, {minimumFractionDigits: 2});

            // Populate Income items
            if (data.income_items && data.income_items.length > 0) {
                incomeListEl.innerHTML = data.income_items.map(item => `
                    <div class="flex justify-between items-center p-2 bg-slate-50 rounded border border-slate-100">
                        <div>
                            <span class="font-medium text-slate-800">${item.receipt_number}</span> - 
                            <span class="text-slate-600">${item.first_name} ${item.last_name}</span> 
                            <span class="text-[10px] px-1.5 py-0.5 bg-teal-100 text-teal-800 rounded font-semibold ml-1">${item.payment_method}</span>
                        </div>
                        <span class="font-bold text-slate-800">+₱${Number(item.amount_paid).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                    </div>
                `).join('');
            } else {
                incomeListEl.innerHTML = '<div class="text-slate-400 italic">No payments collected for this date.</div>';
            }

            // Populate Expense items
            if (data.expense_items && data.expense_items.length > 0) {
                expListEl.innerHTML = data.expense_items.map(item => `
                    <div class="flex justify-between items-center p-2 bg-slate-50 rounded border border-slate-100">
                        <div>
                            <span class="font-medium text-slate-800">${item.title}</span> 
                            <span class="text-[10px] px-1.5 py-0.5 bg-rose-100 text-rose-800 rounded font-semibold ml-1">${item.category}</span>
                            <span class="text-[10px] text-slate-500">(${item.payment_method})</span>
                        </div>
                        <span class="font-bold text-rose-600">-₱${Number(item.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                    </div>
                `).join('');
            } else {
                expListEl.innerHTML = '<div class="text-slate-400 italic">No expenses recorded for this date.</div>';
            }
        })
        .catch(err => {
            formulaEl.innerText = 'Error loading calculation: ' + err;
        });
}

function closeCalculationModal() {
    const modal = document.getElementById('calculationAuditModal');
    if (modal) modal.classList.add('hidden');
}

// Open Treatment Formula Inspector Modal
function openTreatmentFormulaModal(treatmentName, dentistName, actualPrice, commissionRate, dentistShare, clinicShare) {
    const modal = document.getElementById('treatmentFormulaModal');
    if (!modal) return;

    document.getElementById('tModTreatmentName').innerText = treatmentName;
    document.getElementById('tModDentistName').innerText = 'Attending Dentist: ' + dentistName;
    document.getElementById('tModActualPrice').innerText = '₱' + Number(actualPrice).toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('tModCommissionRate').innerText = Number(commissionRate) + '%';
    document.getElementById('tModDentistShare').innerText = '₱' + Number(dentistShare).toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('tModClinicShare').innerText = '₱' + Number(clinicShare).toLocaleString(undefined, {minimumFractionDigits: 2});

    modal.classList.remove('hidden');
}

function closeTreatmentFormulaModal() {
    const modal = document.getElementById('treatmentFormulaModal');
    if (modal) modal.classList.add('hidden');
}

// Close modals when clicking backdrop
window.addEventListener('click', function(e) {
    const calcModal = document.getElementById('calculationAuditModal');
    const treatModal = document.getElementById('treatmentFormulaModal');
    if (e.target === calcModal) closeCalculationModal();
    if (e.target === treatModal) closeTreatmentFormulaModal();
});

