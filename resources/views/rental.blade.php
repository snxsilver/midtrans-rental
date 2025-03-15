<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Rental PS</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
</head>
<body>
  <div class="background">
    <div class="card">
      <div class="card-body">
        <div class="card-title mb-3 title-2" style="display: none">
          <img src="{{ url('assets/image/icon-check.svg')}}" alt="">
          <div class="">
            Pembayaran Berhasil
          </div>
        </div>
        <div class="card-title mb-3 title-1" style="display: none">
          <img src="{{ url('assets/image/icon-waiting.svg')}}" alt="">
          <div class="">
            Menunggu Pembayaran
          </div>
        </div>
        <div class="card-title mb-3 title-3" style="display: none">
          <img src="{{ url('assets/image/icon-cross.svg')}}" alt="">
          <div class="">
            Pembayaran Gagal
          </div>
        </div>
        <div class="card-title mb-3 title-0" style="display: block">Jasa Rental PS</div>
        <form action="{{url('/checkout')}}" method="post">
          @csrf
          <div class="row">
            <div class="col-6">
              <div class="row">
                <div class="col-6">
                  <div class="mb-3">
                    <label class="form-label">Nama Depan</label>
                    <input type="text" name="first_name" class="form-control" value="{{old('first_name')}}">
                    @error('first_name')<small class="form-warning">*{{$message}}</small>@enderror
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label class="form-label">Nama Belakang</label>
                    <input type="text" name="last_name" class="form-control" value="{{old('last_name')}}">
                    @error('last_name')<small class="form-warning">*{{$message}}</small>@enderror
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="mb-3">
                  <div class="input-group">
                    <input type="text" class="form-control" name="email"  value="{{old('email')}}">
                    <span class="input-group-text">@gmail.com</span>
                  </div>
                  @error('email')<small class="form-warning">*{{$message}}</small>@enderror
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">No HP</label>
                <div class="mb-3">
                  <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input type="number" step="1" class="form-control" name="phone"  value="{{old('phone')}}">
                  </div>
                  @error('phone')<small class="form-warning">*{{$message}}</small>@enderror
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input name="date" type="text" readonly class="form-control datepicker" style="background-color: white" id="date"  value="{{old('date')}}">
                @error('date')<small class="form-warning">*{{$message}}</small>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Layanan</label>
                <select name="service_id" class="form-control" id="service">
                  @foreach($services as $item)
                  <option value="{{$item->id}}" bs-data={{$item->price}} @if(old('service_id') == $item->id) selected @endif>{{$item->name}}: Rp {{number_format($item->price)}} per sesi</option>
                  @endforeach
                </select>
                @error('service_id')<small class="form-warning">*{{$message}}</small>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label">Biaya</label>
                <div class="mb-3">
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input name="total" type="number" readonly class="form-control" style="background-color: white" id="total"  value="{{old('total')}}">
                  </div>
                  @error('total')<small class="form-warning">*{{$message}}</small>@enderror
                  <small class="mt-1 form-warning weekend">*terdapat tambahan Rp 50.000 jika pemesanan dilakukan pada hari Sabtu atau Minggu.</small>
                </div>
              </div>
            </div>

          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" id="pay-button">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
  <script>
      $('.datepicker').datepicker({
          autoclose: true,
          forceParse: false,
          orientation: 'auto bottom',
          format: 'dd-M-yyyy',
          setDate: new Date()
      })

      function isWeekend(dateString) {
        var date = new Date(dateString)
        var day = date.getDay()
        return day === 0 || day === 6
      }

      function getCost(){
        var selectedDate = document.querySelector("#date").value
          var service = document.querySelector('#service')
          var selectedOption = service.options[service.selectedIndex]
          var price = selectedOption.getAttribute('bs-data')

          var total = document.querySelector('#total')
          var warning = document.querySelector('.form-warning.weekend')
          if (isWeekend(selectedDate)){
            total.value = parseInt(price) + 50000
            warning.style.display = 'block'
            return
          }
          total.value = price
          warning.style.display = 'none'
      }

      function resetTitle(){
        document.querySelector('.title-0').style.display = "none"
        document.querySelector('.title-1').style.display = "none"
        document.querySelector('.title-2').style.display = "none"
        document.querySelector('.title-3').style.display = "none"
      }

    function getTitle(val){
      resetTitle()
      switch(val){
        case 0:
          document.querySelector('.title-0').style.display = "block"
          break;
        case 1:
          document.querySelector('.title-1').style.display = "block"
          break;
        case 2:
          document.querySelector('.title-2').style.display = "block"
          break;
        case 3:
          document.querySelector('.title-3').style.display = "block"
          break;
        default:
        document.querySelector('.title-0').style.display = "block"
      }
    }

    $("#try-button").on('click', function(){
      getTitle(2)
    })

    $("#date").on('change',function(e){
      getCost()
    })

    $("#service").on('change',function(e){
      getCost()
    })
  </script>
  @if(session('snapToken'))
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
  <script>
    let snapToken = "{{ session('snapToken') }}";
    console.log("Snap Token:", snapToken);

    window.snap.pay(snapToken, {
      onSuccess: function(result) {
          getTitle(2)
      },
      onPending: function(result) {
          getTitle(1)
      },
      onError: function(result) {
        getTitle(3)
      }
    });

    function checkStatus(val = true) {    
      if (val == false){
        return
      }
      $.ajax({
        type: 'GET',
        url: "{{ url('/check/'.session('orderId')) }}",
        dataType: 'json',
        success: function(response){
            if(response.data.transaction_status == 'pending'){
              getTitle(1)
            } else if (response.data.transaction_status == 'settlement'){
              val = false
              getTitle(2)
            } else if (response.data.transaction_status == 'cancel'){
              val = false
              getTitle(3)
            }
        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
      });
      setTimeout(() => {
          checkStatus(val);
      }, 3000);
    }

    checkStatus();
</script>
  @endif
</body>
</html>