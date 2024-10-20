<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de contatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>

    <div class="container text-center">
        <h1 class="welcome">Bem-vindo ao Sistema de Agenda</h1>
        <h2 class="welcome">Gerencie seus contatos</h2>
        
        <div class="cards-container">
            <!-- Card de Cadastro de Agendas -->
            <div class="card shadow-lg" style="width: 25rem;">
                <div class="card-body">
                    <h5 class="card-title">Cadastro de Contatos</h5>
                    <p class="card-text">Gerencie e organize as seus contatos de forma fácil e prática.</p>
                    <a href="/contatos" class="btn btn-primary btn-lg">Acessar</a>
                </div>
            </div>

            <!-- Card de Cadastro de Pessoas -->
            <div class="card shadow-lg" style="width: 25rem;">
                <div class="card-body">
                    <h5 class="card-title">Cadastro de Pessoas</h5>
                    <p class="card-text">Cadastre e mantenha o controle de todas as pessoas da agenda.</p>
                    <a href="/pessoas" class="btn btn-success btn-lg">Acessar</a>
                </div>
            </div>
        </div>
        <h6 class="welcome">Sistema desenvolvido por Pierri Alexander Vidmar - Teste Backend Magazord</h6>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
