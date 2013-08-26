<?php

return '@extends(\'models.master\')

@section(\'body\')
<div class="row-fluid">
	<div class="span12 widget">
        <div class="widget-header">
            <span class="title">' . ucfirst($model) . '</span>
        </div>
        <div class="widget-content table-container">
            <table id="demo-dtable-02" class="table table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Title</th>
                        <th>Tools</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($'.Str::plural(strtolower($model)).' as $'.strtolower($model).')
                    <tr>
                        <td width="20px">{{ $'.strtolower($model).'->id }}</td>
                        <td>{{ $'.strtolower($model).'->title }}</td>
                        <td class="action-col" width="10%">
                            <span class="btn-group">
                                <a href="{{ URL::to(\'model/'.ucfirst($model).'/\' . $'.strtolower($model).'->id) }}" class="btn btn-small"><i class="icon-search"></i></a>
		                        <a href="{{ URL::to(\'model/'.ucfirst($model).'/\' . $'.strtolower($model).'->id . \'/edit\') }}" class="btn btn-small"><i class="icon-pencil"></i></a>
                                <a href="{{ URL::to(\'model/'.ucfirst($model).'/\' . $'.strtolower($model).'->id . \'/delete\') }}" class="btn btn-small"><i class="icon-trash"></i></a>
                            </span>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>id</th>
                        <th>Title</th>
                        <th>Tools</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@stop';