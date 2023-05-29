@extends("admin.main")

@section('title', 'Новый товар')

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
                <li class="breadcrumb-item">Редактирование товара</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card" id="id_content">
            
            <form action="{{ route('shopItem.update', $shopItem['id']) }}" method="POST" id="formEdit" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-2">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#main" data-bs-toggle="tab" role="tab">
                                <i class="la la-home " title="Основные"></i>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#images" data-bs-toggle="tab" role="tab">Изображения</a></li>
                        <li class="nav-item"><a class="nav-link" href="#description" data-bs-toggle="tab" role="tab">Описание</a></li>
                        <li class="nav-item"><a class="nav-link" href="#seo" data-bs-toggle="tab" role="tab">SEO</a></li>
                    </ul>
                </div>

                <div class="card-primary">
                    <div class="card-body tab-content">

                        <div class="tab-pane active" id="main">

                            <div  class="mb-3">
                                <label class="mb-1">Название товара</label>
                                <input id="name" value="{{ $shopItem->name }}" type="text" name="name" class="form-control form-control-lg" placeholder="Название товара" data-min="1"  data-max="255" data-required="1">
                                <div id="name_error" class="fieldcheck-error"></div>
                            </div>

                            <div  class="mb-3">
                                <label class="mb-1">Группа</label>
                                <input type="text" value="{{ $shopItem->shop_group_id }}" name="shop_group_id" class="form-control" placeholder="Группа">
                            </div>

                            <div class="row mb-3">
                                <div class="col-3">
                                    <label class="mb-1">Сортировка</label>
                                    <input type="text" value="{{ $shopItem->sorting }}" name="sorting" class="form-control" placeholder="Сортировка">
                                </div>
                                <div class="col-3">
                                    <label class="mb-1">Артикул</label>
                                    <input type="text" value="{{ $shopItem->marking }}" name="marking" class="form-control" placeholder="Артикул">
                                </div>
                                <div class="col-3">
                                    <label class="mb-1">Путь</label>
                                    <input type="text" name="path" value="{{ $shopItem->path }}" class="form-control" placeholder="Путь" data-min="2"  data-max="255" data-required="1">
                                </div>
                                <div class="col-3 d-flex align-items-end">

                                    <div class="d-flex">

                                        <div class="form-check field-check-center">
                                            <div>
                                                    
                                                @if ($shopItem['active'] == 1)
                                                    <input class="form-check-input" name="active" type="checkbox" id="active" checked="">
                                                @else
                                                    <input class="form-check-input" name="active" type="checkbox" id="active" >
                                                @endif
                                                <label for="active">
                                                    Активность
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-check field-check-center mx-3">
                                            <div>
                                                    
                                                @if ($shopItem['indexing'] == 1)
                                                    <input class="form-check-input" name="indexing" type="checkbox" id="indexing" checked="">
                                                @else
                                                    <input class="form-check-input" name="indexing" type="checkbox" id="indexing" >
                                                @endif
                                                <label for="indexing">
                                                    Индексирование
                                                </label>
                                            </div>
                                        </div>  

                                    </div>
                                </div>
                            
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">

                                    <div class="card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Цена</h3>
                                        </div>
                                        <div class="card-body" style="display: block;">
                                            
                                            <div class="row form-group">
                    
                                                <div class="col-lg-2">
                                                    <input type="text" value="{{ $shopItem->price }}" name="price" class="form-control" placeholder="Цена" >
                                                </div>
                                                <div class="col-lg-2">
                                                    @if ($currencies)
                                                        <select name="shop_currency_id" class="form-select">
                                                            @foreach ($currencies as $currency)
                                                                @if ($currency->id == $shopItem->shop_currency_id)
                                                                    <option checked="checked" value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                                @else
                                                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option> 
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="tab-pane" id="images">

                            <div class="file-box-content mb-3 d-flex">

                                @foreach ($images as $k => $image)
                                    <div class="file-box d-flex align-items-center justify-content-center position-relative" id="admin_image_{{ $k }}">

                                        <a href="javascript:void(0)" onclick="if(confirm('Вы действительно хотите удалить изображение?')) {adminImage.remove({{ $k }})}">
                                            <i class="las la-times file-close-icon"></i>
                                        </a>

                                        <div class="text-center">
                                            <div class="file-box-image">
                                                @if (!empty($image['image_small']))
                                                    <img src="{{ $image['image_small'] }}">
                                                @elseif (!empty($image['image_large']))
                                                    <img src="{{ $image['image_large'] }}">
                                                @endif
                                            </div>
                                        </div>                                                        
                                    </div>
                                @endforeach
                                             
                            </div>

                            <div class="new-images">
                                <div class="card image-box">
                                    <div class="card-body">
                                        <div class="preview-box" id="input-file-preview-box"></div>
                                        
                                        <label class="btn-upload btn btn-primary mt-1">Выбрать файл
                                            <input type="file" id="input-file" name="image[]" accept="image/*" onchange="{handleChange($(this).attr('id'))}" hidden="">    
                                        </label> 
                                        <button type="button" class="btn-upload btn btn-warning mt-1" onclick="adminImage.copy($(this))"><i class="la la-plus"></i></button>        
                                        <button type="button" class="btn-upload btn btn-danger mt-1 delete-image" onclick="adminImage.delete($(this))"><i class="la la-minus"></i></button>                    
                                    </div>
                                </div>
                            </div>



                        </div>

                        <div class="tab-pane" id="description">

                            <div class="mb-3">
                                <label class="mb-1">Краткое описание товара</label>
                                <textarea type="text" name="description" class="form-control editor" placeholder="Описание группы">{{ $shopItem->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="mb-1">Описание товара</label>
                                <textarea type="text" name="text" class="form-control editor" placeholder="Описание группы">{{ $shopItem->text }}</textarea>
                            </div>

                        </div>

                        <div class="tab-pane" id="seo">
                            <div class="mb-3">
                                <label class="mb-1">Заголовок [Seo Title]</label>
                                <input type="text" name="seo_title" value="{{ $shopItem->seo_title }}" class="form-control" placeholder="Заголовок страницы [Seo title]">
                            </div>

                            <div class="mb-3">
                                <label class="mb-1">Описание [Seo Description]</label>
                                <textarea name="seo_description" class="form-control" placeholder="Описание страницы [Seo description]">{{ $shopItem->seo_description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="mb-1">Ключевые слова [Seo Keywords]</label>
                                <input type="text" name="seo_keywords" value="{{ $shopItem->seo_keywords }}" class="form-control" placeholder="Ключевые слова [Seo Keywords]">
                            </div>
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

<script src="/assets/image.js"></script>
<script src="/assets/pages/file-upload.init.js"></script>

    
@endsection