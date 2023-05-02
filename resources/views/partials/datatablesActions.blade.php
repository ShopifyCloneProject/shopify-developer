@can($viewGate)
    <a  href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        {{ trans('global.view') }}
    </a>
    
@endcan
@can($editGate)
    <a  href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        <i data-feather='edit'></i>
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" value="{{ trans('global.delete') }}">
    </form>
@endcan