        </main>
        
        <!-- App Footer -->
        <footer class="bg-white border-t border-slate-200 px-6 py-4 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between">
            <p>&copy; <?= date('Y') ?> <strong><?= APP_NAME ?></strong>. All rights reserved.</p>
            <p class="mt-1 sm:mt-0 text-slate-400">Clinical Management & Financial ERP System (PHP & MySQL)</p>
        </footer>
    </div>
</div>

<!-- Transparent Calculation Breakdown Modal -->
<div id="calculationAuditModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-2xl w-full overflow-hidden animate-fade">
        <div class="bg-slate-900 text-white p-5 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center space-x-2">
                <i data-lucide="calculator" class="w-5 h-5 text-white"></i>
                <h3 id="calcModalTitle" class="font-bold text-base tracking-wide">Transparent Calculation Inspector</h3>
            </div>
            <button onclick="closeCalculationModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="p-6 max-h-[75vh] overflow-y-auto">
            <!-- Formula Summary Card -->
            <div class="bg-slate-100 border border-slate-300 rounded-xl p-4 mb-5">
                <span class="text-xs uppercase font-bold text-slate-700 tracking-wider">Applied Computation Formula</span>
                <p id="calcModalFormula" class="text-sm font-semibold text-slate-900 mt-1 font-mono"></p>
            </div>

            <!-- Summary KPI metrics -->
            <div class="grid grid-cols-3 gap-3 mb-5 text-center">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[11px] text-slate-500 uppercase font-bold">Gross Income</span>
                    <p id="calcGrossIncome" class="text-base font-bold text-slate-800 mt-0.5">₱0.00</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[11px] text-slate-500 uppercase font-bold">Total Expenses</span>
                    <p id="calcTotalExpenses" class="text-base font-bold text-rose-600 mt-0.5">₱0.00</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[11px] text-slate-500 uppercase font-bold">Computed Net</span>
                    <p id="calcComputedNet" class="text-base font-bold text-emerald-600 mt-0.5">₱0.00</p>
                </div>
            </div>

            <!-- Transaction Audit List -->
            <div class="space-y-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Itemized Payments Collected</h4>
                    <div id="calcIncomeItems" class="space-y-1.5 text-xs text-slate-600 max-h-40 overflow-y-auto">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Itemized Operating Expenses</h4>
                    <div id="calcExpenseItems" class="space-y-1.5 text-xs text-slate-600 max-h-40 overflow-y-auto">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
            <button onclick="closeCalculationModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                Close Inspector
            </button>
        </div>
    </div>
</div>

<!-- Treatment Commission Calculation Formula Modal -->
<div id="treatmentFormulaModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden animate-fade">
        <div class="bg-slate-900 text-white p-5 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center space-x-2">
                <i data-lucide="percent" class="w-5 h-5 text-white"></i>
                <h3 class="font-bold text-base tracking-wide">Commission Computation Details</h3>
            </div>
            <button onclick="closeTreatmentFormulaModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="p-6 text-sm text-slate-700 space-y-4">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <span class="text-xs text-slate-500 font-bold uppercase">Procedure</span>
                <p id="tModTreatmentName" class="font-semibold text-slate-800"></p>
                <p id="tModDentistName" class="text-xs text-slate-600 mt-0.5"></p>
            </div>

            <div class="space-y-2 border-y border-slate-200 py-3 font-mono text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">1. Actual Treatment Price:</span>
                    <span id="tModActualPrice" class="font-bold text-slate-800">₱0.00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">2. Commission Split Rate:</span>
                    <span id="tModCommissionRate" class="font-bold text-teal-600">0%</span>
                </div>
                <div class="flex justify-between text-emerald-700 bg-emerald-50 p-2 rounded">
                    <span>Dentist Share (Price × Rate%):</span>
                    <span id="tModDentistShare" class="font-bold">₱0.00</span>
                </div>
                <div class="flex justify-between text-slate-800 bg-slate-100 p-2 rounded">
                    <span>Clinic Share (Price - Dentist Share):</span>
                    <span id="tModClinicShare" class="font-bold">₱0.00</span>
                </div>
            </div>

            <p class="text-xs text-slate-500 italic">
                * All computations are automatically calculated upon procedure entry and locked for accounting audit transparency.
            </p>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
            <button onclick="closeTreatmentFormulaModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
<script>
    // Initialize Lucide Icons
    lucide.createIcons();
</script>
</body>
</html>

