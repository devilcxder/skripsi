@extends('layouts.app', [
'namePage' => 'Dashboard',
'class' => 'login-page sidebar-mini ',
'activePage' => 'home',
'backgroundImage' => asset('now') . "/img/bg14.jpg",
])

@section('content')
<div class="panel-header panel-header-lg">
  <canvas id="realtime"></canvas>
</div>
<div class="content">
  <div class="row">
    <div class="col-lg-4">
      <div class="card card-chart">
        <div class="card-header">
          <h5 class="card-category">Data Training</h5>
          <h4 class="card-title">Upload Data</h4>
          <!-- <div class="dropdown">
            <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
              <i class="now-ui-icons loader_gear"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
              <a class="dropdown-item text-danger" href="#">Remove Data</a>
            </div>
          </div> -->
        </div>
        <div class="card-body">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <div class="m-4 text-center">
            <form action="{{ route('upload.data.training') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="file" class="form-control-file" name="train">
              <button class="btn btn-fill btn-primary">Upload</button>
            </form>
          </div>
        </div>
        <div class="card-footer">
          <div class="stats">
            <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="card card-chart">
        <div class="card-header">
          <h5 class="card-category">Model</h5>
          <h4 class="card-title">Buat Model</h4>
        </div>
        <div class="card-body">
          @if (session('model'))
          <div class="alert alert-success">
            {{ session('model') }}
          </div>
          @endif
          <form action="{{ route('create.model') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="model">Nama Model</label>
              <input type="text" id="model" value="" placeholder="" name="model" class="form-control">
            </div>
            <div class="form-group">
              <label for="slider">Example Range input</label>
              <div class="row">
                <div class="col-sm-10">
                  <input type="range" min="0" max="100" name="split" class="form-control-range" id="slider">
                </div>
                <label class="slider">0%</label>
              </div>
            </div>
            <div class="text-center">
              <button class="btn btn-fill btn-primary">Buat Model</button>
            </div>
          </form>
        </div>
        <div class="card-footer">
          <div class="stats">
            <i class="now-ui-icons arrows-1_refresh-69"></i> Just Updated
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="card card-chart">
        <div class="card-header">
          <h5 class="card-category">Prediksi Emosi</h5>
          <h4 class="card-title">Emosi</h4>
        </div>
        <div class="card-body">
          @if (session('prediction'))
          <div class="alert alert-success">
            {{ session('prediction') }}
          </div>
          @endif
          <form action="{{ route('predict') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="tweet">Contoh Tweet</label>
              <input type="text" id="tweet" value="" placeholder="" name="tweet" class="form-control">
            </div>
            <div class="form-group">
              <label for="model">Model</label>
              <select class="form-control" name="model">
                @foreach($models as $model):
                <option value="{{ $model->model }}">{{ $model->model }}</option>
                @endforeach
              </select>
            </div>
            <div class="text-center">
              <button class="btn btn-fill btn-primary">Prediksi</button>
            </div>
          </form>
        </div>
        <div class="card-footer">
          <div class="stats">
            <i class="now-ui-icons ui-2_time-alarm"></i> Last 7 days
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card  card-tasks">
        <div class="card-header ">
          <h5 class="card-category">Backend development</h5>
          <h4 class="card-title">Tasks</h4>
        </div>
        <div class="card-body ">
          <div class="table-full-width table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <td>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" checked>
                        <span class="form-check-sign"></span>
                      </label>
                    </div>
                  </td>
                  <td class="text-left">Sign contract for "What are conference organizers afraid of?"</td>
                  <td class="td-actions text-right">
                    <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                      <i class="now-ui-icons ui-2_settings-90"></i>
                    </button>
                    <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                      <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox">
                        <span class="form-check-sign"></span>
                      </label>
                    </div>
                  </td>
                  <td class="text-left">Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                  <td class="td-actions text-right">
                    <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                      <i class="now-ui-icons ui-2_settings-90"></i>
                    </button>
                    <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                      <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" checked>
                        <span class="form-check-sign"></span>
                      </label>
                    </div>
                  </td>
                  <td class="text-left">Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
                  </td>
                  <td class="td-actions text-right">
                    <button type="button" rel="tooltip" title="" class="btn btn-info btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Edit Task">
                      <i class="now-ui-icons ui-2_settings-90"></i>
                    </button>
                    <button type="button" rel="tooltip" title="" class="btn btn-danger btn-round btn-icon btn-icon-mini btn-neutral" data-original-title="Remove">
                      <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer ">
          <hr>
          <div class="stats">
            <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-category">All Persons List</h5>
          <h4 class="card-title"> Employees Stats</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead class=" text-primary">
                <th>
                  Name
                </th>
                <th>
                  Country
                </th>
                <th>
                  City
                </th>
                <th class="text-right">
                  Salary
                </th>
              </thead>
              <tbody>
                <tr>
                  <td>
                    Dakota Rice
                  </td>
                  <td>
                    Niger
                  </td>
                  <td>
                    Oud-Turnhout
                  </td>
                  <td class="text-right">
                    $36,738
                  </td>
                </tr>
                <tr>
                  <td>
                    Minerva Hooper
                  </td>
                  <td>
                    Curaçao
                  </td>
                  <td>
                    Sinaai-Waas
                  </td>
                  <td class="text-right">
                    $23,789
                  </td>
                </tr>
                <tr>
                  <td>
                    Sage Rodriguez
                  </td>
                  <td>
                    Netherlands
                  </td>
                  <td>
                    Baileux
                  </td>
                  <td class="text-right">
                    $56,142
                  </td>
                </tr>
                <tr>
                  <td>
                    Doris Greene
                  </td>
                  <td>
                    Malawi
                  </td>
                  <td>
                    Feldkirchen in Kärnten
                  </td>
                  <td class="text-right">
                    $63,542
                  </td>
                </tr>
                <tr>
                  <td>
                    Mason Porter
                  </td>
                  <td>
                    Chile
                  </td>
                  <td>
                    Gloucester
                  </td>
                  <td class="text-right">
                    $78,615
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
  $(document).ready(function() {
    //Slider
    $("#slider").on("input", function() {
      $(".slider").html($(this).val() + '%');
    });

    // Javascript method's body can be found in assets/js/demos.js    
    var ctx = document.getElementById('realtime').getContext("2d");
    var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
    gradientStroke.addColorStop(0, '#80b6f4');
    gradientStroke.addColorStop(1, "#FFFFFF");

    var gradientFill = ctx.createLinearGradient(0, 200, 0, 50);
    gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
    gradientFill.addColorStop(1, "rgba(255, 255, 255, 0.24)");

    var chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["2013", "2014"],
        datasets: [{
            label: "Senang",
            borderColor: "#FFFFFF",
            pointBorderColor: "#FFFFFF",
            pointBackgroundColor: "#1e3d60",
            pointHoverBackgroundColor: "#1e3d60",
            pointHoverBorderColor: "#FFFFFF",
            pointBorderWidth: 1,
            pointHoverRadius: 7,
            pointHoverBorderWidth: 2,
            pointRadius: 5,
            fill: true,
            backgroundColor: gradientFill,
            borderWidth: 2,
            data: [50, 150]
          },
          {
            label: "Sedih",
            borderColor: "#FFFFFF",
            pointBorderColor: "#FFFFFF",
            pointBackgroundColor: "#1e3d60",
            pointHoverBackgroundColor: "#1e3d60",
            pointHoverBorderColor: "#FFFFFF",
            pointBorderWidth: 1,
            pointHoverRadius: 7,
            pointHoverBorderWidth: 2,
            pointRadius: 5,
            fill: true,
            backgroundColor: gradientFill,
            borderWidth: 2,
            data: [100, 150]
          }
        ]
      },
      options: {
        layout: {
          padding: {
            left: 20,
            right: 20,
            top: 0,
            bottom: 0
          }
        },
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: '#fff',
          titleFontColor: '#333',
          bodyFontColor: '#666',
          bodySpacing: 4,
          xPadding: 12,
          mode: "nearest",
          intersect: 0,
          position: "nearest"
        },
        legend: {
          position: "bottom",
          fillStyle: "#FFF",
          display: false
        },
        scales: {
          yAxes: [{
            ticks: {
              fontColor: "rgba(255,255,255,0.4)",
              fontStyle: "bold",
              beginAtZero: true,
              maxTicksLimit: 5,
              padding: 10
            },
            gridLines: {
              drawTicks: true,
              drawBorder: false,
              display: true,
              color: "rgba(255,255,255,0.1)",
              zeroLineColor: "transparent"
            }

          }],
          xAxes: [{
            gridLines: {
              zeroLineColor: "transparent",
              display: false,

            },
            ticks: {
              padding: 10,
              fontColor: "rgba(255,255,255,0.4)",
              fontStyle: "bold",
              autoSkip: true,
              source: 'labels',
              callback: function(tick, index, array) {
                return (index % 3) ? "" : tick;
              }
            }
          }]
        }
      }
    });
    let year = 2015;
    Pusher.logToConsole = true;
    const pusher = new Pusher('d6dc82355927033668cd', { // Replace with 'key' from dashboard
      cluster: 'ap1', // Replace with 'cluster' from dashboard
      forceTLS: true
    });
    const channel = pusher.subscribe('price-btcusd');
    channel.bind('new-price', data => {
      chart.data.labels.push(year++);
      chart.data.datasets[0].data.push(data.value[0]);
      chart.data.datasets[1].data.push(data.value[1]);
      chart.update();
    });
  });
</script>
@endpush