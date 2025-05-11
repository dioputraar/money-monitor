@extends('layout.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>

       <div class="row">
        <div class="card shadow col bg-primary">
            <div class="card-body text-white">
                
                    <span>Total Income</span>
                    <h5 id="totalIncome">0</h5>
            </div>
        </div>
        <div class="card shadow col bg-danger">
            <div class="card-body text-white">
                
                    <span>Total Expense</span>
                    <h5 class="text-bold" id="totalExpense">0</h5 class="text-bold">
            </div>
        </div>
        <div class="card shadow col bg-success">
            <div class="card-body text-white">
                
                    <span>Net Balance</span>
                    <h5 class="text-bold" id="netBalance">0</h5>
            </div>
        </div>
       </div>

       <div class="row">
        <div class="card col-8">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Income and Expense</h6>
            </div>
            <div class="card-body">
                <div id="container1"></div>
            </div>
        </div>
           <div class="card col-4">
               <div class="card-header py-3">
                   <h6 class="m-0 font-weight-bold text-primary">Top Expense Categories</h6>
               </div>
               <div class="card-body">
                   <div id="container"></div>
               </div>
           </div>
       </div>
        

    @endsection

    @section('scripts')
        <script>

            $(document).ready(function() {get();});

            function get(id) {
                $.ajax({
                    url: '/dashboard/get/',
                    type: 'get',
                    success: function(response) {
                        console.log(response);
                        
                        let netBalance = parseFloat(response.data.income) - parseFloat(response.data.expense);
                        $('#totalIncome').text("IDR " + parseFloat(response.data.income).toLocaleString('en-US'));
                        $('#totalExpense').text("IDR " + parseFloat(response.data.expense).toLocaleString('en-US'));
                        $('#netBalance').text("IDR " + netBalance.toLocaleString('en-US'));

                        // top expense categories
                        const data = response.data.top_expense.map(function(item) {
                                return {
                                    name: item.category_name,
                                    y: parseFloat(item.total_expense),
                                };
                            })
                if (Array.isArray(response.data.top_expense) && response.data.top_expense.length > 0) {
                    Highcharts.chart('container', {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: 'Top Expenses'
                        },
                        plotOptions: {
                            pie: {
                                innerSize: '50%', // Set inner size for donut
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.name}: {point.percentage:.1f} %'
                                }
                            }
                        },
                        series: [{
                            name: 'Expenses',
                            colorByPoint: true,
                            data: data
                        }]
                    });
                } else {
                    console.error('Top expense data is invalid or empty.');
                }
// monthly chart
const categories = response.data.monthly.map(item => {
    const date = new Date(item.month);
    return date.toLocaleString('en-US', { month: 'short', year: 'numeric' });
});
const incomeDataMonthly = response.data.monthly.map(item => parseFloat(item.total_income));
const expenseDataMonthly = response.data.monthly.map(item => parseFloat(item.total_expense));

Highcharts.chart('container1', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Income VS Expense Monthly'
    },
    xAxis: {
        categories: categories
    },
    yAxis: {
        title: {
            text: 'Total (IDR)'
        }
    },
    series: [{
        name: 'Income',
        data: incomeDataMonthly
    }, {
        name: 'Expense',
        data: expenseDataMonthly
    }]
});
                    }
                    
                });
            }
        </script>
    @endsection
