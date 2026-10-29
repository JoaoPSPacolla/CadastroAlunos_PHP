<?php
declare(strict_types=1);

$arquivoDados = __DIR__ . '/alunos.json';
$erros = [];
$sucesso = '';

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

$dados = [
    'nome' => '',
    'ra' => '',
    'sexo' => '',
    'idade' => '',
    'endereco' => '',
    'telefone' => '',
    'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valor) {
        $dados[$campo] = trim((string)($_POST[$campo] ?? ''));
    }

    foreach ($dados as $campo => $valor) {
        if ($valor === '') {
            $erros[] = 'Preencha todos os campos do formulário.';
            break;
        }
    }

    if ($dados['email'] !== '' && !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um endereço de e-mail válido.';
    }

    if ($dados['idade'] !== '' && (!ctype_digit($dados['idade']) || (int)$dados['idade'] < 1 || (int)$dados['idade'] > 120)) {
        $erros[] = 'A idade deve ser um número inteiro entre 1 e 120.';
    }

    if ($dados['sexo'] !== '' && !in_array($dados['sexo'], ['Feminino', 'Masculino', 'Outro', 'Prefiro não informar'], true)) {
        $erros[] = 'Selecione uma opção válida para o sexo.';
    }

    if (!$erros) {
        $alunos = carregarAlunos($arquivoDados);

        foreach ($alunos as $aluno) {
            if (isset($aluno['ra']) && strcasecmp((string)$aluno['ra'], $dados['ra']) === 0) {
                $erros[] = 'Já existe um aluno cadastrado com esse RA.';
                break;
            }
        }

        if (!$erros) {
            $alunos[] = $dados;
            $json = json_encode($alunos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            if ($json !== false && file_put_contents($arquivoDados, $json . PHP_EOL, LOCK_EX) !== false) {
                $sucesso = 'Aluno cadastrado com sucesso!';
                foreach ($dados as $campo => $valor) {
                    $dados[$campo] = '';
                }
            } else {
                $erros[] = 'Não foi possível salvar os dados. Verifique as permissões do arquivo.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de alunos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <aside class="menu-lateral">
        <a class="marca" href="index.php">Portal de Alunos</a>
        <nav aria-label="Menu principal">
            <a class="link-menu ativo" href="index.php" aria-current="page">Cadastrar aluno</a>
            <a class="link-menu" href="listar.php">Listar alunos</a>
        </nav>
    </aside>

    <main class="conteudo">
        <header class="cabecalho">
            <p class="sobretitulo">GERENCIAMENTO ACADÊMICO</p>
            <h1>Cadastro de alunos</h1>
            <p>Preencha os campos abaixo para adicionar um aluno à base de dados.</p>
        </header>

        <?php if ($sucesso !== ''): ?>
            <p class="mensagem sucesso" role="status"><?= e($sucesso) ?></p>
        <?php endif; ?>

        <?php if ($erros): ?>
            <div class="mensagem erro" role="alert">
                <ul>
                    <?php foreach (array_unique($erros) as $erro): ?>
                        <li><?= e($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="painel">
            <form method="post" action="index.php">
                <div class="grade-formulario">
                    <div class="campo campo-largo">
                        <label for="nome">Nome completo</label>
                        <input id="nome" name="nome" type="text" maxlength="150" autocomplete="name" value="<?= e($dados['nome']) ?>" required>
                    </div>

                    <div class="campo">
                        <label for="ra">RA</label>
                        <input id="ra" name="ra" type="text" maxlength="30" value="<?= e($dados['ra']) ?>" required>
                    </div>

                    <div class="campo">
                        <label for="sexo">Sexo</label>
                        <select id="sexo" name="sexo" required>
                            <option value="">Selecione</option>
                            <?php foreach (['Feminino', 'Masculino', 'Outro', 'Prefiro não informar'] as $opcao): ?>
                                <option value="<?= e($opcao) ?>" <?= $dados['sexo'] === $opcao ? 'selected' : '' ?>><?= e($opcao) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="idade">Idade</label>
                        <input id="idade" name="idade" type="number" min="1" max="120" value="<?= e($dados['idade']) ?>" required>
                    </div>

                    <div class="campo campo-largo">
                        <label for="endereco">Endereço</label>
                        <input id="endereco" name="endereco" type="text" maxlength="200" autocomplete="street-address" value="<?= e($dados['endereco']) ?>" required>
                    </div>

                    <div class="campo">
                        <label for="telefone">Telefone</label>
                        <input id="telefone" name="telefone" type="tel" maxlength="30" autocomplete="tel" placeholder="(00) 00000-0000" value="<?= e($dados['telefone']) ?>" required>
                    </div>

                    <div class="campo campo-largo">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" maxlength="254" autocomplete="email" value="<?= e($dados['email']) ?>" required>
                    </div>
                </div>

                <div class="acoes">
                    <button class="botao" type="submit">Cadastrar aluno</button>
                    <a class="botao botao-secundario" href="listar.php">Ver alunos cadastrados</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
