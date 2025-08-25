<?php
// 1. Validation
$required = ['name','section','cc_type','cc_number'];
$errors = [];
foreach ($required as $field) {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $errors[] = $field;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Buy Your Way to a Better Education!</title>
    <link href="buyagrade.css" type="text/css" rel="stylesheet" />
  </head>
  <body>
<?php if (!empty($errors)): ?>
    <!-- 2. Error display -->
    <h1>Error: Missing Information</h1>
    <p>Please go back and fill in <strong>all</strong> fields before submitting.</p>
<?php else: ?>
    <?php
    // 3. Append to suckers.html
    $line = $_POST['name']
          . ';' . $_POST['section']
          . ';' . $_POST['cc_number']
          . ';' . strtolower($_POST['cc_type'])
          . "\n";
    file_put_contents('suckers.html', $line, FILE_APPEND | LOCK_EX);

    // 4. Read back entire file
    file_put_contents('suckers.html', $line, FILE_APPEND | LOCK_EX);
    ?>
    <h1>Thanks, sucker!</h1>
    <p>Your info has been saved. Here’s everyone who’s bought their way to a 4.0:</p>
    <pre><?= htmlspecialchars($all) ?></pre>
<?php endif; ?>
    <dl>
      <dt>Name</dt>
      <dd><?= htmlspecialchars($_POST['name']) ?></dd>

      <dt>Section</dt>
      <dd><?= htmlspecialchars($_POST['section']) ?></dd>

      <dt>Credit Card</dt>
      <dd>
        <?= htmlspecialchars($_POST['cc_type']) ?>
        — <?= htmlspecialchars($_POST['cc_number']) ?>
      </dd>
    </dl>
  </body>
</html>
