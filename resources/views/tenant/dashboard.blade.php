<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cenotos | Dashboard</title>
    <style>
        :root {
            --bg: #07111f;
            --panel: rgba(11, 20, 36, 0.88);
            --line: rgba(148, 163, 184, 0.18);
            --text: #edf4ff;
            --muted: #8ea2bf;
            --accent: #61dafb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, rgba(97, 218, 251, 0.16), transparent 28%),
                radial-gradient(circle at bottom left, rgba(79, 140, 255, 0.16), transparent 28%),
                linear-gradient(180deg, #050912, #0a1222 54%, #07111f);
        }
        .wrap { width: min(1120px, calc(100vw - 2rem)); margin: 0 auto; padding: 2rem 0 3rem; }
        .hero, .panel {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(14px);
        }
        .hero { padding: 2rem; margin-bottom: 1.5rem; display:flex; justify-content: space-between; gap: 1rem; align-items: end; }
        h1 { margin: 0 0 .5rem; font-size: clamp(1.8rem, 3vw, 3rem); }
        .muted { color: var(--muted); }
        .stats { display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat, .panel { padding: 1.25rem; }
        .stat { border-radius: 20px; border: 1px solid var(--line); background: rgba(255,255,255,.03); }
        .stat strong { display:block; font-size: 1.7rem; margin-top: .35rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: .9rem 0; text-align: left; border-bottom: 1px solid rgba(148, 163, 184, .12); }
        .badge { display:inline-flex; padding: .25rem .6rem; border-radius: 999px; background: rgba(97, 218, 251, .12); color: var(--accent); font-size: .85rem; }
        .topline { display:flex; justify-content: space-between; gap: 1rem; align-items: center; margin-bottom: 1rem; }
        .button {
            display:inline-flex; align-items:center; justify-content:center;
            padding: .75rem 1rem; border-radius: 14px; border: 1px solid var(--line);
            color: var(--text); text-decoration: none;
            background: rgba(255,255,255,.04);
        }
        form { margin: 0; }
        .pagination { margin-top: 1rem; }
        @media (max-width: 800px) {
            .hero, .topline { flex-direction: column; align-items: start; }
            .stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <section class="hero">
            <div>
                <span class="badge">Tenant ativo</span>
                <h1>{{ $tenant->displayName() }}</h1>
                <p class="muted">Base em uso: <strong>{{ $tenant->getInternal('db_name') ?? 'n/a' }}</strong></p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="button" type="submit">Sair</button>
            </form>
        </section>

        <section class="stats">
            <div class="stat">
                <span class="muted">Vendas</span>
                <strong>{{ $salesTotal }}</strong>
            </div>
            <div class="stat">
                <span class="muted">Volume</span>
                <strong>{{ number_format($salesAmount, 2, ',', '.') }}</strong>
            </div>
            <div class="stat">
                <span class="muted">Cache</span>
                <strong>{{ $cacheInfo['store'] }}</strong>
            </div>
        </section>

        <section class="panel">
            <div class="topline">
                <div>
                    <h2 style="margin:0;">Últimas vendas</h2>
                    <p class="muted" style="margin:.25rem 0 0;">Dados cacheados por tenant e por página. A primeira leitura vem do MySQL; as seguintes, do Redis.</p>
                </div>
                <div>
                    <span class="badge">page {{ $cacheInfo['page'] }}</span>
                    <span class="badge">key {{ $cacheInfo['key'] }}</span>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Referência</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>{{ $sale->reference }}</td>
                            <td>{{ $sale->customer_name }}</td>
                            <td>{{ number_format((float) $sale->amount, 2, ',', '.') }}</td>
                            <td>{{ $sale->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">Sem vendas registadas nesta empresa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $sales->links() }}
            </div>
        </section>
    </div>
</body>
</html>
