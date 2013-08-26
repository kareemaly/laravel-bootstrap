<form action="{{ URL::route('contact-us') }}" method="POST" id="messageForm" class="forms">

    @if(! $authUser)
    <div class="c_row"><input type="text" class="txt" name="User[email]" value="{{ Input::old('User.email') }}" id="message_email"><span>Email* </span></div>
    <div class="c_row"><input type="text" class="txt" name="User[website]" value="{{ Input::old('User.website', 'http://') }}" id="message_website"><span>Website </span></div>
    @endif

    <div class="c_row"><textarea name="Contact[message]" class="txtarea" id="message_body">{{ Input::old('Contact.message') }}</textarea></div>
    <input type="submit" class="sbmt" value="Send Message">
</form>