@extends('template.layout2.base')
@section('content')
<style type="text/css">
    .dataTables_filter{
        padding-bottom: 10px!important;
    }
    .table-responsive{
        overflow-x:none!important;
    }
    .chart-note{
        font-size: 15px;
        font-style: italic;
        color: #8094ae;
        margin-top: 10px;
    }
</style>

<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Business Overview</h3>
                <div class="nk-block-des text-soft">
                    <p>Welcome to Tenant Web Portal.</p>
                </div>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">
                    <h5 class="card-title" style="border-bottom: solid 2px #dbdfea;padding-bottom:25px;margin-bottom: 20px;font-size: 1.75rem">
                        Bank Balance
                    </h5>
                    <div id="bankTotalContainer" style="color: #333; font-weight: bold; margin-bottom: 10px;font-size: 1.75rem">
                        Total: -
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div id="bankChart" style="height:500px;"></div>
                        </div>
                    </div>
                    <div class="chart-note">
                        Note : Data is sourced directly from the latest financial statements available in the system. Figures may be updated when newer financial statements are published.
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">
                    <h5 class="card-title" style="border-bottom: solid 2px #dbdfea;padding-bottom:25px;margin-bottom: 20px;font-size: 1.75rem">
                        Aging AR
                    </h5>
                    <!-- TAMBAHKAN BARIS INI: Tempat untuk menampung total -->
                    <div id="arTotalContainer" style="color: #333; font-weight: bold; margin-bottom: 10px;font-size: 1.75rem">
                        Total: -
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div style="height:500px">
                                <div id="arChart" style="height:500px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-note">
                        Note : Data is derived from documents currently available in the system and may be subject to updates, revisions, or document availability.
                    </div>
                    <br>
                </div>
            </div><!-- .card-preview -->
        </div><!-- .row -->
    </div><!-- .nk-block -->
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">
                    <h5 class="card-title" style="border-bottom: solid 2px #dbdfea;padding-bottom:25px;margin-bottom: 20px;font-size: 1.75rem">
                        Aging AP
                    </h5>
                    <!-- TAMBAHKAN BARIS INI: Tempat untuk menampung total -->
                    <div id="apTotalContainer" style="color: #333; font-weight: bold; margin-bottom: 10px;font-size: 1.75rem">
                        Total: -
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div style="height:500px">
                                <div id="apChart" style="height:500px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-note">
                        Note : Data is derived from documents currently available in the system and may be subject to updates, revisions, or document availability.
                    </div>
                    <br>
                </div>
            </div><!-- .card-preview -->
        </div><!-- .row -->
    </div><!-- .nk-block -->
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">

                    <div class="d-flex justify-content-between align-items-center" style="border-bottom: solid 2px #dbdfea; padding-bottom:15px; margin-bottom: 20px;">

                        <div style="width: 150px;">
                            <select id="yearFilterRevenue" class="form-control form-select">
                                </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div id="revenueChart" style="height:400px;"></div>
                            <br>
                            <div id="dataTableContainerRevenue" style="width: 100%; margin-top: -15px; padding-bottom: 10px;"></div>
                        </div>
                    </div>
                    <div class="chart-note">
                        Note : Data is sourced directly from the latest financial statements available in the system. Figures may be updated when newer financial statements are published.
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">

                    <div class="d-flex justify-content-between align-items-center" style="border-bottom: solid 2px #dbdfea; padding-bottom:15px; margin-bottom: 20px;">

                        <div style="width: 150px;">
                            <select id="yearFilterExpense" class="form-control form-select">
                                </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div id="expenseChart" style="height:400px;"></div>
                            <br>
                            <div id="dataTableContainerExpense" style="width: 100%; margin-top: -15px; padding-bottom: 10px;"></div>
                        </div>
                    </div>
                    <div class="chart-note">
                        Note : Data is sourced directly from the latest financial statements available in the system. Figures may be updated when newer financial statements are published.
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">

                    <div class="d-flex justify-content-between align-items-center" style="border-bottom: solid 2px #dbdfea; padding-bottom:15px; margin-bottom: 20px;">

                        <div style="width: 150px;">
                            <select id="yearFilterProfit" class="form-control form-select">
                                </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div id="profitChart" style="height:400px;"></div>
                            <br>
                            <div id="dataTableContainerProfit" style="width: 100%; margin-top: -15px; padding-bottom: 10px;"></div>
                        </div>
                    </div>
                    <div class="chart-note">
                        Note : Data is sourced directly from the latest financial statements available in the system. Figures may be updated when newer financial statements are published.
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br><br>

