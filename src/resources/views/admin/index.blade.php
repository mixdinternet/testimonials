@extends('mixdinternet/admix::index')

@section('title')
    Listagem de {{ strtolower(config('mtestimonials.name', 'Depoimentos')) }}
@endsection

@section('btn-insert')
    @if((!checkRule('admin.testimonials.create')) && (!$trash))
        @include('mixdinternet/admix::partials.actions.btn.insert', ['route' => route('admin.testimonials.create')])
    @endif
    @if((!checkRule('admin.testimonials.trash')) && (!$trash))
        @include('mixdinternet/admix::partials.actions.btn.trash', ['route' => route('admin.testimonials.trash')])
    @endif
    @if($trash)
        @include('mixdinternet/admix::partials.actions.btn.list', ['route' => route('admin.testimonials.index')])
    @endif
@endsection

@section('btn-delete-all')
    @if((!checkRule('admin.testimonials.destroy')) && (!$trash))
        @include('mixdinternet/admix::partials.actions.btn.delete-all', ['route' => route('admin.testimonials.destroy')])
    @endif
@endsection

@section('search')
    {!! Form::model($search, ['route' => ($trash) ? 'admin.testimonials.trash' : 'admin.testimonials.index', 'method' => 'get', 'id' => 'form-search'
        , 'class' => '']) !!}
    <div class="row">
        <div class="col-md-4">
            {!! BootForm::select('status', 'Status', ['' => '-', 'active' => 'Ativo', 'inactive' => 'Inativo'], null
                , ['class' => 'jq-select2']) !!}
        </div>
        <div class="col-md-4">
            {!! BootForm::select('star', 'Destaque', ['' => '-', '1' => 'Sim', '0' => 'Não'], null
                , ['class' => 'jq-select2']) !!}
        </div>
        <div class="col-md-4">
            {!! BootForm::text('name', 'Nome') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="{{ route(($trash) ? 'admin.testimonials.trash' : 'admin.testimonials.index') }}"
                   class="btn btn-default btn-flat">
                    <i class="fa fa-list"></i>
                    <i class="fs-normal hidden-xs">Mostrar tudo</i>
                </a>
                <button class="btn btn-success btn-flat">
                    <i class="fa fa-search"></i>
                    <i class="fs-normal hidden-xs">Buscar</i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('table')
    @if (count($testimonials) > 0)
        <table class="table table-striped table-hover table-action jq-table-rocket">
            <thead>
            <tr>
                @if((!checkRule('admin.testimonials.destroy')) && (!$trash))
                    <th>
                        <div class="checkbox checkbox-flat">
                            <input type="checkbox" id="checkbox-all">
                            <label for="checkbox-all">
                            </label>
                        </div>
                    </th>
                @endif
                <th>{!! columnSort('#', ['field' => 'id', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Nome', ['field' => 'name', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Destaque', ['field' => 'star', 'sort' => 'asc']) !!}</th>
                <th>{!! columnSort('Status', ['field' => 'status', 'sort' => 'asc']) !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($testimonials as $testimonial)
                <tr>
                    @if((!checkRule('admin.testimonials.destroy')) && (!$trash))
                        <td>
                            @include('mixdinternet/admix::partials.actions.checkbox', ['row' => $testimonial])
                        </td>
                    @endif
                    <td>{{ $testimonial->id }}</td>
                    <td>{{ $testimonial->name }}</td>
                    <td>@include('mixdinternet/admix::partials.label.yes-no', ['yesNo' => $testimonial->star])</td>
                    <td>@include('mixdinternet/admix::partials.label.status', ['status' => $testimonial->status])</td>
                    <td>
                        @if((!checkRule('admin.testimonials.edit')) && (!$trash))
                            @include('mixdinternet/admix::partials.actions.btn.edit', ['route' => route('admin.testimonials.edit', $testimonial->id)])
                        @endif
                        @if((!checkRule('admin.testimonials.destroy')) && (!$trash))
                            @include('mixdinternet/admix::partials.actions.btn.delete', ['route' => route('admin.testimonials.destroy'), 'id' => $testimonial->id])
                        @endif
                        @if($trash)
                            @include('mixdinternet/admix::partials.actions.btn.restore', ['route' => route('admin.testimonials.restore', $testimonial->id)])
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        @include('mixdinternet/admix::partials.nothing-found')
    @endif
@endsection

@section('pagination')
    {!! $testimonials->appends(request()->except(['page']))->render() !!}
@endsection

@section('pagination-showing')
    @include('mixdinternet/admix::partials.pagination-showing', ['model' => $testimonials])
@endsection