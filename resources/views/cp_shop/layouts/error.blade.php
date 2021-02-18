@if ($errors->has($input))
<span class="help-block" dir="{{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
        <strong style="color: red;">{{ $errors->first($input) }}</strong>
</span>
@endif
