<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>
                Blog Posts
            </title>    
    </head>
<body> 
    <div card style="margin: 40px; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; background:linear-gradient(135deg, rgba(92, 119, 255, 0.2), rgba(232, 65, 245, 0.2)); -webkit-backdrop-filter: blur(12px); backdrop-filter: blur(12px);">           
        <h1>Blog Posts</h1>
    </div>
    
        <div card style="margin: 40px; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: center; background:linear-gradient(135deg, rgba(92, 119, 255, 0.2), rgba(232, 65, 245, 0.2)); -webkit-backdrop-filter: blur(12px); backdrop-filter: blur(12px);">          
            @foreach ($posts as $post)
                <li>
                    <h2>{{ $post->title }}</h2>
                    <p>{{ $post->content }}</p>
                </li>
            @endforeach
        </div>
    
</body>
</html>