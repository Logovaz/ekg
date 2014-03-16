<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <div>
      [% Lang::get('reminders.confirmation', array('link' => $link, 'code' => $code)) %]
    </div>
    <div>
      [% Lang::get('reminders.verification') %]
    </div>
  </body>
</html>