<html>
<head>
  <title>404 Not Found</title>
</head>
<body>
  <h1>Not Found</h1>
  <p>The requested URL <?php echo Dispatcher::getStatus('requested_url'); ?> was not found on this server.</p>
  <hr/>
  <address>KISS 'Keep It Simple, Stupid'</address>
</body>
</html>

<!--
   - Unfortunately, Microsoft has added a clever new 'feature' to Internet Explorer. 
   - If the text of an error's message is 'too small', specifically less than 512 bytes, 
   - Internet Explorer returns its own error message. You can turn that off, but it's 
   - pretty tricky to find switch called 'smart error messages'. That means, of course,
   - that short error messages are censored by default.
   - 
   - The workaround is pretty simple: pad the error message with a big comment like this 
   - to push it over the five hundred and twelve bytes minimum. Of course, that's exactly 
   - what you're reading right now.
   -->