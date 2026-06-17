<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cenotos | Login</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #081120;
            --panel: rgba(10, 18, 32, 0.82);
            --line: rgba(148, 163, 184, 0.16);
            --text: #e5eefb;
            --muted: #8ea2bf;
            --accent: #4f8cff;
            --accent-2: #7af0d5;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(79, 140, 255, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(122, 240, 213, 0.16), transparent 28%),
                linear-gradient(160deg, #050a14 0%, #0b1730 52%, #09111d 100%);
        }
        .card {
            width: min(92vw, 440px);
            padding: 2rem;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: var(--panel);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(14px);
        }
        h1 { margin: 0 0 .5rem; font-size: 1.9rem; }
        p { margin: 0 0 1.5rem; color: var(--muted); line-height: 1.5; }
        label { display:block; margin-bottom: .4rem; font-size: .92rem; color: #d7e3f3; }
        input {
            width: 100%;
            margin-bottom: 1rem;
            padding: .95rem 1rem;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,.03);
            color: var(--text);
            outline: none;
        }
        input:focus { border-color: rgba(79, 140, 255, .7); box-shadow: 0 0 0 4px rgba(79, 140, 255, .14); }
        button {
            width: 100%;
            padding: 1rem;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #03101f;
            font-weight: 700;
            cursor: pointer;
        }
        .error {
            margin: 0 0 1rem;
            padding: .85rem 1rem;
            border-radius: 12px;
            background: rgba(248, 113, 113, .12);
            color: #ffd9d9;
            border: 1px solid rgba(248, 113, 113, .2);
        }
        .hint { margin-top: 1rem; font-size: .9rem; color: var(--muted); }
    </style>
</head>
<body>
    <main class="card">
        <h1>Cenotos</h1>
        <p>Entre com a conta central e o sistema carrega automaticamente a empresa associada e a respetiva base de dados de vendas.</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" autocomplete="current-password" required>

            <button type="submit">Entrar</button>
        </form>

        <div class="hint">Base central: <strong>cenotos_admin</strong>. Tenant ativa: base por empresa.</div>
    </main>
</body>
</html>
