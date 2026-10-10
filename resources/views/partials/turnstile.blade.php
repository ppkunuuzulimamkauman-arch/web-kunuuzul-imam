{{-- Widget Cloudflare Turnstile — hanya tampil bila key sudah diisi di .env --}}
@if(\App\Services\Turnstile::enabled())
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<div class="cf-turnstile" data-sitekey="{{ \App\Services\Turnstile::siteKey() }}"></div>
@error('captcha')<p style="color:#dc2626;font-size:13px;font-weight:700;margin-top:6px">{{ $message }}</p>@enderror
@endif
{{-- Honeypot anti-bot: manusia tidak melihat/mengisi field ini --}}
<input type="text" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;opacity:0;height:0;width:0;">
