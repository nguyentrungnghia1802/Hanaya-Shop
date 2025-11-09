<?php

return [
    // 認証
    'email' => 'メールアドレス',
    'password' => 'パスワード',
    'remember_me' => 'ログイン状態を保持',
    'forgot_password' => 'パスワードをお忘れですか？',
    'log_in' => 'ログイン',
    'register' => '登録',
    'dont_have_account' => 'アカウントをお持ちでないですか？',
    'already_have_account' => 'すでにアカウントをお持ちですか？',
    'sign_up' => '今すぐ登録',
    'confirm_password' => 'パスワードの確認',
    'name' => '名前',
    'verify_email' => 'メールアドレスの確認',
    'reset_password' => 'パスワードをリセット',
    'send_reset_link' => 'パスワードリセットリンクを送信',
    'back_to_login' => 'ログインに戻る',
    'current_password' => '現在のパスワード',
    'new_password' => '新しいパスワード',
    'logout' => 'ログアウト',

    // メッセージ
    'thanks_for_signing_up' => 'ご登録ありがとうございます！ご利用を開始する前に、先ほどお送りしたリンクをクリックしてメールアドレスを確認してください。メールが届かない場合は、再送いたしますのでご連絡ください。',
    'verification_link_sent' => '新しい確認リンクが、登録時にご提供いただいたメールアドレスに送信されました。',
    'resend_verification' => '確認メールを再送',
    'forgot_password_text' => 'パスワードをお忘れですか？ご心配なく。メールアドレスをお知らせいただければ、新しいパスワードを設定できるリセットリンクをお送りします。',
    'secure_area_confirmation' => 'このアプリケーションの安全なエリアです。続行する前にパスワードを確認してください。',
    'confirm' => '確認',

    // バリデーション
    'failed' => 'これらの認証情報は当社の記録と一致しません。',
    'password_incorrect' => '入力されたパスワードが正しくありません。',
    'throttle' => 'ログイン試行が多すぎます。:seconds秒後に再度お試しください。',

    // プロフィール編集
    'delete_account' => 'アカウントを削除',
    'delete_account_confirmation' => 'アカウントを削除すると、すべてのリソースとデータが完全に削除されます。削除前に必要なデータをダウンロードしてください。',
    'are_you_sure_you_want_to_delete_your_account' => '本当にアカウントを削除しますか？',
    'cancel' => 'キャンセル',
    'update_password' => 'パスワードを更新',
    'update_password_description' => 'アカウントの安全のため、長くてランダムなパスワードを使用してください。',
    'saved' => '保存しました',
    'save' => '保存',
    'profile_information' => 'プロフィール情報',
    'update_profile_information_description' => 'アカウントのプロフィール情報とメールアドレスを更新します。',
    'your_email_address_is_unverified' => 'メールアドレスが未確認です。',
    'click_here_to_resend_verification_email' => '確認メールを再送するにはこちらをクリックしてください。',
    'verification_link_sent_notification' => '新しい確認リンクがあなたのメールアドレスに送信されました。',
    'profile' => 'プロフィール',
    
    // 新しいメール確認システム
    'verify_email_title' => 'メールアドレスを確認してください',
    'verify_email_sent' => '確認リンクを以下のメールアドレスに送信いたしました：',
    'important' => '重要',
    'verify_email_instructions' => 'メール（迷惑メールフォルダも含む）をご確認いただき、確認リンクをクリックして登録を完了してください。',
    'resend_verification_email' => '確認メールを再送信',
    'register_different_email' => '別のメールで登録する',
    'verification_link_resent' => '確認リンクが正常に送信されました！',
    
    // メール確認成功
    'verification_success_title' => 'メール確認が完了しました！',
    'verification_success_message' => 'メールアドレスが確認され、アカウントが有効になりました。',
    'welcome' => 'ようこそ',
    'account_ready' => 'アカウントの準備が完了しました。',
    'go_to_dashboard' => 'ダッシュボードへ',
    'continue_shopping' => 'お買い物を続ける',
    'verification_completed_at' => '確認完了時刻：',
    
    // メールテンプレート
    'verify_email_subject' => 'メールアドレスの確認 - Hanaya Shop',
    'email_greeting' => 'Hanaya Shopにご登録いただき、ありがとうございます！',
    'email_verification_instruction' => '下のボタンをクリックしてメールアドレスを確認し、アカウント設定を完了してください：',
    'verify_email_button' => 'メールアドレスを確認',
    'verification_link_expires' => 'この確認リンクは24時間で有効期限が切れます。',
    'email_manual_copy' => 'ボタンが機能しない場合は、このリンクをコピーしてブラウザに貼り付けてください：',
    'email_not_requested' => 'このアカウントをリクエストしていない場合は、このメールを無視してください。',
    'email_footer' => ':shopをお選びいただき、ありがとうございます',
    
    // サポート連絡
    'need_help' => '認証でお困りですか？',
    'contact_support' => 'サポートに連絡',
    'support_email_subject' => 'メール認証のヘルプリクエスト',
    'support_email_body' => 'Hanaya Shopサポート様

アカウントのメール認証について支援が必要です。

認証が必要なメールアドレス: :email

この問題の解決をお手伝いください。

ありがとうございます！',
    
    // Gmail要件
    'create_account' => 'アカウント作成',
    'create_account_description' => 'Hanaya Shopに参加して、ショッピングの旅を始めましょう',
    'gmail_requirement_title' => '📧 Gmailアカウントが必要です',
    'gmail_requirement_description' => '最高のエクスペリエンスのため、有効なGmailアドレスをご使用ください：',
    'gmail_for_order_updates' => '注文状況の更新と通知を受信',
    'gmail_for_password_recovery' => '安全なパスワード回復とアカウントアクセス',
    // 'gmail_for_account_security' => 'アカウントセキュリティと認証の強化',
    'gmail_required_note' => '通知とセキュリティのためGmailアドレスが必要です',
    
    // エラーメッセージ
    'invalid_verification_token' => '無効または期限切れの確認トークンです。',
    'verification_token_expired' => '確認トークンの有効期限が切れました。再度登録してください。',

    // Placeholders
    'email_placeholder' => 'メールアドレスを入力してください',
    'password_placeholder' => 'パスワードを入力してください',
    'name_placeholder' => '氏名を入力してください',
    'confirm_password_placeholder' => 'もう一度パスワードを入力してください',

    //Notes
    'notice' => '注意',
    'password_requirement_title' => 'パスワード要件',
    'password_requirement_description' => 'パスワードは8文字以上で、大文字・小文字・数字・記号を含み、スペースを含まない必要があります。',

    // パスワードリセットメール
    'reset_password_subject' => 'パスワードリセット通知',
    'reset_password_greeting' => 'こんにちは！',
    'reset_password_line' => 'お客様のアカウントに対してパスワードリセットリクエストを受信したため、このメールを送信しています。',
    'reset_password_action' => 'パスワードリセット',
    'reset_password_expire' => 'このパスワードリセットリンクは:count分で有効期限が切れます。',
    'reset_password_no_action' => 'パスワードリセットを要求していない場合、特に対応は不要です。',
    'reset_password_regards' => '敬具、',
    'reset_password_signature' => 'Hanaya Shop',
    'reset_password_trouble' => '「パスワードリセット」ボタンをクリックできない場合は、以下のURLをコピーしてWebブラウザに貼り付けてください：',

    // テストアカウント体験
    'test_account_button' => 'テストアカウントで体験',
    'test_account_experience_title' => '登録前にウェブサイトを体験してください',
    'test_account_experience_description' => '以下のテストアカウントを使用して、新しいアカウントを作成せずにすべての機能をご体験いただけます',
    'test_account_free_note' => '全て完全無料です。お気軽にご利用ください！',
    'test_account_password_note' => '全てのテストアカウントは同じパスワードです：:password',
];