</div>
<script src="<?php echo e(url('assets/js/Chart.min.js')); ?>" type="text/javascript"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>

<script>

$(document).ready(function () {

    let currentYear = new Date().getFullYear();

    // Tangkap ketiga elemen dropdown
    let yearFilterRevenue = $('#yearFilterRevenue');
    let yearFilterExpense = $('#yearFilterExpense');
    let yearFilterProfit  = $('#yearFilterProfit');

    // 1. Generate opsi tahun untuk KETIGA dropdown
    for (let i = 0; i < 20; i++) {
        let y = currentYear - i;

        // Buat elemen option baru
        let optionHtml = `<option value="${y}">${y}</option>`;

        // Masukkan ke masing-masing dropdown
        yearFilterRevenue.append(optionHtml);
        yearFilterExpense.append(optionHtml);
        yearFilterProfit.append(optionHtml);
    }

    // 2. Event Listener khusus untuk Chart Revenue
    yearFilterRevenue.on('change', function() {
        loadRevenueChart($(this).val());
    });

    // 3. Event Listener khusus untuk Chart Expense
    yearFilterExpense.on('change', function() {
        loadExpenseChart($(this).val());
    });

    // 4. Event Listener khusus untuk Chart Profit
    yearFilterProfit.on('change', function() {
        loadProfitChart($(this).val());
    });

    // 5. Load chart yang tidak butuh filter tahun
    loadApChart();
    loadArChart();
    loadBankBalanceChart();

    // 6. Load ketiga chart pertama kali dengan tahun saat ini
    loadRevenueChart(currentYear);
    loadExpenseChart(currentYear);
    loadProfitChart(currentYear);

});

function formatCurrency(value)
{
    value = Number(value);

    if (value >= 1000000000000) {
        return (value / 1000000000000).toFixed(2) + 'T';
    }

    if (value >= 1000000000) {
        return (value / 1000000000).toFixed(2) + 'B';
    }

    if (value >= 1000000) {
        return (value / 1000000).toFixed(2) + 'M';
    }

    if (value >= 1000) {
        return (value / 1000).toFixed(2) + 'K';
    }

    return value.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function renderArTable(response)
{
    var html = '';

    for(var i = 0; i < response.length; i += 5)
    {
        var rowColor = (Math.floor(i / 5) % 2 === 0)
            ? '#f8f9fc'
            : '#eef2ff';

        html += '<tr>';

        for(var j = 0; j < 5; j++)
        {
            var idx = i + j;

            if(idx < response.length)
            {
                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3;">'
                        + response[idx].debtor_acct +
                        '</td>';

                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3; text-align:right;">'
                        + formatCurrency(response[idx].amount) +
                        '</td>';
            }
            else
            {
                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3;"></td>';
                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3;"></td>';
            }
        }

        html += '</tr>';
    }

    $('#arTable tbody').html(html);
}

function loadArChart()
{
    $.ajax({
        url: "<?php echo e(url('management/ar-aging')); ?>",
        type: "GET",
        dataType: "json",

        success: function(response)
        {
            renderArTable(response);

            var categories = [];
            var amounts = [];
            var totalAmountBn = 0; // Variabel untuk total

            // 1. Ambil total dari baris pertama (index 0) jika data tidak kosong
            if (response.length > 0) {
                // Jangan lupa konversi ke Billion (Bn) sesuai format chart Anda
                totalAmountBn = parseFloat(response[0].total_amount) / 1000000000;
            }

            // 2. Format angka dan tampilkan di kotak merah (elemen HTML)
            var formattedTotal = Highcharts.numberFormat(totalAmountBn, 2);
            $('#arTotalContainer').html('TOTAL: IDR ' + formattedTotal + ' Bn');

            $.each(response, function(i, row) {

                categories.push(row.name);

                // convert ke Billion (Bn)
                amounts.push(
                    parseFloat(row.amount) / 1000000000
                );

            });

            Highcharts.chart('arChart', {

                accessibility: {
                    enabled: false // <--- Tambahkan kode ini untuk menghilangkan warning
                },

                chart: {
                    type: 'bar'
                },

                title: {
                    text: 'AR Aging Profile (IDR Billion)'
                },

                xAxis: {
                    categories: categories,
                    title: {
                        text: null
                    }
                },

                yAxis: {
                    min: 0,
                    title: {
                        text: null
                    },
                    labels: {
                        formatter: function() {
                            return this.value.toFixed(2);
                        }
                    }
                },

                legend: {
                    enabled: false
                },

                credits: {
                    enabled: false
                },

                tooltip: {
                    pointFormatter: function() {
                        return '<b>' + Highcharts.numberFormat(this.y, 2) + '</b>';
                    }
                },

                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return Highcharts.numberFormat(this.y, 2);
                            }
                        }
                    }
                },

                series: [{
                    name: 'AR',
                    color: '#2f6f8f',
                    data: amounts
                }]
            });
        }
    });
}

