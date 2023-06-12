@extends("admin.main")

@section('title', 'Интернет-Магазин')

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
                    <a href="{{ $createItem }}" class="btn btn-success"><i class="fas fa-plus icon-separator"></i>Добавить товар</a>
                    <a href="{{ $createGroup }}" class="btn btn-primary"><i class="fas fa-plus icon-separator"></i>Добавить группу</a> 
                    <a href="{{ route('shopCurrency.index') }}" class="btn btn-info"><i class="fas fa-dollar-sign icon-separator"></i>Валюты</a>

                    <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-cogs icon-separator"></i>Настройки</button>
                    <div class="dropdown-menu" style="">
                        <a class="dropdown-item" href="{{ route('shop.edit', 1) }}">Настройки магазина</a>
                        <a class="dropdown-item" href="{{ route('shopItemProperty.index') }}">Свойства товаров</a>
                        <a class="dropdown-item" href="{{ route('shopItemList.index') }}">Списки</a>
                    </div>

                </div>

   
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1%">#ID</th>
                                    <th style="width: 40px"  class="px-0 text-center"><i class="fa fa-bars" title="—"></i></th>
                                    <th>Название</th>
                                    <th>Путь</th>
                                    <th width="40px">
                                    <i class="fa fa-lightbulb-o" title="Активность"></i>
                                    </th>
                                    <th class="controll-td"></th>
                                </tr>
                            </thead>
                            <tbody>
    
                            @foreach ($shop_groups as $shop_group)
                                <tr>
                                    <td>
                                        {{ $shop_group['id'] }}
                                    </td>
                                    <td class="px-0 text-center"><i data-feather="folder"></i></td>
                                    <td>
                                        <div>
                                            <a href="?parent_id={{ $shop_group['id'] }}">{{ $shop_group['name'] }}</a>
                                            @if ($shop_group['subCount'] > 0)
                                                <span class="badge badge-secondary admin-badge mx-1">{{ $shop_group['subCount'] }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $shop_group['url'] }}">{{ $shop_group['url'] }}</a>
                                    </td>
                                    <td>
                                        @if ($shop_group['active'] == 1)
                                            <i class="fa fa-lightbulb-o" title="Активность"></i>
                                        @else
                                            <i class="fa fa-lightbulb-o fa-inactive" title="Активность"></i>
                                        @endif
                                    </td>

                                    <td class="td-actions">
                                        <a href="{{ route('shopGroup.edit', $shop_group['id']) }}" class="mr-2"><i class="las la-pen text-secondary font-16"></i></a>
                                        <form action="{{ route('shopGroup.destroy', $shop_group['id']) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="if(confirm('Вы действительно хотите удалить?')){$(this).parents('form').submit()}"class="td-list-delete-btn">
                                                <i class="las la-trash-alt text-secondary font-16"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
    
                            @foreach ($shop_items as $shop_item)
                                <tr>
                                    <td>
                                        {{ $shop_item['id'] }}
                                    </td>

                                    <td class="px-0 text-center">
                                        @if (!empty($shop_item['image_small']))
                                            <img src="{{ $shop_item['image_small'] }}" alt="" height="40">
                                        @else
                                            <i class="la la-image " title=""></i> 
                                        @endif
                                    </td>

                                    <td>
                                        <p class="d-inline-block align-middle mb-0">
                                            <a class="d-inline-block align-middle mb-0 product-name fw-semibold">{{ $shop_item['name'] }}</a> 
                                            <br>
                                            <span class="text-muted font-13 fw-semibold">{{ $shop_item['marking'] }} </span> 
                                        </p>
                                    </td>
                       
                                    <td>
                                        <a target="_blank" href="{{ $shop_item['url'] }}">{{ $shop_item['url'] }}</a>
                                    </td>
                                    <td>
                                        @if ($shop_item['active'] == 1)
                                            <i class="fa fa-lightbulb-o" title="Активность"></i>
                                        @else
                                            <i class="fa fa-lightbulb-o fa-inactive" title="Активность"></i>
                                        @endif
                                    </td>

                                    <td class="td-actions">
                                        <a href="{{ route('shopItem.edit', $shop_item['id']) }}" class="mr-2"><i class="las la-pen text-secondary font-16"></i></a>
                                        <form action="{{ route('shopItem.destroy', $shop_item['id']) }}" method="POST" style="display:inline-block;">
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