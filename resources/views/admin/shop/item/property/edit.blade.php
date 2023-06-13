@extends("admin.main")

@section('title', 'Редактирование свойства')

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
                <li class="breadcrumb-item">Редактирование свойства</li>
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

    @if (session('error'))
        <div class="alert alert-danger border-0" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <link href="/assets/plugins/select/selectr.min.css" rel="stylesheet" type="text/css" />
    <script src="/assets/plugins/select/selectr.min.js"></script>
    <script src="/assets/js/pages/shopItemProperty.js"></script>


    <div class="row">
        <div class="col-lg-12">

            <div class="card" id="id_content">
    
                <form action="{{ route('shopItemProperty.update', $property->id) }} " method="POST" id="formEdit" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                        <div class="p-2">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#main" data-bs-toggle="tab" role="tab">
                                        <i class="la la-home " title="Основные"></i>
                                    </a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#groups" data-bs-toggle="tab" role="tab">Доступность в группах</a></li>
                            </ul>
                        </div>
    
                        <div class="card-primary">
                            <div class="card-body tab-content">

                                <div class="tab-pane active" id="main">

                                    <div class="mb-3">
                                        <label class="mb-1">Название свойства</label>
                                        <input id="name" value="{{ $property->name }}" type="text" name="name" class="form-control form-control-lg" placeholder="Название свойства" data-min="1"  data-max="255" data-required="1">
                                        <div id="name_error" class="fieldcheck-error"></div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <label class="mb-1">Тип</label>
                                            <select id="shop_item_property_type" name="type">
                                                @foreach ($types as $k => $type)
                                                    @if ($property->type == $k)
                                                        <option selected="selected" value="{{ $k }}">{{ $type }}</option>
                                                    @else 
                                                        <option value="{{ $k }}">{{ $type }}</option>
                                                    @endif
                                                    
                                                @endforeach
                                            </select> 
                                            <script>new Selectr('[name="type"]');</script>
                                        </div>
                                        <div class="col-4">

                                            @php
                                                $listVisible = $property->type != 4 ? true : false
                                            @endphp

                                            <div @class(['shop-property-lists', 'hidden' => $listVisible])>
                                                <label class="mb-1">Списки</label>
                                                <select name="shop_item_list_id">
                                                    <option value="">...</option>
                                                    @foreach ($lists as $k => $list)
                                                        
                                                        @if ($property->type == 4 && $property->shop_item_list_id == $list->id)
                                                            <option selected="selected" value="{{ $list->id }}">{{ $list->name }}</option>
                                                        @else 
                                                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                                                        @endif
        
                                                    @endforeach
                                                </select> 
                                                <script>new Selectr('[name="shop_item_list_id"]');</script>
                                            </div>
                                        </div>
        
                                    </div>

                                    @php
                                        $multipleVisible = $property->type == 3 ? true : false
                                    @endphp
                                    <div @class(['mb-3', 'hidden' => $multipleVisible]) id="row_multiple">
                                        <div class="form-check form-switch form-switch-purple">
                                            @php
                                                $checked = $property->multiple == 1 ? 'checked=""' : ''
                                            @endphp
                                            <input class="form-check-input" type="checkbox" name="multiple" id="multiple" {{ $checked }}>
                                            <label class="form-check-label" for="multiple">Множественный</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="mb-1">Сортировка</label>
                                        <input type="text" value="{{ $property->sorting }}" name="sorting" class="form-control" placeholder="Сортировка">
                                    </div>

                                </div>

                                <div class="tab-pane" id="groups">
                                    
                                    @foreach ($groups as $group)

                                        <div class="col-3 d-flex align-items-end">

                                            <div class="d-flex">
                                                <div class="form-check field-check-center">
                                                    <div>
                                                        @php
                                                            $checked = in_array($group->id, $properties_for_groups) ? 'checked="checked"' : ''
                                                        @endphp
                                                        <input {{ $checked }} class="form-check-input" name="property_for_group[]" type="checkbox" id="property_for_group_{{ $group->id }}" value="{{ $group->id }}" >
                                                        <label for="property_for_group_{{ $group->id }}">
                                                            [{{ $group->id }}] {{ $group->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                        </div>

                                    @endforeach

                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" name="save" value="0" class="btn btn-primary">Сохранить</button>
                                <button type="submit" name="apply" value="1" class="btn btn-success">Применить</button>
                            </div>
                        </div>
                    
                </form>

            </div>
        </div>
    </div>

    
@endsection