function loadBankBalanceChart()
{
    $.ajax({
        url: "<?php echo e(url('management/bank-balance')); ?>",
        type: "GET",
        dataType: "json",

        success: function(response)
        {
            var categories = [];
            var amounts = [];
            var totalAmountBn = 0;

            if (response.length > 0) {
                totalAmountBn = parseFloat(response[0].total_amount) / 1000000000;
            }

            $('#bankTotalContainer').html('TOTAL: IDR ' + Highcharts.numberFormat(totalAmountBn, 2) + ' Bn');

            $.each(response, function(i, row) {
                categories.push(row.bank_name);
                amounts.push(parseFloat(row.amount) / 1000000000);
            });

            Highcharts.chart('bankChart', {

                accessibility: {
                    enabled: false
                },

                chart: {
                    type: 'bar'
                },

                title: {
                    text: 'Bank Balance (IDR Billion)'
                },

                xAxis: {
                    categories: categories,
                    title: { text: null }
                },

                yAxis: {
                    // Tanpa min: 0 supaya saldo minus (overdraft) tetap terlihat
                    title: { text: null },
                    plotLines: [{ value: 0, width: 1, color: '#999', zIndex: 3 }],
                    labels: {
                        formatter: function() {
                            return this.value.toFixed(2);
                        }
                    }
                },

                legend: {
                    enabled: false
                },

                credits: {
                    enabled: false
                },

                tooltip: {
                    pointFormatter: function() {
                        return '<b>' + Highcharts.numberFormat(this.y, 2) + '</b>';
                    }
                },

                plotOptions: {
                    bar: {
                        negativeColor: '#C0504D', // saldo minus jadi merah
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return Highcharts.numberFormat(this.y, 2);
                            }
                        }
                    }
                },

                series: [{
                    name: 'Balance',
                    color: '#2f6f8f',
                    data: amounts
                }]
            });
        }
    });
}

function renderApTable(response)
{
    var html = '';

    for(var i = 0; i < response.length; i += 5)
    {
        var rowColor = (Math.floor(i / 5) % 2 === 0)
            ? '#f8f9fc'
            : '#eef2ff';

        html += '<tr>';

        for(var j = 0; j < 5; j++)
        {
            var idx = i + j;

            if(idx < response.length)
            {
                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3;">'
                        + response[idx].creditor_acct +
                        '</td>';

                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3; text-align:right;">'
                        + formatCurrency(response[idx].amount) +
                        '</td>';
            }
            else
            {
                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3;"></td>';
                html += '<td style="background:'+ rowColor +'; border:1px solid #d3d3d3;"></td>';
            }
        }

        html += '</tr>';
    }

    $('#apTable tbody').html(html);
}

