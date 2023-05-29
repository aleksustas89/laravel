<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
    </head>
    <body>

        <h1>Кабинет пользователя</h1> 

        <a href="{{ route('logout') }}" class="nav-link delete-btn" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                Выход
            </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        
    </body>
</html>