<?php

return '@extends(\'models.master\')

@section(\'body\')
<div class="row-fluid">
	<div class="span12 widget">
        <div class="widget-header">
            <span class="title"><i class="icon-resize-horizontal"></i> ' . ucfirst($model) . ' form</span>
        </div>
        <div class="widget-content form-container">
            
            <form action="{{ URL::action(\''.ucfirst($model).'Controller@store\') }}" method="POST" enctype="multipart/form-data" id="mainForm">

                <div class="control-group">
                    <label class="control-label" for="Title">Title</label>
                    <div class="controls">
                        <input type="text" id="input00" name="'.ucfirst($model).'[title]" class="span12" value="{{ $'.strtolower($model).'->title }}">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save changes</button>
                    <button type="reset" class="btn">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>
@stop

@section(\'scripts\')
<script type="text/javascript">

    $(document).ready(function()
    {
        var html = new HtmlHandler($("#mainForm"));
        var control_unit = new ControlUnit( [ \'validate\', \'save_in_db\' ], html, {{ $'.strtolower($model).'->id ? $'.strtolower($model).'->id : 0 }} );

        control_unit.requestUrls( \'{{ URL::route("request-urls", "' . ucfirst($model) . '") }}\' );
        html.addListener( control_unit );
    });

</script>
@stop';