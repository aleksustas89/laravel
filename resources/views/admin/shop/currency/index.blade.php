@extends("admin.main")

@section('title', 'Валюты')

@section('breadcrumbs')

<div class="page-title-box d-flex flex-column">
    <div class="float-start">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
            @if (!empty($breadcrumb["url"]))
                <li class="breadcrumb-item"><a href="{{ $breadcrumb["url"] }}">{{ $breadcrumb["name"] }}</a></li>
            @else 
                <li class="breadcrumb-item">{{ $breadcrumb["name"] }}</li>
            @endif
        @endforeach
        </ol>
    </div>
</div>

@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success border-0" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header">
                    <a href="{{ route('shopCurrency.create') }}" class="btn btn-primary">Добавить</a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 1%">#ID</th>
                                <th style="width: 40px" class="px-0 text-center">Код</th>
                                <th>Название</th>
                                <th>Курс</th>
                                <th width="40px" class="px-0 text-center">Базовая</th>
                                <th class="controll-td"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($currencies as $currency)

                            <tr>
                                <td>
                                    {{ $currency->id }}
                                </td>

                                <td class="px-0 text-center">
                                    {{ $currency->code }}
                                </td>

                                <td>
                                    
                                    {{ $currency->name }}
                                </td>
                    
                                <td>
                                    {{ $currency->exchange_rate }}
                                </td>
                                <td class="px-0 text-center">
                                    @if ($currency->default == 1)
                                        <i data-feather="check" title="Базовая"></i>
                                    @else
                                        <i class="fa fa-lightbulb-o fa-inactive" title="Базовая"></i>
                                    @endif
                                </td>

                                <td class="td-actions">
                                    <a href="{{ route('shopCurrency.edit', $currency->id) }}" class="mr-2"><i class="las la-pen text-secondary font-16"></i></a>
                                    <form action="{{ route('shopCurrency.destroy', $currency->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="if(confirm('Вы действительно хотите удалить?')){$(this).parents('form').submit()}" class="td-list-delete-btn">
                                            <i class="las la-trash-alt text-secondary font-16"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    
@endsection