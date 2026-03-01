<div class="flex flex-col items-center justify-center space-y-4">
    <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
        {!! auth()->user()->twoFactorAuth->toQr() !!}
    </div>
    
    <div class="text-center">
        <p class="text-sm font-medium text-gray-900 dark:text-white">
            {{ __('Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)') }}
        </p>
        <p class="text-xs text-gray-500 mt-1">
            {{ __('Or enter this code manually:') }} <span class="font-mono font-bold text-primary-600">{{ auth()->user()->twoFactorAuth->shared_secret }}</span>
        </p>
    </div>
</div>
