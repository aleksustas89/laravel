@extends("admin.main")

@section('title', 'Свойства товаров интернет-Магазин')

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
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 1%">#ID</th>
                            <th>Название</th>
                            <th>Тэг</th>
                            <th>Тип</th>
                            <th class="controll-td"></th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($properties as $property)
                        <tr>
                            <td>
                                {{ $property->id }}
                            </td>
                            <td>
                                {{ $property->name }}
                            </td>
                            <td>
                                {{ $property->tag_name }}
                            </td>
                            <td>
                                {{ $property->type }}
                            </td>

                            <td class="td-actions">
                                <a href="{{ route('shopItemProperty.edit', $property->id) }}" class="mr-2"><i class="las la-pen text-secondary font-16"></i></a>
                                <form action="{{ route('shopItemProperty.destroy', $property->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="if(confirm('Вы действительно хотите удалить?')){$(this).parents('form').submit()}"class="td-list-delete-btn">
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