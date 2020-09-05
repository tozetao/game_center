@php
    use App\Services\Flash;
@endphp
@if(session()->has(Flash::SUCCESS))
    <div class="adm-alert adm-alert-success">
        {{ session()->get(Flash::SUCCESS) }}
    </div>
@elseif(session()->has(Flash::FAILED))
    <div class="adm-alert adm-alert-warning">
        {{ session()->get(Flash::FAILED) }}
    </div>
@endif