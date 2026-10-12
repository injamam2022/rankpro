<p>Need help with tests, payments, login or mentorship? Reach the RankPro team on the channels below.</p>
<ul>
    @if(!empty($whatsapp))
        <li>WhatsApp: <a href="{{ $wa }}" target="_blank" rel="noopener noreferrer">{{ $whatsapp }}</a></li>
    @endif
    @if(!empty($phone))
        <li>Call Us: <a href="{{ $tel }}">{{ $phone }}</a></li>
    @endif
    @if(!empty($email))
        <li>Email: <a href="mailto:{{ $email }}">{{ $email }}</a></li>
    @endif
</ul>
<p>You can also <a href="{{ route('contact') }}">send a message</a> or read the <a href="{{ route('index') }}#faq">FAQs</a> on the home page.</p>
