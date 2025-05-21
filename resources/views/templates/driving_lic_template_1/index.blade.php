{{-- resources/views/templates/medical_template_1/index.blade.php --}}

<!DOCTYPE html>
<html>
<head>
  <title>{{ $content['site_title'] ?? 'My Clinic' }}</title>
  <link rel="stylesheet" href="{{ asset('css/templates/medical_template_1/style.css') }}">
  <style>
    :root {
      --primary-color: {{ $color ?? '#0066ff' }};
    }
  </style>
</head>
<body>
  <header>
    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" />
    <h1>{{ $content['hero_title'] ?? 'Welcome!' }}</h1>
  </header>

  <section>
    {{ $content['about'] ?? 'About us goes here' }}
  </section>

  <script src="{{ asset('js/templates/medical_template_1/script.js') }}"></script>
</body>
</html>
