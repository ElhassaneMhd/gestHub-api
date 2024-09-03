<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Email Subject</title>
  <style>
    /* Reset styles */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    /* Container */
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      border-radius: 5px;
    }

    /* Header and Footer */
    header, footer {
      background-color: #f0f0f0;
      padding: 10px;
      text-align: center;
    }

    /* Content Area */
    .content {
      background-color: #fff;
      padding: 20px;
    }

    /* Buttons and Links */
    a {
      color: #007bff;
      text-decoration: none;
    }
    button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <img src="your-logo.png" alt="Your Logo">
  </header>
  <div class="container">
    <h2>{{ $data['subject'] }}</h2>
        <p>{!! $data['message'] !!}</p>
        @if (isset($data['pdfPath']) && $data['pdfPath'])
            <a href="https://gesthub.netlify.app/assets/{{ $data['pdfPath'] }}" target="_blank">Show Document</a>
        @else
            <a href="https://gesthub.netlify.app" target="_blank">Visit our website</a>
        @endif
    <button>Click here to do something</button>
  </div>
  <footer>
    Copyright © GestHub
  </footer>
</body>
</html>
