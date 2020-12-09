@component('mail::message')
# Activation Email
登録が完了しました。
名前：{{ $user->name }}
パスワード：{{ $user->password }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent