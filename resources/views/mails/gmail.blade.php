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
      text-align: center;
    }
    /* Header and Footer */
    header, footer {
      background-color: #f0f0f0bf;
      padding: 10px;
      text-align: center;
    }

    /* Content Area */
    .content {
      background-color: #fff;
      padding: 20px;
    }

    .logo {
      display: block;
      margin: 0 auto;
    }
    /* Buttons and Links */
    a {
      color: #ffffff;
      text-decoration: none;
      cursor: pointer;
    }
    button {
      background-color:#7566d8;
      color: #fff;
      border-radius: 0%;
      padding: 10px 20px;
      border: none
    }

    @media (max-width: 500px) {
      .logo {
        max-width: 50%;
      }
    }
    @media (max-width: 2000px) {
      .logo {
        max-width: 25%;
      }
    }

  </style>
</head>
<body>
    <header>
        <img class="logo" src="{{ asset('appLogo/logo.png') }}" alt="Your Logo">
    </header>
    <div class="container">
        <h2>{{ $data['subject'] }}</h2>
        <hr/>
        <p>{!! $data['message'] !!}</p>
        <div class="container">
            @if (isset($data['pdfPath']) && $data['pdfPath'])
            <a href="https://gesthub.netlify.app/assets/{{ $data['pdfPath'] }}" target="_blank">
                <button>Show Document</button>
            </a>
            @else
            <a href="https://gesthub.netlify.app" target="_blank">
                <button>Visit our website</button>
            </a>
            @endif
        </div>
  </div>
  <footer> Copyright © GestHub </footer>
</body>
</html>
