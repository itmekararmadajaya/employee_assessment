<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <div class="text-center">
      <span class="text-4xl font-bold">Monitoring {{$assessment->name}}</span>
    </div>

    <div style="width: 50%;" class="card">
        <canvas id="chartCriteria"></canvas>

    </div>

    <div>
        <x-filament::button color="gray" wire:click="back">
            Kembali
        </x-filament::button>
    </div>

    <script>
        var chartCriteria = @json($chartCriteria);
        
        const htmlChartCriteria = document.getElementById('chartCriteria');
      
        new Chart(htmlChartCriteria, {
          plugins: [ChartDataLabels],
          type: 'line',
          data: {
            labels: chartCriteria['label'],
            datasets: [{
              label: 'Jumlah per Kriteria',
              data: chartCriteria['value'],
              borderWidth: 1,
              fill: false,
              tension: 0.4,
              datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: 'black',
                        font: {
                            size: 12
                        },
                    }
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
              },
              x: {
                    beginAtZero: true,
                    ticks: {
                        min: 1
                    }
                },
            },
            onClick: (event, elements) => {
                if(elements.length > 0){
                    alert("Hello World");
                }
            }
          }
        });
      </script>
</x-filament-panels::page>
