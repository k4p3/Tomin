<x-mail::message>
# {{ __('Hello!') }}

{{ __('You have been invited to collaborate on the shared wallet:') }} **{{ $walletName }}**

{{ __('By joining this wallet, you will be able to record expenses, view balances and participate in shared financial planning.') }}

<x-mail::button :url="$url">
{{ __('Join Wallet') }}
</x-mail::button>

{{ __('This invitation will expire in 7 days.') }}

{{ __('If you did not expect this invitation, you can safely ignore this email.') }}

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>
