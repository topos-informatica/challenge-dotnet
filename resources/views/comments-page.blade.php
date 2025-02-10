<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários Moderados</title>
</head>
<style>
    .place {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.place h2 {
    margin-bottom: 10px;
}

.comments {
    list-style-type: none;
    padding: 0;
}

.comments li {
    background-color: #f9f9f9;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
}

.comment-reason {
    font-size: 0.9em;
    color: #888;
}

.no-comments {
    font-style: italic;
    color: #555;
}

</style>    
<body>
    <h1>Comentários Moderados</h1>
    
    @foreach ($places as $place)
    <div class="place">
        <h2>{{ $place['name'] }}</h2> <!-- Exibe o nome do lugar -->

        @if (isset($place['Comments']) && count($place['Comments']) > 0)
            <ul class="comments">
                @foreach ($place['Comments'] as $comment)
                    <li>
                        <strong>{{ $comment['message'] }}</strong>
                        <span class="comment-reason">
                            <!-- Exibe a razão da toxicidade -->
                            @if (isset($comment['reason']) && $comment['reason'])
                                (Motivo: {{ $comment['reason'] }})
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="no-comments">Não há comentários para este local.</p>
        @endif
    </div>
    @endforeach


</body>
</html>