function loadApChart()
{
    $.ajax({
        url: "<?php echo e(url('management/ap-aging')); ?>",
        type: "GET",
        dataType: "json",

        success: function(response)
        {
            renderApTable(response);

            var categories = [];
            var amounts = [];
            var totalAmountBn = 0; // Variabel untuk total

            // 1. Ambil total dari baris pertama (index 0) jika data tidak kosong
            if (response.length > 0) {
                // Jangan lupa konversi ke Billion (Bn) sesuai format chart Anda
                totalAmountBn = parseFloat(response[0].total_amount) / 1000000000;
            }

            // 2. Format angka dan tampilkan di kotak merah (elemen HTML)
            var formattedTotal = Highcharts.numberFormat(totalAmountBn, 2);
            $('#apTotalContainer').html('TOTAL: IDR ' + formattedTotal + ' Bn');

            $.each(response, function(i, row) {
                categories.push(row.name);
                amounts.push(parseFloat(row.amount) / 1000000000);
            });

            Highcharts.chart('apChart', {

                accessibility: {
                    enabled: false // <--- Tambahkan kode ini untuk menghilangkan warning
                },

                chart: {
                    type: 'bar'
                },

                title: {
                    text: 'AP Aging Profile (IDR Billion)'
                },

                xAxis: {
                    categories: categories,
                    title: {
                        text: null
                    }
                },

                yAxis: {
                    min: 0,
                    title: {
                        text: null
                    },
                    labels: {
                        formatter: function() {
                            return this.value.toFixed(2);
                        }
                    }
                },

                legend: {
                    enabled: false
                },

                credits: {
                    enabled: false
                },

                tooltip: {
                    pointFormatter: function() {
                        return '<b>' + Highcharts.numberFormat(this.y, 2) + '</b>';
                    }
                },

                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return Highcharts.numberFormat(this.y, 2);
                            }
                        }
                    }
                },

                series: [{
                    name: 'AP',
                    color: '#2f6f8f',
                    data: amounts
                }]
            });
        }
    });
}

