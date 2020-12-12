<div style="max-width: 600px; margin: auto;">
    <p>{{ $user->name }} さん</p>
    
    <p>mymemoriesにご登録いただきありがとうございます。</p>
    
    <p>下記の登録者情報のご確認をお願いします</p>
    <ul>
        <li>名前: {{ $user->name }}</li>
        <li>ニックネーム: {{ $user->nickname }}</li>
        <li>メールアドレス: {{ $user->email }}</li>
        <li>パスワード: {{ $user->password }}</li>
    </ul>
</div>