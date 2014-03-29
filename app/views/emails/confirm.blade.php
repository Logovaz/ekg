<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <div>
      [% Lang::get('reminders.confirmation', array('link' => $link, 'code' => $code, 'user_id' => $user_id)) %]
    </div>
    <div>
      [% Lang::get('reminders.verification') %]
    </div>
  </body>
</html>