@if(! empty($success))
<div class="success">
    <div class="close">×</div>
    <ul>
        @foreach($success as $successMessage)
        <li>{{ $successMessage }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(! empty($errors))
<div class="errors">
    <div class="close">×</div>
    <ul>
        @foreach($errors as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="clr"></div>