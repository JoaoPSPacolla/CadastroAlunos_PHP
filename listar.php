<?php
declare(strict_types=1);

$arquivoDados = __DIR__ . '/alunos.json';

function carregarAlunos(string $arquivo): array
{
    if (!is_file($arquivo)) {
        return [];
    }

    $conteudo = file_get_contents($arquivo);
    if ($conteudo === false || trim($conteudo) === '') {
        return [];
    }

    $alunos = json_decode($conteudo, true);
    return is_array($alunos) ? $alunos : [];
}

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$alunos = carregarAlunos($arquivoDados);

usort($alunos, static function (array $a, array $b): int {
    $raA = (string)($a['ra'] ?? '');
    $raB = (string)($b['ra'] ?? '');

    if (ctype_digit($raA) && ctype_digit($raB)) {
        return (int)$raA <=> (int)$raB;
    }

    return strnatcasecmp($raA, $raB);
});
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alunos cadastrados</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <aside class="menu-lateral">
        <a class="marca" href="index.php">Portal de Alunos</a>
        <nav aria-label="Menu principal">
            <a class="link-menu" href="index.php">Cadastrar aluno</a>
            <a class="link-menu ativo" href="listar.php" aria-current="page">Listar alunos</a>
        </nav>
    </aside>

    <main class="conteudo">
        <header class="cabecalho">
            <p class="sobretitulo">GERENCIAMENTO ACADÊMICO</p>
            <h1>Alunos cadastrados</h1>
            <p>Os registros são exibidos em ordem crescente de RA.</p>
        </header>

        <section class="painel">
            <div class="resumo-listagem">
                <h2>Base de alunos</h2>
                <span class="contador"><?= count($alunos) ?> <?= count($alunos) === 1 ? 'aluno' : 'alunos' ?></span>
            </div>

            <?php if (!$alunos): ?>
                <div class="estado-vazio">
                    <h3>Nenhum aluno cadastrado</h3>
                    <p>Use a página de cadastro para adicionar o primeiro aluno.</p>
                    <a class="botao" href="index.php">Cadastrar aluno</a>
                </div>
            <?php else: ?>
                <div class="tabela-responsiva">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">RA</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Sexo</th>
                                <th scope="col">Idade</th>
                                <th scope="col">Endereço</th>
                                <th scope="col">Telefone</th>
                                <th scope="col">E-mail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos as $aluno): ?>
                                <tr>
                                    <td><?= e((string)($aluno['ra'] ?? '')) ?></td>
                                    <td><?= e((string)($aluno['nome'] ?? '')) ?></td>
                                    <td><?= e((string)($aluno['sexo'] ?? '')) ?></td>
                                    <td><?= e((string)($aluno['idade'] ?? '')) ?></td>
                                    <td><?= e((string)($aluno['endereco'] ?? '')) ?></td>
                                    <td><?= e((string)($aluno['telefone'] ?? '')) ?></td>
                                    <td><?= e((string)($aluno['email'] ?? '')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
