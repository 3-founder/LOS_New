{{--  Modal perhitungan aspek keuangan  --}}
{{-- <!-- Modal --> --}}
@php
  $lev1 = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)->where('level', 1)->get();
@endphp
<div class="modal fade" id="perhitunganModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Perhitungan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <!-- form -->
              <form id="form-perhitungan" method="" action="">
                {{--  <div class="row">  --}}
                  <!-- content -->
                    <!-- pilih bulan -->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-4">
                          <label for="inputHarta" class="font-weight-semibold">Pilih Periode :</label>
                          <select name="" style="width: 100%; height: 40px" class="select-date" id="">
                                <option selected>--Pilih Bulan--</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                      </div>
                    </div>
                    <!-- end pilih bulan -->
                    <!-- form bagian level 1 -->
                    @foreach ($lev1 as $item)
                      <div class="head">
                          <h4 class="mb-4 font-weight-bold" style="letter-spacing: -1px">
                              {{$item->field}}
                          </h4>
                      </div>
                      <!-- form bagian level 2 -->
                      @php
                        $lev2 = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)
                                                                    ->where('level', 2)
                                                                    ->where('parent_id', $item->id)
                                                                    ->get();
                      @endphp
                      <div class="row">
                        @foreach ($lev2 as $item2)
                          @php
                            $lev3 = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)
                                                                        ->where('level', 3)
                                                                        ->where('parent_id', $item2->id)
                                                                        ->get();
                          @endphp
                          <div class="col-md-6">
                            <div class="card mb-4">
                                <h5 class="card-header">{{$item2->field}}</h5>
                                <div class="card-body">
                                  <!-- form bagian level 3 -->
                                  @foreach ($lev3 as $item3)
                                    <div class="form-group">
                                        <label for="inp_{{$item3->id}}" class="font-weight-semibold">{{$item3->field}}</label>
                                        <div class="input-group">
                                          <input type="text" class="form-control inp_{{$item3->id}}" name="inp_{{$item3->id}}"
                                            id="inp_{{$item3->id}}" data-formula="{{$item3->formula}}" data-detail="{{$item3->have_detail}}"
                                            @if ($item3->readonly) readonly @endif />
                                          @if ($item3->have_detail)
                                            @php
                                              $lev4 = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)
                                                                                          ->where('level', 4)
                                                                                          ->where('parent_id', $item3->id)
                                                                                          ->get();
                                            @endphp
                                            <div class="input-group-prepend">
                                                <a class="btn btn-danger" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                                                    aria-controls="collapseExample">
                                                    Tampilkan
                                                    <i class="bi bi-caret-down"></i>
                                                </a>
                                            </div>
                                            <div class="collapse mt-4" id="collapseExample">
                                                <div class="table-responsive">
                                                    <table class="table" id="table_item" style="box-sizing: border-box">
                                                        <thead>
                                                            <tr>
                                                                @foreach ($lev4 as $item4)
                                                                  <th scope="col">{{$item4->field}}</th>
                                                                @endforeach
                                                                <th scope="col">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                @foreach ($lev4 as $item4)
                                                                  <td id="detail-item">
                                                                      <input class="form-control" type="text" name="inp_{{$item4->id}}[]"
                                                                        id="inp_{{$item4->id}}[]" data-formula="{{$item4->formula}}" onkeyup="calcForm()"/>
                                                                  </td>
                                                                @endforeach
                                                                <td>
                                                                    <button class="btn-add-2 btn btn-success" type="button">
                                                                        +
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                          @endif
                                          @if ($item3->add_on)
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">{{$item3->add_on}}</span>
                                            </div>
                                          @endif
                                        </div>
                                    </div>
                                  @endforeach
                                  <!-- end form bagian level 3 -->
                                </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                      <!-- end form bagian level 2 -->
                    @endforeach
                    <!-- end form bagian level 1 -->
                    <!-- form bagian level 3 no parent -->
                    @php
                      $lev3NoParent = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)
                                                                          ->where('level', 3)
                                                                          ->whereNull('parent_id')
                                                                          ->get();
                    @endphp
                    <div class="form-row">
                        @foreach ($lev3NoParent as $item3NoParent)
                          <div class="col-6">
                              <div class="form-group form-field">
                                  <label for="inp_{{$item3NoParent->id}}" class="font-weight-semibold">{{$item3NoParent->field}}</label>
                                  <div class="input-group">
                                    <input type="text" class="form-control inp_{{$item3NoParent->id}}" name="inp_{{$item3NoParent->id}}"
                                        id="inp_{{$item3NoParent->id}}" data-formula="{{$item3NoParent->formula}}" @if ($item3NoParent->readonly) readonly @endif />
                                      @if ($item3NoParent->add_on)
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">{{$item3NoParent->add_on}}</span>
                                        </div>
                                      @endif
                                  </div>
                              </div>
                          </div>
                        @endforeach
                    </div>
                    <!-- end form bagian level 3 no parent -->
                    <!-- form bagian level 2 no parent -->
                      @php
                        $lev2NoParent = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)
                                                                    ->where('level', 2)
                                                                    ->whereNull('parent_id')
                                                                    ->get();
                      @endphp
                      @foreach ($lev2NoParent as $item2NoParent)
                        @php
                          $lev3NoParent = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)
                                                                      ->where('level', 3)
                                                                      ->where('parent_id', $item2NoParent->id)
                                                                      ->get();
                        @endphp
                        <div class="card mb-4">
                            <h5 class="card-header">{{$item2NoParent->field}}</h5>
                            <div class="card-body">
                              <!-- form bagian level 3 -->
                              @foreach ($lev3NoParent as $item3NoParent)
                                <div class="form-group form-field">
                                    <label for="inp_{{$item3NoParent->id}}" class="font-weight-semibold">{{$item3NoParent->field}}</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control inp_{{$item3NoParent->id}}" name="inp_{{$item3NoParent->id}}"
                                        id="inp_{{$item3NoParent->id}}" data-formula="{{$item3NoParent->formula}}" @if ($item3NoParent->readonly) readonly @endif />
                                      @if ($item3NoParent->add_on)
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">{{$item3NoParent->add_on}}</span>
                                        </div>
                                      @endif
                                    </div>
                                </div>
                              @endforeach
                              <!-- end form bagian level 3 -->
                            </div>
                        </div>
                      @endforeach
                      <!-- end form bagian level 2 no parent -->
                    <!-- form bagian level 3 no parent -->
                    @php
                      $lev3NoParent = \App\Models\MstItemPerhitunganKredit::where('skema_kredit_limit_id', 1)
                                                                          ->where('level', 3)
                                                                          ->where('parent_id', 0)
                                                                          ->get();
                    @endphp
                    <div class="form-row">
                        @foreach ($lev3NoParent as $item3NoParent)
                          <div class="col-6">
                              <div class="form-group form-field">
                                  <label for="inp_{{$item3NoParent->id}}" class="font-weight-semibold">{{$item3NoParent->field}}</label>
                                  <input type="text" class="form-control inp_{{$item3NoParent->id}}" name="inp_{{$item3NoParent->id}}"
                                      id="inp_{{$item3NoParent->id}}" data-formula="{{$item3NoParent->formula}}"
                                      @if ($item3NoParent->readonly) readonly @endif />
                              </div>
                          </div>
                        @endforeach
                    </div>
                    <!-- end form bagian level 3 no parent -->
                {{--  </div>  --}}
              </form>
              <!-- end form -->
          </div>
          <!-- button wrapper -->
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                  Batal
              </button>
              <button type="button" class="btn btn-danger">
                  Simpan
              </button>
          </div>
      </div>
  </div>
</div>

<script>
  var selectDate = $(".select-date");
  selectDate.select2();
  $(".btn-add-2").on("click", function(e) {
      var allId = [];
      $('#detail-item input').each(function() {
        var id = $(this).attr('id')
        allId.push(id)
      })
      var content = `<tr>`
      $.each(allId, function(i, item) {
        content += `<td>
          <input
                  class="form-control"
                  type="text"
                  name="${item}"
                  id="${item}"
                  data-formula=""
                  onkeyup="calcForm()"
          />
          </td>`
      })
      content += `<td>
                              <button
                                  class="btn-minus btn btn-danger"
                                  type="button"
                              >
                                  -
                              </button>
                          </td>
                      </tr>`

      $("#table_item tbody").append(content);
      calcForm()
  });

  $("#table_item").on("click", ".btn-minus", function() {
      $(this).closest("tr").remove();
  });

  $("#form-perhitungan .form-control").keyup(function() {
      var id = $(this).attr('id');
      calcForm()
  })
</script>
<style>
  .modal-lg {
    max-width: 90% !important;
  }
</style>