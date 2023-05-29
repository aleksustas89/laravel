@extends("admin.main")

@section('title', 'Структура сайта')

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
                    <a href="{{ $create }}" class="btn btn-success">Добавить</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1%">#ID</th>
                                    <th>Название структуры</th>
                                    <th>Путь</th>
                                    <th width="40px">
                                        <i data-feather="eye" title="Активность"></i>
                                    </th>
                                    <th width="40px">
                                        <i data-feather="search"></i>
                                    </th>
                                    <th class="controll-td"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($structures as $structure)
                                <tr>
                                    <td>
                                        {{ $structure['id'] }}
                                    </td>
                                    <td>
                                        <div>
                                            <a href="?parent_id={{ $structure['id'] }}">{{ $structure['name'] }}</a>
                                            @if ($structure['subCount'] > 0)
                                                <span class="badge badge-secondary admin-badge mx-1">{{ $structure['subCount'] }}</span>
                                            @endif
    
                                        </div>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $structure['url'] }}">{{ $structure['url'] }}</a>
                                    </td>
                                    <td>
                                        @if ($structure['active'] == 1)
                                            <i data-feather="eye" title="Активность"></i>
                                        @else
                                            <i data-feather="eye-off" title="Активность"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($structure['indexing'] == 1)
                                            <i data-feather="search" title="Индексация"></i>
                                        @else
                                            <i data-feather="search" title="Индексация"></i>
                                        @endif
                                    </td>
                        
                                    <td class="td-actions">
                                        <a href="{{ route('structure.edit', $structure['id']) }}" class="mr-2"><i class="las la-pen text-secondary font-16"></i></a>
                                        <form action="{{ route('structure.destroy', $structure['id']) }}" method="POST" class="d-inline">
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
    </div>
    
@endsection