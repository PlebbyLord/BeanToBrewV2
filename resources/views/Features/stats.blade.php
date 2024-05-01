@extends('layouts.analytics')

@section('content')
    <div class="p-2 bg-teal-300">
        <div class="p-2 flex items-center gap-1">
            <a href="{{ route('features.sales') }}" class="text-12">
                <svg class="w-12 h-10 text-red-500 hover:text-red-700 hover:cursor-pointer dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 16-4-4 4-4m-6 8-4-4 4-4"/>
                </svg>
            </a>
              
            <h1 class="text-xl font-bold">Sale's Statistic</h1>
        </div>
        <div class="rounded-md grid grid-cols-1 md:grid-cols-2 gap-2">
            {{-- pie graph --}}
            <div class="bg-slate-100 p-2 rounded-md">
                <div class="w-full py-2 flex flex-col gap-2 item-center">
                    <h1 class="font-bold text-xl uppercase text-slate-700 mx-auto mb-2">Pie Chart Statistics</h1>
                    <h1 class="text-md font-bold tracking-wide capitalize text-blue-900 mx-auto -mt-4">Future 5(y) sales</h1>
                    <div class="rounded-md text-center p-2">
                        
                        <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">

                            <!-- Line Chart -->
                            <div class="py-6" id="pie-chart"></div>

                            <div
                                class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- line graph --}}
            <div class="bg-slate-100 p-2 rounded-md">
                <div class="w-full py-2 flex flex-col gap-2 item-center">
                    <h1 class="font-bold text-xl uppercase text-slate-700 mx-auto mb-2">Line Chart Statistics</h1>
                    <p class="text-md font-bold tracking-wide capitalize text-blue-900 mx-auto -mt-4">Future 5(y) sales</p>
                    <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                        <div class="flex justify-between mb-5">
    
                            <div class="flex gap-1 flex-wrap " id="header-title">
    
                            </div>
    
                        </div>
                        <div id="line-chart"></div>
    
                    </div>
                </div>
                

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function() {
            let data = @json($salesInPast5YEars);
            let tempArr = [];
            let s = [];
            let sales = [];
            let lineHeader = '';
            if (data !== null) {
                data.forEach(d => {
                    tempArr.push(`${d.year}`)
                    s.push(d.sales_kg)
                    lineHeader += `
                            <div class="shadow-md p-1">
                                <h5
                                    class="inline-flex items-center text-blue-700 dark:text-gray-400 leading-none font-normal mb-2">
                                    <span class="text-[12px]">${d.coffee}</span>
                                    
                                </h5>
                                <p class="text-blue-900 dark:text-white text-[14px] leading-none font-bold">${d.sales_kg} <span class="text-sm">(kg)</span></p>
                                <span class="text-[12px] text-gray-500 dark:text-gray-400 leading-none font-normal mb-2">${d.year}</span>
                            </div>
                `
                });

                $('#header-title').html(lineHeader)
                // console.log(s)
                const options = {
                    chart: {
                        height: '100%',
                        width: "100%",
                        type: "line",
                        fontFamily: "Inter, sans-serif",
                        dropShadow: {
                            enabled: true,
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    tooltip: {
                        enabled: true,
                        x: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    stroke: {
                        width: 6,
                    },
                    grid: {
                        show: true,
                        strokeDashArray: 4,
                        padding: {
                            left: 5,
                            right: 5,
                            top: -26
                        },
                    },
                    series: [{
                            name: "Sales in future year (10%) growth rate",
                            data: s,
                            color: "#1A56DB",
                        },
                        // {
                        //     name: "CPC",
                        //     data: [6456, 6356, 6526, 6332, 6418, 6500],
                        //     color: "#7E3AF2",
                        // },
                    ],
                    legend: {
                        show: true
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    xaxis: {
                        categories: tempArr, //render the year
                        labels: {
                            show: true,
                            style: {
                                fontFamily: "Inter, sans-serif",
                                cssClass: 'text-xs flex flex-col border font-normal fill-gray-500 dark:fill-gray-400',
                            }
                        },
                        axisBorder: {
                            show: true,
                        },
                        axisTicks: {
                            show: false,
                        },
                    },
                    yaxis: {
                        show: false,
                    },
                }

                if (document.getElementById("line-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("line-chart"), options);
                    chart.render();
                }

                // pie chart

                const getChartOptions = () => {
                    return {
                        series: s,
                        colors: ["#1C64F2", "#16BDCA", "#9061F9", "#FF5733", "#33FF57", "#5733FF"],
                        chart: {
                            height: 420,
                            width: "100%",
                            type: "pie",
                        },
                        stroke: {
                            colors: ["white"],
                            lineCap: "",
                        },
                        plotOptions: {
                            pie: {
                                labels: {
                                    show: true,
                                },
                                size: "100%",
                                dataLabels: {
                                    offset: -25
                                }
                            },
                        },
                        labels: tempArr,
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontFamily: "Inter, sans-serif",
                            },
                        },
                        legend: {
                            position: "bottom",
                            fontFamily: "Inter, sans-serif",
                        },
                        yaxis: {
                            labels: {
                                formatter: function(value) {
                                    return value + "kg"
                                },
                            },
                        },
                        xaxis: {
                            labels: {
                                formatter: function(value) {
                                    return value + "%"
                                },
                            },
                            axisTicks: {
                                show: false,
                            },
                            axisBorder: {
                                show: false,
                            },
                        },
                    }
                }

                if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
                    const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
                    chart.render();
                }

            }
        })
    </script>
@endsection
