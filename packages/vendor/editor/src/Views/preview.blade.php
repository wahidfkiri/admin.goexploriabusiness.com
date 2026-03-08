<!Doctype>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$template->name}}</title>
        <style>
            {!! $template->css_content !!}
        </style>
        {!! $template->assets_data !!}
        {!! $template->assets_folder !!}
    </head>
    <body>
        {!! $template->html_content !!}

        <script>
            // Pour tous les liens avec href commençant par #
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const targetId = this.getAttribute('href');
    if (targetId === '#') return;
    
    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      // Défilement doux
      targetElement.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
      
      // Optionnel : mettre à jour l'URL sans rechargement
      history.pushState(null, null, targetId);
    }
  });
});
        </script>
    </body>
</html>