function loadRevenueChart(year) {
    $.ajax({
        url: "<?php echo e(url('management/revenue-data')); ?>",
        type: "GET",
        data: { year: year }, //baru tambah
        dataType: "json",
        success: function(response) {
            var categories = [
                'JAN','FEB','MAR','APR','MAY','JUN',
                'JUL','AUG','SEP','OCT','NOV','DEC'
            ];

            var actuals = [];
            var budgets = [];

            // <--- TAMBAHAN: Variabel penampung total
            var totalActual = 0;
            var totalBudget = 0;

            $.each(response, function(i, row){
                var act = parseFloat(row.actual || 0);
                var bud = parseFloat(row.budget || 0);

                actuals.push(act);
                budgets.push(bud);

                // <--- TAMBAHAN: Kalkulasi total 1 tahun
                totalActual += act;
                totalBudget += bud;
            });

            // 1. Inisialisasi Highcharts
            Highcharts.chart('revenueChart', {
                accessibility: {
                    enabled: false // <--- Tambahkan kode ini untuk menghilangkan warning
                },
                chart: {
                    type: 'column',
                    marginLeft: 220, // Disinkronkan dengan lebar kolom pertama tabel
                    marginRight: 15,
                    marginBottom: 20 // Kurangi margin bawah agar menempel ke tabel
                },
                title: {
                    text: 'REVENUE ' + year // <--- Akan berubah otomatis jadi REVENUE 2025, dsb.
                },
                xAxis: {
                    categories: categories,
                    labels: { enabled: false }, // Sembunyikan label X bawaan
                    tickLength: 0,
                    lineWidth: 0
                },
                yAxis: {
                    min: 0,
                    title: { text: null },
                    labels: {
                        formatter: function () {
                            // Format 30,000.00
                            return Highcharts.numberFormat(this.value, 2, '.', ',');
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false // Legend dimatikan karena informasinya ada di tabel
                },
                tooltip: {
                    shared: true,
                    pointFormatter: function() {
                        return '<span style="color:' + this.color + '">●</span> ' +
                            this.series.name + ': <b>' +
                            Highcharts.numberFormat(this.y, 2, '.', ',') +
                            '</b><br/>';
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0,   // Membuat bar saling menempel (tidak ada jarak dalam 1 grup)
                        groupPadding: 0.1, // Jarak antar bulan dibuat lebih sempit
                        borderWidth: 0,
                        dataLabels: { enabled: false }
                    }
                },
                series: [
                    {
                        name: 'TOTAL REVENUE ACTUAL',
                        data: actuals,
                        color: '#4F81BD',
                    },
                    {
                        name: 'TOTAL REVENUE BUDGET',
                        data: budgets,
                        color: '#C0504D',
                    }
                ]
            });

            // 2. Build HTML Data Table Dinamis
            let tableHTML = `<table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-family: 'Segoe UI', sans-serif; font-size: 12px; text-align: center; color: #666;">`;

            // Baris 1: Header Bulan
            tableHTML += `<tr><td style="width: 220px; border: none;"></td>`; // Kosong di pojok kiri (lebar sama dengan marginLeft chart)
            $.each(categories, function(i, cat) {
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${cat}</td>`;
            });
            // <--- TAMBAHAN: Header Kolom TOTAL
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2;">YTD</td>`;
            tableHTML += `</tr>`;

            // Baris 2: Data Actual
            tableHTML += `<tr>
                <td style="border: 1px solid #ccc; text-align: left; padding: 5px 10px;">
                    <span style="color:#4F81BD; margin-right:5px;">■</span> TOTAL REVENUE ACTUAL
                </td>`;
            $.each(actuals, function(i, val) {
                // Menampilkan kosong jika 0, atau format angka "21,428.80"
                let txt = val > 0 ? Highcharts.numberFormat(val, 2, '.', ',') : '';
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${txt}</td>`;
            });
            // <--- TAMBAHAN: Cetak nilai Total Actual
            let txtTotalAct = totalActual > 0 ? Highcharts.numberFormat(totalActual, 2, '.', ',') : '0.00';
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2; color: #4F81BD;">${txtTotalAct}</td>`;
            tableHTML += `</tr>`;

            // Baris 3: Data Budget
            tableHTML += `<tr>
                <td style="border: 1px solid #ccc; text-align: left; padding: 5px 10px;">
                    <span style="color:#C0504D; margin-right:5px;">■</span> TOTAL REVENUE BUDGET
                </td>`;
            $.each(budgets, function(i, val) {
                let txt = val > 0 ? Highcharts.numberFormat(val, 2, '.', ',') : '';
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${txt}</td>`;
            });
            // <--- TAMBAHAN: Cetak nilai Total Budget
            let txtTotalBud = totalBudget > 0 ? Highcharts.numberFormat(totalBudget, 2, '.', ',') : '0.00';
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2; color: #C0504D;">${txtTotalBud}</td>`;
            tableHTML += `</tr></table>`;

            // Render tabel ke dalam container
            $('#dataTableContainerRevenue').html(tableHTML);
        }
    });
}

function loadExpenseChart(year) {
    $.ajax({
        url: "<?php echo e(url('management/expense-data')); ?>",
        type: "GET",
        data: { year: year }, //baru tambah
        dataType: "json",
        success: function(response) {
            var categories = [
                'JAN','FEB','MAR','APR','MAY','JUN',
                'JUL','AUG','SEP','OCT','NOV','DEC'
            ];

            var actuals = [];
            var budgets = [];

            // <--- TAMBAHAN: Variabel penampung total
            var totalActual = 0;
            var totalBudget = 0;

            $.each(response, function(i, row){
                var act = parseFloat(row.actual || 0);
                var bud = parseFloat(row.budget || 0);

                actuals.push(act);
                budgets.push(bud);

                // <--- TAMBAHAN: Kalkulasi total 1 tahun
                totalActual += act;
                totalBudget += bud;
            });

            // 1. Inisialisasi Highcharts
            Highcharts.chart('expenseChart', {
                accessibility: {
                    enabled: false // <--- Tambahkan kode ini untuk menghilangkan warning
                },
                chart: {
                    type: 'column',
                    marginLeft: 220, // Disinkronkan dengan lebar kolom pertama tabel
                    marginRight: 15,
                    marginBottom: 20 // Kurangi margin bawah agar menempel ke tabel
                },
                title: {
                    text: 'EXPENSE ' + year // <--- Akan berubah otomatis jadi REVENUE 2025, dsb.
                },
                xAxis: {
                    categories: categories,
                    labels: { enabled: false }, // Sembunyikan label X bawaan
                    tickLength: 0,
                    lineWidth: 0
                },
                yAxis: {
                    min: 0,
                    title: { text: null },
                    labels: {
                        formatter: function () {
                            // Format 30,000.00
                            return Highcharts.numberFormat(this.value, 2, '.', ',');
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false // Legend dimatikan karena informasinya ada di tabel
                },
                tooltip: {
                    shared: true,
                    pointFormatter: function() {
                        return '<span style="color:' + this.color + '">●</span> ' +
                            this.series.name + ': <b>' +
                            Highcharts.numberFormat(this.y, 2, '.', ',') +
                            '</b><br/>';
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0,   // Membuat bar saling menempel (tidak ada jarak dalam 1 grup)
                        groupPadding: 0.1, // Jarak antar bulan dibuat lebih sempit
                        borderWidth: 0,
                        dataLabels: { enabled: false }
                    }
                },
                series: [
                    {
                        name: 'TOTAL EXPENSE ACTUAL',
                        data: actuals,
                        color: '#4F81BD',
                    },
                    {
                        name: 'TOTAL EXPENSE BUDGET',
                        data: budgets,
                        color: '#C0504D',
                    }
                ]
            });

            // 2. Build HTML Data Table Dinamis
            let tableHTML = `<table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-family: 'Segoe UI', sans-serif; font-size: 12px; text-align: center; color: #666;">`;

            // Baris 1: Header Bulan
            tableHTML += `<tr><td style="width: 220px; border: none;"></td>`; // Kosong di pojok kiri (lebar sama dengan marginLeft chart)
            $.each(categories, function(i, cat) {
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${cat}</td>`;
            });
            // <--- TAMBAHAN: Header Kolom TOTAL
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2;">YTD</td>`;
            tableHTML += `</tr>`;

            // Baris 2: Data Actual
            tableHTML += `<tr>
                <td style="border: 1px solid #ccc; text-align: left; padding: 5px 10px;">
                    <span style="color:#4F81BD; margin-right:5px;">■</span> TOTAL EXPENSE ACTUAL
                </td>`;
            $.each(actuals, function(i, val) {
                // Menampilkan kosong jika 0, atau format angka "21,428.80"
                let txt = val > 0 ? Highcharts.numberFormat(val, 2, '.', ',') : '';
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${txt}</td>`;
            });
            // <--- TAMBAHAN: Cetak nilai Total Actual
            let txtTotalAct = totalActual > 0 ? Highcharts.numberFormat(totalActual, 2, '.', ',') : '0.00';
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2; color: #4F81BD;">${txtTotalAct}</td>`;
            tableHTML += `</tr>`;

            // Baris 3: Data Budget
            tableHTML += `<tr>
                <td style="border: 1px solid #ccc; text-align: left; padding: 5px 10px;">
                    <span style="color:#C0504D; margin-right:5px;">■</span> TOTAL EXPENSE BUDGET
                </td>`;
            $.each(budgets, function(i, val) {
                let txt = val > 0 ? Highcharts.numberFormat(val, 2, '.', ',') : '';
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${txt}</td>`;
            });
            // <--- TAMBAHAN: Cetak nilai Total Budget
            let txtTotalBud = totalBudget > 0 ? Highcharts.numberFormat(totalBudget, 2, '.', ',') : '0.00';
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2; color: #C0504D;">${txtTotalBud}</td>`;
            tableHTML += `</tr></table>`;

            // Render tabel ke dalam container
            $('#dataTableContainerExpense').html(tableHTML);
        }
    });
}
function loadProfitChart(year) {
    $.ajax({
        url: "<?php echo e(url('management/profit-data')); ?>",
        type: "GET",
        data: { year: year },
        dataType: "json",
        success: function(response) {
            var categories = [
                'JAN','FEB','MAR','APR','MAY','JUN',
                'JUL','AUG','SEP','OCT','NOV','DEC'
            ];

            var actuals = [];
            var budgets = [];

            var totalActual = 0;
            var totalBudget = 0;

            $.each(response, function(i, row){
                var act = parseFloat(row.actual || 0);
                var bud = parseFloat(row.budget || 0);

                actuals.push(act);
                budgets.push(bud);

                totalActual += act;
                totalBudget += bud;
            });

            // Format angka; minus diberi warna merah, 0 dikosongkan
            function fmt(val, emptyText) {
                if (val === 0) return emptyText;
                var txt = Highcharts.numberFormat(val, 2, '.', ',');
                return val < 0 ? '<span style="color:#C00000;">' + txt + '</span>' : txt;
            }

            // 1. Inisialisasi Highcharts
            Highcharts.chart('profitChart', {
                accessibility: {
                    enabled: false
                },
                chart: {
                    type: 'column',
                    marginLeft: 220,
                    marginRight: 15,
                    marginBottom: 20
                },
                title: {
                    text: 'Operating Profit / (Loss) ' + year
                },
                xAxis: {
                    categories: categories,
                    labels: { enabled: false },
                    tickLength: 0,
                    lineWidth: 0
                },
                yAxis: {
                    // Tanpa min: 0 supaya bulan rugi (minus) tetap terlihat
                    title: { text: null },
                    plotLines: [{ value: 0, width: 1, color: '#999', zIndex: 3 }],
                    labels: {
                        formatter: function () {
                            return Highcharts.numberFormat(this.value, 2, '.', ',');
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    shared: true,
                    pointFormatter: function() {
                        return '<span style="color:' + this.color + '">●</span> ' +
                            this.series.name + ': <b>' +
                            Highcharts.numberFormat(this.y, 2, '.', ',') +
                            '</b><br/>';
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0,
                        groupPadding: 0.1,
                        borderWidth: 0,
                        dataLabels: { enabled: false }
                    }
                },
                series: [
                    {
                        name: 'TOTAL Operating Profit / (Loss) ACTUAL',
                        data: actuals,
                        color: '#4F81BD',
                    },
                    {
                        name: 'TOTAL Operating Profit / (Loss) BUDGET',
                        data: budgets,
                        color: '#C0504D',
                    }
                ]
            });

            // 2. Build HTML Data Table Dinamis
            let tableHTML = `<table style="width: 100%; table-layout: fixed; border-collapse: collapse; font-family: 'Segoe UI', sans-serif; font-size: 12px; text-align: center; color: #666;">`;

            // Baris 1: Header Bulan
            tableHTML += `<tr><td style="width: 220px; border: none;"></td>`;
            $.each(categories, function(i, cat) {
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${cat}</td>`;
            });
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2;">YTD</td>`;
            tableHTML += `</tr>`;

            // Baris 2: Data Actual
            tableHTML += `<tr>
                <td style="border: 1px solid #ccc; text-align: left; padding: 5px 10px;">
                    <span style="color:#4F81BD; margin-right:5px;">■</span> TOTAL Operating Profit / (Loss) ACTUAL
                </td>`;
            $.each(actuals, function(i, val) {
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${fmt(val, '')}</td>`;
            });
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2; color: #4F81BD;">${fmt(totalActual, '0.00')}</td>`;
            tableHTML += `</tr>`;

            // Baris 3: Data Budget
            tableHTML += `<tr>
                <td style="border: 1px solid #ccc; text-align: left; padding: 5px 10px;">
                    <span style="color:#C0504D; margin-right:5px;">■</span> TOTAL Operating Profit / (Loss) BUDGET
                </td>`;
            $.each(budgets, function(i, val) {
                tableHTML += `<td style="border: 1px solid #ccc; padding: 5px;">${fmt(val, '')}</td>`;
            });
            tableHTML += `<td style="border: 1px solid #ccc; padding: 5px; font-weight: bold; background-color: #f2f2f2; color: #C0504D;">${fmt(totalBudget, '0.00')}</td>`;
            tableHTML += `</tr></table>`;

            $('#dataTableContainerProfit').html(tableHTML);
        }
    });
}

</script>
@endsection
